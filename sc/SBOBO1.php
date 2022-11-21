<?php
session_start();

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";


require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';
include_once "./frame/Email.php";

$re = $_SESSION["re"];
$uf_sessao = $_SESSION["uf"];

$acao = $txtTitulo['acao'];

$data = date('Y-m-d');
$hora = date('H:i', time());

$chave = md5($_SESSION['re'] . $data);

// Verifica se existe os dados da sessão de login
if (!isset($_SESSION["nome"]) || !isset($_SESSION["email"])) {
    header("Location: ../");
    exit;
}

$erro = "0";
$msg = "";

if ($acao === "listaSITE") {

    listaSITE($mysqli);
} else
if ($acao === "listaGMG") {

    listaGMG($mysqli);
} else
if ($acao === "listaCN") {
    listaCN($mysqli, $uf_sessao);
} else
if ($acao === "listaFechaduraStatus") {
    listaFechaduraStatus($mysqli);
} else
if ($acao === "cadastroBO") {

    $data = date('Y-m-d');
    $hora = date('H:i');
    $data_1 = "2022-01-01";//$txtTitulo['data_inicio'];
    $hora_1 = "08:00";//$txtTitulo['hora_inicio'];
    $data_2 = "2022-01-01";//$txtTitulo['data_final'];
    $hora_2 = "09:00";//$txtTitulo['hora_final'];

    $horas_indisponibilidade = calcula_indisponibilidade($data_1, $hora_1, $data_2, $hora_2);
    $bo = array(
        "re" => $re,
        "data" => date('Y-m-d'),
        "hora" => date('H:i'),
        "sinistro" => $txtTitulo['sinistro'],
        "site" => $txtTitulo['site'],
        "ta" => $txtTitulo['ta'],
        "relato" => str_replace("'", "", $txtTitulo['relato_OC']),
        "furtado" => str_replace("'", "", $txtTitulo['furtado']),
        "vandalizado" => str_replace("'", "", $txtTitulo['vandalizado']),
        "sobra" => str_replace("'", "", $txtTitulo['sobra']),
        "data_oc" => $txtTitulo['data'],
        "hora_oc" => $txtTitulo['hora'],
        "data_inicio" => $data_1,
        "hora_inicio" => $hora_1,
        "data_final" => $data_2,
        "hora_final" => $hora_2,
        "indis" => $horas_indisponibilidade,
        "f_bluetooth" => $txtTitulo['f_bluetooth'],
        "f_bluetooth_status" => $txtTitulo['f_bluetooth_status'],
        "modulo_box" => $txtTitulo['modulo_box'],
        "bateria" => $txtTitulo['bateria']
    );

    cadastroBO($mysqli, $bo, $re);
} else
if ($acao === "editaForm") {

    $bo = $txtTitulo['bo'];
    $texto = $txtTitulo['texto'];
    $campo = $txtTitulo['campo'];

    editaForm($mysqli, $bo, $texto, $campo, $re);
} else 
if ($acao === "BOProcura") {

    $cn = $txtTitulo['cn'];
    $site = $txtTitulo['site'];
    $status = $txtTitulo['status'];
    $dataInicio = $txtTitulo['dataInicio'];
    $dataFim = $txtTitulo['dataFinal'];

    boProcura($mysqli, $cn, $site, $status, $dataInicio, $dataFim, $re);
} else
if ($acao === "Detalhe") {

    $id = $txtTitulo['id'];
    Detalhes($mysqli, $id);
} else
if ($acao === "listaSTATUS") {

    listaStatus($mysqli);
} else
if ($acao === "iniciaTratativaBO") {

    $id = $txtTitulo['bo_id'];

    boInicia($mysqli, $re, $id, $data, $hora);
} else
if ($acao === "confirmaInformacoesBO") {

    $id = $txtTitulo['bo_id'];

    boConfirmaInfo($mysqli, $re, $id, $data, $hora);
} else
if ($acao === "cancelaTratativaBO") {
    $id = $txtTitulo['bo_id'];
    $obs = $txtTitulo['obs'];

    boCancela($mysqli, $re, $id, $obs, $data, $hora);
} else
if ($acao === "cancelaTratativaPermissao") {


    boCancelaPermissao($mysqli, $re);
} else
if ($acao === "concluiTratativaBO") {
    $id = $txtTitulo['bo_id'];
    $sinistro = $txtTitulo['sinistro'];

    boFinaliza($mysqli, $re, $id, $sinistro, $data, $hora);
} else
if ($acao === "verificaPUpload") {

    $p = permissaoVerifica($mysqli, '50', $re);

    if ($p === 0) {

        $erro = "1";
        $msg = "Você não tem permissão para a atividade!";
    } else {
        $erro = "0";
        $msg = "Carregando formulário!";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function boProcura($mysqli, $cn, $site, $status, $dataInicio, $dataFim, $re)
{

    $p = permissaoVerifica($mysqli, "53", $re);

    $usuario = "";

    $where = "";

    if ($cn > 0) {
        $where .= " site.cn='{$cn}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($site > 0) {
        $where .= " bo.site='{$site}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($status > 0) {
        $where .= " bo.status='{$status}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($dataInicio != "") {
        $where .= " bo.data >= '{$dataInicio}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($dataFim != "") {
        $where .= " bo.data <= '{$dataFim}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    $sql = "select bo.id as id, bo.re as re, bo.data as data, bo.hora as hora, site.sigla as site, cn.nome as cn, bs.nome as status, bs.ico as ico from sbo_bo bo inner join usuario usr on usr.re=bo.re inner join site site on site.id=bo.site inner join cn cn on cn.id=site.cn inner join sbo_bo_status bs on bs.id=bo.status WHERE" . $where . " order by data, hora asc";


    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function editaForm($mysqli, $bo, $texto, $campo, $re)
{

    $verifica = $mysqli->query("select id from sbo_bo where id='{$bo}' and re='{$re}'")->num_rows;

    $p = permissaoVerifica($mysqli, "91", $re);

    $erro = "1";
    $sql = "update sbo_bo set " . $campo . "='{$texto}' where id='{$bo}'";

    if ($verifica === 0 and $p === 0) {
        $msg = "Você não pode editar essa informação.";
    } else 
    if ($texto === "" or strlen($texto) < 5) {
        $msg = "Texto insuficiente.";
    } else {

        $erro = "0";
        $msg = $sql;
        "Edição realizada com sucesso.";
        $mysqli->query($sql);
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function calcula_indisponibilidade($data_1, $hora_1, $data_2, $hora_2)
{
    $diferenca = abs(strtotime($data_1 . ' ' . $hora_1) - strtotime($data_2 . ' ' . $hora_2));

    $horas = explode(".", $diferenca / 3600)[0];
    if ($horas < 10) {
        $horas = "0" . $horas;
    }
    $minutos = $diferenca / 60 % 60;
    if ($minutos < 10) {
        $minutos = "0" . $minutos;
    }
    return $horas . ":" . $minutos;
}
function listaSITE($mysqli)
{
    $sql = "select site.id as id, site.sigla as sigla, cn.nome as cn, stip.nome as tipo from site site inner join cn cn on cn.id=site.cn inner join site_tipo stip on stip.id=site.tipo order by tipo, sigla";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function boConfirmaInfo($mysqli, $re, $id, $data, $hora)
{

    $verifica = $mysqli->query("select id from sbo_bo where id='{$id}' and re='{$re}'")->num_rows;
    $status = $mysqli->query("select id from sbo_bo where id='{$id}' and status='6'")->num_rows;

    $p = permissaoVerifica($mysqli, '91', $re);

    $erro = "1";
    if ($status != "6") {
        $msg = "Esse BO não pode ser alterado.";
    } else
    if ($p === 0 and $verifica === 0) {
        $msg = "Você não tem permissão para a atividade.";
    } else {
        $sql = "update sbo_bo set status='1' where id='{$id}'";
        $mysqli->query($sql);

        bo_historico($mysqli, $id, $re, $data, $hora, '2');

        $mysqli->close();

        $erro = "0";
        $msg = "Informações confirmadas com sucesso.";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function boInicia($mysqli, $re, $id, $data, $hora)
{

    $p = permissaoVerifica($mysqli, '49', $re);

    if ($p === 0) {
        $erro = "1";
        $msg = "Você não tem permissão para a atividade!";
    } else {
        $sql = "update sbo_bo set status='2' where id='{$id}'";
        $mysqli->query($sql);

        bo_historico($mysqli, $id, $re, $data, $hora, '2');

        $mysqli->close();

        $erro = "0";
        $msg = "Tratativa iniciada!";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function boCancelaPermissao($mysqli, $re)
{
    $p = permissaoVerifica($mysqli, '52', $re);

    if ($p === 0) {

        $erro = "1";
    } else {
        $erro = "0";
    }

    $arr = array("erro" => $erro);
    echo JsonEncodePAcentos::converter($arr);
}
function boCancela($mysqli, $re, $id, $obs, $data, $hora)
{
    $p = permissaoVerifica($mysqli, '52', $re);

    if ($p === 0) {

        $erro = "1";
        $msg = "Você não tem permissão para cancelar BO's";
    } else {

        if ($obs === "" || !$obs) {
            $erro = "1";
            $msg = "Necessário informar a observação de cancelamento.";
        } else {

            $sql = "update sbo_bo set obs_cancelamento='{$obs}', status='5' where id='{$id}'";
            if ($mysqli->query($sql)) {

                bo_historico($mysqli, $id, $re, $data, $hora, '4');

                $erro = "0";
                $msg = "BO canelado com sucesso.";
            } else {
                $erro = "1";
                $msg = "Houve um erro ao finalizar.";
            }
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function boFinaliza($mysqli, $re, $id, $sinistro, $data, $hora)
{

    $p = permissaoVerifica($mysqli, '51', $re);

    if ($p === 0) {

        $erro = "1";
        $msg = "Você não tem permissão para concluir tratativa.";
    } else {

        if ($sinistro === "" || !$sinistro) {
            $erro = "1";
            $msg = "Necessário informar o número de sinistro.";
        } else {

            $sql = "update sbo_bo set numero_sinistro='{$sinistro}', status='4' where id='{$id}'";
            if ($mysqli->query($sql)) {

                bo_historico($mysqli, $id, $re, $data, $hora, '4');

                $erro = "0";
                $msg = "BO finalizado com sucesso.";
            } else {
                $erro = "1";
                $msg = "Houve um erro ao finalizar.";
            }
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function bo_historico($mysqli, $id, $re, $data, $hora, $status)
{
    $sql = "insert into sbo_bo_historico (bo, re, data, hora, status) values ('{$id}', '{$re}', '{$data}', '{$hora}', '{$status}')";
    $mysqli->query($sql);
}
function listaStatus($mysqli)
{
    $sql = "select id, nome from sbo_bo_status order by id";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaCN($mysqli, $uf_sessao)
{
    $sql = "select id, nome from cn where uf='{$uf_sessao}'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaGMG($mysqli)
{
    $sql = "select id, nome from gmg where tipo='1'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaFechaduraStatus($mysqli)
{
    $sql = "select id, nome from sbo_fechadura_status";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function cadastroBO($mysqli, $bo, $re)
{
    if ($bo['re'] === "" || !$bo['re']) {
        $erro = "1";
        $msg = "Necessário fazer login novamente.";
        header("Location: logOut");
    } else
    if ($bo['site'] === "0" || !$bo['site']) {
        $erro = "1";
        $msg = "Necessário selecionar o site.";
    } else
    if ($bo['ta'] === "0" || !$bo['ta']) {
        $erro = "1";
        $msg = "Necessário informar o TA.";
    } else
    if ($bo['data_oc'] === "" || ValidaData($bo['data_oc']) === "0") {
        $erro = "1";
        $msg = "Data da ocorrência inválida ou não informada.";
    } else
    if ($bo['data_inicio'] === "" || ValidaData($bo['data_inicio']) === "0") {
        $erro = "1";
        $msg = "Data de início de indisponibilidade inválida ou não informada.";
    } else
    if ($bo['data_final'] === "" || ValidaData($bo['data_final']) === "0") {
        $erro = "1";
        $msg = "Data final de indisponibilidade inválida ou não informada.";
    } else
    if ($bo['hora_oc'] === "" || validaHora($bo['hora_oc']) === "0") {
        $erro = "1";
        $msg = "Hora da ocorrência inválida ou não informada.";
    } else
    if ($bo['hora_inicio'] === "" || validaHora($bo['hora_inicio']) === "0") {
        $erro = "1";
        $msg = "Hora de início de indisponibilidade inválida ou não informada.";
    } else
    if ($bo['hora_final'] === "" || validaHora($bo['hora_final']) === "0") {
        $erro = "1";
        $msg = "Hora de final de indisponibilidade inválida ou não informada.";
    } else
    if ($bo['data_oc'] > $bo['data_inicio'] or $bo['data_oc'] > $bo['data_final'] or $bo['data'] < $bo['data_oc'] or $bo['data'] < $bo['data_inicio'] or $bo['data'] < $bo['data_final']) {
        $erro = "1";
        $msg = "Verifique os campos de data.";
    } else 
    if ($bo['furtado'] === "" or !$bo['furtado']) {
        $erro = "1";
        $msg = "Os itens furtados devem ser informados.";
    } else
     if ($bo['vandalizado'] === "" or !$bo['vandalizado']) {
        $erro = "1";
        $msg = "Os itens Vandalizados devem ser informados.";
    } else
     if ($bo['sobra'] === "" or !$bo['sobra']) {
        $erro = "1";
        $msg = "As sobras de itens vandalizados ou furtados devem ser informados.";
    } else
        if ($bo['relato'] === "" or !$bo['relato']) {
        $erro = "1";
        $msg = "O relato da ocorrência deve ser preenchido.";
    } else if ($bo['f_bluetooth'] === "ND") {
        $erro = "1";
        $msg = "Necessário informar dados da fechadura bluetooth.";
    } else if ($bo['f_bluetooth'] === "1" and $bo['f_bluetooth_status'] === "0") {
        $erro = "1";
        $msg = "Necessário informar a situação da fechadura bluetooth.";
    } else if ($bo['modulo_box'] === "ND") {
        $erro = "1";
        $msg = "Necessário informar a situação do modulo box.";
    } else if ($bo['bateria'] === "ND") {
        $erro = "1";
        $msg = "Necessário informar a situação da bateria.";
    } else {

        $sql = "insert into sbo_bo (
            re, 
            data, 
            hora, 
            data_oc, 
            hora_oc,
            data_inicio_oc,
            hora_inicio_oc,
            data_final_oc,
            hora_final_oc,
            tempo_indisponibilidade,
            f_bluetooth,
            f_bluetooth_status,
            modulo_box,
            bateria,
            site,
            ta,
            relato,
            furtado,
            vandalizado,
            sobra, 
            numero_sinistro,
            status) values (
                '" . $bo['re'] .
            "', '" . $bo['data'] .
            "', '" . $bo['hora'] .
            "','" . $bo['data_oc'] .
            "','" . $bo['hora_oc'] .
            "', '" . $bo['data_inicio'] .
            "', '" . $bo['hora_inicio'] .
            "', '" . $bo['data_final'] .
            "', '" . $bo['hora_final'] .
            "', '" . $bo['indis'] .
            "', '" . $bo['f_bluetooth'] .
            "', '" . $bo['f_bluetooth_status'] .
            "', '" . $bo['modulo_box'] .
            "', '" . $bo['bateria'] .
            "', '" . $bo['site'] .
            "', '" . $bo['ta'] .
            "', '" . $bo['relato'] .
            "', '" . $bo['furtado'] .
            "', '" . $bo['vandalizado'] .
            "', '" . $bo['sobra'] .
            "', '" . $bo['sinistro'] .
            "', '6')";
        $mysqli->query($sql);

        $id = $mysqli->insert_id;

        bo_historico($mysqli, $id, $bo['re'], $bo['data'], $bo['hora'], '1');

        $erro = "0";
        $msg = "Informações cadastradas com sucesso.";

        $dados_bo = $mysqli->query("select bo.id as bo_id, u.nome as nome, u.email as email, bo.data_oc as dataOcorrencia, bo.data as dataCadastro, bo.hora as horaCadastro, s.sigla as site, cn.nome as cn from sbo_bo bo inner join usuario u on u.re=bo.re inner join site s on s.id=bo.site inner join cn on cn.id=s.cn where bo.id='{$id}'")->fetch_array();
        $dados_email = $mysqli->query("select bo.id as bo_id, u.nome as nome, u.email as email, uc.nome as coordenadorNome, uc.email coordenadorEmail, s.sigla as site, cn.nome as cn from sbo_bo bo inner join usuario u on u.re=bo.re inner join site s on s.id=bo.site inner join cn on cn.id=s.cn inner join usuario uc on uc.re=u.supervisor where bo.id='{$id}'")->fetch_array();

        $sqlEmail = "select email, nome, colaborador, tipo from email_endereco where finalidade='sbo_cadastro'";
        $assunto = "BO Cadastrado ID: " . $dados_email['bo_id'] . " - SITE " . $dados_email['site'] . "|" . $dados_email['cn'];

        enviar($mysqli, $sqlEmail, $dados_email, $assunto, bodyHtml_SBO_Cadastro($dados_bo));
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
    $data = explode("-", "$dat");
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
function validaHora($horas)
{
    $h = intval(substr($horas, 0, 2));
    $m = intval(substr($horas, 3, 2));

    if ($h > 23 or $m > 59) {
        return "0";
    } else {
        return "1";
    }
}

function Detalhes($mysqli, $id)
{

    $sql = "select bo.id as id, bo.re as re, usr.nome as nome, coo.nome as nome_c, coo.re as re_c, bo.data as data, bo.hora as hora, bo.data_oc as data_oc, bo.hora_oc as hora_oc, bo.data_inicio_oc as data_inicio, bo.hora_inicio_oc as hora_inicio, bo.data_final_oc as data_final, bo.hora_final_oc as hora_final, bo.tempo_indisponibilidade as indisp, bo.f_bluetooth as f_bluetooth, fs.nome as f_bluetooth_status, bo.modulo_box as modulo_box, bo.bateria as bateria, site.sigla as site, site.descricao as s_descricao, ifnull(cid.nome,'NÃO INFORMADA') as s_cidade, ifnull(bai.nome,'NÃO INFORMADO') as s_bairro, site.cep as s_cep, site.endereco as s_endereco, cn.nome as cn, bo.relato as relato, bo.furtado as furtado, bo.vandalizado as vandalizado, bo.sobra as sobra, bs.nome as status, bs.id as status_id, bs.ico as ico, bo.anexo as anexo, bo.numero_bo as numero_bo, bo.numero_sinistro as numero_sinistro, bo.ta as ta, bo.obs_cancelamento as text_cancelamento from sbo_bo bo inner join usuario usr on usr.re=bo.re inner join usuario coo on coo.re=usr.supervisor inner join site site on site.id=bo.site inner join cn cn on cn.id=site.cn left join cidade cid on cid.id=site.cidade left join bairro bai on bai.id=site.bairro left join sbo_fechadura_status fs on fs.id=bo.f_bluetooth_status inner join sbo_bo_status bs on bs.id=bo.status where bo.id='{$id}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $result->close();

    echo JsonEncodePAcentos::converter($row);

    $mysqli->close();
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
