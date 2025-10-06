<?php

class ContactSetupTests extends SetupTestRepository {

    /**
     * @return SetupTest[]
     */
    public function getTests(): array {
        $tests = [];

        $tests[] = new MysqlTableExistsTest(ContactModelRepository::TABLE_NAME);

        return $tests;
    }
}