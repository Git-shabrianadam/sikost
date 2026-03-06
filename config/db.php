<?php

class Database {

    private $host = "localhost";
    private $port = "5432";
    private $db_name = "kost_db";
    private $username = "postgres";
    private $password = "12345678";

    public $conn;

    public function getConnection() {

        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name};";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $exception) {

            echo "Database connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>