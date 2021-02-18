<?php

class Lock
{
    protected static function path()
    {
        return Cwd::get() . '/.lock';
    }

    public static function on()
    {
        if (File::is(self::path())) {
            Err::invoke("Lock file '".self::path()."' exists");
        }
        File::touch(self::path());
        Out::info('Lock::on');
    }

    public static function off()
    {
        File::is(self::path()) && File::unlink(self::path());
        Out::info('Lock::off');
    }
}
