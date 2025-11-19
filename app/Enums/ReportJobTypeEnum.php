<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum ReportJobTypeEnum: int implements TableEnumInterface
{
    use HasTableEnum;

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

    public function color(): string
    {
        return match ($this) {
            self::CSV => 'green',
            self::PDF => 'red',
            self::XLSX => 'blue',
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
