import requests
import os
from dotenv import dotenv_values
from Utils import Utils
import json

config = dotenv_values('../.env')
API_BASE_URL = config['API_BASE_URL']

headers = {
    'Content-Type': 'application/json'
}

def post_brands() -> None:
    brands = Utils.read_json_file('brands.json')
    response = requests.post(
        f"{API_BASE_URL}brands",
        headers=headers,
        json=brands
    )

post_brands()
