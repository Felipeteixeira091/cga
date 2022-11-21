<?php

include "l_sessao.php";

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

include 'SMAXLS.php';
include 'SMAEMAIL.php';

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

/** Include PHPExcel */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$re = $_SESSION['re'];
$uf = $_SESSION['uf'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('dH:i', time());

$acao = $txtTitulo['acao'];

if ($acao === "lista_status") {
    listaStatus($mysqli);
} else if ($acao === "searchTec") {
    $txt = $txtTitulo['txt'];
    tecSearch($mysqli, $txt, $_SESSION['re']);
} else if ($acao === "generate") {

    stampGenerate($mysqli, $txtTitulo);
}
function stampGenerate($mysqli, $data)
{

    $msg = "";
    $erro = "1";
    $string = "";
    $ta = intval($data['ta']);

    if (strlen($ta) <= 4) {

        $msg = "O número de TA informado é inválido.";
    } else 
    if ($data['status'] === "0") {
        $msg = "O status informado é inválido.";
    } else
    if (($data['status'] === "4" or $data['status'] === "6") and (!$data['os'] or strlen($data['os']) < "6" or $data['os'] === 0)) {
        $msg = "O número da Os correto de fatura B é necessário";
    } else
    if (!$data['prediction']) {
        $msg = "A data de previsão não foi informada.";
    } else
    if (date('d/m/Y H:i', strtotime($data['prediction'])) <= date('d/m/Y H:i')) {
        $msg = "A data de previsão deve ser maior do que a atual.";
    } else
    if (strlen($data['note']) < 10) {
        $msg = "A inforção de atualização é inválida.";
    } else {
        
        $now = strtotime($data['prediction']);///new DateTime(date('d/m/Y H:i', strtotime($data['prediction'])));
        $previsao = 1;//new DateTime(date('d/m/Y H:i'));
        
        $now = new DateTime(strtotime(date('d/m/Y H:i')));
        $previsao = new DateTime($data['prediction']);
        $diff = $now->diff($previsao)->h;

        $string = "Atualização:" . date('d/m/Y H:i') . "|";
        $string .=  "Previsão:" . date('d/m/Y H:i', strtotime($data['prediction']))  . "|";
        $string .=  "Status:" . strtoupper($data['status_text']) . "|";
        $string .=  "Os:" . $data['os'] . "|";
        $string .=  "Técnico:" . $data['technician_name'] . " " . $data['technician_re'] . "|";
        $string .=  "Observação:" . $data['note']. " tempo restante:".$diff. " horas |";
        $string .=  "GA:" . $data['ga_name'] . " " . $data['ga_re'] .  "|";
        $string .=  "Responsabilidade:" . $data['responsibility_text'];

        $erro = "0";
        $msg = "Carimbo gerado com sucesso.";

        $txt = addslashes($data['note']);
        $dh = date("Y-m-d H:i");
        $ga = $data['ga_re'];
        $technician = $data['technician_re'];
        $stamp = $string;

        $sql = "insert into cga_stamp (ta, dh, prediction, status, os, technician, note, ga, responsibility, text, stamp) values ('" . $data['ta'] . "','" . $dh . "', '" . $data['prediction'] . "', '" . $data['status'] . "','" . $data['os'] . "', '" . $technician . "', '" . $data['note'] . "', '" . $ga . "', '" . $data['responsibility'] . "', '" . $txt . "', '" . $stamp . "')";
        @$mysqli->query($sql);
    }

    $arr = array("erro" => $erro, "string" => $string, "msg" => $msg);
    echo json_encode($arr);
     stampCreate($mysqli, $data);
}
function stampCreate($mysqli, $data)
{
   // $sql = "insert into stamp (ta, dh, prediction, status, os, technician, note, ga, responsibility, stamp) values ('" . $data['ta'] . "','" . $data['dh'] . "', '" . $data['prediction'] . "', '" . $data['status'] . "','" . $data['os'] . "', '" . $data['technician'] . "', '" . $data['note'] . "', '" . $data['ga'] . "', '" . $data['responsibility'] . "', '" . $data['stamp'] . "')";

 //   $mysqli->query($sql);
}
function tecSearch($mysqli, $txt, $re_sessao)
{
    $txt = strtoupper($txt);

    $sql = "SELECT uf.sigla as uf, u.nome as name, u.re as re, u.telefone as telefone FROM usuario u inner join uf on uf.id=u.estado WHERE u.ativo=2 and u.nome like '%" . $txt . "%'";
    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo json_encode($myArray);
    }
}
function listaStatus($mysqli)
{
    $sql = "select id, descricao from cga_status WHERE ativo=1 ORDER BY descricao";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo json_encode($myArray);
    }
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
