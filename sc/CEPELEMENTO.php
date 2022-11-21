<?php

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";
include_once "./l_sessao.php";

require_once '../lib/PHPExcel/PHPExcel.php';

$re_sessao = $_SESSION['re'];
$uf_sessao = $_SESSION['uf'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());


$chave = md5($_SESSION['re'] . $data);

if (verificaChave($mysqli, $chave) < 1) {
    //    header("Location: logOut");
}

$acao = $txtTitulo['acao'];

if ($acao === "filtraElemento") {

    $status = @$txtTitulo['status'];
    $cn = @$txtTitulo['cn'];
    $data1 = @$txtTitulo['data1'];
    $data2 =  @$txtTitulo['data2'];

    filtra($mysqli, $re_sessao, $status, $cn, $data1, $data2);
} else
if ($acao === "elemento") {

    $id = $txtTitulo['id'];
    elemento($mysqli, $id);
} else
if ($acao === "elementoUpdate") {

    $elemento = $txtTitulo['elemento'];
    $tipo = $txtTitulo['tipo'];

    if ($tipo === "inicia") {
        $dados = array("status" => "3", "permissao" => "38", "acao" => "TRATATIVA INICIADA", "msg0" => "Você não tem permissão para iniciar tratativa.", "msg1" => "Nenhum elemento foi enviado para início.", "msg2" => "Nenhum dos elemento foi iniciado devido à questões de status.", "msg3" => " elemento iniciado com sucesso.", "msg4" => " elementos iniciados com sucesso.");
    } else
    if ($tipo === "conclui") {
        $dados = array("status" => "2", "permissao" => "34", "acao" => "TRATATIVA CONCLUÍDA", "msg0" => "Você não tem permissão para concluir tratativa.", "msg1" => "Nenhum elemento foi enviado para conclusão.", "msg2" => "Nenhum elemento foi concluído devido à questões de status.", "msg3" => " elemento concluído com sucesso.", "msg4" => " elementos concluidos com sucesso.");
    } else
    if ($tipo === "cancela") {
        $dados = array("status" => "5", "permissao" => "36", "acao" => "TRATATIVA CANCELADA", "msg0" => "Você não tem permissão para cancelar tratativa.", "msg1" => "Nenhum elemento foi enviado para cancelamento.", "msg2" => "Nenhum elemento foi cancelado devido à questões de status.", "msg3" => " elemento cancelado com sucesso.", "msg4" => " elementos cancelados com sucesso.");
    }

    elementoUpdate($mysqli, $elemento, $dados, $data, $hora, $re_sessao);
} else
if ($acao === "elementoConclui") {

    $id = $txtTitulo['id'];
    concluiElemento($mysqli, $id, $re_sessao);
} else
if ($acao === "listaCn") {

    listaCn($mysqli, $re_sessao);
} else
if ($acao === "listaStatus") {

    listaStatus($mysqli);
}

