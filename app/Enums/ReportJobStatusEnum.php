<?php

namespace App\Enums;

enum ReportJobStatusEnum: int
{
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
}
