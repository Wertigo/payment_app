<?php

namespace App\Components\Report;

use App\User,
    App\WalletOperationHistory,
    App\Helpers\WalletHelper,
    App\Helpers\WalletOperationHistoryHelper,
    App\Repositories\UserRepository,
    App\Repositories\WalletOperationHistoryRepository;
use Spatie\ArrayToXml\ArrayToXml;

class XMLReport extends AbstractReport
{
    /**
     * @inheritdoc
     */
    public static function getReport($userName, $fromDate = null, $toDate = null)
    {
        $user = UserRepository::findByName($userName, true);
        $operations = WalletOperationHistoryRepository::findByWalletAndDates($user->wallet->id, $fromDate, $toDate);
        $array = [];

        foreach ($operations as $key => $operation) {
            $array['operation'][] = self::getOperationArray($operation, $user);
        }

        $xml = ArrayToXml::convert($array);
        $fileName = 'report.xml';
        $headers = [
            'Content-type' => 'text/xml',
            'Content-Disposition' => 'attachment; filename=' . $fileName,
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response($xml, 200, $headers);
    }

    /**
     * @inheritdoc
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
            'id' => $operation->id,
            'operation_name' => WalletOperationHistoryHelper::getOperationName($operation, $user->wallet->id),
            'from' => ($operation->from_wallet_id === null ? null : $operation->fromWallet->user->name),
            'to' => $operation->wallet->user->name,
            'currency' => $currency = $user->wallet->currency,
            'money' => $money,
            'created_at' => $operation->created_at,
        ];
    }
}