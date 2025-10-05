<?php

class SetupTest {

    var string $title;
    var string $description;
    var string $testPassedHint;
    var string $testNotPassedHint;

    /** @var Closure() : bool */
    var \Closure $testHandler;

    /**
     * @param string $title
     * @param string $description
     * @param string $testPassedHint
     * @param string $testNotPassedHint
     * @param Closure():bool $testHandler
     */
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

    public function testPassed() : bool {
        $handler = $this->testHandler;
        return $handler();
    }
}