<?php

class Cwd
{
    protected static $cwd;

    public static function set($cwd)
    {
        self::$cwd = $cwd;
    }
    public static function get()
    {
        return self::$cwd;
    }
}
