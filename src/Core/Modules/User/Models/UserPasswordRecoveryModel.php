<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class UserPasswordRecoveryModel extends DatabaseModel {

    const string TableName = 'web_users_pwd_recovery';

    const string TypeRecovery = 'recovery';
    const string TypeOnBoarding = 'on-boarding';
    const string StatusNew = 'new';
    const string StatusRecovered = 'recovered';

    public static function GetBy(string $field, string $value): UserPasswordRecoveryModel|null {
        $recovery = Capsule::table(self::TableName)
            ->where($field, $value)
            ->first();
        return $recovery ? new UserPasswordRecoveryModel($recovery) : null;
    }

    public static function GetByUid(string $uid): UserPasswordRecoveryModel|null {
        return self::GetBy('uid', $uid);
    }

    public static function GetByRecoveryToken(string $recoveryToken): UserPasswordRecoveryModel|null {
        return self::GetBy('recovery_token', $recoveryToken);
    }

    public static function CreateRecovery(string $userUid, string $username, string $email,
                                          string $type = self::TypeRecovery,
                                          ?DateTime $expiresAt = null): UserPasswordRecoveryModel {
        $uid = Crypto::UuidV4();
        $recoveryToken = Crypto::Sha515(Crypto::CreateRandomString(2048, Crypto::PasswordKeyspace));
        if(!$expiresAt) {
            $expiresAt = new DateTime()->add(new DateInterval('PT24H'));
        }
        Capsule::table(self::TableName)->insert([
            'uid' => $uid,
            'user_uid' => $userUid,
            'username' => $username,
            'email' => $email,
            'type' => $type,
            'status' => self::StatusNew,
            'recovery_token' => $recoveryToken,
            'expires_at' => $expiresAt,
        ]);

        return self::GetByUid($uid);
    }

    var ?string $uid;
    var ?string $userUid;
    var ?string $username;
    var ?string $email;
    var ?string $type;
    var ?string $status;
    var ?string $recoveryToken;
    var ?DateTime $expiresAt;

    public function __construct($recovery = null) {
        parent::__construct(self::TableName);

        if($recovery) {
            $this->uid = $recovery->uid;
            $this->userUid = $recovery->user_uid;
            $this->username = $recovery->username;
            $this->email = $recovery->email;
            $this->type = $recovery->type;
            $this->status = $recovery->status;
            $this->recoveryToken = $recovery->recovery_token;
            $this->expiresAt = new DateTime($recovery->expires_at);
        }
    }


    protected function createTable(): void {
        Capsule::schema()->create(self::TableName, function ($table) {
            $table->string('uid', 36);
            $table->string('user_uid', 36);
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->string('recovery_token', 2048)->nullable();
            $table->timestamps();
            $table->datetime('expires_at')->nullable();
            $table->primary('uid');
        });
    }
}