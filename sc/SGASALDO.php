<?php

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include "l_sessao.php";
include 'json_encode.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());

$chave = md5($_SESSION['re'] . $data);

if (verificaChave($mysqli, $chave) < 1) {
    //    header("Location: logOut");
}

$acao = $txtTitulo['acao'];

if ($acao === "listaElemento") {

    listaSaldo($mysqli, $re);
} else 
if ($acao === "tipoLista") {
    tipoLista($mysqli);
} else
if ($acao === "verifica") {

    verifica($mysqli, $re);
} else
if ($acao === "saldo") {

    $id = $txtTitulo['id'];
    $tipo = $txtTitulo['tipo'];
    saldo($mysqli, $id, $tipo);
} else
if ($acao === "cria") {

    $site = $txtTitulo['site'];
    $tipo = $txtTitulo['tipo'];
    $os = $txtTitulo['os'];

    cria($mysqli, $re, $data, $hora, $site, $tipo, $os);
} else
if ($acao === "add") {

    $id = $txtTitulo['id'];
    $tipo = $txtTitulo['tipo'];
    $qtd = $txtTitulo['qtd'];

    add($mysqli, $re, $data, $hora, $id, $tipo, $qtd);
} else
if ($acao === "conclui") {
    $id = $txtTitulo['id'];
    conclui($mysqli, $id);
} else
if ($acao === "cancela") {
    $id = $txtTitulo['id'];
    cancela($mysqli, $id);
}
function tipoLista($mysqli)
{
    $sql = "select id, nome, ico from sga_tipo";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function lcEstoque($mysqli, $re)
{
    $sql = "select st.id as tipo_id, st.nome as tipo, st.ico as ico, sum(pa.multiplicador*if(ss.tipo=2,(-1*ssi.quantidade),ssi.quantidade)) as sma, ifnull((SELECT sum(sb.qtd) FROM sga_baixa sb inner join sga s on s.id=sb.sga where s.re=ss.re_retirada and sb.tipo=st.id),0) as pb_sga, ifnull((SELECT sum(sb.qtd_entregue) FROM sga_baixa sb inner join sga s on s.id=sb.sga where s.re=ss.re_retirada and sb.tipo=st.id),0) as sga from sma_solicitacao_itens ssi inner join sma_solicitacao ss on ss.id=ssi.solicitacao inner join sma_pa pa on pa.id=ssi.pa inner join sga_tipo st on st.id=pa.sga_tipo WHERE ss.data>='2021-09-01' and ss.status=3 and ss.re_retirada='{$re}' and pa.sga=1 group by st.nome";
    $sql_sga = "select st.nome as tipo, sb.qtd as declarado, sb.qtd_entregue recebido, concat(sg.data,' ',sg.hora) as dh from sga_baixa sb inner join sga sg on sg.id=sb.sga inner join sga_tipo st on st.id=sb.tipo WHERE sg.status=2 and sg.re='{$re}' order by dh";
    $sql_sma = "select sp.descricao as pa, (ssi.quantidade*sp.multiplicador) as qtd, ss.solicitacao as dh from sma_solicitacao_itens ssi inner join sma_pa sp on sp.id=ssi.pa inner join sma_solicitacao ss on ss.id=ssi.solicitacao inner join sga_tipo st on st.id=sp.sga_tipo WHERE ss.data>='2021-09-01' and ss.re_retirada='{$re}' and sp.sga=1 and ss.status=3 order by ss.solicitacao;";

    $myArray_g = array();
    $myArray_sma = array();
    $myArray_sga = array();

    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray_g[] = $row;
        }
    }
    if ($result = $mysqli->query($sql_sma)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray_sma[] = $row;
        }
    }
    if ($result = $mysqli->query($sql_sga)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray_sga[] = $row;
        }
    }
    $arr = array("geral" => $myArray_g, "sma" => $myArray_sma, "sga" => $myArray_sga);
    echo JsonEncodePAcentos::converter($arr);
}
function listaSaldo($mysqli, $re)
{
    $sql = "select st.nome as nome, st.ico as ico, st.descricao as descricao, sum(sb.qtd) as pBaixa, sum(sb.qtd_entregue) as baixa, ifnull((select sum(ssi.quantidade) from sma_solicitacao_itens ssi inner join sma_pa sp on sp.id=ssi.pa inner join sma_solicitacao ss on ss.id=ssi.solicitacao where sp.sga_tipo=st.id and ss.data>='01-09-2021' and ss.re=sga.re),0) sma from sga_baixa sb inner join sga on sga.id=sb.sga inner join sga_tipo st on st.id=sb.tipo where sga.re='{$re}' group by st.nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function verifica($mysqli, $re)
{
    $ativo = $mysqli->query("select id from sga where status='3' and re='{$re}'")->num_rows;

    $sql = "select s.id as id, s.tipo as tipo, s.os as os, concat(stt.nome,' ',st.sigla) site, st.id as id_site from sga s inner join site st on st.id=s.site inner join site_tipo stt on stt.id=st.tipo where s.status='3' and s.re='{$re}'";

    if ($ativo === 0) {
        $ativo = "nd";
        $sga = "nd";
    } else {
        $sga = $mysqli->query($sql)->fetch_array();
    }
    $arr = array("ativo" => $ativo, "sga" => $sga);
    $mysqli->close();
    echo JsonEncodePAcentos::converter($arr);
}
function saldo($mysqli, $id, $tipo)
{
    $sql = "select id, qtd as saldo from sga_baixa where sga='{$id}' and tipo='{$tipo}'";

    $sga = $mysqli->query($sql)->fetch_array();

    $mysqli->close();
    echo JsonEncodePAcentos::converter($sga);
}
function cria($mysqli, $re, $data, $hora, $site, $tipo, $os)
{
    $erro = "1";

    $pendente = $mysqli->query("SELECT re FROM sga WHERE re='{$re}' and (status=3)")->num_rows;


    $sql = "insert into sga (tipo, os, site, re, status, data, hora) values ('{$tipo}','{$os}','{$site}','{$re}','3', '{$data}','{$hora}')";


    if ($pendente > 0) {
        $msg = "Já existe uma solicitação de devolução pendente.";
    } else
    if ($site === "0" || !$site || $site === "") {
        $msg = "Necessário selecionar o site.";
    } else
    if ($tipo === "0") {
        $msg = "Necessário selecionar o tipo da Os.";
    } else
    if ($os === "0" | !$os) {
        $msg = "Necessário informar o número da OS.";
    } else {

        if ($mysqli->query($sql)) {
            $msg = "Solicitação criada com sucesso.";
            $erro = "0";
        } else {

            $msg = "Erro ao criar solicitação.";
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function add($mysqli, $re, $data, $hora, $id, $tipo, $qtd)
{
    $erro = "1";

    $verifica = $mysqli->query("select id from sga_baixa where sga='{$id}' and tipo='{$tipo}'")->num_rows;
    $consulta = $mysqli->query("select st.id as tipo_id, st.nome as tipo, st.ico as ico, sum(pa.multiplicador*ssi.quantidade) as sma, ifnull((SELECT sum(sb.qtd) FROM sga_baixa sb inner join sga s on s.id=sb.sga where s.re=ss.re_retirada and sb.tipo=st.id),0) as pb_sga, ifnull((SELECT sum(sb.qtd_entregue) FROM sga_baixa sb inner join sga s on s.id=sb.sga where s.re=ss.re_retirada and sb.tipo=st.id),0) as sga from sma_solicitacao_itens ssi inner join sma_solicitacao ss on ss.id=ssi.solicitacao inner join sma_pa pa on pa.id=ssi.pa inner join sga_tipo st on st.id=pa.sga_tipo WHERE ss.data>='2021-09-01' and ss.status=3 and ss.re_retirada='{$re}' and ss.tipo=1 and st.id='{$tipo}' and pa.sga=1 group by st.nome")->fetch_array();

    $saldo = $consulta['sma'] - ($consulta['sga'] + $consulta['pre']);


    if ($verifica === 0) {
        $sql = "insert into sga_baixa (re, sga, tipo, qtd, data, hora) values ('{$re}','{$id}','{$tipo}','{$qtd}','{$data}','{$hora}')";
    } else {
        $sql = "update sga_baixa set qtd='{$qtd}' where sga='{$id}' and tipo='{$tipo}'";
    }

    if ($qtd === "" | !$qtd) {
        $msg = "Necessário informar a quantidade.";
    } else
     if (intval($qtd) > intval($saldo)) {
        $msg = "Você não pode descartar uma quantidade maior do que tem de saldo.";
    } else {
        if ($mysqli->query($sql)) {

            $msg = "Saldo atualizado com sucesso.saldo:" . $saldo . "qtd:" . $qtd;
            $erro = "0";
        } else {

            $msg = "Erro ao atualizar saldo.";
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function conclui($mysqli, $id)
{
    $erro = "1";

    $verifica = $mysqli->query("select id from sga_baixa where sga='{$id}'")->num_rows;

    if ($verifica === 0) {
        $msg = "Nenhum ítem foi adicionado à baixa.";
    } else {
        $sql = "update sga set status='1' where id='{$id}'";

        if ($mysqli->query($sql)) {
            $msg = "Baixa solicitada com sucesso.";
        }

        $erro = "0";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function cancela($mysqli, $id)
{
    $erro = "1";

    $sql1 = "delete from sga where id='{$id}'";
    $sql2 = "delete from sga_baixa where sga='{$id}'";
    $mysqli->query($sql1);
    $mysqli->query($sql2);

    $erro = "0";
    $msg = "Solicitação cancelada com sucesso.";

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
