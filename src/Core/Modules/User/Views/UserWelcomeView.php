<?php

class UserWelcomeView extends View {

    public function render(): void {
        $user = $this->responseArgs['user'];

        $this->renderUser($user);
    }

    private function renderUser(UserModel $user): void {
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <div class="uk-grid-match uk-child-width-1-2@m" uk-grid>
                    <div class="uk-text-center uk-flex-middle">
                        <p><span uk-icon="icon: user; ratio: 5"></span></p>
                    </div>
                    <div>
                        <p class="uk-text-lead">Welcome <?= $user->username ?></p>
                        <p>Your account got created successfully.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="uk-section uk-section-muted">
            <div class="uk-container">
                <div class="uk-text-center">
                    <h2>Sign in now</h2>
                    <p>You'd like to login to your account?</p>
                    <p><a href="/user/login" class="uk-button uk-button-primary">Login now</a></p>
                </div>
            </div>
        </div>
        <?php
    }

    private function renderUserNotFound($username): void {
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <div class="uk-grid-match uk-child-width-1-2@m" uk-grid>
                    <div class="uk-text-center uk-flex-middle">
                        <p><span uk-icon="icon:  close-circle; ratio: 5"></span></p>
                    </div>
                    <div>
                        <div>
                            <p class="uk-text-lead">No user found</p>
                            <p>You requested a user with username <strong><?= urldecode($username) ?></strong> which
                                does not exist.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}