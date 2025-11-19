<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum ReportJobStatusEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case PENDING = 0;
    case PROCESSING = 1;
    case DONE = 2;
    case FAILED = 3;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::PROCESSING => 'Processando',
            self::DONE => 'Concluído',
            self::FAILED => 'Falha',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::PROCESSING => 'blue',
            self::DONE => 'green',
            self::FAILED => 'red',
        };
    }
}
