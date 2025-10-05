<?php

include_once('SetupTest.php');

class SetupTestRepository {

    /** @var SetupTest[] */
    var array $tests;

    public function __construct() {

    }

    /**
     * @return SetupTest[]
     */
    public function getTests() : array {
        $tests = [];

        // Tests should be in correct order, so we do these manually
        $tests[] = new SetupTest(
            'PHP',
            'PHP is installed',
            'Version ' . phpversion(),
            '',
            function () {
                return true;
            });
        $tests[] = new SetupTest(
            'MySQL-Extension',
            'Tests MySQL extensions are installed',
            'MySQL extensions installed',
            'MySQL extensions not found. Please install MySQL Improved exteions. See: https://www.php.net/manual/en/book.mysqli.php',
            function () {
                return class_exists('mysqli');
            });
//        $tests[] = new SetupTest(
//            'Failing test',
//            'Test will always fail',
//            '',
//            'Remove this setup test from SetupTestRepository',
//            function () {
//                return false;
//            });

        return $tests;
    }

    public function init() : void {

    }
}