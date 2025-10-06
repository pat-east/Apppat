<?php

class UserRegisterView extends View {

    public function render(): void {
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <div>
                    <h1>Register new user</h1>
                    <form class="uk-form-horizontal uk-margin-large" action="/user/register" method="post">
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-horizontal-text">Username</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="form-horizontal-text" type="text" placeholder="Username">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-horizontal-text">Firstname</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="form-horizontal-text" type="text" placeholder="Firstname">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-horizontal-text">Lastname</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="form-horizontal-text" type="text" placeholder="Lastname">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-horizontal-text">Password</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="form-horizontal-text" type="Password" placeholder="Password">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-horizontal-text">Password (repeat)</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" id="form-horizontal-text" type="Password" placeholder="Password (repeat)">
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
}