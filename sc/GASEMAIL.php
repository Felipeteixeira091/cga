<?php
header('Content-type: text/html; charset=UTF-8');

function enviar($mysqli, $html, $registro)
{

    $mail = new PHPMailer;

    $sql = "select g.id as id, u.re as colaborador_re, u.nome as colaborador_nome, u.email as colaborador_email, c.re as coordenador_re, c.nome as coordenador_nome, c.email as coordenador_email, g.prisma_os as os_prisma from gas_lancamento g inner join usuario u on u.re=g.re inner join usuario c on c.re=u.supervisor where g.id='{$registro}'";
    $result = $mysqli->query($sql);
    $email = $result->fetch_array();

    $email_suporte = "ftsilva@icomontecnologia.com.br";
    $nome_suporte = "SUPORTE SOLICITAÇÕES O&M";
    $assunto_suporte = "UTILIZAÇÃO DE GÁS | ID: " . $registro;
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

    $sql = "select l.id as id, l.prisma_os os, l.data as data_lancamento, l.hora as hora_lancamento, l.prisma_data as os_data, l.prisma_hora as os_hora, gt.nome as gas_tipo, l.qtd_kg as qtd, st.nome as site_tipo, s.sigla as site, cn.nome as cn, u.re as re, u.nome as nome, u.telefone as telefone, c.re as re_coordenador, c.nome as nome_coordenador, l.obs as obs from gas_lancamento l left join site s on s.id=l.site inner join usuario u on u.re=l.re inner join usuario c on c.re=u.supervisor left join cn on cn.id=s.cn inner join gas_tipo gt on gt.id=l.tipo left join site_tipo st on st.id=s.tipo where l.id='{$registro}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();
    $tel = $row['telefone'];
    $telefone = "(" . $tel[0] . $tel[1] . ") " . $tel[2] . "-" . $tel[3] . $tel[4] . $tel[5] . $tel[6] . "-" . $tel[7] . $tel[8] . $tel[9] . $tel[10];

    $table = "<center>";
    $table .= "<table style='border: 1px solid #ccc; width: 80%; font-size: 12px; color: #444444; margin-top: 2px; margin-left: auto; margin-right: auto; border-collapse: collapse'>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb; text-align:right;'>OS PRISMA: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['os'] . "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>DATA OS: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['os_data'] . " " . $row['os_hora'] . "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>TIPO DE GÁS: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['gas_tipo'] . "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>QUANTIDADE UTILIZADA: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['qtd'] . "kg</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>SITE: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['site_tipo'] . " - " . $row['site'] . "</td>";
    $table .= "</tr>";
    if ($row['obs'] = !"") {
        $table .= "<tr>";
        $table .= "<td colspan='2' style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:center;'>Observações: " . $row['obs'] . "</td>";
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
	 	<h1 style='text-align: center; font-size: 18px;'>Procotolo de utilização de gás</h1>
        <h2 style='text-align: center;color: #0016b0;font-size: 16px;'>ICOMON</h2>
	 	<p style='text-align:center'>ID Registro:<strong>" . $row['id'] . "</strong>
         </p><div style='font-size: 12px;'>" . $table . "</div>
         </div>" .
        "<p style='text-align: right;color:#878a85;'> Data da envio: " . $row['data_lancamento'] . " " . $row['hora_lancamento'] . "</p>
	 	<p style='color:#878a85;line-height: 12px;'>Responsável pelo envio: <strong>" . $row['nome'] . "</strong>
<br>Telefone: " . $telefone . "
</body></html>";

    $body = $pagina . $html;

    return $body;
}
