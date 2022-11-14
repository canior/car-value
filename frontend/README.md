## Installation (Local)


#### create .env file
- copy .env.example to .env
- update database connection if necessary
- update MIX_ENDPOINT_URL for vue to connect to laravel service
- update CAR_PREDICTION_URL to connect to flask rest api if necessary

#### install dependencies

```
composer install
```

#### database migration

```
php artisan migrate
```

#### build vue with mix

```
npm i
node_modules/.bin/webpack --config=node_modules/laravel-mix/setup/webpack.config.js
```

#### run unit tests
- copy .env.example to .env.testing
- update database connection if necessary
- update MIX_ENDPOINT_URL for vue to connect to laravel service
- update CAR_PREDICTION_URL to connect to flask rest api if necessary
```
php artisan test
```

### start local server

```
php artisan serve
```

### run import service

```
php artisan import:sold-cars {file-path}
```

### seed data if no data file found

```
php artisan db:seed
```
