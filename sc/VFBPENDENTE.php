<?php
include "l_sessao.php";
include 'json_encode.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';
include_once "./VFBEMAIL.php";

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);


$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());

$chave = md5($_SESSION['re'] . $data);

if (verificaChave($mysqli, $chave) < 1) {
    header("Location: logOut");
}

$acao = $txtTitulo['acao'];

if ($acao === "notaVerifica") {

    notaAtiva($mysqli, $re);
} else
if ($acao === "qtdVfb") {
    vfbQtd($mysqli, $re);
} else
if ($acao === "listaNota") {

    vfbLista($mysqli, $re);
} else
if ($acao === "vfbAnexo") {

    $vfb = $txtTitulo['vfb'];
    vfbAnexo($mysqli, $vfb);
} else
if ($acao === "deletaImg") {

    $img = $txtTitulo['img'];
    deletaImg($mysqli, $img);
} else
if ($acao === "vfbCheckList") {

    $vfb = $txtTitulo['id'];
    $seg = $txtTitulo['seg'];
    $perg1 = $txtTitulo['perg1'];
    $perg2 = $txtTitulo['perg2'];
    $perg3 = $txtTitulo['perg3'];
    $perg4 = $txtTitulo['perg4'];
    $perg5 = $txtTitulo['perg5'];
    $perg6 = $txtTitulo['perg6'];
    $perg7 = $txtTitulo['perg7'];

    vfbCheckList($mysqli, $re, $data, $hora, $vfb, $seg, $perg1, $perg2, $perg3, $perg4, $perg5, $perg6, $perg7);
} else
if ($acao === "conclui") {

    $id = $txtTitulo['id'];
    $status = $txtTitulo['status'];
    $obs = $txtTitulo['obs'];

    conclui($mysqli, $re, $data, $hora, $id, $status, $obs);
} else
if ($acao === "vfbObs") {

    $vfb = $txtTitulo['vfb'];
    $obs = $txtTitulo['obs'];
    vfbObs($mysqli, $re, $data, $hora, $vfb, $obs);
}
function vfbCheckList($mysqli, $re, $data, $hora, $vfb, $seg, $perg1, $perg2, $perg3, $perg4, $perg5, $perg6, $perg7)
{
    $v = $mysqli->query("select id from vfb_checklist where vfb='{$vfb}'")->num_rows;

    $erro = "1";
    $msg = "Necessário selecionar o segmento da obra.";
    if (!$seg) {
        $msg = "Necessário selecionar o segmento da obra.";
    } else
    if (!$perg1 || !$perg2 || !$perg3 || !$perg4 || !$perg5 || !$perg6) {

        $msg = "Necessário responder todas as questões.";
    } else
    if (!$perg7) {
        $msg = "Necessário selecionar a nota de avaliação da obra.";
    } else {

        if ($v === 0) {
            $sql = "insert into vfb_checklist (vfb, seg, perg1, perg2, perg3, perg4, perg5, perg6, perg7, data, hora) 
            values ('{$vfb}', '{$seg}', '{$perg1}', '{$perg2}', '{$perg3}', '{$perg4}', '{$perg5}', '{$perg6}', '{$perg7}', '{$data}', '{$hora}')";
        } else {
            $sql = "update vfb_checklist set seg='{$seg}', perg1='{$perg1}', perg2='{$perg2}', perg3='{$perg3}', perg4='{$perg4}', 
            perg5='{$perg5}', perg6='{$perg6}', perg7='{$perg7}' where vfb='{$vfb}'";
        }

        if ($mysqli->query($sql)) {

            $erro = "0";
            $msg = "Checklist enviado com sucesso.";
        }
    }
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function vfbObs($mysqli, $re, $data, $hora, $vfb, $obs)
{
    $erro = "1";

    if (strlen($obs) < 3 || $obs === "") {
        $msg = "Observação inválida." . $obs;
    } else {

        $erro = "0";
        $msg = "Observação inserida com sucesso.";

        $sql_vid = "insert into vfb_anexo (vistoria, descricao, data, hora) values ('{$vfb}','{$obs}','{$data}', '{$hora}')";
        $mysqli->query($sql_vid);
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function conclui($mysqli, $re, $data, $hora, $id, $status, $obs)
{
    $erro = "1";

    $p = permissao($mysqli, "81", $re);

    $anexo = $mysqli->query("select id from vfb_anexo where tipo='1' and vistoria='{$id}'")->num_rows;
    $check = $mysqli->query("select id from vfb_checklist where vfb='{$id}'")->num_rows;

    if ($p === 0) {

        $msg = "Você não tem permissão concluir solicitações.";
    } else
    if ($anexo === 0) {
        $msg = "Nenhum anexo foi incluso à solicitação.";
    } else
    if ($check === 0) {
        $msg = "O checklist não foi preenchido.";
    } else {
        $sql = "update vfb_vistoria set status='{$status}', conclusao='{$obs}' where id='{$id}'";

        $erro = "0";
        $msg = "Solicitação concluída com sucesso.";

        if ($mysqli->query($sql)) {

            if ($status === "3" || $status === "4") {

                $html = bodyHtml($mysqli, $id);
                enviar($mysqli, $html, $id);
            }

            $sql_vid = "insert into vfb_vida (vistoria, data, hora, status, movimento, re) values ('{$id}','{$data}', '{$hora}', '{$status}', 'Solicitação enviada.', '{$re}')";
            $mysqli->query($sql_vid);
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function vfbQtd($mysqli, $re)
{
    $sql = "select id from vfb_vistoria WHERE responsavel='{$re}' and (status='1' or status='5')";
    $num = $mysqli->query($sql)->num_rows;

    $arr = array(
        "qtd" => $num
    );
    echo JsonEncodePAcentos::converter($arr);
}
function vfbLista($mysqli, $re)
{
    $sql = "select vfb.id as id, vfb.os as os, DATEDIFF(CURDATE(), vfb.data) as dias, vfb.data as data, vfb.hora as hora, s.sigla as site, vs.nome as status from vfb_vistoria vfb inner join site s on s.id=vfb.site inner join vfb_status as vs on vs.id=vfb.status WHERE responsavel='{$re}' and (vfb.status='1' or vfb.status='4')";
    $num = $mysqli->query($sql)->num_rows;
    $notas = "nd";
    if ($num > 0) {
        $myArray = array();
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $myArray[] = $row;
            }
            $notas = $myArray;
        }
    }
    $arr = array(
        "qtd" => $num,
        "notas" => $notas
    );
    echo JsonEncodePAcentos::converter($arr);
}
function vfbAnexo($mysqli, $v)
{
    $sqlD = "select vv.id as id, vv.data as data, vv.hora as hora, vv.os as os, vv.valor as valor, s.sigla as site, cn.nome as cn, vv.status as status, vs.nome as statusTxt, vs.ico as ico, u.re as re, u.nome as nome, vv.solicitacao as solicitacao, vv.conclusao as conclusao, if(mo=1,'ICOMON','FORNECEDOR') as mo, me.re meRe, me.nome meNome from vfb_vistoria vv inner join site s on s.id=vv.site inner join usuario u on u.re=vv.responsavel inner join cn on cn.id=s.cn inner join vfb_status vs on vs.id=vv.status left join usuario me on me.re=vv.mo where vv.id='{$v}'";

    $res = $mysqli->query($sqlD);
    $detalhe = $res->fetch_assoc();

    $sqlchecklist = "select * from vfb_checklist WHERE vfb='{$v}'";

    $res = $mysqli->query($sqlchecklist);
    $checklist = $res->fetch_assoc();

    $sql = "select id, data, hora, codigo, descricao from vfb_anexo WHERE vistoria='{$v}' order by data, hora";


    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
    }


    // $hs = "<table class='table table-sm table-striped w-auto'>";
    // $hs .= "<thead class='thead-dark'>";
    //  $hs .= "<tr>";
    //  $hs .= "<th scope='col'>DATA/HORA</th>";
    //  $hs .= "<th scope='col'>TIPO</th>";
    //  $hs .= "<th scope='col'>DESCRIÇÃO</th>";
    //   $hs .= "<th scope='col'>APAGAR</th>";
    //   $hs .= "</thead>";
    //   $hs .= "</tr>";
    //   while ($row3 = $result3->fetch_array(MYSQLI_ASSOC)) {

    //        if ($row3['codigo'] === "") {
    //            $bt = "<button type='button' disabled class='btn btn-sm btn-light border text-muted'><i class='icon-comment'></i> TXT</button>";
    //        } else {
    //           $bt = "<button type='button' class='btn btn-sm btn-light border text-muted'><i class='icon-attach-4'></i> " . substr($row3['codigo'], -3) . "</button>";
    //        }
    //
    //        $hs .= "<tr class='small'>";
    //        $hs .= "<td class='align-middle'>" . $row3['data'] . " " . $row3['hora'] . "</td>";
    //        $hs .= "<td class='align-middle'><a href='vfb_anexo/" . $row3['codigo'] . "' target='_blank'>" . $bt . "</a></td>";
    //       $hs .= "<td class='align-middle'>" . $row3['descricao'] . "</td>";
    //        $hs .= "<td class='align-middle'><button type='button' value='" . $row3['id'] . "' class='deletaImg btn btn-sm btn-light border text-muted'><i class='icon-attach-4 text-danger'></i> Apagar</button></td>";
    //        $hs .= "</tr>";
    //    }

    $arr = array(
        "anexo" => $myArray,
        "detalhe" => $detalhe,
        "checklist" => $checklist
    );

    $mysqli->close();

    echo JsonEncodePAcentos::converter($arr);
}
function deletaImg($mysqli, $id)
{
    $sql = "delete from vfb_anexo WHERE id='{$id}'";
    $mysqli->query($sql);

    $arr = array("erro" => "1", "msg" => "Item removido com sucesso.");
    echo JsonEncodePAcentos::converter($arr);
}
function permissao($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
