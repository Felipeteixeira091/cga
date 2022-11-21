<?php
include "l_sessao.php";
include 'json_encode.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");

$tipo = $_POST['tipo'];
$sce = $_POST['sce'];
$dh = date("Y-m-d H:i");

$erro = "1";
$cont = 0;

$dir = __DIR__ . DIRECTORY_SEPARATOR . '../sce';

//$p = permissaoVerifica($mysqli, "82", $re);


if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

if ($sce === "" || !$sce || $sce === 0) {

    $msg = "Primeiro é necessário cadastrar a solicitação.";
} else
if ($tipo === "0") {

    $msg = "Necessário selecionar o tipo do arquivo.";
} else
    if (isset($_FILES['file'])) {

    $arquivo = $_FILES['file']['name'];

    $ext = strtoupper(pathinfo($arquivo, PATHINFO_EXTENSION));

    if ($ext === "JPEG" || $ext === "JPG" || $ext === "PNG") {

        $nomeAnexo = md5($dh . $tipo . $re . $sce) . "." . $ext;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $dir . DIRECTORY_SEPARATOR . $nomeAnexo)) {

            $verifica = $mysqli->query("select id from sce_anexo where sce='{$sce}' and tipo='{$tipo}'")->num_rows;
            if ($verifica > 0) {

                $sql = "update sce_anexo set arquivo='{$nomeAnexo}', dh='" . $dh . "' where sce='{$sce}' and tipo='{$tipo}'";
            } else {

                $sql = "insert into sce_anexo (sce, tipo, arquivo, dh) values ('{$sce}', '{$tipo}', '{$nomeAnexo}', '" . $dh . "')";
            }
            $mysqli->query($sql);

            $cont = anexoVerifica($mysqli, $sce);

            if ($cont === 2) {
                $mysqli->query("update solicitacao set status='6' where id='{$sce}'");
                $msg = "Solicitação concluída com sucesso.";
            } else {
                $msg = "Arquivo carregado com sucesso.";
            }

            $mysqli->close();
            $erro = "0";
        } else {
            $erro = "1";
            $msg = "Erro ao enviar arquivo.";
        }
    } else {
        $erro = "1";
        $msg = "Tipo de arquivo inválido.";
    }
}
//}

$arr = array("erro" => $erro, "msg" => $msg, "contador" => $cont);
echo JsonEncodePAcentos::converter($arr);

function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
function anexoVerifica($mysqli, $sce)
{
    $num = $mysqli->query("select id from sce_anexo where sce='{$sce}'")->num_rows;
    return $num;
}
