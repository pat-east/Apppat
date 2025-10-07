<?php

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

class Config {
    /** @var array<string, string> */
    static array $dotenv = [];

    public static MySqlConfig $MySql;

    public static SessionConfig $Session;

    public function init(): void {
        if(self::$dotenv == null) {
            if(file_exists(Defaults::DOTENVPATH)) {
                self::$dotenv = parse_ini_file(Defaults::DOTENVPATH);
                self::$MySql = new MySqlConfig(self::$dotenv);
                self::$Session = new SessionConfig(self::$dotenv);
            }

        }
    }
}