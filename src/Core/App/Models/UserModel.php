<?php

class UserModel extends Model
{

    var string $username;
    var string $firstname;
    var string $lastname;
    var string $email;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array[UserModel]
     */
    public function getAll(): array
    {
        return $this->loadUsers();
    }

    public function getByUsername(string $username): UserModel|null
    {
        return array_find($this->loadUsers(), function (UserModel $user) use ($username) {
            return $user->username === $username;
        });
    }

    /**
     * @return UserModel[]
     */
    private function loadUsers(): array
    {

        $json = '
        [
            {
                "username": "max",
                "firstname": "Max",
                "lastname": "Mustermann",
                "email": "max@mustermann.de"
            },
            {
                "username": "maria",
                "firstname": "Maria",
                "lastname": "Musterfrau",
                "email": "maria@musterfrau.de"
            }
        ]
        ';

        $users = [];
        foreach (json_decode($json) as $userFromJson) {
            $user = new UserModel();
            $user->username = $userFromJson->username;
            $user->firstname = $userFromJson->firstname;
            $user->lastname = $userFromJson->lastname;
            $user->email = $userFromJson->email;
            $users[] = $user;
        }

        return $users;
    }
}