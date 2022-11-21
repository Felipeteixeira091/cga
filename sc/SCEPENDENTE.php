<?php
include_once "l_sessao.php";
include_once "json_encode.php";

if (!isset($_SESSION)) {
    session_start();
}
// Verifica se existe os dados da sessão de login
if (!isset($_SESSION["nome"]) || !isset($_SESSION["email"])) {
    header("Location: ../");
    exit;
}

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$acao = $txtTitulo['acao'];
$re_sessao = $_SESSION['re'];

$data = date('Y-m-d');
$hora = date('H:i');
$uf = $_SESSION['uf'];

if ($acao === "lista_coordenador") {

    lista_coordenador($mysqli, $_SESSION['uf'], $re_sessao);
} else
if ($acao === "lista_status") {
    lista_status($mysqli);
} else
if ($acao === "filtra") {

    $status = $txtTitulo['status'];
    $solicitante = $txtTitulo['coordenador'];
    $data1 = $txtTitulo['data1'];
    $data2 = $txtTitulo['data2'];

    filtra_aprovacao($mysqli, $status, $solicitante, $data1, $data2, $uf, $re_sessao);
} else
if ($acao === "detalhes") {

    $id = $txtTitulo['id'];
    solicitacao_detalhe($mysqli, $id);
} else
if ($acao === "update") {

    $id = $txtTitulo['id'];
    $obs = $txtTitulo['obs'];
    $status = $txtTitulo['status'];
    $valor = $txtTitulo['valor'];

    update($mysqli, $id, $obs, $re_sessao, $data, $hora, $status, $valor);
}
function lista_status($mysqli)
{
    $sql = "select id, nome from solicitacao_status";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }

    $result->close();
    $mysqli->close();
}

