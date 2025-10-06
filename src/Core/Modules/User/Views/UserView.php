<?php

class UserView extends View
{

    public function render(): void
    {
        $model = new UserModel();
        $username = $this->args['username'];
        $user = $model->getByUsername($this->args['username']);

        if ($user) {
            $this->renderUser($user);
        } else {
            $this->renderUserNotFound($username);
        }
    }

    private function renderUser(UserModel $user): void
    {
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <div class="uk-grid-match uk-child-width-1-2@m" uk-grid>
                    <div class="uk-text-center uk-flex-middle">
                        <p><span uk-icon="icon: user; ratio: 5"></span></p>
                    </div>
                    <div>
                        <p class="uk-text-lead"><?= $user->firstname ?> <?= $user->lastname ?></p>
                        <p><?= $user->email ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private function renderUserNotFound($username): void
    {
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