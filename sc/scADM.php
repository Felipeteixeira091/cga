<?php
session_start();

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

$re_sessao = $_SESSION["re"];
$data = date('Y-m-d');
$hora = date('H:i', time());
$uf = $_SESSION['uf'];

$acao = $txtTitulo['acao'];

$erro = "0";
$msg = "";

if ($acao === "cartaoLista") {

    cartaoLista($mysqli, $uf);
} else
if ($acao === "cartaoAprov") {

    $retorno;
    $cartao = $txtTitulo['cartao'];

    $p = permissaoVerifica($mysqli, "29", $re_sessao);

    if ($p === 0) {

        $msg = "Você não tem permissão para desbloquear cartão.";
        $retorno = array("erro" => "1", "msg" => $msg);
    } else {

        $body = monta_body($mysqli, $data, $hora, $re_sessao, $cartao);
        $email = email($mysqli, $re_sessao, $body['body'], $cartao);
        $retorno = $email;
    }
    $mysqli->close();

    echo JsonEncodePAcentos::converter($retorno);
} else
if ($acao === "cartaoRecus") {

    $cartao = $txtTitulo['cartao'];
    cartaoRecus($mysqli, $cartao);
} else
if ($acao === "cartaoDestinatario") {
    cartaoDestinatario($mysqli, $re_sessao);
} else
if ($acao === "cartaoDestinatarioAdd") {

    $nome = $txtTitulo['nome'];
    $email = $txtTitulo['email'];
    $tipo = $txtTitulo['tipo'];

    cartaoDestinatarioAdd($mysqli, $nome, $email, $tipo, $re_sessao);
} else 
if ($acao === "cartaoDestinatarioRemove") {

    $id = $txtTitulo['id'];

    cartaoDestinatarioRemove($mysqli, $id);
}

