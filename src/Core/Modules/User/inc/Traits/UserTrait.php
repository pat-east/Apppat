<?php

trait UserTrait {
    var string $uid;
    var string $username;
    var string $email;
    var ?string $roles;
    var bool $totpEnabled;
}