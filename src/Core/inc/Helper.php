<?php

class InputHelper {


    /**
     * @param array<string, string> $args
     * @param array<string, string> $defaults
     * @param bool $sanitizeInputs
     * @return array<string, string>
     */
    public static function ParseArgs(array $args, array $defaults, bool $sanitizeInputs = true): array {
        $arr = array_merge($defaults, $args);

        if($sanitizeInputs) {
            foreach($arr as $key => $val) {
                $arr[$key] = Sanitize::text($val);
            }
        }

        return $arr;
    }
}

class Helper {

    public static function GetDerivingClasses($baseClassName) {
        $classes = [];
        foreach(get_declared_classes() as $class) {
            if(is_subclass_of($class, $baseClassName)) {
                $classes[] = $class;
            }
        }

        return $classes;
    }

    public static function CreateUuidV4() : string {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
        $hex = bin2hex($bytes);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split($hex, 4));
    }

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
            if(is_dir($path)) {
                $filesWithinFolder = scandir($path);
                foreach($filesWithinFolder as $file) {
                    if(!str_starts_with($file, '.')) {
                        $handler($path);
                    }
                }
            } else {
                $handler($path);
            }
        } else {
            if(is_dir($path)) {
                $filesWithinFolder = scandir($path);
                foreach($filesWithinFolder as $file) {
                    if(!str_starts_with($file, '.')) {
                        self::IncludeOnce($path . '/' . $file, $recursivly);
                    }
                }
            } else {
                $handler($path);
            }
        }
    }
}