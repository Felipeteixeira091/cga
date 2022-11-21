<?php
header('Content-type: text/html; charset=UTF-8');

function enviar($mysqli, $html, $registro)
{

    $mail = new PHPMailer;

    $sql = "select scp.id as id, u.re as colaborador_re, u.nome as colaborador_nome, u.email as colaborador_email, c.re as coordenador_re, c.nome as coordenador_nome, c.email as coordenador_email, scp.os as os from scp_registro scp inner join usuario u on u.re=scp.re inner join usuario c on c.re=u.supervisor where scp.id='{$registro}'";
    $result = $mysqli->query($sql);
    $email = $result->fetch_array();

    $sql = "select ro.email as rotaEmail, ro.nome as rotaNome from usuario u inner join cn on cn.id=u.cn inner join rota r on r.id=cn.rota inner join usuario ro on ro.re=r.responsavel WHERE u.re='{$email['colaborador_re']}'";
    $result = $mysqli->query($sql)->fetch_array();
    $rotaEmail = $result['email'];
    $rotaNome = $result['nome'];

    $email_suporte = "ftsilva@icomontecnologia.com.br";
    $nome_suporte = "SUPORTE SOLICITAÇÕES O&M";
    $assunto_suporte = "SOLICITAÇÃO DE CORREÇÃO DE PONTO | ID: " . $registro;
    $senha_suporte = "wqrtqqrcyjypdqzb"; //&fKC0FcYp]]8"; //    $dados['servidorSenha'];              // SMTP password
    $smtp_suporte = "smtp.office365.com"; //mail.solicitacaooem.com.br"; //
    $porta_suporte = 587;

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

    $mail->addAddress($email['colaborador_email'], $email['colaborador_nome']);
    $mail->addCC($email['coordenador_email'], $email['coordenador_nome']);
    $mail->addCC($rotaEmail, $rotaNome);
    $mail->addBCC('ftsilva@icomontecnologia.com.br', 'Felipe Teixeira');

    $mail->addReplyTo('ftsilva@icomontecnologia.com.br', 'ReplyTo'); //Endereço de resposta

    //  $mail->addAttachment($dados['anexo']);         // Add attachments
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $assunto_suporte;
    $mail->Body = $html;
    $mail->AltBody = $html;

    if (!$mail->send()) {
        $erro = "1";
    } else {
        $erro = "0";
    }
    return $erro;
}
function bodyHtml($mysqli, $registro)
{

    $sql = "select scp.id as id, site.sigla as site, stipo.nome as site_tipo, ativ.nome as atividade, ativ.id as ativ_id, scp.os as os, scp.data as data, scp.hora as hora, scp.data1 as data1, scp.hora1 as hora1,scp.data2 as data2, scp.hora2 as hora2, scp.obs as justificativa, u.nome as nome, u.re as re, u.telefone as telefone from scp_registro scp inner join site on site.id=scp.site inner join scp_atividade ativ on ativ.id=scp.atividade inner join usuario u on u.re=scp.re inner join site_tipo stipo on stipo.id=site.tipo where scp.id='{$registro}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();
    $tel = $row['telefone'];
    $telefone = "(" . $tel[0] . $tel[1] . ") " . $tel[2] . "-" . $tel[3] . $tel[4] . $tel[5] . $tel[6] . "-" . $tel[7] . $tel[8] . $tel[9] . $tel[10];
    $atividade = $row['ativ_id'];

    $table = "<center>";
    $table .= "<table style='border: 1px solid #ccc; width: 80%; font-size: 12px; color: #444444; margin-top: 2px; margin-left: auto; margin-right: auto; border-collapse: collapse'>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>ATIVIDADE: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['atividade'] . "</td>";
    $table .= "</tr>";
    if ($atividade === "2" || $atividade === "3" || $atividade === "4") {
        $table .= "<tr>";
        $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb; text-align:right;'>OS/TA/TP: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['os'] . "</td>";
        $table .= "</tr>";
    }
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>SITE: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['site_tipo'] . " - " . $row['site'] . "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>DATA CADASTRO: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['data'] . " " . $row['hora'] . "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>ENTRADA: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['data1'] . " " . $row['hora1'] . "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>SAÍDA: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . " - " . $row['data2'] . " " . $row['hora2'] . "</td>";
    $table .= "</tr>";
    if ($row['obs'] = !"") {
        $table .= "<tr>";
        $table .= "<td colspan='2' style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:center;'>JUSTIFICATIVA: " . $row['justificativa'] . "</td>";
        $table .= "</tr>";
    }
    $table .= "</table></center><br>";

    $result->close();

    $pagina = '<html>
    <head>
        <meta charset="UTF-8">
        <title>Protocolo</title>
    </head>
    <body>';

    $html = "
    <div style='width: 80%; margin: 2px auto; color: #444; padding: 2px;font-family: Arial, Helvetica, sans-serif'>
	 	<h1 style='text-align: center; font-size: 18px;'>Procotolo de solicitação de correção de ponto</h1>
        <h2 style='text-align: center;color: #0016b0;font-size: 16px;'>ICOMON</h2>
	 	<p style='text-align:center'>ID Registro:<strong>" . $row['id'] . "</strong>
         </p><div style='font-size: 12px;'>" . $table . "</div>
         </div>" .
        "<p style='text-align: right;color:#878a85;'> Data da envio: " . $row['data'] . " " . $row['hora'] . "</p>
	 	<p style='color:#878a85;line-height: 12px;'>Responsável pelo envio: <strong>" . $row['nome'] . "</strong>
<br>Telefone: " . $telefone . "
</body></html>";

    $body = $pagina . $html;

    return $body;
}
function enviarValidacao($mysqli, $html, $registro)
{

    $mail = new PHPMailer;

    $sql = "select scp.id as id, u.re as colaborador_re, u.nome as colaborador_nome, u.email as colaborador_email, c.re as coordenador_re, c.nome as coordenador_nome, c.email as coordenador_email, ua.nome av_nome, ua.email as av_email, scp.os as os from scp_registro scp inner join usuario u on u.re=scp.re inner join usuario c on c.re=u.supervisor inner join usuario ua on ua.re=scp.re_avaliacao where scp.id='{$registro}'";
    $result = $mysqli->query($sql);
    $email = $result->fetch_array();

    $email_suporte = "ftsilva@icomontecnologia.com.br";
    $nome_suporte = "SUPORTE SOLICITAÇÕES O&M";
    $assunto_suporte = "RETORNO CORREÇÃO DE PONTO | ID: " . $registro;
    $senha_suporte = "znfthdwhjwjqjfhv"; //&fKC0FcYp]]8"; //    $dados['servidorSenha'];              // SMTP password
    $smtp_suporte = "smtp.office365.com"; //mail.solicitacaooem.com.br"; //
    $porta_suporte = 587;

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

    $mail->addAddress($email['colaborador_email'], $email['colaborador_nome']);
    $mail->addCC($email['av_email'], $email['av_nome']);
    $mail->addCC($email['coordenador_email'], $email['coordenador_nome']);
    $mail->addCC($email['coordenador_email'], $email['coordenador_nome']);
    $mail->addCC('brpaiva@icomontecnologia.com.br', 'Bruno Rosse');
    $mail->addCC('plvan@icomontecnologia.com.br', 'Patricia Van Zanten');

    $mail->addBCC('ftsilva@icomontecnologia.com.br', 'Felipe Teixeira');
 
   // $mail->addReplyTo('ftsilva@icomontecnologia.com.br', 'ReplyTo'); //Endereço de resposta

    //  $mail->addAttachment($dados['anexo']);         // Add attachments
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $assunto_suporte;
    $mail->Body = $html;
    $mail->AltBody = $html;

    if (!$mail->send()) {
        $erro = "1";
    } else {
        $erro = "0";
    }
    return $erro;
}
function bodyHtmlValidacao($mysqli, $registro)
{
    $sql = "select scp.id as id, site.sigla as site, stipo.nome as site_tipo, ativ.nome as atividade, ativ.id as ativ_id, scp.os as os, scp.data as data, scp.hora as hora, scp.data1 as data1, scp.hora1 as hora1,scp.data2 as data2, scp.hora2 as hora2, scp.obs as justificativa, u.nome as nome, u.re as re, ua.nome as nomeA, ua.telefone as telefoneA, scp.avaliacao as avaliacao, ss.nome as status from scp_registro scp inner join site on site.id=scp.site inner join scp_atividade ativ on ativ.id=scp.atividade inner join usuario u on u.re=scp.re inner join site_tipo stipo on stipo.id=site.tipo inner join usuario ua on ua.re=scp.re_avaliacao inner join scp_status ss on ss.id=scp.status where scp.id='{$registro}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();
    $tel = $row['telefoneA'];
    $telefone = "(" . $tel[0] . $tel[1] . ") " . $tel[2] . "-" . $tel[3] . $tel[4] . $tel[5] . $tel[6] . "-" . $tel[7] . $tel[8] . $tel[9] . $tel[10];
    $atividade = $row['ativ_id'];

    $table = "<center>";
    $table .= "<table style='border: 1px solid #ccc; width: 80%; font-size: 12px; color: #444444; margin-top: 2px; margin-left: auto; margin-right: auto; border-collapse: collapse'>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>COLABORADOR: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['nome'] . "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>ATIVIDADE: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['atividade'] . "</td>";
    $table .= "</tr>";
    if ($atividade === "2" || $atividade === "3" || $atividade === "4") {
        $table .= "<tr>";
        $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb; text-align:right;'>OS/TA/TP: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['os'] . "</td>";
        $table .= "</tr>";
    }
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>SITE: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['site_tipo'] . " - " . $row['site'] . "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>DATA CADASTRO: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['data'] . " " . $row['hora'] . "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>ENTRADA: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['data1'] . " " . $row['hora1'] . "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>SAÍDA: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . " - " . $row['data2'] . " " . $row['hora2'] . "</td>";
    $table .= "</tr>";
    if ($row['obs'] = !"") {
        $table .= "<tr>";
        $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>JUSTIFICATIVA: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['justificativa'] . "</td>";
        $table .= "</tr>";
    }
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right; color:red'>RETORNO: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['avaliacao'] . " - " . $row['status'] . "</td>";
    $table .= "</tr>";
    $table .= "</table></center><br>";

    $result->close();

    $pagina = '<html>
    <head>
        <meta charset="UTF-8">
        <title>Protocolo</title>
    </head>
    <body>';

    $html = "
    <div style='width: 80%; margin: 2px auto; color: #444; padding: 2px;font-family: Arial, Helvetica, sans-serif'>
	 	<h1 style='text-align: center; font-size: 18px;'>Retorno de solicitação de correção de ponto</h1>
        <h2 style='text-align: center;color: #0016b0;font-size: 16px;'>ICOMON</h2>
	 	<p style='text-align:center'>ID Registro:<strong>" . $row['id'] . "</strong>
         </p><div style='font-size: 12px;'>" . $table . "</div>
         </div>" .
        "<p style='text-align: right;color:#878a85;'> Data da envio: " . $row['data'] . " " . $row['hora'] . "</p>
	 	<p style='color:#878a85;line-height: 12px;'>Responsável pelo envio: <strong>" . $row['nomeA'] . "</strong>
<br>Telefone: " . $telefone . "
</body></html>";

    $body = $pagina . $html;

    return $body;
}
