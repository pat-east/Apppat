<?php

class ContactedView extends View {

    public function render(): void {
        ?>
        <div class="uk-section uk-section-muted">
            <div class="uk-container">
                <div class="uk-text-center">
                    <h2>Thanks for contacting me!</h2>
                    <p>I received your message and I look forward to reply as soon as possible.</p>
                </div>
            </div>
        </div>
        <?php
    }
}
