import os
import time

from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from django.conf import settings

#TARGET_FOLDER = '/per_frame_animated/{}/{}/{}.png'

LOCAL_PATH = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))+'/testToBeDeleted/per_frame_animated/{}/{}/{}.png'

WINDOW_SIZE = 1920, 1080
FRAMES_PER_ANIMATION = 60  # this many screenshots will be created from the animation
ANIM_DURATION = 2  # in seconds

BASE_SCR = 'arguments[0].setAttribute("style", "display: block;animation-delay: {}s;animation-duration: {}s; animation-iteration-count: infinite;animation-play-state: paused;")'

options = Options()
options.add_argument("--headless")
options.add_argument("--no-sandbox")
options.add_argument("start-maximized")
options.add_argument("disable-infobars")
options.add_argument("--disable-extensions")

driver = webdriver.Chrome(executable_path='/usr/local/bin/chromedriver', options=options,
                          service_args=['--ignore-ssl-errors=true', '--ssl-protocol=any'])
driver.set_window_size(*WINDOW_SIZE)
driver.get('https://daneden.github.io/animate.css/')
target = driver.find_element_by_xpath('//span[@id="animationSandbox"]')
select = driver.find_element_by_xpath('//select[contains (@class, "input")]')
groups = select.find_elements_by_xpath('./optgroup')
shots = {}


def record(anim_name):
    whole_page = driver.find_element_by_xpath('//html')
    init_scr = 'arguments[0].classList.add("{}")'.format(anim_name)
    out_scr = 'arguments[0].classList.remove("{}")'.format(anim_name)

    driver.execute_script(init_scr, target)
    for i in range(FRAMES_PER_ANIMATION):
        driver.execute_script(BASE_SCR.format((-i / FRAMES_PER_ANIMATION) * ANIM_DURATION, ANIM_DURATION), target)
    shots[anim_name].append(whole_page.screenshot_as_png)
    driver.execute_script(out_scr, target)


def save(anim_group, anim_name):
    for i in range(len(shots[anim_name])):
        #with open(os.path.join(settings.MEDIA_ROOT + TARGET_FOLDER.format(anim_group, anim_name, i)), "wb") as f:
        #with open(TARGET_FOLDER.format(anim_group, anim_name, i), "wb") as f:
        with open(LOCAL_PATH.format(anim_group, anim_name, i), "wb") as f:
            f.write(shots[anim_name][i])


# break the js
driver.execute_script('arguments[0].setAttribute("id", "nice_page_sorry_for_breaking_it")', target)
driver.execute_script(BASE_SCR.format(0, ANIM_DURATION), target)

# to make sure all set
time.sleep(3)

for group in groups:
    group_name = group.get_attribute('label')
    options = group.find_elements_by_xpath('./option')
    for option in options:
        option_name = option.get_attribute('value')
        #if not os.path.isdir(os.path.join(settings.MEDIA_ROOT + TARGET_FOLDER.format(group_name, option_name))):
        if not os.path.isdir(os.path.dirname(LOCAL_PATH).format(group_name, option_name)):
            os.makedirs(os.path.dirname(LOCAL_PATH).format(group_name, option_name))
            #os.makedirs(os.path.join(settings.MEDIA_ROOT + TARGET_FOLDER.format(group_name, option_name)))
        shots[option_name] = []
        option.click()
        record(option_name)
        save(group_name, option_name)
