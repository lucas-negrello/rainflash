<?php

namespace App\Contracts\Filament;

interface SharedFilamentSchema
{
    public static function getBase(bool $useRelationshipFields = false): array;
}
