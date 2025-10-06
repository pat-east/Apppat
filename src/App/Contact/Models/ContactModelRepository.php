<?php

class ContactModelRepository {
    public const TABLE_NAME = 'contact';

    public static function Init(): void {
        $query = sprintf('CREATE TABLE IF NOT EXISTS %s%s(
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(64) NOT NULL,
            email VARCHAR(128) NOT NULL,
            message TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;', Config::$MySql->tablePrefix, self::TABLE_NAME);

        MySqlClient::ExecuteQueryRaw($query);
    }

    public function __construct() {

    }

    public function get($id) : ContactModel|null {
        $sql = sprintf('SELECT * FROM %s%s WHERE id = ?',
            Config::$MySql->tablePrefix, self::TABLE_NAME);
        $conn = MySqlClient::Connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $results = $stmt->get_result();
        $model = null;
        if ($results->num_rows > 0) {
            while ($row = $results->fetch_assoc()) {
                $model = new ContactModel($row['name'], $row['email'], $row['message'], new DateTime($row['created_at']), $row['id']);
                break;
            }
        }
        $stmt->close();
        $conn->close();
        return $model;
    }

    /**
     * @return ContactModel[]
     */
    public function getAll() : array {
        $sql = sprintf('SELECT * FROM %s%s',
            Config::$MySql->tablePrefix, self::TABLE_NAME);
        $conn = MySqlClient::Connect();
        $results = $conn->query($sql);
        $models = [];
        if ($results->num_rows > 0) {
            while ($row = $results->fetch_assoc()) {
                $models[] = new ContactModel($row['name'], $row['email'], $row['message'], new DateTime($row['created_at']), $row['id']);
            }
        }
        $conn->close();
        return $models;
    }

    public function add(ContactModel $contact) : ContactModel {
        $sql = sprintf('INSERT INTO %s%s (name, email, message) VALUES (?, ?, ?)',
            Config::$MySql->tablePrefix, self::TABLE_NAME);
        $conn = MySqlClient::Connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $contact->name, $contact->email, $contact->message);
        $stmt->execute();

        $contactId = $conn->insert_id;

        $stmt->close();
        $conn->close();

        return $this->get($contactId);
    }
}
