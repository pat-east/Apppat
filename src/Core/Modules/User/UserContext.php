<?php

class UserContext {
    public static ?UserContext $Instance = null;

    public Session $session;

    public function __construct() {
        if(self::$Instance != null) {
            return;
        }

        $this->session = Session::$Instance;
        self::$Instance = $this;
    }
}