# test-exchange-rate

## Installation

Clone the repository-
```
git clone https://github.com/dimidrol298/test-exchange-rate.git
```
Then do a composer install and composer update
```
composer install && composer update
```

Create .env file
```
cp .env.example .env
```

Set up your connection with redis
```
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CLIENT=predis
```

Then launch the worker
```
php artisan queue:work --queue=high,redis
```

## Commands:
To run the command to collect data, enter
```
php artisan currency:half
```
To run the command to get data on the selected date, enter
```
php artisan currency:get 15/08/2023 USD
```


