<?php
session_start();
$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);

$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());

$acao = $txtTitulo['acao'];

if ($acao === "lista") {

    $regiao = regiao($mysqli, $re);
    //echo $regiao['regiao'];
    lista_sol_envio($mysqli, $regiao['gestao'], $re);
} else
if ($acao === "update") {
    $id = $txtTitulo['id'];
    $valor = $txtTitulo['valor'];
    $valor_solicitado = $txtTitulo['valor_solicitado'];

    if (!$valor || $valor === "" || $valor === "0" || $valor === 0) {
        $erro = "1";
        $msg = "O novo valor não foi informado ou é inválido!";
    } else {
        $obs = "/////VALOR SOLICITADO: " . $valor_solicitado . " | VALOR APROVADO: " . $valor;
        $obs_mysql = obter_obs($mysqli, $id);

        $obs = $obs_mysql . $obs;

        $sql = update_valor($mysqli, $id, $valor, $obs, $re);
    }
} else
if ($acao === "envia") {

    $retorno;
    $re_resp = $_SESSION["re"];

    $p = permissao($mysqli, "3", $re_resp);

    if ($p === 0) {

        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
        $retorno = array("erro" => "1", "msg" => $msg);
    } else {

        $gestao = regiao($mysqli, $re_resp);
        $notas = qtd_notas($mysqli, $gestao['gestao']);

        if ($notas['qtd'] > 0) {

            $body = monta_body($mysqli, $data, $re_resp, $gestao['gestao']);
            $email = email($mysqli, $re_resp, $body['body'], $body['notas']);

            if ($email['erro'] === "0") {

                $update = update($mysqli, $body['notas'], $re_resp, $data, $hora, date("Y-m-d H:i"));
            }
            $retorno = $email;
        } else {
            $retorno = $notas;
        }
    }

    echo JsonEncodePAcentos::converter($retorno);
    $mysqli->close();
} else
if ($acao === "qtdEnvio") {

    $regiao = regiao($mysqli, $re);

    qtdEnvio($mysqli, $regiao['gestao'], $re);
}
function regiao($mysqli, $re)
{
    $sql = "select cn.regiao as regiao, u.gestao as gestao from usuario u inner join cn on cn.id=u.cn where u.re='{$re}'";
    $result = $mysqli->query($sql)->fetch_array();
    $regiao = $result;

    return $regiao;
}

