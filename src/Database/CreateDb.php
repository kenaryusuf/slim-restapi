<?php

namespace App\Database;

use PDO;
Use PDOException;

class CreateDb
{
    public function createDb($db){

        try {
            $sql = "
    CREATE TABLE posts
    (
        id     INT PRIMARY KEY,
        userId INT,
        title  VARCHAR(255),
        body   TEXT
    );

    CREATE TABLE comments
    (
        id     INT PRIMARY KEY,
        postId INT,
        name   VARCHAR(255),
        email  VARCHAR(255),
        body   TEXT
    );
    ";

            $db->exec($sql);
            echo "  Tables created successfully  ";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return 0;
    }
}
