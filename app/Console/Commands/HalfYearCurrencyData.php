<?php

namespace App\Console\Commands;

use App\Jobs\SaveExchangeRateToRedis;
use Illuminate\Console\Command;

class HalfYearCurrencyData extends Command
{
    protected $signature = 'currency:half';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        SaveExchangeRateToRedis::dispatch()->onQueue('redis');
        $this->info('A queue of currency data has been started.');
    }
}

