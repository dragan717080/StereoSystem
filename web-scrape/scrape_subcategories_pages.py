from selenium_config import *
from selenium.common.exceptions import \
    NoSuchElementException, \
    ElementClickInterceptedException, \
    ElementNotInteractableException, \
    StaleElementReferenceException
from selenium.webdriver.remote.webelement import WebElement
from time import sleep
from log_config import log
from typing import List, Dict, Any, Optional
from Utils import Utils
import re

"""
Returns:
    data_dict: updated data_dict
"""
def scrape_subcategory_banner_images() -> Dict[str, Any]:
    for category in data_dict:
        for subcategory_index, subcategory in enumerate(data_dict[category]):
            link = subcategory['link']
            driver.get(link)
            sleep(0.45)
            # Get parent element of banner image

            # Subcategories with no banner image contain only ads and not products, thus can be deleted
            subcategories_to_delete = []

            banner_image_element_parent = get_element_by_class_name('category-cms')
            if not banner_image_element_parent:
                log.warning(f"Subcategory {subcategory['title']} with link {subcategory['link']} has no banner image")
                # Cannot delete while iterating so will delete later
                subcategories_to_delete.append(subcategory['link'])
                continue

            banner_image_element = banner_image_element_parent.find_elements(By.TAG_NAME, 'div')[0]
            background_images = banner_image_element.get_attribute('data-background-images')
            if not background_images:
                log.warning(f"Subcategory {subcategory['title']} with link {subcategory['link']} has no banner image")
                subcategories_to_delete.append(subcategory['link'])
                continue

            try:
                banner_image = background_images.split('"')[3][:-1]
            except Exception as e:
                log.warning(f"Couldn't find background image for category {category} with link {subcategory['link']}")
            data_dict[category][subcategory_index]['banner_img'] = banner_image

    # Delete subcategories that have no banner image since they only contains ads
    for category in data_dict:
        for subcategory_index, subcategory in enumerate(data_dict[category]):
            if 'banner_img' not in subcategory.keys():
                del data_dict[category][subcategory_index]

    Utils.write_data_dict_to_json_file(data_dict)

    # Visual indicator that scraping was completed
    log.info('Scraping subcategory banner images completed')

"""
Gets brand name for product by comparing it with all brand names using difflib
e.g. product with name Cambridge Hi-Fi will have its brand name Cambridge Audio

Args:
    product_name: str

Returns:
    brand_name: str
"""
def get_brand_name_for_product(product_name: str) -> str:
    # Read brand names from json file 'brands.json' and then compare product name to get its corresponding brand
    all_brand_names = [brand['name'] for brand in Utils.read_json_file('brands.json')]

    # First word of product name usually indicates its brand
    product_brand_name = product_name.split()[0]

    # At first try to find direct match
    brand_name = Utils.find_closest_match(product_brand_name, all_brand_names)

    # If it fails, compare first word of product name with first word of each brand name
    if not brand_name:
        all_brand_names = [brand.split()[0] for brand in all_brand_names]
    
    brand_name = Utils.find_closest_match(product_brand_name, all_brand_names)

    return brand_name

"""
Formats reviews of product
Strips new lines (ascii value 10) and turns it into a list of strings
"""
def format_product_reviews(reviews: str) -> List[str]:
    cleaned_reviews = re.sub(r'\s+', ' ', reviews).strip()

    # Split by char • and then filter out empty strings from reviews, also remove extra whitespaces
    cleaned_reviews = [part.strip() for part in cleaned_reviews.split(chr(8226)) if part]

    return cleaned_reviews

"""
Get product price

Args:
    product_element: WebElement - its children elements will be accessed
    product_name: str - Used for formatting error messages (if any),
    link: str - Used for formatting error messages (if any)

Returns:
    price_dict: Dict[str, Any] - dict that either contains one key (price),
    or two keys vip_price and regular_member_price
"""

def get_product_price(product_element: WebElement, product_name: str, link: str) -> Dict[str, Any]:
    # Some products don't have set price but are rather of kind 'Call to set price',
    # For them fixed price will be set (initiated as str but will be converted to int in Utils function)
    try:
        price = product_element.find_element(By.CLASS_NAME, 'price').get_attribute('innerText')
    except NoSuchElementException:
        price = '1500'

        product_name = product_element.find_element(By.CLASS_NAME, 'product-item-name').get_attribute('innerText')
    # Some products have special discount for VIP members and different class names
    if price == '':
        vip_price_parent_element = get_element_by_class_name('vip-price__wrapper')
        # For those elements label will be 'VIP Club member price'
        vip_price = vip_price_parent_element.find_elements(By.CLASS_NAME, 'price')[1].get_attribute('innerText')
        regular_member_parent_element = get_element_by_class_name('non-vip-price')
        regular_member_price_element = regular_member_parent_element.find_element(By.CLASS_NAME, 'price')
        if not regular_member_price_element:
            log.warning("Regular member price does not exist for product '{}' on link '{}'".format(product_name, link))
        regular_member_price = regular_member_parent_element.get_attribute('innerText')

        return {
            'vip_price': vip_price,
            'regular_member_price': regular_member_price,
        }

    try:
        price = Utils.str_to_int(price)
        return {
            'price': price,
        }
    except ValueError as e:
        log.error("Error for name {}, on link {}, price value '{}', error: {}".format(product_name, link, price, e))


