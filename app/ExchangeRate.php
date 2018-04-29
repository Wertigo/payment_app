<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ExchangeRate
 *
 * @property int $id
 * @property string $currency
 * @property int $rate
 * @property string $date
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExchangeRate whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExchangeRate whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExchangeRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExchangeRate whereRate($value)
 * @mixin \Eloquent
 */
class ExchangeRate extends Model
{
    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * @inheritdoc
     */
    protected $fillable = ['currency', 'date'];
}
