<?php

namespace App\Contracts;

interface TableEnumInterface
{
    /**@return array<string>*/
    public static function labels(): array;

    /**@return string*/
    public function label(): string;
}
