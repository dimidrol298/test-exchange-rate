<?php

namespace App\Services;

use GuzzleHttp\Client;

class CurrencyService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getExchangeRateForDate($date)
    {
        $url = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=$date";

        $response = $this->client->get($url);

        return $this->parseExchangeRates($response->getBody());
    }

    public function parseExchangeRates($xmlContent)
    {
        $xml = simplexml_load_string($xmlContent);
        $exchangeRates = [];
        foreach ($xml->Valute as $valute) {
            $currencyCode = (string) $valute->CharCode;
            $exchangeRate = str_replace(',', '.', (string) $valute->Value);
            $exchangeRates[$currencyCode] = (float) $exchangeRate;
        }

        return $exchangeRates;
    }
}
