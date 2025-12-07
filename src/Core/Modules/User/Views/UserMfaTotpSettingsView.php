<?php

class UserMfaTotpSettingsView extends View {

    public function render(): void {
        if(UserContext::Instance()->userCredentials->totp->mfaTotpEnabled()) {
            $this->renderEnabled();
        } else {
            $this->renderNotEnabled();
        }
    }

    private function renderNotEnabled(): void {
        ?>
        <div class="uk-section uk-section-muted">
            <div class="uk-container">
                <div uk-grid>
                    <div class="uk-width-1-2">
                        <p>MFA/TOTP is not enabled</p>
                    </div>
                    <div class="uk-width-1-2">
                        <div class="uk-text-right">
                            <a href="/dashboard/user/settings/mfa/totp/enable"><span uk-icon="lock"></span> Enable TOTP</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="uk-section">
            <div class="uk-container">
                <h2>Multi-factor authentication using TOTP</h2>
                <p class="uk-text-lead">Secure your account using multi-factor authentication by enablding TOTP.</p>
                <p>
                    Use authenticator apps to secure your login by bringing in another factor.
                    By enabling MFA/TOTP any login attempt is secured by a timely-based factor which you can
                    get from authenticator apps like Google or Microsoft Authenticator.
                </p>
                <p>Download an authenticator app on your smartphone:</p>
                <ul>
                    <li><a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank">Google Authenticator for Android</a></li>
                    <li><a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank">Google Authenticator for iOS</a></li>
                    <li><a href="https://play.google.com/store/apps/details?id=com.azure.authenticator" target="_blank">Microsoft Authenticator for Android</a></li>
                    <li><a href="https://apps.apple.com/de/app/microsoft-authenticator/id983156458" target="_blank">Microsoft Authenticator for iOS</a></li>
                </ul>
            </div>
        </div>
        <?php
    }

    private function renderEnabled(): void {
        ?>
        <div class="uk-section uk-section-muted">
            <div class="uk-container">
                <div uk-grid>
                    <div class="uk-width-1-2">
                        <p>MFA/TOTP is enabled</p>
                    </div>
                    <div class="uk-width-1-2">
                        <div class="uk-text-right">
                            <a href="/dashboard/user/settings/mfa/totp/disable"><span uk-icon="unlock"></span> Disable TOTP</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="uk-section">
            <div class="uk-container">
                <h2>Multi-factor authentication using TOTP</h2>
                <p class="uk-text-lead">Your account is secured by multi-factor authentication using TOTP.</p>
                <p>
                    Use authenticator apps to secure your login by bringing in another factor.
                    By enabling MFA/TOTP any login attempt is secured by a timely-based factor which you can
                    get from authenticator apps like Google or Microsoft Authenticator.
                </p>
                <p>Download an authenticator app on your smartphone:</p>
                <ul>
                    <li><a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank">Google Authenticator for Android</a></li>
                    <li><a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank">Google Authenticator for iOS</a></li>
                    <li><a href="https://play.google.com/store/apps/details?id=com.azure.authenticator" target="_blank">Microsoft Authenticator for Android</a></li>
                    <li><a href="https://apps.apple.com/de/app/microsoft-authenticator/id983156458" target="_blank">Microsoft Authenticator for iOS</a></li>
                </ul>
            </div>
        </div>
        <?php
    }
}