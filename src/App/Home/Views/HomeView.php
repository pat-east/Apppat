<?php

class HomeView extends AboutView {

    public function render(): void {
?>


        <div class="uk-section">
            <div class="uk-container">
                <div class="uk-text-center">
                    <h2>Login</h2>
                    <p>Want to access your dashboard?</p>
                    <p><a href="/login" class="uk-button uk-button-primary">Login now</a></p>
                </div>
            </div>
        </div>
<!--        <div class="uk-section uk-section-muted">-->
<!--            <div class="uk-container">-->
<!--                <div class="uk-text-center">-->
<!--                    <h2>Register new user</h2>-->
<!--                    <p>You'd also like to register as a new user?</p>-->
<!--                    <p><a href="/user/register" class="uk-button uk-button-primary">Register now</a></p>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
        <?php
    }
}
