<?php

class DisableUserMfaTotpView extends View {

    public const string StatusInvalidTotp = 'invalid_totp';

    public function render(): void {
        $status = $this->responseArgs['status'] ?? '';
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h2>Disable MFA using TOTP</h2>
                <h3>Confirm disabling MFA/TOTP</h3>
                <p>Please confirm disabling MFA/TOTP.</p>
                <?php if($status == self::StatusInvalidTotp) : ?>
                    [html-callout message="Invalid TOTP code."]
                <?php endif; ?>
                <form class="uk-form-stacked" method="post" action="/dashboard/user/settings/mfa/totp/disable/">
                    [form-nonce secret="<?= UserMfaController::NONCE_SECRET ?>"]
                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-stacked-code">Code</label>
                        <div class="uk-form-controls">
                            <input name="totp-code" class="uk-input" id="form-stacked-code" type="text" placeholder="xxx yyy">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <div class="uk-form-controls">
                            <label><input value="<?= Defaults::YES ?>" name="renew-secrets" class="uk-checkbox" type="checkbox" checked> Renew TOTP secret and recovery key</label>
                        </div>
                    </div>

                    <div class="uk-margin uk-text-right">
                        <div class="uk-form-controls">
                            <button type="submit" class="uk-button uk-button-primary">Confirm</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}