<?php

namespace Link1515\JobNotification\Utils;

class LogUtils
{
    public static function log(string $message): void
    {
        echo self::getDateTime() . ' | ' . $message . PHP_EOL;
    }

    private static function getDateTime(): string
    {
        return date('Y-m-d H:i:s');
    }
}
