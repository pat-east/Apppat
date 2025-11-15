<?php

class UserRegisterView extends View {
    public const string StatusUserAlreadyExists = 'user_already_exists';
    public const string StatusInvalidUsername = 'invalid_username';
    public const string StatusInvalidEmail = 'invalid_email';
    public const string StatusInvalidPassword = 'invalid_pwd';
    public const string StatusInvalidPasswordRepeat = 'invalid_pwd_repeat';
    public const string StatusError = 'error';

    public function render(): void {

        $email = $this->requestArgs['email'] ?? '';
        $username = $this->requestArgs['username'] ?? '';

        ?>
        <div class="uk-section">
            <div class="uk-container">
                <div>
                    <h1>Register new user</h1>
                    <?php $this->renderStatus(); ?>
                    <form class="uk-form-horizontal uk-margin-large" action="/user/register" method="post">
                        [form-nonce secret="<?= UserController::NONCE_SECRET ?>"]
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-username">Username</label>
                            <div class="uk-form-controls">
                                <input value="<?= $username ?>" name="username" class="uk-input" id="form-username" type="text" placeholder="Username" />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-email">Email</label>
                            <div class="uk-form-controls">
                                <input value="<?= $email ?>" name="email" class="uk-input" id="form-email" type="text" placeholder="Email" />
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-pwd">Password</label>
                            <div class="uk-form-controls">
                                <input name="password" class="uk-input" id="form-pwd" type="Password" placeholder="Password">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-pwd-repeat">Password (repeat)</label>
                            <div class="uk-form-controls">
                                <input name="password-repeat" class="uk-input" id="form-pwd-repeat" type="Password" placeholder="Password (repeat)">
                            </div>
                        </div>
                        <div class="uk-text-right">
                            <p><button type="submit" class="uk-button uk-button-primary">Register new user</button></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    private function renderStatus(): void {
        if(!isset($this->responseArgs['status'])) { return; }

        $errMsg = 'Could not register user.';

        switch($this->responseArgs['status']) {
            case self::StatusUserAlreadyExists:
                $errMsg = 'Please choose another username and or email address.';
                break;
            case self::StatusInvalidEmail:
                $errMsg = 'Invalid email address.';
                break;
            case self::StatusInvalidUsername:
                $errMsg = 'Invalid username.';
                break;
            case self::StatusInvalidPassword:
                $errMsg = 'Invalid password.';
                break;
            case self::StatusInvalidPasswordRepeat:
                $errMsg = 'The passwords do not match.';
                break;
            case self::StatusError:
                $errMsg = 'An error occurred.';
                break;
        }

        ?>
        <div class="uk-alert-danger" uk-alert>
            <a href class="uk-alert-close" uk-close></a>
            <p><?= $errMsg ?></p>
        </div>
        <?php
    }
}