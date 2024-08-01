from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from bs4 import BeautifulSoup
import time
import pandas as pd
import datetime
import random
import undetected_chromedriver as uc
import subprocess
import os

user_agents = [
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
    "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:89.0) Gecko/20100101 Firefox/89.0",
    # Add more user agents if necessary
]

class WebScraper:
    def __init__(self):
        self.driver = self.create_driver()

    def random_delay(self, min_seconds=2, max_seconds=5):
        time.sleep(random.uniform(min_seconds, max_seconds))

    def get_random_user_agent(self):
        return random.choice(user_agents)

    def create_driver(self):
        options = webdriver.ChromeOptions()
        options.add_argument('--ignore-certificate-errors')
        options.add_argument('--ignore-ssl-errors')
        options.add_argument('--no-sandbox')
        options.add_argument('--disable-dev-shm-usage')
        options.add_argument('--disable-blink-features=AutomationControlled')
        options.add_argument(f'user-agent={self.get_random_user_agent()}')

        driver = uc.Chrome(options=options)
        return driver

    def perform_human_interaction(self):
        actions = webdriver.ActionChains(self.driver)
        actions.move_by_offset(random.randint(0, 100), random.randint(0, 100))
        actions.perform()
        self.random_delay()
        self.driver.execute_script("window.scrollBy(0, {});".format(random.randint(200, 800)))
        self.random_delay()

    def login_sinta(self, username, password):
        self.driver.get("https://sinta.kemdikbud.go.id/logins")
        self.driver.find_element(By.NAME, "username").send_keys(username)
        self.driver.find_element(By.NAME, "password").send_keys(password)
        self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()

    def login_elsevier(self, username, password):
        self.driver.get("https://id.elsevier.com/as/authorization.oauth2?platSite=SC%2Fscopus&ui_locales=en-US&scope=openid+profile+email+els_auth_info+els_analytics_info+urn%3Acom%3Aelsevier%3Aidp%3Apolicy%3Aproduct%3Aindv_identity&els_policy=idp_policy_indv_identity_plus&response_type=code&redirect_uri=https%3A%2F%2Fwww.scopus.com%2Fauthredirect.uri%3FtxGid%3De5949ec1f7f8942be40f031fec9c4705&state=userLogin%7CtxId%3DBFEEEC06342ACB062CC06964CAAFD770.i-091fb6f4d2a483d2a%3A5&authType=SINGLE_SIGN_IN&prompt=login&client_id=SCOPUS")
        WebDriverWait(self.driver, 20).until(EC.element_to_be_clickable((By.ID, 'onetrust-accept-btn-handler'))).click()
        self.random_delay()
        self.driver.find_element(By.ID, "bdd-email").send_keys(username)
        self.random_delay()
        self.driver.find_element(By.CSS_SELECTOR, "button[value='emailContinue']").click()
        
        WebDriverWait(self.driver, 20).until(EC.visibility_of_element_located((By.ID, "bdd-password")))
        self.random_delay()
        self.driver.find_element(By.ID, "bdd-password").send_keys(password)
        
        WebDriverWait(self.driver, 20).until(EC.element_to_be_clickable((By.CSS_SELECTOR, "button[value='signin']")))
        self.random_delay()
        self.driver.find_element(By.CSS_SELECTOR, "button[value='signin']").click()

    def get_article_links(self, url, num_pages):
        current_year = datetime.datetime.now().year
        target_year = current_year - 1
        done = False

        self.driver.get(url)
        WebDriverWait(self.driver, 20).until(EC.presence_of_element_located((By.CLASS_NAME, "ar-title")))
        
        all_article_links = []
        all_years = []

        for _ in range(num_pages):
            x = 0
            soup = BeautifulSoup(self.driver.page_source, 'html.parser')
            years = []
            ar_years = soup.find_all('a', class_='ar-year')
            for ar_year in ar_years:
                x += 1
                year = int(ar_year.text.strip())
                if year <= target_year:
                    x -= 1
                    done = True
                    break
                years.append(year)
            
            all_years.extend(years)
            article_links = []
            ar_titles = soup.find_all('div', class_='ar-title')
            for i in range(x):
                link = ar_titles[i].find('a')['href']
                article_links.append(link)
            
            all_article_links.extend(article_links)
            if done:
                break
            if any(year <= target_year for year in years):
                all_years.extend(years)
                break
            try:
                self.driver.find_element(By.XPATH, "//a[contains(@class,'page-link') and contains(text(),'Next')]").click()
            except:
                break
        return all_article_links, all_years

    def scrape_article(self, article_links, article_years):
        result = {
            "judul": [],
            "penulis": [],
            "tahun": [],
            "sdgs": [],
            "abstrak": []
        }

        judul = []
        penulis = []
        abstrak = []
        sdgs = []

        for link in article_links:
            self.driver.get(link)
            self.random_delay()
            self.perform_human_interaction()
            penulisBanyak = []
            soup = BeautifulSoup(self.driver.page_source, 'html.parser')
            judul.append([h2.get_text(strip=True) for h2 in soup.find_all('h2', class_='Typography-module__lVnit Typography-module__o9yMJ Typography-module__JqXS9 Typography-module__ETlt8')])
            abstrak.append([p.get_text(strip=True) for p in soup.find_all('p', class_='Typography-module__lVnit Typography-module__ETlt8 Typography-module__GK8Sg')])
            sdgs.append([div.get_text(strip=True) for div in soup.find_all('div', class_='Col-module__hwM1N')])
            div_elements = soup.find_all('ul', class_='DocumentHeader-module__LpsWx')
            for div_element in div_elements:
                spans = div_element.find_all('span', class_='Typography-module__lVnit Typography-module__Nfgvc Button-module__Imdmt')
                for span in spans:
                    penulisBanyak.append(span.text)
            penulis.append(";".join(penulisBanyak))
        result["judul"] = judul
        result["penulis"] = penulis
        result["tahun"] = article_years
        result["abstrak"] = abstrak
        result["sdgs"] = sdgs
        return result

    def save_to_json(self, result):
        df = pd.DataFrame(result)
        file = f'{datetime.datetime.now().strftime("%Y-%m-%d")}_scrappingSinta.json'
        if not os.path.exists('./storage/result/scrappingSinta/'):
            os.makedirs('./storage/result/scrappingSinta/')
            
        df.to_json(f'./storage/result/scrappingSinta/{file}', orient='records')
        print("Crawl Success")

    def run(self, sinta_credentials, elsevier_credentials, sinta_url, num_pages):
        username_sinta, password_sinta = sinta_credentials
        username_elsevier, password_elsevier = elsevier_credentials
        
        self.login_sinta(username_sinta, password_sinta)
        article_links, article_years = self.get_article_links(sinta_url, num_pages)
        print(article_links)
        # print(article_years)
        
        self.driver = self.create_driver()
        self.login_elsevier(username_elsevier, password_elsevier)
        result = self.scrape_article(article_links, article_years)
        self.save_to_json(result)
        # subprocess.run(["python", "preprocessSinta/preprocessSinta.py"])
        self.driver.quit()