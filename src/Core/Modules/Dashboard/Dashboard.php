<?php

class Dashboard {

    private static ?Dashboard $Instance;

    public static function Instance(): Dashboard {
        if (!isset(self::$Instance)) {
            self::$Instance = new Dashboard();
            self::$Instance->init();
        }
        return self::$Instance;
    }

    /** @var array<string, DashboardItem[]> */
    private array $dashboardItemCategories;

    private function __construct() {

    }

    /**
     * @return array<string, DashboardItem[]>
     */
    public function getDashboardItemCategories(): array {
        return $this->dashboardItemCategories;
    }

    public function init(): void {
        $categories = [];
        $items = Helper::GetDerivingClasses('DashboardItem');

        foreach ($items as $item) {
            $itemInstance = new $item();
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