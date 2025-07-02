<?php

class Core {
    public function init() {
        Logger::Info(__FILE__, "Core initialized [version=%s]", Defaults::VERSION);
    }
}