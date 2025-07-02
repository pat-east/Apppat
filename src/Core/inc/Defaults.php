<?php

define('__ABSPATH__', realpath(dirname(__FILE__) . '/../..'));

class Defaults {

    const VERSION = '0.0.1';
    const ABSPATH = __ABSPATH__;
    const LOGS_PATH = self::ABSPATH . '/.logs';
    const LOG_FILE = self::LOGS_PATH . '/log.txt';

    public function init(): void {
        if(!is_dir(self::LOGS_PATH)) {
            mkdir(self::LOGS_PATH);
        }
    }

}