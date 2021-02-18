<?php

class File
{
    public static function is($path)
    {
        return is_file($path);
    }

    public static function assert($path)
    {
        self::is($path) || Err::invoke("file '$path' not exists");
    }

    public static function touch($path)
    {
        touch($path);
    }
    public static function unlink($path)
    {
        unlink($path);
    }

    public static function copy($src, $dest)
    {
        copy($src, $dest) || Err::invoke("Copy $src to $dest faild!");
    }
}
