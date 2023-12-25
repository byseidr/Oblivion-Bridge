<?php

namespace Oblivion;

include 'Db.php';

error_reporting(E_ALL);
ini_set('display_errors', 'On');

if (strrpos($_SERVER["REQUEST_URI"], ".php") || strrpos($_SERVER["REQUEST_URI"], ".php") !== false) {
        header("Location: /");
        exit;
}

class Game extends Db {
    static function sso() {
        $sso = strtotime("Now");
        $ssonome = "Salsa-";
        $ssofix = rand(1, 999);
        $ssoss = $sso - $ssofix;
        $ssofinal = "" . $ssonome . "" . $ssoss . "-" . usuario . "-" . md5($sso) . "";
        $m = "UPDATE users SET auth_ticket='" . $ssofinal . "' WHERE username='" . usuario . "'";
        parent::query($m);
        echo $ssofinal;
    }
}
