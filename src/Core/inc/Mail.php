<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail {

    public PHPMailer $phpMailer;

    /** @var array<string> */
    private array $receiver;
    private ?string $subject;
    private ?string $headline;
    private ?string $subline;
    private ?string $body;
    private MailTemplate $mailTemplate;

    public function __construct() {
        $this->phpMailer = new PHPMailer(true);
        $this->receiver = [];
        $emailTplClass = Helper::GetDerivingClass('MailTemplate');
        $this->mailTemplate = new $emailTplClass();
        $this->init();
    }

    /**
     * @throws Exception
     */
    public function send(): bool {
        $this->phpMailer->Subject = sprintf('%s | %s', Defaults::APPTITLE, $this->subject);
        $this->phpMailer->Body = $this->mailTemplate->template($this->body, $this->headline, $this->subline);
        foreach($this->receiver as $receiver) {
            $this->phpMailer->addAddress($receiver[0], $receiver[1]); // see addReceiver()
        }

        return $this->phpMailer->send();
    }

    public function addReceiver($receiver, $name = '') {
        $this->receiver[] = [$receiver, $name];
    }

    public function setSubject(string $subject) {
        $this->subject = $subject;
    }

    public function setBody(string $body) {
        $this->body = $body;
    }

    public function setHeadline(string $headline) {
        $this->headline = $headline;
    }

    public function setSubline(string $subline) {
        $this->subline = $subline;
    }

    private function init() {

        if(Config::$MailConfig->useSmtp) {
            $this->phpMailer->isSMTP();
            $this->phpMailer->Host = Config::$MailConfig->smtpHost;
            if(Config::$MailConfig->smtpUsername) {
                $this->phpMailer->SMTPAuth = true;
                $this->phpMailer->Username = Config::$MailConfig->smtpUsername;
                $this->phpMailer->Password = Config::$MailConfig->smtpPassword;
            }
            $this->phpMailer->SMTPSecure = Config::$MailConfig->smtpEncryption;
            $this->phpMailer->Port = Config::$MailConfig->smtpPort;
            $this->phpMailer->setFrom(Config::$MailConfig->fromAddress, Config::$MailConfig->fromName);
            $this->phpMailer->isHTML(); // Always send mails in html format
            $this->phpMailer->Timeout = Config::$MailConfig->smtpTimeout;
        }
    }
}