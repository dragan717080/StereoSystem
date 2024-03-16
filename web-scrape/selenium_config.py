from selenium import webdriver
from selenium.webdriver.common.by import By
import selenium.common.exceptions as e
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.remote.remote_connection import LOGGER
from selenium.webdriver.remote.webelement import WebElement
from selenium.webdriver.chrome.service import Service
from typing import List, Optional
from log_config import log

def get_element_by_x_path(x_path: str) -> WebElement:
    try:
        selected_element = driver.find_element(By.XPATH, x_path)
    except e.NoSuchElementException:
        raise ValueError("Expected XPATH input")
    return selected_element

def get_element_if_exists_by_x_path(x_path) -> Optional[WebElement]:
    try:
        selected_element = driver.find_element(By.XPATH, x_path)
    except e.NoSuchElementException:
        log.warning("Element does not exist")
        return None
    return selected_element

def get_text_if_exists_by_x_path(x_path) -> str:
    try:
        selected_element = driver.find_element(By.XPATH, x_path)
    except e.NoSuchElementException:
        # Need to use Python 2 format syntax when formatting with single quotes around variables
        log.warning("Element with xpath '{}' does not exist".format(x_path))
        return None
    return selected_element.get_attribute("innerText")

def get_elements_by_tag_name(tag_name) -> List[WebElement]:
    return driver.find_elements(By.TAG_NAME, tag_name)

def get_element_by_id(id) -> Optional[WebElement]:
    try:
        selected_element = driver.find_element(By.ID, id)
    except e.NoSuchElementException:
        # Warning for allow cookies can be ignored
        if id != 'onetrust-accept-btn-handler':
            log.warning("Element with id '{}' does not exist".format(id))
        return None
    return selected_element

def get_elements_by_class_name(class_name) -> List[WebElement]:
    return driver.find_elements(By.CLASS_NAME, class_name)

def get_element_by_class_name(class_name) -> Optional[WebElement]:
    try:
        selected_element = driver.find_element(By.CLASS_NAME, class_name)
    except e.NoSuchElementException:
        log.warning("Element with class name '{}' does not exist".format(class_name))
        return None
    return selected_element

def hover_over_element_by_x_path(x_path) -> Optional[WebElement]:
    try:
        selected_element = driver.find_element(By.XPATH, x_path)
        action = ActionChains(driver)
        action.move_to_element(selected_element).perform()
    except e.NoSuchElementException:
        log.warning("Element with x_path '{}' does not exist".format(x_path))
        return None

def config_options(options) -> None:
    #options.add_argument('--headless')
    options.add_argument('--window-size=1920,1080')
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-gpu')
    options.add_argument('--disable-crash-reporter')
    options.add_argument('--disable-extensions')
    options.add_argument('--disable-in-process-stack-traces')
    options.add_argument('--disable-logging')
    options.add_argument('--disable-dev-shm-usage')
    options.add_argument('--log-level=3')
    options.add_argument('--output=/dev/null')

def config_driver() -> webdriver.Chrome:
    chrome_options = Options()
    config_options(chrome_options)
    # Change in 4.10 version
    service = Service(executable_path='chromedriver.exe')

    driver = webdriver.Chrome(service=service, options=chrome_options)

    return driver

driver = config_driver()
