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

if ($acao === "listaPendente") {

    $coordenador = $re_sessao;
    listaPendente($mysqli, $coordenador);
} else
if ($acao === "colaborador") {
    colaborador($mysqli, $re_sessao);
} else
if ($acao === "gmg") {
    gmg($mysqli, $re_sessao);
} else
if ($acao === "dadosColaborador") {

    $colaborador = $txtTitulo['colaborador'];
    dadosColaborador($mysqli, $colaborador);
} else
if ($acao === "dadosGMG") {

    $gmg = $txtTitulo['gmg'];
    dadosGMG($mysqli, $gmg);
} else
if ($acao === "solicita") {

    $tipo = $txtTitulo['tipo'];
    $idAnterior = $txtTitulo['idAnterior'];
    $colaborador = $txtTitulo['re'];
    $cartao = $txtTitulo['cartao'];
    $identificacao = $txtTitulo['identificacao'];
    $saldo = $txtTitulo['saldo'];
    $valor = $txtTitulo['valor'];
    $obs = addslashes($txtTitulo['obs']);
    $ultKm = $txtTitulo['ultKM'];
    $km = $txtTitulo['km'];
    $uf = $_SESSION['uf'];

    $carct = array("'", "(", ")", ";", "'", "/", "|", "'\'");

    $obs = str_replace($carct, "", $obs);

    solicita($mysqli, $re_sessao, $tipo, $idAnterior, $colaborador, $cartao, $identificacao, $saldo, $valor, $obs, $ultKm, $km, $data, $hora, $uf);
} else
if ($acao === "confirma") {

    $obs1 = addslashes($txtTitulo['obs1']);
    $obs2 = addslashes($txtTitulo['obs2']);

    if (strlen($obs1) <= 2 || $obs1 === "") {
        $obs1 = "Sem observações.";
    }

    if (strlen($obs2) <= 2 || $obs2 = "") {
        $obs2 = "Sem observações.";
    }

    $obs = "Solicitante: " . $obs1 . " | Coordenador: " . $obs2;

    confirma($mysqli, $re_sessao, $txtTitulo['colaborador'], $txtTitulo['id'], $txtTitulo['valor'], $obs);
} else
if ($acao === "valor") {

    $id = $txtTitulo['id'];
    valor($mysqli, $id);
}
function confirma($mysqli, $re, $colaborador, $id, $valor, $obs)
{

    $p =  permissao($mysqli, "4", $re);

    $verifica = $mysqli->query("select id from solicitacao WHERE (status=1 or status=5 or status=6) and colaborador='{$colaborador}'")->num_rows;

    $erro = "1";

    //    if ($verifica > 0) {
    //        $msg = "Já existe uma solicitação pendente para esse colaborador.";
    //    } else
    if ($p === 0) {
        $msg = "Você não tem permissão para confirmar solicitações.";
    } else
    if ($valor === "" || $valor === 0 || intval($valor) === 0) {
        $msg = "Necessário informar o valor da solicitação.";
    } else {

        $sql = "update solicitacao set valor='{$valor}', obs='{$obs}', status='1' where id='{$id}'";

        if ($mysqli->query($sql)) {

            $id = $mysqli->insert_id;
            $data = date('Y-m-d');
            $hora = date('H:i');
            $dh = date('Y-m-d H:i');

            $sql_insert = "INSERT INTO solicitacao_historico (re, solicitacao, status, data, hora, atualizacao) VALUES ('{$re}','{$id}', '1','{$data}', '{$hora}','{$dh}')";
            $mysqli->query($sql_insert);
        }

        $erro = "0";
        $msg = "Solicitação confirmada com sucesso.";
    }


    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);

    $mysqli->close();
}
function valor($mysqli, $id)
{
    $sql = "select s.id as id, s.saldo as saldo, s.cartao as cartao, s.km as km, u.nome as nome, u.re as re, s.obs as obs from solicitacao s inner join usuario u on u.re=s.colaborador WHERE s.id=" . $id;

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();


    echo JsonEncodePAcentos::converter($row);

    $result->close();
    $mysqli->close();
}

