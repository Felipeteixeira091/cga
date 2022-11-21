<?php
session_start();

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";
require_once '../lib/PHPMailer/PHPMailerAutoload.php';
include_once "./frame/Email.php";

$re_sessao = $_SESSION["re"];
$data = date('Y-m-d');
$hora = date('H:i', time());
$uf_sessao = $_SESSION['uf'];

$acao = $txtTitulo['acao'];

$erro = "0";
$msg = "";

if ($acao === "dados") {
    $cod = $txtTitulo['cod'];

    dados($mysqli, $cod);
} else
if ($acao === "EstadoLista") {

    estadoLista($mysqli);
} else
if ($acao === "CNLista") {

    cnLista($mysqli, $uf_sessao, $re_sessao);
} else
if ($acao === "TipoLista") {

    tipoLista($mysqli);
} else
if ($acao === "tipoLista") {

    tipoLista($mysqli);
} else
if ($acao === "AtivoLista") {

    statusLista($mysqli);
} else
if ($acao === "CoordenadorLista") {

    coordenadorLista($mysqli, $re_sessao, $uf_sessao);
} else
if ($acao === "editaGMG") {

    $cod = $txtTitulo['cod'];
    $uf_gmg = $txtTitulo['uf'];
    $cn = $txtTitulo['cn'];
    $identificacao = addslashes(strtoupper($txtTitulo['identificacao']));
    $tipo = $txtTitulo['tipo'];
    $ativo = $txtTitulo['ativo'];
    $coordenador = $txtTitulo['coordenador'];

    editaGMG($mysqli, $re_sessao, $uf_sessao, $cod, $uf_gmg, $cn, $identificacao, $tipo, $ativo, $coordenador, $data, $hora);
} else
if ($acao === "transferenciaLista") {

    transferenciaLista($mysqli, $re_sessao);
} else 
if ($acao === "transfereGmg") {

    $colaborador = $txtTitulo['cod'];
    $tipo = $txtTitulo['tipo'];

    transfereGmg($mysqli, $tipo, $colaborador, $re_sessao, $data, $hora);
} else
if ($acao === "cadastroGMG") {

    $uf = $txtTitulo['uf'];
    $cn = $txtTitulo['cn'];
    $identificacao = addslashes(strtoupper($txtTitulo['identificacao']));
    $tipo = $txtTitulo['tipo'];
    $cartao = $txtTitulo['cartao'];
    $coordenador = $txtTitulo['coordenador'];

    cadastroGMG($mysqli, $re_sessao, $uf_sessao, $cn, $identificacao, $tipo, $cartao, $coordenador, $data, $hora);
} else 
if ($acao === "update") {

    $id = $txtTitulo['id'];
    $nome = addslashes(strtoupper($txtTitulo['nome']));
    $cn = $txtTitulo['cn'];
    $fabricante = $txtTitulo['fabricante'];
    $tipo = $txtTitulo['tipo'];
    $kva = $txtTitulo['kva'];
    $status = $txtTitulo['status'];

    update($mysqli, $id, $nome, $cn, $fabricante, $tipo, $kva, $status);
} else
if ($acao === "procura") {

    $txt = $txtTitulo['txt'];
    $cn = $txtTitulo['cn'];
    Procura($mysqli, $re_sessao, $txt, $cn, $uf_sessao);
} else
if ($acao === "dadosCartao") {

    dadosCartao($mysqli,  $txtTitulo['cod'], $re_sessao);
} else
if ($acao === "cartaoAltera") {

    $cod = $txtTitulo['cod'];
    $cartaoAtual =$txtTitulo['cartaoAtual'];
    $cartaoNovo = $txtTitulo['cartaoNovo'];
    $cartaoMotivo = addslashes($txtTitulo['cartaoMotivo']);

    cartaoAltera($mysqli, $re_sessao, $cod, $cartaoAtual, $cartaoNovo, $cartaoMotivo, $data, $hora);
} else
if ($acao === "cartaoLista") {

    cartaoLista($mysqli);
} else
if ($acao === "cartaoRemove") {
    $cod = $txtTitulo['cod'];
    $cartaoAtual = $txtTitulo['cartaoAtual'];

    cartaoRemove($mysqli, $re_sessao, $cod, $cartaoAtual, $data, $hora);
} else
if ($acao === "cartaoAtribui") {
    $cod = $txtTitulo['cod'];
    $cartaoAtual = $txtTitulo['cartaoAtual'];
    $cartaoNovo = $txtTitulo['cartaoNovo'];

    cartaoAtribui($mysqli, $re_sessao, $cod, $cartaoAtual, $cartaoNovo, $data, $hora);
} else
if ($acao === "cartaoDesbloqueio") {
    $id = $txtTitulo['id'];
    $cartaoAtual = $txtTitulo['cartaoAtual'];

    cartaoDesbloqueio($mysqli, $re_sessao, $id, $cartaoAtual, $data, $hora);
}

