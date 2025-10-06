<?php

class ContactView extends View {

    public function render(): void {
        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h1>Contact</h1>
                <form class="uk-form-horizontal uk-margin-large" action="/contact" method="post">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-name">Name</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="form-name" type="text" placeholder="Firstname Lastname" name="name">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-email">Email address</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="form-email" type="text" placeholder="me@mail.com" name="email">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-message">Message</label>
                        <div class="uk-form-controls">
                            <textarea class="uk-textarea" rows="5" placeholder="I'm contacting you about ..." aria-label="form-message" name="message"></textarea>
                        </div>
                    </div>
                    <div class="uk-text-right">
                        <p><button type="submit" class="uk-button uk-button-primary">Send</button></p>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}
