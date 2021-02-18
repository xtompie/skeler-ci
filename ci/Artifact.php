<?php

class Artifact
{
    public static function is($sha)
    {
        return is_file("$sha.tar.gz");
    }

    public static function latest($path = null)
    {
        if ($path !== null) {
            Nav::go($path);
        }
        $out = trim(Cmd::make("ls -t | head -n1", false, false)->__invoke()->out());
        list($sha) = explode(".", $out);
        self::assert($sha);
        if ($path !== null) {
            Nav::back();
        }
        return $sha;
    }

    public static function boostable($sha, $source, $dest)
    {
        Dir::ensure($source);
        Dir::ensure($dest);

        Nav::go($source);
        $sourceExists = File::is($sha);
        Nav::back();
        if (!$sourceExists) {
            return false;
        }

        Nav::go($dest);
        $destExists = File::is($sha);
        Nav::back();
        if ($destExists) {
            return false;
        }

        return true;
    }

    public static function boost($sha, $source, $dest)
    {
        Nav::go($dest);
        Dir::nuke();
        Nav::back();
        
        File::copy("$source/$sha.tar.gz", "$dest/$sha.tar.gz");
    }

    public static function assert($sha)
    {
        File::is("$sha.tar.gz") || Err::invoke("No artifact $sha");
    }
}
