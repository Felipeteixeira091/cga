<?php

include "l_sessao.php";
include 'json_encode.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

include 'SMAXLS.php';
include 'SMAEMAIL.php';

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

/** Include PHPExcel */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$re = $_SESSION['re'];
$uf = $_SESSION['uf'];

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

    $funcao = "45";
    $permissao = permissaoVerifica($mysqli, $funcao, $re);

    $acesso = "";
    if ($permissao > 0) {
        $acesso = "tudo";
    }

    SolicitacaoProcura($mysqli, $txt, $data1, $data2, $status, $re);
} else
 if ($acao === "relatorio") {

    $solicitacao = $txtTitulo['solicitacao'];
    $status = $txtTitulo['status'];

    $sql = "update sma_solicitacao set status='{$status}' where id='{$solicitacao}'";
    if ($mysqli->query($sql)) {
        $retorno = array("erro" => "0", "msg" => "Solicitação alterada com sucesso.");
    } else {
        $retorno = array("erro" => "1", "msg" => "Erro identificado, tente novamente.");
    }
    echo JsonEncodePAcentos::converter($retorno);
    $mysqli->close();
} else
if ($acao === "SolicitacaoConclui") {

    $solicitacao = $txtTitulo['solicitacao'];
    $obs = addslashes($txtTitulo['obs']);
    $relatorio = $txtTitulo['relatorio'];
    $solicitacao_dados = obterSolicitacao($mysqli, $solicitacao);
    $cab = $solicitacao_dados['solicitacao'];

    $status = "";

    $permissao_material = permissaoVerifica($mysqli, "43", $re);
    $permissao_sobressalente = permissaoVerifica($mysqli, "46", $re);

    if ($relatorio != 1 and $relatorio != 2) {
        $retorno = array("erro" => "1", "msg" => "Necessário informar a necessidade ou não de relatório de conclusão.");
    } else
    if ($cab['sobressalente'] === "1" && $permissao_sobressalente === 0) {

        $retorno = array("erro" => "1", "msg" => "Você não pode aprovar solicitações de sobressalente.");
    } else 
    if ($cab['sobressalente'] === "0" && $permissao_material === 0) {

        $retorno = array("erro" => "1", "msg" => "Você não pode aprovar solicitações de materiais.");
    } else {

        if ($relatorio === "1") {
            $status = "11";
        } else {
            $status = "3";
        }
        if ($cab['status'] === "2" or $cab['status'] === "8") {

            $arquivo = databaseToExcel($solicitacao_dados);

            $cab['status'] = "3";
            $tabela = bodyHtml($mysqli, $solicitacao);
            $dados = dados_email($mysqli, $re, $arquivo, $cab, $obs);
            $retorno = enviar($mysqli, $dados, $tabela, "S", $solicitacao);

            if ($retorno['erro'] === "0") {

                updateStatus($mysqli, $solicitacao, $obs, $status, $re);
                historico($mysqli, $re, $solicitacao, $status);
            }

            if ($cab['tipo'] === "1") {
                bagagemGas($mysqli, $cab['reRetirada'], $solicitacao);
            }
        } else 
        if ($cab['status'] === "1" or $cab['status'] === "7") {
            $retorno = array("erro" => "1", "msg" => "Essa solicitação ainda está pendente ou em edição e não pode ser enviada.");
        } else 
        if ($cab['status'] === "4") {
            $retorno = array("erro" => "1", "msg" => "Essa solicitação foi negada e não pode ser enviada.");
        } else 
        if ($cab['status'] === "3") {
            $retorno = array("erro" => "1", "msg" => "Essa solicitação já foi enviada.");
        }
    }
    $mysqli->close();
    echo JsonEncodePAcentos::converter($retorno);
} else
if ($acao === "SolicitacaoEdita") {

    $solicitacao = $txtTitulo['solicitacao'];
    $obs = addslashes($txtTitulo['obs']);
    $solicitacao_dados = obterSolicitacao($mysqli, $solicitacao);

    $sql = "SELECT sol.id as solicitacao, sol.status as status from sma_solicitacao sol where sol.id='{$solicitacao}'";
    $dados = $mysqli->query($sql)->fetch_array();

    $permissao = permissaoVerifica($mysqli, "73", $re);
    $erro = "1";
    if ($permissao === 0) {

        $msg = "<i class='icon-attention'></i> Você não tem permissão para solicitar edição.";
    } else
    if ($dados['status'] != "3" and $dados['status'] != "2" and $dados['status'] != "7") {
        $msg = "<i class='icon-attention'></i> Essa solicitação não pode ser editada.";
    } else {

        $erro = "0";
        $msg = "<i class='icon-ok-1'></i> Edição solicitada com sucesso.";
        $sql = "update sma_solicitacao set status='7', obs='{$obs}' where id='{$solicitacao}'";

        if ($mysqli->query($sql)) {
            historico($mysqli, $re, $solicitacao, "7");
        }
    }

    $retorno = array("erro" => $erro, "msg" => $msg);

    echo JsonEncodePAcentos::converter($retorno);
} else
if ($acao === "SolicitacaoReprova") {

    $solicitacao = $txtTitulo['solicitacao'];

    $obs = addslashes($txtTitulo['obs']);
    $solicitacao_dados = obterSolicitacao($mysqli, $solicitacao);
    $cab = $solicitacao_dados['solicitacao'];

    $permissao_material = permissaoVerifica($mysqli, "44", $re);
    $permissao_sobressalente = permissaoVerifica($mysqli, "47", $re);

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
            $dados = dados_email($mysqli, $re, $arquivo, $cab, $obs);
            $tabela = bodyHtml($mysqli, $solicitacao);
            $retorno = enviar($mysqli, $dados, $tabela, "N", $solicitacao);

            if ($retorno['erro'] === "0") {

                updateStatus($mysqli, $solicitacao, $obs,  "4", $re);
                historico($mysqli, $re, $solicitacao, "4");
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
    $mysqli->close();
    echo JsonEncodePAcentos::converter($retorno);
} else 
if ($acao === "statusLista") {
    statusLista($mysqli);
} else
if ($acao === "historicoLista") {

    $solicitacao = $txtTitulo['solicitacao'];
    historicoLista($mysqli, $solicitacao);
}

function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
function historico($mysqli, $re, $id, $status)
{
    $agora = date("Y-m-d H:i");
    $sql_insert = "INSERT INTO sma_historico (re, status, atualizacao, sma) VALUES ('{$re}','{$status}', '{$agora}','{$id}')";

    $mysqli->query($sql_insert);
}
function historicoLista($mysqli, $solicitacao)
{
    $sql = "select u.re as re, sh.id as id, u.nome as nome, ss.nome as status, ss.ico as ico, sh.atualizacao as atualizacao from sma_historico sh inner join sma_solicitacao_status ss on ss.id=sh.status inner join usuario u on u.re=sh.re where sh.sma='{$solicitacao}'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function update_obs($mysqli, $obs, $solicitacao)
{
    $sql = "update sma_solicitacao set obs='{$obs}' where id='{$solicitacao}'";
    $mysqli->query("update sma_solicitacao set obs='{$obs}' where id='{$solicitacao}'");
}
function updateStatus($mysqli, $solicitacao, $obs, $status, $re)
{
    $aprovacao = date("Y-m-d H:i");
    $sql = "update sma_solicitacao set status='{$status}', obs='{$obs}', re_aprovacao='{$re}', aprovacao='{$aprovacao}' where id='{$solicitacao}'";
    $mysqli->query($sql);
}
function statusLista($mysqli)
{
    $sql = "select sss.id as id, sss.nome as nome from sma_solicitacao_status sss inner join sma_solicitacao ss on ss.status=sss.id group by sss.id";

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
    $sql = "select sol.id as id, sol.re as re, usr.nome as nome, ifnull(sol.os,'ND') as os, DATE_FORMAT(sol.solicitacao, '%Y-%m-%d') as data, count(si.pa) as itens, sit.sigla as site, cn.nome as cn, ss.nome as status from solicitacao sol inner join site sit on sit.id=sol.site inner join cn as cn on cn.id=sit.cn inner join solicitacao_status ss on ss.id=sol.status inner join usuario usr on usr.re=sol.re inner join solicitacao_itens si on si.solicitacao=sol.id";

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
    $sql = "SELECT sol.id as solicitacao, sol.re_retirada as ColaboradorRE, sol.sobressalente as sobressalente, ss.nome status, ss.ico as ico, sol.obs as Obs, col.nome as ColaboradorNome, col.supervisor as CoordenadorRE, coo.nome as CoordenadorNome, us.re as sRe, us.nome as sNome, DATE_FORMAT(sol.solicitacao, '%Y-%m-%d') as Prazo, sol.os as Os, ifnull(sit.sigla,'ERRO') as Sigla, cn.endereco as Endereco, stip.nome as tipo, ftip.nome as Fatura, aprov.nome as aprovNome, aprov.re as aprovRe, ifnull(sol.anexo,'nd') as anexo FROM sma_solicitacao sol inner join usuario col on col.re=sol.re_retirada inner join usuario coo on coo.re=col.supervisor inner join usuario us on us.re=sol.re left join site sit on sit.id=sol.site inner join sma_solicitacao_tiposegmento stip on stip.id=sol.tipo inner join sma_fatura_tipo ftip on ftip.id=sol.tipo_fatura inner join cn cn on cn.id=col.cn inner join sma_solicitacao_status ss on ss.id=sol.status left join usuario aprov on aprov.re=sol.re_aprovacao where sol.id='{$solicitacao}'";
    $sql_itens = "SELECT si.id as id, pa.numero as pa, pa.descricao as descricao, si.quantidade as quantidade, pa.gas as gas, ptu.nome as unidade, ifnull(sa.anexo,'nd') as anexo FROM sma_solicitacao_itens si inner join sma_pa pa on pa.id=si.pa inner join sma_pa_tipo_unidade ptu on ptu.id=pa.pa_tipo_unidade left join sma_anexo sa on sa.item=si.id where si.solicitacao='{$solicitacao}'";
    $sql_gas = "select gb.id as id, gb.kg as kg, gt.nome as tipo, gb.data as data, gb.hora as hora from gas_bagagem gb inner join gas_tipo gt on gt.id=gb.tipo inner join sma_solicitacao ss on ss.re_retirada=gb.re where ss.id='{$solicitacao}'";

    $result = $mysqli->query($sql);
    $solicitacao = $result->fetch_array();

    $vGas = 0;
    $itens = array();
    if ($result = $mysqli->query($sql_itens)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $itens[] = $row;

            if ($row['gas'] > 0) {
                $vGas++;
            }
        }
    }
    if ($vGas > 0) {
        $bagagem = array();
        if ($result = $mysqli->query($sql_gas)) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $bagagem[] = $row;
            }
        }
    } else {
        $bagagem = "";
    }

    $arr = array("solicitacao" => $solicitacao, "itens" => $itens, "bagagem" => $bagagem);


    $result->close();
    $mysqli->close();

    echo JsonEncodePAcentos::converter($arr);
}
function solicitacaoConclui($mysqli, $solicitacao, $re)
{
    $Item = ItemVerifica($mysqli, $solicitacao, '0');
    $existe = $Item['existe'];

    if ($existe == "N") {
        $erro = "1";
        $msg = "Nenhum ítem na solicitação, a mesma foi cancelada.";
        $sql = "delete sma_solicitacao.* from solicitacao WHERE id='{$solicitacao}'";
    } else {
        $erro = "0";
        $msg = "Solicitação enviada com sucesso.";
        $sql = "update sma_solicitacao set status='2' where id='{$solicitacao}'";
    }

    $mysqli->query($sql);

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function bagagemGas($mysqli, $re, $solicitacao)
{

    $sql = "select pa.numero as pa, sss.quantidade as qtd, gt.id as tipo_id, gt.nome as tipo_gas, gt.kg as kg from sma_solicitacao_itens sss inner join sma_pa pa on pa.id=sss.pa left join gas_tipo gt on gt.id=pa.gas where sss.solicitacao='{$solicitacao}'";

    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

            if ($row['tipo_id'] != "0" and $row['tipo_id'] != "") {

                $qtd = $row['qtd'];

                bagagemAdiciona($mysqli, $re, $row['tipo_id'], $row['kg'], $qtd);
            }
        }
    }
}
function bagagemAdiciona($mysqli, $re, $tipo, $kg, $qtd)
{
    $agora = date("Y-m-d H:i");

    //DATE_FORMAT(".$agora.", '%Y-%m-%d %H:%i')

    $bag = $mysqli->query("select count(id) as qtd, kg as kg from gas_bagagem where re='{$re}' and tipo='{$tipo}'")->fetch_array();

    if ($bag['qtd'] > 0) {

        $nKG = str_replace(".", ",", str_replace(",", ".", str_replace(".", "", $bag['kg'])) + ($qtd * str_replace(",", ".", str_replace(".", "", $kg))));

        $sql = "update gas_bagagem set kg='{$nKG}', modificacao='{$agora}' where re='{$re}' and tipo='{$tipo}'";
    } else {

        $kgN =  floatval(str_replace(",", ".", $kg)) * $qtd;
        $kgN = str_replace(".", ",", $kgN);

        $sql = "insert into gas_bagagem (re, tipo, kg, modificacao) values ('{$re}', '{$tipo}', '{$kgN}', '{$agora}')";
    }
    $mysqli->query($sql);
}