function solicita($mysqli, $re_sessao, $tipo, $idAnterior, $colaborador, $cartao, $identificacao, $saldo, $valor, $obs, $ultKm, $km, $data, $hora, $uf)
{
    $erro = "1";

    $p =  permissao($mysqli, "4", $re_sessao);

    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else {
        if ($idAnterior === "") {
            $idAnterior = 0;
        }
        if ($saldo === "") {
            $saldo = 0;
        }

        $dados = vDados($mysqli, $tipo, $colaborador, $identificacao);

        if ($cartao === "0" || !$cartao || strlen($cartao) < 6 || $cartao === "N CADASTRADO") {
            $msg = "<i class='icon-attention'></i> Não existe um cartão atribuído a esse colaborador.";
        } else
            if ($tipo === "1" && solicitacaoVerifica($mysqli, $tipo, $colaborador) > 0) {
            $msg = "<i class='icon-attention'></i> Já existe uma solicitação pendente para esse colaborador.";
        } else 
        if ($tipo === "2" && solicitacaoVerifica($mysqli, $tipo, $colaborador) > 0) {
            $msg = "<i class='icon-attention'></i> Já existe uma solicitação pendente para esse GMG.";
        } else
        if ($valor === "" || $valor === 0) {
            $msg = "<i class='icon-attention'></i> Necessário informar o valor solicitado.";
        } else
        if ($tipo === "1" && ($km === 0 || $km === "")) {

            $msg = "<i class='icon-attention'></i> Necessário informar o KM atual do veículo.";
        } else
        if ($tipo === "1" && $ultKm >= $km) {
            $msg = "<i class='icon-attention'></i> O Km atual deve ser maior que o KM anterior.";
        } else {

            if ($tipo === "2") {
                $identificacao = $colaborador;
                $colaborador = $re_sessao;
            }
            $data_hora = $data . " " . $hora;
            $sql = "insert into solicitacao (anterior, solicitante, tipo, cartao, coordenador, colaborador, identificacao, saldo, valor, km, data, hora, solicitacao, status, obs, cn, uf) values (";
            $sql .= "'{$idAnterior}','{$re_sessao}', '{$tipo}', '{$cartao}', '{$dados['coordenador']}', '{$colaborador}', '{$identificacao}', '{$saldo}', '{$valor}', '{$km}', '{$data}', '{$hora}','{$data_hora}', '1', '{$obs}','{$dados['cn']}', '{$uf}')";

            if ($mysqli->query($sql)) {

                $id = $mysqli->insert_id;

                $sql_insert = "INSERT INTO solicitacao_historico (re, solicitacao, status, data, hora, atualizacao) VALUES ('{$re_sessao}','{$id}', '1','{$data}', '{$hora}','{$data_hora}')";
                $mysqli->query($sql_insert);
            }

            $erro = "0";
            $msg = "<i class='icon-ok-1'></i> Solicitação enviada com sucesso.";
        }
    }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function solicitacaoVerifica($mysqli, $tipo, $solicitado)
{
    $sql = "select id from solicitacao where (status=1 or status=5 or status=6) and tipo='{$tipo}' and colaborador='{$solicitado}'";
    $num = $mysqli->query($sql)->num_rows;
    return $num;
}

function listaPendente($mysqli, $coordenador)
{
    $sql = "select s.id as id, s.tipo as tipo, s.status as status, u.nome as colaborador, g.identificacao as gmg, gt.nome as gTipo, concat('R$ ',format(s.valor,2,'de_DE')) as valor, s.data as data, s.hora as hora from solicitacao s inner join usuario u on s.colaborador=u.re left join gmg g on s.identificacao=g.codigo left join gmg_tipo as gt on g.tipo=gt.id where (s.status=1 or s.status=6) and s.solicitante='{$coordenador}'";

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
    $sql = "select u.re as re, u.nome as nome from usuario u where u.ativo=2 and u.supervisor='{$coordenador}' order by u.nome";
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


    $sql = "select s.id as idAnterior, u.nome as nome, u.re as re, if(ifnull(u.cartao,'N CADASTRADO')='','N CADASTRADO',ifnull(u.cartao,'N CADASTRADO')) as cartao, ifnull(f.placa,'N CADASTRADO') as placa, f.km as froKm, ifnull(v.vei_marca,'ND') as vMarca, ifnull(v.vei_modelo,'ND') vModelo, ifnull(s.data,'NUNCA') as ultData, ifnull(concat('R$ ',format(s.valor,2,'de_DE')),'R$') as ultValor, concat('R$ ',format((select sum(valor) from solicitacao WHERE colaborador='{$colaborador}' and status=4 and tipo=1 and data like '%" . $mes_atual . "%'),2,'de_DE')) as valorMes, s.km as ultKm from usuario u left join frota f on u.frota=f.placa left join veiculo v on f.veiculo=v.vei_id left join solicitacao s on s.id='{$ultSol}' where u.re='{$colaborador}'";

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