function cartaoDesbloqueio($mysqli, $re_sessao, $id, $cartaoAtual, $data, $hora)
{
    $erro = "1";
    $p = permissaoVerifica($mysqli, "83", $re_sessao);

    $verificaSolicitacao = $mysqli->query("select id from cartao where controle='{$cartaoAtual}' and status='1'")->num_rows;

    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else
    if ($verificaSolicitacao > 0) {
        $msg = "<i class='icon-attention'></i> Solicitação já realizada, aguarde.";
    } else {

        if ($mysqli->query("update cartao set status='1', finalidade='2' where controle='{$cartaoAtual}'")) {

            historico($mysqli, $re_sessao, $id, "15", $cartaoAtual, "", $data, $hora);

            $erro = "0";
            $msg = "<i class='icon-ok-circle-1'></i> Desbloqueio solicitado com sucesso.";
        } else {
            $msg = "<i class='icon-attention'></i> Erro ao efetuar solicitação.";
        }
    }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function dadosCartao($mysqli, $codigo, $re_resp)
{
    $sql = "select concat('GMG_',gt.nome,'_',g.identificacao) as gmg, g.codigo as cod, g.cartao as cartao from gmg g inner join gmg_tipo gt on gt.id=g.tipo WHERE g.codigo='{$codigo}'";

    $result = $mysqli->query($sql);
    $cartao = $result->fetch_array();

    $result->close();

    $p = permissaoVerifica($mysqli, "12", $re_resp);

    $arr = array("cartao" => $cartao, "permissao" => $p);

    echo JsonEncodePAcentos::converter($arr);

    $mysqli->close();
}
function cartaoAltera($mysqli, $re_sessao, $cod, $cartaoAtual, $cartaoNovo, $cartaoMotivo, $data, $hora)
{
    $erro = "1";
    $p = permissaoVerifica($mysqli, "11", $re_sessao);

    $vCartao = preg_match('/^[0-9]+$/', $cartaoNovo);

    $verificaCartao = $mysqli->query("select controle from cartao where controle='{$cartaoNovo}'")->num_rows;

    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else
    if (strlen($cartaoNovo) != 6 or $vCartao === 0) {
        $msg = "<i class='icon-attention'></i> O novo cartão informado é inválido.";
    } else
   if ($verificaCartao > 0) {
        $msg = "<i class='icon-attention'></i> O novo cartão informado já está cadastrado no sistema.";
    } else
    if ($cartaoNovo == $cartaoAtual) {
        $msg = "<i class='icon-attention'></i> O novo cartão atual e novo são iguais.";
    } else
    if (strlen($cartaoMotivo) < 10) {
        $msg = "<i class='icon-attention'></i> O motivo da troca é insuficiente.";
    } else {

        $sql = "insert into cartao (controle, obs, anterior, re_cadastro, status, data_cadastro, hora_cadastro, finalidade) values ('{$cartaoNovo}', '{$cartaoMotivo}', '{$cartaoAtual}','{$re_sessao}','1','{$data}','{$hora}','2')";

        if ($mysqli->query($sql)) {
            $sqlUp = "update gmg set cartao='{$cartaoNovo}' where codigo='{$cod}'";

            if ($mysqli->query($sqlUp)) {

                historico($mysqli, $re_sessao, $cod, "1", $cartaoAtual, $cartaoNovo, $data, $hora);

                $dados_html = $mysqli->query("select c.controle as controle, cad.nome as nome, cad.email as email, c.data_cadastro as data, c.hora_cadastro as hora, co.nome as colaborador, c.obs as motivo from cartao c inner join usuario cad on cad.re=c.re_cadastro inner join usuario co on co.cartao=c.controle where c.controle='{$cartaoNovo}'")->fetch_array();
                $dados_email = $mysqli->query("select cad.nome as nome, cad.email as email, c.data_cadastro as data, c.hora_cadastro as hora from cartao c inner join usuario cad on cad.re=c.re_cadastro inner join usuario co on co.cartao=c.controle where c.controle='{$cartaoNovo}'")->fetch_array();

                $sqlEmail = "select email, nome, tipo from email_endereco where finalidade='adm_cartao'";
                $assunto = "Novo cartão cadastrado: " . $cartaoNovo;

                enviar($mysqli, $sqlEmail, $dados_email, $assunto, bodyHtml_ADM_Cartao($dados_html));

                $erro = "0";
                $msg = "<i class='icon-ok-circle-1'></i> Alteração realizada com sucesso.";
            } else {
                $msg = "<i class='icon-attention'></i> Erro ao efetuar alteração.";
            }
        } else {
            $msg = "<i class='icon-attention'></i> Erro ao efetuar alteração.";
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function cartaoLista($mysqli)
{
    $sql = "select c.controle as cartao from cartao c where ((select count(cartao) from usuario WHERE cartao=c.controle)+(select count(cartao) from gmg WHERE cartao=c.controle))=0 order by c.controle asc";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function cartaoRemove($mysqli, $re_sessao, $cod, $cartaoAtual, $data, $hora)
{
    $erro = "1";
    $p = permissaoVerifica($mysqli, "12", $re_sessao);

    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else {

        if ($mysqli->query("update gmg set cartao='' where cartao='{$cartaoAtual}' and codigo='{$cod}'")) {

            historico($mysqli, $re_sessao, $cod, "1", $cartaoAtual, "", $data, $hora);

            $erro = "0";
            $msg = "<i class='icon-ok-circle-1'></i> Cartão removido com sucesso.";
        } else {
            $msg = "<i class='icon-attention'></i> Erro ao efetuar alteração.";
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function cartaoAtribui($mysqli, $re_sessao, $cod, $cartaoAtual, $cartaoNovo, $data, $hora)
{
    $erro = "1";
    $p = permissaoVerifica($mysqli, "12", $re_sessao);

    $verificaCartao = $mysqli->query("select cartao from gmg where cartao='{$cartaoNovo}'")->num_rows;

    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else
   if ($verificaCartao > 0) {
        $msg = "<i class='icon-attention'></i> O novo cartão informado já está atribuído a outro colaborador.";
    } else
    if ($cartaoNovo == $cartaoAtual) {
        $msg = "<i class='icon-attention'></i> O novo cartão atual e novo são iguais.";
    } else {
        $sql = "update gmg set cartao='{$cartaoNovo}' where codigo='{$cod}'";
        if ($mysqli->query($sql)) {

            historico($mysqli, $re_sessao, $cod, "1", $cartaoAtual, $cartaoNovo, $data, $hora);

            $erro = "0";
            $msg = "<i class='icon-ok-circle-1'></i> Alteração realizada com sucesso.";
        } else {
            $msg = "<i class='icon-attention'></i> Erro ao efetuar alteração.";
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function dados($mysqli, $cod)
{
    $sql = "select g.estado as estado, g.cn as cn, g.codigo as cod, g.identificacao as identificacao, g.supervisor as coordenador, g.status as ativo, g.tipo as tipo, gt.nome as tipoNome from gmg g inner join gmg_tipo gt on gt.id=g.tipo where g.codigo='{$cod}'";

    $result = $mysqli->query($sql);
    $gmg = $result->fetch_array(MYSQLI_ASSOC);

    echo JsonEncodePAcentos::converter($gmg);

    $result->close();
    $mysqli->close();
}
function cnLista($mysqli, $uf_sessao, $re_sessao)
{
    $p = permissaoVerifica($mysqli, "30", $re_sessao);
    if ($p === 0) {
        $sql = "select id, nome from cn where uf='{$uf_sessao}'";
    } else {
        $sql = "select id, nome from cn";
    }
    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function estadoLista($mysqli)
{
    $sql = "select id, sigla from uf";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function statusLista($mysqli)
{
    $sql = "select id, nome from gmg_status";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function tipoLista($mysqli)
{
    $sql = "select id, nome from gmg_tipo";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function coordenadorLista($mysqli, $re_sessao, $uf)
{

    $p = permissaoVerifica($mysqli, "23", $re_sessao);

    if ($p > 0) {

        $sql = "select concat(cn.nome,'-',u.nome) as nome, u.re as re from permissao p inner join usuario u on u.re=p.colaborador inner join cn on cn.id=u.cn WHERE p.funcao='4' order by u.estado, cn.nome, u.nome";
    } else {
        $sql = "select u.nome as nome, u.re as re from permissao p inner join usuario u on u.re=p.colaborador WHERE p.funcao='4' and u.estado='{$uf}' order by u.nome";
    }

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function cadastroGMG($mysqli, $re_sessao, $uf, $cn, $identificacao, $tipo, $cartao, $coordenador, $data, $hora)
{
    $p = permissaoVerifica($mysqli, "24", $re_sessao);
    $p2 = permissaoVerifica($mysqli, "25", $re_sessao);

    $usuario = $mysqli->query("select u.re as re, u.nome as nome, u.estado as estado, uf.sigla as uf, u.cn as cn, cn.nome as cn1 from usuario u inner join cn on cn.id=u.cn inner join uf on uf.id=u.estado where re='{$re_sessao}'")->fetch_array(MYSQLI_ASSOC);

    $erro = "1";
    $tempo = 0;
    $codigo = "eqp" . substr(md5($identificacao . $data . date('H:i:s') . $re_sessao), 0, -1);
    $vCartao = cartaoVerifica($mysqli, $cartao);

    if ($p === 0) {
        $msg = "Você não tem permissão";
    } else
    if ($uf === "0") {
        $msg = "Necessário selecionar o estado.";
    } else
    if ($p2 === 0 and $usuario['estado'] != $uf) {
        $msg = "você só pode selecionar o estado: " . $usuario['uf'] . ".";
    } else
    if ($cn === "0") {
        $msg = "Necessário selecionar o cn.";
    } else
    if ($p2 === 0 and $usuario['cn'] != $cn) {
        $msg = "você só pode selecionar o cn: " . $usuario['cn1'] . ".";
    } else
    if ($identificacao === "" || strlen($identificacao) < 3) {
        $msg = "A identificação é inválida.";
    } else
    if (identificacaoVerifica($mysqli, $identificacao) > 0) {
        $msg = "A identificação informada já está cadastrada.";
    } else 
    if ($tipo === "0") {
        $msg = "Necessário selecionar o tipo.";
    } else
    if (preg_match('/^([1-9][0-9][0-9][0-9][0-9][0-9])$/', $cartao) === 0) {
        $msg = "O número de controle (Cartão) informado, é inválido.";
    } else
    if ($vCartao != "0") {
        $tempo = 3000;
        $msg = $vCartao;
    } else
    if ($coordenador === "0") {
        $msg = "Necessário selecionar o coordenador responsável.";
    } else
    if ($p2 === 0 and $usuario['re'] != $coordenador) {
        $msg = "você só pode selecionar o coordenador: " . $usuario['nome'] . ".";
    } else
    if (codigoVerifica($mysqli, $codigo) > 0) {
        $msg = "Duplicidade de código, tente novamente.";
    } else {

        $sql = "insert into gmg (codigo, identificacao, estado, supervisor, cn, tipo, cartao, re_cadastro, status, data_cadastro, hora_cadastro) values ('{$codigo}', '{$identificacao}', '{$uf}', '{$coordenador}', '{$cn}', '{$tipo}', '{$cartao}', '{$re_sessao}', '2', '{$data}','{$hora}')";

        if ($mysqli->query($sql)) {

            $obs = "CARTÃO EQUIPAMENTO: " . $codigo;
            $sql_cartao = "insert into cartao (controle, obs, re_cadastro, status, data_cadastro, hora_cadastro, finalidade) values ('{$cartao}', '{$obs}', '{$re_sessao}','1','{$data}','{$hora}','2')";
            if ($mysqli->query($sql_cartao)) {

                $erro = "0";
                $msg = "Cadastro realizado com sucesso.";
            }
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg, "tempo" => $tempo);
    echo JsonEncodePAcentos::converter($arr);
}
function editaGMG($mysqli, $re_sessao, $uf_sessao, $cod, $uf, $cn, $identificacao, $tipo, $ativo, $coordenador, $data, $hora)
{
    $erro = "1";

    $p = permissaoVerifica($mysqli, "22", $re_sessao);
    $p2 = permissaoVerifica($mysqli, "28", $re_sessao);
    $usuario = $mysqli->query("select u.re as re, u.nome as nome, u.estado as estado, uf.sigla as uf, u.cn as cn, cn.nome as cn1 from usuario u inner join cn on cn.id=u.cn inner join uf on uf.id=u.estado where re='{$re_sessao}'")->fetch_array(MYSQLI_ASSOC);

    $identificacao = eliminaespaco($identificacao);

    if ($p === 0) {
        $msg = "Você não tem permissão para editar GMG's/Equipamentos.";
    } else 
    if ($cod === "0" or !$cod) {
        $msg = "Erro ao carregar dados, atualize a página.";
    } else
    if ($uf === "0") {
        $msg = "Necessário selecionar o estado.";
    } else
    if ($uf != $uf_sessao and $p2 === 0) {
        $msg = "você só pode selecionar o estado: " . $usuario['uf'] . ".";
    } else
    if ($cn === "0" or !$cn) {
        $msg = "Necessário selecionar o CN.";
    } else
    if ($identificacao === "" or strlen($identificacao) < 3) {
        $msg = "A identificação informada é inválida.";
    } else
    if ($tipo === "0" or !$tipo) {
        $msg = "Necessário selecionar o tipo.";
    } else
    if ($ativo === "0" or !$ativo) {
        $msg = "Necessário selecionar o status.";
    } else
    if ($coordenador === "0" or !$coordenador) {
        $msg = "Necessário selecionar o coordenador.";
    } else {
        if ($usuario['re'] === $coordenador) {
            $cpCoordenador = "supervisor='{$coordenador}'";
        } else
        if ($p2 > 0 and $usuario['re'] != $coordenador) {
            $cpCoordenador = "supervisor='{$coordenador}'";
        } else
        if ($p2 === 0 and $usuario['re'] != $coordenador) {
            $cpCoordenador = "transferencia='{$coordenador}'";
        }

        $sql = "update gmg set identificacao='{$identificacao}', estado='{$uf}', " . $cpCoordenador . ", cn='{$cn}', tipo='{$tipo}', status='{$ativo}' where codigo='{$cod}'";

        //Armazena dados antes da mudança
        $d = $mysqli->query("select g.codigo as cod,g.estado as ufId, uf.sigla as uf, g.cn as cnId, cn.nome as cn, g.identificacao as identificacao,g.tipo as tipoId, gt.nome as tipo, g.supervisor as coordenadorRe, c.nome as coordenador, g.status as statusId, if(g.status=2,'ATIVO','DESATIVADO') as status from gmg g left join uf uf on uf.id=g.estado left join cn cn on cn.id=g.cn inner join gmg_tipo gt on gt.id=g.tipo left join usuario c on c.re=g.supervisor where g.codigo='{$cod}'")->fetch_array(MYSQLI_ASSOC);

        if ($mysqli->query($sql)) {
            $d2 = $mysqli->query("select g.codigo as cod,g.estado as ufId, uf.sigla as uf, g.cn as cnId, cn.nome as cn, g.identificacao as identificacao,g.tipo as tipoId, gt.nome as tipo, g.supervisor as coordenadorRe, c.nome as coordenador, g.status as statusId, if(g.status=2,'ATIVO','DESATIVADO') as status from gmg g left join uf uf on uf.id=g.estado left join cn cn on cn.id=g.cn inner join gmg_tipo gt on gt.id=g.tipo left join usuario c on c.re=g.supervisor where g.codigo='{$cod}'")->fetch_array(MYSQLI_ASSOC);


            if ($d['ufId'] != $d2['ufId']) {
                historico($mysqli, $re_sessao, $cod, "4", $d['uf'], $d2['uf'], $data, $hora);
            }
            if ($d['cnId'] != $d2['cnId']) {
                historico($mysqli, $re_sessao, $cod, "5", $d['cn'], $d2['cn'], $data, $hora);
            }
            if ($d['identificacao'] != $d2['identificacao']) {
                historico($mysqli, $re_sessao, $cod, "13", $d['identificacao'], $d2['identificacao'], $data, $hora);
            }
            if ($d['tipoId'] != $d2['tipoId']) {
                historico($mysqli, $re_sessao, $cod, "14", $d['tipo'], $d2['tipo'], $data, $hora);
            }
            if ($d['statusId'] != $d2['statusId']) {
                historico($mysqli, $re_sessao, $cod, "12", $d['status'], $d2['status'], $data, $hora);
            }

            $erro = "0";
            $msg = "Operação realizada com sucesso.";
        }
    }

    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function transferenciaLista($mysqli, $re)
{
    $sql = "select g.codigo as cod, g.identificacao as identificacao, c.nome as coordenador, cn.nome as cn from gmg g left join usuario c on c.re=g.supervisor left join cn cn on cn.id=g.cn where g.transferencia='{$re}'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
    $mysqli->close();
}
function transfereGmg($mysqli, $tipo, $cod, $novoCoordenador, $data, $hora)
{
    $erro = "1";
    $msg = "Erro";

    if ($tipo === "aceita") {
        $sql = "update gmg set supervisor='{$novoCoordenador}', transferencia='0' where codigo='{$cod}'";

        $atual = $mysqli->query("select c.re as re, c.nome as nome from gmg g inner join usuario c on c.re=g.supervisor where g.codigo='{$cod}'")->fetch_array(MYSQLI_ASSOC);
        $novo = $mysqli->query("select u.re as re, u.nome as nome from usuario u where u.re='{$novoCoordenador}'")->fetch_array(MYSQLI_ASSOC);

        $atual_coordenador = $atual['nome'] . " [" . $atual['re'] . "]";
        $novo_coordenador = $novo['nome'] . " [" . $novo['re'] . "]";

        if ($mysqli->query($sql)) {

            historico($mysqli, $atual['re'], $cod, "10", $atual_coordenador, $novo_coordenador, $data, $hora);

            $erro = "0";
            $msg = "Ítem aceito com sucesso.";
        } else {
            $msg = "Erro ao aceitar ítem.";
        }
    } else {
        $sql = "update gmg set transferencia='0' where codigo='{$cod}'";

        if ($mysqli->query($sql)) {
            $erro = "0";
            $msg = "Ítem recusado com sucesso.";
        } else {
            $msg = "Erro ao recusar ítem.";
        }
    }
    $mysqli->close();
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function update($mysqli, $id, $nome, $cn, $fabricante, $tipo, $kva, $status)
{
    if (!$id || $nome === "" || $cn === "0" || $fabricante === "0" || $tipo === "0" || $kva === "0" || !$kva || $status === "0") {

        $erro = "1";
        $msg = "Um campo obrigatório não foi preenchido!";
    } else {
        $sql = "update gmg set identificacao='{$nome}', cn='{$cn}', fabricante='{$fabricante}', tipo='{$tipo}', kva='{$kva}', status='{$status}' where id='{$id}'";

        if ($mysqli->query($sql)) {

            $erro = 0;
            $msg = "GMG atualizado com sucesso!";
        } else {
            $erro = 0;
            $msg = "Erro ao atualizar GMG!";
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function identificacaoVerifica($mysqli, $identificacao)
{
    $num = $mysqli->query("select id from gmg where identificacao='{$identificacao}'")->num_rows;
    return $num;
}
function codigoVerifica($mysqli, $codigo)
{
    $num = $mysqli->query("select id from gmg where codigo='{$codigo}'")->num_rows;
    return $num;
}
function cartaoVerifica($mysqli, $cartao)
{
    $G = $mysqli->query("select id from gmg where cartao='{$cartao}'")->num_rows;
    $U = $mysqli->query("select id from usuario where cartao='{$cartao}'")->num_rows;
    $C = $mysqli->query("select id from cartao where controle='{$cartao}'")->num_rows;

    $retorno = "0";

    if ($U > 0) {
        $dados = $mysqli->query("select u.re as re, u.nome as nome, u.estado as estado, uf.sigla as uf, u.cn as cn, cn.nome as cn1 from usuario u inner join cn on cn.id=u.cn inner join uf on uf.id=u.estado where u.cartao='{$cartao}'")->fetch_array(MYSQLI_ASSOC);

        $retorno = "O cartão informado já está cadastrado e atribuído ao colaborador " . $dados['nome'] . ", do CN " . $dados['cn1'];
    } else 
    if ($G > 0) {
        $dados = $mysqli->query("select g.identificacao, gt.nome as tipo, uf.sigla as uf, cn.nome as cn from gmg g inner join gmg_tipo gt on gt.id=g.tipo left join cn on cn.id=g.cn left join uf on uf.id=g.estado where g.cartao='{$cartao}'")->fetch_array(MYSQLI_ASSOC);

        $retorno = "O cartão informado já está cadastrado e atribuído ao " . $dados['tipo'] . "_" . $dados['identificacao'] . ", do CN " . $dados['cn'];
    } else 
    if ($C > 0) {
        $retorno = "O cartão informado já está cadastrado e desativado no sistema, verifique o número de controle e tente novamente.";
    }

    return $retorno;
}
function Procura($mysqli, $re_sessao, $txt, $cn, $uf)
{
    $txt = strtoupper($txt);
    $p = permissaoVerifica($mysqli, "23", $re_sessao);
    $p2 = permissaoVerifica($mysqli, "65", $re_sessao);

    $where = "";
    if ($txt != "") {
        $where .= " (g.identificacao LIKE '%{$txt}%' or f.nome LIKE '%{$txt}%' or g.cartao like '%" . $txt . "%') ";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($cn > 0) {
        $where .= " g.cn='{$cn}' ";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    if ($p > 0) {

        $sql = "select g.id as id, g.codigo as codigo, g.identificacao as nome, IF(char_length(g.cartao) = 6 ,g.cartao,'S/CARTÃƒO') as cartao, gt.nome as tipo, f.nome as fabricante, u.nome as coordenador, cn.nome as cn, gs.nome as status from gmg g left join gmg_status gs on gs.id=g.status left join gmg_fabricante f on f.id=g.fabricante left join gmg_tipo gt on gt.id=g.tipo left join usuario u on u.re=g.supervisor left join cn on cn.id=g.cn WHERE" . $where . " order by g.cn, g.identificacao asc";
    } else
    if ($p2 > 0) {

        $sql = "select g.id as id, g.codigo as codigo, g.identificacao as nome, IF(char_length(g.cartao) = 6 ,g.cartao,'S/CARTÃƒO') as cartao, gt.nome as tipo, f.nome as fabricante, u.nome as coordenador, cn.nome as cn, g.estado as estado, gs.nome as status from gmg g left join gmg_status gs on gs.id=g.status left join gmg_fabricante f on f.id=g.fabricante left join gmg_tipo gt on gt.id=g.tipo left join usuario u on u.re=g.supervisor left join cn on cn.id=g.cn WHERE g.estado='{$uf}' and " . $where . " order by g.cn, g.identificacao asc";
    } else {

        $sql = "select g.id as id, g.codigo as codigo, g.identificacao as nome, IF(char_length(g.cartao) = 6 ,g.cartao,'S/CARTÃƒO') as cartao, gt.nome as tipo, f.nome as fabricante, u.nome as coordenador, cn.nome as cn, gs.nome as status from gmg g left join gmg_status gs on gs.id=g.status left join gmg_fabricante f on f.id=g.fabricante left join gmg_tipo gt on gt.id=g.tipo left join usuario u on u.re=g.supervisor left join cn on cn.id=g.cn WHERE g.supervisor='{$re_sessao}' and " . $where . " order by g.cn, g.identificacao asc";
    }

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
function eliminaespaco($variavel)
{
    $variavel = trim($variavel);
    $variavel = preg_replace('/s(?=s)/', '', $variavel);
    $variavel = preg_replace('/[nrt]/', ' ', $variavel);
    return $variavel;
}
function historico($mysqli, $re, $re_alterado, $tipo, $valorAtual, $valorNovo, $data, $hora)
{

    $sql = "insert into alteracao (re, re_alterado, tipo, valor_anterior, valor_novo, data, hora) values ('{$re}', '{$re_alterado}', '{$tipo}', '{$valorAtual}', '{$valorNovo}', '{$data}', '{$hora}')";

    $mysqli->query($sql);
}
