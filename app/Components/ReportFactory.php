<?php

namespace App\Components;

use App\Components\Report\AbstractReport,
    App\Components\Report\CSVReport,
    App\Components\Report\XMLReport;

class ReportFactory
{
    /**
     * @var int
     */
    const TYPE_CSV = 1;

    /**
     * @var int
     */
    const TYPE_XML = 2;

    /**
     * @param string $type
     *
     * @throws \Exception
     *
     * @return AbstractReport
     */
    public static function getReportObject($type)
    {
        switch ($type) {
            case self::TYPE_CSV:
                return new CSVReport();
            case self::TYPE_XML:
                return new XMLReport();
            default: throw new \Exception('Unknown report type');
        }
    }
}