<?php


abstract class SetupTestRepository {


    public function __construct() {

    }

    /**
     * @return SetupTest[]
     */
    public abstract function getTests() : array;
}