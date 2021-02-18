<?php

require "ci/ci.php";

Task::test();
Task::build();
Task::devCandidate();
Task::devDeploy();
