<?php

require "common.php";

lock();
$test = run("git rev-parse HEAD");
success($test);
unlock();

// gitmark('')
// cicd-build
// cicd-build-error
// cicd-deploy-prod
// cicd-deploy-dev