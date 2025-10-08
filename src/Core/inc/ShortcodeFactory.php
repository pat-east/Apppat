<?php

include_once('Shortcode.php');

class ShortcodeFactory {

    static array $ShortcodeClassesByName = [];

    /**
     * @param string $shortcode
     * @param array<string, string> $attrs
     * @param string $content
     */
    public static function Create(string $shortcodeName, array $attrs, string $content) : Shortcode|null {
        if(isset(self::$ShortcodeClassesByName[$shortcodeName])) {
            return new self::$ShortcodeClassesByName[$shortcodeName]($attrs, $content);
        }
        return null;
    }

    public static function Init() {
        Helper::IncludeOnce(Defaults::BUILD_IN_SHORTCODES_PATH);

        foreach(Helper::GetDerivingClasses('Shortcode') as $class) {
            $name = call_user_func([$class, 'GetName']);
            self::$ShortcodeClassesByName[$name] = $class;
        }
    }
}