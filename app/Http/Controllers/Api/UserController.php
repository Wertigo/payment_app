<?php

namespace App\Http\Controllers\Api;

use App\User,
    App\Wallet,
    App\Helpers\WalletHelper,
    App\Repositories\ExchangeRateRepository,
    App\Repositories\WalletOperationHistoryRepository,
    App\Repositories\UserRepository;
use Illuminate\Http\Request,
    Illuminate\Support\Facades\DB,
    Illuminate\Validation\Validator as ValidatorInstance;
use Validator;

class UserController extends ApiController
{
    /**
     * @var string
     */
    const ERROR_USER_HAVENT_ENOUGH_MONEY_FOR_TRANSFER = 'User don\'t have enough money for transfer';

    /**
     * @var string
     */
    const ERROR_SELF_TRANSFER_IS_SUPPORTED = 'Self transfer is not supported';

    /**
     * Create new User
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users|max:255',
            'country' => 'nullable|string',
            'city' => 'nullable|string',
            'currency' => 'nullable|string',
        ]);
        $this->sendErrorResponseIfValidatorFail($validator);
        $user = new User();
        $user->fill($request->all());
        $user->save();
        $this->createEmptyWalletForUser($user, $request->currency);

        return $this->response(['id' => $user->id]);
    }

    /**
     * @param ValidatorInstance $validator
     *
     * @return \Illuminate\Http\JsonResponse|bool
     */
    private function sendErrorResponseIfValidatorFail(ValidatorInstance $validator)
    {
        if ($validator->fails()) {
            return $this->response(['errors' => $validator->getMessageBag()->toArray()], false);
        }

        return true;
    }

    /**
     * @param User $user
     * @param string $currency
     */
    private function createEmptyWalletForUser(User $user, $currency)
    {
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->money = 0;
        $wallet->currency = $currency === null ? WalletHelper::CURRENCY_DEFAULT : $currency;
        $wallet->save();
    }

    /**
     * @param User $user
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMoneyAction(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'money' => 'required|numeric',
            'currency' => 'required|string',
        ]);
        $this->sendErrorResponseIfValidatorFail($validator);

        try {
            $userWallet = $user->wallet;
            $incomingExchangeRate = ExchangeRateRepository::findExchangeRateForDay($request->currency);
            $walletExchangeRate = ExchangeRateRepository::findExchangeRateForDay($userWallet->currency);
            $comingMoney = WalletHelper::convertCurrency($request->money, $incomingExchangeRate, $walletExchangeRate);
            WalletHelper::transferMoneyToWallet($userWallet, $comingMoney);
            WalletOperationHistoryRepository::createComingOperation($userWallet->id, $comingMoney);

            return $this->response([
                'currency' => $userWallet->currency,
                'user_money' => WalletHelper::formatUserMoney($userWallet->money),
            ]);
        } catch (\Exception $e) {
            return $this->response(['error' => $e->getMessage()], false);
        }
    }

    /**
     * @param User $user
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function transferMoneyAction(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'money' => 'required|numeric',
            'to_user_id' => 'required|numeric',
        ]);
        $this->sendErrorResponseIfValidatorFail($validator);
        $toUser = UserRepository::findById($request->to_user_id);

        if (!WalletHelper::isWalletHaveEnoughMoney($user->wallet, $request->money)) {
            return $this->response(['error' => self::ERROR_USER_HAVENT_ENOUGH_MONEY_FOR_TRANSFER], false);
        }

        if ($user->id == $toUser->id) {
            return $this->response(['error' => self::ERROR_SELF_TRANSFER_IS_SUPPORTED], false);
        }

        DB::beginTransaction();

        try {
            $debitMoney = WalletHelper::debitMoneyFromWallet($user->wallet, $request->money);
            $incomingExchangeRate = ExchangeRateRepository::findExchangeRateForDay($user->wallet->currency);
            $walletExchangeRate = ExchangeRateRepository::findExchangeRateForDay($toUser->wallet->currency);
            $money = WalletHelper::convertCurrency($request->money, $incomingExchangeRate, $walletExchangeRate);
            WalletHelper::transferMoneyToWallet($toUser->wallet, $money);
            WalletOperationHistoryRepository::createTransferOperation($toUser->wallet->id, $money, $user->wallet->id, $debitMoney);
            DB::commit();

            return $this->response([
                'user_id' => $request->to_user_id,
                'currency' => $toUser->wallet->currency,
                'user_money' => WalletHelper::formatUserMoney($toUser->wallet->money),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->response(['error' => $e->getMessage()], false);
        }
    }
}
