import pandas as pd
import mysql.connector as connection
import numpy as np
import pickle
from sklearn.metrics import r2_score
from sklearn.ensemble import RandomForestRegressor
from sklearn.model_selection import train_test_split


def detect_outlier(raw_data):
    outlier = []
    threshold = 3
    mean = np.mean(raw_data)
    std = np.std(raw_data)
    for i in raw_data:
        z_score = (i - mean) / std
        if np.abs(z_score) > threshold:
            outlier.append(i)
    return outlier


def clean_data(df):
    df.dropna(subset=['listing_mileage', 'listing_price_in_cents'], inplace=True)
    df = df[df['listing_price_in_cents'] != 0]

    final_data = df[['year', 'listing_mileage', 'listing_price_in_cents']]
    final_data['year'] = pd.to_numeric(df['year'], errors='ignore')

    final_data['age'] = 2022 - final_data['year']
    final_data.drop(['year'], axis=1, inplace=True)

    outlier_prices = detect_outlier(final_data['listing_price_in_cents'].to_list())
    if len(outlier_prices) > 0:
        final_data.drop(final_data[final_data['listing_price_in_cents'] >= outlier_prices[0]].index, inplace=True)

    outlier_mileages = detect_outlier(final_data['listing_mileage'].to_list())
    if len(outlier_mileages) > 0:
        final_data.drop(final_data[final_data['listing_mileage'] >= outlier_mileages[0]].index, inplace=True)

    return final_data


def train_data(candidate_data):
    x = candidate_data.drop(['listing_price_in_cents'], axis=1)
    y = candidate_data['listing_price_in_cents']
    x_train, x_test, y_train, y_test = train_test_split(x, y, test_size=0.3, random_state=0)
    rf = RandomForestRegressor(n_estimators=10,
                               criterion='mse',
                               random_state=20,
                               n_jobs=-1)
    rf.fit(x_train, y_train)
    rf_test_pred = rf.predict(x_test)
    print(row['name'] + '[' + str(row['id']) + ']: ' + str(r2_score(y_test, rf_test_pred)))
    return rf


def create_model(file_id, data_model):
    file = open('models/' + str(file_id) + '_model.pkl', 'wb')
    pickle.dump(data_model, file)


connected = False
try:
    db = connection.connect(host="127.0.0.1", database='car', user="root", passwd="", use_pure=True)
    connected = True
except Exception as e:
    print(str(e))
if connected:
    cursor = db.cursor(dictionary=True)
    cursor.execute("select * from car_makes limit 3")
    results = cursor.fetchall()

    for row in results:
        query = "select cs.year, cs.listing_mileage, cs.listing_price_in_cents " \
                "from car_solds cs " \
                "inner join car_models mo on cs.car_model_id = mo.id " \
                "inner join car_makes ma on mo.car_make_id=ma.id " \
                "where ma.name like '%' %(make)s '%'"
        data = pd.read_sql(query, db, params={'make': row['name']})
        fd = clean_data(data)
        rf_model = train_data(fd)
        create_model(row['id'], rf_model)
        m = pickle.load(open('models/' + str(row['id']) + '_model.pkl', 'rb'))

    cursor.close()
    db.close()
