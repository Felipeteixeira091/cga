<?php

include "l_sessao.php";
include 'json_encode.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';
include_once "./VFBEMAIL.php";

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());

$acao = $txtTitulo['acao'];


if ($acao === "vfbStatus") {
    vfbStatus($mysqli);
} else
if ($acao === "SiteProcura") {

    $txt = $txtTitulo['txt'];

    SiteProcura($mysqli, $txt);
} else
if ($acao === "vfbColaborador") {

    vfbColaborador($mysqli);
} else 
if ($acao === "criavfb") {

    $site = $txtTitulo['site'];
    $os = $txtTitulo['os'];
    $valor = $txtTitulo['valor'];
    $colaborador = $txtTitulo['colaborador'];
    $moTipo = $txtTitulo['moTipo'];
    $mo = $txtTitulo['mo'];
    $obs =  addslashes($txtTitulo['obs']);

    vfbCria($mysqli, $re, $data, $hora, $site, $os, $valor, $colaborador, $moTipo, $mo, $obs);
    $mysqli->close();
} else
if ($acao === "vfbDetalhe") {

    $vfb = $txtTitulo['vfb'];

    vfbDetalhe($mysqli, $vfb);
} else
if ($acao === "vfbExec") {

    $mo = $txtTitulo['mo'];
    vfbExecutante($mysqli, $mo);
} else
if ($acao === "notaEnviaLista") {

    notaEnviaLista($mysqli);
} else
if ($acao === "notaDestino") {
    notaDestino($mysqli, $re);
} else 
if ($acao === "notaDestinoRemove") {

    $id = $txtTitulo['id'];

    notaDestinoRemove($mysqli, $id);
} else
if ($acao === "notaDestinoAdd") {

    $nome = $txtTitulo['nome'];
    $email = $txtTitulo['email'];
    $tipo = $txtTitulo['tipo'];

    notaDestinatarioAdd($mysqli, $nome, $email, $tipo, $re);
} else
if ($acao === "vfbProcura") {

    $txt = $txtTitulo['txt'];
    $status = $txtTitulo['status'];
    $data1 = $txtTitulo['data1'];
    $data2 = $txtTitulo['data2'];
    $acesso = "";

    vfbProcura($mysqli, $txt, $data1, $data2, $status);
} else
if ($acao === "vfbUpdate") {

    $vfb = $txtTitulo['vfb'];
    $status = $txtTitulo['status'];
    $obs = addslashes($txtTitulo['obs']);

    $erro = "1";

    //Validar notas
    $p = permissao($mysqli, "81", $re);
    if ($p === 0) {
        $msg = "Você não tem permissão para validar vistorias.";
    } else if (strlen($obs) < 4) {
        $msg = "O campo de observações deve ser preenchido.";
    } else {

        $update = update_vfb($mysqli, $re, $vfb, $status, $obs, $data, $hora);

        if ($update === "1") {

            $erro = "0";
            vfb_vida($mysqli, $vfb, $data, $hora, $status, $re);
            $msg = "Vistoria atualizada com sucesso.";

            if ($status === "3" || $status === "4") {

                $html = bodyHtml($mysqli, $vfb);
                enviar($mysqli, $html, $vfb);
            }
        } else {
            $msg = "Erro ao atualizar vistoria.";
        }
    }

    $retorno = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($retorno);
}

