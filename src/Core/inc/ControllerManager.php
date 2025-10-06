<?php

include_once('View.php');
include_once('Controller.php');


class ControllerManager {
    public function __construct() {

    }

    public function init(Core $core) : void {
        /* Now we initiate all the controllers ... */
        foreach(Helper::GetDerivingClasses('Controller') as $class) {
            new $class($core);
        }
    }
}