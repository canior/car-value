# Car Price Prediction:

## Table of Content
  * [Demo](#demo)
  * [Overview](#overview)
  * [Motivation](#motivation)
  * [Technical Aspect](#Technical-Aspect)
  * [Installation](#installation)
  * [Deployement on Heroku](#deployement-on-heroku)
  * [Directory Tree](#directory-tree)
  * [Technologies used](#technologies-used)
  * [Bug / Feature Request](#bug---feature-request)
  * [Future scope of project](#future-scope)
  * [Credits](#credits)
  
## Demo
Link: [https://carpricepredictions-api.herokuapp.com/](https://carpricepredictions-api.herokuapp.com/)

[![](https://i.imgur.com/NZxvv5f.png)](https://carpricepredictions-api.herokuapp.com/)

[![](https://i.imgur.com/2PxcsLa.png)](https://carpricepredictions-api.herokuapp.com/)
  
## Overview
This is a flask web app which predicts the price of used Cars trained on the top of Random Forest Regressor model. The dataset contains information about used cars is taken from   Kaggle listed on www.cardekho.com. The trained model takes a data of used cars as a input and predict the Price of the Car as a output.
  
## Motivation
What could be a perfect way to utilize unfortunate lockdown period? Like most of you, I spend my time in online games, web series and coding. Last Year, I started to learn Data   Science and Machine Learning course from online platform. I came to know mathematics behind all the supervised/unsupervised model but it is important to work on real world         application to actually makes a difference. It is just a small initiative towards this.

## Technical Aspect:
This project is divided into two parts:
1) Trained a Machine Learning model using Random Forest Regressor(Code is available in this repo)
2) Deployed the model using Flask on Heroku Platform.

## Installation
The Code is written in Python 3.7.9. If you don't have Python installed you can find it [here](https://www.python.org/downloads/). If you are using a lower version of Python you can upgrade using the pip package, ensuring you have the latest version of pip. To install the required packages and libraries, run this command in the project directory after [cloning](https://www.howtogeek.com/451360/how-to-clone-a-github-repository/) the repository:
```bash
pip install -r requirements.txt
```

## Deployement on Heroku
Login or signup in order to create virtual app. You can either connect your github profile or download ctl to manually deploy this project.

[![](https://i.imgur.com/dKmlpqX.png)](https://heroku.com)

Our next step would be to follow the instruction given on [Heroku Documentation](https://devcenter.heroku.com/articles/getting-started-with-python) to deploy a web app.

## Directory Tree 
```
├── static 
│   ├── styles.css
├── template
│   ├── index.html
├── CarPricePrediction.ipynb
├── Procfile	
├── README.md
├── app.py
├── car_data1.csv	
├── random_forest_regressor_model.pkl
├── requirements.txt
```

## Technologies used

![](https://forthebadge.com/images/badges/made-with-python.svg)

[<img target="_blank" src="https://flask.palletsprojects.com/en/1.1.x/_images/flask-logo.png" width=170>](https://flask.palletsprojects.com/en/1.1.x/) [<img target="_blank" src="https://number1.co.za/wp-content/uploads/2017/10/gunicorn_logo-300x85.png" width=280>](https://gunicorn.org) [<img target="_blank" src="https://scikit-learn.org/stable/_static/scikit-learn-logo-small.png" width=200>](https://scikit-learn.org/stable/) [<img target="_blank" src="https://i.imgur.com/gh8nX4U.png" width=170>](https://flask.palletsprojects.com/en/1.1.x/)


## Bug / Feature Request

If you find a bug (the website couldn't handle the query and / or gave undesired results), kindly open an [issue](https://github.com/nayan2112/Car-Price-Prediction/issues) here by including your search query and the expected result

## Future Scope
* Use multiple Algorithms 
* Optimize Flask app.py
* Front-End 

## Credits
* Dataset: The dataset contains information about used cars is taken from Kaggle listed on www.cardekho.com.
* Dataset Link: https://www.kaggle.com/nehalbirla/vehicle-dataset-from-cardekho


