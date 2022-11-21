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


if ($acao === "verifica") {

    verifica($mysqli, $re);
} else
if ($acao === "qtdNota") {
    notaQtd($mysqli, $re);
} else
if ($acao === "listaNota") {

    notaLista($mysqli, $re);
} else
if ($acao === "listaTipo") {

    listaTipo($mysqli);
} else
if ($acao === "listaVeiculo") {

    listaVeiculo($mysqli, $re);
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
if ($acao === "cadastro") {

    $tipo = $txtTitulo['tipo'];
    $veiculo = $txtTitulo['veiculo'];
    $data = $txtTitulo['data'];
    $hora = $txtTitulo['hora'];
    $obs =  addslashes($txtTitulo['obs']);

    cadastro($mysqli, $re, $data, $hora, $tipo, $veiculo, $obs);
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
function cadastro($mysqli, $re, $data, $hora, $tipo, $veiculo, $obs)
{
    $erro = "1";

    $p = permissao($mysqli, "90", $re);

    if ($p === 0) {

        $msg = "Você não tem permissão para cadastrar sinistro.";
    } else
    if ($tipo === "0") {
        $msg = "Necessário selecionar o tipo do sinistro.";
    } else
    if ($veiculo === "0") {
        $msg = "Necessário selecionar o veículo envolvido.";
    } else
    if (!$data || ValidaData($data) === "0") {
        $msg = "Necessário informar a data do ocorrido.";
    } else
    if (!$hora || $hora === "") {
        $msg = "Necessário informar a hora do ocorrido.";
    } else {
        $erro = "0";
        $msg = "sinistro cadastrado com sucesso.";

        $sql = "insert into psf (tipo, veiculo, data, hora, re, status) values ('{$tipo}', '{$veiculo}', '{$data}','{$hora}', '{$re}', '1')";
        if ($mysqli->query($sql)) {

            $id = $mysqli->insert_id;

            $sql_vid = "insert into psf_vida (psf, texto, re, data, hora, status) values ('{$id}','{$obs}', '{$re}', '{$data}', '{$hora}', '1')";
            $mysqli->query($sql_vid);
        }
    }

    $arr = array("erro" => $erro, "msg" => $sql);
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
function listaTipo($mysqli)
{
    $sql = "select id, nome from psf_tipo order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaVeiculo($mysqli, $re)
{
    $p = permissao($mysqli, "57", $re);

    $sql = "select f.id as id, u.nome as nome, f.placa as placa from frota f inner join usuario u on u.frota=f.placa order by u.nome asc";

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
    $v = $mysqli->query("select id from psf WHERE status=1 and re='{$re}'")->num_rows;
    $sql = "select * from psf p WHERE p.re='{$re}' and p.status=1";

    $ativa = "nd";

    if ($v > 0) {

        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();

        $ativa = "sim";

        $arr = array(
            "ativa" => $ativa,
            "dados" => $row
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
