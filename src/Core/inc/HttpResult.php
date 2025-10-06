<?php

abstract class HttpResult {
    public abstract function run(HttpRequestContext $request);
}