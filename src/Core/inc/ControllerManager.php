<?php

include_once('View.php');
include_once('Controller.php');


class ControllerManager {
    public function __construct() {

    }

    public function init(Core $core) : void {
        /* All app logic is inside /App-folder.
         * We first include all the files within that folder recursivly. */
        Helper::IncludeOnce(Defaults::APPSPATH, true);

        /* Now we initiate all the controllers ... */
        foreach(get_declared_classes() as $class) {
            if(is_subclass_of($class, 'Controller')) {
                new $class($core);
            }
        }

    }
}