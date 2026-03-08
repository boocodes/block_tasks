<?php

namespace StorageTask2\Application\Database\Services;
use PDO;
use PDOException;


class ConnectionService
{
    private string $host;
    private string $user;
    private string $password;
    private string $database;
    private PDO $connection;

    public function __construct(string $host, string $user, string $password, string $database)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;

        $this->setConnection();
    }

    private function setConnection(): void
    {
        try {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password);
            $this->connection->exec("set names utf8");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            throw $e;
        }
    }
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}