"""
Scrapes a single product and returns it

Args:
    product_element: WebElement - parent web element that contains
        web elements with info of product

Returns:
    product: dict
"""
def scrape_product(product_element: WebElement, link: str) -> Optional[dict]:
    product_name = product_element.find_element(By.CLASS_NAME, 'product-item-name').get_attribute('innerText').strip()

    if not product_name:
        return

    product = {
        'name': product_name,
        'image_photo': product_element.find_element(By.CLASS_NAME, 'product-image-photo').get_attribute('src'),
        'brand': get_brand_name_for_product(product_name),
    }

    # Return value from 'get_product_price' function is a dict with either one or two keys,
    # update existing product with it
    product.update(**get_product_price(product_element, product_name, link))

    # Some products have no reviews
    try:
        reviews = product_element.find_element(By.CLASS_NAME, 'product-promotion-text').get_attribute('innerText').strip()
        reviews = format_product_reviews(reviews)
        product.update({
            'reviews': reviews,
        })
    except NoSuchElementException as e:
        pass

    # Some products have no previous price
    try:
        prev_price = Utils.str_to_int(
            product_element.find_element(By.CSS_SELECTOR, '[data-price-type="previousPrice"]').get_attribute('innerText')
        )

        product.update({
            'prev_price': prev_price,
        })
    except NoSuchElementException as e:
        pass

    return product

"""
Scrapes products from all pages
"""
def scrape_products_from_pages() -> None:
    for category in data_dict:
        for subcategory_index, subcategory in enumerate(data_dict[category]):
            for detailed_category_index, detailed_category in enumerate(subcategory['detailed_categories']):
                driver.get(detailed_category['link'])
                sleep(3)
                Utils.allow_cookies()

                # Initialize products for given detailed category
                detailed_category_products = []
                while True:
                    # Scrape products from current page in pagination then go to next page
                    product_elements = get_elements_by_class_name(('product-item-info'))
                    [detailed_category_products.append(scrape_product(product_element, detailed_category['link'])) for product_element in product_elements]
                    pages_items_element = get_element_by_class_name('pages-items')
                    # Detailed categories page has no pagination
                    if not pages_items_element:
                        break
                    # If next button isn't visible it has no more product subpages
                    try:
                        next_button_element = pages_items_element.find_element(By.CLASS_NAME, 'pages-items-next')
                    except NoSuchElementException:
                        break

                    next_button_element.click()

                    product_elements = get_elements_by_class_name(('product-item-info'))

                # Filter out None values that are added to detailed_category_products from function 'scrape_product'
                detailed_category_products = [product for product in detailed_category_products if product]

                # Update data_dict by adding products to detailed category
                data_dict[category][subcategory_index]['detailed_categories'][detailed_category_index]['products'] = detailed_category_products

    # Write updated data_dict with added products to json file
    Utils.write_to_json_file(data_dict, 'categories.json')

"""
Cleanup function
Opens product file, and finds missing brands
This is because website that was scraped from has some brands missing
and products that are of those brands

Updates the json file
"""
def find_missing_brands() -> List[str]:
    categories_dict = Utils.read_json_file('categories.json')

    for category in categories_dict:
        for specific_category in enumerate(data_dict[category]):
            for detailed_category in enumerate(specific_category['detailed_categories']):
                products = detailed_category['products']
                if products:
                    for product in enumerate(products):
                        if not product['brand']:
                            if product['name'].split()[0] == 'Hoody':
                                product['brand'] = 'Habitech'
                            else:
                                log.warning(f"Product {product['name']} has no brand")

    # Update json file
    Utils.write_to_json_file(data_dict, 'categories.json')

    log.info('Written missing brands to json file')

if __name__ == '__main__':
    # Open data_dict which contains incomplete list of all categories levels 1, 2, 3
    data_dict = Utils.read_json_file('categories.json')

    #scrape_subcategory_banner_images()
    #scrape_products_from_pages()
    #find_missing_brands()

    driver.quit()
