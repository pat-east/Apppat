<?php

class EnableUserMfaTotpView extends View {

    public const string StatusInvalidTotp = 'invalid_totp';

    public function render(): void {
        if(UserContext::Instance()->userCredentials->totp->mfaTotpEnabled()) {
            $this->renderAlreadyEnabled();
        } else {
            $this->renderEnable();
        }
    }

    private function renderEnable(): void {
        $status = $this->responseArgs['status'] ?? '';
        $provisioningUri = UserContext::Instance()->userCredentials->totp->getProvisioningUri();
        $provisioningQrCodeBase64 = base64_encode(QrCode::Create($provisioningUri));
        $recoveryKey = UserContext::Instance()->userCredentials->totp->getRecoveryKey();
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h2>Enable MFA using TOTP</h2>
                <div uk-grid>
                    <div class="uk-width-1-2@s">
                        <h3>1. Save recovery key</h3>
                        <p>If you loose access to your phone, you might not be able to create TOTP codes anymore.</p>
                        <p>To recover your account while not be able to create TOTP codes, you must enter the following recovery key:</p>
                        <div class="uk-section-primary uk-padding-small">
                            <span class="uk-text-primary uk-display-block uk-text-center"><?= chunk_split($recoveryKey, 4, ' ') ?></span>
                        </div>
                        <p><strong>Store this recovery key safely and keep it secretly.</strong></p>
                        <h3>2. Confirm MFA/TOTP</h3>
                        <p>Please scan the QR code using your authenticator app.</p>
                        <p>Following enter current code from your authenticator app and confirm to enable MFA using TOTP.</p>
                        <?php if($status == self::StatusInvalidTotp) : ?>
                            [html-callout message="Invalid TOTP code."]
                        <?php endif; ?>
                        <form class="uk-form-stacked" method="post" action="/dashboard/user/settings/mfa/totp/enable/">
                            [form-nonce secret="<?= UserMfaController::NONCE_SECRET ?>"]
                            <div class="uk-margin">
                                <label class="uk-form-label" for="form-stacked-code">Code</label>
                                <div class="uk-form-controls">
                                    <input name="totp-code" class="uk-input" id="form-stacked-code" type="text" placeholder="xxx yyy">
                                </div>
                            </div>
                            <div class="uk-margin uk-text-right">
                                <div class="uk-form-controls">
                                    <button type="submit" class="uk-button uk-button-primary">Confirm</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="uk-width-1-2@s uk-text-right">
                        <img src="data:image/png;base64,<?= $provisioningQrCodeBase64 ?>" alt="Bild" />
                    </div>
                </div>


            </div>
        </div>

        <?php
    }

    private function renderAlreadyEnabled(): void {
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h2>MFA using TOTP</h2>
                <p>Multi-factor authentication using TOTP is already enabled.</p>
            </div>
        </div>

        <?php
    }


}