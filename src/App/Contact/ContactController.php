<?php

class ContactController extends Controller {

    public const string NONCE_SECRET = 'contact-nonce-secret-tYSWMDSHRk';

    public function __construct(Core $core) {
        parent::__construct($core);

        $this->registerRoute(new ViewRoute('/contact', ContactView::class));
        $this->registerRoute(new ViewRoute('/contacted', ContactedView::class));
        $this->registerRoute(new CtrlRoute('/contact', [ $this, 'contact' ], [
            'name' => null,
            'email' => null,
            'message' => null,
        ], self::NONCE_SECRET));
    }

    public function contact(HttpRequestContext $request) : ViewHttpResult {
        $args = $request->route->getRequestArguments();

        new ContactModelRepository()->add(new ContactModel($args['name'], $args['email'], $args['message']));

        return new ViewHttpResult(ContactedView::class);
    }
}