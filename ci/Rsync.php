<?php

class Rsync
{
    public static function run($source, $dest)
    {
        Exec::cmd([
            'rsync',
            '-rtO --links --delete',
            escapeshellarg($source),
            escapeshellarg($dest),
        ]);
    }
}
