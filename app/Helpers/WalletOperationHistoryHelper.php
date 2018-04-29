<?php

namespace App\Helpers;

use App\WalletOperationHistory;

class WalletOperationHistoryHelper
{
    /**
     * @var int
     */
    const TYPE_COMING = 1;

    /**
     * @var int
     */
    const TYPE_TRANSFER = 2;

    /**
     * @var string
     */
    const TYPE_COMING_NAME = 'Coming operation';

    /**
     * @var string
     */
    const TYPE_TRANSFER_COMING_NAME = 'Transfer operation: coming';

    /**
     * @var string
     */
    const TYPE_TRANSFER_DEBIT_NAME = 'Transfer operation: debit';

    /**
     * @var string
     */
    const TYPE_SELF_TRANSFER = 'Self transfer';

    /**
     * @param WalletOperationHistory $operation
     *
     * @return string
     */
    public static function getOperationName(WalletOperationHistory $operation, $userWalletId)
    {
        switch ($operation->type) {
            case self::TYPE_COMING:
                return self::TYPE_COMING_NAME;
            case self::TYPE_TRANSFER: {
                if ($operation->wallet_id != $userWalletId && $operation->from_wallet_id == $userWalletId) {
                    return self::TYPE_TRANSFER_DEBIT_NAME;
                }

                return self::TYPE_TRANSFER_COMING_NAME;
            }
            default:
                return 'Unknown operation name';
        }
    }
}