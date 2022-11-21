<?php

include "l_sessao.php";
include 'json_encode.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());

$acao = "deleta";
$txtTitulo['acao'];

if ($acao === "nota_verifica") {

    verifica($mysqli, $re);
} else
if ($acao === "nota_obter") {

    nota_obter($mysqli, $re, @$txtTitulo['id']);
} else
if ($acao === "lista") {

    lista($mysqli, $txtTitulo['tipo'], @$txtTitulo['id']);
} else
if ($acao === "notaProcura") {

    notaProcura($mysqli, $txtTitulo['txt'], $txtTitulo['data1'], $txtTitulo['data2'], $txtTitulo['status'], $txtTitulo['fornecedor']);
} else
if ($acao === "nova") {

    nova($mysqli, $txtTitulo['fornecedor'], $txtTitulo['tipo'], $re, date("Y-m-d H:i"));
} else
if ($acao === "update") {

    update($mysqli, $re, $txtTitulo['id'], $txtTitulo['status']);
} else
if ($acao === "deleta") {

    $nota = "28";
    deleta($mysqli, $re, $nota);
}
function deleta($mysqli, $re, $nota)
{

    $sql = "select na.id as id_anexo, na.arquivo as arquivo from nota_anexo na where na.nota='{$nota}'";

    $dir = '../nota/';
    echo "Caminho: " . $dir . "<br>";

    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

            $arquivo = $dir . $row['arquivo'];

            if (file_exists($arquivo)) {
                if (unlink($arquivo)) {

                    echo "Arquivo " . $row['arquivo'] . " deletado com sucesso!<br>";
                } else {
                    echo "Arquivo " . $row['arquivo'] . " Não foi deletado!<br>";
                }
            } else{
                echo "O arquivo " . $row['arquivo'] . " Não existe.<br>";
            }
        }
    }
}
function verifica($mysqli, $re)
{

    if ($mysqli->query("select id from nota where re='{$re}' and status=1")->num_rows > 0) {
        $tipo = "pendente";
    } else {
        $tipo = "novo";
    }

    $arr = array("tipo" => $tipo);
    echo JsonEncodePAcentos::converter($arr);
};
function update($mysqli, $re, $id, $status)
{
    $dados = $mysqli->query("select id, re, status from nota where id='{$id}'")->fetch_assoc();
    $verifica_anexo = $mysqli->query("select id from nota_anexo where nota='{$id}'")->num_rows;
    $p = permissao($mysqli, 57, $re);
    $erro = "1";

    if ($id === "" || !$id) {
        $msg = "Não existe uma nota ativa para ser atualizada.";
    } else
    if ($verifica_anexo === 0) {
        $msg = "O cadastro não pode prosseguir sem anexo(s).";
    } else
    if ($dados['re'] != $re and $p === 0) {
        $msg = "Somente quem iniciou o cadastro da nota pode atualiza-la.";
    } else
    if ($mysqli->query("update nota set status='{$status}' where id='{$id}'")) {
        $msg = "Nota atualizada com sucesso.";
        $erro = "0";
    } else {
        $msg = "Erro ao atualizar nota.";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function nota_obter($mysqli, $re, $id)
{
    $sql = "select u.nome as nome, n.id as id, n.fornecedor as fornecedor, n.tipo as tipo, n.status as status, concat('Status: ',ifnull(ns.nome,'EM CADASTRO')) as status_span from nota n inner join usuario u on u.re=n.re left join nota_status ns on ns.id=n.status where ";

    $retorno = array();
    $pendente = $mysqli->query($sql . "n.re='{$re}' and n.status=1")->fetch_assoc();
    $verificar = $mysqli->query($sql . "n.id='{$id}'")->fetch_assoc();

    if ($id === "0" and $pendente) {
        $retorno['existe'] = "s";
        $retorno['pendente'] = "s";
        $retorno['dados'] = $pendente;
    } else if ($verificar) {
        $retorno['existe'] = "s";
        $retorno['pendente'] = "n";
        $retorno['dados'] = $verificar;
    } else {
        $retorno['existe'] = "n";
        $retorno['pendente'] = "n";
        $retorno['dados'] = "";
    }
    echo JsonEncodePAcentos::converter($retorno);
}
function nova($mysqli, $fornecedor, $tipo, $re, $dh)
{
    $sql = "insert into nota (re, fornecedor, tipo, status, dh) values ('{$re}','{$fornecedor}', '{$tipo}', 1, '{$dh}')";
    $verifica_nota = $mysqli->query("select id from nota where re='$re' and status=1")->num_rows;
    $erro = "1";
    $msg = "";
    $nota = "";
    $sql;

    if ($verifica_nota > 0) {
        $msg = "Já existe uma nota em processo de cadastro.";
    } else
    if ($fornecedor === "0") {
        $msg = "Necessário selecionar o fornecedor.";
    } else if ($tipo === "0") {
        $msg = "Necessário selecionar o tipo de nota.";
    } else if ($mysqli->query($sql)) {
        $sql = "select u.nome as nome, n.id as id from nota n inner join usuario u on u.re=n.re where n.id=" . $mysqli->insert_id;
        $nota = $mysqli->query($sql)->fetch_assoc();
        $erro = "0";
        $msg = "Cadastro iniciado com sucesso.";
    } else {
        $msg = "Erro encontrado, tente novamente";
    }
    $arr = array(
        "erro" => $erro,
        "msg" => $msg,
        "nota" => $nota
    );

    echo JsonEncodePAcentos::converter($arr);
}
function lista($mysqli, $tipo, $filtro)
{
    if ($tipo === "status1") {
        $sql = "select id as cod, nome as txt from nota_status";
    } else
    if ($tipo === "anexo") {
        $sql = "select a.id as id, a.nome as nome, a.arquivo as arquivo, a.dh as dh from nota_anexo a where a.nota='{$filtro}'";
    } else
    if ($tipo === "fornecedor" || $tipo === "fornecedor1") {
        $sql = "select nome as txt, id as cod from usuario where cargo=43 order by nome";
    } else
    if ($tipo === "tipo_nota") {
        $sql = "select nome as txt, id as cod from nota_tipo";
    }
    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}

function permissao($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
function notaDetalhe($mysqli, $nota)
{
    $sql = "select n.id as id, cn.nome as cn, s.re as solicitante_re, s.nome as solicitante_nome, c.nome as colaborador_nome, c.re as colaborador_re, sit.sigla as site, n.data as data, n.hora as hora, n.dataNota as dataNota, n.status as IdStatus, ns.nome as status, concat('R$ ',n.valor) as valor, nt.nome as tipo, n.os as os, nm.nome as motivo, n.anexo as anexo from ext_nota n inner join usuario s on s.re=n.re inner join usuario c on c.re=n.colaborador inner join site sit on sit.id=n.site inner join ext_nota_status ns on ns.id=n.status inner join cn cn on cn.id=sit.cn inner join ext_nota_tipo nt on nt.id=n.tipo inner join ext_nota_motivo nm on nm.id=n.motivo where n.id=" . $nota . "";
    $sql_historico = "select nv.id as id, nv.nota as nota, nv.data as data, nv.hora as hora, ns.nome as status, u.nome as nome, nv.obs from ext_nota_vida nv inner join ext_nota_status ns on ns.id=nv.status inner join usuario u on u.re=nv.re where nota='{$nota}' order by data, hora";

    $res = $mysqli->query($sql);
    $detalhe = $res->fetch_assoc();

    $result3 = $mysqli->query($sql_historico);

    $hs = "<table class='table table-sm table-striped w-auto'>";
    $hs .= "<thead class='thead-dark'>";
    $hs .= "<tr>";
    $hs .= "<th scope='col'>STATUS</th>";
    $hs .= "<th scope='col'>DATA/HORA</th>";
    $hs .= "<th scope='col'>OBS</th>";
    $hs .= "</thead>";
    $hs .= "</tr>";
    while ($row3 = $result3->fetch_array(MYSQLI_ASSOC)) {

        $hs .= "<tr class='small'>";
        $hs .= "<td>" . $row3['status'] . "</td>";
        $hs .= "<td>" . $row3['data'] . " - " . $row3['hora'] . "</td>";
        $hs .= "<td>" . $row3['obs'] . "</td>";
        $hs .= "</tr>";
    }

    if ($hs == "") {
        $historico = "Solicitação sem histórico";
    } else {
        $historico = $hs;
    }

    $arr = array(
        "detalhe" => $detalhe,
        "historico" => $historico
    );

    $mysqli->close();

    echo JsonEncodePAcentos::converter($arr);
}
function notaProcura($mysqli, $txt, $data1, $data2, $status, $fornecedor)
{

    $txt = strtoupper($txt);
    $where = "";

    if ($data1 != "") {
        $where .= " DATE(n.dh) >='{$data1}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($data2 != "") {
        $where .= " DATE(n.dh) <='{$data2}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($status > 0) {
        $where .= " n.status='{$status}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($fornecedor > 0) {
        $where .= " n.fornecedor='{$fornecedor}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($txt != "") {
        $where .= " (n.re like '%" . $txt . "%' or s.nome like '%" . $txt . "%' or t.nome like '%" . $txt . "%' or u.nome like '%" . $txt . "%' or f.nome like '%" . $txt . "%')";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }
    $sql = "select n.id as id, n.dh as dh, u.nome as nome, s.nome as status, t.nome as tipo, f.nome as fornecedor from nota n inner join nota_status s on s.id=n.status inner join usuario u on u.re=n.re inner join usuario f on f.id=n.fornecedor inner join nota_tipo t on t.id=n.tipo where" . $where . "";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
