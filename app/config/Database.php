<?php

class DataBase{
    private $host = "localhost";
    private $db_name = "mydevfriendslist";
    private $username = "root";
    private $password = "";

    public function connect(){
        $conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
}