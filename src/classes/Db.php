<?php

namespace Oblivion;

class Db
{
    public $conn;

    function __construct()
    {
        $this->conn = mysqli_connect($_ENV['DATABASE_HOSTNAME'], $_ENV['DATABASE_USERNAME'], $_ENV['DATABASE_PASSWORD'], $_ENV['DATABASE_NAME']);
    }
    function query($query)
    {
        return mysqli_query($this->conn, $query);
    }
    function error()
    {
        return mysqli_error($this->conn);
    }
}