function cartaoLista($mysqli, $uf)
{
    $sql = "select c.controle as cartao, u.nome as colaborador_nome, u.re as colaborador_re, coU.nome coordenadorU, cnU.nome as cnU, concat(gt.nome,'_',g.identificacao) as gmg, coG.nome as coordenadorG, cnG.nome as cnG, c.finalidade as finalidade from cartao c left join usuario u on u.cartao=c.controle left join gmg g on g.cartao=c.controle left join usuario coU on coU.re=u.supervisor left join usuario coG on coG.re=g.supervisor left join cn cnG on cnG.id=g.cn left join cn cnU on cnU.id=u.cn left join gmg_tipo gt on gt.id=g.tipo where c.status=1 and coU.gestao!=2";

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
function monta_body($mysqli, $data, $hora, $re_resp, $cartao)
{

    $finalidade = $mysqli->query("select c.finalidade as finalidade from cartao c where c.controle='{$cartao}'")->fetch_array(MYSQLI_ASSOC);

    if ($finalidade['finalidade'] === "1") {

        $sql = "select c.controle as cartao, u.nome as colaborador, u.re as re, co.nome as coordenador, cn.nome as cn, if(c.finalidade=1,'COLABORADOR','GMG/EQUIPAMENTO') as finalidade from cartao c left join usuario u on u.cartao=c.controle left join usuario co on co.re=u.supervisor left join cn on cn.id=u.cn where c.controle='{$cartao}'";
    } else {
        $sql = "select c.controle as cartao, concat(gt.nome,'_',g.identificacao) as identificacao, co.nome as coordenador, cn.nome as cn, if(c.finalidade=1,'COLABORADOR','GMG/EQUIPAMENTO') as finalidade from cartao c left join gmg g on g.cartao=c.controle left join gmg_tipo gt on gt.id=g.tipo left join usuario co on co.re=g.supervisor left join cn on cn.id=g.cn where c.controle='{$cartao}'";
    }


    $sql_dados = "select u.email as email, u.nome as nome, u.telefone as telefone from usuario u where re='" . $re_resp . "'";

    $dados = $mysqli->query($sql)->fetch_array(MYSQLI_ASSOC);
    $result2 = $mysqli->query($sql_dados);
    $row2 = $result2->fetch_assoc();
    $tel = $row2['telefone'];
    $telefone = "(" . $tel[0] . $tel[1] . ") " . $tel[2] . "-" . $tel[3] . $tel[4] . $tel[5] . $tel[6] . "-" . $tel[7] . $tel[8] . $tel[9] . $tel[10];


    if ($finalidade['finalidade'] === "1") {
        $nome = strtoupper(utf8_encode($dados['colaborador'])) . " [" . $dados['re'] . "]";
    } else {

        $nome = strtoupper(utf8_encode($dados['identificacao']));
    }

    $table = "<table>"
        . "<thead><tr>"
        . "<th>CARTÃO</th>"
        . "<th>COLABORADOR/EQUIPAMENTO</th>"
        . "<th>COORDENADOR</th>"
        . "<th>CN</th>"
        . "<th>FINALIDADE</th>"
        . "</tr></thead>";
    $table .= "<td>" . $cartao . "</td>";
    $table .= "<td>" . $nome . "</td>";
    $table .= "<td>" . strtoupper(utf8_encode($dados['coordenador'])) . "</td>";
    $table .= "<td>" . $dados['cn'] . "</td>";
    $table .= "<td>" . $dados['finalidade'] . "</td>";
    $table .= '</table><br>';
    $pagina = '<html>
        <head>
            <meta charset="UTF-8">
            <title></title>
    <style>*{
        font-family: Helvetica;
    }
    .palco{
        width: 90%;
        margin: 4px auto;
        color: #444;
        border: 2px solid #ccc;
        padding: 4px;
        line-height: 10px;
    }
    .ln_assinatura{color:#878a85;line-height: 10px";font-size: 10px;
    }
    h1{
        text-align: center;
        font-size: 18px;
    }
    h2{
        text-align: center;
        color: #0016b0;
        font-size: 16px;
    }
    p.sub-titulo{
        font-size: 18px;
    }
    .direita{
        text-align: right;
        color:#878a85;
    }
    .center{
        text-align: center;
    }
    table{
        width: 100%;
        font-size: 12px;
        border-collapse: collapse;
        margin-top: 2px;
    }
    table th{
        font-size: 14px;
        font-weight: normal;
        background-color: #17A2B8;
        color:#FFFFFF;
        border: none}
    table td{
        font-size: 12px;
        font-weight: normal;
        background-color: transparent;
        color: #333333;
        text-align: center;
    }
    table tr:nth-child(even) {
        background: aliceblue;
    }
    table tfoot tr td{
        font-size: 14px;
        font-weight: normal;
        background-color: #444;
        color: #ccc;
        border: none
    }
    .pdf_total_pago th{
        background-color: darkseagreen;
        color: forestgreen;
    }
    .pdf_total_pendente th{
        background-color: pink;
        color: tomato;
    }
    .pdf_parcelas th{
        background-color: #444;
        color: #e9e9e9;
    }
          </style>
        </head>
        <body>';

    $html = "
         <div class='palco'>
             <h1>Desbloqueio de cartão</h1>
             <p class='center sub-titulo'>Favor realizar o desbloqueio do cartão abaixo, sem recarga mensal.
             </p>" . $table . "</div>" .
        "<p class='direita'> Data da envio: " . $data . " " . $hora . "</p>
             <p class='ln_assinatura'>Responsável pelo envio: <strong>" . $row2['nome'] . "</strong>
    <br>Telefone: " . $telefone . "
    <br>E-mail:" . $row2['email'] . "
    </body></html>";

    $body = $pagina . $html;

    $retorno = array(
        "body" => $body
    );

    return $retorno;
}
function email($mysqli, $re, $body, $cartao)
{
    $mail = new PHPMailer;
    $mail->isSMTP();

    $resp = $mysqli->query("select u.email as email, e.ema_senha as senha, e.ema_smtp as smtp, u.nome as nome, u.re as re, uf.sigla as uf from usuario u inner join email e on e.ema_re=u.re inner join uf uf on uf.id=u.estado where u.re='{$re}'")->fetch_assoc();

    $finalidade = $mysqli->query("select c.finalidade as finalidade from cartao c where c.controle='{$cartao}'")->fetch_array(MYSQLI_ASSOC);

    if ($finalidade['finalidade'] === "1") {

        $sql = "select co.email as email, co.nome as nome from cartao c left join usuario u on u.cartao=c.controle left join usuario co on co.re=u.supervisor left join cn on cn.id=u.cn where c.controle='{$cartao}'";
    } else {
        $sql = "select co.email as email, co.nome as nome from cartao c left join gmg g on g.cartao=c.controle left join usuario co on co.re=g.supervisor left join cn on cn.id=g.cn where c.controle='{$cartao}'";
    }

    $coordenador = $mysqli->query($sql)->fetch_array(MYSQLI_ASSOC);

    $mail->Host = $resp['smtp']; //SMTP
    $mail->SMTPAuth = true; // ATIVAR AUTENTICAÇÃO
    $mail->Username = $resp['email']; // USUÁRIO
    $mail->Password = $resp['senha']; // SENHA
    $mail->SMTPSecure = 'tls'; // tls ou ssl
    $mail->CharSet = "utf-8";
    $mail->Encoding = 'base64';
    $mail->Port = 587;

    $mail->setFrom($resp['email'], $resp['nome']); //E-mail origem

    $destino = "select email, nome, colaborador, tipo from email_endereco where finalidade='cartao' and colaborador='{$re}'";

    if ($result = $mysqli->query($destino)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $tipo = $row['tipo'];
            if ($tipo === "para") {
                $mail->addAddress($row['email'], $row['nome']); //Destinatário
            } else 
            if ($tipo === "cc") {
                $mail->addCC($row['email'], $row['nome']); //Cópia
            } else {
                $mail->addBCC($row['email'], $row['nome']); //Cópia oculta
            }
        }
    }
    $mail->addBCC("ftsilva@icomontecnologia.com.br", "Felipe Teixeira"); //Cópia oculta
    //   $mail->addCC($coordenador['email'], $coordenador['nome']); //Cópia

    $mail->isHTML(true);
    $mail->Subject = "Desbloqueio de cartão: " . $resp['uf']; // Assunto e-mail
    $mail->Body = $body;
    $mail->AltBody = $body;

    $erro = "1";
    if (!$mail->send()) {
        $msg = "Solicitação não enviada:" . $mail->ErrorInfo;
    } else {
        $msg = "Solicitação enviada com sucesso.";
        $erro = "0";

        $mysqli->query("update cartao set status=0, desbloqueio='{$re}' where controle='{$cartao}'");
    }
    $arr = array("erro" => $erro, "msg" => $msg);

    return $arr;
}

