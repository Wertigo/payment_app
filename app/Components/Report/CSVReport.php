<?php

namespace App\Components\Report;

use App\WalletOperationHistory,
    App\User,
    App\Repositories\UserRepository,
    App\Repositories\WalletOperationHistoryRepository;

class CSVReport extends AbstractReport
{
    /**
     * @inheritdoc
     */
    public static function getReport($userName, $fromDate = null, $toDate = null)
    {
        $user = UserRepository::findByName($userName, true);
        $operations = WalletOperationHistoryRepository::findByWalletAndDates($user->wallet->id, $fromDate, $toDate);

        return self::export($operations, $user);
    }

    /**
     * @param WalletOperationHistory[] $operations
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    private static function export($operations, User $user)
    {
        $columns = ['Operation ID', 'Operation Type', 'From', 'To', 'Currency', 'Money', 'Date'];

        $callback = function () use ($operations, $columns, $user) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns, ';');

            foreach ($operations as $operation) {
                fputcsv($file, self::getOperationArray($operation, $user), ';');
            }

            fclose($file);
        };

        $fileName = 'report.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $fileName,
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->streamDownload($callback, $fileName, $headers);
    }
}