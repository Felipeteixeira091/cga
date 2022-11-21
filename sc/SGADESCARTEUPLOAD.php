<?php
session_start();

include_once "./conf/conexao2.php";
include_once "./json_encode.php";

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());

$id = $_POST['id'];
$obs = $_POST['observacao'];

$p = permissaoVerifica($mysqli, '89', $re);

if ($p === 0) {
    $erro = "1";
    $msg = "Você não tem permissão necessária.";
} else {
    $dir = __DIR__ . DIRECTORY_SEPARATOR . '../sga_anexo';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    if (isset($_FILES['file'])) {

        $arquivo = $_FILES['file']['name'];

        $ext = strtoupper(pathinfo($arquivo, PATHINFO_EXTENSION));

        if ($ext != "PDF") {

            $erro = "1";
            $msg = 'Tipo de arquivo inválido.';
        } else {

            $nome_sql = md5(rand(0, 2000) . $id . $data . $hora);
            $nome = $nome_sql . "." . $ext;


            if (move_uploaded_file($_FILES['file']['tmp_name'], $dir . DIRECTORY_SEPARATOR . $nome)) {

                $sql_insert = "update sga_descarte set anexo='{$nome}', obs='{$obs}' where id='{$id}'";
                $mysqli->query($sql_insert);

                $mysqli->close();

                $erro = "0";
                $msg = 'Arquivo caregado com sucesso.';
                $nota = $nome;
            } else {
                $erro = "1";
                $msg = 'Erro ao enviar dados.';
                $nota = "nd";
            }
        }
    }
}

$arr = array("erro" => $erro, "msg" => $msg, "nota" => $nota);
echo JsonEncodePAcentos::converter($arr);

function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
