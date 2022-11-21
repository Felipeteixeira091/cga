<?php

include "l_sessao.php";
include 'json_encode.php';

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
$hora = date('H:i', time());

$acao = $txtTitulo['acao'];

if ($acao === "SolicitacaoLista") {

    solicitacaoLista($mysqli);
} else 
if ($acao === "detalhe") {

    $solicitacao = $txtTitulo['solicitacao'];

    solicitacaoDetalhe($mysqli, $solicitacao);
} else 
if ($acao === "carrega") {

    $id = $txtTitulo['id'];
    $item = $txtTitulo['item'];
    carregaItem($mysqli, $item, $id);
} else
if ($acao === "filtra") {

    $txt = $txtTitulo['txt'];
    $status = $txtTitulo['status'];
    $data1 = $txtTitulo['data1'];
    $data2 = $txtTitulo['data2'];

    filtra($mysqli, $txt, $data1, $data2, $status, $re, $uf);
} else 
if ($acao === "statusLista") {
    statusLista($mysqli);
} else
if ($acao === "almoxLista") {
    almoxLista($mysqli, $re);
} else
if ($acao === "valida") {

    $solicitacao = $txtTitulo['solicitacao'];
    $item = $txtTitulo['item'];
    $qtd = $txtTitulo['qtd'];
    $baixa = $txtTitulo['baixa'];

    valida($mysqli, $solicitacao, $item, $qtd, $baixa, $re, $data, $hora);
} else 
if ($acao === "saldobaixa") {

    $id = $txtTitulo['id'];
    $tipo = $txtTitulo['tipo'];

    saldobaixa($mysqli, $id, $tipo);
} else
if ($acao === "conclui") {

    $solicitacao = $txtTitulo['solicitacao'];
    $obs = $txtTitulo['obs'];
    $almox = $txtTitulo['almox'];

    conclui($mysqli, $solicitacao, $obs, $almox, $re, $data, $hora);
}
function filtra($mysqli, $txt, $data1, $data2, $status, $re, $uf)
{
    $sql = "select cn.regiao as regiao from usuario u inner join cn on cn.id=u.cn where u.re='{$re}'";
    $result = $mysqli->query($sql)->fetch_array();
    $regiao = $result['regiao'];

    $txt = strtoupper($txt);
    $where = "";


    if ($data1 != "") {
        $where .= " s.data >='{$data1}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($data2 != "") {
        $where .= " s.data <='{$data2}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($txt != "") {
        $where .= " (u.re like '%" . $txt . "%' or u.nome like '%" . $txt . "%' or site.sigla like '%" . $txt . "%')";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($status != "0") {
        $where .= " s.status='{$status}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    $sql = "select s.id as id, s.data as data, s.hora as hora, ss.nome as status, site.sigla as site, u.nome as nome, cn.nome as cn from sga s inner join sga_status ss on ss.id=s.status inner join usuario u on u.re=s.re inner join site on site.id=s.site inner join cn on cn.id=u.cn WHERE cn.regiao='{$regiao}' and" . $where;

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function saldobaixa($mysqli, $id, $tipo)
{
    $sql = "select sb.id as id, st.ico as ico, st.descricao as descricao, qtd as pbaixa, qtd_entregue as baixa from sga_baixa sb inner join sga_tipo st on st.id=sb.tipo WHERE sb.id='{$tipo}'";

    $sga = $mysqli->query($sql)->fetch_array();

    $mysqli->close();
    echo JsonEncodePAcentos::converter($sga);
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
function baixaValida($mysqli, $id)
{
    $num = $mysqli->query("select qtd_entregue from sga_baixa WHERE sga='{$id}' and tipo!=0 and qtd_entregue=''")->num_rows;
    return $num;
}

function statusLista($mysqli)
{
    $sql = "select s.id as id, s.nome as nome from sga_status s";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function almoxLista($mysqli, $re)
{
    $sql = "select cn.regiao as regiao from usuario u inner join cn on cn.id=u.cn where u.re='{$re}'";
    $result = $mysqli->query($sql)->fetch_array();
    $regiao = $result['regiao'];

    if ($regiao === "2") {


        $sql = "select id, nome from sma_almoxarifado where tipo=2 and regiao='{$regiao}'";
    } else {
        $sql = "select id, nome from sma_almoxarifado where tipo=0 and regiao='{$regiao}'";
    }
    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function solicitacaoDetalhe($mysqli, $solicitacao)
{
    $sql = "select s.id as id, s.data as data, s.hora as hora, s.os as os, ss.nome as status, site.sigla as site, u.re as re, u.nome as nome, c.nome as nome_c, c.re as c_re, cn.nome as cn, if(s.tipo='1','CORRETIVA','PREVENTIVA') as atividade, ifnull(sa.nome,'PENDENTE') as almoxarifado from sga s inner join usuario u on u.re=s.re inner join site on site.id=s.site inner join cn on cn.id=u.cn inner join usuario c on c.re=u.supervisor left join sma_almoxarifado sa on sa.id=s.almoxarifado inner join sga_status ss on ss.id=s.status where s.id='{$solicitacao}'";
    $sga = $mysqli->query($sql)->fetch_array();

    $sql1 = "select sb.id as id, st.nome as nome, sb.qtd from sga_baixa sb inner join sga_tipo as st on st.id=sb.tipo where sb.sga='{$solicitacao}'";
    $item = array();
    if ($result = $mysqli->query($sql1)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $item[] = $row;
        }
    }

    $mysqli->close();
    $arr = array("sga" => $sga, "item" => $item);
    echo JsonEncodePAcentos::converter($arr);
}
function carregaItem($mysqli, $item)
{
    $sql = "select s.id as id, st.nome as item, st.descricao pa_desc, st.ico as ico, s.qtd as qtd, s.qtd_entregue as qtdV from sga_baixa s inner join sga_tipo st on st.id=s.tipo where s.id='{$item}'";

    $item = $mysqli->query($sql)->fetch_assoc();

    echo JsonEncodePAcentos::converter($item);
}
function valida($mysqli, $solicitacao, $item, $qtd, $baixa, $re, $data, $hora)
{
    $p = permissaoVerifica($mysqli, "84", $re);
    $erro = "1";

    $declarado = vDeclarado($mysqli, $item);


    if ($p === 0) {
        $msg = "Você não tem permissão necessária.";
    } else
    if ($qtd > $declarado) {
        $msg = "A quantidade recebida não pode ser maior do que informado pelo técnico.";
    } else
    if ($item === "0") {
        $msg = "Necessário selecionar o ítem a ser baixado.";
    } else
    if ($qtd === 0 || !$qtd) {
        $msg = "A quantidade informada é inválida.";
    } else {

        $sql = "update sga_baixa set qtd_entregue='{$qtd}', data='{$data}', hora='{$hora}' where id='{$item}'";

        if ($mysqli->query($sql)) {
            $msg = "Ítem recebido com sucesso.";
        }

        $erro = "0";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function vDeclarado($mysqli, $item)
{

    $consulta = $mysqli->query("SELECT sb.qtd as declarado FROM sga_baixa sb WHERE sb.id='{$item}'")->fetch_array();

    return $consulta['declarado'];
}
function conclui($mysqli, $solicitacao, $obs, $almox, $re, $data, $hora)
{
    $p = permissaoVerifica($mysqli, "85", $re);
    $erro = "1";

    $b = baixaValida($mysqli, $solicitacao);

    if ($p === 0) {
        $msg = "Você não tem permissão necessária.";
    } else
    if ($b > 0) {
        $msg = "Alguns ítens não foram validados.";
    } else
    if ($almox === "0") {
        $msg = "Necessário informar o almoxarifado de recebimento.";
    } else {

        $sql = "update sga set status='2', almoxarifado='{$almox}', almoxarifado_re='{$re}', obs='{$obs}', data='{$data}', hora='{$hora}' where id='{$solicitacao}'";

        if ($mysqli->query($sql)) {
            $msg = "Baixa concluída com sucesso.";
        }

        $erro = "0";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
