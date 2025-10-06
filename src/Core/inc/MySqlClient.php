<?php

class MySqlClient {
    public static function QueryRaw(string $queryString): bool|mysqli_result {
        try {
            $conn = self::Connect();
            return $conn->query($queryString);
        } catch(Exception $e) {
            Logger::Error(__FILE__, $e->getMessage());
            return false;
        }
    }

    public static function ExecuteQueryRaw(string $queryString): void {
        try {
            $conn = self::Connect();
            $conn->execute_query($queryString);
            $conn->close();
        } catch(Exception $e) {
            Logger::Error(__FILE__, $e->getMessage());
        }
    }

    public static function Connect() : mysqli {
        return new mysqli(Config::$MySql->host, Config::$MySql->username, Config::$MySql->password, Config::$MySql->database);

    }
}