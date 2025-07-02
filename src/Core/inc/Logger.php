<?php

require_once('Defaults.php');

enum LogLevel {
    case Info;
    case Warning;
    case Error;
    case Critical;

    public function toString(): string
    {
        return match($this) {
            LogLevel::Info => 'INFO',
            LogLevel::Warning => 'WARN',
            LogLevel::Error => 'ERROR',
            LogLevel::Critical => 'CRIT'
        };
    }
}

class Logger {

    public static function Info(string $sender, string $fmt, mixed ...$values): void {
        self::Log($sender, LogLevel::Info, $fmt, $values);
    }

    public static function Log(string $sender, LogLevel $lvl, string $fmt, array $args = []): void {
        $ms = sprintf("[%s] %s: %s\r\n", $lvl->toString(), basename($sender), vsprintf($fmt, $args));
        error_log($ms);
        file_put_contents(Defaults::LOG_FILE, $ms, FILE_APPEND);
    }

    public function init() {

    }
}