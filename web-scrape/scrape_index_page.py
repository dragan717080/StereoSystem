from selenium_config import *
from selenium.webdriver.remote.webelement import WebElement
import re
from time import sleep
from typing import List, Dict, Any
from log_config import log
from Utils import Utils

def get_element_inner_text(web_element: WebElement, has_html=True) -> str:
    inner_text = web_element.get_attribute('innerText').split('\n')[0]
    return inner_text.split('#')[0] if has_html else inner_text

# Returns category dict with name and an element that can be hovered over
def scrape_general_categories() -> List[Dict[str, Any]]:
    categories = [get_element_by_class_name(f'nav-{i}') for i in range(1, 15)]

    # Filter out None entries
    categories = [category for category in categories if category]

    all_categories = [{
        'name': get_element_inner_text(category),
        'element': category
    } for category in categories]

    return all_categories

# Filter out web elements that have class in 'nav-number-number' format e.g. nav-2-2
def find_nav_subtitles(subtitle_element):
    classes = subtitle_element.get_attribute('class').split(' ')
    return any(class_name.startswith('nav') for class_name in classes)

"""
When traversing through subcategories (specific categories - level 2),
build a specific category element dict

Args:
    specific_category_name: str
    specific_category_element: WebElement

Returns:
    specific_category_dict: Dict[str, Any]
"""
def get_specific_category_dict_from_element(
        specific_category_name: str,
        specific_category_element: WebElement
    ) -> Dict[str, Any]:

    return {
        'title': specific_category_name,
        'link': specific_category_element.find_elements(By.TAG_NAME, 'a')[0].get_attribute('href'),
        'image': specific_category_element.find_elements(By.CLASS_NAME, 'category-image')[0].get_attribute('src'),
        'detailed_categories': [],
    }

"""
Scrape different hierarchy subcategories

Some categories (like 'TVs & Accessories') has different structure so must be scraped differently
Get titles of level 2 elements (which are actually level 3 compared
to other subcategories), then move those titles one level up,
so levels 3 and 4 become levels 2 and 3

Args:
    specific_category_element: WebElement - parent web element
    data_dict: Dict[str, Any] - main dict with 3 levels of categories
    category_title: str - for which key in data_dict to update dict
Returns:
    data_dict: Dict[str, Any] - updated data_dict
"""
def scrape_different_hierarchy_subcategories(
        specific_category_element: WebElement,
        data_dict: Dict[str, Any],
        category_title: str,
        is_tv: bool
    ) -> Dict[str, Any]:

    # Don't move to other function, it's different from similar function on other place
    def get_subcategory_dict(lvl_3_element):
        return {
            'title': get_element_inner_text(lvl_3_element),
            'link': lvl_3_element.get_attribute('href')
        }

    detailed_category_elements = list(specific_category_element.find_elements(By.CLASS_NAME, 'level2'))
    # Parent element, it has title which is link then UL of detailed categories
    detailed_category_elements = [detailed_category for detailed_category in detailed_category_elements if re.search(r"nav-", detailed_category.get_attribute('class'))]
    
    for detailed_category_element in detailed_category_elements:
        subcategory_element = detailed_category_element.find_elements(By.TAG_NAME, 'a')[0]
        subcategory_dict = get_subcategory_dict(subcategory_element)
        all_detailed_categories = []

        detailed_categories_parent_element = detailed_category_element.find_element(By.TAG_NAME, 'ul')
        new_detailed_category_elements = detailed_categories_parent_element.find_elements(By.TAG_NAME, 'li')
        for new_detailed_category_element in new_detailed_category_elements:
            link_element = new_detailed_category_element.find_element(By.TAG_NAME, 'a')
            new_detailed_category_dict = get_subcategory_dict(link_element)
            all_detailed_categories.append(new_detailed_category_dict)

        data_dict[category_title].append({
            **subcategory_dict,
            'detailed_categories': all_detailed_categories
        })

    # to do: remove is_tv bool
    return data_dict

