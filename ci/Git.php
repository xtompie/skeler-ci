<?php

class Git
{
    public static function ensure($path)
    {
        Out::info("Git::ensure | $path");
        Dir::is($path) || Exec::cmd([
            'git', 
            'clone', 
            escapeshellarg(Config::get('repository')), 
            $path
        ]);
    }

    public static function sha()
    {
        return trim(Cmd::make("git rev-parse HEAD", false, false)->__invoke()->out());
    }

    public static function mark($tag, $sha = null)
    {
        Out::info("Git::mark | $tag $sha");
        Nav::root();
        Git::ensure('mark');
        Nav::go('mark');
        Git::pull();

        // set tag and push
        $cmd = ["git", "tag", "-f", $tag];
        if ($sha != null) {
            $cmd[] = $sha;
        }
        Exec::cmd($cmd);
        Exec::cmd(["git", "push", "origin", $tag, "-f"]);

        Nav::back();
        Nav::back();
    }
    
    public static function pull()
    {
        Exec::cmd(['git', 'pull', 'origin', 'master']);
    }

    public static function hot($dir)
    {
        Git::ensure($dir);
        Nav::go($dir);
        Git::pull();
        $sha = Git::sha();
        Nav::back();
        return $sha;
    }
}
