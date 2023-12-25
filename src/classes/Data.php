<?php

namespace Oblivion;

if (strrpos($_SERVER["REQUEST_URI"], ".php") || strrpos($_SERVER["REQUEST_URI"], ".php") !== false) {
    header("Location: /");
    exit;
}

class Data
{
    static function usuarios_online($db)
    {
        $qr = "SELECT * FROM users WHERE online='1'";
        if ($r = $db->query($qr)) {
            $online = mysqli_num_rows($r);
            echo $online;
            mysqli_free_result($r);
        }
    }
    static function usuarios_registrados($db)
    {
        $qr = "SELECT * FROM users";
        if ($r = $db->query($qr)) {
            $reg = mysqli_num_rows($r);
            echo $reg;
            mysqli_free_result($r);
        }
    }
    static function items($db)
    {
        $qr = "SELECT * FROM items";
        if ($r = $db->query($qr)) {
            $itens = mysqli_num_rows($r);
            echo $itens;
            mysqli_free_result($r);
        }
    }
    static function conversas($db)
    {
        $qr = "SELECT * FROM chatlogs_room";
        if ($r = $db->query($qr)) {
            $itens = mysqli_num_rows($r);
            echo $itens;
            mysqli_free_result($r);
        }
    }
    static function banimentos($db)
    {
        $qr = "SELECT * FROM bans";
        if ($r = $db->query($qr)) {
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