function lista_coordenador($mysqli, $uf, $re)
{
    $gestao = regiao($mysqli, $re)['gestao'];

    $p = permissaoVerifica($mysqli, "16", $re);

    $where = "";

    if ($p === 0) {
        $where .= " and u.gestao ='{$gestao}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    $sql = "select u.re as re,concat(g.nome,'-',u.nome) as nome, g.nome as gestao from permissao p inner join usuario u on u.re=p.colaborador inner join gestao g on g.id=u.gestao WHERE p.funcao=66 and u.ativo=2 and u.sistema=2 " . $where . " order by u.gestao, nome ASC";


    $myArray = array();
    if ($result = $mysqli->query($sql)) {

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }

    $result->close();
    $mysqli->close();
}

function filtra_aprovacao($mysqli, $status, $solicitante, $data1, $data2, $uf, $re)
{

    $p = permissaoVerifica($mysqli, "16", $re);

    $gestao = regiao($mysqli, $re)['gestao'];

    $where = "";

    if ($p === 0) {
        $where .= " u.gestao ='{$gestao}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

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

    if ($status != "0") {
        $where .= " s.status='{$status}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($solicitante != "0") {
        $where .= " s.solicitante='{$solicitante}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    $sql = "select s.id as id, s.tipo as tipo, s.cartao as cartao, u.nome as colaborador, co.re as re_coordenador, co.nome as nome_coordenador, g.identificacao as gmg, gt.nome as gTipo, concat('R$ ',format(s.valor,2,'de_DE')) as valor, s.data as data, s.hora as hora, ss.id as status, ss.nome as status_N from solicitacao s inner join usuario u on s.colaborador=u.re left join usuario co on co.re=u.supervisor left join gmg g on s.identificacao=g.codigo left join gmg_tipo gt on g.tipo=gt.id inner join solicitacao_status ss on ss.id=s.status inner join cn on cn.id=u.cn inner join uf on uf.id=s.uf WHERE " . $where;

    $myArray = array();
    if ($result = $mysqli->query($sql)) {

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }

    $result->close();
    $mysqli->close();
}

function solicitacao_detalhe($mysqli, $id)
{

    $dData = $mysqli->query("select SUBSTRING(data,1,7) as data from solicitacao where id='{$id}'")->fetch_assoc();

    $mes_atual = $dData['data'];

    $sql = "select s.id as id, s.status as iDstatus, sst.nome as status, s.anterior as idAnt, ifnull(sAnt.data,'ND') as antData, sAnt.hora as antHora, concat('R$ ', format(sAnt.valor,2,'de_DE')) as antValor, s.tipo as tipo, s.cartao as cartao, u.nome as colaborador_nome, u.re as colaborador_re, s.identificacao as vPlaca, f.km as froKm, v.vei_marca as vMarca, v.vei_modelo as vModelo, s.km as km, sAnt.identificacao as antIdentificacao, sAnt.km as antKm, concat('R$ ',format((select sum(valor) from solicitacao WHERE colaborador=u.re and status=4 and tipo=1 and data like '%" . $mes_atual . "%'),2,'de_DE')) as valorMes_colaborador, co.re as coordenador_re, co.nome as coordenador_nome, g.identificacao as gmg, gt.nome as gTipo, concat('R$ ',format((select sum(valor) from solicitacao WHERE identificacao=s.identificacao and status=4 and tipo=2 and data like '%" . $mes_atual . "%'),2,'de_DE')) as valorMes_GMG, concat('R$ ',format(s.valor,2,'de_DE')) as valor, s.data as data, s.hora as hora, s.obs as obs from solicitacao s inner join solicitacao_status sst on sst.id=s.status inner join usuario u on s.colaborador=u.re left join usuario co on co.re=u.supervisor left join gmg g on s.identificacao=g.codigo left join gmg_tipo gt on g.tipo=gt.id left join frota f on f.placa=s.identificacao left join veiculo v on v.vei_id=f.veiculo left join solicitacao sAnt on s.anterior=sAnt.id where s.id='{$id}'";

    $result = $mysqli->query($sql);

    $row = $result->fetch_assoc();

    if ($row['tipo'] === "1") {

        $diferenca_km = "";
        $km2 = "";

        if ($row['froKm'] === $row['antKm'] and $row['vPlaca'] === $row['antIdentificacao']) {
            $km2 = $row['antKm'];
        } else
        //  if ($row['vPlaca'] === $row['antIdentificacao'] and $row['froKm'] != $row['antKm']) 
        {
            $km2 =  $row['froKm'];
        }

        $diferenca_km = $row['km'] - $km2;

        $arr = array(
            "detalhe" => $row,
            "km_diferenca" => $diferenca_km,
            "kmAnt" => $km2,
            "historico" => historico($mysqli, $row['id']),
        );
    } else {

        $d30 = date('Y-m-d', strtotime('-30 days', strtotime(date('Y-m-d'))));
        $gmg = $row['vPlaca'];
        $sql_tempo = "select g.codigo as cod_gmg, g.identificacao gmg, SEC_TO_TIME(SUM(TIME_TO_SEC(ac.tempo))) as tempo from gmg_acoplamento ac inner join gmg g on g.codigo=ac.gmg_codigo inner join site s on s.id=ac.site inner join cn c on c.id=s.cn WHERE ac.gmg_codigo='{$gmg}' and data_inicio>='{$d30}' GROUP by cod_gmg";
        $row_tempo = $mysqli->query($sql_tempo)->fetch_assoc();

        $arr = array(
            "detalhe" => $row,
            "historico" => historico($mysqli, $row['id']),
            "tempo" => $row_tempo['tempo']

        );
    }

    $result->close();
    echo JsonEncodePAcentos::converter($arr);
    $mysqli->close();
}
function historico($mysqli, $id)
{
    $sql_historico = "SELECT sta.nome as status, sh.data as data, sh.hora as hora, sh.atualizacao as atualizacao, sta.ico as ico, concat(substring_index(u.nome, ' ', 1),' ') as nome from solicitacao_historico sh inner join solicitacao_status sta on sta.id=sh.status inner join usuario u on u.re=sh.re where sh.solicitacao='{$id}' order by sh.id";
    $result3 = $mysqli->query($sql_historico);
    $status = "";
    while ($row3 = $result3->fetch_array(MYSQLI_ASSOC)) {

        $status .= "<i class='" . $row3['ico'] . " text-left'></i>" . $row3['status'] . " " . $row3['atualizacao'] . "| <i class='icon-user'></i>" . $row3['nome'] . "<p>";
    }

    if ($status == "") {
        $status = "Solicitação sem histórico";
    }
    return $status;
}
function update($mysqli, $id, $obs, $re, $data, $hora, $status, $valor)
{

    //Aprovar solicitações de combustível
    $p = permissaoVerifica($mysqli, "2", $re);
    $solicitacao = dadosSolicitacao($mysqli, $id);
    $data_hora = $data . " " . $hora;

    if ($solicitacao['status'] === "6") {

        $valor_update = $valor;
        $status = "1";
    } else {
        $valor_update = $solicitacao['valor'];
    }

    $msg = "";
    $erro = "1";
    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else
    if ($valor_update === 0 || $valor_update === "0") {

        $msg = "<i class='icon-attention'></i> Necessário definir o valor da solicitação.";
    } else
    if ($solicitacao['status'] != "1" and $solicitacao['status'] != "6") {
        $msg = "<i class='icon-attention'></i> Essa solicitação não pode mais ser alterada.";
    } else
    if ($solicitacao['gestao'] === 1 and $solicitacao['tipo'] === 2 and ($re != 34821 or $re != 30127 or $re != 29819)) {
        $msg = "<i class='icon-attention'></i> Solicitações para GMGs devem ser aprovados somente por Alana Deivlan ou Bruno Rosse.";
    } else {
        $erro = "0";
        if ($status === "2") {
            $msg = "<i class='icon-ok-circle-1'></i> Solicitação aprovada com sucesso.";
        } else if ($status === "3") {
            $msg = "<i class='icon-ok-circle-1'></i> Solicitação negada com sucesso.";
        }
        $sql_insert = "INSERT INTO solicitacao_historico (re, solicitacao, status, data, hora, atualizacao) VALUES ('{$re}','{$id}', '{$status}','{$data}', '{$hora}', '{$data_hora}')";
        if ($mysqli->query($sql_insert)) {
            $id_hs = $mysqli->insert_id;

            $sql_update = "update solicitacao set status='{$status}', valor='{$valor_update}', obs='{$obs}', aprovacao='{$id_hs}' where id='{$id}'";
            $mysqli->query($sql_update);
        }
    }
    $mysqli->close();

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function dadosSolicitacao($mysqli, $id)
{
    $sql = "select s.id as id, s.tipo as tipo, s.status as status, s.valor as valor, u.gestao as gestao from solicitacao s inner join usuario u on u.re=s.solicitante where s.id='{$id}'";

    $result = $mysqli->query($sql);

    return $result->fetch_assoc();
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
