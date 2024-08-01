import pandas as pd
import numpy as np
import re
import os
import subprocess

from bs4 import BeautifulSoup

class PreprocessLibrary:
        
    def cleaningAbstrak(self, text):
        text = str(text)
        text = BeautifulSoup(text, "html.parser").get_text()
        text = text.lower()
        text = re.sub(r'http\\S+', '', text)
        text = re.sub('(@\\w+|#\\w+)', '', text)
        text = re.sub('[^a-zA-Z]', ' ', text)
        text = re.sub("\\n", " ", text)
        text = re.sub('(s{2,})', ' ', text)
        return text

    def cleaningJudul(self, text):
        text = str(text)
        text = BeautifulSoup(text, "html.parser").get_text()
        text = text.title()
        text = re.sub("\\n", " ", text)
        text = re.sub('(s{2,})', ' ', text)
        return text

    def cleaningPenulis(self, text):
        text = text.title()
        text = re.sub("\\n", " ", text)
        text = re.sub("(s{2,})", " ", text)
        return text

    def save_to_json(self, df, file_name, folder='./storage/result/preprocessOplib'):
        subprocess.run(["php", "artisan", "log:crawling", f"asd"])
        if not os.path.exists(folder):
            os.makedirs(folder)
        df.to_json(f'{folder}/{file_name}', orient='records')

    def preprocess_dataframe(self, df):
        df = df.rename(columns={'title': 'Judul', 'author': 'Penulis1', 'lecturer': 'Penulis2', 'publish_year': 'Tahun', 'abstract': 'Abstrak'})
        df = df[["Judul", "Penulis1", "Penulis2", "Tahun", "Abstrak"]]

        df = df.dropna()

        df['Abstrak'] = df['Abstrak'].apply(self.cleaningAbstrak)
        df['Judul'] = df['Judul'].apply(self.cleaningJudul)
        df["Penulis1"] = df["Penulis1"].apply(self.cleaningPenulis)
        df["Penulis"] = df["Penulis1"] + ", " + df["Penulis2"]
        df = df.drop(["Penulis1", "Penulis2"], axis=1)
        df = df[["Judul", "Tahun", "Abstrak", "Penulis"]]
        df["Tahun"] = df["Tahun"].astype(int)

        df['Abstrak'] = df['Abstrak'].replace('', np.nan)
        df['Judul'] = df['Judul'].replace('', np.nan)
        df = df.dropna()

        return df