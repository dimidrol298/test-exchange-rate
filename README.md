# tic-tac-toe-php

## Installation

Clone the repository-
```
git clone https://github.com/dimidrol298/test-exchange-rate.git
```
Then do a composer install and composer update
```
composer install && composer update
```
Then launch the worker
php artisan queue:work --queue=high,redis

## Commands:
To run the command to collect data, enter
```
php artisan currency:half
```
To run the command to get data on the selected date, enter
```
php artisan currency:get 15/08/2023 USD
```


