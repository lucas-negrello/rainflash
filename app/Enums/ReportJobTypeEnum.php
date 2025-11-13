<?php

namespace App\Enums;

enum ReportJobTypeEnum: int
{
    case CSV = 0;
    case PDF = 1;
    case XLSX = 2;

    public function label(): string
    {
        return match ($this) {
            self::CSV => 'CSV',
            self::PDF => 'PDF',
            self::XLSX => 'XLSX',
        };
    }

    public function extension(): string
    {
        return match ($this) {
            self::CSV => 'csv',
            self::PDF => 'pdf',
            self::XLSX => 'xlsx',
        };
    }
}
