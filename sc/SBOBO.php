<?php

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include "l_sessao.php";
include_once "./json_encode.php";

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';
include_once "./frame/Email.php";
$format = 'Y-m-d H:i:s';
$re = $_SESSION["re"];

$acao = $txtTitulo['acao'];

$data = date('Y-m-d');
$hora = date('H:i', time());

$chave = md5($_SESSION['re'] . $data);

// Verifica se existe os dados da sessão de login
if (!isset($_SESSION["nome"]) || !isset($_SESSION["email"])) {
    header("Location: ../");
    exit;
}

$erro = "0";
$msg = "";

if ($acao === "listaSITE") {

    $cn = $txtTitulo['cn'];
    listaSITE($mysqli, $cn, $re);
} else
if ($acao === "listaGMG") {

    listaGMG($mysqli);
} else
if ($acao === "listaCN") {

    $gestao = regiao($mysqli, $re)['gestao'];

    listaCN($mysqli, $gestao);
} else
if ($acao === "listaFechaduraStatus") {
    listaFechaduraStatus($mysqli);
} else
if ($acao === "cadastroBO") {

    $obj = $txtTitulo['bo'];
    $obj['dh'] = DateTime::createFromFormat($format, date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
    $obj['re'] = $re;


    cadastroBO($mysqli, $obj);
} else
if ($acao === "editaForm") {

    $bo = $txtTitulo['bo'];
    $texto = $txtTitulo['texto'];
    $campo = $txtTitulo['campo'];

    editaForm($mysqli, $bo, $texto, $campo, $re);
} else 
if ($acao === "BOProcura") {

    $cn = $txtTitulo['cn'];
    $site = $txtTitulo['site'];
    $status = $txtTitulo['status'];
    $dataInicio = $txtTitulo['dataInicio'];
    $dataFim = $txtTitulo['dataFinal'];

    boProcura($mysqli, $cn, $site, $status, $dataInicio, $dataFim, $re);
} else
if ($acao === "Detalhe") {

    $id = $txtTitulo['id'];
    Detalhes($mysqli, $id);
} else
if ($acao === "listaSTATUS") {

    listaStatus($mysqli);
} else
if ($acao === "iniciaTratativaBO") {

    $id = $txtTitulo['bo_id'];

    boInicia($mysqli, $re, $id, $data, $hora);
} else
if ($acao === "confirmaInformacoesBO") {

    $id = $txtTitulo['bo_id'];
    boConfirmaInfo($mysqli, $re, $id, $data, $hora);
} else
if ($acao === "cancelaTratativaBO") {
    $id = $txtTitulo['bo_id'];
    $obs = $txtTitulo['obs'];

    boCancela($mysqli, $re, $id, $obs);
} else
if ($acao === "cancelaTratativaPermissao") {

    boCancelaPermissao($mysqli, $re);
} else
if ($acao === "statusUpdate") {
    $id =   $txtTitulo['bo_id'];
    $os = $txtTitulo['os'];
    $sinistro = $txtTitulo['sinistro'];
    $status = $txtTitulo['status'];
    boUpdate($mysqli, $re, $id, $os, $sinistro, $status);
}

function boProcura($mysqli, $cn, $site, $status, $dataInicio, $dataFim, $re)
{

    $p = permissaoVerifica($mysqli, "53", $re);

    $usuario = "";

    $where = "";

    if ($cn > 0) {
        $where .= " site.cn='{$cn}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($site > 0) {
        $where .= " bo.site='{$site}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($status > 0) {
        $where .= " bo.status='{$status}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($dataInicio != "") {
        $where .= " DATE(dh) >= '{$dataInicio}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($dataFim != "") {
        $where .= " DATE(dh) <= '{$dataFim}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    $sql = "select bo.id as id, bo.re as re, (select dh from sbo_bo_historico where bo=bo.id and status=bo.status limit 1) as dh, site.sigla as site, cn.nome as cn, bs.nome as status, bs.ico as ico, if(bo.os='0','ND',bo.os) as os, if(bo.numero_sinistro='','ND',bo.numero_sinistro) as sinistro from sbo_bo bo inner join usuario usr on usr.re=bo.re inner join site site on site.id=bo.site inner join cn cn on cn.id=site.cn inner join sbo_bo_status bs on bs.id=bo.status WHERE" . $where . " order by dh asc";
    //    $sql = "select bo.id as id, bo.re as re, bo.dh as dh, site.sigla as site, cn.nome as cn, bs.nome as status, bs.ico as ico, if(bo.numero_sinistro='','NÃO INFORMADO',bo.numero_sinistro) as sinistro from sbo_bo bo inner join usuario usr on usr.re=bo.re inner join site site on site.id=bo.site inner join cn cn on cn.id=site.cn inner join sbo_bo_status bs on bs.id=bo.status WHERE" . $where . " order by dh, sinistro asc";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function editaForm($mysqli, $bo, $texto, $campo, $re)
{

    $verifica = $mysqli->query("select id from sbo_bo where id='{$bo}' and re='{$re}'")->num_rows;

    $p = permissaoVerifica($mysqli, "91", $re);

    $erro = "1";
    $sql = "update sbo_bo set " . $campo . "='{$texto}' where id='{$bo}'";

    if ($verifica === 0 and $p === 0) {
        $msg = "Você não pode editar essa informação.";
    } else 
    if ($texto === "" or strlen($texto) < 5) {
        $msg = "Texto insuficiente.";
    } else {

        $erro = "0";
        $msg = $sql;
        "Edição realizada com sucesso.";
        $mysqli->query($sql);
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function calcula_indisponibilidade($data_1, $hora_1, $data_2, $hora_2)
{
    $diferenca = abs(strtotime($data_1 . ' ' . $hora_1) - strtotime($data_2 . ' ' . $hora_2));

    $horas = explode(".", $diferenca / 3600)[0];
    if ($horas < 10) {
        $horas = "0" . $horas;
    }
    $minutos = $diferenca / 60 % 60;
    if ($minutos < 10) {
        $minutos = "0" . $minutos;
    }
    return $horas . ":" . $minutos;
}
function listaSITE($mysqli, $cn, $re)
{

    $sql = "select cn.regiao as regiao from usuario u inner join cn on cn.id=u.cn where u.re='{$re}'";
    $result = $mysqli->query($sql)->fetch_array();
    $regiao = $result['regiao'];

    if ($cn === "v2") {

        $sql = "select site.id as id, site.sigla as sigla, cn.nome as cn, stip.nome as tipo from site site inner join cn cn on cn.id=site.cn inner join site_tipo stip on stip.id=site.tipo where site.tipo=2 and cn.regiao='{$regiao}' order by cn, sigla";
    } else {

        $sql = "select site.id as id, site.sigla as sigla, cn.nome as cn, stip.nome as tipo from site site inner join cn cn on cn.id=site.cn inner join site_tipo stip on stip.id=site.tipo where cn.id='{$cn}' order by sigla";
    }


    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function boConfirmaInfo($mysqli, $re, $id, $data, $hora)
{

    $sql = "select id, re from sbo_bo where id='{$id}'";
    $dados = $mysqli->query($sql)->fetch_array();


    $status = $mysqli->query("select id from sbo_bo where id='{$id}' and status='6'")->num_rows;
    $p = permissaoVerifica($mysqli, '92', $re);

    $erro = "1";
    if ($status === 0) {
        $msg = "Esse BO não pode ser alterado.";
    } else
    if ($dados['re'] != $re and $p === 0) {
        $msg = "Você não tem permissão para alterar esse BO.";
    } else {
        $sql = "update sbo_bo set status='1' where id='{$id}'";
        $mysqli->query($sql);

        bo_historico($mysqli, $id, date("Y-m-d H:i"), $re, '1');
        $mysqli->close();

        $erro = "0";
        $msg = "Informações confirmadas com sucesso.";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function boInicia($mysqli, $re, $id, $data, $hora)
{

    $p = permissaoVerifica($mysqli, '49', $re);

    if ($p === 0) {
        $erro = "1";
        $msg = "Você não tem permissão para a atividade!";
    } else {
        $sql = "update sbo_bo set status='2' where id='{$id}'";
        $mysqli->query($sql);

        bo_historico($mysqli, $id, date("Y-m-d H:i"), $re, '2');

        $mysqli->close();

        $erro = "0";
        $msg = "Tratativa iniciada!";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function boCancelaPermissao($mysqli, $re)
{
    $p = permissaoVerifica($mysqli, '52', $re);

    if ($p === 0) {

        $erro = "1";
    } else {
        $erro = "0";
    }

    $arr = array("erro" => $erro);
    echo JsonEncodePAcentos::converter($arr);
}
function boCancela($mysqli, $re, $id, $obs)
{
    $p = permissaoVerifica($mysqli, '52', $re);

    if ($p === 0) {

        $erro = "1";
        $msg = "Você não tem permissão para cancelar BO's";
    } else {

        if ($obs === "" || !$obs) {
            $erro = "1";
            $msg = "Necessário informar a observação de cancelamento.";
        } else {

            $sql = "update sbo_bo set obs_cancelamento='{$obs}', status='5' where id='{$id}'";
            if ($mysqli->query($sql)) {

                bo_historico($mysqli, $id, date("Y-m-d H:i"), $re, '5');

                $erro = "0";
                $msg = "BO canelado com sucesso.";
            } else {
                $erro = "1";
                $msg = "Houve um erro ao finalizar.";
            }
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function boUpdate($mysqli, $re, $id, $os, $sinistro, $status)
{

    $p = permissaoVerifica($mysqli, '51', $re);

    if ($p === 0) {

        $erro = "1";
        $msg = "Você não tem permissão para concluir tratativa.";
    } else {

        if ($os === "" || !$os) {
            $erro = "1";
            $msg = "Necessário informar o número da os.";
        } else {

            $sql = "update sbo_bo set os='{$os}', numero_sinistro='{$sinistro}', status='{$status}' where id='{$id}'";
            if ($mysqli->query($sql)) {

                bo_historico($mysqli, $id, date("Y-m-d H:i"), $re, $status);

                $erro = "0";
                $msg = "BO alterado com sucesso.";
            } else {
                $erro = "1";
                $msg = "Houve um erro ao finalizar.";
            }
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function bo_historico($mysqli, $id, $dh, $re, $status)
{
    $sql = "insert into sbo_bo_historico (bo, dh, re, status) values ('{$id}', '{$dh}', '{$re}', '{$status}')";
    $mysqli->query($sql);
}
function listaStatus($mysqli)
{
    $sql = "select id, nome from sbo_bo_status order by id";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaCN($mysqli, $gestao)
{
    $sql = "select cn.id as id, cn.nome as nome from sbo_bo bo inner join usuario u on u.re=bo.re inner join cn on cn.id=u.cn where u.gestao='{$gestao}' group by cn.nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaGMG($mysqli)
{
    $sql = "select id, nome from gmg where tipo='1'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaFechaduraStatus($mysqli)
{
    $sql = "select id, nome from sbo_fechadura_status";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}

function cadastroBO($mysqli, $bo)
{

    $erro = "1";
    if ($bo['site'] === "0" || !$bo['site']) {

        $msg = "Necessário selecionar o site.";
    } else
    if ($bo['ta'] === "0" || !$bo['ta']) {

        $msg = "Necessário informar o TA.";
    } else
    if (!$bo['dh_oc']) {

        $msg = "Data da ocorrência inválida.";
    } else
    if ($bo['os'] === "0" || !$bo['os']) {

        $msg = "Necessário informar a OS.";
    } else
    if (!$bo['inicio'] || !$bo['final']) {

        $msg = "O período de indisponibilidade é inválido.";
    } else
    if ($bo['inicio'] >= $bo['final']) {
        $msg = "O início do período não pode ser maior ou igual ao final.(" . str_replace("T", " ", $bo['final']) . ")";
    } else
    if ($bo['municipio'] <= 0 || !$bo['municipio']) {

        $msg = "A quantidade de municípios afetados deve ser informada.";
    } else
    if ($bo['elemento'] <= 0 || !$bo['elemento']) {

        $msg = "A quantidade de elemento afetados deve ser informada.";
    } else if ($bo['f_bluetooth'] === "ND") {

        $msg = "Necessário informar dados da fechadura bluetooth.";
    } else if ($bo['f_bluetooth'] === "1" and $bo['f_bluetooth_status'] === "0") {

        $msg = "Necessário informar a situação da fechadura bluetooth.";
    } else if ($bo['modulo_box'] === "ND") {

        $msg = "Necessário informar a situação do modulo box.";
    } else if ($bo['bateria'] === "ND") {

        $msg = "Necessário informar a situação da bateria.";
    } else if ($bo['furtado'] === "" or !$bo['furtado']) {

        $msg = "Os itens furtados devem ser informados.";
    } else 
        if ($bo['vandalizado'] === "" or !$bo['vandalizado']) {
        $msg = "Os itens Vandalizados devem ser informados.";
    } else
        if ($bo['sobra'] === "" or !$bo['sobra']) {
        $msg = "As sobras de itens vandalizados ou furtados devem ser informados.";
    } else
        if ($bo['relato'] === "" or !$bo['relato']) {
        $msg = "As observações da ocorrência deve ser preenchido.";
    } else {

        $txt_furtado = addslashes($bo['furtado']);
        $txt_vandalizado = addslashes($bo['vandalizado']);
        $txt_sobra = addslashes($bo['sobra']);
        $txt_relato = addslashes($bo['relato']);

        $sql = "insert into sbo_bo (re, dh, dh_ocorrido, dh_indisp_inicio, dh_indisp_fim, tempo_indisp, indisp_municipio, indisp_elemento, f_bluetooth, f_bluetooth_status, modulo_box, bateria, site, ta, os, relato, furtado, vandalizado, sobra, numero_sinistro, status) values ('" . $bo['re'] . "', '" . date("Y-m-d H:i") . "', '" . $bo['dh_oc'] . "', '" . $bo['inicio'] . "', '" . $bo['final'] . "', TIMEDIFF('" . str_replace("T", " ", $bo['final']) . "', '" . str_replace("T", " ", $bo['inicio']) . "'), '" . $bo['municipio'] . "', '" . $bo['elemento'] . "', '" . $bo['f_bluetooth'] . "', '" . $bo['f_bluetooth_status'] . "', '" . $bo['modulo_box'] . "', '" . $bo['bateria'] . "', '" . $bo['site'] . "', '" . $bo['ta'] . "', '" . $bo['os'] . "', '" . $txt_relato . "', '" . $txt_furtado . "', '" . $txt_vandalizado . "', '" . $txt_sobra . "', '" . $bo['sinistro'] . "', 6)";

        $mysqli->query($sql);

        $id = $mysqli->insert_id;

        bo_historico($mysqli, $id, date("Y-m-d H:i"), $bo['re'], '6');

        $erro = "0";
        $msg = "Informações cadastradas com sucesso.";

        $dados_bo = $mysqli->query("select bo.id as bo_id, u.nome as nome, u.email as email, bo.dh_ocorrido as dhOc, bo.dh_indisp_inicio as indisp_inicio, bo.dh_indisp_fim as dh_indisp_fim, bo.tempo_indisp as indsp, bo.indisp_municipio as indisp_municipio, bo.indisp_elemento as indisp_elemento, bo.dh as dh, s.sigla as site, cn.nome as cn, if(bo.f_bluetooth=2,'NÃO','SIM') as fb, sfs.nome as fbs, if(bo.modulo_box=2,'NÃO','SIM') as btresinada, if(bo.bateria=2,'NÃO','SIM') as bateria, bo.ta as ta, bo.os as os, bo.relato as relato, bo.furtado as furtado, bo.vandalizado as vandalizado, bo.sobra as sobra, bo.numero_bo as bo, bo.numero_sinistro as sinistro from sbo_bo bo inner join usuario u on u.re=bo.re inner join site s on s.id=bo.site inner join cn on cn.id=s.cn inner join sbo_fechadura_status sfs on sfs.id=bo.f_bluetooth_status where bo.id='{$id}'")->fetch_array();
        $dados_email = $mysqli->query("select bo.id as bo_id, u.nome as nome, u.email as email, uc.nome as coordenadorNome, uc.email coordenadorEmail, s.sigla as site, cn.nome as cn from sbo_bo bo inner join usuario u on u.re=bo.re inner join site s on s.id=bo.site inner join cn on cn.id=s.cn inner join usuario uc on uc.re=u.supervisor where bo.id='{$id}'")->fetch_array();

        $sqlEmail = "select email, nome, colaborador, tipo from email_endereco where finalidade='sbo_cadastro'";
        $assunto = "BO Cadastrado ID: " . $dados_email['bo_id'] . " - SITE " . $dados_email['site'] . "|" . $dados_email['cn'];

        enviar($mysqli, $sqlEmail, $dados_email, $assunto, bodyHtml_SBO_Cadastro($dados_bo));
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function validateDate($date, $format)
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
function paVerifica($mysqli, $pa)
{

    $num = $mysqli->query("select numero from pa where numero='{$pa}'")->num_rows;
    return $num;
}
function ValidaData($dat)
{
    $data = explode("-", "$dat");
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
function validaHora($horas)
{
    $h = intval(substr($horas, 0, 2));
    $m = intval(substr($horas, 3, 2));

    if ($h > 23 or $m > 59) {
        return "0";
    } else {
        return "1";
    }
}

function Detalhes($mysqli, $id)
{
    $sql = "select bo.id as id, bo.re as re, usr.nome as nome, coo.nome as nome_c, coo.re as re_c, bo.dh as dh, sbh.dh as dh_registro, bo.dh_ocorrido as dh_ocorrido, bo.dh_indisp_inicio as indisp_inicio, bo.dh_indisp_fim as indisp_final, bo.tempo_indisp as indisp, bo.indisp_elemento as indisp_elemento, bo.indisp_municipio as indisp_municipio, if(bo.f_bluetooth=1,'SIM','NÃO') as f_bluetooth, fs.nome as f_bluetooth_status, if(bo.modulo_box=1,'SIM','NÃO') as modulo_box, if(bo.bateria=1,'SIM','NÃO') as bateria, site.sigla as site, site.descricao as s_descricao, ifnull(cid.nome,'NÃO INFORMADA') as s_cidade, ifnull(bai.nome,'NÃO INFORMADO') as s_bairro, site.cep as s_cep, site.endereco as s_endereco, cn.nome as cn, bo.relato as relato, bo.furtado as furtado, bo.vandalizado as vandalizado, bo.sobra as sobra, bs.nome as status, (select dh from sbo_bo_historico where bo=bo.id and status=bo.status limit 1) as dh_status, bs.id as status_id, bs.ico as ico, bo.anexo as anexo, bo.numero_bo as numero_bo, bo.numero_sinistro as numero_sinistro, bo.ta as ta, bo.os as os, bo.obs_cancelamento as text_cancelamento from sbo_bo bo inner join usuario usr on usr.re=bo.re inner join usuario coo on coo.re=usr.supervisor inner join site site on site.id=bo.site inner join cn cn on cn.id=site.cn left join cidade cid on cid.id=site.cidade left join bairro bai on bai.id=site.bairro left join sbo_fechadura_status fs on fs.id=bo.f_bluetooth_status inner join sbo_bo_status bs on bs.id=bo.status left join sbo_bo_historico sbh on (sbh.bo=bo.id and sbh.status=6) where bo.id='{$id}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $hs = historico($mysqli, $id);

    $arr = array("dados" => $row, "hs" => $hs);

    $result->close();

    echo JsonEncodePAcentos::converter($arr);

    $mysqli->close();
}
function historico($mysqli, $bo)
{
    $sql_historico = "select resp.nome as nome, resp.re as re, sbs.nome as status, sbh.dh as dh, sbs.ico as ico from sbo_bo_historico sbh inner join sbo_bo_status sbs on sbs.id=sbh.status inner join usuario resp on resp.re=sbh.re where sbh.bo='{$bo}' order by sbs.ordem";

    $hs = "<table class='table table-borderless'>";
    $hs .= "<thead>";
    $hs .= "<tr>";
    $hs .= "<th scope='col'>#</th>";
    $hs .= "<th scope='col'>STATUS</th>";
    $hs .= "<th scope='col'>DATA/HORA</th>";
    $hs .= "<th scope='col'>RESP.</th>";
    $hs .= "</tr>";
    $hs .= "</thead>";
    $hs .= "<tbody>";

    $result3 = $mysqli->query($sql_historico);

    while ($row3 = $result3->fetch_array(MYSQLI_ASSOC)) {

        $n = explode(' ', $row3['nome']);

        $hs .= "<tr>";
        $hs .= "<td>" . "<i class='" . $row3['ico'] . "'></i>" . "</td>";
        $hs .= "<td>" . $row3['status'] . "</td>";
        $hs .= "<td>" . $row3['dh'] . "</td>";
        $hs .= "<td>" . $n[0] . " " . $n[1] . "</td>";
        $hs .= "</tr>";
    }
    $hs .= "</tbody>";
    $hs .= "</table>";

    return $hs;
}

function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
