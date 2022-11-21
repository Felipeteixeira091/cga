<?php
include_once "l_sessao.php";
include_once "json_encode.php";

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$re_sessao = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());

$chave = md5($_SESSION['re'] . $data);

if (verificaChave($mysqli, $chave) < 1) {
    header("Location: logOut");
}

$acao = $txtTitulo['acao'];

if ($acao === "periodo") {

    periodo($mysqli, $re_sessao);
} else
if ($acao === "anexo") {

    $sce = $txtTitulo['sce'];
    anexo($mysqli, $sce);
} else
if ($acao === "procura") {

    procura($mysqli, $re_sessao, $txtTitulo['periodo']);
} else
if ($acao === "dados") {

    detalhe($mysqli, $re_sessao);
} else
if ($acao === "solicita") {

    $obj = $txtTitulo['obj'];

    $obj['data'] = date("Y-m-d");
    $obj['hora'] = date("H:i");
    $obj['dh'] = date("Y-m-d H:i");
    $obj['re'] = $re_sessao;

    solicita($mysqli, $obj);
}
function anexo($mysqli, $sce)
{
    $sql = "select id, sce, tipo, arquivo from sce_anexo where sce='{$sce}'";

    $myArray = array();
    $result = $mysqli->query($sql);

    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $myArray[] = $row;
    }

    echo JsonEncodePAcentos::converter($myArray);
    $mysqli->close();
}
function solicita($mysqli, $obj)
{
    $erro = "1";

    $verifica = $mysqli->query("select id from solicitacao where colaborador='" . $obj['re'] . "' and (status=1 or status=2 or status=5 or status=6)")->num_rows;

    if ($verifica > 0) {
        $msg = "Já existe uma solicitação aguardando aprovação ou envio.";
    } else
    if ($obj['saldo'] === "" || !$obj['saldo']) {
        $msg = "Necessário informar o saldo atual.";
    } else
    if (!$obj['km'] || $obj['km'] === "") {
        $msg = "Necessário informar o km atual.";
    } else {

        $sql = "SELECT s.id as id, u.re as solicitante, u.cartao as cartao, co.re as coordenador, f.placa as placa, u.cn as cn, u.estado as uf FROM solicitacao s inner join usuario u on u.re=s.colaborador inner join usuario co on co.re=u.supervisor left join frota f on f.placa=u.frota left join veiculo v on v.vei_id=f.veiculo WHERE s.id=" . $obj['id'];
        $dados = $mysqli->query($sql)->fetch_assoc();

        $sql_insert = "insert into solicitacao (anterior, solicitante, tipo, cartao, coordenador, colaborador, identificacao, saldo, valor, km, data, hora, solicitacao, status, obs, cn, uf) 
        values ('" . $obj['id'] . "','" . $obj['re'] . "', '1', '" . $dados['cartao'] . "', '" . $dados['coordenador'] . "', '" . $obj['re'] . "', '" . $dados['placa'] . "', '" . $obj['saldo'] . "', '0', '" . $obj['km'] . "', '" . $obj['data'] . "', '" . $obj['hora'] . "','" . $obj['dh'] . "', '5', '" . addslashes($obj['obs']) . "','" . $dados['cn'] . "', '" . $dados['uf'] . "')";


        if ($mysqli->query($sql_insert)) {

            $erro = "0";
            $msg = "<i class='icon-ok-1'></i> Solicitação enviada com sucesso.";
        } else {
            $msg = "Erro ao concluir solicitação.";
        }
    }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function detalhe($mysqli, $re)
{

    $sql = "SELECT s.id as id, u.re as re, u.nome as nome, u.cartao as cartao, max(s.data) as data, s.km as UltKm, f.placa as placa, concat(v.vei_marca,'-',v.vei_modelo) as veiculo FROM solicitacao s inner join usuario u on u.re=s.colaborador left join frota f on f.placa=u.frota left join veiculo v on v.vei_id=f.veiculo WHERE s.colaborador='{$re}' and s.status=4 limit 1";
    $verifica_sql = "select id, saldo, km, obs from solicitacao where colaborador='{$re}' and (status=1 or status=2 or status=5 or status=6)";

    $tipo = "";
    $pendente = "";

    if ($mysqli->query($verifica_sql)->num_rows > 0) {
        $tipo = "pendente";
        $pendente =  $mysqli->query($verifica_sql)->fetch_assoc();
    } else {
        $tipo = "novo";
        $pendente = "nd";
    }

    $arr = array(
        "detalhe" => $mysqli->query($sql)->fetch_assoc(),
        "tipo" => $tipo,
        "pendente" => $pendente
    );


    echo JsonEncodePAcentos::converter($arr);
}
function procura($mysqli, $re, $periodo)
{

    $sql = "SELECT id, data, cartao, concat('R$ ',format(valor,2,'de_DE')) as valor from solicitacao WHERE colaborador='{$re}' and DATE_FORMAT(STR_TO_DATE(data, '%Y-%m'), '%Y-%m')='{$periodo}'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}
function periodo($mysqli, $re)
{
    $sql = "SELECT DATE_FORMAT(STR_TO_DATE(data, '%Y-%m'), '%Y-%m') as anoMes from solicitacao WHERE colaborador='{$re}' GROUP BY anoMes order by anoMes";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}
function colaborador($mysqli, $coordenador)
{
    $sql = "select u.re as re, u.nome as nome from usuario u where u.ativo='2' and u.cartao!='' and u.supervisor='{$coordenador}' order by u.nome";
    $myArray = array();
    if ($result = $mysqli->query($sql)) {

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}
function dadosColaborador($mysqli, $colaborador)
{
    $mes_atual = date('Y-m');

    $rUlt = $mysqli->query("SELECT max(id) as id FROM solicitacao s WHERE status='4' and tipo='1' and colaborador='{$colaborador}'");
    $rowUlt = $rUlt->fetch_array(MYSQLI_ASSOC);

    $ultSol = $rowUlt['id'];


    $sql = "select s.id as idAnterior, u.nome as nome, u.re as re, ifnull(u.cartao,'N CADASTRADO') as cartao, ifnull(f.placa,'N CADASTRADO') as placa, f.km as froKm, ifnull(v.vei_marca,'ND') as vMarca, ifnull(v.vei_modelo,'ND') vModelo, s.data as ultData, concat('R$ ',format(s.valor,2,'de_DE')) as ultValor, concat('R$ ',format((select sum(valor) from solicitacao WHERE colaborador='{$colaborador}' and status=4 and tipo=1 and data like '%" . $mes_atual . "%'),2,'de_DE')) as valorMes, s.km as ultKm from usuario u left join frota f on u.frota=f.placa left join veiculo v on f.veiculo=v.vei_id left join solicitacao s on s.id='{$ultSol}' where u.re='{$colaborador}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $result->close();


    echo JsonEncodePAcentos::converter($row);

    $mysqli->close();
}
function gmg($mysqli, $gmg)
{
    $sql = "select g.codigo as re, g.identificacao as identificacao, g.id as id, g.cartao as cartao, gt.nome as tipo, cn.nome as cn from gmg g inner join gmg_tipo gt on gt.id=g.tipo inner join cn on cn.id=g.cn WHERE g.supervisor='{$gmg}' and g.status=2 order by gt.nome, g.identificacao";
    $myArray = array();
    if ($result = $mysqli->query($sql)) {

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}
function dadosGMG($mysqli, $gmg)
{
    $rUlt = $mysqli->query("SELECT max(id) as id FROM solicitacao s WHERE status='4' and tipo='2' and identificacao='{$gmg}'");
    $rowUlt = $rUlt->fetch_array(MYSQLI_ASSOC);

    $ultSol = $rowUlt['id'];

    $mes_atual = date('Y-m');
    $sql = "select s.id as idAnterior, g.id as id, g.codigo as re, g.identificacao as gmg, gt.nome as tipo, g.cartao as cartao, g.supervisor as coordenador, s.data as ultData, concat('R$ ',format(s.valor,2,'de_DE')) as ultValor, (select max(data) from solicitacao where cartao=g.cartao and status=4) as ultima_solicitacao, concat('R$ ',format((select sum(valor) from solicitacao WHERE cartao=g.cartao and status=4 and tipo=2 and data like '%" . $mes_atual . "%'),2,'de_DE')) as valor_solicitado from gmg g inner join gmg_tipo gt on gt.id=g.tipo left join solicitacao s on s.id='{$ultSol}' where g.codigo='{$gmg}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $d30 = date('Y-m-d', strtotime('-30 days', strtotime(date('Y-m-d'))));
    $gmg = $row['re'];
    $sql_tempo = "select g.codigo as cod_gmg, g.identificacao gmg, SEC_TO_TIME(SUM(TIME_TO_SEC(ac.tempo))) as tempo from gmg_acoplamento ac inner join gmg g on g.codigo=ac.gmg_codigo inner join site s on s.id=ac.site inner join cn c on c.id=s.cn WHERE ac.gmg_codigo='{$gmg}' and data_inicio>='{$d30}' GROUP by cod_gmg";
    $row_tempo = $mysqli->query($sql_tempo)->fetch_assoc();

    $arr = array(
        "detalhe" => $row,
        "tempo" => $row_tempo['tempo']
    );


    $result->close();

    echo JsonEncodePAcentos::converter($arr);

    $mysqli->close();
}
function vDados($mysqli, $tipo, $colaborador, $identificacao)
{
    if ($tipo === "1") {
        $sql = "select c.re as coordenador, cn.rota as rota from usuario u inner join usuario c on c.re=u.supervisor left join cn on cn.id=u.cn where u.re='{$colaborador}'";
    } else {
        $sql = "select c.re as coordenador, cn.rota as rota from gmg g inner join usuario c on c.re=g.supervisor left join cn on cn.id=g.cn where g.codigo='{$identificacao}'";
    }
    $dados = $mysqli->query($sql)->fetch_array(MYSQLI_ASSOC);
    return $dados;
}
function permissao($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
