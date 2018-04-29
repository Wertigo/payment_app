<?php

namespace App\Helpers;

use App\ExchangeRate,
    App\Wallet;

class WalletHelper
{
    /**
     * @var string
     */
    const CURRENCY_DEFAULT = 'USD';

    /**
     * @var int
     */
    const CURRENCY_PRECISION_RATIO = 1000000;

    /**
     * @param double $money
     * @param ExchangeRate $fromExchangeRate
     * @param ExchangeRate $toExchangeRate
     * @param bool $currencyWithPrecision
     *
     * @return float
     */
    public static function convertCurrency($money, ExchangeRate $fromExchangeRate, ExchangeRate $toExchangeRate, $currencyWithPrecision = false)
    {
        if ($currencyWithPrecision) {
            $usdMoney = $money * ($fromExchangeRate->rate / self::CURRENCY_PRECISION_RATIO);
        } else {
            $usdMoney = $money * self::CURRENCY_PRECISION_RATIO / ($fromExchangeRate->rate / self::CURRENCY_PRECISION_RATIO);
        }

        return $usdMoney * ($toExchangeRate->rate / self::CURRENCY_PRECISION_RATIO);
    }

    /**
     * @param $money
     * @return float
     */
    public static function formatUserMoney($money)
    {
        return $money / self::CURRENCY_PRECISION_RATIO;
    }

    /**
     * @param Wallet $wallet
     * @param double $money
     * @return bool
     */
    public static function isWalletHaveEnoughMoney(Wallet $wallet, $money)
    {
        return $wallet->money >= $money * self::CURRENCY_PRECISION_RATIO;
    }

    /**
     * @param Wallet $wallet
     * @param double $money
     *
     * @return double
     */
    public static function debitMoneyFromWallet(Wallet $wallet, $money)
    {
        $debitMoney = $money * self::CURRENCY_PRECISION_RATIO;
        $wallet->money -= $debitMoney;
        $wallet->save();

        return $debitMoney;
    }

    /**
     * @param Wallet $wallet
     * @param double $money
     */
    public static function transferMoneyToWallet(Wallet $wallet, $money)
    {
        $wallet->money += $money;
        $wallet->save();
    }
}