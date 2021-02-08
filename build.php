<?php

require "common.php";

is_dir('build') || run('mkdir build');

cd('build', function() {
    run(['echo', e(config('repository'))]);
});

done();
// git rev-parse --short HEAD