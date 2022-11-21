<?php

include "l_sessao.php";
include 'json_encode.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

include 'xls.php';
include 'email.php';

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

/** Include PHPExcel */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());

$acao = $txtTitulo['acao'];

if ($acao === "SolicitacaoLista") {

    solicitacaoLista($mysqli);
} else 
if ($acao === "SolicitacaoDetalhe") {

    $solicitacao = $txtTitulo['solicitacao'];

    solicitacaoDetalhe($mysqli, $solicitacao);
} else 
if ($acao === "SolicitacaoProcura") {

    $txt = $txtTitulo['txt'];
    $status = $txtTitulo['status'];
    $data1 = $txtTitulo['data1'];
    $data2 = $txtTitulo['data2'];
    $pa = $txtTitulo['pa']; 

    $funcao = "3";
    $permissao = permissaoVerifica($mysqli, $funcao, $re);

    $acesso = "";
    if ($permissao > 0) {
        $acesso = "tudo";
    }

    SolicitacaoProcura($mysqli, $txt, $data1, $data2, $status, $acesso, $re, $pa);
} else
if ($acao === "SolicitacaoConclui") {

    $solicitacao = $txtTitulo['solicitacao'];
    $obs = addslashes($txtTitulo['obs']);
    update_obs($mysqli, $obs, $solicitacao);
    $solicitacao_dados = obterSolicitacao($mysqli, $solicitacao);
    $cab = $solicitacao_dados['solicitacao'];

    $permissao_material = permissaoVerifica($mysqli, "1", $re);
    $permissao_sobressalente = permissaoVerifica($mysqli, "4", $re);

    if ($cab['sobressalente'] === "1" && $permissao_sobressalente === 0) {

        $retorno = array("erro" => "1", "msg" => "Você não pode aprovar solicitações de sobressalente!");
    } else 
    if ($cab['sobressalente'] === "0" && $permissao_material === 0) {

        $retorno = array("erro" => "1", "msg" => "Você não pode aprovar solicitações de materiais!");
    } else {

        if ($cab['status'] === "2") {

            $arquivo = databaseToExcel($solicitacao_dados);

            $cab['status'] = "3";
            $tabela = bodyHtml($mysqli, $solicitacao);
            $dados = dados_email($mysqli, $re, $arquivo, $cab);
            $retorno = enviar($mysqli, $dados, $tabela, "S");

            if ($retorno['erro'] === "0") {
                updateStatus($mysqli, $solicitacao, $obs, "3", $re, $data, $hora);
            }
        } else 
        if ($cab['status'] === "1") {
            $retorno = array("erro" => "1", "msg" => "Essa solicitação ainda está pendente e não pode ser enviada.");
        } else 
        if ($cab['status'] === "4") {
            $retorno = array("erro" => "1", "msg" => "Essa solicitação foi negada e não pode ser enviada.");
        } else 
        if ($cab['status'] === "3") {
            $retorno = array("erro" => "1", "msg" => "Essa solicitação já foi enviada.");
        }
    }

    echo JsonEncodePAcentos::converter($retorno);
}
if ($acao === "SolicitacaoReprova") {

    $solicitacao = $txtTitulo['solicitacao'];
    $obs = addslashes($txtTitulo['obs']);
    update_obs($mysqli, $obs, $solicitacao);

    $solicitacao_dados = obterSolicitacao($mysqli, $solicitacao);
    $cab = $solicitacao_dados['solicitacao'];

    $permissao_material = permissaoVerifica($mysqli, "2", $re);
    $permissao_sobressalente = permissaoVerifica($mysqli, "5", $re);

    if ($cab['sobressalente'] === "1" && $permissao_sobressalente === 0) {

        $retorno = array("erro" => "1", "msg" => "Você não pode reprovar solicitações de sobressalente!");
    } else 
    if ($cab['sobressalente'] === "0" && $permissao_material === 0) {

        $retorno = array("erro" => "1", "msg" => "Você não pode reprovar solicitações de materiais!");
    } else {

        $solicitacao_dados = obterSolicitacao($mysqli, $solicitacao);

        $cab = $solicitacao_dados['solicitacao'];

        if ($cab['status'] === "2") {

            $arquivo = databaseToExcel($solicitacao_dados);

            $cab['status'] = "4";
            $dados = dados_email($mysqli, $re, $arquivo, $cab);
            $tabela = bodyHtml($mysqli, $solicitacao);
            $retorno = enviar($mysqli, $dados, $tabela, "N");

            if ($retorno['erro'] === "0") {
                updateStatus($mysqli, $solicitacao, $obs,  "4", $re, $data, $hora);
            }
        } else 
    if ($cab['status'] === "1") {
            $retorno = array("erro" => "1", "msg" => "Essa solicitação ainda está pendente e não pode ser negada.");
        } else 
    if ($cab['status'] === "4") {
            $retorno = array("erro" => "1", "msg" => "Essa solicitação já foi negada.");
        } else 
    if ($cab['status'] === "5") {
            $retorno = array("erro" => "1", "msg" => "Essa solicitação já foi enviada e não pode mais ser negada.");
        }
    }
    echo JsonEncodePAcentos::converter($retorno);
} else 
if ($acao === "statusLista") {
    statusLista($mysqli);
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}

