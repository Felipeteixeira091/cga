<?php
include "l_sessao.php";
include 'json_encode.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

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
if ($acao === "qtdNota") {
    notaQtd($mysqli, $re);
} else
if ($acao === "listaNota") {

    notaLista($mysqli, $re);
} else
if ($acao === "notaTipo") {

    notaTipo($mysqli);
} else
if ($acao === "notaColaborador") {

    notaColaborador($mysqli, $re);
} else
if ($acao === "notaMotivo") {

    notaMotivo($mysqli);
} else
if ($acao === "SiteProcura") {

    $txt = $txtTitulo['txt'];

    SiteProcura($mysqli, $txt);
} else
if ($acao === "selecionaSite") {

    $site = $txtTitulo['site'];
    SelecionaSite($mysqli, $site);
} else 
if ($acao === "criaNota") {

    $site = $txtTitulo['site'];
    $tipo = $txtTitulo['tipo'];
    $colaborador = $txtTitulo['colaborador'];
    $motivo = $txtTitulo['motivo'];
    $os = $txtTitulo['os'];
    $valor = $txtTitulo['valor'];
    $dataNota = $txtTitulo['data'];
    $obs =  addslashes($txtTitulo['obs']);

    notaCria($mysqli, $re, $data, $hora, $site, $tipo, $colaborador, $motivo, $os, $valor, $dataNota, $obs);
    $mysqli->close();
} else
if ($acao === "siteLista") {
    SiteLista($mysqli, $re);
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
function notaAtiva($mysqli, $re)
{
    $sql_verifica = "select count(id) as cont from ext_nota where re='{$re}' and status='1'";
    $sql = "select id, re, data, hora, status from ext_nota WHERE re='{$re}' and status='1'";

    $result_v = $mysqli->query($sql_verifica);
    $row_v = $result_v->fetch_assoc();
    $ativa = "nd";

    if ($row_v['cont'] > 0) {

        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        $ativa = "sim";

        $arr = array(
            "ativa" => $ativa,
            "id" => $row['id'],
            "re" => $row['re'],
            "data" => $row['data'],
            "hora" => $row['hora'],
            "staus" => $row['status'],

        );
        $result->close();
    } else {
        $arr = array(
            "ativa" => $ativa
        );
    }

    $mysqli->close();

    echo JsonEncodePAcentos::converter($arr);
}
function notaQtd($mysqli, $re)
{
    $sql = "select n.id as id, n.data as data, n.valor as valor, c.nome as colaborador from ext_nota n inner join usuario c on c.re=n.colaborador where n.re='{$re}' and (n.status='1' or n.status='5')";
    $num = $mysqli->query($sql)->num_rows;

    $arr = array(
        "qtd" => $num
    );
    echo JsonEncodePAcentos::converter($arr);
}
function notaLista($mysqli, $re)
{
    $sql = "select n.id as id, n.data as data, n.valor as valor, c.nome as colaborador, s.sigla as site, ns.nome as status from ext_nota n inner join usuario c on c.re=n.colaborador inner join ext_nota_status ns on ns.id=n.status inner join site s on s.id=n.site where n.re='{$re}' and (n.status='1' or n.status='5')";
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
function notaCria($mysqli, $re, $data, $hora, $site, $tipo, $colaborador, $motivo, $os, $valor, $dataNota, $obs)
{
    $erro = "1";

    $p = permissao($mysqli, "58", $re);

    if ($p === 0) {

        $msg = "Você não tem permissão para cadastrar notas.";
    } else

    if ($site === "0" || $site === "" || !$site) {
        $msg = "Necessário selecionar o site relacionado ao custo gerado.";
    } else
    if ($tipo === "0") {
        $msg = "Necessário selecionar o tipo de custo.";
    } else
    if ($colaborador === "0") {
        $msg = "Necessário selecionar o colaborador.";
    } else
    if ($motivo === "0") {
        $msg = "Necessário selecionar o motivo do custo gerado.";
    } else 
    if ($motivo === "1" && $os === "") {
        $msg = "Necessário informar o número do <b>TA</b> relacionado ao custo gerado.";
    } else 
    if ($motivo === "2" && $os === "") {
        $msg = "Necessário informar o número do <b>TP</b> relacionado ao custo gerado.";
    } else 
    if ($motivo === "3" && $os === "") {
        $msg = "Necessário informar o número da <b>OS</b> relacionado ao custo gerado.";
    } else
    if ($motivo === "4" && $os === "") {
        $msg = "Necessário informar o número da <b>OS</b> relacionado ao custo gerado.";
    } else
    if (empty($valor) or $valor === 0) {
        $msg = "Necessário informar o valor da nota.";
    } else
    if (!$dataNota || ValidaData($dataNota) === "0") {
        $msg = "Necessário informar a data da nota.";
    } else {
        $erro = "0";
        $msg = "Nota cadastrada com sucesso.";

        // $valor = str_replace('.00', '', $valor);

        $sql = "insert into ext_nota(re, tipo, motivo, os, colaborador, site, data, hora, dataNota, valor, anexo, movimentacao, status, obs) values('{$re}', '{$tipo}', '{$motivo}','{$os}', '{$colaborador}', '{$site}', '{$data}', '{$hora}', '{$dataNota}', '{$valor}', '', '{$re}', '1', '{$obs}')";
        if ($mysqli->query($sql)) {

            $id = $mysqli->insert_id;

            if ($obs === "") {
                $obs = "NOTA CADASTRADA.";
            } else {
                $obs = $obs;
            }
            $sql_vid = "insert into ext_nota_vida (nota, data, hora, status, re, obs) values ('{$id}','{$data}', '{$hora}', '1', '{$re}', '{$obs}')";
            $mysqli->query($sql_vid);
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function SiteLista($mysqli, $re)
{
    $sql = "SELECT site.id as id, site.tipo as tipo, concat(site.sigla,'-',tipo.nome) as sigla, site.estado as s_estado FROM site site inner join site_tipo tipo on tipo.id=site.tipo inner join usuario usr on usr.re='{$re}' where site.estado=usr.estado order by tipo, sigla";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function notaTipo($mysqli)
{
    $sql = "select id, nome from ext_nota_tipo order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function notaColaborador($mysqli, $re)
{
    $p = permissao($mysqli, "57", $re);

    if ($p > 0) {

        $sql = "select u.re as re, concat(u.nome,' - ',cn.nome) as nome from usuario u inner join cn on cn.id=u.cn where ativo=2 order by cn.id asc, u.nome";
    } else {
        $sql = "select u.re as re, concat(u.nome,' - ',cn.nome) as nome from usuario u inner join cn on cn.id=u.cn where ativo=2 and (supervisor='{$re}' or re='{$re}') order by cn.id asc, u.nome";
    }

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function notaMotivo($mysqli)
{
    $sql = "select id, nome from ext_nota_motivo order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function UsuarioLista($mysqli, $re)
{
    $sql = "select r.re as re, r.nome as nome, c.nome as cn from usuario r inner join cn c on c.id=r.cn inner join usuario s on s.re='{$re}' where r.estado=s.estado and r.ativo=2 order by r.nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
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
function permissao($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
