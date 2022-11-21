<?php

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include "l_sessao.php";
include 'json_encode.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';


$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());

$chave = md5($_SESSION['re'] . $data);

if (verificaChave($mysqli, $chave) < 1) {
    //    header("Location: logOut");
}

$acao = $txtTitulo['acao'];

if ($acao === "notaUnidade") {
    notaUnidade($mysqli, $re);
} else
if ($acao === "almoxLista") {

    almoxLista($mysqli, $re);
} else
if ($acao === "residuoLista") {

    $almox = $txtTitulo['almox'];
    residuoLista($mysqli, $almox);
} else 
if ($acao === "SolicitacaoProcura") {

    $unidade = $txtTitulo['unidade'];
    $data1 = $txtTitulo['data1'];
    $data2 = $txtTitulo['data2'];
    SolicitacaoProcura($mysqli, $unidade, $data1, $data2);
} else
if ($acao === "solicitacaoDetalhe") {

    $id = $txtTitulo['id'];
    descarteDetalhe($mysqli, $id);
} else
if ($acao === "reciclagem") {

    $almox = $txtTitulo['almox'];
    $item = $txtTitulo['item'];
    $qtd_almox = $txtTitulo['qtd_almox'];
    $qtd_descarte = $txtTitulo['qtd_descarte'];

    $sql_insert = "insert into sga_descarte (almox, item, qtd_almox, qtd_descarte, re, data, hora) values ('{$almox}','{$item}', '{$qtd_almox}', '{$qtd_descarte}','{$re}', '{$data}', '{$hora}')";

    if($mysqli->query($sql_insert)){
        $erro = "0";
        $msg = "Informação carregada com sucesso.";
    } else {
        $erro = "1";
        $msg = "Erro ao enviar informação.";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}

function SolicitacaoProcura($mysqli, $unidade, $data1, $data2)
{
    $where = "";

    if ($data1 != "") {
        $where .= " s.data >='{$data1}'";
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

    if ($unidade > 0) {
        $where .= " s.almox='{$unidade}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }
    $sql = "select s.id as id, s.data as data, s.hora as hora, sa.nome as unidade, s.anexo as nota, st.nome as tipo, st.ico as ico, s.obs as obs from sga_descarte s inner join sma_almoxarifado sa on sa.id=s.almox inner join sga_tipo st on st.id=s.item where" . $where . "";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function descarteDetalhe($mysqli, $id)
{

    $sql = "select s.id as id, s.re as re, u.nome as nome, st.nome as tipo, sl.limite as limite, s.qtd_descarte as qtd, s.qtd_almox as qtd_almox, s.data as data, s.hora as hora, sa.nome as unidade, s.anexo as nota, s.obs as obs from sga_descarte s inner join sma_almoxarifado sa on sa.id=s.almox inner join sga_tipo st on st.id=s.item inner join sga_limite sl on sl.tipo=st.id inner join usuario u on u.re=s.re where s.id='{$id}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $result->close();

    echo JsonEncodePAcentos::converter($row);

    $mysqli->close();
}
function residuoLista($mysqli, $almox)
{
    $sql = "select sa.id as id, sa.nome as almox, st.id as idItem, st.nome as tipo, sum(sb.qtd_entregue) as qtd, sl.limite as limite, ifnull((select sum(qtd_descarte) from sga_descarte WHERE item=st.id and almox=sa.id),0) as descarte from sma_almoxarifado sa inner join sga s on s.almoxarifado=sa.id inner join sga_baixa sb on sb.sga=s.id inner join sga_tipo st on st.id=sb.tipo inner join sga_limite sl on sl.id=st.id WHERE sa.id='{$almox}' GROUP by st.nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function notaUnidade($mysqli, $re)
{
    $sqlV = "select cn.regiao as regiao from usuario u inner join cn on cn.id=u.cn where u.re='{$re}'";
    $result = $mysqli->query($sqlV)->fetch_array();
    $regiao = $result['regiao'];

    $sql = "select nome, id from sma_almoxarifado where tipo=0 and regiao='{$regiao}'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function almoxLista($mysqli, $re)
{
    $sqlV = "select cn.regiao as regiao from usuario u inner join cn on cn.id=u.cn where u.re='{$re}'";
    $result = $mysqli->query($sqlV)->fetch_array();
    $regiao = $result['regiao'];

    $sql = "select sa.nome as nome, sa.id as id from sma_almoxarifado sa WHERE ((sa.tipo=0 and sa.regiao=1) or (sa.tipo=2 and sa.regiao=2)) and sa.regiao='{$regiao}'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaSolicitacaoAtiva($mysqli, $re)
{
    $ativa = "nd";

    $n = $mysqli->query("select id from cep_elemento_pai where re='{$re}' and status='1'")->num_rows;

    if ($n > 0) {

        $sql = "select pai.id as id, s.id as idSite, s.sigla as sigla, s.endereco as endereco, s.descricao as descricao, st.nome as tipo, pai.re as re, pai.data as data, pai.hora as hora, pai.elementos from cep_elemento_pai pai inner join site s on s.id=pai.site inner join site_tipo st on st.id=s.tipo left join cidade c on c.id=s.cidade WHERE re='{$re}' and status='1'";
        $result = $mysqli->query($sql);
        $row = $result->fetch_array();

        $result->close();
    } else {
        $row = array(
            "ativa" => $ativa
        );
    }

    echo JsonEncodePAcentos::converter($row);
}
function SiteProcura($mysqli, $txt)
{
    $txt = strtoupper($txt);

    $sql = "SELECT s.id as id, s.sigla as sigla, s.descricao as descricao, c.nome as cidade, st.nome as tipo FROM site s inner join site_tipo st on st.id=s.tipo left join cidade c on c.id=s.cidade WHERE s.sigla like '%" . $txt . "%'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function verificaDadosSite($mysqli, $site)
{
    $sql = "SELECT s.id as id, s.sigla as sigla, s.descricao as descricao, st.nome as tipo, s.endereco as endereco FROM site s inner join site_tipo st on st.id=s.tipo left join cidade c on c.id=s.cidade WHERE s.id='{$site}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $result->close();

    echo JsonEncodePAcentos::converter($row);

    $mysqli->close();
}
function CriaSolicitacao($mysqli, $re, $data, $hora, $site)
{
    //    $verificabeta = $mysqli->query("SELECT re FROM usuario WHERE re='{$re}' and beta=1")->num_rows;
    $erro = "1";
    $beta = 1;
    //    if ($verificabeta === 0) {
    //        $erro = "1";
    //        $msg = "Você não tem acesso a essa versão do sistema.";
    //        $beta = 0;
    //    } else
    if ($site == "0" || $site == 0 || $site == "" || !$site) {
        $msg = "Necessário selecionar o site.";
    } else {
        $erro = "0";
        $msg = "Cadastro iniciado com sucesso.";

        $sql = "insert into cep_elemento_pai (re, data, hora, site, elementos, status) values ('{$re}', '{$data}', '{$hora}', '{$site}', '0','1')";
        $mysqli->query($sql);
    }

    $arr = array("erro" => $erro, "msg" => $msg, "beta" => $beta);
    echo JsonEncodePAcentos::converter($arr);
}
function solicitacaoCancela($mysqli, $solicitacao)
{
    $Item = ItemVerifica($mysqli, $solicitacao, '0');
    $existe = $Item['existe'];

    if ($existe == "N") {
        $sql = "delete solicitacao.* from solicitacao WHERE id='{$solicitacao}'";
    } else {
        $sql = "delete solicitacao.*, solicitacao_itens.* from solicitacao, solicitacao_itens WHERE solicitacao.id='{$solicitacao}' and solicitacao_itens.solicitacao='{$solicitacao}'";
    }

    $mysqli->query($sql);

    $arr = array("erro" => "1", "msg" => "Solicitação cancelada com sucesso.");
    echo JsonEncodePAcentos::converter($arr);
}
function solicitacaoConclui($mysqli, $solicitacao, $re)
{
    $Item = ItemVerifica($mysqli, $solicitacao, '0');
    $existe = $Item['existe'];

    if ($existe == "N") {
        $erro = "1";
        $msg = "Nenhum ítem na solicitação.";
        //        $sql = "delete solicitacao.* from solicitacao WHERE id='{$solicitacao}'";
    } else {
        $erro = "0";
        $msg = "Solicitação enviada com sucesso.";
        $sql = "update solicitacao set status='2', re='{$re}' where id='{$solicitacao}'";

        $mysqli->query($sql);
    }

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

    $arr = array("erro" => "1", "msg" => "Item removido com sucesso.");
    echo JsonEncodePAcentos::converter($arr);
}
function listaEstrutura($mysqli)
{
    $sql = "select id, descricao as sigla from cep_site_estrutura";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaTipo($mysqli)
{
    $sql = "select id, nome as nome from cep_elemento_tipo order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function listaElemento($mysqli, $tipo)
{
    $sql = "select id, descricao as sigla, ativo_pai from cep_site_elemento where ativo=1 and tipo='{$tipo}' order by ativo_pai";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function cancelaSolicitacao($mysqli, $pai)
{
    $erro = "1";
    $msg = "";

    $sql = "delete from cep_elemento WHERE pai='{$pai}'";
    $sql_pai = "delete from cep_elemento_pai WHERE id='{$pai}'";

    if ($mysqli->query($sql)) {

        $erro = "0";
        $msg = "Cadastro cancelado com sucesso.";
        $mysqli->query($sql_pai);
    } else {
        $erro = "1";
        $msg = "Erro!";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function concluiElemento($mysqli, $pai)
{
    $erro = "1";
    $msg = "";

    $sql = "update cep_elemento set status='1' WHERE pai='{$pai}'";
    $sql_pai = "update cep_elemento_pai set status='2' WHERE id='{$pai}'";
    if ($mysqli->query($sql)) {

        $erro = "0";
        $msg = "Cadastro concluído com sucesso.";
        $mysqli->query($sql_pai);
    } else {
        $erro = "1";
        $msg = "Erro!";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function excluiElemento($mysqli, $elemento)
{
    $erro = "1";
    $msg = "";

    $sql = "DELETE FROM cep_elemento WHERE id='{$elemento}'";
    if ($mysqli->query($sql)) {

        $erro = "0";
        $msg = "Elemento removido com sucesso.";
    } else {
        $erro = "1";
        $msg = "Elemento não removido.";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
//TipoElemento
function cadastroElemento($mysqli, $elemento, $re, $data, $hora)
{
    $p = permissaoVerifica($mysqli, '33', $re);

    $erro = "1";
    if ($p === 0) {
        $msg = "Você não tem essa permissão.";
    } else {

        if ((int) $elemento['estrutura'] === 2 and (int) $elemento['elemento'] === 5) {
            $erro = "2";
            $msg = "Ar condicionado do tipo SPLIT não pode ser cadastrado em Gabinete, selecionar abrigo/casa/conteiner.";
        } else
        if ((int) $elemento['estrutura'] === 0) {
            $msg = "Necessário selecionar a estrutura.";
        } else
    if ((int) $elemento['estrutura'] === 2 and ((int) $elemento['Ngabinete'] === 0 || !$elemento['Ngabinete'])) {
            $msg = "Necessário informar o número do gabinete.";
        } else
    if ((int) $elemento['elemento'] === 0) {
            $msg = "Necessário selecionar o elemento.";
        } else
    if ((int) $elemento['Nelemento'] === 0) {
            $msg = "Necessário informar o número do elemento.";
        } else
        if ($elemento['elemento'] === "23" and ($elemento['obs'] === "" or strlen($elemento['obs']) < 2)) {
            $msg = "Necessário informar o modelo do rádio no campo de observações.";
        } else
        if ($elemento['elemento'] === "32" and $elemento['NFcc'] === "") {
            $msg = "Necessário informar o número do FCC da bateria.";
        } else {

            $Ngabinete = $elemento['Ngabinete'];

            if ($elemento['estrutura'] === "1") {
                $Ngabinete = "0";
            }

            $sql = "INSERT INTO cep_elemento (pai, re, site, estrutura, estrutura_n, elemento, elemento_n, fcc, observacao, data_cadastro, hora_cadastro, status) values(";
            $sql .= "'" . $elemento['pai'] . "','{$re}', '" . $elemento['site'] . "', '" . $elemento['estrutura'] . "', '{$Ngabinete}', '" . $elemento['elemento'] . "', '" . $elemento['Nelemento'] . "', '" . $elemento['NFcc'] . "', '" . $elemento['obs'] . "', '{$data}', '{$hora}', '4')";
            $mysqli->query($sql);

            $sql_ativo_pai = "";
            //  $id_inserido = $mysqli->insert_id;
            //$dados = obter_dados_historico($mysqli, $id_inserido);

            $verificaPAI = ativoPaiVerifica($mysqli, $elemento['pai'],  $elemento['elemento'], $elemento['site']);

            cadastraElementoVerifica($mysqli, $elemento, $Ngabinete, $re, $data, $hora);

            $erro = 0;
            $msg = "Elemento adicionado com sucesso.";

            $acao = "CADASTRO";

            // historico($mysqli, $elemento, $re, $data, $hora, $acao, $dados);
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function cadastraElementoVerifica($mysqli, $elemento, $Ngabinete, $re, $data, $hora)
{
    $e = $elemento['elemento'];
    $pai = $elemento['pai'];
    $site = $elemento['site'];

    if ($e === "14" || $e === "15" || $e === "29") {

        $num = $mysqli->query("select ele.id from cep_elemento ele where ele.pai='{$pai}' and ele.site='{$site}' and ele.ePai='1' and ele.elemento='36'")->num_rows;
        if ($num === 0) {
            cadastraElementoPrimario($mysqli, $pai, $re, "36", "0", $site, $elemento['estrutura'], $Ngabinete, $data, $hora, $num);
        }
    } else 
    if ($e === "4" || $e === "23") {

        $num = $mysqli->query("select ele.id from cep_elemento ele where ele.pai='{$pai}' and ele.site='{$site}' and ele.ePai='1' and ele.elemento='37'")->num_rows;
        if ($num === 0) {
            cadastraElementoPrimario($mysqli, $pai, $re, "37", "0", $site, $elemento['estrutura'], $Ngabinete, $data, $hora, $num);
        }
    } else 
    if ($e === "5" || $e === "6") {

        $num = $mysqli->query("select ele.id from cep_elemento ele where ele.pai='{$pai}' and ele.site='{$site}' and ele.ePai='1' and ele.elemento='24'")->num_rows;
        if ($num === 0) {
            cadastraElementoPrimario($mysqli, $pai, $re, "24", "1", $site, $elemento['estrutura'], $Ngabinete, $data, $hora, $num);
        }
    }
}
function cadastraElementoPrimario($mysqli, $pai, $re, $elemento, $elemento_n, $site, $estrutura, $Ngabinete, $data, $hora, $num)
{
    $sql_ativo_pai = "INSERT INTO cep_elemento (pai, ePai, re, site, estrutura, estrutura_n, elemento, elemento_n, fcc, observacao, data_cadastro, hora_cadastro, status) values(";
    $sql_ativo_pai .= "'" . $pai . "','1','{$re}', '" . $site . "', '" . $estrutura . "', '{$Ngabinete}', '{$elemento}', '{$elemento_n}', '0', 'ELEMENTO PAI', '{$data}', '{$hora}', '4')";

    $mysqli->query($sql_ativo_pai);
}
function ativoPaiVerifica($mysqli, $pai, $elemento, $site)
{

    $sql = "select ele.id from cep_elemento ele where ele.pai='{$pai}' and ele.elemento='{$elemento}' and ele.site='{$site}' and ele.ePai='1'";
    $num = $mysqli->query($sql)->num_rows;

    //   $num = $mysqli->query("select ele.id from elemento ele inner join site_elemento se on se.id=ele.elemento where ele.pai='{$pai}' and ele.elemento='{$elemento}' and ele.site='{$site}' and ele.observacao<>'ELEMENTO PAI' and (se.ativo_pai='TX' or se.ativo_pai='SAC1' or se.ativo_pai='RF')")->num_rows;
    return $num;
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
