<?php

class ShortcodeFactory {

    static array $ShortcodeClassesByName = [];

    /**
     * @param string $shortcodeName
     * @param array<string, string> $attrs
     * @param string $content
     * @return Shortcode|null
     */
    public static function Create(string $shortcodeName, array $attrs, string $content) : Shortcode|null {
        if(isset(self::$ShortcodeClassesByName[$shortcodeName])) {
            return new self::$ShortcodeClassesByName[$shortcodeName]($attrs, $content);
        }
        return null;
    }

    public static function Init(): void {
        Helper::IncludeOnce(Defaults::ABSPATH . '/Core/Shortcodes');

        foreach(Helper::GetDerivingClasses('Shortcode') as $class) {
            $name = call_user_func([$class, 'GetName']);
            self::$ShortcodeClassesByName[$name] = $class;
        }
    }
}