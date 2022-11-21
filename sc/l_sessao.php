<?php

include_once "conf/conexao2.php";
session_start();



if (!isset($_SESSION["nome"]) || !isset($_SESSION["email"]) || !isset($_SESSION["re"])) {
    header("Location: telaLogin");
    exit;
}

$data = date('Y-m-d');
$chave = md5($_SESSION['re'] . $data);

if (verificaChave($mysqli, $chave) < 1) {

    header("Location: logout");
}


function verificaChave($mysqli, $chave)
{
    $sql = "select re, chave from chave where chave='{$chave}'";
    $num = $mysqli->query($sql)->num_rows;
    return $num;
}
function regiao($mysqli, $re)
{
    $sql = "select cn.regiao as regiao, u.gestao as gestao from usuario u inner join cn on cn.id=u.cn where u.re='{$re}'";
    $result = $mysqli->query($sql)->fetch_array();
    $regiao = $result;

    return $regiao;
}