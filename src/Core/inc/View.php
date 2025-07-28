<?php

abstract class View {

    public function __construct() {

    }

    abstract public function render(): void;

}