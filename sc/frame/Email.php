<?php

function enviar($mysqli, $sqlEMail, $dados_email, $assunto, $body)
{
    $email_suporte = "ftsilva@icomontecnologia.com.br"; //"suporte@solicitacaooem.com.br"; //$dados['servidorSmtp']; // Specify main and backup SMTP servers
    $nome_suporte = "SUPORTE SOLICITAÇÕES O&M";
    $senha_suporte = "znfthdwhjwjqjfhv"; //&fKC0FcYp]]8"; //    $dados['servidorSenha'];              // SMTP password
    $smtp_suporte = "smtp.office365.com"; //mail.solicitacaooem.com.br"; //
    $porta_suporte = 587;

    $mail = new PHPMailer;

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $smtp_suporte;
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $email_suporte;
    $mail->Password = $senha_suporte;
    $mail->SMTPSecure = 'tls';                         // Enable TLS encryption, `ssl` also accepted
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->Port = $porta_suporte;

    $mail->setFrom($email_suporte, $nome_suporte); //E-mail origem

    $mail->addAddress($dados_email['email'], $dados_email['nome']); //Destinatário principal

    if ($result = $mysqli->query($sqlEMail)) {
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

    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $assunto;
    $mail->Body = $body;
    $mail->AltBody = $body;
    $mail->send();
}
function bodyHtml_SBO_Cadastro($dados)
{
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
.center{
    text-align: center;
}
table{
    width: 100%;
    font-size: 12px;
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
    border:solid 1px aliceblue;
}
      </style>
    </head>
    <body>';

    $html = "
         <h1>Novo boletim de ocorrência cadastrado</h1>
         <p class='center sub-titulo'><strong>Resumo dos dados</strong>
         <br>
         <table style='border:solid 1px steelblue'>
         <tr>
         <td style='text-align: right;'><strong>RESPONSÁVEL PELO CADASTRO:</strong></td>
         <td style='text-align: left;'>" . $dados['nome'] . "</td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>DATA DE CADASTRO:</strong></td>
         <td style='text-align: left;'>" . $dados['dh'] . "</td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>DATA OCORRÊNCIA:</strong></td>
         <td style='text-align: left;'>" . $dados['dhOc'] .  "</td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>SITE: </strong>" . $dados['site'] . " - " . $dados['cn'] . "</td>
         <td style='text-align: left;'><strong>TA: </strong>" . $dados['ta'] . "</td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>INDISPONIBILIDADE:</strong></td>
         <td style='text-align: left;'>" . $dados['indisp_inicio'] . " - " . $dados['dh_indisp_fim'] . " - Tempo: " . $dados['indsp'] . "</td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>MUNICÍPIOS AFETADOS:</strong>" . $dados['indisp_municipio'] . "</td>
         <td style='text-align: right;'><strong>ELEMENTOS AFETADOS:</strong>" . $dados['indisp_elemento'] . "</td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>FECHADURA BLUETHOOTH:</strong></td>
         <td style='text-align: left;'>" . $dados['fb'] . " - " . $dados['fbs'] . "</td>
         </tr>
         <tr>
         <td style='text-align: left;'><strong>BATERIA RESINADA (POLÍMERO): " . $dados['btresinada'] . "</strong></td>
         <td style='text-align: right;'><strong>BATERIA ION-LITÍO: " . $dados['bateria'] . "</strong></td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>BO: " . $dados['bo'] . "</strong></td>
         <td style='text-align: left;'><strong>SINISTRO: </strong>" . $dados['sinistro'] . "</td>
         </tr>
         <tr>
         <td style='text-align: left;'><strong>OS: </strong>" . $dados['os'] . "</td>
         <td style='text-align: left;'><strong>TA: </strong>" . $dados['ta'] . "</td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>RELATO: </strong>" . $dados['relato'] . "</td>
         </tr>
         <tr>
         <td style='text-align: left;'><strong>FURTADO: </strong>" . $dados['furtado'] . "</td>
         </tr>
         <tr>
         <td style='text-align: left;'><strong>VANDALIZADO: </strong>" . $dados['vandalizado'] . "</td>
         </tr>
         <tr>
         <td style='text-align: left;'><strong>SOBRA: </strong>" . $dados['sobra'] . "</td>
         </tr>
         </table>
        <p class='direita'> Data/Hora do cadastro: " . $dados['dh'] . "</p>
	 	<p class='ln_assinatura'>Responsável: <strong>" . $dados['nome'] . "</strong>
<br>E-mail:" . $dados['email'] . "</p>
</body></html>";

    $body = $pagina . $html;

    return $body;
}
function bodyHtml_ADM_Cartao($dados)
{
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
.center{
    text-align: center;
}
table{
    width: 100%;
    font-size: 12px;
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
    border:solid 1px aliceblue;
}
      </style>
    </head>
    <body>';

    $html = "
         <h1>Novo cartão cadastrado para desbloqueio</h1>
         <br>
         <table style='border:solid 1px red'>
         <tr>
         <td style='text-align: right;'><strong>CONTROLE:</strong></td>
         <td style='text-align: left;'>" . $dados['controle'] . "</td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>CADASTRO:</strong></td>
         <td style='text-align: left;'>" . $dados['data'] . " " . $dados['hora'] .  "</td>
         </tr>
         <tr>
         <td style='text-align: right;'><strong>MOTIVO:</strong></td>
         <td style='text-align: left;'>" . $dados['motivo'] . "</td>
         </tr>
         </table>
	 	<p class='ln_assinatura'>Responsável: <strong>" . $dados['nome'] . "</strong>
<br>E-mail:" . $dados['email'] . "</p>
</body></html>";

    $body = $pagina . $html;

    return $body;
}
