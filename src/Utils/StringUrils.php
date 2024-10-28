<?php

namespace Link1515\JobNotification\Utils;

class StringUrils
{
    public static function stringContain(string $string, string $needle): string
    {
        return str_contains($string, $needle);
    }

    public static function stringNotContain(string $string, string $needle): string
    {
        return !str_contains($string, $needle);
    }

    public static function stringContainAny(string $string, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($string, $needle)) {
                return true;
            }
        }
        return false;
    }

    public static function stringContainNone(string $string, array $needles): bool
    {
        return !self::stringContainAny($string, $needles);
    }
}