function filtra($mysqli, $re, $status, $cn, $data1, $data2)
{
    $p = permissaoVerifica($mysqli, "35", $re);
    $gestao = regiao($mysqli, $re)['gestao'];

    $erro = "1";
    $myArray = array();
    $msg = "";

    if ($status === "0") {
        $msg = "O campo *Status é obrigatório.";
    } else {
        $erro = "0";
        $where = "";

        if ($status != 0) {
            $where .= " status='{$status}'";
        }
        if ($where != "" and substr($where, -3) != "and") {
            $where .= " and";
        }
        if ($cn != 0) {
            $where .= " sit.cn='{$cn}'";
        }
        if ($where != "" and substr($where, -3) != "and") {
            $where .= " and";
        }
        if ($data1 != "") {
            $where .= " ele.data_cadastro >='{$data1}'";
        }
        if ($where != "" and substr($where, -3) != "and") {
            $where .= " and";
        }
        if ($data2 != "") {
            $where .= " ele.data_cadastro <='{$data2}'";
        }
        if ($where != "" and substr($where, -3) != "and") {
            $where .= " and";
        }

        if (substr($where, -3) === "and") {
            $where =  substr($where, 0, (strlen($where) - 3));
        }

        $sql = "select ele.id as id, sit.tipo as tipo_site, ele.ePai as ePai, sit.sigla as site, ifnull(se.sigla,'ELEMENTO_PAI') as estrutura, ele.estrutura_n as estrutura_n, sel.excel as excel, sel.ativo_pai as ativo_pai, sel.sigla as elemento, ele.elemento_n as elemento_n, ele.fcc as fcc, ele.data_cadastro as data, ele.hora_cadastro as hora, es.nome as status, ele.re as re, uf.sigla as uf, un.nome as unidade from cep_elemento ele left join site sit on sit.id=ele.site inner join cn on cn.id=sit.cn inner join uf on uf.id=cn.uf left join cep_site_estrutura se on se.id=ele.estrutura left join cep_site_elemento sel on sel.id=ele.elemento left join cep_elemento_status es on es.id=ele.status inner join usuario u on u.re=ele.re inner join unidade_negocio un on un.id=cn.fk_unidade WHERE u.gestao='{$gestao}' and" . $where . " order by ele.data_cadastro, ele.hora_cadastro";

        $contaElemento = $mysqli->query($sql)->num_rows;

        if ($contaElemento === 0) {
            $erro = "1";
            $msg = "Nenum elemento encontrado.";
        } else
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $myArray[] = $row;
            }
            $erro = "0";
            $msg = $contaElemento . " elemento(s) encontrado(s).";
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg, "elemento" => $myArray);
    echo JsonEncodePAcentos::converter($arr);
}
function elementoUpdate($mysqli, $elemento, $dados, $data, $hora, $re_sessao)
{
    $string = $elemento;

    $erro = "1";
    $contador = 0;

    $p = permissaoVerifica($mysqli, $dados['permissao'], $re_sessao);

    if ($p === 0) {
        $msg = $dados['msg0'];
    } else
    if (strlen($elemento) < 1) {
        $msg = $dados['msg1'];
    } else {

        if (substr($string, -1) === "|") {
            $string = substr($string, 0, -1);
        }
        $string = explode("|", $string);

        for ($i = 0; $i < count($string); $i++) {

            $dadosElemento =  elementoDados($mysqli, $string[$i]);

            $sa = $dadosElemento['status']; //STATUS ATUAL DO ELEMENTO
            $sn = $dados['status']; //NOVO STATUS DO ELEMENTO

            if (($sa === "1" and $sn === "3") or ($sa === "3" and $sn === "2") or ($sa === "1" and $sn === "5") or ($sa === "3" and $sn === "5")) {

                $id = $string[$i]; //ID DO ELEMENTO
                $sql = "update cep_elemento set status = '{$sn}' where id='{$id}'";

                if ($mysqli->query($sql)) {

                    historico($mysqli, $re_sessao, $data, $hora, $elemento, $dados['acao'], $dados['status']); //INSERE NOVA INFORMAÇÃO DE HISTÓRICO
                }
                $contador++;
            }
        }

        if ($contador === 0) {

            $msg = $dados['msg2'];
        } else
        if ($contador === 1) {
            $msg = $contador . " de " . count($string) . $dados['msg3'];
            $erro = "0";
        } else {
            $msg = $contador . " de " . count($string) . $dados['msg4'];
            $erro = "0";
        }
    }

    $mysqli->close();

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function concluiElemento($mysqli, $id, $re)
{
    $sql = "update elemento set status='2' where id='{$id}'";

    $p = permissaoVerifica($mysqli, '2', $re);

    $erro = "1";
    if ($p === 0) {
        $msg = "Você não tem essa permissão!";
    } else {
        if ($mysqli->query($sql)) {
            $erro = "0";
            $msg = "Concluído com sucesso!";
        } else {
            $msg = "Erro ao concluir!";
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function elementoDados($mysqli, $elemento)
{
    return $mysqli->query("select status from cep_elemento where id='{$elemento}'")->fetch_array(MYSQLI_ASSOC);
}
function historico($mysqli, $re, $data, $hora, $elemento, $acao, $status)
{
    $sql_insert = "insert into cep_historico (re, data, hora, elemento, status, acao)
    values ('{$re}', '{$data}', '{$hora}', '{$elemento}', '{$status}', '{$acao}')";

    $mysqli->query($sql_insert);
}
function listaCn($mysqli, $re)
{

    $regiao = regiao($mysqli, $re)['regiao'];
    $sql = "select id, nome as cn from cn where campo=1 and cn.regiao='{$regiao}'";

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
    $sql = "select id, nome as cn from cep_elemento_status";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function elemento($mysqli, $id)
{
    $sql = "select ele.id as id, usr.nome as nome, site.sigla as site, st.nome as site_tipo, cn.nome as cn, sest.descricao as estrutura, ele.estrutura_n as Ngabinete, se.descricao as elemento, ele.elemento_n as Nelemento, ele.observacao as obs, els.nome as status from cep_elemento ele inner join site site on site.id=ele.site inner join site_tipo st on st.id=site.tipo inner join cn cn on cn.id=site.cn inner join cep_site_elemento se on se.id=ele.elemento inner join cep_site_estrutura sest on sest.id=ele.estrutura inner join cep_elemento_status es on es.id=ele.status inner join usuario usr on usr.re=ele.re inner join cep_elemento_status els on els.id=ele.status where ele.id='{$id}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $result->close();

    echo JsonEncodePAcentos::converter($row);

    $mysqli->close();
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
