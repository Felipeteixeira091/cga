<?php

include_once "conf/conexao2.php";
include_once "json_encode.php";
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION["nome"]) || !isset($_SESSION["email"])) {
    header("Location: ../");
    exit;
}

$data = date('Y-m-d');
$hora = date('H:i', time());

$retorno;
$re_resp = $_SESSION["re"];

$p = permissao($mysqli, "5", $re_resp);

if ($p === 0) {

    $msg = "<i class='icon-attention'></i> Você não tem permissão.";
    $retorno = array("erro" => "1", "msg" => $msg);
} else {

    $notas = qtd_notas($mysqli);
    if ($notas['qtd'] > 0) {

        $body = monta_body($mysqli, $data, $re_resp);
        $email = email($mysqli, $re_resp, $body['body'], $body['notas']);
        if ($email['erro'] === "0") {

            $update = update($mysqli, $body['notas'], $re_resp, $data, $hora);
        }

        $retorno = $email;
    } else {

        $retorno = $notas;
    }
}

echo JsonEncodePAcentos::converter($retorno);
$mysqli->close();

function update($mysqli, $notas, $re_resp, $data, $hora)
{
    $qtd = count($notas);
    if ($qtd > 0) {

        for ($i = 0; $i < $qtd; $i++) {

            $nota = $notas[$i];
            $sql_up = "update nota set status='6', obs='NOTA ENVIADA' where id='{$nota}'";
            $sql_vid = "insert into nota_vida (nota, data, hora, status, re, obs) values ('{$nota}','{$data}', '{$hora}', '6', '{$re_resp}', 'NOTA ENVIADA')";

            $mysqli->query($sql_up);
            $mysqli->query($sql_vid);
        }
    }
    return $qtd;
}
function monta_body($mysqli, $date, $re_resp)
{

    $sql = "select n.id as id, cn.nome as cn, s.re as solicitante_re, s.nome as solicitante_nome, c.nome as colaborador_nome, c.re as colaborador_re, uc.nome as coordenador, c.re as colaborador_re, sit.sigla as site, n.data as data, n.hora as hora, n.dataNota as dataNota, ns.nome as status, n.valor as valor, nt.nome as tipo, n.os as os, nm.nome as motivo, n.anexo as anexo from nota n inner join usuario s on s.re=n.re inner join usuario c on c.re=n.colaborador inner join usuario uc on uc.re=c.supervisor inner join site sit on sit.id=n.site inner join nota_status ns on ns.id=n.status inner join cn cn on cn.id=sit.cn inner join nota_tipo nt on nt.id=n.tipo inner join nota_motivo nm on nm.id=n.motivo where n.status=3 order by c.nome";
    $sql_dados = "select u.email as email, u.nome as nome, u.telefone as telefone from usuario u where re='" . $re_resp . "'";

    $tabela = $mysqli->query($sql);
    $result2 = $mysqli->query($sql_dados);
    $row2 = $result2->fetch_assoc();
    $tel = $row2['telefone'];
    $telefone = "(" . $tel[0] . $tel[1] . ") " . $tel[2] . "-" . $tel[3] . $tel[4] . $tel[5] . $tel[6] . "-" . $tel[7] . $tel[8] . $tel[9] . $tel[10];

    $table = "<table>"
        . "<thead><tr>"
        . "<th>RE</th>"
        . "<th>NOME</th>"
        . "<th>COORDENADOR</th>"
        . "<th>DATA</th>"
        . "<th>TIPO</th>"
        . "<th>MOTIVO</th>"
        . "<th>OS/TA</th>"
        . "<th>SITE</th>"
        . "<th>VALOR</th>"
        . "</tr></thead>";
    $total_geral = 0;
    $notas = array();

    while ($row = $tabela->fetch_array(MYSQLI_ASSOC)) {
        $table .= "<tr>";
        $table .= "<td>" . $row['colaborador_re'] . "</td>";
        $table .= "<td>" . strtoupper(utf8_encode($row['colaborador_nome'])) . "</td>";
        $table .= "<td>" . strtoupper(utf8_encode($row['coordenador'])) . "</td>";
        $table .= "<td>" . $row['dataNota'] . "</td>";
        $table .= "<td>" . $row['tipo'] . "</td>";
        $table .= "<td>" . $row['motivo'] . "</td>";
        $table .= "<td>" . $row['os'] . "</td>";
        $table .= "<td>" . $row['site'] . "</td>";
        $table .= "<td>R$" . number_format($row['valor'], 2, ',', '.') . "</td>";
        $table .= "</tr>";
        $total_geral = $total_geral + $row['valor'];

        $notas[] = $row['id'];
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
             <h1>Notas aprovadas</h1>
             <p class='center sub-titulo'>VALOR TOTAL <strong>R$ " . number_format($total_geral, 2, ',', '.') . "</strong>
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
function email($mysqli, $re, $body, $qtd)
{
    $mail = new PHPMailer;
    $mail->isSMTP();

    $sql = "select u.email as email, e.ema_senha as senha, e.ema_smtp as smtp, u.nome as nome, u.re as re from usuario u inner join email e on e.ema_re=u.re where u.re=" . $re;
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

    $destino = "select email, nome, colaborador, tipo from email_endereco where colaborador=" . $re;

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
    $mail->Subject = "REEMBOLSONS AUTORIZADOS, QUANTIDADE: " . $qtd; // Assunto e-mail
    $mail->Body = $body;
    $mail->AltBody = $body;

    $erro = "1";
    if (!$mail->send()) {
        $msg = "<i class='icon-attention'></i> Notas não enviadas, erro:" . $mail->ErrorInfo;
    } else {
        $msg = "<i class='icon-ok-circle-1'></i> " . $qtd . " Notas enviadas com sucesso.";
        $erro = "0";
    }
    $arr = array("erro" => $erro, "msg" => $msg);

    return $arr;
}
function qtd_notas($mysqli)
{
    $num = $mysqli->query("select id from nota where status='3'")->num_rows;

    $msg = "";
    $erro = "1";
    if ($num === 0) {

        $msg = "<i class='icon-attention'></i> Nenhuma nota pendente para envio.";
    } else {
        $msg = "<i class='icon-ok-circle-1'></i> Mensagens disponíveis para envio, " . $num . ".";
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
