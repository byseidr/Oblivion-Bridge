<?php

namespace Oblivion;

class Account {
    static function conectar($db) {
        if (isset($_POST['conectar'])) {
            $usuario = Filter::fs($_POST['usuario_salsa']);
            $vlsalsa = 1;
            $senha = Filter::fs($_POST['senha_salsa']);
            $senhagerada = password_hash($senha, PASSWORD_BCRYPT);
            if (LOG == 0) {
                $_SESSION['erro'] = 'O login está desativado';
                $erro = 1;
            } else {
                $logar = "SELECT * FROM users WHERE username='" . $usuario . "'";
                $lg = $db->query($logar) or die($db->error());
                if ($ra = $db->query($logar)) {
                    $existe = mysqli_num_rows($ra);
                    if ($existe == $vlsalsa) {
                        $busca = $lg->fetch_assoc(); {
                            $existesenha = $busca['password'];
                            if (password_verify($senha, $existesenha)) {
                                $ban = "SELECT * FROM bans WHERE user_id ='" . $busca['id'] . "' AND type='account'";
                                if ($ra2 = $db->query($ban)) {
                                    $existe2 = mysqli_num_rows($ra2);
                                    if ($existe2 == $vlsalsa) {
                                        $a = $ban;
                                        $c = $db->query($a) or die($db->error());
                                        while ($b = $c->fetch_assoc()) {
                                            $_SESSION['erro'] = '
                                                Você está banido pelo seguinte motivo: ' . $b['ban_reason'] . ' e ficará banido até ' . date('d/m/Y', $b['ban_expire']) . '. Você pode contestar seu banimento em nossa página do Facebook.';
                                            $erro = 1;
                                        }
                                    }
                                }
                                $ban3 = "SELECT * FROM bans WHERE ip ='" . $_SERVER['REMOTE_ADDR'] . "' AND type='ip'";
                                if ($ra4 = $db->query($ban3)) {
                                    $existe3 = mysqli_num_rows($ra4);
                                    if ($existe3 == $vlsalsa) {
                                        $a = $ban3;
                                        $c2 = $db->query($a) or die($db->error());
                                        while ($b2 = $c2->fetch_assoc()) {
                                            $_SESSION['erro'] = 'Você está banido por IP e não pode criar novas contas, você está banido pelo seguinte motivo: ' . $b2['ban_reason'] . ' e ficará banido até ' . date('d/m/Y', $b2['ban_expire']) . '. Você pode contestar seu banimento em nossa página do Facebook.';
                                            $erro = 1;
                                        }
                                    }
                                }
                                if ($busca['rank'] >= RANK_MINIMO_MANUTENCAO && MANUTENCAO == 1) {
                                    $mns = 1;
                                } elseif ($busca['rank'] < RANK_MINIMO_MANUTENCAO && MANUTENCAO == 1) {
                                    $mns = 0;
                                    $_SESSION['erro'] = 'Apenas membros da equipe podem fazer login no momento.';
                                    $erro = 1;
                                }
                                if (isset($erro)) {
                                    if ($erro == 1) echo $_SESSION['erro'];
                                } else {
                                    $_SESSION['usuario'] = $usuario;
                                    $_SESSION['senha'] = $senhagerada;
                                    $ip = "UPDATE users SET ip_current='" . $_SERVER['REMOTE_ADDR'] . "' WHERE username='" . $usuario . "'";
                                    $db->query($ip);
                                    exit(header("Location: /me"));
                                }
                            } else {
                                $_SESSION['erro'] = 'Usuário e/ou senha incorretos.';
                                $erro = 1;
                            }
                        }
                    } else {
                        $_SESSION['erro'] = 'Usuário e/ou senha incorretos.';
                        $erro = 1;
                    }
                }
            }
        }
    }
    static function registrar($db) {
        if (isset($_POST['registrar'])) {
            $usuario = Filter::fs($_POST['usuario_salsa']);
            $email = Filter::fs($_POST['email']);
            $vlsalsa = 1;
            $senha = Filter::fs($_POST['senha_salsa']);
            $senharapetida = Filter::fs($_POST['senha_repetir']);
            $senhagerada = password_hash($senha, PASSWORD_BCRYPT);
            $usuariovic = strlen($usuario);
            if (CAPTCHA == 1 || CAPTCHA == true) {
                $scaptcha = Filter::fs($_POST['captcha']);
                $scaptchar = Filter::fs($_POST['captcha_repetir']);
                if ($scaptcha != $scaptchar) {
                    $_SESSION['erro'] = 'O captcha inserido está incorreto.';
                    $erro = 1;
                }
            }
            if (REG == 0) {
                $_SESSION['erro'] = 'O registro está desativado.';
                $erro = 1;
            } else {
                $ban3 = "SELECT * FROM bans WHERE ip ='" . $_SERVER['REMOTE_ADDR'] . "' AND type='ip'";
                if ($ra4 = $db->query($ban3)) {
                    $existe3 = mysqli_num_rows($ra4);
                    $vlsalsa = 1;
                    if ($existe3 == $vlsalsa) {
                        $a = $ban3;
                        $c2 = $db->query($a) or die($db->error());
                        while ($b2 = $c2->fetch_assoc()) {
                            $_SESSION['erro'] = 'Você está banido por IP e não pode criar novas contas, você está banido pelo seguinte motivo: <b>' . $b['ban_reason'] . '</b> e ficará banido até ' . date('d/m/Y', $b['ban_expire']) . '. Você pode contestar seu banimento em nossa página do Facebook.';
                            $erro = 1;
                        }
                    }
                }
                $qra = "SELECT * FROM users WHERE username='" . $usuario . "'";
                if ($ra = $db->query($qra)) {
                    $existe = mysqli_num_rows($ra);
                    if ($existe == $vlsalsa) {
                        $_SESSION['erro'] = 'O nome de usuário já existe, por gentileza escolha outro.';
                        $erro = 1;
                    }
                    $qra2 = "SELECT * FROM users WHERE ip_current='" . $_SERVER['REMOTE_ADDR'] . "'";
                    if ($ra2 = $db->query($qra2)) {
                        $existe2 = mysqli_num_rows($ra2);
                        if ($existe2 > CONTASPORIP) {
                            $_SESSION['erro'] = 'Você só pode criar ' . CONTASPORIP . ' contas por IP.';
                            $erro = 1;
                        } else {
                            if ($usuariovic > 14 || $usuariovic < 3) {
                                $_SESSION['erro'] = 'Seu nome de usuário deve conter mais de 3 caracteres e no máximo 14 caracteres.';
                                $erro = 1;
                            } else {
                                if ($senha != $senharapetida) {
                                    $_SESSION['erro'] = 'As senhas digitadas nao sao iguais.';
                                    $erro = 1;
                                } else {
                                    if (preg_match('/[\'^êãõñêâôîôû£$%&*()}{@#~?><>,|=_+¬-]/', $usuario)) {
                                        $_SESSION['erro'] = 'Não é permitido caracteres especiais no nome de usuário.';
                                        $erro = 1;
                                    } else {
                                        if (strrpos($usuario, " ") || strrpos($usuario, " ") !== false) {
                                            $_SESSION['erro'] = 'Não é permitido espaços  no nome de usuário.';
                                            $erro = 1;
                                        } else {
                                            if (isset($erro)) {
                                                if ($erro == 1)
                                                $_SESSION['erro'] = $_SESSION['erro'];
                                            } else {
                                                $xy = "INSERT INTO `users` (`username`, `password`, `mail`, `rank`, `motto`, `account_created`, `last_login`, `look`, `home_room`, `ip_current`, `credits`, `ip_register`) VALUES ('$usuario', '$senhagerada', '$email', '1', '" . MISSAO . "', '" . time() . "', '" . time() . "', '" . VISUAL . "', '" . QUARTOINICIAL . "', '" . $_SERVER['REMOTE_ADDR'] . "', '" . CREDITOS . "', '" . $_SERVER['REMOTE_ADDR'] . "');";
                                                $db->query($xy);
                                                $_SESSION['usuario'] = $usuario;
                                                $_SESSION['senha'] = $senhagerada;
                                                exit(header("Location: /me"));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    static function configuracoes($db) {
        if (isset($_POST['enviar'])) {
            $missao = Filter::fs(Filter::rmdominio($_POST['missao']));
            $email = Filter::fs($_POST['email']);
            $confirmacao = Filter::fs($_POST['confirmacao']);
            $dc = Filter::fs(Filter::rmdominio($_POST['discord']));
            $capa = Filter::fs($_POST['capa']);
            $missaog = strlen($missao);
            $vlsalsa = 1;
            if ($missaog > 13 || $missaog < 1) {
                $_SESSION['erro'] = 'Sua missão deve conter mais de 2 caracteres e no máximo 13 caracteres.';
                $erro = 1;
            }
            else
            {
                $erro = 0;
            }
            $m = "UPDATE users SET motto='" . $missao . "' WHERE username='" . USUARIO . "'";
            $db->query($m);
            if ($confirmacao != $email) {
                $qra = "SELECT * FROM users WHERE mail='" . $email . "'";
                if ($ra = $db->query($qra)) {
                    $existe = mysqli_num_rows($ra);
                    if ($existe == $vlsalsa) {
                        $_SESSION['erro'] = 'O e-mail  informado já está em uso, por gentileza escolha outro.';
                        $erro = 1;
                    }
                    mysqli_free_result($ra);
                }
                $m = "UPDATE users SET mail='" . $email . "' WHERE username='" . USUARIO . "'";
                $db->query($m);
                $erro = 0;
            }
            if (!empty($dc)) {
                $qra = "SELECT * FROM users WHERE discord='" . $dc . "'";
                $vlsalsa = 1;
                if ($ra = $db->query($qra)) {
                    $existe = mysqli_num_rows($ra);
                    if ($existe == $vlsalsa) {
                        $_SESSION['erro'] = 'O discord informado já está em uso, por gentileza escolha outro.';
                        $erro = 1;
                    }
                    mysqli_free_result($ra);
                }
                $m = "UPDATE users SET discord='" . $dc . "' WHERE username='" . USUARIO . "'";
                $db->query($m);
            }

            if (!empty($capa)) {
                $m = "UPDATE users SET capa='" . $capa . "' WHERE username='" . USUARIO . "'";
                $db->query($m);
            }
            if (isset($erro)) {
                if ($erro == 1)
                {

                 $_SESSION['erro'] = $_SESSION['erro'];
             }
             else
             {
                  $_SESSION['erro'] = 'Configurações salvas com sucesso.';
             }
            } 
        }
    }
    static function publicar($db) {
        if (isset($_POST['postar'])) {
            if (POSTAGENS == 0) {
                $_SESSION['erro'] = 'As publicações estão desativadas no momento.';
            } else {
                $sql31 = "SELECT * FROM users WHERE username='" . USUARIO . "'";
                $query10 = $db->query($sql31) or die($db->error());
                while ($rows = $query10->fetch_assoc()) {
                    if ($rows['post_hoje'] == DIA && $rows['rank'] == 1) {
                        $_SESSION['erro'] = 'Você só pode fazer uma publicação por dia.';
                        $erro = 1;
                    } else {
                        $postagem = Filter::fs(Filter::rmdominio($_POST['postagem']));
                        if (RANK > 4) $staff = 1;
                        else $staff = 0;
                        $nome = USUARIO;
                        $data = strtotime("Now");
                        $roupa = ROUPANOVA;
                        if (empty($postagem)) {
                            $_SESSION['erro'] = 'Sua mensagem é inválida.';
                            $erro = 1;
                        } else {
                            if (isset($erro)) {
                                if ($erro == 1) echo $_SESSION['erro'];
                            } else {
                                $m = "INSERT INTO `salsa_posts` (`postagem`, `usuario`, `data`, `staff`, `look`) VALUES ('$postagem', '$nome', '$data', '$staff', '$roupa');";
                                $db->query($m);
                                $_SESSION['erro'] = 'Você publicou <b>"' . $postagem . '"</b> com sucesso!';
                                $m2 = "UPDATE users SET post_hoje='" . DIA . "' WHERE username='" . USUARIO . "'";
                                $db->query($m2);
                            }
                        }
                    }
                }
            }
        }
    }
    static function comprarpontos($db) {
        if (isset($_POST['comprar'])) {
            $sql31 = "SELECT * FROM users WHERE username='" . USUARIO . "'";
            $query10 = $db->query($sql31) or die($db->error());
            while ($rows = $query10->fetch_assoc()) {
                $creditos = $rows['credits'];
                if ($creditos > 500000) {
                    $_SESSION['erro'] = 'Você comprou com sucesso!</div>';
                    $erro = 0;
                    $m = "UPDATE users SET credits = credits-500000 WHERE username = '" . USUARIO . "'";
                    $db->query($m);
                    $m2 = "UPDATE `users_settings` SET `achievement_score` = '1000' WHERE `users_settings`.`user_id` = " . ID . ";";
                    $db->query($m2);
                    exit;
                } else {
                    $_SESSION['erro'] = 'Você não possui o valor necessário para adquirir o produto.';
                    $erro = 1;
                    if ($erro == 1) {
                        echo $_SESSION['erro'];
                    } elseif ($erro == 0) {
                        echo $_SESSION['erro'];
                    }
                }
            }
        }
    }
    static function buscar_usuario() {
        if (isset($_POST['pesq'])) {
            $buscar = Filter::fs($_POST['usuariobus']);
            header("Location: /perfil?=$buscar");
            exit;
        }
    }
    static function recado($db) {
        if (isset($_POST['enviar'])) {
            $recado = Filter::fs(Filter::rmdominio($_POST['recado']));
            $token1 = Filter::fs($_POST['token']);
            $token2 = Filter::fs($_POST['token_salsa']);
            if (empty($recado)) {
                $_SESSION['erro'] = 'Sua mensagem é inválida.';
                $erro = 1;
            } else {
                if ($token1 == $token2) {
                    if (isset($erro)) {
                        if ($erro == 1) echo $_SESSION['erro'];
                    } else {
                        $yeah = $token1;
                        $sql31 = "SELECT * FROM users WHERE username='" . USUARIO . "'";
                        $query10 = $db->query($sql31) or die($db->error());
                        while ($rows = $query10->fetch_assoc()) {
                            $visual = $rows['look'];
                            $nome = $rows['username'];
                            $m2 = "INSERT INTO `salsa_postagens` (`usuario`, `mensagem`, `look`, `data`, `donoperfil`) VALUES ('$nome', '$recado', '$visual', '" . strtotime("Now") . "', '$yeah');";
                            $db->query($m2);
                            $_SESSION['erro'] = 'Você deixou um recado: "' . $recado . '" com sucesso!';
                        }
                    }
                }
            }
        }
    }
    static function curtir($db) {
        if (isset($_POST['curtidas'])) {
            $vlr1 = Filter::fs($_POST['id']);
            $vlr2 = Filter::fs($_POST['usuario']);
            $m2 = "UPDATE salsa_posts SET curtidas = curtidas+1 WHERE id = '" . $vlr1 . "'";
            $db->query($m2);
            $_SESSION['erro'] = 'Você curtiu a publicação com sucesso.';
        }
    }
    static function comentar_noticia($db) {
        if (isset($_POST['comentar'])) {
            $vlr1 = Filter::fs($_POST['id']);
            $vlr2 = Filter::fs(Filter::rmdominio($_POST['mensagem']));
            if (empty($vlr2)) {
                $_SESSION['erro'] = 'Sua mensagem é inválida.';
            } else {
                $m2 = "INSERT INTO `salsa_comentarios_noticia` (`usuario`, `data`, `look`, `mensagem`, `noticia`) VALUES ('" . USUARIO . "', '" . time() . "', '" . ROUPANOVA . "', '$vlr2', '$vlr1');";
                $db->query($m2);
                $_SESSION['erro'] = 'Você comentou ' . $vlr2 . '';
            }
        }
    }
    static function adicionar_amigo($db) {
        if (isset($_POST['enviaramizade'])) {
            $vlr1 = Filter::fs($_POST['id']);
            $vlr2 = Filter::fs($_POST['id_dois']);
            $m2 = "INSERT INTO `messenger_friendrequests` (`user_to_id`, `user_from_id`) VALUES ('" . $vlr1 . "', '" . $vlr2 . "');";
            $db->query($m2);
            $_SESSION['erro'] = 'Você enviou o convite com sucesso. Aguarde a outra parte aceitar.';
        }
    }
}
