<?php

namespace App\Enums;

enum WebhookDeliveryStatusEnum: int
{
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
}
