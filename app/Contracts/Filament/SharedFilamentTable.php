<?php

namespace App\Contracts\Filament;

interface SharedFilamentTable
{
    public static function getBase(array $extraFields = [], bool $includeRelationshipFields = false): array;

    public static function getFilters(array $extraFilters = []): array;
}
