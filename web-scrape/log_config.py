import logging

"""
For this app it is necessary to raise level from DEBUG to INFO
to filter out Selenium logs
"""
logging.basicConfig(
    filename="logs.log",
    level=logging.INFO,
    format="%(asctime)s:%(levelname)s:%(message)s"
)

log = logging.getLogger(__name__)
