<?php

class ChangeUserPasswordView extends View {
    public const string StatusInvalidPassword = 'invalid_pwd';

    public function render(): void {
        $status = $this->responseArgs['status'] ?? '';
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <div>
                    <h1>Change password</h1>
                    <?php if($status == self::StatusInvalidPassword) : ?>
                        [html-callout message="Did not change your password. Please make sure to enter current password and fulfil password criteria for new password."]
                    <?php endif; ?>
                    <form class="uk-form-horizontal uk-margin-large" action="/user/password" method="post">
                        [form-nonce secret="<?= UserController::NONCE_SECRET ?>"]

                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-current-pwd">Current password</label>
                            <div class="uk-form-controls">
                                <input name="current-password" class="uk-input" id="form-current-pwd" type="Password" placeholder="Current password">
                            </div>
                        </div>
                        <hr>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-pwd">New password</label>
                            <div class="uk-form-controls">
                                <input name="new-password" class="uk-input" id="form-pwd" type="Password" placeholder="New password">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-pwd-repeat">New password (repeat)</label>
                            <div class="uk-form-controls">
                                <input name="new-password-repeat" class="uk-input" id="form-pwd-repeat" type="Password" placeholder="New password (repeat)">
                            </div>
                        </div>
                        <div class="uk-text-right">
                            <p><button type="submit" class="uk-button uk-button-primary">Change password</button></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
}