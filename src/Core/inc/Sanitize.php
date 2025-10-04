<?php

class Sanitize {

    public static function text(string $string) : string {
        return self::__text($string);
    }

    public static function dictOfString(array $strings) : array {
        foreach($strings as $key => $val) {
            $strings[$key] = self::__text($val);
        }

        return $strings;
    }

    private static function __text(string $string) : string {
        $string = trim($string);
        $string = strip_tags($string);
        $string = htmlspecialchars($string);
        return $string;
    }
}