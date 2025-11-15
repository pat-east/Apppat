<?php

class DashboardItem {
    public string $title;
    public string $description;
    public string $category;
    public string $icon;
    public string $link;
    public string $viewClassName;


    public function __construct(string $title, string $description, string $category, string $icon, string $link, string $viewClassName) {
        $this->title = $title;
        $this->description = $description;
        $this->category = $category;
        $this->icon = $icon;
        $this->link = $link;
        $this->viewClassName = $viewClassName;
    }

}