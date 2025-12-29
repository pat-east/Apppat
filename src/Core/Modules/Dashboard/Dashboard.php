<?php

class Dashboard {

    public static ?Dashboard $Instance;

    /** @var array<string, DashboardItem[]> */
    private array $dashboardItemCategories;

    public function __construct() {
        $this->dashboardItemCategories = [];
    }

    /**
     * @return array<string, DashboardItem[]>
     */
    public function getDashboardItemCategories(): array {
        return $this->dashboardItemCategories;
    }

    public function init(): void {

        self::$Instance = $this;

        if(UserContext::$Instance->isUserLoggedIn) {
            $userPrivileges = UserContext::$Instance->userRoles->getPrivileges();
            $categories = [];
            $items = Helper::GetDerivingClasses('DashboardItem');

            foreach ($items as $item) {
                /** @var DashboardItem $itemInstance */
                $itemInstance = new $item();

                if(!$itemInstance->isAccessable($userPrivileges))

                if(!array_key_exists($itemInstance->category, $categories)) {
                    $categories[$itemInstance->category] = [];
                }
                $categories[$itemInstance->category][] = $itemInstance;
            }

            $this->dashboardItemCategories = $categories;

            // Order categories by name
            ksort($this->dashboardItemCategories);
            // Order items by name
            foreach ($this->dashboardItemCategories as $category => $items) {
                usort($this->dashboardItemCategories[$category], function ($a, $b) {
                    return strcmp($a->title, $b->title);
                });

            }

            // Now register the urls ...
            foreach ($this->dashboardItemCategories as $category => $items) {
                foreach ($items as $item) {
                    DashboardController::Instance()->registerDashboardItemRoute($item);
                }
            }
        }



    }
}