/////////////////  ---->ENVIO<-------------///////////////////
function update($mysqli, $notas, $re_resp, $data, $hora, $dh)
{
    $qtd = count($notas);
    if ($qtd > 0) {

        for ($i = 0; $i < $qtd; $i++) {

            $nota = $notas[$i];

            $sqlDados = "select id, tipo, km, identificacao from solicitacao where id='{$nota}'";
            $result = $mysqli->query($sqlDados);
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if ($row['tipo'] === "1") {
                $mysqli->query("update frota set km='{$row['km']}' where placa='{$row['identificacao']}'");
            }

            $sql_up = "update solicitacao set status='4' where id='{$nota}'";
            $sql_vid = "insert into solicitacao_historico (re, solicitacao, status, atualizacao, data, hora) values ('{$re_resp}','{$nota}', '4', '{$dh}', '{$data}', '{$hora}')";

            $mysqli->query($sql_up);
            $mysqli->query($sql_vid);
        }
    }
    return $qtd;
}
function monta_body($mysqli, $date, $re_resp, $gestao)
{
    $sql = "select s.id as id, s.tipo as tipo, concat(gt.nome,'_',g.identificacao) as identificacao, s.cartao as cartao, format(s.valor,2,'de_DE') as valor, co.nome as coordenador, u.nome as colaborador, s.solicitacao as data, ss.atualizacao as aprovacao, ua.nome as aprovResp from solicitacao s inner join usuario u on s.colaborador=u.re left join usuario co on co.re=u.supervisor left join gmg g on s.identificacao=g.codigo left join gmg_tipo gt on g.tipo=gt.id left join frota f on f.placa=s.identificacao left join veiculo v on v.vei_id=f.veiculo left join solicitacao_historico ss on ss.id=s.aprovacao inner join usuario ua on ss.re=ua.re where co.gestao='{$gestao}' and s.status='2' group by s.id";
    $sql_dados = "select u.email as email, u.nome as nome, u.telefone as telefone from usuario u where re=" . $re_resp;

    $tabela = $mysqli->query($sql);
    $result2 = $mysqli->query($sql_dados);
    $row2 = $result2->fetch_assoc();
    $tel = $row2['telefone'];
    $telefone = "(" . $tel[0] . $tel[1] . ") " . $tel[2] . "-" . $tel[3] . $tel[4] . $tel[5] . $tel[6] . "-" . $tel[7] . $tel[8] . $tel[9] . $tel[10];

    $table = "<table style='width: 100%; font-size: 12px; color: #444444; margin-top: 2px; margin-left: auto; margin-right: auto;border: solid 1px #17A2B8; border-collapse: collapse'>"
        . "<thead><tr>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>CARTÃO</th>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>VALOR</th>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>COORDENADOR</th>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>FINALIDADE</th>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>DATA</th>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>APROVAÇÃO</th>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>RESPONSÁVEL APROVAÇÃO</th>"
        . "</tr></thead>";
    $total_geral = 0;
    $notas = array();

    $c = 1;
    $qtd = 0;
    while ($row = $tabela->fetch_array(MYSQLI_ASSOC)) {

        if ($c === 1) {
            $cor = "#C7D9E7";
            $c = 2;
        } else {
            $cor = "#EEEEEE";
            $c = 1;
        }
        $finalidade = "";
        if ($row['tipo'] === "1") {
            $finalidade = $row['colaborador'];
        } else {
            $finalidade = $row['identificacao'];
        }
        $table .= "<tr>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>" . $row['cartao'] . "</td>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>R$" . $row['valor'] . "</td>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>" . strtoupper(utf8_encode($row['coordenador'])) . "</td>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>" . strtoupper(utf8_encode($finalidade)) . "</td>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>" . $row['data'] . "</td>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>" . $row['aprovacao'] . "</td>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>" . strtoupper(utf8_encode($row['aprovResp'])) . "</td>";
        $table .= "</tr>";
        $total_geral = $total_geral + $row['valor'];

        $notas[] = $row['id'];

        $qtd = $qtd + 1;
    }
    $tabela->close();
    $table .= '<tfoot>';
    $table .= '</table><br>';
    $pagina = '<html>
        <head>
        <meta charset="UTF-8">
            <title></title>
    <style>*{
        font-family: Helvetica;
    }
    .palco{
        width: 100%;
        margin: 4px auto;
        color: #444;
        border:none;
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

          </style>
        </head>
        <body>';

    $html = "
         <div class='palco'>
             <h1>Solicitação de combustível</h1>
             <p style='text-align: center; font-size: 18px'><strong>VALOR TOTAL: </strong><span style='color:#FF4D4D'> R$ " . $total_geral . "</span> | <strong>QUANTIDADE:</strong> <span style='color:#FF4D4D'>" . $qtd . "</span>
             </p>" . $table . "</div>" .
        "<p class='direita'> Data da envio: " . $date . "</p>
             <p class='ln_assinatura'>Responsável pelo envio: <strong>" . $row2['nome'] . "</strong>
    <br>Telefone: " . $telefone . "
    <br>E-mail:" . $row2['email'] . "
    </body></html>";

    $body = $pagina . $html;

    $retorno = array(
        "body" => $body,
        "notas" => $notas
    );

    return $retorno;
}
function email($mysqli, $re, $body, $notas)
{
    $qtd = count($notas);
    $mail = new PHPMailer;
    $mail->isSMTP();

    $sql = "select u.email as email, e.ema_senha as senha, e.ema_smtp as smtp, u.nome as nome, u.re as re, g.nome as gestao from usuario u inner join email e on e.ema_re=u.re inner join gestao g on g.id=u.gestao where u.re='{$re}'";
    $dados = $mysqli->query($sql);

    $resp = $dados->fetch_assoc();

    $mail->Host = $resp['smtp']; //SMTP
    $mail->SMTPAuth = true; // ATIVAR AUTENTICAÇÃO
    $mail->Username = $resp['email']; // USUÁRIO
    $mail->Password = $resp['senha']; // SENHA
    $mail->SMTPSecure = 'tls'; // tls ou ssl
    $mail->CharSet = "utf-8";
    $mail->Encoding = 'base64';
    $mail->Port = 587;

    $mail->setFrom($resp['email'], $resp['nome']); //E-mail origem

    $destino = "select email, nome, colaborador, tipo from email_endereco where finalidade='combustivel' and colaborador='{$re}'";

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
    $mail->isHTML(true);
    $mail->Subject = "Solicitação de combustível: " . $resp['gestao']; // Assunto e-mail
    $mail->Body = $body;
    $mail->AltBody = $body;

    $erro = "1";
    if (!$mail->send()) {
        $msg = "<i class='icon-attention'></i> Solicitações não enviadas:" . $mail->ErrorInfo;
    } else {
        $msg = "<i class='icon-ok-circle-1'></i> " . $qtd . " Solicitações enviadas enviadas com sucesso.";
        $erro = "0";
    }
    $arr = array("erro" => $erro, "msg" => $msg);

    return $arr;
}
function qtd_notas($mysqli, $gestao)
{
    $num = $mysqli->query("select s.id as id from solicitacao s inner join usuario u on u.re=s.colaborador where status='2' and gestao='{$gestao}'")->num_rows;

    $msg = "";
    $erro = "1";
    if ($num === 0) {

        $msg = "<i class='icon-attention'></i> Nenhuma solicitação para envio.";
    } else {
        $msg = "<i class='icon-ok-circle-1'></i> Solicitações enviadas com sucesso, " . $num . ".";
        $erro = "0";
    }

    $arr = array("erro" => $erro, "msg" => $msg, "qtd" => $num);

    return $arr;
}
function permissao($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
///////////////////////////////////////////////////////
function update_valor($mysqli, $id, $valor, $obs, $re)
{
    $p = permissao($mysqli, "5", $re);
    $erro = "1";
    if ($p === 0) {
        $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    } else {
        $sql = "update solicitacao set valor='{$valor}' where id='{$id}'";

        $erro = "1";
        if ($mysqli->query($sql)) {
            $erro = "0";
            $msg = "<i class='icon-ok-circle-1'></i> Valor alterado com sucesso.";
        } else {
            $msg = "<i class='icon-attention'></i> Erro ao processar alteração.";
        }
    }


    $mysqli->close();


    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function lista_sol_envio($mysqli, $gestao, $re)
{
    $p2 = permissao($mysqli, "1", $re);
    if ($p2 === 0) {
        $sql = "select s.id as id, s.tipo as tipo, concat(gt.nome,'_',g.identificacao) as identificacao, s.cartao as cartao, format(s.valor,2,'de_DE') as valor, co.nome as coordenador, u.nome as colaborador, s.solicitacao as data, ss.atualizacao as aprovacao, ua.nome as aprovResp from solicitacao s inner join usuario u on s.colaborador=u.re left join usuario co on co.re=u.supervisor left join gmg g on s.identificacao=g.codigo left join gmg_tipo gt on g.tipo=gt.id left join frota f on f.placa=s.identificacao left join veiculo v on v.vei_id=f.veiculo left join solicitacao_historico ss on ss.id=s.aprovacao inner join usuario ua on ss.re=ua.re where co.gestao='{$gestao}' and s.status='2'";
    } else {
        $sql = "select s.id as id, s.tipo as tipo, concat(gt.nome,'_',g.identificacao) as identificacao, s.cartao as cartao, format(s.valor,2,'de_DE') as valor, co.nome as coordenador, u.nome as colaborador, s.solicitacao as data, ss.atualizacao as aprovacao, ua.nome as aprovResp from solicitacao s inner join usuario u on s.colaborador=u.re left join usuario co on co.re=u.supervisor left join gmg g on s.identificacao=g.codigo left join gmg_tipo gt on g.tipo=gt.id left join frota f on f.placa=s.identificacao left join veiculo v on v.vei_id=f.veiculo left join solicitacao_historico ss on ss.id=s.aprovacao inner join usuario ua on ss.re=ua.re where s.status='2' group by s.id";
    }
    $lista = array();
    if ($result = $mysqli->query($sql)) {

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $lista[] = $row;
        }
    }

    $mysqli->close();
    echo JsonEncodePAcentos::converter($lista);
}
function obter_obs($mysqli, $id)
{

    $sql = "select obs from solicitacao where id='{$id}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $result->close();

    return $row['obs'];
}
function qtdEnvio($mysqli, $gestao, $re)
{
    $p = permissao($mysqli, "6", $re);
    $p2 = permissao($mysqli, "1", $re);
    $w = "";
    $num = 0;

    if ($p === 0) {

        $num = "nd";
    } else if ($p2 === 0) {
        $num = $mysqli->query("SELECT s.id FROM solicitacao s inner join usuario u on u.re=s.coordenador WHERE s.status=2 and u.gestao='{$gestao}'")->num_rows;
    } else {
        $num = $mysqli->query("SELECT s.id FROM solicitacao s inner join usuario u on u.re=s.coordenador WHERE s.status=2")->num_rows;
    }

    $arr = array("qtd" => $num);
    echo JsonEncodePAcentos::converter($arr);
    $mysqli->close();
}
