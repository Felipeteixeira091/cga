<?php
include "l_sessao.php";
include 'json_encode.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());

$sma = $_POST['sma'];
$id = $_POST['id'];
$pa = $_POST['pa'];
$tipo = $_POST['tipo'];
$obs = $_POST['obs'];
$dh = date("Y-m-d H:i");

$erro = "1";

$dir = __DIR__ . DIRECTORY_SEPARATOR . '../sma_anexo';

$p = permissaoVerifica($mysqli, "82", $re);

//if ($p === 0) {
//    $msg = "Você não tem permissão para carregar arquivos.";
//} else {

if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}
if (isset($_FILES['file'])) {

    $arquivo = $_FILES['file']['name'];

    $ext = strtoupper(pathinfo($arquivo, PATHINFO_EXTENSION));

    if ($ext === "XLS" || $ext === "XLSM" || $ext === "XLSX" || $ext === "JPG" || $ext === "jpg" || $ext === "JPEG" || $ext === "jpeg" || $ext === "PNG" || $ext === "png") {

        $nomePA = $pa . "_" . $id . "." . $ext;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $dir . DIRECTORY_SEPARATOR . $nomePA)) {

            $verifica = $mysqli->query("select id from sma_anexo where item='{$id}'")->num_rows;
            if ($verifica === 0) {
                $sql_insert = "insert into sma_anexo (sma, item, anexo, dh, obs, data, hora) values ('{$sma}','{$id}', '{$nomePA}', '{$dh}', '{$obs}', '{$data}', '{$hora}')";
            } else {
                $sql_insert = "update sma_anexo set anexo='{$nomePA}', dh='{$dh}' where item='{$id}'";
            }
            $mysqli->query($sql_insert);
            $sql_update = "";
            if ($tipo === "2") {
                $sql_update = "update sma_solicitacao set anexo='{$nomePA}', status='12' where id='{$sma}'";
                $mysqli->query($sql_update);
            }

            $mysqli->close();

            $erro = "0";
            $msg = "Arquivo carregado com sucesso.";
        } else {
            $erro = "1";
            $msg = "Erro ao enviar arquivo..";
        }
    } else {
        $erro = "1";
        $msg = "Tipo de arquivo inválido.";
    }
}
//}


$arr = array("erro" => $erro, "msg" => $msg);
echo JsonEncodePAcentos::converter($arr);

function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
function anexoVerifica($mysqli, $item)
{
    $num = $mysqli->query("select id from sma_anexo where item='{$item}'")->num_rows;
    return $num;
}
