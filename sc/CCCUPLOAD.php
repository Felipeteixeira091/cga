<?php
include "l_sessao.php";
include 'json_encode.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());

$erro = "1";
$arq = "";

$dir = __DIR__ . DIRECTORY_SEPARATOR . '../ccc_anexo';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}
if (isset($_FILES['file'])) {

    $arquivo = $_FILES['file']['name'];

    $ext = strtoupper(pathinfo($arquivo, PATHINFO_EXTENSION));

    if ($ext === "PDF" || $ext === "pdf" || $ext === "JPG" || $ext === "jpg" || $ext === "JPEG" || $ext === "jpeg" || $ext === "PNG" || $ext === "png" || $ext === "xls" || $ext === "XLS" || $ext === "xlsx" || $ext === "XLSX" || $ext === "xlsm" || $ext === "XLSM") {

        $nome_sql = md5(rand(0, 2000) . $arquivo . $data . $hora);
        $nome = $nome_sql . "." . $ext;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $dir . DIRECTORY_SEPARATOR . $nome)) {

            $erro = "0";
            $msg = "Arquivo carregado com sucesso.";
            $arq = $nome;
        } else {
            $erro = "1";
            $msg = "Erro ao enviar arquivo..";
        }
    } else {
        $erro = "1";
        $msg = "Tipo de arquivo inválido.";
    }
}


$arr = array("erro" => $erro, "msg" => $msg, "arq" => $arq);
echo JsonEncodePAcentos::converter($arr);
