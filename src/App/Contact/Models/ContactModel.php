<?php

class ContactModel extends Model {

    public int $id;
    public string $name;
    public string $email;
    public string $message;
    public DateTime $created_at;

    public function __construct(string $name, string $email, string $message, ?DateTime $created_at = null, int $id = -1) {
        parent::__construct();
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
        $this->created_at = $created_at;
        $this->id = $id;
    }

}