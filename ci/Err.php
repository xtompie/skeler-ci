<?php

class Err
{
    protected static $handler;

    public static function invoke($msg)
    {
        $break = false;
        if (self::$handler) {
            $break = (self::$handler)($msg);
        }
        if ($break) {
            return;
        }
        Out::error($msg);
        throw new RuntimeException($msg);
    }

    public static function on($callback)
    {
        self::$handler = $callback;
    }
}
