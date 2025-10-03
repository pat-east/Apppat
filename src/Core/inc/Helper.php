<?php

class Helper {
    public static function IncludeOnce(string $path, bool $recursivly = false): void {
        self::__($path, $recursivly, function($file) { include_once($file); });
    }

    public static function Include(string $path, bool $recursivly = false): void {
        self::__($path, $recursivly, function($file) { include($file); });
    }

    public static function Require(string $path, bool $recursivly = false): void {
        self::__($path, $recursivly, function($file) { require($file); });
    }

    public static function RequireOnce(string $path, bool $recursivly = false): void {
        self::__($path, $recursivly, function($file) { require_once($file); });
    }

    static function __(string $path, bool $recursivly, Callable $handler): void {
        if(!$recursivly) {
            include_once($path);
        } else {
            if(is_dir($path)) {
                $filesWithinFolder = scandir($path);
                foreach($filesWithinFolder as $file) {
                    if(!str_starts_with($file, '.')) {
                        self::IncludeOnce($path . '/' . $file, true);
                    }
                }
            } else {
                $handler($path);
            }
        }
    }
}