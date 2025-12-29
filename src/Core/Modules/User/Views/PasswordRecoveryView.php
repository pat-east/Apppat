<?php

class PasswordRecoveryView extends View {
    public const string StatusPasswordChanged = 'success';
    public const string StatusInvalidPassword = 'invalid_pwd';
    public const string StatusInvalidPasswordRepeat = 'invalid_pwd_repeat';
    public const string StatusInvalidRecoveryToken = 'invalid_invalid_token';

    public function render(): void {
        $status = $this->responseArgs['status'] ?? '';

        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h1>Password recovery</h1>
                <?php if($status == self::StatusPasswordChanged) : ?>
                    [html-callout message="New password set."]
                <?php elseif($status == self::StatusInvalidPassword) : ?>
                    [html-callout message="Did not change your password. Please make sure to enter current password and fulfil password criteria for new password."]
                    <?php $this->renderRenewPasswordForm(); ?>
                <?php elseif($status == self::StatusInvalidPasswordRepeat) : ?>
                    [html-callout message="The passwords do not match."]
                    <?php $this->renderRenewPasswordForm(); ?>
                <?php elseif($status == self::StatusInvalidRecoveryToken) : ?>
                    [html-callout message="Invalid recovery token."]
                <?php else: ?>
                    <?php $this->renderRenewPasswordForm(); ?>
                <?php endif; ?>
            </div>

        </div>
        <?php if($status == self::StatusPasswordChanged) : ?>
            <div class="uk-section uk-section-muted">
                <div class="uk-container">
                    <div class="uk-text-center">
                        <h2>Login</h2>
                        <p>Want to access your dashboard?</p>
                        <p><a href="/login" class="uk-button uk-button-primary">Login now</a></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php
    }

    function renderRenewPasswordForm(): void {
        $recoveryToken = $this->requestArgs['recovery_token'];
        ?>
        <form class="uk-form-horizontal uk-margin-large" action="/password-recovery" method="post">
            [form-nonce secret="<?= UserController::NONCE_SECRET ?>"]

            <input value="<?= $recoveryToken ?>" name="recovery_token" class="uk-input" id="form-current-rec-token" type="hidden">

            <div class="uk-margin">
                <label class="uk-form-label" for="form-pwd">New password</label>
                <div class="uk-form-controls">
                    <input name="password" class="uk-input" id="form-pwd" type="Password" placeholder="New password">
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label" for="form-pwd-repeat">New password (repeat)</label>
                <div class="uk-form-controls">
                    <input name="password-repeat" class="uk-input" id="form-pwd-repeat" type="Password" placeholder="New password (repeat)">
                </div>
            </div>
            <div class="uk-text-right">
                <p><button type="submit" class="uk-button uk-button-primary">Recover password</button></p>
            </div>
        </form>
        <?php
    }
}