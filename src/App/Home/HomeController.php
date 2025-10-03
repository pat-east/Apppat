<?php

class HomeController extends Controller {
    public function __construct(Core $core) {
        parent::__construct($core);

        $this->registerRoute(new Route('/', HomeView::class));
    }
}