<?php

namespace App\Core;

trait ValueObjectEnum
{
    use ValueObject;

    public static function validate($value): bool
    {
        return in_array($value, static::values(), true);
    }

    public static function values(): array
    {
        return array_values(static::keyValues());
    }

    public static function keyValues(): array
    {
        $class = new \ReflectionClass(static::class);
        return $class->getConstants();
    }

    public static function excludeValues(array $excludes): array
    {
        return array_values(array_diff(static::values(), $excludes));
    }
}
