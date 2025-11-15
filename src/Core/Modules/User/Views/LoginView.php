<?php

class LoginView extends View {

    public const string StatusInvalidLogin = 'invalid-login';

    public function render(): void {

        $status = $this->responseArgs['status'] ?? null;

        ?>
        <div class="uk-section">
            <div class="uk-container">

                <?php if($status == self::StatusInvalidLogin) : ?>
                [html-callout message="Invalid login. Please check username/email and password."]
                <?php endif; ?>

                <form class="uk-form-horizontal uk-margin-large" action="/login" method="post">
                    [form-nonce secret="<?= UserController::NONCE_SECRET ?>"]
                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-login">Username or email address</label>
                        <div class="uk-form-controls">
                            <input name="username" class="uk-input" id="form-login" type="text" placeholder="">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-login">Password</label>
                        <div class="uk-form-controls">
                            <input name="password" class="uk-input" id="form-login" type="password" placeholder="">
                        </div>
                    </div>
                    <div class="uk-text-right">
                        <p><button type="submit" class="uk-button uk-button-primary">Login</button></p>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}