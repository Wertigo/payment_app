<?php

namespace App\Repositories;

use App\ExchangeRate,
    App\Helpers\WalletHelper;

class ExchangeRateRepository
{
    /**
     * @param string $currency
     * @param string $date
     *
     * @return \Illuminate\Database\Eloquent\Model|ExchangeRate
     */
    public static function findExchangeRateForDay($currency, $date = 'now')
    {
        if ($currency === WalletHelper::CURRENCY_DEFAULT) {
            return self::getExchangeRateForDefaultCurrency($date);
        }

        return ExchangeRate::where([
            ['date', '=', date('Y-m-d', strtotime($date))],
            ['currency', '=', $currency],
        ])->firstOrFail();
    }

    /**
     * @param string $date
     * @return ExchangeRate
     */
    private static function getExchangeRateForDefaultCurrency($date = 'now')
    {
        $exchangeRate = new ExchangeRate();
        $exchangeRate->currency = WalletHelper::CURRENCY_DEFAULT;
        $exchangeRate->rate = WalletHelper::CURRENCY_PRECISION_RATIO;
        $exchangeRate->date = date('Y-m-d', strtotime($date));

        return $exchangeRate;
    }
}