function update_obs($mysqli, $obs, $solicitacao)
{
    $sql = "update solicitacao set obs='{$obs}' where id='{$solicitacao}'";
    $mysqli->query($sql);
}
function updateStatus($mysqli, $solicitacao, $obs, $status, $re, $data, $hora)
{

    $sql = "update solicitacao set status='{$status}', obs='{$obs}', aprovacao='{$re}', data_aprovacao='{$data}', hora_aprovacao='{$hora}' where id='{$solicitacao}'";
    $mysqli->query($sql);
    $mysqli->close();
}
function statusLista($mysqli)
{
    $sql = "select id, nome from solicitacao_status";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function solicitacaoLista($mysqli)
{
    $sql = "select sol.id as id, sol.re as re, usr.nome as nome, ifnull(sol.os,'ND') as os, sol.data as data, count(si.pa) as itens, sit.sigla as site, cn.nome as cn, ss.nome as status from solicitacao sol inner join site sit on sit.id=sol.site inner join cn as cn on cn.id=sit.cn inner join solicitacao_status ss on ss.id=sol.status inner join usuario usr on usr.re=sol.re inner join solicitacao_itens si on si.solicitacao=sol.id";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}

function solicitacaoDetalhe($mysqli, $solicitacao)
{
    $sql = "SELECT sol.retirada as colaboradorRe, sol.sobressalente as sobressalente, ss.nome status, sol.obs as obs, col.nome as colaboradorNome, col.supervisor as coordenadorRe, coo.nome as coordenadorNome, us.re as sRe, us.nome as sNome, sol.data as Prazo, sol.os as Os, sit.sigla as Sigla, cn.endereco as Endereco, stip.nome as tipo, ftip.nome as fatura FROM solicitacao sol inner join usuario col on col.re=sol.retirada inner join usuario coo on coo.re=col.supervisor inner join usuario us on us.re=sol.re inner join site sit on sit.id=sol.site inner join solicitacao_tiposegmento stip on stip.id=sol.tipo inner join fatura_tipo ftip on ftip.id=sol.tipo_fatura inner join cn cn on cn.id=col.cn inner join solicitacao_status ss on ss.id=sol.status where sol.id='{$solicitacao}'";
    $sql_itens = "SELECT si.id as id, pa.numero as pa, pa.descricao as descricao, si.quantidade as quantidade, ptu.nome as unidade FROM solicitacao_itens si inner join pa pa on pa.id=si.pa inner join pa_tipo_unidade ptu on ptu.id=pa.pa_tipo_unidade where si.solicitacao='{$solicitacao}'";


    $myArray = array();
    if ($result = $mysqli->query($sql_itens)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
    }
    $res = $mysqli->query($sql);
    $r = $res->fetch_assoc();

    $arr = array(
        "sRe" => $r['sRe'],
        "sNome" => $r['sNome'],
        "ColaboradorRE" => $r['colaboradorRe'],
        "ColaboradorNome" => $r['colaboradorNome'],
        "CoordenadorRE" => $r['coordenadorRe'],
        "CoordenadorNome" => $r['coordenadorNome'],
        "Prazo" => $r['Prazo'],
        "status" => $r['status'],
        "Os" => $r['Os'],
        "Obs" => $r['obs'],
        "Sigla" => $r['Sigla'],
        "Endereco" => $r['Endereco'],
        "tipo" => $r['tipo'],
        "sobressalente" => $r['sobressalente'],
        "Fatura" => $r['fatura'],
        "plan" => $myArray
    );

    $result->close();
    $mysqli->close();

    echo JsonEncodePAcentos::converter($arr);
}
function solicitacaoConclui($mysqli, $solicitacao)
{
    $Item = ItemVerifica($mysqli, $solicitacao, '0');
    $existe = $Item['existe'];

    if ($existe == "N") {
        $erro = "1";
        $msg = "Nenhum ítem na solicitação, a mesma foi cancelada!";
        $sql = "delete solicitacao.* from solicitacao WHERE id='{$solicitacao}'";
    } else {
        $erro = "0";
        $msg = "Solicitação enviada com sucesso!";
        $sql = "update solicitacao set status='2' where id='{$solicitacao}'";
    }

    $mysqli->query($sql);

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function ItemVerifica($mysqli, $solicitacao, $pa)
{

    if ($pa == 0) {
        $sql = "select ifnull(id,'0') as id, if(count(id)>0,'S','N') as existe, ifnull(quantidade,0) as qtd from solicitacao_itens where solicitacao='{$solicitacao}'";
    } else {
        $sql = "select ifnull(id,'0') as id, if(count(id)>0,'S','N') as existe, ifnull(quantidade,0) as qtd from solicitacao_itens where solicitacao='{$solicitacao}' and pa='{$pa}'";
    }

    $result_v = $mysqli->query($sql);
    $dados = $result_v->fetch_assoc();

    return $dados;
}
function ItemRemove($mysqli, $id)
{
    $sql = "delete from solicitacao_itens WHERE id='{$id}'";
    $mysqli->query($sql);

    $arr = array("erro" => "1", "msg" => "Item removido com sucesso!");
    echo JsonEncodePAcentos::converter($arr);
}
function TipoSegmentoLista($mysqli)
{
    $sql = "SELECT id, nome FROM solicitacao_tiposegmento";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function SiteLista($mysqli)
{
    $sql = "SELECT id, sigla FROM site";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function SiglaLista($mysqli)
{
    $sql = "SELECT id, sigla FROM site";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function SolicitacaoProcura($mysqli, $txt, $data1, $data2, $status, $acesso, $re, $pa)
{
    $txt = strtoupper($txt);
    $where = "";

    $sql_pa = "";
    $sql_pa2 = "";

    if ($pa === "s") {
        $sql_pa = "left join solicitacao_itens sit on sit.solicitacao=s.id inner join pa on pa.id=sit.pa";
        $sql_pa2 = " or pa.numero like '%" . $txt . "%'";
    }

    if ($acesso === "tudo") {
        $where = " u.estado = uL.estado";
    } else {
        $where = " u.estado = uL.estado AND sol.re='{$re}'";
    }

    if ($data1 != "") {
        $where .= " and s.data >='{$data1}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($data2 != "") {
        $where .= " s.data <='{$data2}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($txt != "") {
        $where .= " s.id='{$txt}' or (s.re like '%" . $txt . "%' or si.sigla like '%" . $txt . "%' or u.nome like '%" . $txt . "%' or si.descricao like '%" . $txt . "%'" . $sql_pa2 . ")";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($status != "0") {
        $where .= " s.status='{$status}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }


    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    $sql = "select s.re as solicitante, u.estado estado_solicitante, s.id as id, cn.nome as cn, si.sigla as site, s.sobressalente as sobressalente, s.data as data, s.hora as hora, ss.nome as status, (select count(id) from solicitacao_itens where solicitacao=s.id) as itens from solicitacao s inner join site si on si.id=s.site left join cn cn on cn.id=si.cn left join solicitacao_status ss on ss.id=s.status left join usuario u on u.re=s.re inner join usuario uL on uL.re='{$re}' " . $sql_pa . " WHERE" . $where . " order by s.id";


    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
