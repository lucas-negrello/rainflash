<?php

namespace App\Traits;

use App\Contracts\TableEnumInterface;
use InvalidArgumentException;

/**
 * Trait HasTableEnum
 * Provides default implementations for TableEnumInterface static helpers.
 * Assumes enum implements label() and color() instance methods.
 */
trait HasTableEnum
{
    /** @return array<int|string,string> */
    public static function labels(): array
    {
        /** @var TableEnumInterface&\BackedEnum $case */
        $out = [];
        foreach (static::cases() as $case) {
            $out[$case->value] = $case->label();
        }
        return $out;
    }

    /** @return array<int|string,string> */
    public static function dropdownOptions(): array
    {
        return static::labels();
    }

    /** @return array<int|string,string> */
    public static function colors(): array
    {
        /** @var TableEnumInterface&\BackedEnum $case */
        $out = [];
        foreach (static::cases() as $case) {
            // color() required by interface; still guard just in case.
            $out[$case->value] = method_exists($case, 'color') ? $case->color() : 'gray';
        }
        return $out;
    }

    /**
     * @param int|string $value
     * @return static
     */
    public static function fromValue(int|string $value): static
    {
        foreach (static::cases() as $enum) {
            if ($enum->value === $value) {
                /** @var static $enum */
                return $enum;
            }
        }
        throw new InvalidArgumentException(static::class . " value '$value' is invalid.");
    }

    /** @return array<int|string,string> */
    public static function toSelectOptions(): array
    {
        return static::labels();
    }

    /**
     * Generic mapper to transform cases into arbitrary shape.
     * @template T
     * @param callable(self):T $callback
     * @return array<T>
     */
    public static function map(callable $callback): array
    {
        $out = [];
        foreach (static::cases() as $case) {
            $out[] = $callback($case);
        }
        return $out;
    }
}
