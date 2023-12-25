<?php

namespace Oblivion;

error_reporting(E_ALL);
ini_set('display_errors', 'On');

if (strrpos($_SERVER["REQUEST_URI"], ".php") || strrpos($_SERVER["REQUEST_URI"], ".php") !== false) {
        header("Location: /");
        exit;
}

class Game {
    static function sso($db) {
        $sso = strtotime("Now");
        $ssonome = "Salsa-";
        $ssofix = rand(1, 999);
        $ssoss = $sso - $ssofix;
        $ssofinal = "" . $ssonome . "" . $ssoss . "-" . USUARIO . "-" . md5($sso) . "";
        $m = "UPDATE users SET auth_ticket='" . $ssofinal . "' WHERE username='" . USUARIO . "'";
        $db->query($m);
        echo $ssofinal;
    }
}
