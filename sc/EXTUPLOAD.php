<?php
include "l_sessao.php";
include 'json_encode.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());

$id = $_POST['nota_id'];

$p = permissaoVerifica($mysqli, "60", $re);

if ($p === 0) {
    $erro = "1";
    $msg = "Você não tem permissão para enviar notas.";
} else {

    $dir = __DIR__ . DIRECTORY_SEPARATOR . '../ext_nota';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    if (isset($_FILES['file'])) {

        $arquivo = $_FILES['file']['name'];

        $ext = strtoupper(pathinfo($arquivo, PATHINFO_EXTENSION));

        if ($ext === "PDF" || $ext === "pdf" || $ext === "msg" || $ext === "MSG") {

            $nome_sql = md5(rand(0, 2000) . $id . $data . $hora);
            $nome = $nome_sql . "." . $ext;


            if (move_uploaded_file($_FILES['file']['tmp_name'], $dir . DIRECTORY_SEPARATOR . $nome)) {

                $sql_insert = "update ext_nota set anexo='{$nome}', status='2' where id='{$id}'";
                $mysqli->query($sql_insert);

                $mysqli->close();

                $erro = "0";
                $msg = "Arquivo carregado com sucesso.";
            } else {
                $erro = "1";
                $msg = "Erro ao enviar arquivo.";
            }
        } else {
            $erro = "1";
            $msg = "Tipo de arquivo inválido.";
        }
    }
}

$arr = array("erro" => $erro, "msg" => $msg);
echo JsonEncodePAcentos::converter($arr);

function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
