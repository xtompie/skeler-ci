<?php

class Out
{
    public static function done($msg = 'done')
    {
        self::success($msg);
    }

    public static function error($msg)
    {
        echo "\033[31m$msg\033[0m\n";
    }

    public static function info($msg)
    {
        echo "\033[33m$msg\033[0m\n";
    }

    public static function success($msg)
    {
        echo "\033[32m$msg\033[0m\n";
    }
}
