<?php

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";
date_default_timezone_set("America/Fortaleza");

$p_re = $txtTitulo['re'];
$p_senha = $txtTitulo['senha'];
$erro = "1";

$senha = isset($p_senha) ? md5(trim($p_senha)) : FALSE;

if (!$p_re || !$p_senha) {
    $msg = "Você deve digitar seu RE e senha.";
} else {

    $sql = "select count(u.id) as login, u.re as re, u.email as email, u.nome, s.re as re_sup, s.nome as nome_sup, u.estado as uf, u.cn as cn from usuario u inner join usuario s on s.re=u.supervisor where u.re='{$p_re}' and u.senha='{$senha}' and u.sistema=2";
    $result = $mysqli->query($sql);

    $login = $result->fetch_assoc();

    if ($login['login'] == 1) {
        $session_name = 'sec_session_id';
        $httponly = true;
        // Assim você força a sessão a usar apenas cookies. 
        if (ini_set('session.use_only_cookies', 1) === FALSE) {
            header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
            exit();
        }
        session_start();            // Inicia a sessão PHP 
        $_SESSION["re"] = $login["re"];
        $_SESSION["nome"] = $login["nome"];
        $_SESSION["email"] = $login["email"];
        $_SESSION["uf"] = $login["uf"];
        $_SESSION["cn"] = $login["cn"];

        $erro = "0";
        $msg = "Você está conectado.";

        $data = date('Y-m-d');
        $hora = date('H:i', time());
        $chave = md5($_SESSION['re'] . $data);

        $mysqli->query($sql);

        //limpa_chave($mysqli, $re);
        abre_chave($mysqli, $login['re'], $chave, $data, $hora);
    } else {
        $msg = "RE ou senha incorreto.";
    }
}
$arr = array("erro" => $erro, "msg" => $msg);
echo JsonEncodePAcentos::converter($arr);

function abre_chave($mysqli, $re, $chave, $data, $hora)
{
    $sql = "insert into chave (re, chave, data, hora, situacao) values ('{$re}', '{$chave}', '{$data}', '{$hora}', '1')";
    $mysqli->query($sql);
}
function limpa_chave($mysqli, $re)
{
    $sql = "DELETE FROM chave WHERE re='{$re}'";
    $mysqli->query($sql);
}
