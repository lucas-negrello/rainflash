<?php

namespace App\Enums;

use App\Contracts\TableEnumInterface;
use App\Traits\HasTableEnum;

enum WebhookDeliveryStatusEnum: int implements TableEnumInterface
{
    use HasTableEnum;

    case PENDING = 0;
    case SENT = 1;
    case FAILED = 2;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::SENT => 'Enviado',
            self::FAILED => 'Falha',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::SENT => 'green',
            self::FAILED => 'red',
        };
    }
}
