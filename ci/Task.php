<?php

class Task
{

    public static function lockOff()
    {
        Lock::off();
    }

    public static function test()
    {
        Lock::on();
        $sha = Git::hot('test');
        Nav::go('test');
        Exec::cmd('composer install');
        Exec::cmd('php artisan test');
        Git::mark('ci-test', $sha);
        Nav::back();
        Lock::off();
        Out::success("$sha test passed!");
    }

    public static function build()
    {
        Lock::on();
        # checking if there is something new to build
        $sha = Git::hot('build');
        Dir::ensure('artifacts/build');
        Nav::go('artifacts/build');
        if (Artifact::is($sha)) {
            Out::error("The artifact $sha is already built");
            Lock::off();
            exit(1);
        }
        Nav::back();
        # build $sha
        Out::info("building $sha...");
        Nav::go('build');
        Exec::cmd('composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist');
        Exec::cmd('npm run ci');
        Exec::cmd('npm run build');
        Exec::cmd('npm run clean');
        Exec::cmd([
            "tar", 
            "-czf", 
            "../artifacts/build/$sha.tar.gz", 
            "--exclude=*.git", 
            "--exclude=phpunit.xml", 
            "--exclude=README.md", 
            "--exclude=server.php", 
            "--exclude=tests", 
            "--exclude=server", 
            "--exclude=storage", 
            "*"
        ]);
        Git::mark('ci-build', $sha);
        Nav::back();
        Dir::leaveLatest('artifacts/build', 5);
        Lock::off();
        Out::success("$sha builded!");
    }

    public static function devCandidate()
    {
        self::candidate('dev', 'artifacts/build', 'artifacts/dev-candidate');
    }

    public static function prodCandidate()
    {
        self::candidate('prod', 'artifacts/dev-deploy', 'artifacts/prod-candidate');
    }

    protected static function candidate($stage, $src, $dest)
    {
        Dir::ensure($src);
        Dir::ensure($dest);
        $sha = Artifact::latest($src);
        if (Artifact::boostable($sha, $src, $dest)) {
            Out::error("$sha already candidated to $stage");
            exit(1);
        }
        Lock::on();
        Artifact::boost($sha, $src, $dest);
        Git::mark("ci-$stage-candidate", $sha);
        Lock::off();
        Out::success("$sha candidated to dev");
    }

    public static function devDeploy()
    {
        self::deploy('dev');
    }

    public static function prodDeploy()
    {
        self::deploy('prod');
    }

    protected static function deploy($stage)
    {
        Lock::on();
        $src = "artifacts/$stage-candidate";
        $dest = "artifacts/$stage-deploy";
        Dir::ensure($src);
        Dir::ensure($dest);
        $sha = Artifact::latest($src);
        # already deployed?
        if (Artifact::boostable($sha, $src, $dest)) {
            Out::error("$sha already deployed to $stage");
            Lock::off();
            exit(1);
        }
        $target = Config::get($stage);
        Exec::cmd([
            'scp',
            "$src/$sha.tar.gz",
            "$target/releases/$sha.tar.gz",
        ]);
        Ssh::cmds($target, [
            'cd releases',
            "mkdir $sha",
            "cd ..",
            "tar -xzf releases/$sha.tar.gz -C releases/$sha",
            "rm -f releases/$sha.tar.gz",
            'cd current',
            'php artisan down',
            'cd ..',
            "rsync -rtO --links --delete releases/$sha/ current",
            'cd current',
            'ln -s ../.env .env',
            'ln -s ../shared/storage',
            'php artisan migrate --force --no-interaction',
            'php artisan config:cache',
            'php artisan route:cache',
            'php artisan view:cache',
            'php artisan up',
            'cd ..',
            'cd releases && ls -t -1 | tail -n +6 | xargs rm -rf',
        ]);
        Artifact::boost($sha, $src, $dest);
        Git::mark("ci-$stage-deploy", $sha);
        Lock::off();
        Out::success("$sha deployed to $stage");        
    }

}
