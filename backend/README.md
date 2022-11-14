## Installation (Local)

#### create config.ini
- update database_connection for database connection

#### install dependencies

```
pip3 install -r requirements.txt
```

#### start flask api

```
python3 main.py
```

#### run unit tests

```
python3 -m unittest tests/test_car_value_predict_controller.py -v
``` 

#### script to train models
```
python3 scripts/car_value_predict.py
```