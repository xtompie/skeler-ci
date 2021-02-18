<?php

class Ssh
{
    public static function cmd($uri, $cmd)
    {
        return cmds($uri, [$cmd]);
    }

    public static function cmds($uri, $cmds)
    {
        // resolve user host and path
        $uri = self::uri($uri);

        // cd path
        array_unshift($cmds, "cd " . $uri['path']);

        // commands defined as arra change into string 
        foreach ($cmds as $k => $v) {
            $cmds[$k] = is_array($v) ? implode(' ', $v) : $v;
        }
        Exec::cmd('ssh '.$uri['user'].'@'.$uri['host'] . ' ' . escapeshellarg(implode(" && ", $cmds)));
    }

    protected static function uri($uri)
    {
        $matches = null;
        preg_match("/(?<user>[^@]+)@(?<host>[^:]+):(?<path>.*)/", $uri, $matches);

        $matches || Err::invoke('illegal uri format');
        substr($matches['path'], 0, 1) == '/' || Err::invoke('illegal uri format');
        strlen($matches['path']) > 1 || Err::invoke('illegal uri format');

        return $matches;
    }
}
