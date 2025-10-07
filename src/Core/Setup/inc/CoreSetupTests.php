<?php

class CoreSetupTests extends SetupTestRepository {
    /**
     * @return SetupTest[]
     */
    public function getTests() : array {
        $tests = [];

        // Tests should be in correct order, so we do these manually
//        $tests[] = new SetupTest(
//            'Failing test',
//            'Test will always fail',
//            '',
//            'Remove this setup test from SetupTestRepository',
//            function () {
//                return false;
//            });
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
        $tests[] = new SetupTest(
            'HTTPS is enabled or using dev environments',
            'Tests server is responding encrypted using HTTPS. In development environment this test always passes.',
            'HTTPS is enabled or using dev environment',
            'HTTPS is disabled',
            function () {
                if(Config::$Environment->isDevEnvironment()) { return true; }
                return
                    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                    || $_SERVER['SERVER_PORT'] == 443;
            });
        $tests[] = new SetupTest(
            '.env file exists',
            'Tests .env file exists',
            '.env file exists at ' . Defaults::DOTENVPATH,
            '.env file does not exist at ' . Defaults::DOTENVPATH,
            function () {
                return file_exists(Defaults::DOTENVPATH);
            });
        $tests[] = new SetupTest(
            'Nonce payload is configured',
            'Tests NONCE_PAYLOAD parameter is defined',
            'NONCE_PAYLOAD is set',
            'NONCE_PAYLOAD is not set or empty in ' . Defaults::DOTENVPATH,
            function () {
                return Config::$Environment->noncePayload != '';
            });
        $tests[] = new SetupTest(
            'MySQL configured',
            'Tests MYSQL_HOST parameter is defined',
            'MYSQL_HOST is set',
            'MYSQL_HOST is not set or empty in ' . Defaults::DOTENVPATH,
            function () {
                return Config::$MySql->host != '';
            });
        $tests[] = new SetupTest(
            'MySQL configured',
            'Tests MYSQL_DATABASE parameter is defined',
            'MYSQL_DATABASE is set',
            'MYSQL_DATABASE is not set or empty in ' . Defaults::DOTENVPATH,
            function () {
                return Config::$MySql->database != '';
            });
        $tests[] = new SetupTest(
            'MySQL configured',
            'Tests MYSQL_USERNAME parameter is defined',
            'MYSQL_USERNAME is set',
            'MYSQL_USERNAME is not set or empty in ' . Defaults::DOTENVPATH,
            function () {
                return Config::$MySql->username != '';
            });
        $tests[] = new SetupTest(
            'MySQL configured',
            'Tests MYSQL_PASSWORD parameter is defined',
            'MYSQL_PASSWORD is set',
            'MYSQL_PASSWORD is not set or empty in ' . Defaults::DOTENVPATH,
            function () {
                return Config::$MySql->password != '';
            });
        $tests[] = new SetupTest(
            'MySQL configured',
            'Tests MYSQL_TABLE_PREFIX parameter is defined',
            'MYSQL_TABLE_PREFIX is set',
            'MYSQL_TABLE_PREFIX is not set or empty in ' . Defaults::DOTENVPATH,
            function () {
                return Config::$MySql->tablePrefix != '';
            });

        $tests[] = new SetupTest(
            'MySQL connection test',
            'Tests connecting to MySQL server',
            'Connection established',
            'Could not connect to server.',
            function () {
                try {
                    $mysqli = new mysqli(Config::$MySql->host, Config::$MySql->username, Config::$MySql->password, Config::$MySql->database);
                    $mysqli->close();
                    return true;
                } catch(Exception $e) {
                    if (mysqli_connect_errno()) {
                        Logger::Error(__FILE__, "Failed to connect to MySQL: " . mysqli_connect_error());
                        return false;
                    }
                    return false;
                }

            });

        return $tests;
    }
}