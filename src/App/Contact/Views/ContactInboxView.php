<?php

class ContactInboxView extends View {

    public function render(): void {
        $contacts = new ContactModelRepository()->getAll();

        ?>
        <div class="uk-section">
            <div class="uk-container">
                <h2>Inbox</h2>
                <p>You received <?= count($contacts) ?> message<?= count($contacts) != 1 ? 's' : '' ?>.</p>

                <?php if(false) : // disabled ?>
                <?php foreach($contacts as $contact): ?>
                    <hr>
                    <article class="uk-article">
                        <h1 class="uk-article-title"><a class="uk-link-reset" href=""><?= $contact->name ?></a></h1>
                        <p class="uk-article-meta"><?= $contact->created_at->format('d/m/y H:i') ?></p>
                        <p><?= nl2br($contact->message) ?></p>
                        <a class="uk-button uk-button-text" href="mailto:<?= $contact->email ?>"><?= $contact->email ?></a>
                    </article>
                <?php endforeach; ?>
                <?php endif; ?>

                <table class="uk-table uk-table-divider">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($contacts as $contact): ?>
                            <tr>
                                <td><?= $contact->id ?></td>
                                <td><?= $contact->name ?></td>
                                <td><?= $contact->email ?></td>
                                <td><?= nl2br($contact->message) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }


}