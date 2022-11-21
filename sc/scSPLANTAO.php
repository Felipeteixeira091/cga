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
    $obs = $txtTitulo['obs'];
    $ultKm = $txtTitulo['ultKM'];
    $km = $txtTitulo['km'];
    $uf = $_SESSION['uf'];

    solicita($mysqli, $re_sessao, $tipo, $idAnterior, $colaborador, $cartao, $identificacao, $saldo, $valor, $obs, $ultKm, $km, $data, $hora, $uf);
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

            $obs = "[SOLICITAÇÃO PLANTÃO] " . $obs;

            $sql = "insert into solicitacao (anterior, solicitante, tipo, cartao, coordenador, colaborador, identificacao, saldo, valor, km, data, hora, status, obs, cn, uf) values (";
            $sql .= "'{$idAnterior}','{$re_sessao}', '{$tipo}', '{$cartao}', '{$dados['coordenador']}', '{$colaborador}', '{$identificacao}', '{$saldo}', '{$valor}', '{$km}', '{$data}', '{$hora}', '4', '{$obs}','{$dados['cn']}', '{$uf}')";

            if ($mysqli->query($sql)) {

                $id = $mysqli->insert_id;

                $mysqli->query("INSERT INTO solicitacao_historico (re, solicitacao, status, data, hora) VALUES ('{$re_sessao}','{$id}', '1','{$data}', '{$hora}')");

                $mysqli->query("INSERT INTO solicitacao_historico (re, solicitacao, status, data, hora) VALUES ('{$re_sessao}','{$id}', '4','{$data}', '{$hora}')");
                $id1 = $mysqli->insert_id;
                $mysqli->query("UPDATE solicitacao set aprovacao='{$id1}' where id='{$id}'");
            }

            $erro = "0";
            $msg = "<i class='icon-ok-1'></i> Solicitação registrada com sucesso.";
        }
    }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function solicitacaoVerifica($mysqli, $tipo, $solicitado)
{
    $sql = "select id from solicitacao where status=1 and tipo='{$tipo}' and colaborador='{$solicitado}'";
    $num = $mysqli->query($sql)->num_rows;
    return $num;
}

function listaPendente($mysqli, $coordenador)
{
    $sql = "select s.tipo as tipo, u.nome as colaborador, g.identificacao as gmg, gt.nome as gTipo, concat('R$ ',format(s.valor,2,'de_DE')) as valor, s.data as data, s.hora as hora from solicitacao s inner join usuario u on s.colaborador=u.re left join gmg g on s.identificacao=g.codigo left join gmg_tipo as gt on g.tipo=gt.id where s.status=1 and s.solicitante='{$coordenador}'";

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
    $rota = $mysqli->query("select cn.rota as rota from usuario u inner join cn on cn.id=u.cn where u.re='{$coordenador}'")->fetch_array(MYSQLI_ASSOC);
    $rota = $rota['rota'];

    $sql = "select u.re as re, concat(cn.nome,'-',u.nome) as nome from usuario u inner join cn on cn.id=u.cn where u.ativo='2' and cn.rota='{$rota}' order by cn.nome, u.nome";
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

    $sql = "select s.id as idAnterior, u.nome as nome, u.re as re, u.cartao as cartao, f.placa as placa, f.km as froKm, v.vei_marca as vMarca, v.vei_modelo vModelo, s.data as ultData, concat('R$ ',format(s.valor,2,'de_DE')) as ultValor, concat('R$ ',format((select sum(valor) from solicitacao WHERE  colaborador='{$colaborador}' and status=4 and tipo=1 and data like '%" . $mes_atual . "%'),2,'de_DE')) as valorMes, s.km as ultKm from usuario u inner join frota f on u.frota=f.placa inner join veiculo v on f.veiculo=v.vei_id left join solicitacao s on s.id='{$ultSol}' where u.re='{$colaborador}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $result->close();


    echo JsonEncodePAcentos::converter($row);

    $mysqli->close();
}
function gmg($mysqli, $coordenador)
{
    $rota = $mysqli->query("select cn.rota as rota from usuario u inner join cn on cn.id=u.cn where u.re='{$coordenador}'")->fetch_array(MYSQLI_ASSOC);
    $rota = $rota['rota'];

    $sql = "select g.codigo as re, concat(cn.nome,' - ',gt.nome,'_',g.identificacao) as identificacao, g.id as id, g.cartao as cartao from gmg g inner join gmg_tipo gt on gt.id=g.tipo inner join cn on cn.id=g.cn WHERE g.status=2 and cn.rota='{$rota}' order by cn.nome, gt.nome, g.identificacao";
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

    $result->close();

    echo JsonEncodePAcentos::converter($row);

    $mysqli->close();
}
function vDados($mysqli, $tipo, $colaborador, $identificacao)
{
    if ($tipo === "1") {
        $sql = "select c.re as coordenador, cn.id as cn, cn.rota as rota from usuario u inner join usuario c on c.re=u.supervisor left join cn on cn.id=u.cn where u.re='{$colaborador}'";
    } else {
        $sql = "select c.re as coordenador, cn.id as cn, cn.rota as rota from gmg g inner join usuario c on c.re=g.supervisor left join cn on cn.id=g.cn where g.codigo='{$identificacao}'";
    }
    $dados = $mysqli->query($sql)->fetch_array(MYSQLI_ASSOC);
    return $dados;
}
function permissao($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