"""
After scraping general category web elements, scrape their children - levels 2 and 3

Three levels: 
General category (or just category)
Specific category
Detailed category

Args:
    categories_dict: list of dict from scrape_general_categories function

Returns:
    data_dict: dict with 3 levels of categories
"""
# After scraping general category web elements, scrape their children - levels 2 and 3
def scrape_category_subtitles(categories_dict: List[Dict[str, Any]]) -> Dict[str, Any]:
    # Initiate data dict
    data_dict = { title['name']:[] for title in categories_dict }

    all_brand_names = []

    for category_element in categories_dict:
        element = category_element['element']

        specific_category_elements = element.find_elements(By.CLASS_NAME, 'level1')

        # Don't process web elements with no detailed categories
        if len(specific_category_elements) < 2:
            continue

        specific_category_elements = list(filter(find_nav_subtitles, specific_category_elements))

        for specific_category_element in specific_category_elements:
            subtitle_name = specific_category_element.find_elements(By.TAG_NAME, 'span')[1].get_attribute('innerText')
            # Don't put items with Shop by Brand to dict
            if subtitle_name == 'Shop by Brand':
                scrape_brand_names(specific_category_element, all_brand_names)
                continue

            # This subcategory has different structure so must be scraped differently
            subcategories_with_different_structure = ['TVs & Accessories', 'Projectors & Accessories', ]
            if subtitle_name in subcategories_with_different_structure:
                is_tv = subtitle_name == 'TVs & Accessories'
                scrape_different_hierarchy_subcategories(specific_category_element, data_dict, category_element['name'], is_tv)
                continue

            subtitle_dict = get_specific_category_dict_from_element(subtitle_name, specific_category_element)

            data_dict[category_element['name']].append(subtitle_dict)

            detailed_categories = list(specific_category_element.find_elements(By.CLASS_NAME, 'level2'))
            for detailed_category in detailed_categories:
                subtitle_dict['detailed_categories'].append({
                    'title': get_element_inner_text(detailed_category),
                    'link': detailed_category.find_elements(By.TAG_NAME, 'a')[0].get_attribute('href')
                })
        sleep(0.45)

    # For some reason some brands are not listed on webpage but used on products, need add them manually
    additional_brands = [
        'Tangent', 'Optoma', 'LG', 'Screenline', 'Sapphire', 'AudioQuest', 'Q Acoustics', 'Cello', 'Kinetik', 'Goldring', 'VAVA', 'Zuma', 'Mission', 'Vogels', 'Samsung', 'Emotiva', 'Philips', 'Peerless-AV', 'Hisense', 'HEOS', 'Bowers & Wilkins', 'Reavon', 'Soundstyle', 'MDA', 'Wharfedale', 'Eltax', 'AVIDHIFI', 'Klipsch', 'Biamp', 'Norstone', 'Teac', 'QED', 'Vividstorm', 'BenQ', 'Nedis', 'Mountson', 'Optimum', 'Starscape', 'Solidsteel', 'Roberts', 'MEE Audio', 'SVS', 'FiiO', 'Lindy', 'Cleer Audio', 'Apart', 'TTAP', 'Epson', 'CYP', 'Chord Electronics', 'Valencia', 'Steljes Audio', 'Acer', 'Musical Fidelity', 'SpeakerCraft', 'Dorel Home', 'Pure Theatre', 'Austrian Audio', 'Polk Audio', 'ISO Acoustics', 'Pioneer', 'Rotel', 'Pixel', 'Frank Olsen', 'Hi-Fi Racks', 'Future Automation', 'LOEWE', 'Wiim', 'Habitech', 'Chord', 'Ortofon', 'Sharp', 'Toshiba', 'Freesat', 'Polk', 'Jamo', 'Fyne', 'Kordz', 'Naturewall', 'ELAC', 'MEE Audio', 'SoundMAGIC', 'Audeze', 'iFi', 'Mitchell & Brown', 'We Are Rewind', 'Bluestream', 'BDI', 'JVC'
    ]

    all_brand_names.extend(additional_brands)

    # Write all brand names to json file
    all_brand_names = [{ 'name': brand_name } for brand_name in set(all_brand_names)]
    all_brand_names = Utils.sort_list_of_dict_alphabetically(all_brand_names, 'name')
    Utils.write_to_json_file(all_brand_names, 'brands.json')

    return data_dict

"""
Retrieve names of all brands and write them to json file

Args:
    subtitle_element: web element that contains list of spans with names of brands
    all_brand_names: list of brand names that will get updated with names from current subcategory
"""
def scrape_brand_names(subtitle_element: WebElement, all_brand_names: List[Dict[str, any]]) -> None:
    detailed_categories = list(subtitle_element.find_elements(By.CLASS_NAME, 'level2'))
    for subcategory in detailed_categories:
        all_brand_names.append(get_element_inner_text(subcategory))

def initiate_main_page_scrape() -> None:
    driver.get('https://www.richersounds.com/promotions/value-hi-fi-systems.html?product_list')

    # If allow cookies modal is present, close it
    Utils.allow_cookies()
    categories_dict = scrape_general_categories()

    data_dict = scrape_category_subtitles(categories_dict)
    Utils.write_data_dict_to_json_file(data_dict)

    log.info('Scraping main page completed')   

def scrape_main_page():
    #initiate_main_page_scrape()

    # Uncomment next line if plan to do something else on the website main page
    #sleep(500)
    driver.quit()

if __name__ == '__main__':
    scrape_main_page()
