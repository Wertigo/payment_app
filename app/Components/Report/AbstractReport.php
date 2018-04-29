<?php

namespace App\Components\Report;

use App\User,
    App\WalletOperationHistory,
    App\Helpers\WalletHelper,
    App\Helpers\WalletOperationHistoryHelper;

abstract class AbstractReport
{
    /**
     * @param string $userName
     * @param string|null $fromDate
     * @param string|null $toDate
     *
     * @return mixed
     */
    abstract public static function getReport($userName, $fromDate = null, $toDate = null);

    /**
     * @param WalletOperationHistory $operation
     * @param User $user
     *
     * @return array
     */
    protected static function getOperationArray(WalletOperationHistory $operation, User $user)
    {
        $money = WalletHelper::formatUserMoney($operation->money);

        if ($operation->type != WalletOperationHistoryHelper::TYPE_COMING) {
            if ($operation->wallet_id != $user->wallet->id) {
                $money = WalletHelper::formatUserMoney($operation->from_money);
            }
        }

        return [
            $operation->id,
            WalletOperationHistoryHelper::getOperationName($operation, $user->wallet->id),
            ($operation->from_wallet_id === null ? null : $operation->fromWallet->user->name),
            $operation->wallet->user->name,
            $currency = $user->wallet->currency,
            $money,
            $operation->created_at,
        ];
    }
}