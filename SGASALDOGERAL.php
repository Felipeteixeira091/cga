<?php
include_once "./l_sessao.php";

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

include_once "./GASEMAIL.php";


$re = $_SESSION["re"];
$uf_sessao = $_SESSION["uf"];
$acao = $txtTitulo['acao'];

$data = date('Y-m-d');
$hora = date('H:i', time());

$chave = md5($_SESSION['re'] . $data);

if (verificaChave($mysqli, $chave) < 1) {
    header("Location: logOut");
}

$erro = "0";
$msg = "";

if ($acao === "listaSITE") {

    listaSITE($mysqli);
} else
if ($acao === "listaCN") {

    listaCN($mysqli, $uf_sessao);
} else 
if ($acao === "SCProcura") {

    $cn = $txtTitulo['cn'];
    $nome = $txtTitulo['nome'];

    scProcura($mysqli, $cn, $nome, $re);
} else
if ($acao === "LCEstoque") {

    $colaborador = $txtTitulo['colaborador'];
    LCEstoque($mysqli, $colaborador);
} else
if ($acao === "LCDetalhe") {

    $id = $txtTitulo['id'];
    lcDetalhes($mysqli, $id);
}

function listaSITE($mysqli)
{
    $sql = "select site.id as id, site.sigla as sigla, cn.nome as cn, stip.nome as tipo from site site inner join cn cn on cn.id=site.cn inner join site_tipo stip on stip.id=site.tipo order by tipo, cn, sigla";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaCN($mysqli, $uf)
{
    $sql = "select id, nome from cn where uf='{$uf}' order by nome";

    //$sql = "select id, nome from cn order by nome";
    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaGAS($mysqli)
{
    $sql = "SELECT id, nome FROM gas_tipo";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function cadastroLC($mysqli, $re, $data, $hora, $site, $gas, $qtd, $os, $obs, $data_p, $hora_p)
{

    $erro = "1";
    if ($re === "" || !$re) {

        $msg = "Necessário fazer login novamente.";

        header("Location: logOut");
    } else
    if ($site === "0" || !$site || $site === "") {

        $msg = "Necessário selecionar o SITE.";
    } else
    if ($gas === "0") {

        $msg = "Selecione o tipo de gás utilizado.";
    } else
    if ($qtd === "" || $qtd === 0) {

        $msg = "A quantidade informada é inválida.";
    } else
    if (!$os || $os === "0") {

        $msg = "Necessário informar a OS do prisma.";
    } else
    if (!$data_p || ValidaData($data_p) === "0") {
        $msg = "Data não informada ou inválida";
    } else
    if ($hora_p > $hora || !$hora_p || $hora_p === "") {

        $msg = "A hora informada é inválida.";
    } else {
        $sql = "insert into gas_lancamento (re, data, hora, site, prisma_os, prisma_data, prisma_hora, tipo, qtd_kg, obs) values ('{$re}', '{$data}','{$hora}','{$site}','{$os}','{$data_p}','{$hora_p}','{$gas}','{$qtd}','{$obs}')";

        if ($mysqli->query($sql)) {

            $registro_id = $mysqli->insert_id;

            bagagemAdiciona($mysqli, $re, $gas, $qtd, $data, $hora);

            $dadosEmail = bodyHtml($mysqli, $registro_id);

            enviar($mysqli, $dadosEmail, $registro_id);

            $erro = "0";
            $msg = "Utilização registrada com sucesso.";
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function bagagemAdiciona($mysqli, $re, $tipo, $qtd, $data, $hora)
{

    $bag = $mysqli->query("select count(id) as qtd, kg as kg from gas_bagagem where re='{$re}' and tipo='{$tipo}'")->fetch_array();

    if ($bag['qtd'] > 0) {

        $nKG = str_replace(".", ",", str_replace(",", ".", str_replace(".", "", $bag['kg'])) - str_replace(",", ".", str_replace(".", "", $qtd)));

        $sql = "update gas_bagagem set kg='{$nKG}', data='{$data}', hora='{$hora}' where re='{$re}' and tipo='{$tipo}'";
    } else {

        $kgN = "-" . $qtd;

        $sql = "insert into gas_bagagem (re,tipo, kg, data, hora) values ('{$re}', '{$tipo}', '{$kgN}','{$data}', '{$hora}')";
    }
    $mysqli->query($sql);
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
function scProcura($mysqli, $cn, $nome, $re)
{

    $where = "";

    if ($cn != "0") {
        $where .= " c.cn='{$cn}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if (strlen($nome) > 0) {
        $where .= " c.nome like '%" . $nome . "%'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }


    $sql = "select c.re as re, c.nome as nome, co.re as coRe, co.nome as coNome, cn.nome as cn from usuario c inner join usuario co on co.re=c.supervisor inner join cn on cn.id=c.cn WHERE " . $where . " and c.cargo!= '43' order by nome asc";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function lcEstoque($mysqli, $re)
{

    $sql = "select gb.re as re, gt.nome as tipo, gb.kg as kg, concat(gb.data,' ',gb.hora) as data from gas_bagagem gb inner join gas_tipo gt on gt.id=gb.tipo where gb.re='{$re}'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function lcDetalhes($mysqli, $id)
{

    $sql = "select l.id as id, l.prisma_os os, l.data as data_lancamento, l.hora as hora_lancamento, l.prisma_data as os_data, l.prisma_hora as os_hora, gt.nome as gas_tipo, l.qtd_kg as qtd, st.nome as site_tipo, s.sigla as site, cn.nome as cn, u.re as re, u.nome as nome, c.re as re_coordenador, c.nome as nome_coordenador, l.obs as obs from gas_lancamento l left join site s on s.id=l.site inner join usuario u on u.re=l.re inner join usuario c on c.re=u.supervisor left join cn on cn.id=s.cn inner join gas_tipo gt on gt.id=l.tipo left join site_tipo st on st.id=s.tipo where l.id='{$id}'";

    $lancamento = $mysqli->query($sql)->fetch_array();

    echo JsonEncodePAcentos::converter($lancamento);

    $mysqli->close();
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
