<?php
/***
 *                _
 *               | |
 *      ___  __ _| |___  __ _
 *     / __|/ _` | / __|/ _` |
 *     \__ \ (_| | \__ \ (_| |
 *     |___/\__,_|_|___/\__,_|
 *     GitHub: https://github.com/victorlbs/Salsa-CMS-for-Arcturus-Emulator/
 *
 *
*/
include 'database.php';
define('u', $_SERVER["REQUEST_URI"]);
if (isset($_SESSION['usuario']) && isset($_SESSION['senha'])) {
    $senhageradasesaao = password_hash($_SESSION['senha'], PASSWORD_BCRYPT);
    if (password_verify($_SESSION['senha'], $senhageradasesaao)) {
        $sql = "SELECT * FROM users WHERE username='" . $_SESSION['usuario'] . "'";
        $query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
        $row = $query->fetch_assoc();
        {
            $sessao = $row['username'];
            $mns = $row['rank'];
            $rpns = $row['look'];
            $user_missao = $row['motto'];
            $user_creditos = $row['credits'];
            $user_login = $row['last_login'];
            $user_eventos = $row['pontos_evento'];
            $user_promocao = $row['pontos_promocao'];
            $cur = $sessao;
            define("usuario", $cur);
        }

        if (u == "/index") {
            header("Location: /me");
        }
    } else {
        $sessao = 0;
        $cur = 0;
        $user_missao =0;
        $mns = 0;
        $user_promocao = 0;
        $user_eventos = 0;
        $user_creditos = 0;
        switch (u) {
            case '/me':
                header("Location: /");
            break;
            case '/config':
                header("Location: /");
            break;
            case '/client':
                header("Location: /");
            break;
            case '/produtos':
                header("Location: /");
            break;
            default:
            break;
        }
    }
}
if (isset($_SESSION['usuario']) && isset($sessao)) {
    $zaq = "SELECT * FROM users WHERE username='" . $sessao . "'";
    $qs = mysqli_query($conn, $zaq) or die(mysqli_error($conn));
    $dados = $qs->fetch_assoc();
    {
        define("status", $dados['motto']);
        define("credits", $dados['credits']);
        define("roupanova", $dados['look']);
        define("online", $dados['online']);
        define("rank", $dados['rank']);
        define("id", $dados['id']);
        $referidos = $dados['referidos'];
        $rank = $dados['rank'];
        $ip = $dados['ip_current'];
        $roupa = $dados['look'];
        if (u == "/registro") {
            header("Location: /me");
        }
    }
} else {
    $_SESSION['usuario'] = null;
    $sessao = null;
    $cur = $sessao;
    $mns = null;
    $rank = null;
    $user_missao = null;
    $user_promocao = null;
    $user_eventos = null;
    $user_creditos = null;
    define("usuario", $cur);
    switch (u) {
        case '/me':
            header("Location: /");
        break;
        case '/config':
            header("Location: /");
        break;
        case '/client':
            header("Location: /");
        break;
        case '/produtos':
            header("Location: /");
        break;
        default:
        break;
    }
}
if ($cur != usuario || $cur != $sessao || $sessao != usuario) {
    session_destroy();
    header("Location: /");
}
// Site
define('nome', $_ENV['SITE_NOME']);
define('url', $_ENV['SITE_URL']);
define('manutencao', $_ENV['SITE_MANUTENCAO']);
define('log', $_ENV['SITE_LOGIN']);
define('avatarimage', $_ENV['SITE_AVATAR']);
define('redirecionamentos', $_ENV['SITE_RED']);
define('rank_minimo_manutencao', $_ENV['SITE_RANK_MINIMO']);
define('camuflarclient', $_ENV['SITE_CMFCLIENT']);
// Util
define('facebook', $_ENV['TEMA_FACEBOOK']);
define('twitter', $_ENV['TEMA_TWITTER']);
define('discord', $_ENV['TEMA_DISCORD']);
define('tema', $_ENV['TEMA_TEMA']);
define('logo', $_ENV['TEMA_LOGO']);
define('topo', $_ENV['TEMA_TOPHEADER']);
define('urlemblemas', $_ENV['TEMA_URLEMBLEMAS']);
define('postagens', $_ENV['TEMA_POSTAGENS']);
// Registro
define('missao', $_ENV['REGISTRO_MISSAO']);
define('visual', $_ENV['REGISTRO_VISUAL']);
define('creditos', $_ENV['REGISTRO_CREDITOS']);
define('quartoinicial', $_ENV['REGISTRO_QUARTOINICIAL']);
define('reg', $_ENV['REGISTRO_ATIVADO']);
define('contasporip', $_ENV['REGISTRO_CONTASPORIP']);
define('captcha', $_ENV['REGISTRO_CAPTCHAREGISTRO']);
// Painel de controle
define('rmin', $_ENV['PAINEL_RANKMINIMO']);
define('premiar_creditos', $_ENV['PAINEL_PREMIAR_CREDITOS']);
define('premiar_diamantes', $_ENV['PAINEL_PREMIAR_DIAMANTES']);
define('premiar_codigo_emblema', $_ENV['PAINEL_PREMIAR_CODIGO_EMBLEMA']);
// Data
define('ano', date("Y"));
define('dia', date("d"));
// Banco de dados
define('conn', $conn);
// Notícias
define('noticiafinal', $noticiafinal);
// Usuário
define('cur', $cur);

if (camuflarclient == 1) {
    $_ENV['CONNECTION_INFO_HOST'] = camuflar($_ENV['CONNECTION_INFO_HOST']);
    $_ENV['CONNECTION_INFO_PORT'] = camuflar($_ENV['CONNECTION_INFO_PORT']);
}
if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}