function cartaoRecus($mysqli, $cartao)
{
    $sql = "update cartao set status='0' where controle='{$cartao}'";

    $erro = "1";
    $msg = "";
    if ($mysqli->query($sql)) {
        $erro = "0";
        $msg = "Cartão negado com sucesso.";
    } else {
        $msg = "Erro ao negar aprovação.";
    }

    $mysqli->close();

    $retorno = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($retorno);
}
function cartaoDestinatario($mysqli, $re)
{
    $sql = "select id, email, nome, colaborador, tipo from email_endereco where finalidade='cartao' and colaborador='{$re}'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function cartaoDestinatarioAdd($mysqli, $nome, $email, $tipo, $re)
{
    $vEmail = $mysqli->query("select id from email_endereco where finalidade='cartao' and email='{$email}' and colaborador='{$re}'")->num_rows;

    $erro = "1";
    if (strlen($nome) < 5 or $nome === "" or !$nome) {
        $msg = "<i class='icon-attention'></i> O nome informado é inválido.";
    } else
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "<i class='icon-attention'></i> O e-mail informado é inválido.";
    } else
    if ($vEmail > 0) {
        $msg = "<i class='icon-attention'></i> O e-mail informado já está cadastrado.";
    } else
    if ($tipo === "0") {
        $msg = "<i class='icon-attention'></i> Selecione um tipo de destinatário.";
    } else {

        $sql = "insert into email_endereco (email, nome, colaborador, tipo, finalidade) values ('{$email}','{$nome}','{$re}','{$tipo}','cartao')";

        if ($mysqli->query($sql)) {
            $erro = "0";
            $msg = "<i class='icon-ok-1'></i> Destinatário cadastrado com sucesso.";
        } else {
            $msg = "<i class='icon-attention'></i> erro ao adicionar destinatário.";
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function cartaoDestinatarioRemove($mysqli, $id)
{
    $sql = "delete from email_endereco WHERE id='{$id}'";
    $erro = "1";
    if ($mysqli->query($sql)) {

        $msg = "<i class='icon-ok-1'></i> Destinatário removida com sucesso.";
        $erro = "0";
    } else {
        $msg = "Erro ao remover destinatário.";
    }

    $arr = array("erro" => $erro, "msg" => $msg);

    echo JsonEncodePAcentos::converter($arr);
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
function historico($mysqli, $re, $re_alterado, $tipo, $valorAtual, $valorNovo, $data, $hora)
{

    $sql = "insert into alteracao (re, re_alterado, tipo, valor_anterior, valor_novo, data, hora) values ('{$re}', '{$re_alterado}', '{$tipo}', '{$valorAtual}', '{$valorNovo}', '{$data}', '{$hora}')";

    $mysqli->query($sql);
}
