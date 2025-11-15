<?php

class UsersView extends View {

    public function render(): void {
        ?>
        <?php $this->renderListUsers(); ?>
        <?php $this->renderCreateNewUser(); ?>
        <?php
    }

    public function renderListUsers(): void {
        $users = UserModel::GetAll();
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h1>Users</h1>
                <table class="uk-table">
                    <caption></caption>
                    <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user->username ?></td>
                            <td><?= $user->email ?></td>
                            <td><a href="/user/<?= $user->username ?>"><span uk-icon="link"></span></a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <?php
    }

    public function renderCreateNewUser(): void {
        ?>
        <div class="uk-section uk-section-muted">
            <div class="uk-container">
                <div class="uk-text-center">
                    <h2>Register new user</h2>
                    <p>You'd also like to register as a new user?</p>
                    <p><a href="/user/register" class="uk-button uk-button-primary">Register now</a></p>
                </div>
            </div>
        </div>
        <?php
    }
}