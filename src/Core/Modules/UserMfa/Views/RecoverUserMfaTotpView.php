<?php

class RecoverUserMfaTotpView extends View {

    public const string StatusInvalidRecoveryKey = 'invalid-recovery-key';
    public const string StatusMfaDisabled = 'mfa-disabled';

    public function render(): void {
        $status = $this->responseArgs['status'] ?? null;

        switch($status) {
            case self::StatusMfaDisabled:
                $this->renderMfaDisabled();
                break;
            case self::StatusInvalidRecoveryKey:
            default:
                $this->renderDefault();
                break;
        }
    }

    private function renderMfaDisabled(): void {
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h1>Recover multi-factor authentication</h1>
                <p>Successfully disabled multi-factor authentication.</p>
                <p>You can now log in to your account without TOTP code.</p>
                <p class="uk-text-center">
                    <a class="uk-button uk-button-primary" href="/login">Log in now</a>
                </p>
            </div>
        </div>
        <?php
    }

    private function renderDefault(): void {
        $status = $this->responseArgs['status'] ?? null;
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h1>Recover multi-factor authentication</h1>
                <?php if ($status == self::StatusInvalidRecoveryKey) : ?>
                    [html-callout message="Invalid recovery key."]
                <?php endif; ?>

                <form class="uk-form-horizontal uk-margin-large" action="/recover-mfa-totp" method="post">
                    [form-nonce secret="<?= UserMfaController::NONCE_SECRET ?>"]
                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-login">Username or email address</label>
                        <div class="uk-form-controls">
                            <input name="username" class="uk-input" id="form-login" type="text" placeholder="">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-login">Recovery key</label>
                        <div class="uk-form-controls">
                            <input name="recovery-key" class="uk-input" id="form-login" type="text" placeholder="">
                            <small>Must have at least <?= Totp::RECOVERY_KEY_LENGTH ?> digest</small>
                        </div>
                    </div>
                    <div class="uk-text-right">
                        <p>
                            <button type="submit" class="uk-button uk-button-primary">Recover mfa</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}