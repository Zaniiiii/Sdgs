import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import re
# import googletrans
# from googletrans import Translator
import datetime
import os
import subprocess
import nltk
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from nltk.stem import PorterStemmer, WordNetLemmatizer

# Ensure you have downloaded the necessary NLTK resources
nltk.download('punkt')
nltk.download('stopwords')
nltk.download('wordnet')

class SintaPreprocessor:
    def _init_(self, file_path):
        self.file_path = file_path
        self.df = pd.read_json(file_path)
    
    def list_to_string(self, lst):
        return ' '.join(lst)

    def cleaning_penulis(self, names):
        cleaned_names = []
        for name in names:
            parts = name.strip().split(',')
            if len(parts) == 2:
                cleaned_names.append(parts[1].strip() + ' ' + parts[0].strip())
            else:
                cleaned_names.append(parts[0].strip())
        return cleaned_names

    def cleaning_abstrak_tahap1(self, text):
        text = str(text).lower()
        text = re.sub(r'©.*', '', text)
        return text

    # def translate_text(self, text):
    #     try:
    #         translated = self.translator.translate(text, dest='id')
    #         return translated.text
    #     except Exception as e:
    #         print(f"Error occurred: {e}")
    #         return None

    def cleaning_abstrak_tahap2(self, text):
        # Lowercasing
        text = text.lower()
        
        # Removing punctuation
        text = re.sub(r'[^\w\s]', '', text)
        
        # Removing numbers
        text = re.sub(r'\d+', '', text)
        
        # Tokenization
        tokens = word_tokenize(text)
        
        # Removing stop words
        stop_words = set(stopwords.words('english'))
        tokens = [word for word in tokens if word not in stop_words]
        
        
        # Lemmatization (alternative to stemming)
        lemmatizer = WordNetLemmatizer()
        lemmatized_tokens = [lemmatizer.lemmatize(word) for word in tokens]
        
        # Join tokens back to string (optional)
        preprocessed_text = ' '.join(lemmatized_tokens)  # Use this if lemmatization is chosen
        
        return preprocessed_text

    def get_aspects(self, review):
        review = str(review) if review else ''
        review_aspects = []

        aspects = {
            'SDGS1': ['Goal 1'],
            'SDGS2': ['Goal 2'],
            'SDGS3': ['Goal 3'],
            'SDGS4': ['Goal 4'],
            'SDGS5': ['Goal 5'],
            'SDGS6': ['Goal 6'],
            'SDGS7': ['Goal 7'],
            'SDGS8': ['Goal 8'],
            'SDGS9': ['Goal 9'],
            'SDGS10': ['Goal 10'],
            'SDGS11': ['Goal 11'],
            'SDGS12': ['Goal 12'],
            'SDGS13': ['Goal 13'],
            'SDGS14': ['Goal 14'],
            'SDGS15': ['Goal 15'],
            'SDGS16': ['Goal 16'],
            'SDGS17': ['Goal 17']
        }

        for aspect, keywords in aspects.items():
            for keyword in keywords:
                if re.search(fr'{re.escape(keyword)}', review):
                    review_aspects.append(aspect)
                    break
        return review_aspects

    def preprocess(self):
        self.df['sdgs'] = self.df['sdgs'].apply(self.list_to_string)
        self.df['abstrak'] = self.df['abstrak'].apply(self.list_to_string)
        self.df['judul'] = self.df['judul'].apply(self.list_to_string)

        self.df['penulis'] = self.df['penulis'].apply(lambda x: x.replace('Save all to author list', ''))
        self.df['penulis'] = self.df['penulis'].apply(lambda x: x.split(';'))
        self.df['penulis'] = self.df['penulis'].apply(self.cleaning_penulis)
        self.df['penulis'] = self.df['penulis'].apply(lambda x: ', '.join(x))

        self.df = self.df.dropna()

        self.df['abstrak'] = self.df['abstrak'].apply(self.cleaning_abstrak_tahap1)
        # self.df['abstrak'] = self.df['abstrak'].apply(self.translate_text)
        self.df['abstrak'] = self.df['abstrak'].apply(self.cleaning_abstrak_tahap2)

        self.df['aspects'] = self.df['sdgs'].apply(self.get_aspects)
        self.df['aspects'] = self.df['aspects'].apply(lambda y: np.nan if len(y) == 0 else y)
        self.df = self.df.drop(["sdgs"], axis=1)

        self.df = self.df.rename(columns={'aspects': 'SDGS', 'judul': 'Judul', 'penulis': 'Penulis', 'abstrak': 'Abstrak', 'tahun': 'Tahun'})

        # self.dfNoLabel = self.df.loc[self.df['Aspects'].isnull()]
        # self.dfNoLabel = self.df.drop(['Aspects'], axis=1)

        # # self.df = self.df.dropna()
        # sdgs_columns = ['SDG{}'.format(i) for i in range(1, 18)]
        # for sdg_col in sdgs_columns:
        #     self.df[sdg_col] = self.df['Aspects'].apply(lambda x: 1 if sdg_col in x else 0)

        # sdg_columns = [f'SDGS{i}' for i in range(1, 18)]
        # self.df['SDGS'] = self.df.apply(lambda row: [f'SDGS{i}' for i in range(1, 18) if row[f'SDGS{i}'] == 1], axis=1)

        # aspects_list = self.df['Aspects']
        # self.df = self.df.drop(['Aspects'], axis=1)
        return self.df, #self.dfNoLabel

    def save_result(self, file_name):
        if not os.path.exists('./storage/result'):
            os.makedirs('./storage/result')
        self.df.to_json(f'./storage/result/preprocessSinta/{file_name}.json', orient='records')
        #self.dfNoLabel.to_json(f'./storage/result/preprocessSinta/{file_name}NoLabel.json', orient='records')
        # subprocess.run(["php", "artisan", "app:crawller", f"{file_name}"])