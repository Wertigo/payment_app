<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\WalletOperationHistory
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $wallet_id
 * @property int $type
 * @property int|null $from_wallet_id
 * @property string $from_currency
 * @property int $money
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WalletOperationHistory whereFromCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WalletOperationHistory whereFromWalletId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WalletOperationHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WalletOperationHistory whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WalletOperationHistory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WalletOperationHistory whereWalletId($value)
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WalletOperationHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WalletOperationHistory whereUpdatedAt($value)
 * @property-read \App\Wallet $fromWallet
 * @property-read \App\Wallet $wallet
 * @property int $from_money
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WalletOperationHistory whereFromMoney($value)
 */
class WalletOperationHistory extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|Wallet
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'id', 'wallet_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|Wallet
     */
    public function fromWallet()
    {
        return $this->hasOne(Wallet::class, 'id', 'from_wallet_id');
    }
}