function SiteProcura($mysqli, $txt)
{
    $txt = strtoupper($txt);

    $sql = "SELECT s.id as id, s.sigla as sigla, s.descricao as descricao, st.nome as tipo, cn.nome as cn, uf.sigla as uf FROM site s inner join site_tipo st on st.id=s.tipo inner join cn cn on cn.id=s.cn inner join uf on uf.id=s.estado WHERE s.sigla like '%" . $txt . "%'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function SelecionaSite($mysqli, $site)
{
    $sql = "SELECT s.id as id, s.sigla as sigla, st.nome as tipo FROM site s inner join site_tipo st on st.id=s.tipo WHERE s.id='{$site}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $result->close();

    echo JsonEncodePAcentos::converter($row);

    $mysqli->close();
}
function vfbColaborador($mysqli)
{
    $sql = "select u.nome as nome, u.re as re from permissao p inner join usuario u on u.re=p.colaborador WHERE funcao=81 order by u.nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function vfbExecutante($mysqli, $mo)
{

    if ($mo === "1") {
        $sql = "select u.nome as nome, u.re as re from usuario u WHERE u.cargo!=43 order by u.nome";
    } else {
        $sql = "select u.nome as nome, u.re as re from usuario u WHERE u.cargo=43 order by u.nome";
    }

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function vfbCria($mysqli, $re, $data, $hora, $site, $os, $valor, $colaborador, $moTipo, $mo, $obs)
{
    $erro = "1";

    $p = permissao($mysqli, "80", $re);

    if ($p === 0) {

        $msg = "Você não tem permissão para criar solicitações.";
    } else
    if ($site === "0" || $site === "" || !$site) {
        $msg = "Necessário selecionar o site a ser vistoriado.";
    } else
        if ($os === "0" || $os === "") {
        $msg = "Necessário informar a os do prisma.";
    } else
    if (empty($valor) or $valor === 0) {
        $msg = "Necessário informar o valor da obra.";
    } else
    if (strlen($os) < 4) {
        $msg = "A OS informada é inválida.";
    } else
     if ($colaborador === "0") {
        $msg = "Necessário selecionar o colaborador.";
    } else
    if ($moTipo === "0") {
        $msg = "Necessário selecionar o tipo de mão de obra.";
    } else
     if ($mo === "0") {
        $msg = "Necessário selecionar o executante da obra.";
    } else
    if ($obs === "" || strlen($obs) < 15) {
        $msg = "A orientação da vistoria é inválida ou insuficiente.";
    } else {
        $erro = "0";
        $msg = "Solicitação cadastrada com sucesso.";

        $sql = "insert into vfb_vistoria (data, hora, solicitante, os, valor, site, responsavel, moTipo, mo, solicitacao, status) values ('{$data}', '{$hora}', '{$re}','{$os}', '{$valor}', '{$site}', '{$colaborador}', '{$moTipo}','{$mo}', '{$obs}', '1')";
        if ($mysqli->query($sql)) {

            $id = $mysqli->insert_id;
            $sql_vid = "insert into vfb_vida (vistoria, data, hora, status, movimento, re) values ('{$id}','{$data}', '{$hora}', '1', 'Solicitação iniciada.', '{$re}')";
            $mysqli->query($sql_vid);
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}

function permissao($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
function update_vfb($mysqli, $re, $vfb, $status, $obs, $data, $hora)
{
    $sql = "update vfb_vistoria set status='{$status}', conclusao='{$obs}' where id='{$vfb}'";
    $sql_vid = "insert into vfb_anexo (vistoria, descricao, data, hora) values ('{$vfb}','{$obs}','{$data}', '{$hora}')";

    if ($mysqli->query($sql)) {
        if ($mysqli->query($sql_vid)) {
            return "1";
        } else {
            return "0";
        }
    } else {
        return "0";
    }
}
function vfb_vida($mysqli, $vfb, $data, $hora, $status, $re)
{
    $obs = "";
    if ($status === "3") {
        $obs = "Vistoria concluída e aprovada com sucesso.";
    } else if ($status === "4") {
        $obs = "Vistoria reprovada com sucesso.";
    } else if ($status === "5") {
        $obs = "Necessário correção da vistoria.";
    }

    $sql_vid = "insert into vfb_vida (vistoria, data, hora, status, movimento, re) values ('{$vfb}','{$data}', '{$hora}', '{$status}', '{$obs}', '{$re}')";
    $mysqli->query($sql_vid);
}
function vfbStatus($mysqli)
{
    $sql = "select id, nome from vfb_status order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function vfbDetalhe($mysqli, $v)
{
    $sql = "select vv.id as id, concat(DATEDIFF(CURDATE(), vv.data),' d') dias, vv.data as data, vv.hora as hora, vv.os as os, vv.valor as valor, s.sigla as site, cn.nome as cn, vv.status as status, vs.nome as statusTxt, vs.ico as ico, u.re as re, u.nome as nome, vv.solicitacao as solicitacao, vv.conclusao as conclusao, if(vv.moTipo='1','ICOMON','FORNECEDOR') as mo, me.re meRe, me.nome meNome, ifnull(seg.nome,'NÃO DEFINIDO') segmento from vfb_vistoria vv inner join site s on s.id=vv.site inner join usuario u on u.re=vv.responsavel inner join cn on cn.id=s.cn inner join vfb_status vs on vs.id=vv.status left join usuario me on me.re=vv.mo left join vfb_checklist vc on vc.vfb=vv.id left join vfb_segmento seg on seg.id=vc.seg where vv.id='{$v}'";
    $sql_historico = "select data, hora, movimento from vfb_vida vv WHERE vistoria='{$v}' order by data, hora";
    $sql_anexo = "select data, hora, codigo, descricao from vfb_anexo WHERE vistoria='{$v}' order by data, hora";
    $sqlchecklist = "select * from vfb_checklist WHERE vfb='{$v}'";

    $res = $mysqli->query($sql);
    $detalhe = $res->fetch_assoc();

    $result3 = $mysqli->query($sql_historico);
    $res1 = $mysqli->query($sqlchecklist);
    $checklist = $res1->fetch_assoc();

    if (!$checklist || $checklist === null) {
        $checklist = "nd";
    } else {
        $checklist = $checklist;
    }

    $hs = "<table class='table table-sm table-striped w-auto'>";
    $hs .= "<thead class='thead-dark'>";
    $hs .= "<tr>";
    $hs .= "<th scope='col'>DATA/HORA</th>";
    $hs .= "<th scope='col'>OBS</th>";
    $hs .= "</thead>";
    $hs .= "</tr>";
    while ($row3 = $result3->fetch_array(MYSQLI_ASSOC)) {

        $hs .= "<tr class='small'>";
        $hs .= "<td>" . $row3['data'] . " - " . $row3['hora'] . "</td>";
        $hs .= "<td>" . $row3['movimento'] . "</td>";
        $hs .= "</tr>";
    }

    $anexo = array();
    if ($result = $mysqli->query($sql_anexo)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $anexo[] = $row;
        }
    }

    if ($hs == "") {
        $historico = "Solicitação sem histórico";
    } else {
        $historico = $hs;
    }

    $arr = array(
        "detalhe" => $detalhe,
        "historico" => $historico,
        "anexo" => $anexo,
        "checklist" => $checklist,
    );

    $mysqli->close();

    echo JsonEncodePAcentos::converter($arr);
}
function vfbProcura($mysqli, $txt, $data1, $data2, $status)
{

    $txt = strtoupper($txt);
    $where = "";

    if ($data1 != "") {
        $where .= " vv.data >='{$data1}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($data2 != "") {
        $where .= " vv.data <='{$data2}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($txt != "") {
        $where .= " u.nome='{$txt}' or u.re like '%" . $txt . "%' or cn.nome like '%" . $txt . "%' or s.sigla like '%" . $txt . "%'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($status > 0) {
        $where .= " vv.status='{$status}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }
    $sql = "select vv.id as id, DATEDIFF(CURDATE(), vv.data) dias, vv.data as data, vv.hora as hora, vv.os as os, s.sigla as site, cn.nome as cn, vv.status as status, vs.nome as statusTxt, vs.ico as ico, u.re as re, u.nome as nome from vfb_vistoria vv inner join site s on s.id=vv.site inner join usuario u on u.re=vv.responsavel inner join cn on cn.id=s.cn inner join vfb_status vs on vs.id=vv.status where" . $where . "";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
