<?php

class CrmController extends Controller {

    public const string NONCE_SECRET = 'user-nonce-secret-IkozK4z2OA';

    public function __construct(Core $core) {
        parent::__construct($core);

        $this->registerRoute(new RegexCtrlRoute(
            '#^/user/crm/(?P<branch>[^/]+)/(?P<method>[^/]+)/?$#',
            [ $this, 'create' ],
            [
                'id' => '',
                'type' => '',
                'salutation' => '',
                'title' => '',
                'firstname' => '',
                'name' => '',
                'birthdate' => '',
                'vatid' => '',
                'contact-id' => '',
                'phone' => '',
                'mobile' => '',
                'email' => '',
                'www' => '',
                'fax' => '',
                'address-id' => '',
                'street' => '',
                'street_hno' => '',
                'zip' => '',
                'city' => '',
                'country' => '',
                'state' => '',
                'region' => '',
            ],
            self::NONCE_SECRET.'createorupdate'));

    }

    public function create(HttpRequestContext $request): HttpResultContext {
        $args = $request->route->getRequestArguments();
        switch($args['branch']) {
            case 'basic':
                $e = UserContext::$Instance->crm->commonEntity;
                $e->id = $args['id'];
                $e->entityId = $args['id'];
                $e->userUid = UserContext::$Instance->user->uid;
                $e->typeUid = $args['type'];
                $e->title = $args['title'];
                $e->birthdate = $args['birthdate'] ? new DateTime($args['birthdate']) : null;
                $e->salutation = $args['salutation'];
                $e->firstname = $args['firstname'];
                $e->name = $args['name'];
                $e->vatId = $args['vatid'];

                if($args['method'] == 'update') {
                    UserContext::$Instance->crm->updateCommonEntity($e);
                } else if($args['method'] == 'create') {
                    UserContext::$Instance->crm->createCommonEntity($e);
                }

                break;
            case 'contact':
                $e = UserContext::$Instance->crm->commonEntity;
                $e->id = $args['id'];
                $e->entityId = $args['id'];
                $e->contactId = $args['contact-id'];
                $e->phone = $args['phone'];
                $e->mobile = $args['mobile'];
                $e->email = $args['email'];
                $e->www = $args['www'];
                $e->fax = $args['fax'];

                if($args['method'] == 'update') {
                    UserContext::$Instance->crm->updateCommonEntity($e);
                } else if($args['method'] == 'create') {
                    UserContext::$Instance->crm->createCommonEntity($e);
                }
                break;
            case 'address':
                $e = UserContext::$Instance->crm->commonEntity;
                $e->id = $args['id'];
                $e->entityId = $args['id'];
                $e->contactId = $args['address-id'];
                $e->street = $args['street'];
                $e->street_hno = $args['street_hno'];
                $e->zip = $args['zip'];
                $e->city = $args['city'];
                $e->country = $args['country'];
                $e->state = $args['state'];
                $e->region = $args['region'];

                if($args['method'] == 'update') {
                    UserContext::$Instance->crm->updateCommonEntity($e);
                } else if($args['method'] == 'create') {
                    UserContext::$Instance->crm->createCommonEntity($e);
                }
                break;
        }



        return new RedirectHttpResult('/dashboard/user/crm/manage');
    }
}