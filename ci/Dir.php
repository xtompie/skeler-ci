<?php

class Dir
{
    public static function is($path)
    {
        return is_dir($path);
    }

    public static function assert($path)
    {
        self::is($path) || Err::invoke("directory '$path' not exists");
    }

    public static function ensure($path)
    {
        self::is($path) || mkdir($path, 0777, true);
    }

    public static function nuke($dir = null)
    {
        self::leaveLatest($dir, 0);
    }

    public static function leaveLatest($dir = null, $leave = 5)
    {
        if ($dir !== null) {
            Nav::go($dir);
        }
        $n = 1 + $leave;
        Exec::cmd("ls -t -1 | tail -n +$n | xargs rm -rf");
        if ($dir !== null) {
            Nav::back();
        }
    }
}
