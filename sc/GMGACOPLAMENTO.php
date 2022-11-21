<?php
include_once "./l_sessao.php";

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

include_once "./GMGEMAIL.php";


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
if ($acao === "listaGMG") {
    listaGMG($mysqli, $uf_sessao);
} else
if ($acao === "listaCN") {
    listaCN($mysqli);
} else 
if ($acao === "cadastroAC") {

    $data = date('Y-m-d');
    $hora = date('H:i');

    $gmg = $txtTitulo['gmg'];
    $site = $txtTitulo['site'];
    $ta = $txtTitulo['ta'];
    $observacao = $txtTitulo['observacoes'];
    $data_inicio = $txtTitulo['data_inicio'];
    $hora_inicio = $txtTitulo['hora_inicio'];
    $data_final = $txtTitulo['data_final'];
    $hora_final = $txtTitulo['hora_final'];

    cadastroAC($mysqli, $re, $gmg, $site, $ta, $observacao, $data, $hora, $data_inicio, $hora_inicio, $data_final, $hora_final);
} else 
if ($acao === "ACProcura") {

    $cn = $txtTitulo['cn'];
    $txt = $txtTitulo['txt'];
    $dataInicio = $txtTitulo['dataInicio'];
    $dataFim = $txtTitulo['dataFinal'];

    acProcura($mysqli, $cn, $dataInicio, $dataFim, $re, $txt);
} else
if ($acao === "ACDetalhe") {

    $id = $txtTitulo['id'];
    acDetalhes($mysqli, $id);
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
function listaCN($mysqli)
{
    $sql = "select id, nome from cn";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaGMG($mysqli, $uf_sessao)
{
    $sql = "select g.codigo as id, concat('CN_',cn.nome,' ',gt.nome,'_',g.identificacao) as nome from gmg g inner join gmg_tipo gt on gt.id=g.tipo left join cn on cn.id=g.cn where (tipo='1' or tipo='3') and status='2' and estado='{$uf_sessao}' order by g.cn, gt.id, g.identificacao";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function cadastroAC($mysqli, $re, $gmg, $site, $ta, $observacao, $data, $hora, $data_inicio, $hora_inicio, $data_final, $hora_final)
{
    $acoplamento_id = "";

    if ($re === "" || !$re) {
        $erro = "1";
        $msg = "Necessário fazer login novamente.";

        header("Location: logOut");
    } else
    if ($gmg === "0") {
        $erro = "1";
        $msg = "Necessário selecionar o GMG.";
    } else
    if ($site === "0" || !$site || $site === "") {
        $erro = "1";
        $msg = "Necessário selecionar o SITE.";
    } else
    if (!$ta || $ta === "0") {
        $erro = "1";
        $msg = "Necessário informar o TA.";
    } else
    if (!$data_inicio || ValidaData($data_inicio) === "0") {
        $erro = "1";
        $msg = "Data inicial não informada ou inválida";
    } else
     if (!$data_final || ValidaData($data_final) === "0") {
        $erro = "1";
        $msg = "Data final não informada ou inválida";
    } else
     if ($data_inicio > $data_final) {
        $erro = "1";
        $msg = "Data final não pode ser menor que a data inicial.";
    } else
     if ($data_inicio === $data_final and $hora_inicio >= $hora_final) {
        $erro = "1";
        $msg = "Hora de início ou final incorreta.";
    } else
    if ($data_inicio > $data or $data_final > $data) {

        $erro = "1";
        $msg = "As datas de inicio e fim de acoplamento não podem ser maiores que a data atual.";
    } else
     if ($data_final === $data and ($hora_final > $hora)) {

        $erro = "1";
        $msg = "A hora de fim do acoplamento não pode ser maior que a hora atual.";
    } else {

        
        $registro = date('Y-m-d H:i');
        $ac_inicio = $data_inicio.' '.$hora_inicio;
        $ac_final = $data_final.' '.$hora_final;

        $sql = "insert into gmg_acoplamento (re, gmg, gmg_codigo, site, ta, registro, ac_inicio, ac_final, tempo, data, hora, data_inicio, hora_inicio, data_final,hora_final, observacao) values ('{$re}', '0', '{$gmg}', '{$site}','{$ta}','{$registro}','{$ac_inicio}','{$ac_final}',TIMEDIFF('{$ac_final}','{$ac_inicio}'),'{$data}', '{$hora}', '{$data_inicio}', '{$hora_inicio}', '{$data_final}', '{$hora_final}', '{$observacao}')";

        if ($mysqli->query($sql)) {

            $acoplamento_id = $mysqli->insert_id;

            $dadosEmail = bodyHtml($mysqli, $acoplamento_id);
            enviar($mysqli, $dadosEmail, $acoplamento_id);

            $erro = "0";
            $msg = "Acoplamento cadastrado com sucesso.";
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function paVerifica($mysqli, $pa)
{

    $num = $mysqli->query("select numero from pa where numero='{$pa}'")->num_rows;
    return $num;
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
function acProcura($mysqli, $cn, $dataInicio, $dataFim, $re, $txt)
{
    $p = permissaoVerifica($mysqli, "53", $re);

    $usuario = "";
    if ($p === 0) {
        $usuario = " re='{$re}' and ";
    }
    $where = "";

    if ($cn != "0") {
        $where .= " site.cn='{$cn}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($txt != "") {
        $where .= " gmg.identificacao like '%" . $txt . "%' or site.sigla like '%" . $txt . "%'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($dataInicio != "0") {
        $where .= " '{$dataInicio}' <= ac.data_inicio";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($dataFim != "") {
        $where .= " '{$dataFim}'  >= ac.data_inicio";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    $where = $usuario . $where;
    $sql = "select ac.id as id, gmg.identificacao as gmg, site.sigla as site, ac.data as data, ac.hora as hora, ac.data_inicio as dataInicio from gmg_acoplamento ac inner join gmg gmg on gmg.codigo=ac.gmg_codigo inner join site site on site.id=ac.site WHERE" . $where . " order by data, hora asc";


    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}

function acDetalhes($mysqli, $id)
{

    $sql = "select ac.id as id, ac.re as re, usr.nome as nome, coo.re as reCoordenador,coo.nome as nomeCoordenador, gmg.identificacao as gmg, site.sigla as site, cn.nome as cn, ac.data as data, ac.hora as hora, ac.data_inicio as data_inicio, ac.data_final as data_final, ac.hora_inicio as hora_inicio, ac.hora_final as hora_final, ac.observacao as obs from gmg_acoplamento ac left join gmg on gmg.codigo=ac.gmg_codigo inner join usuario usr on usr.re=ac.re left join usuario coo on coo.re=usr.supervisor inner join site site on site.id=ac.site inner join cn cn on cn.id=site.cn where ac.id='{$id}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();

    $arr = array(
        "id" => $row['id'],
        "re" => $row['re'],
        "nome" => $row['nome'],
        "reCoordenador" => $row['reCoordenador'],
        "nomeCoordenador" => $row['nomeCoordenador'],
        "gmg" => $row['gmg'],
        "site" => $row['site'],
        "cn" => $row['cn'],
        "data" => $row['data'],
        "hora" => $row['hora'],
        "data_inicio" => $row['data_inicio'],
        "data_final" => $row['data_final'],
        "hora_inicio" => $row['hora_inicio'],
        "hora_final" => $row['hora_final'],
        "obs" => $row['obs']
    );

    $result->close();
    echo JsonEncodePAcentos::converter($arr);

    $mysqli->close();
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
