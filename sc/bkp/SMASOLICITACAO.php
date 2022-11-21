<?php
include_once "l_sessao.php";
include_once "json_encode.php";
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

if ($acao === "ItensSolicitacao") {

    $itens = listaSolicitacaoItens($mysqli, $re);
} else
if ($acao === "SiteProcura") {

    $txt = $txtTitulo['txt'];

    SiteProcura($mysqli, $txt, $re);
} else
if ($acao === "selecionaSite") {

    $site = $txtTitulo['site'];
    SelecionaSite($mysqli, $site);
} else
if ($acao === "ItemRemove") {

    $id = $txtTitulo['id'];
    ItemRemove($mysqli, $id);
} else
if ($acao === "ItemProcura") {

    $txt = $txtTitulo['txt'];
    $sobressalente = $txtTitulo['sobressalente'];

    ItemProcura($mysqli, $txt, $sobressalente);
} else 
if ($acao === "SolicitacaoVerifica") {

    listaSolicitacaoAtiva($mysqli, $re);
} else 
if ($acao === "criaSolicitacao") {

    $sigla = $txtTitulo['sigla'];
    $tipo = $txtTitulo['tipo'];
    $rede = $txtTitulo['rede'];
    $faturaTipo = $txtTitulo['faturaTipo'];
    $osTipo = $txtTitulo['osTipo'];
    $almoxarifado = $txtTitulo['almoxarifado'];
    $obs =  addslashes($txtTitulo['obs']);

    $os = $txtTitulo['os'];
    $retirada = $txtTitulo['retirada'];
    $sobressalente = $txtTitulo['sobressalente'];

    solicitacaoCria($mysqli, $re, $data, $hora, $sigla, $tipo, $rede, $faturaTipo, $osTipo, $almoxarifado, $os, $obs, $retirada, $sobressalente);
} else 
if ($acao === "SolicitacaoCancela") {

    $solicitacao = $txtTitulo['solicitacao'];
    solicitacaoCancela($mysqli, $solicitacao);
} else
if ($acao === "SolicitacaoConclui") {
    $solicitacao = $txtTitulo['solicitacao'];
    solicitacaoConclui($mysqli, $solicitacao, $re);
} else
if ($acao === "ItemAdd") {
    $solicitacao = $txtTitulo['solicitacao'];
    $pa = $txtTitulo['pa'];
    $quantidade = $txtTitulo['quantidade'];

    ItemAdd($mysqli, $solicitacao, $pa, $quantidade);
} else 
if ($acao === "tipoSegmentoLista") {

    TipoSegmentoLista($mysqli);
} else
if ($acao === "siteLista") {
    SiteLista($mysqli, $re);
} else
if ($acao === "almoxLista") {

    AlmoxLista($mysqli, $re);
} else
if ($acao === "segmentoLista") {

    SegmentoLista($mysqli);
} else
if ($acao === "osTipoLista") {
    OsTipoLista($mysqli);
} else 
if ($acao === "faturaTipoLista") {
    FaturaTipoLista($mysqli);
} else
if ($acao === "UsuarioLista") {

    UsuarioLista($mysqli, $re);
}
function historico($mysqli, $re, $id, $status)
{
    $agora = date("Y-m-d H:i");
    $sql_insert = "INSERT INTO sma_historico (re, status, atualizacao, sma) VALUES ('{$re}','{$status}', '{$agora}','{$id}')";

    $mysqli->query($sql_insert);

}
function SiteProcura($mysqli, $txt, $re_sessao)
{

    $sqlV = "select cn.regiao as regiao from usuario u inner join cn on cn.id=u.cn where u.re='{$re_sessao}'";
    $result = $mysqli->query($sqlV)->fetch_array();
    $regiao = $result['regiao'];

    $txt = strtoupper($txt);

    $sql = "SELECT s.id as id, s.sigla as sigla, s.descricao as descricao, st.nome as tipo, cn.nome as cn, uf.sigla as uf FROM site s inner join site_tipo st on st.id=s.tipo inner join cn cn on cn.id=s.cn inner join uf on uf.id=s.estado WHERE uf.regiao='{$regiao}' and s.sigla like '%" . $txt . "%'";


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
function listaSolicitacaoAtiva($mysqli, $re)
{
    $sql_verifica = "select count(id) as cont from sma_solicitacao where re='{$re}' and status='1'";
    $sql = "select s.id as id, s.re as re, s.data as data, s.status as status, s.sobressalente as sobressalente, ifnull(site.sigla,'ERRO') as site from sma_solicitacao s left join site on site.id=s.site WHERE re='{$re}' and status='1'";

    $result_v = $mysqli->query($sql_verifica);
    $row_v = $result_v->fetch_assoc();
    $ativa = "nd";

    if ($row_v['cont'] > 0) {

        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        $ativa = "sim";

        $arr = array(
            "ativa" => $ativa,
            "id" => $row['id'],
            "re" => $row['re'],
            "data" => $row['data'],
            "site" => $row['site'],
            "sobressalente" => $row['sobressalente'],
            "staus" => $row['status'],

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
function listaSolicitacaoItens($mysqli, $re)
{
    $sql = "select si.id as id, pa.numero as pa, pa.descricao as descricao, si.quantidade as quantidade, ifnull(sa.anexo,'nd') as anexo from sma_solicitacao_itens si inner join sma_solicitacao sol on sol.id=si.solicitacao inner join sma_pa as pa on pa.id=si.pa left join sma_anexo sa on sa.item=si.id where sol.re='{$re}' and sol.status='1' order by pa.numero";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function solicitacaoCria($mysqli, $re, $data, $hora, $sigla, $tipo, $rede, $faturaTipo, $osTipo, $almoxarifado, $os, $obs, $retirada, $sobressalente)
{

    $tipoSite = $mysqli->query("select tipo from site WHERE id='{$sigla}'")->fetch_assoc();

    $tipoSite = $tipoSite['tipo'];
    $erro = "1";

    if ($rede === "0") {
        $msg = "<i class='icon-attention'></i> Necessário selecionar o tipo de rede.";
    } else
    if ($tipo === "0") {
        $msg = "<i class='icon-attention'></i> Necessário selecionar o tipo de solicitação.";
    } else
    if ($faturaTipo === "0") {
        $msg = "<i class='icon-attention'></i> Necessário selecionar o tipo de fatura.";
    } else
    if ($osTipo === "0") {
        $msg = "<i class='icon-attention'></i> Necessário selecionar o tipo de OS.";
    } else
    if ($almoxarifado === "0") {
        $msg = "<i class='icon-attention'></i> Necessário selecionar o almoxarifado.";
    } else 
    if ($tipoSite === "3" && $osTipo != "4") {
        $msg = "<i class='icon-attention'></i> Para sites do tipo ADM o tipo de atividade deve ser ADM.";
    } else
    if ($tipoSite != "3" && $osTipo === "0") {
        $msg = "<i class='icon-attention'></i> Selecione um tipo de atividade.";
    } else
    if ($tipoSite === "3" && $faturaTipo === "2") {
        $msg = "<i class='icon-attention'></i> Deve ser selecionado Fatura A.";
    } else
    if ($faturaTipo === "0") {
        $msg = "<i class='icon-attention'></i> Necessário selecionar o tipo de fatura.";
    } else
    if ($os == "" && $tipoSite != "3") {
        $msg = "<i class='icon-attention'></i> Necessário informar a OS, TA ou TP.";
    } else
    if ($sobressalente === "2") {
        $msg = "<i class='icon-attention'></i> Necessário especificar se é sobressalente!";
    } else
    if ($retirada === "0") {
        $msg = "<i class='icon-attention'></i> Selecione o colaborador que irá retirar os itens.";
    } else {

      
        $erro = "0";
        $msg = "<i class='icon-ok-1'></i> Solicitação cadastrada com sucesso.";

        if ($osTipo === "4") {
            $os = "0";
        }
        if ($tipoSite === "3" && $osTipo === "0") {
            $osTipo = "4";
        }

        $data_hora = $data . " " . $hora;

        $sql = "insert into sma_solicitacao (re, tipo, rede, sobressalente, obs, tipo_os, os, tipo_fatura, data, hora, solicitacao, site, almoxarifado, re_retirada, status) values ('{$re}', '{$tipo}', '{$rede}','{$sobressalente}','{$obs}', '{$osTipo}','{$os}', '{$faturaTipo}','{$data}','{$hora}', '{$data_hora}', '{$sigla}', '{$almoxarifado}', '{$retirada}', '1')";
        
        if ($mysqli->query($sql)) {

            $id = $mysqli->insert_id;
            historico($mysqli, $re, $id, "1");
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function solicitacaoCancela($mysqli, $solicitacao)
{
    $Item = ItemVerifica($mysqli, $solicitacao, '0');
    $existe = $Item['existe'];

    if ($existe == "N") {
        $sql = "delete sma_solicitacao.* from sma_solicitacao WHERE id='{$solicitacao}'";
    } else {
        $sql = "delete sma_solicitacao.*, sma_solicitacao_itens.*, sma_historico.* from sma_solicitacao, sma_solicitacao_itens WHERE sma_solicitacao.id='{$solicitacao}' and sma_solicitacao_itens.solicitacao='{$solicitacao}' and sma_historico.sma='{$solicitacao}'";
    }

    $mysqli->query($sql);

    $arr = array("erro" => "1", "msg" => "<i class='icon-attention'></i> Solicitação cancelada com sucesso.");
    echo JsonEncodePAcentos::converter($arr);
}
function solicitacaoConclui($mysqli, $solicitacao, $re)
{
    $Item = ItemVerifica($mysqli, $solicitacao, '0');
    $existe = $Item['existe'];

    if ($existe == "N") {
        $erro = "1";
        $msg = "Nenhum ítem na solicitação!";
        //        $sql = "delete solicitacao.* from solicitacao WHERE id='{$solicitacao}'";
    } else {
        $erro = "0";
        $msg = "Solicitação enviada com sucesso.";
        $sql = "update sma_solicitacao set status='2', re='{$re}' where id='{$solicitacao}'";

        if ($mysqli->query($sql)) {
            historico($mysqli, $re, $solicitacao, "2");
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}

function ItemAdd($mysqli, $solicitacao, $pa, $quantidade)
{
    $item = ItemVerifica($mysqli, $solicitacao, $pa);
    $existe = $item['existe'];
    $quantidade_atual = $item['qtd'];
    $id_atual = $item['id'];
    $contaItens = itensVerifica($mysqli, $solicitacao);

    if ($contaItens === 10) {
        $arr = array("erro" => "1", "msg" => "Quantidade máxima de itens por solicitação atingida (10).");
    } else {
        if ($quantidade == 0) {
            $quantidade = 1;
        }
        if ($existe === "S") {
            $quantidade = $quantidade + $quantidade_atual;
            $sql = "update sma_solicitacao_itens set quantidade='{$quantidade}' where id='{$id_atual}'";
        } else {
            $sql = "insert into sma_solicitacao_itens(solicitacao, pa, quantidade) values ('{$solicitacao}', '{$pa}', '{$quantidade}')";
        }

        $mysqli->query($sql);
        $arr = array("erro" => "0", "msg" => "Item adicionado com sucesso.");
    }
    echo JsonEncodePAcentos::converter($arr);
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

    $arr = array("erro" => "1", "msg" => "Item removido com sucesso.");
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
function AlmoxLista($mysqli, $re)
{
    $sql = "select cn.regiao as regiao from usuario u inner join cn on cn.id=u.cn where u.re='{$re}'";
    $result = $mysqli->query($sql)->fetch_array();
    $regiao = $result['regiao'];

    $sql = "select almox.id as id, almox.nome as nome, almox.email as email from sma_almoxarifado almox where almox.tipo='0' and almox.regiao='{$regiao}'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}
function SegmentoLista($mysqli)
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
function OsTipoLista($mysqli)
{
    $sql = "SELECT id, nome FROM sma_os_tipo";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function FaturaTipoLista($mysqli)
{
    $sql = "SELECT id, nome FROM sma_fatura_tipo";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function SiglaLista($mysqli, $re)
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
function UsuarioLista($mysqli, $re)
{

    $sql = "select cn.regiao as regiao from usuario u inner join cn on cn.id=u.cn where u.re='{$re}'";
    $result = $mysqli->query($sql)->fetch_array();
    $regiao = $result['regiao'];

    $sql = "select r.re as re, r.nome as nome, c.nome as cn from usuario r inner join cn c on c.id=r.cn where c.regiao='{$regiao}' and r.ativo=2 order by r.nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function ItemProcura($mysqli, $txt, $sobressalente)
{
    $txt = strtoupper($txt);

    //  if ($sobressalente === "SIM") {

    $sql = "SELECT pa.id as id, pa.numero as numero, pa.descricao as descricao, pt.nome as tipo, if(pa.sobressalente=1,'SIM','NÃO') as sobre FROM sma_pa pa left join sma_pa_tipo pt on pt.id=pa.tipo WHERE pa.status='1' and (descricao like '%" . $txt . "%' or numero like '%" . $txt . "%' or tipo like '%" . $txt . "%')";
    //  } else {
    //      $sql = "SELECT pa.id as id, pa.numero as numero, pa.descricao as descricao, pt.nome as tipo FROM sma_pa pa left join sma_pa_tipo pt on pt.id=pa.tipo WHERE pa.status='1' and pa.sobressalente = '0' and (descricao like '%" . $txt . "%' or numero like '%" . $txt . "%' or tipo like '%" . $txt . "%')";
    //  }

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function itensVerifica($mysqli, $solicitacao)
{

    $num = $mysqli->query("SELECT id FROM sma_solicitacao_itens WHERE solicitacao='{$solicitacao}'")->num_rows;
    return $num;
}
