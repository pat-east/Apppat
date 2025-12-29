<?php

abstract class Shortcode {

    abstract static function GetName(): string;

    /** @var array<string, string> $attrs */
    var array $attrs;
    var string $content;

    /**
     * @param string $name
     * @param array<string, string> $attrs
     * @param string $content
     */
    function __construct(array $attrs, string $content) {
        $this->attrs = array_merge($this->getDefaultArgs(), $attrs);
        $this->content = $content;
    }

    abstract function process(): string;

    abstract function getDefaultArgs(): array;
}