<?php

include_once "l_sessao.php";
include 'json_encode.php';


$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$acao = $txtTitulo['acao'];
$re = $_SESSION['re'];
$uf = $_SESSION['uf'];

if ($acao === "menucanva") {

    menuCanva($mysqli, $re);
} else
if ($acao === "index") {
    index($mysqli, $re);
} else
if ($acao === "menu") {

    $sistema = $txtTitulo['sistema'];
    menu($mysqli, $re, $sistema);
} else
if ($acao === "solicitacoes_pendente") {
    solicitacoes_pendentes($mysqli, $re, $uf);
}
function menuCanva($mysqli, $re)
{

    $sql_sub = "SELECT pag.sub as sub from permissao p inner join permissao_tipo pt on pt.id=1 inner join pagina pag on pag.id=p.pagina WHERE p.colaborador={$re} group by pag.sub ORDER by pag.sub";
    $sql_m = "SELECT pag.sub as pag_sub, concat(pag.sub,' ',pag.nome) as pag_nome, pag.link as pag_link from permissao p inner join pagina pag on pag.id=p.pagina WHERE p.tipo=1 and p.colaborador={$re}";

    $arrSub = array();
    if ($result = $mysqli->query($sql_sub)) {

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $arrSub[] = $row;
        }
    }
    $arrMenu = array();
    if ($result = $mysqli->query($sql_m)) {

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $arrMenu[] = $row;
        }
    }

    $arr = array("sub" => $arrSub, "menu" => $arrMenu);

    echo JsonEncodePAcentos::converter($arr);
}
function solicitacoes_pendentes($mysqli, $re, $uf)
{
    $qtd_material = 0;
    $qtd_sobressalente = 0;
    $sql = "select s.id as id, uf.sigla as UF, s.re as RE, u.nome as NOME, s.data as DATA from solicitacao s inner join usuario u on u.re=s.re inner join estado uf on uf.id=u.estado WHERE s.status='2' and uf.id='{$uf}' and s.sobressalente='0'";
    $sql1 = "select s.id as id, uf.sigla as UF, s.re as RE, u.nome as NOME, s.data as DATA from solicitacao s inner join usuario u on u.re=s.re inner join estado uf on uf.id=u.estado WHERE s.status='2' and uf.id='{$uf}' and s.sobressalente='1'";

    if (SolicitacaoVerifica($mysqli, $re, "1") > 0) {

        $qtd_material = $mysqli->query($sql)->num_rows;
    }

    if (SolicitacaoVerifica($mysqli, $re, "4") > 0) {

        $qtd_sobressalente = $mysqli->query($sql1)->num_rows;
    }

    $qtd = $qtd_sobressalente + $qtd_material;

    $arr = array("qtd" => $qtd);

    echo JsonEncodePAcentos::converter($arr);

    $mysqli->close();
}
function index($mysqli, $re)
{
    $sql = "select pag.id as id, nome as nome, link as link, sub as sub from pagina as pag INNER JOIN permissao per on per.pagina=pag.id where per.colaborador='{$re}' order by pag.id";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }

    $result->close();
    $mysqli->close();
}
function menu($mysqli, $re, $sistema)
{
    if ($sistema === "ADM") {
        $sistema = "GERAL";
    }
    $sql = "select pag.id as id, nome as nome, link as link, sub as sub from pagina as pag INNER JOIN permissao per on per.pagina=pag.id where per.colaborador='{$re}' and pag.sub='{$sistema}' order by pag.id";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
    }

    $usuario = $mysqli->query("select usr.re as re, usr.nome as nome from usuario usr WHERE usr.re='{$re}'")->fetch_array();

    $arr = array("menu" => $myArray, "usuario" => $usuario);

    echo JsonEncodePAcentos::converter($arr);

    $result->close();
    $mysqli->close();
}
function SolicitacaoVerifica($mysqli, $re, $funcao)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
