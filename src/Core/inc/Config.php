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

class Config {
    /** @var array<string, string> */
    static array $dotenv = [];

    public static MySqlConfig $MySql;

    public function init(): void {
        if(self::$dotenv == null) {
            if(file_exists(Defaults::DOTENVPATH)) {
                self::$dotenv = parse_ini_file(Defaults::DOTENVPATH);
                self::$MySql = new MySqlConfig(self::$dotenv);
            }
        }
    }
}