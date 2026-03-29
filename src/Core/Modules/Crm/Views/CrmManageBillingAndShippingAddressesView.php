<?php

class CrmManageBillingAndShippingAddressesView extends View {

    public function getBreadcrumbItems(): array {
        return [
            [ 'label' => 'Dashboard', 'href' => '/dashboard' ],
            [ 'label' => 'User', 'href' => null ],
            [ 'label' => 'CRM', 'href' => '/dashboard/user/crm/manage' ],
            [ 'label' => 'Billing & Shipping', 'href' => null ],
        ];
    }

    public function render(): void {
        $commonEntity = UserContext::$Instance->crm->commonEntity;
        ?>
        <div class="uk-section uk-section-muted">
            <div class="uk-container">
                <div class="">
                    <?php $this->renderAddressSummary(); ?>
                </div>
            </div>
        </div>
        <div class="uk-section">
            <div class="uk-container">
                <div class="">
                    <ul uk-tab>
                        <li class="uk-active"><a href="#">Billing address</a></li>
                        <li class="<?= !$commonEntity->id ? 'uk-disabled' : '' ?>"><a href="#">Shipping address</a></li>
                    </ul>
                    <div class="uk-switcher uk-margin">
                        <div role="tabpanel">
                            <?php $this->renderBillingAddress(); ?>
                        </div>
                        <div role="tabpanel">
                            <?php $this->renderShippingAddress(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private function renderAddressSummary(): void {
        $billingEntity = UserContext::$Instance->crm->billingAddressEntity;
        $shippingEntity = UserContext::$Instance->crm->shippingAddressEntity;
        ?>
        <div uk-grid>
            <div class="uk-width-1-2@s">
                <div class="uk-grid-small uk-flex-top" uk-grid>
                    <div class="uk-width-auto">
                        <span uk-icon="icon: credit-card; ratio: 2"></span>
                    </div>
                    <div class="uk-width-expand">
                        <h3 class="uk-card-title uk-margin-remove-bottom">Billing address</h3>
                        <p class="uk-text-meta uk-margin-remove-top">
                            <?php if($billingEntity->getDisplayName()) : ?><span uk-icon="user"></span><span> <?= $billingEntity->getDisplayName() ?></span><br><?php endif; ?>
                            <?php if($billingEntity->vatId) : ?><span uk-icon="code"></span><span> <?= $billingEntity->vatId ?></span><br><?php endif; ?>
                            <?php if($billingEntity->street) : ?><span uk-icon="bookmark"></span><span> <?= $billingEntity->street ?> <?= $billingEntity->street_hno ?></span><br><?php endif; ?>
                            <?php if($billingEntity->zip || $billingEntity->city) : ?><span uk-icon="location"></span><span> <?= trim(($billingEntity->zip ?: '') . ' ' . ($billingEntity->city ?: '')) ?></span><br><?php endif; ?>
                            <?php if($billingEntity->country) : ?>
                                <span uk-icon="world"></span><span> <?= $billingEntity->country ?></span><?php if($billingEntity->state) : ?>, <?= $billingEntity->state ?><?php endif; ?>
                                <?php if($billingEntity->region) : ?> (<?= $billingEntity->region ?>)<?php endif; ?>
                            <?php else : ?>
                                Add your billing address details below.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-2@s">
                <div class="uk-grid-small uk-flex-top" uk-grid>
                    <div class="uk-width-auto">
                        <span uk-icon="icon: truck; ratio: 2"></span>
                    </div>
                    <div class="uk-width-expand">
                        <h3 class="uk-card-title uk-margin-remove-bottom">Shipping address</h3>
                        <p class="uk-text-meta uk-margin-remove-top">
                            <?php if($shippingEntity->getDisplayName()) : ?><span uk-icon="user"></span><span> <?= $shippingEntity->getDisplayName() ?></span><br><?php endif; ?>
                            <?php if($shippingEntity->vatId) : ?><span uk-icon="code"></span><span> <?= $shippingEntity->vatId ?></span><br><?php endif; ?>
                            <?php if($shippingEntity->street) : ?><span uk-icon="bookmark"></span><span> <?= $shippingEntity->street ?> <?= $shippingEntity->street_hno ?></span><br><?php endif; ?>
                            <?php if($shippingEntity->zip || $shippingEntity->city) : ?><span uk-icon="location"></span><span> <?= trim(($shippingEntity->zip ?: '') . ' ' . ($shippingEntity->city ?: '')) ?></span><br><?php endif; ?>
                            <?php if($shippingEntity->country) : ?>
                                <span uk-icon="world"></span><span> <?= $shippingEntity->country ?></span><?php if($shippingEntity->state) : ?>, <?= $shippingEntity->state ?><?php endif; ?>
                                <?php if($shippingEntity->region) : ?> (<?= $shippingEntity->region ?>)<?php endif; ?>
                            <?php elseif(!$billingEntity->id) : ?>
                                Create your CRM profile first. Shipping details require a CRM entity.
                            <?php else : ?>
                                Add a shipping address below.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private function renderBillingAddress(): void {
        $commonEntity = UserContext::$Instance->crm->commonEntity;
        if(!$commonEntity->id) {
            ?>
            <div class="uk-alert-primary" uk-alert>
                <p>Create your CRM profile first to manage billing and shipping addresses.</p>
            </div>
            <?php
            return;
        }

        $this->renderAddressForm(
            UserContext::$Instance->crm->billingAddressEntity,
            new CrmDebtorType()->getType(),
            'Billing address',
            'Save billing address'
        );
    }

    private function renderShippingAddress(): void {
        $commonEntity = UserContext::$Instance->crm->commonEntity;
        if(!$commonEntity->id) {
            return;
        }

        $this->renderAddressForm(
            UserContext::$Instance->crm->shippingAddressEntity,
            new CrmCreditorType()->getType(),
            'Shipping address',
            'Save shipping address'
        );
    }

    private function renderAddressForm(CrmEntity $entity, string $crmType, string $heading, string $submitLabel): void {
        ?>
        <h2><?= $heading ?></h2>
        [form action="/user/crm/address/<?= $entity->id ? 'update' : 'create' ?>" nonce="<?= CrmController::NONCE_SECRET . 'createorupdate' ?>"]
            [form-hidden-input label="id" name="id" value="<?= $entity?->id ?: '' ?>"]
            [form-hidden-input label="type" name="type" value="<?= $crmType ?>"]
            [form-hidden-input label="contact-id" name="contact-id" value="<?= $entity?->contactId ?: '' ?>"]
            [form-hidden-input label="address-id" name="address-id" value="<?= $entity?->addressId ?: '' ?>"]
            [form-text-input label="Salutation" name="salutation" value="<?= $entity?->salutation ?: '' ?>"]
            [form-text-input label="Title" name="title" value="<?= $entity?->title ?: '' ?>"]
            [form-text-input label="Firstname" name="firstname" value="<?= $entity?->firstname ?: '' ?>"]
            [form-text-input label="Lastname" name="name" value="<?= $entity?->name ?: '' ?>"]
            [form-date-input label="Date of birth" name="birthdate" value="<?= $entity?->birthdate?->format('Y-m-d') ?>"]
            [form-text-input label="VAT ID" name="vatid" value="<?= $entity?->vatId ?: '' ?>"]
            [form-text-input label="Phone" name="phone" value="<?= $entity?->phone ?: '' ?>"]
            [form-text-input label="Mobile" name="mobile" value="<?= $entity?->mobile ?: '' ?>"]
            [form-text-input label="Email" name="email" value="<?= $entity?->email ?: '' ?>"]
            [form-text-input label="Internet" name="www" value="<?= $entity?->www ?: '' ?>"]
            [form-text-input label="Fax" name="fax" value="<?= $entity?->fax ?: '' ?>"]
            [form-text-input label="Street" name="street" value="<?= $entity?->street ?: '' ?>"]
            [form-text-input label="House no." name="street_hno" value="<?= $entity?->street_hno ?: '' ?>"]
            [form-text-input label="Zip" name="zip" value="<?= $entity?->zip ?: '' ?>"]
            [form-text-input label="City" name="city" value="<?= $entity?->city ?: '' ?>"]
            [form-text-input label="Country" name="country" value="<?= $entity?->country ?: '' ?>"]
            [form-text-input label="State" name="state" value="<?= $entity?->state ?: '' ?>"]
            [form-text-input label="Region" name="region" value="<?= $entity?->region ?: '' ?>"]
            [form-submit label="<?= $submitLabel ?>"]
        [/form]
        <?php
    }
}
