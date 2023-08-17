<?php

namespace App\Console\Commands;

use App\Repository\RedisRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GetExchangeRate extends Command
{
    protected $signature = 'currency:get {date} {currency}';
    protected $description = 'Get exchange rate for a specific date and currency';

    private RedisRepository $redisRepository;

    public function __construct(RedisRepository $redisRepository)
    {
        parent::__construct();
        $this->redisRepository = $redisRepository;
    }

    public function handle()
    {
        $date = $this->argument('date');
        $currency = strtoupper($this->argument('currency'));

        $cachedExchangeRate = $this->getCachedExchangeRate($date);
        if (isset($cachedExchangeRate[$currency])) {
            $this->info("Exchange rate for $currency on $date: {$cachedExchangeRate[$currency]}");
            $previousDate = Carbon::createFromFormat('d/m/Y', $date)->subDay()->format('d/m/Y');
            $previousCachedExchangeRate = $this->getCachedExchangeRate($previousDate);

            if (isset($previousCachedExchangeRate[$currency])) {
                $this->outputDifferenceBetweenCurrency($currency, $cachedExchangeRate, $previousCachedExchangeRate);
            } else {
                $this->error("No exchange rate found for $currency on $previousDate");
            }
        } else {
            $this->error("No exchange rate found for $currency on $date");
        }
    }

    private function getCachedExchangeRate($date)
    {
        return $this->redisRepository->getDialog("exchange_rate:$date");
    }

    private function outputDifferenceBetweenCurrency($currency, $currentRates, $previousRates)
    {
        $difference = $currentRates[$currency] - $previousRates[$currency];
        $this->info("Difference from previous day: $difference");
    }
}

