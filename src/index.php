<?php

require_once('Bootstrapper.php');

$bootstrapper = new Bootstrapper();
$bootstrapper->boot();
Core::$Instance->run();