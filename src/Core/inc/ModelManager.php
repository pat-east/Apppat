<?php

include_once('Model.php');
include_once('ModelRepository.php');

class ModelManager {
    public function __construct() {

    }

    public function init() {
        /* All app models are inside /Modules/Models-folder.
         * All files being included within that folder being included by ControllerManager already. */
    }
}