<?php

class ContactController extends Controller {

    public const string NONCE_SECRET = 'contact-nonce-secret-tYSWMDSHRk';

    public function __construct(Core $core) {
        parent::__construct($core);

        $this->registerRoute(new ViewRoute('/contact', ContactView::class));
        $this->registerRoute(new ViewRoute('/contact/inbox', ContactInboxView::class));
        $this->registerRoute(new ViewRoute('/contacted', ContactedView::class));
        $this->registerRoute(new CtrlRoute('/contact', [ $this, 'contact' ]));
    }

    public function contact(HttpRequestContext $request) : ViewHttpResult {

        // If using csrf-token
//        if(!$request->verifyCsrfToken()) {
//            return new ViewHttpResult(ContactView::class);
//        }

        // Else using nonces
        if(!$request->verifyNonce(self::NONCE_SECRET)) {
            return new ViewHttpResult(ContactView::class);
        }

        $args = $request->parseArgs($_POST, [
            'name' => null,
            'email' => null,
            'message' => null,
        ]);

        new ContactModelRepository()->add(new ContactModel($args['name'], $args['email'], $args['message']));

        return new ViewHttpResult(ContactedView::class);
    }
}