function ItemVerifica($mysqli, $solicitacao, $pa)
{

    if ($pa == 0) {
        $sql = "select ifnull(id,'0') as id, if(count(id)>0,'S','N') as existe, ifnull(quantidade,0) as qtd from sma_solicitacao_itens where solicitacao='{$solicitacao}'";
    } else {
        $sql = "select ifnull(id,'0') as id, if(count(id)>0,'S','N') as existe, ifnull(quantidade,0) as qtd from sma_solicitacao_itens where solicitacao='{$solicitacao}' and pa='{$pa}'";
    }

    $result_v = $mysqli->query($sql);
    $dados = $result_v->fetch_assoc();

    return $dados;
}
function ItemRemove($mysqli, $id)
{
    $sql = "delete from sma_solicitacao_itens WHERE id='{$id}'";
    $mysqli->query($sql);

    $arr = array("erro" => "1", "msg" => "Item removido com sucesso!");
    echo JsonEncodePAcentos::converter($arr);
}
function TipoSegmentoLista($mysqli)
{
    $sql = "SELECT id, nome FROM sma_solicitacao_tiposegmento";

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
function SolicitacaoProcura($mysqli, $txt, $data1, $data2, $status, $re)
{

    $sql = "select cn.regiao as regiao from usuario u inner join cn on cn.id=u.cn where u.re='{$re}'";
    $result = $mysqli->query($sql)->fetch_array();
    $regiao = $result['regiao'];

    $txt = strtoupper($txt);
    $where = "";

    if ($data1 != "") {
        $where .= " DATE(ss.solicitacao) >='{$data1}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($data2 != "") {
        $where .= " DATE(ss.solicitacao) <='{$data2}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($status != "0") {
        $where .= " ss.status='{$status}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($txt != "") {
        $where .= " (ss.id like '%" . $txt . "%' or ss.re like '%" . $txt . "%' or s.sigla like '%" . $txt . "%' or ss.re like '%" . $txt . "%' or sp.descricao like '%" . $txt . "%' or sp.numero like '%" . $txt . "%' or sp.descricao like '%" . $txt . "%' or st.nome like '%" . $txt . "%' or cn.nome like '%" . $txt . "%' or solicitante.nome like '%" . $txt . "%')";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    $sql = "select ss.id as ID_SOLICITACAO, ssi.id as ID_ITEM, DATE_FORMAT(ss.solicitacao, '%Y-%m-%d %H:%i') as SOLICITACAO, sp.numero as PA, sp.descricao as DESCRICAO, ssi.quantidade as QUANTIDADE, if(ss.sobressalente=0,'NÃO','SIM') as SOBRESSALENTE, sft.nome as FATURA, if(ss.tipo=1,'RETIRADA','DEVOLUÇÃO') as TIPO, ss.re_retirada as RE_RETIRADA, ss.re as RE_SOLICITANTE, cn.nome as CN, site.sigla as SITE, solicitante.nome as NOME_SOLICITANTE, sss.nome as STATUS, sss.ico as ICO, ifnull(st.nome,'ND') as sga_tipo from sma_solicitacao_itens ssi inner join sma_solicitacao ss on ss.id=ssi.solicitacao inner join sma_pa sp on sp.id=ssi.pa inner join sma_fatura_tipo sft on sft.id=ss.tipo_fatura inner join site s on s.id=ss.site inner join cn on cn.id=s.cn inner join usuario solicitante on solicitante.re=ss.re inner join site on site.id=ss.site inner join sma_solicitacao_status sss on sss.id=ss.status left join sga_tipo st on st.id=sp.sga_tipo WHERE " . $where . " and cn.regiao='{$regiao}' GROUP BY ss.id order by SOLICITACAO";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
