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

if ($acao === "solicitacaoLista") {

    solicitacaoLista($mysqli, $re);
} else
if ($acao === "ItensSolicitacao") {

    $id = $txtTitulo['id'];
    $itens = listaSolicitacaoItens($mysqli, $re, $id);
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
    $id = $txtTitulo['id'];
    listaSolicitacaoAtiva($mysqli, $re, $id);
} else
if ($acao === "editaConta") {
    contaEdicao($mysqli, $re);
} else 
if ($acao === "SolicitacaoCancela") {

    $solicitacao = $txtTitulo['solicitacao'];
    solicitacaoCancela($mysqli, $solicitacao, $re);
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
}
function solicitacaoLista($mysqli, $re)
{
    $permissao = permissaoVerifica($mysqli, "74", $re);

    $where = "";

    if ($permissao === 0) {
        $where = "and sol.re='{$re}'";
    } else {
        $where = "";
    }

    $sql = "SELECT s.id as id, si.sigla, cn.nome as cn, u.nome as solicitante, if(s.sobressalente=1,'SIM','NÃO') as sobressalente, s.data as data, s.hora as hora FROM sma_solicitacao s left join site si on si.id=s.site left join cn on cn.id=si.cn left join usuario u on u.re=s.re WHERE s.status='7' or s.status='10' " . $where . "";

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
function contaEdicao($mysqli, $re)
{
    $permissao = permissaoVerifica($mysqli, "74", $re);

    if ($permissao === 0) {
        $where = "and s.re='{$re}'";
    } else {
        $where = "";
    }

    $sql = "select count(s.id) as qtd from sma_solicitacao s where (s.status='7' or s.status='10') " . $where;
    $dados = $mysqli->query($sql)->fetch_assoc();

    $arr = array("qtd" => $dados['qtd']);

    echo JsonEncodePAcentos::converter($arr);
}
function listaSolicitacaoAtiva($mysqli, $re, $id)
{
    $sql_verifica = "select count(id) as cont from sma_solicitacao where id='{$id}'";
    $sql = "select s.id as id, ifnull(u.re,'ND') as re, u.nome as nome, s.data as data, s.status as status, s.sobressalente as sobressalente from sma_solicitacao s left join usuario u on u.re=s.re_edicao WHERE s.id='{$id}'";

    $result_v = $mysqli->query($sql_verifica);
    $row_v = $result_v->fetch_assoc();
    $ativa = "nd";

    if ($row_v['cont'] > 0) {

        $row = $mysqli->query($sql)->fetch_assoc();
        $ativa = "sim";
        if ($row['re'] === "ND") {

            $mysqli->query("update sma_solicitacao set status='10', re_edicao='{$re}' where id='{$id}'");
            $row = $mysqli->query($sql)->fetch_assoc();
        }

        $edicao = "0";
        if ($re === $row['re']) {
            $edicao = "1";
        }
        $arr = array(
            "ativa" => $ativa,
            "edicao" => $edicao,
            "id" => $row['id'],
            "re" => $row['re'],
            "nome" => $row['nome'],
            "data" => $row['data'],
            "sobressalente" => $row['sobressalente'],
            "staus" => $row['status'],
        );
        //$result->close();
    } else {

        $arr = array(
            "ativa" => $ativa
        );
    }


    $mysqli->close();

    echo JsonEncodePAcentos::converter($arr);
}
function listaSolicitacaoItens($mysqli, $re, $id)
{
    $sql = "select si.id as id, pa.numero as pa, pa.descricao as descricao, si.quantidade as quantidade from sma_solicitacao_itens si inner join sma_solicitacao sol on sol.id=si.solicitacao inner join sma_pa as pa on pa.id=si.pa where sol.id='{$id}' order by pa.numero";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function solicitacaoCancela($mysqli, $solicitacao, $re)
{
    $Item = ItemVerifica($mysqli, $solicitacao, '0');
    $existe = $Item['existe'];

    $erro = "1";
    $msg = "";

    $sql = "select s.id as id, ifnull(u.re,'ND') as re, u.nome as nome, s.data as data, s.status as status, s.sobressalente as sobressalente from sma_solicitacao s left join usuario u on u.re=s.re_edicao WHERE s.id='{$solicitacao}'";
    $dados = $mysqli->query($sql)->fetch_assoc();

    if ($existe == "N") {
        $erro = "1";
        $msg = "<i class='icon-attention'></i> Nenhum ítem na solicitação!";
    } else
    if ($dados['re'] != $re) {
        $msg = "<i class='icon-ok-1'></i> A solicitação continua em edição.";
    } else {
        $erro = "0";
        $msg = "<i class='icon-ok-1'></i> Solicitação liberada com sucesso.";
        $sql = "update sma_solicitacao set status='7', re_edicao='' where id='{$solicitacao}'";

        $mysqli->query($sql);
    }

    $arr = array("erro" => $erro, "msg" => $msg);
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
        $msg = "Edição concluída com sucesso.";
        $sql = "update sma_solicitacao set status='8', re='{$re}' where id='{$solicitacao}'";

        $mysqli->query($sql);
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
    $sql = "select almox.id as id, almox.nome as nome, almox.email as email from sma_almoxarifado almox inner join usuario usr on usr.re='{$re}' where usr.estado=almox.estado and almox.tipo='0' order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
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
    $sql = "select r.re as re, r.nome as nome, c.nome as cn from usuario r inner join cn c on c.id=r.cn inner join usuario s on s.re='{$re}' where r.estado=s.estado and r.ativo=2 order by r.nome";

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

    if ($sobressalente === "SIM") {

        $sql = "SELECT pa.id as id, pa.numero as numero, pa.descricao as descricao, pt.nome as tipo, pa.sobressalente as sobre FROM sma_pa pa left join sma_pa_tipo pt on pt.id=pa.tipo WHERE pa.status='1' and pa.sobressalente = '1' and (descricao like '%" . $txt . "%' or numero like '%" . $txt . "%' or tipo like '%" . $txt . "%')";
    } else {
        $sql = "SELECT pa.id as id, pa.numero as numero, pa.descricao as descricao, pt.nome as tipo FROM sma_pa pa left join sma_pa_tipo pt on pt.id=pa.tipo WHERE pa.status='1' and pa.sobressalente = '0' and (descricao like '%" . $txt . "%' or numero like '%" . $txt . "%' or tipo like '%" . $txt . "%')";
    }

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
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
