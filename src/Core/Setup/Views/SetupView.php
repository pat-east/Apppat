<?php

class SetupView extends View {

    public function render(): void {

        $tests = new SetupTestRepository()->getTests();

        ?>
        <div class="uk-section">
            <div class="uk-container">
                <div class="uk-card uk-card-default uk-width-1-1">
                    <div class="uk-card-header">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-expand">
                                <h3 class="uk-card-title uk-margin-remove-bottom">Setup</h3>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">

                        <table class="uk-table">
                            <caption></caption>
                            <tbody>
                            <?php foreach($tests as $test): $passed = $test->testPassed(); ?>
                                <tr>
                                    <td><span uk-icon="icon: <?= $passed ? 'check' : 'close' ?>"></span></td>
                                    <td><?= $test->title ?></td>
                                    <td>
                                        <?= $test->description ?><br>
                                        <strong><?= $passed ? $test->testPassedHint : $test->testNotPassedHint ?></strong>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="uk-card-footer">
                        <p class="uk-text-meta uk-margin-remove-top"><?= Defaults::APPTITLE ?> v.<?= Defaults::VERSION ?></p>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
}