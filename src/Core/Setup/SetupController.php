<?php

class SetupController extends Controller {
    public function __construct(Core $core) {
        parent::__construct($core);
        $this->registerRoute(new ViewRoute('/setup', 'SetupView'));
    }
}