<?php
include "l_sessao.php";
$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

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

if ($acao === "SolicitacaoVerifica") {

    listaSolicitacaoAtiva($mysqli, $re);
} else
if ($acao === "SiteProcura") {

    $txt = $txtTitulo['txt'];

    SiteProcura($mysqli, $txt, $re);
} else
if ($acao === "verificaDadosSite") {

    $site = $txtTitulo['site'];
    verificaDadosSite($mysqli, $site);
} else
if ($acao === "criaSolicitacao") {
    $site = $txtTitulo['site'];
    CriaSolicitacao($mysqli, $re, $data, $hora, $site);
} else
 if ($acao === "listaEstrutura") {

    listaEstrutura($mysqli);
} else 
if ($acao === "listaTipo") {

    listaTipo($mysqli);
} else
if ($acao === "listaElemento") {

    listaElemento($mysqli, $txtTitulo['tipo']);
} else
if ($acao === "addElemento") {

    $elemento = $txtTitulo['elemento'];

    cadastroElemento($mysqli, $elemento, $re, $data, $hora);
} else
if ($acao === "excluiElemento") {

    excluiElemento($mysqli, $txtTitulo['elemento']);
} else
if ($acao === "concluiElemento") {

    concluiElemento($mysqli, $txtTitulo['pai']);
} else 
if ($acao === "listaElementoCadastrados") {

    listaCadastro($mysqli, $re);
} else
if ($acao === "SolicitacaoCancela") {

    cancelaSolicitacao($mysqli, $txtTitulo['pai']);
} else
if ($acao === "dataCadastro") {

    $agora = date('/m/Y');
    $agora = "19" . $agora;

    $row = array(
        "data" => $agora
    );

    echo JsonEncodePAcentos::converter($row);
}

function listaCadastro($mysqli, $re)
{

    $where = " ele.ePai=0 and es.id='4' and ele.re='{$re}'";
    $sql = "select ele.id as id, ele.ePai as ePai, sit.tipo as tipo_site, sit.sigla as site, ifnull(se.sigla,'ELEMENTO_PAI') as estrutura, ele.estrutura_n as estrutura_n, sel.descricao as descricao, sel.excel as excel, sel.ativo_pai as ativo_pai, sel.sigla as elemento, ele.elemento_n as elemento_n, ele.fcc as fcc, ele.data_cadastro as data, ele.hora_cadastro as hora, es.nome as status, ele.re as re, uf.sigla as uf from cep_elemento ele inner join site sit on sit.id=ele.site inner join cn on cn.id=sit.cn inner join uf on uf.id=cn.uf inner join cep_site_estrutura se on se.id=ele.estrutura inner join cep_site_elemento sel on sel.id=ele.elemento inner join cep_elemento_status es on es.id=ele.status WHERE" . $where . " order by ele.data_cadastro, ele.hora_cadastro";

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
function SiteProcura($mysqli, $txt, $re)
{

    $regiao = regiao($mysqli, $re)['regiao'];

    $txt = strtoupper($txt);

    $p = permissaoVerifica($mysqli, '96', $re);

    $where = "";
    if ($p === 0) {
        $where = " cn.regiao='{$regiao}' and ";
    }

    $sql = "SELECT s.id as id, s.sigla as sigla, cn.nome as cn, s.descricao as descricao, c.nome as cidade, st.nome as tipo FROM site s inner join site_tipo st on st.id=s.tipo left join cidade c on c.id=s.cidade inner join cn on cn.id=s.cn WHERE " . $where . "s.sigla like '%" . $txt . "%'";

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

    $regiao = regiao($mysqli, $re)['regiao'];

    $erro = "1";
    if ($p === 0) {
        $msg = "Você não tem essa permissão.";
    } else {

        if ((int) $elemento['estrutura'] === 2 and (int) $elemento['elemento'] === 5 and $regiao === 1) {
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
