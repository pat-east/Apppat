<?php

abstract class HttpResultContext {
    public abstract function run(HttpRequestContext $request);
}