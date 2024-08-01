#!/usr/bin/env python3

from oplib import OpenLibrary, AdvancedSearchType
from preprocessOplib import PreprocessLibrary
import pandas as pd
import numpy as np
import datetime
import json
import subprocess
import os

if __name__ == "__main__":
    oplib = OpenLibrary()
    preprocess = PreprocessLibrary()

    # TODO: Change the search options based on your needs

    # Get current date
    current_date = datetime.date.today()

    # Extract year, month, and day
    year = current_date.year
    month = current_date.month
    day = current_date.day

    one_month_ago = current_date - datetime.timedelta(days=60)

    year2 = one_month_ago.year
    month2 = one_month_ago.month
    day2 = one_month_ago.day
    
    start_day,start_month,start_year = day2,month2,year2
    end_day,end_month,end_year = day,month,year
    # print(day,month,year)
    # print(day2,month2,year2)
    publication_type =[#AdvancedSearchType.SKRIPSI, 
                       AdvancedSearchType.TA,
                       #AdvancedSearchType.THESIS
                       ]

    for i in range(len(publication_type)):
        search_options = {
            'search[type]': publication_type[i],
            'search[number]': '',
            'search[title]': '',
            'search[author]': '',
            'search[publisher]': '',
            'search[editor]': '',
            'search[subject]': '',
            'search[classification]': '',
            'search[location]': '',
            'search[entrance][from][day]': start_day,
            'search[entrance][from][month]': start_month,
            'search[entrance][from][year]': start_year,
            'search[entrance][to][day]': end_day,
            'search[entrance][to][month]': end_month,
            'search[entrance][to][year]': end_year,
        }

        if publication_type[i] == 4 : 
            file = 'SKRIPSI' 
        elif publication_type[i] == 6 :
            file = 'TA'
        # print(f'Scraping {file} at',start_day,start_month,start_year, 'to ',end_day,end_month,end_year,'......')

        content = oplib.get_all_data_from_range_date(**search_options)
        results = oplib.parse_results(content)
    
        df = pd.DataFrame({'title': [],
                        'classification' : [],
                        'type_publication' : [],
                        'subject' : [],
                        'abstract' : [],
                        'keywords' : [],
                        'author' : [],
                        'lecturer' : [],
                        'publisher' : [],
                        'publish_year' : []
        })

        for index, totals, data in results:
            df = pd.concat([df, pd.DataFrame([data])], ignore_index=True)
            # print("Scrapping done, Melakukan preprocessing....")
            file = f'{datetime.datetime.now().strftime("%Y-%m-%d")}_scrappingOplib.json'

            if not os.path.exists('./storage/result/scrappingOplib'):
                os.makedirs('./storage/result/scrappingOplib')
        
            df.to_json(f'./storage/result/scrappingOplib/{file}', orient='records')
            # df.to_json(preprocessed_file_name, orient='records')
            # print(f"[{index}/{totals}]: {data['title']}")

        # Lakukan preprocess
        try:
            # print('=' * 32)
            # print(f'Preprocessing data...')
            # print('=' * 32)
            df = preprocess.preprocess_dataframe(df)

            file = f'{datetime.datetime.now().strftime("%Y-%m-%d")}_oplib.json'
            preprocess.save_to_json(df, file)
            # print("Done")
            # sleep
            subprocess.run(["php", "artisan", "app:crawller", f"{file}"])
        except Exception as e:
            # print("Terjadi error:", str(e))
            subprocess.run(["php", "artisan", "log:crawling", f"{e}"])
        #Save JSON File
        
