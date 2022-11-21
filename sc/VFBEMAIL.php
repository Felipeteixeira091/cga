<?php
header('Content-type: text/html; charset=UTF-8');
function vfb($mysqli, $vfb)
{
    $sql = "select vv.id as id, vv.data as data, vv.hora as hora, vv.os as os, vv.valor as valor, s.sigla as site, cn.nome as cn, vv.status as status, vs.nome as statusTxt, vs.ico as ico, u.re as re, u.nome as nome, vv.solicitacao as solicitacao, vv.conclusao as conclusao, if(vv.moTipo='1','ICOMON','FORNECEDOR') as mo, me.re meRe, me.nome meNome, ifnull(seg.nome,'NÃO DEFINIDO') segmento from vfb_vistoria vv inner join site s on s.id=vv.site inner join usuario u on u.re=vv.responsavel inner join cn on cn.id=s.cn inner join vfb_status vs on vs.id=vv.status left join usuario me on me.re=vv.mo left join vfb_checklist vc on vc.vfb=vv.id left join vfb_segmento seg on seg.id=vc.seg where vv.id='{$vfb}'";
    $dados = $mysqli->query($sql)->fetch_assoc();

    $table = "<table style='width: 100%; font-size: 12px; color: #444444; margin-top: 2px; margin-left: auto; margin-right: auto;border: solid 1px #17A2B8; border-collapse: collapse'>"
        . "<thead><tr>"
        . "<th colspan='3' style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>Vistoriador: " . $dados['nome'] . "</th>"
        . "</tr></thead>";

    $table .= "<tr>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#C7D9E7;color:#444444;border: none;'>" .
        "<b>Mão de obra:</b> " . $dados['mo'] . "</td>";
    $table .= "<td colspan='2' style='text-align: center; font-size: 12px;font-weight: normal;background-color:#C7D9E7;color:#444444;border: none;'>" .
        "<b>Executante:</b> " . $dados['meNome'] . "</td>";
    $table .= "</tr>";

    $table .= "<tr>";
    $table .= "<td colspan='1' style='text-align: center; font-size: 12px;font-weight: normal;background-color:#EEEEEE;color:#444444;border: none;'>" .
        "<b>Data:</b> " . $dados['data'] . " " . $dados['hora'] . "</td>";
    $table .= "<td colspan='2' style='text-align: center; font-size: 12px;font-weight: normal;background-color:#EEEEEE;color:#444444;border: none;'>" .
        "<b>Segmento:</b> " . $dados['segmento'] . "</td>";
    $table .= "</tr>";

    $table .= "<tr>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#C7D9E7;color:#444444;border: none;'>" .
        "<b>Site: </b>" . $dados['site'] . "</td>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#C7D9E7;color:#444444;border: none;'>" .
        "<b>Os Prisma: </b>" . $dados['os'] . "</td>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#C7D9E7;color:#444444;border: none;'>" .
        "<b>Valor da obra: </b>R$" . $dados['valor'] . "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td colspan='3' style='text-align: center; font-size: 12px;font-weight: normal;background-color:#EEEEEE;color:#444444;border: none;'>" .
        "<b>Serviço aprovado: </b>" . $dados['solicitacao'] . "</td>";
    $table .= "</tr>";

    return $table;
}
function checklist($mysqli, $vfb)
{
    $ck = $mysqli->query("select * from vfb_checklist inner join vfb_segmento on vfb_checklist.seg=vfb_segmento.id WHERE vfb='{$vfb}'")->fetch_assoc();

    $table = "<table style='width: 100%; font-size: 12px; color: #444444; margin-top: 2px; margin-left: auto; margin-right: auto;border: solid 1px #17A2B8; border-collapse: collapse'>"
        . "<thead><tr>"
        . "<th colspan='2' style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>CHECK-LIST</th>"
        . "</tr></thead>";

    $table .= "<tr>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#C7D9E7;color:#444444;border: none;'>" .
        "Segmento de atividade:" . "</td>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#C7D9E7;color:#444444;border: none;'>" .
        $ck['nome'] . "</td>";
    $table .= "</tr>";

    $table .= "<tr>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#EEEEEE;color:#444444;border: none;'>" .
        "1. Obra executada conforme solicitação?" . "</td>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#EEEEEE;color:#444444;border: none;'>" .
        vf($ck['perg1']) . "</td>";
    $table .= "</tr>";

    $table .= "<tr>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#C7D9E7;color:#444444;border: none;'>" .
        "2. Obra executada gerou alguma falha secundaria no site?" . "</td>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#C7D9E7;color:#444444;border: none;'>" .
        vf($ck['perg2']) . "</td>";
    $table .= "</tr>";

    $table .= "<tr>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#EEEEEE;color:#444444;border: none;'>" .
        "1. Todas as falhas identificadas na OS foram sanadas após execução da atividade?" . "</td>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#EEEEEE;color:#444444;border: none;'>" .
        vf($ck['perg3']) . "</td>";
    $table .= "</tr>";

    $table .= "<tr>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#C7D9E7;color:#444444;border: none;'>" .
        "2. O material utilizado na execução da atividade oferece algum risco ( material reutilizado, fora das normas, etc)?" . "</td>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#C7D9E7;color:#444444;border: none;'>" .
        vf($ck['perg4']) . "</td>";
    $table .= "</tr>";

    $table .= "<tr>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#EEEEEE;color:#444444;border: none;'>" .
        "3. A atividade esta dentro do padrão de qualidade solicitado?" . "</td>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#EEEEEE;color:#444444;border: none;'>" .
        vf($ck['perg5']) . "</td>";
    $table .= "</tr>";

    $table .= "<tr>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#C7D9E7;color:#444444;border: none;'>" .
        "4. Foram deixados resíduos (sujeira,restos de materiais) no local da obra?" . "</td>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#C7D9E7;color:#444444;border: none;'>" .
        vf($ck['perg6']) . "</td>";
    $table .= "</tr>";

    $table .= "<tr>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#EEEEEE;color:#444444;border: none;'>" .
        "Nota de avaliação geral sobre a obra:" . "</td>";
    $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color:#EEEEEE;color:#444444;border: none;'>" .
        $ck['perg7'] . "</td>";
    $table .= "</tr>";

    return $table;
}
function vf($input)
{

    $r = "";
    if ($input === "1") {
        $r = "SIM";
    } else if ($input === "2") {
        $r = "NÃO";
    }
    return $r;
}
function enviar($mysqli, $html, $vfb)
{
    $mail = new PHPMailer;

    $sql = "SELECT v.id as id, u.nome as vNome, u.email as vEmail, e.ema_senha as vSenha, e.ema_smtp as vSmtp, c.nome as cNome, c.email as cEmail, s.nome as sNome, s.email as sEmail, vs.nome as status FROM vfb_vistoria v inner join vfb_status vs on vs.id=v.status inner join usuario u on u.re=v.responsavel inner join usuario c on c.re=u.supervisor inner join usuario s on s.re=v.solicitante inner join email e on e.ema_re=u.re where v.id='{$vfb}'";
    $result = $mysqli->query($sql);
    $email = $result->fetch_array();

    $email_suporte = $email['vEmail'];//"ftsilva@icomontecnologia.com.br";
    $nome_suporte = $email['vNome'];//"SOLICITAÇÕES O&M";
    $assunto_suporte = "VISTORIA DE OBRA " . $email['status'] . " | ID: " . $vfb;
    $senha_suporte = $email['vSenha'];//"wqrtqqrcyjypdqzb"; //&fKC0FcYp]]8"; //    $dados['servidorSenha'];              // SMTP password
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

    $mail->addAddress($email['vEmail'], $email['vNome']);
    $mail->addCC($email['cEmail'], $email['cNome']);
    $mail->addCC($email['sEmail'], $email['sNome']);
    $mail->addCC("brpaiva@icomontecnologia.com.br", "Bruno Paiva");
    $mail->addBCC('ftsilva@icomontecnologia.com.br', 'Felipe Teixeira');

    $arquivos = "select id, codigo from vfb_anexo where tipo='1' and vistoria='{$vfb}'";

    if ($result = $mysqli->query($arquivos)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

            $caminho = "../vfb_anexo/" . $row['codigo'];
            $mail->addAttachment($caminho);
        }
    }

    //    $mail->addReplyTo('ftsilva@icomontecnologia.com.br', 'ReplyTo'); //Endereço de resposta

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
function bodyHtml($mysqli, $vfb)
{
    $sql = "select vv.id as id, vv.data as data, vv.hora as hora, s.sigla as site, cn.nome as cn, vv.status as status, vs.nome as statusTxt, u.re as re, u.nome as nome from vfb_vistoria vv inner join site s on s.id=vv.site inner join usuario u on u.re=vv.responsavel inner join cn on cn.id=s.cn inner join vfb_status vs on vs.id=vv.status left join usuario me on me.re=vv.mo left join vfb_checklist vc on vc.vfb=vv.id left join vfb_segmento seg on seg.id=vc.seg where vv.id='{$vfb}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $checklist = checklist($mysqli, $vfb);
    $detalhes = vfb($mysqli, $vfb);

    $pagina = '<html>
    <head>
        <meta charset="UTF-8">
        <title>Protocolo</title>
    </head>
    <body>';

    $html = "
    <div style='width: 80%; margin: 2px auto; color: #444; padding: 2px;font-family: Arial, Helvetica, sans-serif'>
	 	<h1 style='text-align: center; font-size: 18px;'>Vistoria: " . $row['statusTxt'] . "</h1>
        <h2 style='text-align: center;color: #0016b0;font-size: 16px;'>ICOMON MG</h2>
	 	<p style='text-align:center'>ID da vistoria:<strong>" . $row['id'] . "</strong>
         </p><div style='font-size: 12px;'>" . $detalhes . "</div><br>".$checklist ."</div><br>" .
        
        "<p style='text-align: right;color:#878a85;'> Data vistoria: " . $row['data'] . " " . $row['hora'] . "</p>
	 	<p style='color:#878a85;line-height: 12px;'>Responsável: <strong>" . $row['nome'] . "</strong>
</body></html>";

    $body = $pagina . $html;

    return $body;
}
