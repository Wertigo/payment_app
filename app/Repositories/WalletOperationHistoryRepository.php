<?php

namespace App\Repositories;

use App\WalletOperationHistory,
    App\Helpers\WalletOperationHistoryHelper;
use Illuminate\Database\Query\Builder;

class WalletOperationHistoryRepository
{
    /**
     * @param int $walletId
     * @param int $money
     */
    public static function createComingOperation($walletId, $money)
    {
        $walletOperationHistory = new WalletOperationHistory();
        $walletOperationHistory->wallet_id = $walletId;
        $walletOperationHistory->money = $money;
        $walletOperationHistory->type = WalletOperationHistoryHelper::TYPE_COMING;
        $walletOperationHistory->save();
    }

    /**
     * @param int $walletId
     * @param int $money
     * @param int $fromWalletId
     */
    public static function createTransferOperation($walletId, $money, $fromWalletId, $fromMoney)
    {
        $walletOperationHistory = new WalletOperationHistory();
        $walletOperationHistory->wallet_id = $walletId;
        $walletOperationHistory->money = $money;
        $walletOperationHistory->type = WalletOperationHistoryHelper::TYPE_TRANSFER;
        $walletOperationHistory->from_wallet_id = $fromWalletId;
        $walletOperationHistory->from_money = $fromMoney;
        $walletOperationHistory->save();
    }

    /**
     * @param int $walletId
     * @param string|null $startDate
     * @param string|null $endDate
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|WalletOperationHistory|WalletOperationHistory[]
     */
    public static function findByWalletAndDates($walletId, $startDate = null, $endDate = null)
    {
        $where = [
            ['wallet_id', '=', $walletId],
            ['from_wallet_id', '=', $walletId, 'OR'],
        ];

        if ($startDate !== null) {
            $where[] = ['created_at', '>=', $startDate . ' 00:00:00'];
        }

        if ($endDate !== null) {
            $where[] = ['created_at', '<=', $endDate . ' 23:59:59'];
        }

        return WalletOperationHistory::where($where)->get();
    }
}