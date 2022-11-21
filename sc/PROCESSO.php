<?php

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";
require_once '../lib/PHPMailer/PHPMailerAutoload.php';
include_once "./frame/Email.php";

session_start();
$re_sessao = $_SESSION["re"];
$uf_sessao = $_SESSION["uf"];

$acao = $txtTitulo['acao'];
$data = date('Y-m-d');
$hora = date('H:i');

$erro = "0";
$msg = "";

if ($acao === "lista") {

    lista($mysqli, $re_sessao);
} else
if ($acao === "tipo") {

    tipo($mysqli);
} else
if ($acao === "novo") {

    $processo = $txtTitulo['processo'];

    novo($mysqli, $re_sessao, $processo, date("Y-m-d H:i"));
} else
if ($acao === "exclui") {

    exclui($mysqli, $txtTitulo['id'], $re_sessao);
}
function exclui($mysqli, $id, $re)
{

    $sql = "delete from processo where id='{$id}'";
    $p = permissaoVerifica($mysqli, "94", $re);
    $erro = "1";
    $msg = "";

    if ($p === 0) {
        $msg = "Você não tem permissão para excluir processos.";
    } else
    if ($mysqli->query($sql)) {

        $msg = "Processo excluído com sucesso.";
    } else {
        $erro = "0";
        $msg = "Erro ao excluir processo.";
    }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function lista($mysqli, $re)
{
    $sql = "SELECT p.id as id, pt.nome as tipo, p.nome as nome, p.descricao as descricao, p.anexo as anexo FROM processo p inner join processo_tipo pt on pt.id=p.tipo";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        //        echo JsonEncodePAcentos::converter($myArray);
    }

    $p = permissaoVerifica($mysqli, "94", $re);
    $arr = array("processo" => $myArray, "p" => $p);
    echo JsonEncodePAcentos::converter($arr);

    $mysqli->close();
}
function novo($mysqli, $re, $processo, $dh)
{
    $p = permissaoVerifica($mysqli, "93", $re);
    $erro = "1";
    $msg = "";

    if ($p === 0) {
        $msg = "Você não tem permissão para cadastrar processos.";
    } else
    if ($processo['tipo'] === "0" || $processo['tipo'] === 0) {
        $msg = "Necessário informar o tipo do processo.";
    } else
    if ($processo['nome'] === "") {
        $msg = "Necessário informar o nome do processo.";
    } else
    if (strlen($processo['desc']) < 5) {
        $msg = "A descrição do processo é insuficiente.";
    } else
    if ($processo['anexo'] === "pendente" || $processo['anexo'] === "") {
        $msg = "O arquivo contendo o processo não foi anexado.";
    } else {

        $sql = "UPDATE processo set tipo='" . $processo['tipo'] . "', nome='" . $processo['nome'] . "', descricao='" . $processo['desc'] . "', dh='{$dh}' where anexo='" . $processo['anexo'] . "'";
        $mysqli->query($sql);

        $erro = "0";
        $msg = "Processo cadastrado com sucesso.";
    }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function dados($mysqli, $re)
{
    $sql = "select usr.re as re, usr.nome as nome, usr.estado as estado, usr.supervisor as coordenador, usr.cargo as cargo, usr.email as email, usr.telefone as telefone, usr.cn as cn, usr.sistema as sistema, usr.ativo as ativo from usuario usr WHERE usr.re='{$re}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $result->close();

    echo JsonEncodePAcentos::converter($row);

    $mysqli->close();
}
function tipo($mysqli)
{
    $sql = "SELECT id, nome FROM processo_tipo ORDER BY nome";
    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
