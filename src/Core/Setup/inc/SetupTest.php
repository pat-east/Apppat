<?php

class SetupTest {

    var string $title;
    var string $description;
    var string $testPassedHint;
    var string $testNotPassedHint;

    var \Closure $testHandler;

    public function __construct(
        string $title, string $description,
        string $testPassedHint, string $testNotPassedHint,
        \Closure $testHandler) {

        $this->title = $title;
        $this->description = $description;
        $this->testPassedHint = $testPassedHint;
        $this->testNotPassedHint = $testNotPassedHint;
        $this->testHandler = $testHandler;

    }

    public function testPassed() {
        $handler = $this->testHandler;
        return $handler();
    }
}