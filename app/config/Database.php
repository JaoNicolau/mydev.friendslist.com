<?php

class DataBase {
    private $host = "localhost";
    private $dbname = "mydevfriendslist";
    private $username = "root";
    private $password = "";

    public function connect() {
            $conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->username, $this->password);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
    }
}
