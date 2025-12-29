<?php

class UserView extends View {

    public function render(): void {
        $username = $this->requestArgs['username'];
        $user = UserModel::GetByUsername($username);

        if ($user) {
            $this->renderUser($user);
        } else {
            $this->renderUserNotFound($username);
        }
    }

    private function renderUser(UserModel $user): void {
        $roles = new UserRoles($user);
        ?>
        <div class="uk-section uk-section-muted">
            <div class="uk-container">
                <div class="uk-grid-match uk-child-width-1-2@m" uk-grid>
                    <div class="uk-text-center uk-flex-middle">
                        <p><span uk-icon="icon: user; ratio: 5"></span></p>
                    </div>
                    <div>
                        <p class="uk-text-lead">
                            <?= $user->username ?>
                            <br>
                            <?php foreach($roles->getRoles() as $role) : ?>
                                <span class="uk-badge"><?= $role->getUid() ?></span>
                            <?php endforeach; ?>
                        </p>
                        <p><?= $user->email ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php if(UserContext::$Instance->hasPrivilege(EditUsersPrivilege::class)) : ?>
            <?php $this->renderEditUser($user); ?>
        <?php endif; ?>
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

    private function renderEditUser(UserModel $user) {
        $allRoles = UserRoles::GetAllRoles();
        $userRoles = new UserRoles($user);
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <div class="uk-grid-match uk-child-width-1-1" uk-grid>
                    <div>
                        <div>
                            <h2 class="uk-text-lead">Edit user</h2>
                            <form action="/dashboard/user/<?= $user->uid ?>/" method="post">
                                [form-nonce secret="<?= UserAdminController::NONCE_SECRET ?>"]
                                <h3>Roles</h3>
                                <table class="uk-table">
                                    <thead>
                                    <?php foreach($allRoles as $role) : ?>
                                        <tr>
                                            <td>
                                                <input id="role-<?= $role->getUid() ?>"
                                                       class="uk-checkbox"
                                                       name="userrole-<?= $role->getUid() ?>"
                                                       type="checkbox" <?= $userRoles->hasRole($role->getUid()) ? 'checked=checked' : '' ?> />
                                            </td>
                                            <td><label for="role-<?= $role->getUid() ?>"><?= $role->getUid() ?></label></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </thead>
                                </table>
                                <div class="uk-text-right">
                                    <button type="submit" class="uk-button uk-button-primary">Save</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
}