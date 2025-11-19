<?php

namespace App\Contracts;

interface TableEnumInterface
{
    // Static class methods

    /**
     * Returns a mapping of backed enum values to their human labels.
     * @return array<int|string,string>
     */
    public static function labels(): array;

    /**
     * Alias of labels() kept for backwards compatibility with older code expecting dropdownOptions().
     * Prefer using labels() or toSelectOptions().
     * @deprecated Use labels() instead.
     * @return array<int|string,string>
     */
    public static function dropdownOptions(): array;

    /**
     * Returns a mapping of backed enum values to their color (e.g. for UI badges).
     * If an enum case does not define a specific color, implementations may fallback to a default.
     * @return array<int|string,string>
     */
    public static function colors(): array;

    /**
     * Attempts to resolve an enum case from its backed value.
     * Should throw an \InvalidArgumentException if value is invalid.
     * @param int|string $value
     * @return static
     */
    public static function fromValue(int|string $value): static;

    /**
     * Returns array suitable for select inputs (value=>label). Provided for semantic clarity.
     * @return array<int|string,string>
     */
    public static function toSelectOptions(): array;

    // Specific instance methods

    /** @return string */
    public function label(): string;

    /** @return string */
    public function color(): string;
}
