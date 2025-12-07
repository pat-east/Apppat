<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Random\RandomException;


class UserModel extends DatabaseModel {

    const string TableName = 'web_users';


    public static function GetByUsernameOrEmail(string $usernameOrEmail): UserModel|null {
        $user = Capsule::table(self::TableName)
            ->where("username", $usernameOrEmail)
            ->orWhere("email", $usernameOrEmail)
            ->first();
        if (!$user) {
            return null;
        }
        return new UserModel($user);
    }

    public static function GetByUid(string $uid): UserModel|null {
        return self::GetBy('uid', $uid);
    }

    public static function GetByUsername(string $username): UserModel|null {
        return self::GetBy('username', $username);
    }

    public static function GetByEmail(string $email): UserModel|null {
        return self::GetBy('email', $email);
    }

    public static function GetBy(string $field, string $value): UserModel|null {
        $user = Capsule::table(self::TableName)
            ->where($field, $value)
            ->first();
        return $user ? new UserModel($user) : null;
    }

    /**
     * @return UserModel[]
     */
    public static function GetAll(): array {
        $users = Capsule::table(self::TableName)->get();
        $userModels = [];
        foreach ($users as $user) {
            $userModels[] = new UserModel($user);
        }
        return $userModels;
    }

    public static function UserExists(?string $username = null, ?string $email = null): bool {
        $users = Capsule::table(self::TableName)
            ->where("username", $username)
            ->orWhere("email", $email)
            ->count();

        return $users > 0;
    }

    /**
     * @throws Exception
     */
    public static function Create(string $username, string $email, string $password, ?DateTime $passwordExpiresAt = null): UserModel|null {
        if (self::GetByUsername($username) || self::GetByEmail($email)) {
            throw new Exception('User already exists.');
        }
        if(!$passwordExpiresAt) {
            $passwordExpiresAt = (new DateTime())->add(new DateInterval('P'.Config::$AppConfig->PasswordExpirationInDays.'D'));
        }
        Capsule::table(self::TableName)->insert([
            'uid' => Crypto::UuidV4(),
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'password_expires_at' => $passwordExpiresAt,
            'created_at' => new DateTime(),
        ]);

        return self::GetByUsername($username);
    }

    var string $uid;
    var string $username;
    var string $email;
    var bool $totpEnabled;

    public function __construct($user = null) {
        parent::__construct(self::TableName);

        if ($user) {
            $this->uid = $user->uid;
            $this->username = $user->username;
            $this->email = $user->email;
            $this->totpEnabled = $user->totp_enabled === Defaults::YES;
        }
    }

    public function update(string $field, $value): void {
        Capsule::table(self::TableName)
            ->where('username', $this->username)
            ->update([$field => $value, 'updated_at' => new DateTime()]);
    }

    public function getPasswordHash(): string {
        $user = Capsule::table(self::TableName)
            ->where('username', $this->username)
            ->first();
        return $user->password;
    }

    public function getTotpSecret(): ?string {
        $user = Capsule::table(self::TableName)
            ->where('username', $this->username)
            ->first();
        return $user->totp_secret;
    }

    public function getTotpRecoverKey(): ?string {
        return Capsule::table(self::TableName)
            ->where('username', $this->username)
            ->select('totp_recovery_key')
            ->first()
            ->totp_recovery_key;
    }

    public function setTotpSecret(?string $secret): void {
        $this->update("totp_secret", $secret);
    }

    public function setTotpRecoverKey(?string $totpRecoverKey): void {
        $this->update("totp_recovery_key", $totpRecoverKey);
    }

    public function updatePassword(string $password): void {
        $this->update("password", password_hash($password, PASSWORD_DEFAULT));
        $expiration = new DateTime();
        $this->update("password_expires_at",
            (new DateTime())->add(new DateInterval('P'.Config::$AppConfig->PasswordExpirationInDays.'D')));
    }

    /**
     * @throws RandomException
     * @throws Exception
     */
    protected function createTable(): void {
        Capsule::schema()->create(self::TableName, function ($table) {
            $table->string('uid', 36);
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->datetime('password_expires_at')->nullable();
            $table->string('totp_enabled', 1)->nullable();
            $table->string('totp_secret', 4096)->nullable();
            $table->string('totp_recovery_key', 4096)->nullable();

            $table->timestamps();
            $table->primary('uid');
        });

        /* TODO
         * Move creating an admin-account to a setup-routine which will be introduced later on. */
        $adminUsr = 'admin_' . Crypto::CreateRandomString(8, 'abcdefghijklmnopqrstuvwxyz0123456789');
        $adminPwd = UserCredentials::CreateRandomPassword();
        self::Create($adminUsr, '', $adminPwd, new DateTime());
        Log::Info(__FILE__, 'Created admin user [username=%s; password=%s]', $adminUsr, $adminPwd);
    }

}
