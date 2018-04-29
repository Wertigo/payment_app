<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * App\User
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $country
 * @property string $city
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @property-read \App\Wallet $wallet
 */
class User extends Model
{
    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * @inheritdoc
     */
    protected $fillable = ['name', 'country', 'city'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|Wallet
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
}
