<?php
header('Content-type: text/html; charset=UTF-8');

function enviar($mysqli, $html, $acoplamento)
{

    //  $body = '<span style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:midnightblue">' . $html . '</span>';

    $mail = new PHPMailer;

    $sql = "select ac.id as id, ac.re as colaborador_re, usr.nome as colaborador_nome, usr.email as colaborador_email, coo.re as coordenador_re,coo.nome as coordenador_nome, coo.email as coordenador_email, concat(gt.nome,'_',gmg.identificacao) as gmg from gmg_acoplamento ac inner join gmg gmg on gmg.codigo=ac.gmg_codigo inner join gmg_tipo gt on gt.id=gmg.tipo inner join usuario usr on usr.re=ac.re left join usuario coo on coo.re=usr.supervisor where ac.id='{$acoplamento}'";
    $result = $mysqli->query($sql);
    $email = $result->fetch_array();

    $email_suporte = "ftsilva@icomontecnologia.com.br";
    $nome_suporte = "SUPORTE SOLICITAÇÕES O&M";
    $assunto_suporte = "ACOPLAMENTO | ID: " . $acoplamento;
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
function bodyHtml($mysqli, $acoplamento)
{
    $sql = "select ac.id as id_ac, u.nome as nome, u.telefone as telefone, concat(gt.nome,'_',g.identificacao) as gmg, ac.data as data, ac.hora, ac.data_inicio as inicio_data, ac.hora_inicio as inicio_hora, ac.data_final as final_data, ac.hora_final as final_hora, s.sigla as site_sigla, s.descricao as site_nome from gmg_acoplamento ac inner join gmg g on g.codigo=ac.gmg_codigo inner join gmg_tipo gt on gt.id=g.tipo inner join usuario u on u.re=ac.re inner join site s on s.id=ac.site where ac.id='{$acoplamento}'";


    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $tel = $row['telefone'];
    $telefone = "(" . $tel[0] . $tel[1] . ") " . $tel[2] . "-" . $tel[3] . $tel[4] . $tel[5] . $tel[6] . "-" . $tel[7] . $tel[8] . $tel[9] . $tel[10];

    $table = "<center>";
    $table .= "<table style='border: 1px solid #ccc; width: 80%; font-size: 12px; color: #444444; margin-top: 2px; margin-left: auto; margin-right: auto; border-collapse: collapse'>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb; text-align:right;'>GMG: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['gmg'] . "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>INÍCIO ACOPLAMENTO: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['inicio_data'] . " - " . $row['inicio_hora'] . "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>FIM ACOPLAMENTO: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['final_data'] . " - " . $row['final_hora'] . "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:right;'>SITE: </td><td style='background-color: #ebebeb; border: 1px dotted #ebebeb;text-align:left;'>" . $row['site_sigla'] . " - " . $row['site_nome'] . "</td>";
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
	 	<h1 style='text-align: center; font-size: 18px;'>Procotolo de acoplamento</h1>
        <h2 style='text-align: center;color: #0016b0;font-size: 16px;'>ICOMON</h2>
	 	<p style='text-align:center'>ID Acoplamento:<strong>" . $row['id_ac'] . "</strong>
         </p><div style='font-size: 12px;'>" . $table . "</div>
         </div>" .
        "<p style='text-align: right;color:#878a85;'> Data da envio: " . $row['data'] . "</p>
	 	<p style='color:#878a85;line-height: 12px;'>Responsável pelo envio: <strong>" . $row['nome'] . "</strong>
<br>Telefone: " . $telefone . "
</body></html>";

    $body = $pagina . $html;

    return $body;
}
