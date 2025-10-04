<?php

include_once('View.php');
include_once('Controller.php');


class ControllerManager {
    public function __construct() {

    }

    public function init(Core $core) : void {
        /* Now we initiate all the controllers ... */
        foreach(get_declared_classes() as $class) {
            if(is_subclass_of($class, 'Controller')) {
                new $class($core);
            }
        }

    }
}