<?php
include_once "./l_sessao.php";

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

include_once "./SCPEMAIL.php";


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
if ($acao === "listaColaborador") {
    listaColaborador($mysqli, $re);
} else
if ($acao === "listaAtividade") {
    listaAtividade($mysqli);
} else
if ($acao === "listaCN") {
    listaCN($mysqli, $uf_sessao);
} else 
if ($acao === "cadastroSCP") {

    $data = date('Y-m-d');
    $hora = date('H:i');

    $site = $txtTitulo['site'];
    $colaborador = $txtTitulo['colaborador'];
    $atividade = $txtTitulo['atividade'];
    $os = $txtTitulo['os'];
    $obs = addslashes($txtTitulo['obs']);
    $data1 = $txtTitulo['data1'];
    $hora1 = $txtTitulo['hora1'];
    $data2 = $txtTitulo['data2'];
    $hora2 = $txtTitulo['hora2'];
    $dh = date('Y-m-d H:i');

    cadastroSCP($mysqli, $re, $colaborador, $data, $hora, $dh, $site, $atividade, $os, $obs, $data1, $hora1, $data2, $hora2, $uf_sessao);
} else 
if ($acao === "SCPProcura") {

    $dataInicio = $txtTitulo['dataInicio'];
    $dataFim = $txtTitulo['dataFinal'];

    scpProcura($mysqli, $dataInicio, $dataFim, $re);
} else
if ($acao === "LCEstoque") {
    LCEstoque($mysqli, $re);
} else
if ($acao === "SCPDetalhe") {

    $id = $txtTitulo['id'];
    scpDetalhes($mysqli, $id);
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

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaColaborador($mysqli, $re)
{

    $gestao = regiao($mysqli, $re)['gestao'];
    $p = permissaoVerifica($mysqli, 98, $re);

    $where = "";

    if ($p === 0) {
        $where = " and u.supervisor='{$re}'";
    }

    $sql = "SELECT u.re as re, concat(cn.nome,' - ',u.nome) as nome FROM usuario u inner join cn on cn.id=u.cn WHERE u.gestao=1 and u.cargo!=43 and u.ativo=2 and u.gestao='{$gestao}'" . $where . " order by cn.nome, u.nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaAtividade($mysqli)
{
    $sql = "SELECT id, nome FROM scp_atividade order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function cadastroSCP($mysqli, $re, $colaborador, $data, $hora, $dh, $site, $atividade, $os, $obs, $data1, $hora1, $data2, $hora2, $uf)
{

    $p = permissaoVerifica($mysqli, 97, $re);

    $erro = "1";
    if ($p === 0) {

        $msg = "Você não tem permissão para solicitar correção de ponto, solicite ao seu coordenador.";
    } else
    if ($re === "" || !$re) {

        $msg = "Necessário fazer login novamente.";

        header("Location: logOut");
    } else
    if ($colaborador === 0) {
        $msg = "Necessário selecionar o colaborador.";
    } else
    if ($site === "0" || !$site || $site === "") {

        $msg = "Necessário selecionar o SITE.";
    } else
    if ($atividade === "0") {

        $msg = "Selecione o tipo de atividade.";
    } else
    if ($atividade != "1" and $atividade != "5" and (!$os || $os === "" || $os === "0")) {

        $msg = "Necessário informar o número OS/TA/TP.";
    } else
    if (strlen($obs) < 3) {
        $msg = "A justificativa informada é inválida.";
    } else
    if (!$data1 || ValidaData($data1) === "0") {
        $msg = "Data de entrada não informada ou inválida.";
    } else
    if ($hora1 === "") {
        $msg = "A hora de entrada informada é inválida.";
    } else
    if (!$data2 || ValidaData($data2) === "0") {
        $msg = "Data de saída não informada ou inválida.";
    } else
    if ($hora2 === "") {
        $msg = "A hora de saída informada é inválida.";
    } else {
        $sql = "insert into scp_registro (site, atividade, os, obs, dh, data, hora, data1, hora1, data2, hora2, status, solicitante, re, uf) values ('{$site}', '{$atividade}','{$os}','{$obs}', '{$dh}','{$data}','{$hora}','{$data1}','{$hora1}','{$data2}','{$hora2}','1','{$re}','{$colaborador}', '{$uf}')";

        if ($mysqli->query($sql)) {

            $registro_id = $mysqli->insert_id;

            $dadosEmail = bodyHtml($mysqli, $registro_id);

            enviar($mysqli, $dadosEmail, $registro_id);

            $erro = "0";
            $msg = "Solicitação realizada com sucesso.";
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
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
function scpProcura($mysqli, $dataInicio, $dataFim, $re)
{
    $p = permissaoVerifica($mysqli, 76, $re);

    $usuario = "";

    $usuario = " scp.re='{$re}' and ";

    $where = "";

    if ($dataInicio != "0") {
        $where .= " scp.data1 >= '{$dataInicio}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($dataFim != "") {
        $where .= " scp.data1 <= '{$dataFim}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    $where = $usuario . $where;
    //    $sql = "select l.id as id, l.prisma_os os, l.data as data_lancamento, l.hora as hora_lancamento, l.prisma_data as os_data, l.prisma_hora as os_hora, gt.nome as gas_tipo, l.qtd_kg as qtd, s.sigla as site, cn.nome as cn, u.re as re, u.nome as nome, c.re as re_coordenador, c.nome as nome_coordenador from gas_lancamento l left join site s on s.id=l.site inner join usuario u on u.re=l.re inner join usuario c on c.re=u.supervisor left join cn on cn.id=s.cn inner join gas_tipo gt on gt.id=l.tipo WHERE" . $where . " order by os_data, os_hora asc";
    $sql = "select scp.id as id, site.sigla as site, stipo.nome as site_tipo, ativ.nome as atividade, ativ.id as ativ_id, scp.os as os, scp.data as data, scp.hora as hora, scp.data1 as data1, scp.hora1 as hora1,scp.data1 as data2, scp.hora1 as hora2, scp.obs as justificativa, u.nome as nome, u.re as re, u.telefone as telefone, st.nome as status from scp_registro scp inner join site on site.id=scp.site inner join scp_atividade ativ on ativ.id=scp.atividade inner join usuario u on u.re=scp.re inner join site_tipo stipo on stipo.id=site.tipo inner join scp_status st on st.id=scp.status where " . $where;

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function scpDetalhes($mysqli, $id)
{
    $sql = "select scp.id as id, cn.nome as cn, site.sigla as site, stipo.nome as site_tipo, ativ.nome as atividade, ativ.id as ativ_id, scp.os as os, scp.data as data, scp.hora as hora, scp.data1 as data1, scp.hora1 as hora1,scp.data1 as data2, scp.hora1 as hora2, scp.obs as justificativa,scp.avaliacao as avaliacao, u.nome as nome, u.re as re, u.telefone as telefone, st.nome as status from scp_registro scp inner join site on site.id=scp.site inner join scp_atividade ativ on ativ.id=scp.atividade inner join usuario u on u.re=scp.re inner join site_tipo stipo on stipo.id=site.tipo inner join scp_status st on st.id=scp.status inner join cn on cn.id=site.cn where scp.id='{$id}'";

    $lancamento = $mysqli->query($sql)->fetch_array();

    echo JsonEncodePAcentos::converter($lancamento);

    $mysqli->close();
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
