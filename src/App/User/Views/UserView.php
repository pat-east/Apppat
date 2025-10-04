<?php

class UserView extends View {

    public function render(): void {
        $model = new UserModel();
        $user = $model->getByUsername($this->args['username']);
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
}