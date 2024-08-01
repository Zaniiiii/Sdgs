from scrappingSinta import WebScraper
from preprocessSinta import SintaPreprocessor
import datetime
import subprocess

if __name__ == "__main__":
    try:
        sinta_credentials = ("suryoadhiwibowo@telkomuniversity.ac.id", "Bangkit2023!")
        elsevier_credentials = ("hamdanazani@student.telkomuniversity.ac.id", "dayak1352")
        sinta_url = 'https://sinta.kemdikbud.go.id/affiliations/profile/1093?page=1&view=scopus'
        num_pages = 1

        scraper = WebScraper()
        scraper.run(sinta_credentials, elsevier_credentials, sinta_url, num_pages)

        file_sinta = f'./storage/result/scrappingSinta/{datetime.datetime.now().strftime("%Y-%m-%d")}_scrappingSinta.json'
        preprocessor = SintaPreprocessor(file_sinta)
        processed_df = preprocessor.preprocess()
        file_result = f'{datetime.datetime.now().strftime("%Y-%m-%d")}_sinta'
        preprocessor.save_result(file_result)
    except Exception as e:
        subprocess.run(["php", "artisan", "log:crawling", f"{e}"]) 
        print("Terjadi error:", str(e))