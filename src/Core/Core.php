<?php

class Core {
    public function init(): void {
        Logger::Info(__FILE__, "Core initialized [version=%s]", Defaults::VERSION);
    }
}