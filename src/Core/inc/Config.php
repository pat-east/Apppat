<?php

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

class Config {
    /** @var array<string, string> */
    static array $dotenv = [];

    public static MySqlConfig $MySql;

    public static SessionConfig $Session;

    public static EnvironmentConfig $Environment;

    public static CrytpoConfig $Crypto;

    public function init(): void {
        if(self::$dotenv == null) {
            if(file_exists(Defaults::DOTENVPATH)) {
                self::$dotenv = parse_ini_file(Defaults::DOTENVPATH);
                self::$MySql = new MySqlConfig(self::$dotenv);
                self::$Environment = new EnvironmentConfig(self::$dotenv);
                self::$Session = new SessionConfig(self::$dotenv);
                self::$Crypto = new CrytpoConfig(self::$dotenv);
            } else {
                throw new Exception('.env file does not exist');

            }

        }
    }
}