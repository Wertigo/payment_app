<?php

namespace App\Http\Controllers\Api;

use App\ExchangeRate,
    App\Helpers\WalletHelper;
use Illuminate\Http\Request;
use Validator;

class ExchangeRateController extends ApiController
{
    /**
     * Create new Exchange rate
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required|string|max:255|unique_with:exchange_rates,date',
            'rate' => 'required|numeric|min:0',
            'date' => 'required|date_format:"Y-m-d"|unique_with:exchange_rates,currency',
        ]);

        if ($validator->fails()) {
            return $this->response(['errors' => $validator->getMessageBag()->toArray()], false);
        }

        $exchangeRate = new ExchangeRate();
        $exchangeRate->fill($request->all());
        $exchangeRate->rate = floor($request->rate * WalletHelper::CURRENCY_PRECISION_RATIO);
        $exchangeRate->save();

        return $this->response(['id' => $exchangeRate->id]);
    }
}