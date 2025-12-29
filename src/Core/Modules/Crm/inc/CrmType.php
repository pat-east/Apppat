<?php

abstract class CrmType {
    public function getType(): string {
        return get_class($this);
    }
}