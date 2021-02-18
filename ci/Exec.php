<?php

class Exec
{
    public static function cmd($cmd)
    {
        $cmd = is_array($cmd) ?  implode(' ', $cmd) : $cmd;
        Out::info("Exec::cmd | $cmd");
        $status = null;
        $return = trim(system($cmd, $status));
        if ($status !== 0) {
            Err::invoke("$cmd faild!");
        }
        return $return;
    }

    public static function cmds($cmds)
    {
        // @TODO
    }

}
