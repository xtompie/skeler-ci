<?php

class Nav
{
    protected static $stack = [];

    public static function go($dir)
    {
        Out::info("Nav::go | cd $dir");
        $cwd = getcwd();
        Dir::is($dir) || Err::invoke("directory $dir not found");
        chdir($dir) || Err::invoke("chdir($dir) faild");
        self::$stack[] = $cwd;
    }

    public static function back()
    {
        $dir = array_pop(self::$stack);
        Out::info("Nav::back | cd $dir");
        Dir::is($dir) || Err::invoke("directory $dir not found");
        chdir($dir) || Err::invoke("chdir($dir) faild");
    }

    public static function root()
    {
        self::go(Cwd::get());
    }

}
