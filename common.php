<?php

function cd($dir, $callback)
{
    $cwd = getcwd();
    chdir($dir) || error("cd error | $dir");
    info("cd | $dir");
    $callback();
    chdir($cwd) || error("cd return error | $cwd");
    info("cd return | $cwd");
}

function config($var)
{
    static $config = null;
    if ($config === null) {
        $config = require 'config.php';
    }
    if (!isset($config[$var]) || trim(strlen($config[$var])) == 0) {
        error("config error | variable '$var' not defined");
    }
    return $config[$var];
}

function done($msg = 'done')
{
    success($msg);
}

function e($arg)
{
    return escapeshellarg($arg);
}

function error($msg)
{
    echo "\033[31m$msg\033[0m\n";
    exit(1);
}

function info($msg)
{
    echo "\033[33m$msg\033[0m\n";
}

function lock()
{
    if (is_file('.lock')) {
        error("lock error | lock file '.lock' exists");
    }
    touch('.lock');
}

function run($command)
{
    $command = is_array($command) ?  implode(' ', $command) : $command;
    info("run | $command");
    $status = null;
    system($command, $status);
    if ($status !== 0) {
        error("run error | $command");
    }
}

function success($msg)
{
    echo "\033[32m$msg\033[0m\n";
}

function unlock()
{
    unlink('.lock');
}

