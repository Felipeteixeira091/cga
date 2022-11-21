<?php
//$servidor = "108.179.253.25";
$servidor = "108.179.253.25";

$usuario = "solici51";
$banco = "solici51_oem";
$tipo_conexao = substr($_SERVER['HTTP_HOST'],4);

if (($tipo_conexao == 'localhost.com') || ($tipo_conexao == '127.0.0.1')) {

    $usuario = "solici51_ico_web";
    $senha = "-^b-Y5#0I.uh";
} else {

    $usuario = "solici51_ico_web";
    $senha = "-^b-Y5#0I.uh";
}


// Conecta-se ao banco de dados MySQL

$mysqli = new mysqli($servidor, $usuario, $senha, $banco);

$mysqli->set_charset("utf8");

date_default_timezone_set("America/Fortaleza");
