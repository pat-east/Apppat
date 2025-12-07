<?php

class RecoverPasswordView extends View {
    public const string StatusRecoveryMailSent = 'recovery-mail-sent';
    public const string StatusRecoveryFailed = 'recovery-failed';

    public function render(): void {
        $status = $this->responseArgs['status'] ?? null;

        switch($status) {
            case self::StatusRecoveryMailSent:
                $this->renderMailSent();
                break;
            default:
                $this->renderDefault();
                break;
        }
    }

    private function renderMailSent(): void {
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h1>Mail to recover your account sent</h1>
                <p>Check your mail inbox to recover your password.</p>
            </div>
        </div>
        <?php
    }

    private function renderDefault(): void {
        $status = $this->responseArgs['status'] ?? null;
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h1>Recover password</h1>
                <?php if ($status == self::StatusRecoveryFailed) : ?>
                    [html-callout message="Something went wrong."]
                <?php endif; ?>

                <form class="uk-form-horizontal uk-margin-large" action="/recover-password" method="post">
                    [form-nonce secret="<?= UserController::NONCE_SECRET ?>"]
                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-login">Username or email address</label>
                        <div class="uk-form-controls">
                            <input name="username" class="uk-input" id="form-login" type="text" placeholder="">
                        </div>
                    </div>
                    <div class="uk-text-right">
                        <p>
                            <button type="submit" class="uk-button uk-button-primary">Recover password</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}