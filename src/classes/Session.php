<?php

namespace Oblivion;

include_once 'Filter.php';

class Session
{
    static function init($db)
    {
        define('U', $_SERVER["REQUEST_URI"]);
        if (isset($_SESSION['usuario']) && isset($_SESSION['senha'])) {
            $senhageradasesaao = password_hash($_SESSION['senha'], PASSWORD_BCRYPT);
            if (password_verify($_SESSION['senha'], $senhageradasesaao)) {
                $sql = "SELECT * FROM users WHERE username='" . $_SESSION['usuario'] . "'";
                $query = $db->query($sql) or die($db->error());
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
                    define('USUARIO', $cur);
                }

                if (U == "/index") {
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
                switch (U) {
                    case '/me':
                        header("Location: /");
                    break;
                    case '/config':
                        header("Location: /");
                    break;
                    case '/'.$_ENV['CLIENT_PATH']:
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
            $qs = $db->query($zaq) or die($db->error());
            $dados = $qs->fetch_assoc();
            {
                $status = $dados['motto'];
                $user_creditos = $dados['credits'];
                $roupanova = $dados['look'];
                $online = $dados['online'];
                $rank = $dados['rank'];
                $id = $dados['id'];
                $referidos = $dados['referidos'];
                $ip = $dados['ip_current'];
                $roupa = $dados['look'];
                if (U == "/registro") {
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
            define('USUARIO', $cur);
            switch (U) {
                case '/me':
                    header("Location: /");
                break;
                case '/config':
                    header("Location: /");
                break;
                case '/'.$_ENV['CLIENT_PATH']:
                    header("Location: /");
                break;
                case '/produtos':
                    header("Location: /");
                break;
                default:
                break;
            }
        }
        if ($cur != USUARIO || $cur != $sessao || $sessao != USUARIO) {
            session_destroy();
            header("Location: /");
        }

        $noticiaid    = $_SERVER["REQUEST_URI"];
        $noticia      = Filter::noticia($noticiaid);
        $noticiafinal = Filter::fs($noticia);
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $noticiafinal))
            $noticiafinal = 0;
        else
            $noticiafinal = $noticiafinal;

        // Site
        define('NOME', $_ENV['SITE_NOME']);
        define('URL', $_ENV['SITE_URL']);
        define('MANUTENCAO', $_ENV['SITE_MANUTENCAO']);
        define('LOG', $_ENV['SITE_LOGIN']);
        define('AVATARIMAGE', $_ENV['SITE_AVATAR']);
        define('REDIRECIONAMENTOS', $_ENV['SITE_RED']);
        define('RANK_MINIMO_MANUTENCAO', $_ENV['SITE_RANK_MINIMO']);
        define('CLIENT', $_ENV['CLIENT_PATH']);
        define('CAMUFLARCLIENT', $_ENV['SITE_CMFCLIENT']);
        // Util
        define('FACEBOOK', $_ENV['TEMA_FACEBOOK']);
        define('TWITTER', $_ENV['TEMA_TWITTER']);
        define('DISCORD', $_ENV['TEMA_DISCORD']);
        define('TEMA', $_ENV['TEMA_TEMA']);
        define('LOGO', $_ENV['TEMA_LOGO']);
        define('TOPO', $_ENV['TEMA_TOPHEADER']);
        define('URLEMBLEMAS', $_ENV['TEMA_URLEMBLEMAS']);
        define('POSTAGENS', $_ENV['TEMA_POSTAGENS']);
        // Registro
        define('MISSAO', $_ENV['REGISTRO_MISSAO']);
        define('VISUAL', $_ENV['REGISTRO_VISUAL']);
        define('CREDITOS', $_ENV['REGISTRO_CREDITOS']);
        define('QUARTOINICIAL', $_ENV['REGISTRO_QUARTOINICIAL']);
        define('REG', $_ENV['REGISTRO_ATIVADO']);
        define('CONTASPORIP', $_ENV['REGISTRO_CONTASPORIP']);
        define('CAPTCHA', $_ENV['REGISTRO_CAPTCHAREGISTRO']);
        // Painel de controle
        define('RMIN', $_ENV['PAINEL_RANKMINIMO']);
        define('PREMIAR_CREDITOS', $_ENV['PAINEL_PREMIAR_CREDITOS']);
        define('PREMIAR_DIAMANTES', $_ENV['PAINEL_PREMIAR_DIAMANTES']);
        define('PREMIAR_CODIGO_EMBLEMA', $_ENV['PAINEL_PREMIAR_CODIGO_EMBLEMA']);
        // Data
        define('ANO', date("Y"));
        define('DIA', date("d"));
        // Notícias
        define('NOTICIAFINAL', $noticiafinal);
        // Usuário
        define('CUR', $cur ?? NULL);
        define('ID', $id ?? NULL);
        define('IP', $ip ?? NULL);
        define('MNS', $mns ?? NULL);
        define('ONLINE', $online ?? NULL);
        define('RANK', $rank ?? NULL);
        define('REFERIDOS', $referidos ?? NULL);
        define('ROUPA', $roupa ?? NULL);
        define('ROUPANOVA', $roupanova ?? NULL);
        define('RPNS', $rpns ?? NULL);
        define('SESSAO', $sessao ?? NULL);
        define('STATUS', $status ?? NULL);
        define('USER_CREDITOS', $user_creditos ?? NULL);
        define('USER_EVENTOS', $user_eventos ?? NULL);
        define('USER_LOGIN', $user_login ?? NULL);
        define('USER_MISSAO', $user_missao ?? NULL);
        define('USER_PROMOCAO', $user_promocao ?? NULL);

        if (CAMUFLARCLIENT == 1) {
            $_ENV['CONNECTION_INFO_HOST'] = Filter::camuflar($_ENV['CONNECTION_INFO_HOST']);
            $_ENV['CONNECTION_INFO_PORT'] = Filter::camuflar($_ENV['CONNECTION_INFO_PORT']);
        }
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
    }
}