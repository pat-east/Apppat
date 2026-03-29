<?php

class CrmView extends View {

    public function getBreadcrumbItems(): array {
        return [
            [ 'label' => 'Dashboard', 'href' => '/dashboard' ],
            [ 'label' => 'User', 'href' => null ],
            [ 'label' => 'CRM', 'href' => null ],
        ];
    }

    public function render(): void {
        $entity = UserContext::$Instance->crm->commonEntity;
        ?>
        <div class="uk-section uk-section-muted">
            <div class="uk-container">
                <div class="">
                    <?php $this->renderCommonEntity(); ?>
                </div>
            </div>
        </div>
        <div class="uk-section">
            <div class="uk-container">
                <div class="">
                    <ul uk-tab>
                        <li class="uk-active"><a href="#">Basic information</a></li>
                        <li class="<?= !$entity->id ? 'uk-disabled' : '' ?>"><a href="#">Contact information</a></li>
                        <li class="<?= !$entity->id ? 'uk-disabled' : '' ?>"><a href="#">Address information</a></li>
                    </ul>
                    <div class="uk-switcher uk-margin">
                        <div role="tabpanel">
                            <?php $this->renderCommonBasicEntity(); ?>
                        </div>
                        <div role="tabpanel">
                            <?php $this->renderCommonContactEntity(); ?>
                        </div>
                        <div role="tabpanel">
                            <?php $this->renderCommonAddressEntity(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private function renderCommonEntity(): void {
        $entity = UserContext::$Instance->crm->commonEntity;
        if(!$entity->id) { return; }
        ?>
        <div uk-grid>
            <div class="uk-width-1-3@s">
                <div class="uk-grid-small uk-flex-top" uk-grid>
                    <div class="uk-width-auto">
                        <span uk-icon="icon: user; ratio: 2"></span>
                    </div>
                    <div class="uk-width-expand">
                        <h3 class="uk-card-title uk-margin-remove-bottom"><?= $entity->getDisplayName() ?></h3>
                        <p class="uk-text-meta uk-margin-remove-top">
                            <?php if($entity->birthdate) : ?>
                                <span><span uk-icon="star"></span> <?= $entity->birthdate->format('M d, Y') ?><br></span>
                            <?php endif; ?>
                            <?php if($entity->vatId) : ?>
                                <span><span uk-icon="code"></span> <?= $entity->vatId ?></span><br>
                            <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if($entity->contactId) : ?>
            <div class="uk-width-1-3@s">
                <div class="uk-grid-small uk-flex-top" uk-grid>
                    <div class="uk-width-auto">
                        <span uk-icon="icon: commenting; ratio: 2"></span>
                    </div>
                    <div class="uk-width-expand">
                        <h3 class="uk-card-title uk-margin-remove-bottom"><?= $entity->email ?></span></h3>
                        <p class="uk-text-meta uk-margin-remove-top">
                            <?php if($entity->www) : ?><span uk-icon="world"></span> <span><?= $entity->www ?></span><br><?php endif; ?>
                            <?php if($entity->phone) : ?><span uk-icon="receiver"></span> <span><?= $entity->phone ?></span><br><?php endif; ?>
                            <?php if($entity->mobile) : ?><span uk-icon="phone"></span> <span><?= $entity->mobile ?></span><br><?php endif; ?>
                            <?php if($entity->fax) : ?><span uk-icon="print"></span> <span><?= $entity->fax ?></span><br><?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if($entity->addressId) : ?>
            <div class="uk-width-1-3@s">
                <div class="uk-grid-small uk-flex-top" uk-grid>
                    <div class="uk-width-auto">
                        <span uk-icon="icon: location; ratio: 2"></span>
                    </div>
                    <div class="uk-width-expand">
                        <h3 class="uk-card-title uk-margin-remove-bottom"><?= $entity->zip ?> <?= $entity->city ?></h3>
                        <p class="uk-text-meta uk-margin-remove-top">
                            <?php if($entity->street) : ?><span uk-icon="bookmark"></span><span> <?= $entity->street ?> <?= $entity->street_hno ?></span><br><?php endif; ?>
                            <?php if($entity->country) : ?>
                                <span uk-icon="world"></span><span> <?= $entity->country ?></span><?php if($entity->state) : ?>, <?= $entity->state ?><?php endif; ?>
                                <?php if($entity->region) : ?> (<?= $entity->region ?>)<?php endif; ?>
                                <br>
                            <?php endif; ?>

                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <?php
    }

    private function renderCommonBasicEntity(): void {
        $entity = UserContext::$Instance->crm->commonEntity;
        ?>
        <h2>Basic information</h2>
        [form action="/user/crm/basic/<?= $entity ? 'update' : 'create' ?>" nonce="<?= CrmController::NONCE_SECRET . 'createorupdate' ?>"]
            [form-hidden-input name="type" value="<?= new CrmCommonType()->getType() ?>"]
            [form-hidden-input label="id" name="id" value="<?= $entity?->id ?: '' ?>"]
            [form-text-input label="Salutation" name="salutation" value="<?= $entity?->salutation ?: '' ?>"]
            [form-text-input label="Title" name="title" value="<?= $entity?->title ?: '' ?>"]
            [form-text-input label="Firstname" name="firstname" value="<?= $entity?->firstname ?: '' ?>"]
            [form-text-input label="Lastname" name="name" value="<?= $entity?->name ?: '' ?>"]
            [form-date-input label="Date of birth" name="birthdate" value="<?= $entity?->birthdate?->format('Y-m-d') ?>"]
            [form-text-input label="VAT ID" name="vatid" value="<?= $entity?->vatId ?: '' ?>"]
            [form-submit]
        [/form]
        <?php
    }

    private function renderCommonContactEntity(): void {
        $entity = UserContext::$Instance->crm->commonEntity;
        if(!$entity->id) { return; }
        ?>
        <h2>Contact information</h2>
        [form action="/user/crm/contact/<?= $entity ? 'update' : 'create' ?>" nonce="<?= CrmController::NONCE_SECRET . 'createorupdate' ?>"]
            [form-hidden-input label="id" name="id" value="<?= $entity?->id ?: '' ?>"]
            [form-hidden-input label="contact-id" name="contact-id" value="<?= $entity?->contactId ?: '' ?>"]
            [form-text-input label="Phone" name="phone" value="<?= $entity?->phone ?: '' ?>"]
            [form-text-input label="Mobile" name="mobile" value="<?= $entity?->mobile ?: '' ?>"]
            [form-text-input label="Email" name="email" value="<?= $entity?->email ?: '' ?>"]
            [form-text-input label="Internet" name="www" value="<?= $entity?->www ?: '' ?>"]
            [form-text-input label="Fax" name="fax" value="<?= $entity?->fax ?: '' ?>"]
        [form-submit]
        [/form]
        <?php
    }

    private function renderCommonAddressEntity(): void {
        $entity = UserContext::$Instance->crm->commonEntity;
        if(!$entity->id) { return; }
        ?>
        <h2>Address information</h2>
        [form action="/user/crm/address/<?= $entity ? 'update' : 'create' ?>" nonce="<?= CrmController::NONCE_SECRET . 'createorupdate' ?>"]
            [form-hidden-input label="id" name="id" value="<?= $entity?->id ?: '' ?>"]
            [form-hidden-input label="address-id" name="address-id" value="<?= $entity?->addressId ?: '' ?>"]
            [form-text-input label="Street" name="street" value="<?= $entity?->street ?: '' ?>"]
            [form-text-input label="House no." name="street_hno" value="<?= $entity?->street_hno ?: '' ?>"]
            [form-text-input label="Zip" name="zip" value="<?= $entity?->zip ?: '' ?>"]
            [form-text-input label="City" name="city" value="<?= $entity?->city ?: '' ?>"]
            [form-text-input label="Country" name="country" value="<?= $entity?->country ?: '' ?>"]
            [form-text-input label="State" name="state" value="<?= $entity?->state ?: '' ?>"]
            [form-text-input label="region" name="region" value="<?= $entity?->region ?: '' ?>"]
            [form-submit]
        [/form]
        <?php
    }

}
