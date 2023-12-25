<?php

namespace Oblivion;

if (strrpos($_SERVER["REQUEST_URI"], ".php") || strrpos($_SERVER["REQUEST_URI"], ".php") !== false) {
    header("Location: /");
    exit;
}

class Data
{
    static function usuarios_online()
    {
        $qr = "SELECT * FROM users WHERE online='1'";
        if ($r = mysqli_query($conn, $qr)) {
            $online = mysqli_num_rows($r);
            echo $online;
            mysqli_free_result($r);
        }
    }
    static function usuarios_registrados()
    {
        $qr = "SELECT * FROM users";
        if ($r = mysqli_query($conn, $qr)) {
            $reg = mysqli_num_rows($r);
            echo $reg;
            mysqli_free_result($r);
        }
    }
    static function items()
    {
        $qr = "SELECT * FROM items";
        if ($r = mysqli_query($conn, $qr)) {
            $itens = mysqli_num_rows($r);
            echo $itens;
            mysqli_free_result($r);
        }
    }
    static function conversas()
    {
        $qr = "SELECT * FROM chatlogs_room";
        if ($r = mysqli_query($conn, $qr)) {
            $itens = mysqli_num_rows($r);
            echo $itens;
            mysqli_free_result($r);
        }
    }
    static function banimentos()
    {
        $qr = "SELECT * FROM bans";
        if ($r = mysqli_query($conn, $qr)) {
            $itens = mysqli_num_rows($r);
            echo $itens;
            mysqli_free_result($r);
        }
    }
    static function unicoid()
    {
        $id    = time();
        $unico = md5($id);
        echo $unico;
    }
}
