<?php

include_once(Defaults::THEMESPATH . '/SetupTheme/SetupTheme.php');
include_once('inc/SetupTestRepository.php');
include_once('SetupController.php');


class Setup {

    var Core $core;

    public function __construct(Core $core) {
        $this->core = $core;
    }

    public function init() : void {
        Helper::IncludeOnce(Defaults::SETUPPATH . '/Views', true);
        Helper::IncludeOnce(Defaults::SETUPPATH . '/SetupTests', true);
    }

    public function isSetUp() : bool {
        $tests = new SetupTestRepository()->getTests();
        foreach($tests as $test) {
            if(!$test->testPassed()) {
                return false;
            }
        }
        return true;
    }

    public function runSetUp() : void {
        // Redirect to /setup if not set up yet and user is not visiting /setup
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            if($_SERVER['REQUEST_URI'] != '/setup') {
                header('Location: /setup');
                exit;
            }
        }

        // Load SetupTheme
        $this->core->themeManager->useTheme(new SetupTheme());
    }
}