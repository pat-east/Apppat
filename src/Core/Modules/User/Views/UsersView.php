<?php

class UsersView extends View {

    public function render(): void {

        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h1>Users and roles</h1>
                <ul uk-tab>
                    <li class="uk-active"><a href="#">Users</a></li>
                    <li><a href="#">Roles</a></li>
                </ul>
                <div class="uk-switcher uk-margin">
                    <div role="tabpanel">
                        <?php $this->renderUsers(); ?>
                    </div>
                    <div role="tabpanel">
                        <?php $this->renderRoles(); ?>
                    </div>

                </div>

            </div>
        </div>

        <?php
    }

    private function renderUsers(): void {
        $users = UserModel::GetAll();
        ?>

        <table class="uk-table">
            <caption></caption>
            <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Roles</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): $userRoles = new UserRoles($user); ?>
                <tr>
                    <td><?= $user->username ?></td>
                    <td><?= $user->email ?></td>
                    <td>
                        <?php foreach ($userRoles->getRoles() as $userRole) : ?>
                            <span class="uk-badge"><?= $userRole->getUid() ?></span>
                        <?php endforeach; ?>
                    </td>
                    <td><a href="/dashboard/user/<?= $user->username ?>"><span uk-icon="link"></span></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td></td>
            </tr>
            </tfoot>
        </table>
        <?php
    }

    private function renderRoles(): void {
        $roles = UserRoles::GetAllRoles();
        ?>
        <table class="uk-table">
            <caption></caption>
            <thead>
            <tr>
                <th>Uid</th>
                <th>Role</th>
                <th>Privileges</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($roles as $role): ?>
                <tr>
                    <td><span class="uk-badge"><?= $role->getUid() ?></span></td>
                    <td><?= $role::class ?></td>
                    <td>
                        <ul class="uk-list">
                            <?php foreach($role->getPrivileges() as $privilege): ?>
                                <li>
                                    <span class="uk-badge"><?= $privilege->getUid() ?></span>
                                    <?php if($privilege->getDescription()) : ?>
                                        <br><span><?= $privilege->getDescription() ?></span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td></td>
            </tr>
            </tfoot>
        </table>
        <?php
    }
}