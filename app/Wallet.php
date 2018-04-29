<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Wallet
 *
 * @property int $id
 * @property int $user_id
 * @property string $currency
 * @property int $money
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wallet whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wallet whereMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wallet whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\User $user
 */
class Wallet extends Model
{
    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|User
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
