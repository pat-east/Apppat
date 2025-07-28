<?php

require_once('Theme.php');

class ThemeManager {

    var Theme $theme;

    public function __construct() {

    }

    public function init() {

    }

    public function useTheme(Theme $theme): void {
        $this->theme = $theme;
    }
}