<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExchangeRateService {

    public static function convertCurrency($amount, $to)
    {
        $API_KEY  = config('exchange.key');
        $BASE_URL = config('exchange.base');
        $amount   = str_replace(',', '', $amount);

        $url = "$BASE_URL/$API_KEY/pair/USD/$to/$amount";
        $response = Http::get($url)->json();

        if (!array_key_exists('result', $response) || $response['result'] != 'success')
            return 'error';

        return $response['conversion_result'];

    }

}