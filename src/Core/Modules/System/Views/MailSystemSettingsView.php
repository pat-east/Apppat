<?php

class MailSystemSettingsView extends View {

    public const string STATUS_INVALID_MAIL = 'invalid_mail';
    public const string STATUS_TEST_MAIL_SUCCESS = 'test_mail_success';
    public const string STATUS_TEST_MAIL_FAILED = 'test_mail_failed';

    public function render(): void {
        $status = $this->responseArgs['status'] ?? '';
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h1>Mailing</h1>

                <h2>Test</h2>
                <?php if($status == self::STATUS_TEST_MAIL_FAILED) : ?>
                    [html-callout message="Could not send mail"]
                <?php else : ?>
                    [html-callout message="Successfully sent mail"]
                <?php endif; ?>
                <form class="uk-form-horizontal uk-margin-large" action="/system/mail/test" method="post">
                    [form-nonce secret="<?= SystemController::NONCE_SECRET ?>"]
                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-receiver">Receiver</label>
                        <div class="uk-form-controls">
                            <input placeholder="me@my-business.com" name="receiver" class="uk-input" id="form-receiver" type="text">
                        </div>
                    </div>
                    <div class="uk-text-right">
                        <button type="submit" class="uk-button uk-button-primary">Send test mail</button>
                    </div>
                </form>




                <h2>SMTP</h2>
                [html-callout message="SMTP settings are configured using .env"]
                <table class="uk-table">
                    <tr>
                        <th><label for="form-smtp-enabled">Enabled</label></th>
                        <td><input id="form-smtp-enabled" class="uk-checkbox" <?= Config::$MailConfig->useSmtp ? 'checked="checked"' : '' ?> type="checkbox" disabled="disabled" /></td>
                    </tr>
                    <tr>
                        <th><label for="form-smtp-from-addr">SMTP from address</label></th>
                        <td><input id="form-smtp-from-addr" class="uk-input" value="<?= Config::$MailConfig->fromAddress ?>" type="text" disabled="disabled" /></td>
                    </tr>
                    <tr>
                        <th><label for="form-smtp-from-name">SMTP from name</label></th>
                        <td><input id="form-smtp-from-name" class="uk-input" value="<?= Config::$MailConfig->fromName ?>" type="text" disabled="disabled" /></td>
                    </tr>
                    <tr>
                        <th><label for="form-smtp-host">SMTP host</label></th>
                        <td><input id="form-smtp-host" class="uk-input" value="<?= Config::$MailConfig->smtpHost ?>" type="text" disabled="disabled" /></td>
                    </tr>
                    <tr>
                        <th><label for="form-smtp-port">SMTP port</label></th>
                        <td><input id="form-smtp-port" class="uk-input" value="<?= Config::$MailConfig->smtpPort ?>" type="text" disabled="disabled" /></td>
                    </tr>
                    <tr>
                        <th><label for="form-smtp-usr">SMTP user</label></th>
                        <td><input id="form-smtp-usr" class="uk-input" value="<?= Config::$MailConfig->smtpUsername ?>" type="text" disabled="disabled" /></td>
                    </tr>
                    <tr>
                        <th><label for="form-smtp-pwd">SMTP password</label></th>
                        <td><input id="form-smtp-pwd" class="uk-input" value="<?= Config::$MailConfig->smtpPassword ? 'password-hidden' : '' ?>" type="password" disabled="disabled" /></td>
                    </tr>
                    <tr>
                        <th><label for="form-smtp-enc">SMTP encryption</label></th>
                        <td><input id="form-smtp-enc" class="uk-input" value="<?= Config::$MailConfig->smtpEncryption?>" type="text" disabled="disabled" /></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
    }
}