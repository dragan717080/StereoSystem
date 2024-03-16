from abc import ABC
import difflib
import json
from log_config import log
import re
from selenium_config import get_element_by_id
from typing import List, Dict, Any, Optional

# These methods are app specific (outside of 'write_to_json_file')
class Utils(ABC):
    @staticmethod
    def write_data_dict_to_json_file(data_dict: Dict[str, Any], file_name: str = 'categories.json') -> None:
        # Some elements don't have subcategories so they will not be processed
        data_dict = {k: v for (k, v) in data_dict.items() if len(v)}
        with open(file_name, "w") as file:
            json.dump(data_dict, file, indent=4)

        log.info(f"Successfully written to '{file_name}' json file")

    @staticmethod
    # Write anything to json file, similar to 'write_data_dict_to_json_file' function but without preconceived format
    def write_to_json_file(data: dict, file_name: str) -> None:
        with open(file_name, 'w') as file:
            json.dump(data, file, indent=4)

        log.info(f"Successfully written to '{file_name}' json file")

    @staticmethod
    def read_file_line_by_line(file_name: str) -> List[str]:
        lines = []
        with open(file_name, 'r') as file:
            while True:
                line = file.readline()
                if not line:
                    break
                lines.append(line.strip())
            return lines

    @staticmethod
    def read_json_file(file_name: str) -> dict:
        with open(file_name, 'r') as file:
            data = json.load(file)
            return data

    # If allow cookies modal is present, close it
    @staticmethod
    def allow_cookies() -> None:
        button = get_element_by_id('onetrust-accept-btn-handler')
        if button:
            button.click()

    # Sorts list of dict by some key value
    @staticmethod
    def sort_list_of_dict_alphabetically(list_of_dict: List[dict], key) -> List[dict]:
        list_of_dict = sorted(list_of_dict, key=lambda x: x[key])
        return list_of_dict

    # Strip non digits and convert remaining string to int
    @staticmethod
    def str_to_int(s: str) -> int:
        return int(re.sub(r"\D", "", s))

    @staticmethod
    def find_closest_match(given_string: str, offered_list: List[str]) -> Optional[str]:
        closest_match = difflib.get_close_matches(given_string, offered_list, n=1)
        return closest_match[0] if closest_match else None
