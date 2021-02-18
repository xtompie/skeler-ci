<?php

class Config
{
    protected static $config;

    public static function get($var)
    {
        if (!self::is($var)) {
            Err::invoke("config variable '$var' not defined");
        }
        return self::raw()[$var];
    }
    
    public static function raw()
    {
        if (self::$config === null) {
            self::$config = require 'config.php';
        }
        return self::$config;
    }

    public static function is($var)
    {
        return array_key_exists($var, self::raw());
    }
}
