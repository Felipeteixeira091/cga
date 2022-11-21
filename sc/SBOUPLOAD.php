<?php

session_start();

define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);
define('TB', 1099511627776);

include_once "./conf/conexao2.php";

include_once "./json_encode.php";

require_once '../lib/PHPExcel/PHPExcel.php';

require_once '../lib/PHPMailer/PHPMailerAutoload.php';



$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());

$bo_id = $_POST['bo_id'];
$bo = $_POST['bo'];

$p = permissaoVerifica($mysqli, '50', $re);

if ($p === 0) {

    $erro = "1";

    $msg = "Você não tem permissão para enviar arquivo.";
} else {

    $dir = __DIR__ . DIRECTORY_SEPARATOR . '../sbo_pdf';

    if (!is_dir($dir)) {

        mkdir($dir, 0777, true);
    }
    if (isset($_FILES['file'])) {
        $arquivo = $_FILES['file']['name'];

        $arq = $_FILES['file'];

        $ext = strtoupper(pathinfo($arquivo, PATHINFO_EXTENSION));
        if ($ext != "PDF") {
            $erro = "1";
            $msg = 'Tipo de arquivo inválido.';
        } else
        if ($_FILES['file']['size'] > 1*MB){
            $erro = "1";
            $msg = "O arquivo excede o tamanho máximo permitido." . filesize($arq);
        } else {



            $nome_sql = md5(rand(0, 2000) . $bo_id . $data . $hora);

            $nome = $nome_sql . "." . $ext;





            if (move_uploaded_file($_FILES['file']['tmp_name'], $dir . DIRECTORY_SEPARATOR . $nome)) {



                $sql_insert = "update sbo_bo set anexo='{$nome_sql}', status='3', numero_bo='{$bo}' where id='{$bo_id}'";

                $mysqli->query($sql_insert);



                bo_historico($mysqli, $bo_id, $re, date("Y-m-d H:i"), '3');



                $mysqli->close();



                $erro = "0";

                $msg = 'Arquivo carregado com sucesso...' . filesize($arq);
            } else {

                $erro = "1";

                $msg = 'Erro no upload';
            }
        }
    }
}



$arr = array("erro" => $erro, "msg" => $msg);

echo JsonEncodePAcentos::converter($arr);



function bo_historico($mysqli, $id, $re, $dh, $status)

{

    $sql = "insert into sbo_bo_historico (bo, re, dh, status) values ('{$id}', '{$re}', '{$dh}', '{$status}')";

    $mysqli->query($sql);
}



function permissaoVerifica($mysqli, $funcao, $re)

{

    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;

    return $num;
}
