<?php

class DashboardController extends Controller {

    public const string NONCE_SECRET = 'dashboard-nonce-secret-Xs9ML4m2aK';

    private static ?DashboardController $Instance = null;

    public static function Instance(): DashboardController {
        return self::$Instance;
    }

    public function __construct(Core $core) {
        if(self::$Instance !== null) { return; }
        parent::__construct($core);

        self::$Instance = $this;
        $this->registerRoute(new ViewRoute('/dashboard',
            UserContext::Instance()->isUserLoggedIn ? 'DashboardView' : 'LoginView'));
    }

    public function registerDashboardItemRoute(DashboardItem $dashboardItem) {
        $this->registerRoute(new ViewRoute($dashboardItem->link,
            UserContext::Instance()->isUserLoggedIn ? $dashboardItem->viewClassName : 'LoginView'));
    }
}