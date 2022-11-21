<?php

include_once "l_sessao.php";
include 'json_encode.php';


$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$acao = $txtTitulo['acao'];
$re = $_SESSION['re'];
$uf = $_SESSION['uf'];

if ($acao === "index") {
    index($mysqli, $re);
} else
if ($acao === "menu") {
    menu($mysqli, $re);
} else 
if ($acao ==="sub"){
    
    $sub = $txtTitulo['sub'];

    sub($mysqli, $re, $sub);
}
function menu($mysqli, $re)
{
    $sql = "select pa.id as id, pa.sub as sub, ps.descricao as descricao from permissao p inner join pagina pa on pa.id=p.pagina inner join pagina_sub ps on ps.sub=pa.sub where tipo=1 and colaborador='{$re}' group by pa.sub";

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
function sub($mysqli, $re, $sub)
{

    $sql = "select pa.link as link, pa.nome as nome, pa.sub as sub from permissao p inner join pagina pa on pa.id=p.pagina where tipo=1 and colaborador='{$re}' and sub='{$sub}' order by nome";

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
function SolicitacaoVerifica($mysqli, $re, $funcao)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
