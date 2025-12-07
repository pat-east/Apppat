<?php

class SystemController extends Controller {
    public const string NONCE_SECRET = 'system-nonce-secret-gI0d4SKzId';

    public function __construct(Core $core) {
        parent::__construct($core);

        $this->registerRoute(new CtrlRoute(
            '/system/mail/test',
            [ $this, 'sendMailTest' ],
            [ 'receiver' => '' ],
            self::NONCE_SECRET));
    }

    public function sendMailTest(HttpRequestContext $request): HttpResultContext {

        $receiver = $request->route->getRequestArguments()['receiver'];
        if (!filter_var($receiver, FILTER_VALIDATE_EMAIL)) {
            return new ViewHttpResult('MailSystemSettingsView',
                [
                    'status' => MailSystemSettingsView::STATUS_INVALID_MAIL
                ]);
        }

        $mail = new Mail();

        try {
            $mail->setHeadline('Test mail');
            $mail->setSubline('This is just a test mail');
            $mail->setSubject('Some mailing test');
            $mail->setBody('<p>This mail sent to test mail delivery</p>');
            $mail->addReceiver($receiver);
            $mail->send();
        } catch(Exception $e) {
            return new ViewHttpResult('MailSystemSettingsView',
                [
                    'status' => MailSystemSettingsView::STATUS_TEST_MAIL_FAILED
                ]);
        }


        return new ViewHttpResult('MailSystemSettingsView',
            [
                'status' => MailSystemSettingsView::STATUS_TEST_MAIL_SUCCESS
            ]);
    }
}