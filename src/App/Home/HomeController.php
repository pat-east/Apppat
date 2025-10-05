<?php

class HomeController extends Controller {
    public function __construct(Core $core) {
        parent::__construct($core);

        $this->registerRoute(new ViewRoute('/', HomeView::class));
        $this->registerRoute(new ViewRoute('/about', AboutView::class));
        $this->registerRoute(new ViewRoute('/contact', ContactView::class));
    }
}