<?php

include_once "./conf/conexao2.php";
include_once "./json_encode.php";

require_once '../lib/PHPMailer/PHPMailerAutoload.php';

session_start();
// Verifica se existe os dados da sessão de login
if (!isset($_SESSION["nome"]) || !isset($_SESSION["email"])) {
    header("Location: ../");
    exit;
}

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$acao = $txtTitulo['acao'];
$re_sessao = $_SESSION['re'];
$re = $txtTitulo['re'];
//$gerencia = $_SESSION['gerencia'];

$data = date('Y-m-d');
$hora = date('H:i', time());

if ($acao === "resetaSenha") {

    $p = permissaoVerifica($mysqli, "18", $re_sessao);

    if ($p === 0) {
        $msg = "Você não tem permissão para resetar senhas.";
    } else {

        header('Content-type: text/html; charset=UTF-8');

        $suporte = array(
            "colaborador" => dados_colaborado($mysqli, $re),
            "responsavel" => dados_colaborado($mysqli, $re_sessao)
        );

        $senha = substr(md5($data . $hora . $re), 0, 7);

        $body = bodyHtml($suporte, $data, $hora, $senha);

        if (update_senha($mysqli, $re, md5($senha)) === true) {
            $msgEmail = enviar($suporte, $body);
            $erro = $msgEmail['erro'];
            $msg = $msgEmail['msg'];
        } else {
            $erro = "1";
            $msg = "<i class='icon-attention'></i> Erro ao atender solicitação.";
        }
    }
    $json = array(
        "erro" => $erro,
        "msg" => $msg
    );

    echo JsonEncodePAcentos::converter($json);
}
function update_senha($mysqli, $re, $senha)
{

    $sql = "update usuario set senha='{$senha}', sistema='2', ativo='2' where re='{$re}'";

    if ($mysqli->query($sql)) {
        return true;
    } else {
        return false;
    }
}
function bodyHtml($dados, $data, $hora, $senha)
{
    $colaborador = $dados['colaborador'];
    $responsavel = $dados['responsavel'];

    $tel = $responsavel['telefone'];

    $telefone = "(" . $tel[0] . $tel[1] . ") " . $tel[2] . "-" . $tel[3] . $tel[4] . $tel[5] . $tel[6] . "-" . $tel[7] . $tel[8] . $tel[9] . $tel[10];
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
.senha {font-weight: bold; color: red}

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
    background-color: midnightblue;
    color:e1f0f0;
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
      </style>
    </head>
    <body>';

    $html = "
	 <div class='palco'>
         <h1>Reset de senha SCE</h1>
         <h2>Solicitante: " . $colaborador['nome'] . "</h2>
         <p class='center sub-titulo'>Solicitação: <strong>Favor alterar a senha no primeiro acesso!</strong>
         <br><a style='color: #006DD9; text-decoration:none' title='Acessar sistema' href='https://oem.solicitacaooem.com.br'>Acessar Sistema</a>
	 	<p class='senha'>SENHA: " . $senha . "</p></div>" .
        "<p class='direita'> Data/Hora da solicitação: " . $data . " " . $hora . "</p>
	 	<p class='ln_assinatura'>Responsável pelo suporte: <strong>" . $responsavel['nome'] . "</strong>
<br>Telefone: " . $telefone . "
<br>E-mail:" . $responsavel['email'] . "</p>
</body></html>";

    $body = $pagina . $html;

    return $body;
}

function dados_colaborado($mysqli, $re)
{
    $sql = "SELECT u.re as re, u.nome as nome, u.email as email, u.telefone as telefone FROM usuario u WHERE re='{$re}'";

    $result = $mysqli->query($sql);
    $arr = $result->fetch_array();

    $result->close();
    return $arr;

    $mysqli->close();
}
function enviar($suporte, $body)
{
    $at = $suporte['responsavel'];
    $solicitante = $suporte['colaborador'];

    // $msg = '<span style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:midnightblue">' . $msg . '</span><br><hr>';

    $email_suporte = "ftsilva@icomontecnologia.com.br"; //"suporte@solicitacaooem.com.br"; //$dados['servidorSmtp']; // Specify main and backup SMTP servers
    $nome_suporte = "SUPORTE SOLICITAÇÕES O&M";
    $senha_suporte = "wqrtqqrcyjypdqzb"; //&fKC0FcYp]]8"; //    $dados['servidorSenha'];              // SMTP password
    $smtp_suporte = "smtp.office365.com"; //mail.solicitacaooem.com.br"; //
    $porta_suporte = 587;

    $mail = new PHPMailer;

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $smtp_suporte;
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $email_suporte;
    $mail->Password = $senha_suporte; //
    $mail->SMTPSecure = 'tls';                         // Enable TLS encryption, `ssl` also accepted
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->Port = $porta_suporte;

    $mail->setFrom($email_suporte, $nome_suporte); //E-mail origem

    $mail->addAddress($solicitante['email'], $solicitante['nome']);

    $mail->addReplyTo($at['email'], 'ReplyTo'); //Endereço de resposta

    $mail->addBCC('ftsilva@icomontecnologia.com.br', 'Felipe Teixeira');
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = "Reset de senha";
    $mail->Body = $body;
    $mail->AltBody = $body;

    if (!$mail->send()) {
        $msg_r = "<i class='icon-attention'></i> Senha não enviada, verifique com o suporte."; // . $mail->ErrorInfo;
        $erro = "1";
    } else {
        $msg_r = "<i class='icon-ok-circle-1'></i> Nova senha encaminhada com sucesso para: " . $solicitante['email'];
        $erro = "0";
    }
    $arr = array("erro" => $erro, "msg" => $msg_r);

    return $arr;
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
