<?php

class SetupTestManager {
    /**
     * @return SetupTest[]
     */
    public function getTests() : array {
        $tests = [];

        foreach(Helper::GetDerivingClasses('SetupTestRepository') as $class) {
            /** @var SetupTestRepository $testRepo */
            $testRepo = new $class();
            $tests = array_merge($tests, $testRepo->getTests());
        }

        return $tests;
    }
}