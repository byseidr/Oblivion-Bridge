<?php

namespace Oblivion;

class Db
{
    static function query($query)
    {
        return mysqli_query($conn, $query);
    }
    static function error()
    {
        return mysqli_error($conn);
    }
}