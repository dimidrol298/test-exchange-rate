<?php

namespace App\Jobs;

use App\Repository\RedisRepository;
use Carbon\CarbonInterval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\Log;

class SaveExchangeRateToRedis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var RedisRepository
     */
    private RedisRepository $redisRepository;

    public function __construct()
    {
        $this->redisRepository = new RedisRepository();
    }

    public function handle(CurrencyService $currencyService)
    {
        $endDate = now();
        $startDate = now()->subDays(180);
        $interval = CarbonInterval::day();
        $period = new \DatePeriod($startDate, $interval, $endDate);

        foreach ($period as $date) {
            $formattedDate = $date->format('d/m/Y');
            Log::info("Queueing exchange rates for $formattedDate");
            if (!$this->redisRepository->getDialog("exchange_rate:$formattedDate")) {
                try {
                    $exchangeRate = $currencyService->getExchangeRateForDate($formattedDate);
                    $this->redisRepository->setDialog("exchange_rate:$formattedDate", $exchangeRate);
                    Log::info("Exchange rate for $formattedDate saved to Redis.");
                } catch (\Exception $e) {
                    Log::error("Error saving exchange rates for $formattedDate to Redis: " . $e->getMessage());
                }
            }
        }
        Log::info("Currency data queuing completed.");
    }
}

