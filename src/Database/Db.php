<?php

namespace App\Database;

use PDO;

class Db
{
    private $dbhost = "localhost";
    private $dbuser = "root";
    private $dbpass = "";
    private $dbname = "slim-rest-test";

    public function connect()
    {
        $mysql_connection = "mysql:host=$this->dbhost;dbname=$this->dbname;charset=utf8";
        $connection = new PDO($mysql_connection, $this->dbuser, $this->dbpass);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    }
}