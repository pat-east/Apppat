<?php

class ContactController extends Controller {
    public function __construct(Core $core) {
        parent::__construct($core);

        $this->registerRoute(new ViewRoute('/contact', ContactView::class));
        $this->registerRoute(new ViewRoute('/contact/inbox', ContactInboxView::class));
        $this->registerRoute(new ViewRoute('/contacted', ContactedView::class));
        $this->registerRoute(new CtrlRoute('/contact', [ $this, 'contact' ]));
    }

    public function contact(HttpRequestContext $request) : ViewHttpResult {

        $args = $request->ParseArgs($_POST, [
            'name' => null,
            'email' => null,
            'message' => null,
        ]);

        $model = new ContactModelRepository()->add(new ContactModel($args['name'], $args['email'], $args['message']));
        Logger::Object(__FILE__, $model);

        return new ViewHttpResult(ContactedView::class);
    }
}