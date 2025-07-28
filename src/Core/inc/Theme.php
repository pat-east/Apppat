<?php

abstract class Theme {

    public function __construct() {

    }

    abstract public function render(View $view): void;
}