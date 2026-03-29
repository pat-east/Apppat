<?php

require_once('Core/Bootstrapper.php');

$bootstrapper = new Bootstrapper();
$core = $bootstrapper->bootCli();
exit($core->runCli($_SERVER['argv'] ?? []));
