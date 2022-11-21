<?php
include_once "./l_sessao.php";

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

include_once "./SCPEMAIL.php";


$re = $_SESSION["re"];
$uf_sessao = $_SESSION["uf"];
$acao = $txtTitulo['acao'];

$data = date('Y-m-d');
$hora = date('H:i', time());

$chave = md5($_SESSION['re'] . $data);

if (verificaChave($mysqli, $chave) < 1) {
    header("Location: logOut");
}

$erro = "0";
$msg = "";

if ($acao === "listaCN") {
    listaCN($mysqli, $uf_sessao);
} else
if ($acao === "listaStatus") {
    listaStatus($mysqli);
} else 
if ($acao === "SCPProcura") {

    $cn = $txtTitulo['cn'];
    $txt =$txtTitulo['txt'];
    $dataInicio = $txtTitulo['dataInicio'];
    $dataFim = $txtTitulo['dataFinal'];
    $status = $txtTitulo['status'];

    scpProcura($mysqli, $cn, $txt, $dataInicio, $dataFim, $re, $uf_sessao, $status);
} else
if ($acao === "SCPDetalhe") {

    $id = $txtTitulo['id'];
    scpDetalhes($mysqli, $id);
} else
if ($acao === "SCPValida") {

    $id = $txtTitulo['id'];
    $status = $txtTitulo['status'];
    $obs = $txtTitulo['obs'];

    scpValida($mysqli, $id, $status, $obs, $re, $data, $hora);
}

function listaSITE($mysqli)
{
    $sql = "select site.id as id, site.sigla as sigla, cn.nome as cn, stip.nome as tipo from site site inner join cn cn on cn.id=site.cn inner join site_tipo stip on stip.id=site.tipo order by tipo, cn, sigla";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaCN($mysqli, $uf)
{
    $sql = "select id, nome from cn where uf='{$uf}' order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaStatus($mysqli)
{
    $sql = "select id, nome from scp_status order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaAtividade($mysqli)
{
    $sql = "SELECT id, nome FROM scp_atividade order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function scpValida($mysqli, $id, $status, $obs, $re, $data, $hora)
{
    $erro = "1";
    $p = permissaoVerifica($mysqli, "76", $re);

    if ($p === 0) {
        $msg = "Você não tem permissão para avaliar.";
    } else
    if (strlen($obs) < 3) {
        $msg = "A Observação informada é inválida.";
    } else {
        $sql = "update scp_registro set status='{$status}', re_avaliacao='{$re}', data_avaliacao='{$data}', hora_avaliacao='{$hora}', avaliacao='{$obs}' where id='{$id}'";

        if ($mysqli->query($sql)) {

            $dadosEmail = bodyHtmlValidacao($mysqli, $id);

            enviarValidacao($mysqli, $dadosEmail, $id);

            $erro = "0";
            $msg = "Validação realizada com sucesso.";
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function ValidaData($dat)
{
    $data = explode("-", "$dat"); // fatia a string $dat em pedados, usando / como referência
    $y = $data[0];
    $m = $data[1];
    $d = $data[2];

    $res = checkdate($m, $d, $y);
    if ($res == 1) {
        return "1";
    } else {
        return "0";
    }
}
function scpProcura($mysqli, $cn, $txt, $dataInicio, $dataFim, $re, $uf, $status)
{
    $p = permissaoVerifica($mysqli, "76", $re);

    $usuario = "";

    $where = "";

    $where .= " scp.uf='{$uf}' and";

    if ($cn != "0") {
        $where .= " u.cn='{$cn}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($status != "0") {
        $where .= " scp.status='{$status}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($txt != "") {
        $where = " (u.nome like '%" . $txt . "%' or u.re like '%" . $txt . "%')";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($dataInicio != "") {

        $where .= " DATE(scp.dh) >='{$dataInicio}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($dataFim != "") {
        $where .= " DATE(scp.dh) <='{$dataFim}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    $where = $usuario . $where;

    $sql = "select scp.id as id, ativ.nome as atividade, scp.data as data, scp.data1 as data1, scp.data1 as data2, u.nome as nome, u.re as re, st.nome as status, concat(cn.nome,'/',site.sigla) as site from scp_registro scp inner join site on site.id=scp.site inner join scp_atividade ativ on ativ.id=scp.atividade inner join usuario u on u.re=scp.re inner join site_tipo stipo on stipo.id=site.tipo inner join scp_status st on st.id=scp.status inner join cn on cn.id=site.cn where " . $where;

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function scpDetalhes($mysqli, $id)
{
    $sql = "select scp.id as id, cn.nome as cn, site.sigla as site, stipo.nome as site_tipo, ativ.nome as atividade, ativ.id as ativ_id, scp.os as os, scp.data as data, scp.hora as hora, scp.data1 as data1, scp.hora1 as hora1,scp.data2 as data2, scp.hora2 as hora2, scp.obs as justificativa, scp.avaliacao as avaliacao, u.nome as nome, u.re as re, u.telefone as telefone, st.nome as status from scp_registro scp inner join site on site.id=scp.site inner join scp_atividade ativ on ativ.id=scp.atividade inner join usuario u on u.re=scp.re inner join site_tipo stipo on stipo.id=site.tipo inner join scp_status st on st.id=scp.status inner join cn on cn.id=site.cn where scp.id='{$id}'";

    $lancamento = $mysqli->query($sql)->fetch_array();

    echo JsonEncodePAcentos::converter($lancamento);

    $mysqli->close();
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
