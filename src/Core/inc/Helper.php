<?php

class Helper {
    public static function IncludeOnce($path) {
        foreach (glob($path) as $filename)
        {
            include_once ($filename);
        }
    }

    public static function Include($path) {
        foreach (glob($path) as $filename)
        {
            include ($filename);
        }
    }

    public static function Require($path) {
        foreach (glob($path) as $filename)
        {
            require ($filename);
        }
    }

    public static function RequireOnce($path) {
        foreach (glob($path) as $filename)
        {
            require_once ($filename);
        }
    }
}