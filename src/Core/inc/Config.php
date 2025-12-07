<?php

class AppConfig {

    /** @var bool
     * If this option is enabled, password recovery will always give
     * status StatusRecoveryMailSent, even if no account exists.
     */
    public bool $PasswordRecoveryFalsePositive;

    public int $PasswordExpirationInDays = 365;

    public function __construct(array $dotenv) {
        $this->PasswordRecoveryFalsePositive = true;
    }
}

class EnvironmentConfig {

    public string $noncePayload;

    /**
     * @param array<string, string> $dotenv
     */
    public function __construct(array $dotenv) {
        $this->noncePayload = $dotenv['NONCE_PAYLOAD'] ?? '';
    }

    public function isDevEnvironment(): bool {
        return str_ends_with($_SERVER['HTTP_HOST'], '.local');
    }
}

class MySqlConfig {
    public string $database;
    public string $host;
    public string $username;
    public string $password;
    public string $tablePrefix;

    /**
     * @param array<string, string> $dotenv
     */
    public function __construct(array $dotenv) {
        $this->database = $dotenv['MYSQL_DATABASE'] ?? '';
        $this->host = $dotenv['MYSQL_HOST'] ?? '';
        $this->username = $dotenv['MYSQL_USERNAME'] ?? '';
        $this->password = $dotenv['MYSQL_PASSWORD'] ?? '';
        $this->tablePrefix = $dotenv['MYSQL_TABLE_PREFIX'] ?? '';
    }
}

class SessionConfig {
    public string $name = 'apppat_session';
    public string $cookie_path = '/';
    public int $cookie_lifetime = 60 * 60 * 24; // For one day
    public string $cookie_domain = '';
    public int $cookie_secure = 1;
    public int $cookie_httponly = 1;
    public string $cookie_samesite = 'strict';
    public int $use_only_cookies = 1;
    public int $use_strict_mode = 1;
    public int $sid_length = 64;

    public function __construct(array $dotenv) {
        // TODO allow setting properties using .env
    }
}

class CrytpoConfig {
    public const int DefaultKeyLength = 4096;

    private ?string $userRsaKeyPairBaseDir = null;

    public function __construct(array $dotenv) {
        $this->userRsaKeyPairBaseDir = $dotenv['USER_RSA_KEY_PAIR_BASE_DIR'] ?? '';
        if(!$this->userRsaKeyPairBaseDir) {
            throw new Exception('USER_RSA_KEY_PAIR_BASE_DIR not configured in .env');
        }

        if(!file_exists($this->userRsaKeyPairBaseDir)) {
            mkdir($this->userRsaKeyPairBaseDir, 0700, true);
        }
    }

    public function getUserRsaKeyPairBaseDir(): ?string {
        return $this->userRsaKeyPairBaseDir;
    }
}

class MailConfig {
    public bool $useSmtp;
    public string $smtpHost;
    public int $smtpPort;
    public string $smtpUsername;
    public string $smtpPassword;
    public string $smtpEncryption;

    public string $fromAddress;
    public string $fromName;

    public int $smtpTimeout = 10;

    /**
     * @param array<string, string> $dotenv
     */
    public function __construct(array $dotenv) {
        $this->useSmtp = isset($dotenv['USE_SMTP']) && $dotenv['USE_SMTP'];
        $this->smtpHost = $dotenv['SMTP_HOST'] ?? '';
        $this->smtpPort = isset($dotenv['SMTP_PORT']) ? intval($dotenv['SMTP_PORT']) : 587; // We dont use 25 anymore
        $this->smtpUsername = $dotenv['SMTP_USERNAME'] ?? '';
        $this->smtpPassword = $dotenv['SMTP_PASSWORD'] ?? '';
        $this->fromAddress = $dotenv['FROM_ADDRESS'] ?? '';
        $this->fromName = $dotenv['FROM_NAME'] ?? '';
        $this->smtpEncryption = $dotenv['SMTP_ENCRYPTION'] ?? '';
        if($this->smtpEncryption && !in_array($this->smtpEncryption, ['ssl', 'tls'])) {
            throw new Exception(
                sprintf(
                    'SMTP_ENCRYPTION not properly configured using [%s]. It must be one of ["ssl", "tls"].',
                    $this->smtpEncryption));
        }
    }
}

class Config {
    /** @var array<string, string> */
    static array $dotenv = [];

    public static AppConfig $AppConfig;

    public static MySqlConfig $MySql;

    public static SessionConfig $Session;

    public static EnvironmentConfig $Environment;

    public static CrytpoConfig $Crypto;

    public static MailConfig $MailConfig;

    public function init(): void {
        if(self::$dotenv == null) {
            if(file_exists(Defaults::DOTENVPATH)) {
                self::$dotenv = parse_ini_file(Defaults::DOTENVPATH);
                self::$AppConfig = new AppConfig(self::$dotenv);
                self::$MySql = new MySqlConfig(self::$dotenv);
                self::$Environment = new EnvironmentConfig(self::$dotenv);
                self::$Session = new SessionConfig(self::$dotenv);
                self::$Crypto = new CrytpoConfig(self::$dotenv);
                self::$MailConfig = new MailConfig(self::$dotenv);
            } else {
                throw new Exception('.env file does not exist');

            }

        }
    }
}