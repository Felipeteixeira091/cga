<?php
include "l_sessao.php";
include 'json_encode.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");

$nome = $_POST['nome'];
$nota = $_POST['nota'];
$dh = date("Y-m-d H:i");

$erro = "1";

$dir = __DIR__ . DIRECTORY_SEPARATOR . '../nota';

$p = permissaoVerifica($mysqli, "82", $re);


if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}
if ($nota === "" || !$nota || $nota === 0){
    $erro = "1";
    $msg = "Primeiro é necessário cadastrar a nota.";
} else
    if (isset($_FILES['file'])) {

        $arquivo = $_FILES['file']['name'];

        $ext = strtoupper(pathinfo($arquivo, PATHINFO_EXTENSION));

        if ($ext === "PDF" || $ext === "MSG") {

            $nomeAnexo = md5($dh . $nome . $re . $nota) . "." . $ext;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $dir . DIRECTORY_SEPARATOR . $nomeAnexo)) {

                $sql = "insert into nota_anexo (nota, nome, arquivo, dh) values ('{$nota}', '{$nome}', '{$nomeAnexo}', '" . $dh . "')";

                $mysqli->query($sql);
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
