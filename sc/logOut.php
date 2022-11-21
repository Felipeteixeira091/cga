<?php

include "l_sessao.php";
include 'json_encode.php';


$re = $_SESSION["re"];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());
$chave = md5($_SESSION['re'] . $data);

$erro = "0";
$msg = "Você foi desconectado com sucesso.";

fecha_chave($mysqli, $re, $chave, $data, $hora);
session_destroy();

$arr = array("erro" => $erro, "msg" => $msg);
echo JsonEncodePAcentos::converter($arr);

function fecha_chave($mysqli, $re, $chave, $data, $hora)
{
    $sql = "insert into chave (re, chave, data, hora, situacao) values ('{$re}', '{$chave}', '{$data}', '{$hora}', '0')";
    $mysqli->query($sql);
}
