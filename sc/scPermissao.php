<?php
include 'conf/conexao2.php';
include 'json_encode.php';

session_start();

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);


/** Include PHPExcel */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


$re = $_SESSION['re'];
date_default_timezone_set('America/Sao_Paulo');
$data = date('Y-m-d');

$acao = $txtTitulo['acao'];

if ($acao === "PermissaoListaUsuario") {

    PermissaoListaUsuario($mysqli);
} else 
if ($acao === "PermissaoListaTipo") {

    PermissaoListaTipo($mysqli);
} else
if ($acao === "PermissaoListaPagina") {

    PermissaoListaPagina($mysqli);
} else
if ($acao === "PermissaoListaFuncao") {

    PermissaoListaFuncao($mysqli);
} else 
if ($acao === "PermissaoLista") {
    $u = $txtTitulo['u'];

    PermissaoLista($mysqli, $u);
} else 
if ($acao === "PermissaoAdd") {

    $u = $txtTitulo['u'];
    $t = $txtTitulo['t'];
    $p = $txtTitulo['p'];
    $f = $txtTitulo['f'];

    PermissaoAdd($mysqli, $u, $t, $p, $f, $re, $data);
} else 
if ($acao === "PermissaoRemove") {

    $id = $txtTitulo['permissao'];

    PermissaoRemove($mysqli, $id, $re);
} else
if ($acao === "verificaPermissao") {

    $p = permissaoVerifica($mysqli, "1", $re);

    $arr = array("permissao" => $p);

    echo JsonEncodePAcentos::converter($arr);
}

function PermissaoLista($mysqli, $u)
{
    $sql = "SELECT pt.nome as tipo, pag.nome as pagina, pag.sub as subP,  fun.nome as funcao, fun.sub as subF, per.id as id FROM permissao per left join pagina pag on pag.id=per.pagina left join funcao fun on fun.id=per.funcao inner join permissao_tipo pt on pt.id=per.tipo where per.colaborador='{$u}' order by per.tipo, pag.nome, fun.nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function PermissaoListaUsuario($mysqli)
{
    $sql = "select u.re as re, u.nome as nome, cn.nome as cn from usuario u inner join cn on cn.id=u.cn where sistema=2 order by cn, nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function PermissaoListaTipo($mysqli)
{
    $sql = "select id, nome from permissao_tipo";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function PermissaoListaPagina($mysqli)
{
    $sql = "select id, nome, sub from pagina order by sub, nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function PermissaoListaFuncao($mysqli)
{
    $sql = "select id, nome, sub from funcao order by sub, nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function PermissaoAdd($mysqli, $usuario, $tipo, $pagina, $funcao, $re, $data)
{
    $p = permissaoVerifica($mysqli, "1", $re);

    $erro = "1";

    if ($p === 0) {

        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else
    if ($tipo === "0") {
        $msg = "<i class='icon-attention'></i> Selecione um tipo de permissão!";
    } else 
if ($tipo != "0" && $pagina === "0") {
        $msg = "<i class='icon-attention'></i> Selecione uma pagina!";
    } else
if ($tipo != "0" && $funcao === "0") {
        $msg = "<i class='icon-attention'></i> Selecione uma funcionalidade!";
    } else
    if ($tipo === "1" && paginaVerifica($mysqli, $pagina, $usuario) > 0) {
        $msg = "<i class='icon-attention'></i> Página já atribuída para o colaborador!";
    } else 
    if ($tipo === "2" && funcaoVerifica($mysqli, $funcao, $usuario) > 0) {

        $msg = "<i class='icon-attention'></i> Funcionalidade já atribuída para o colaborador!";
    } else {

        if ($tipo === "1") {
            $funcao = "0";
        } else {
            $pagina = "0";
        }
        $sql = "insert into permissao (colaborador, tipo, pagina, funcao, re, data) values ('{$usuario}','{$tipo}','{$pagina}','{$funcao}','{$re}','{$data}')";

        $mysqli->query($sql);

        $erro = "0";
        $msg = "<i class='icon-ok-1'></i> Permissão atribuída com sucesso.";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}

function paginaVerifica($mysqli, $pagina, $re)
{
    $num = $mysqli->query("select id from permissao where pagina='{$pagina}' and colaborador='{$re}'")->num_rows;
    return $num;
}
function funcaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
function PermissaoRemove($mysqli, $id, $re)
{

    $p = permissaoVerifica($mysqli, "1", $re);

    $erro = "1";

    if ($p === 0) {

        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else {
        $sql = "delete from permissao WHERE id='{$id}'";
        $mysqli->query($sql);
        $erro = "0";
        $msg = "<i class='icon-ok-1'></i> Permissão removida com sucesso.";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
