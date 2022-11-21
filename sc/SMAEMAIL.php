<?php
header('Content-type: text/html; charset=UTF-8');

function enviar($mysqli, $dados, $tabela, $almox, $solicitacao)
{

    $body = '<span style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:midnightblue">' . $dados['body'] . '</span>';

    $mail = new PHPMailer;

    $mail->isSMTP();                                     // Set mailer to use SMTP
    $mail->Host = $dados['smtp']; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $dados['email']; // SMTP username
    $mail->Password = $dados['senha'];              // SMTP password
    $mail->SMTPSecure = 'tls';                         // Enable TLS encryption, `ssl` also accepted
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->Port = 587;

    $mail->setFrom($dados['email'], $dados['nome']); //E-mail origem
    $mail->addAddress($dados['solicitanteEmail'], $dados['solicitanteNome']);

    $msgRetorno = "";

    if ($almox === "S") {
        $mail->addAddress($dados['almoxEmail'], $dados['almoxNome']);

        if ($dados['copiaEmail1'] != "") {
            $mail->addCC($dados['copiaEmail1'], $dados['copiaNome1']);
        }
        if ($dados['copiaEmail2'] != "") {
            $mail->addCC($dados['copiaEmail2'], $dados['copiaNome2']);
        }
        if ($dados['copiaEmail3'] != "") {
            $mail->addCC($dados['copiaEmail3'], $dados['copiaNome3']);
        }
        if ($dados['copiaEmail4'] != "") {
            $mail->addCC($dados['copiaEmail4'], $dados['copiaNome4']);
        }
        if ($dados['copiaEmail5'] != "") {
            $mail->addCC($dados['copiaEmail5'], $dados['copiaNome5']);
        }
        if ($dados['copiaEmail6'] != "") {
            $mail->addCC($dados['copiaEmail6'], $dados['copiaNome6']);
        }

        $msgRetorno = "Solicitação enviada com sucesso.";
    } else {
        $msgRetorno = "Solicitação negada com sucesso.";
    }

    $mail->addReplyTo($dados['email'], 'ReplyTo'); //Endereço de resposta
    //$mail->addCC('brpaiva@icotel.net', 'Bruno Paiva');
    $mail->addBCC('ftsilva@icomontecnologia.com.br', 'Felipe Teixeira');
    //$mail->addBCC('adsantos@icotel.net', 'Alana Deivlan');

    $mail->addAttachment($dados['anexo']);         // Add attachments

    $arquivos = "select sma, anexo from sma_anexo where sma='{$solicitacao}'";

    if ($result = $mysqli->query($arquivos)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

            $caminho = "../sma_anexo/" . $row['anexo'];
            $mail->addAttachment($caminho);
        }
    }
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $dados['assunto'];
    $mail->Body = $body . $tabela;
    $mail->AltBody = $body;


    if (!$mail->send()) {
        $msg_r = "Solicitação não enviada, <b>Senha de e-mail não adicionada ou incorreta!</b>";
        $erro = "1";
    } else {
        $msg_r = $msgRetorno;
        $erro = "0";
    }

    $arr = array("erro" => $erro, "msg" => $msg_r);

    return $arr;
}

function dados_email($mysqli, $re, $arquivo, $dados, $obs)
{

    $sigla = $dados['Sigla'];
    $Os = $dados['Os'];
    $status = $dados['status'];
    $tipo = $dados['tipo'];
    $solicitacao = $dados['solicitacao'];

    $body = $dados['msg'];

    if ($obs === "|" || $obs === "" || $obs===" | ") {
        $obs = "Sem observações.";
    }

    $obs = "<p style='text-left: right;color:#0016b0;'>Observação: " . $obs . "</p><br>";
    if ($status === "4") {
        $almox = "N";
        $status = " negada.";
    } else 
    if ($status === "3") {
        $almox = "S";
        $status = " aprovada.";
    }

    $body = str_replace("\n", "<br><br>", $body . $status . "<br>" . $obs);

    if ($tipo === "2") {
        $assunto = "SOLICITAÇÃO DE DEVOLUÇÃO DE MATERIAL " . $sigla . "_ OS: " . $Os;
    } else {
        $assunto = "SOLICITAÇÃO DE MATERIAL " . $sigla . "_ OS: " . $Os;
    }

    $sql = "SELECT usr.email as email, ema_senha as senha, ema_smtp as smtp, usr.nome as nome from email INNER JOIN usuario usr on usr.re=ema_re where usr.re=" . $re;

    $result = $mysqli->query($sql);

    $row = $result->fetch_assoc();

    $sql_solicitante = "SELECT u.email as email, u.nome as nome FROM sma_solicitacao s inner join usuario u on u.re=s.re WHERE s.id='{$solicitacao}'";
    $result_solicitante = $mysqli->query($sql_solicitante);

    $dados_solicitante = $result_solicitante->fetch_assoc();

    $arr = array(
        "email" => $row['email'],
        "nome" => $row['nome'],
        "senha" => $row['senha'],
        "smtp" => $row['smtp'],
        "status" => $status,
        "almox" => $almox,
        "re" => $re,
        "almoxEmail" => $dados['almoxEmail'],
        "almoxNome" => $dados['almoxNome'],
        "copiaEmail1" => $dados['copiaEmail1'],
        "copiaNome1" => $dados['copiaNome1'],
        "copiaEmail2" => $dados['copiaEmail2'],
        "copiaNome2" => $dados['copiaNome2'],
        "copiaEmail3" => $dados['copiaEmail3'],
        "copiaNome3" => $dados['copiaNome3'],
        "copiaEmail4" => $dados['copiaEmail4'],
        "copiaNome4" => $dados['copiaNome4'],
        "copiaEmail5" => $dados['copiaEmail5'],
        "copiaNome5" => $dados['copiaNome5'],
        "copiaEmail6" => $dados['copiaEmail6'],
        "copiaNome6" => $dados['copiaNome6'],
        "solicitanteEmail" => $dados_solicitante['email'],
        "solicitanteNome" => $dados_solicitante['nome'],
        "assunto" => $assunto,
        "body" => $body,
        "anexo" => $arquivo
    );
    return $arr;

    $result->close();
}
function bodyHtml($mysqli, $solicitacao)
{
    $sql = "select sol.re as colaboradorRe, usr.nome as colaboradorNome, usr.email as colaboradorEmail, usr.telefone as colaboradorTelefone, cn.endereco as colaboradorEndereco, sol.data as data, coo.re as coordenadorRe, coo.nome as coordenadorRe, sol.data as Prazo, sol.os as Os, site.sigla as Sigla, ftip.nome as fatura from sma_solicitacao sol inner join usuario usr on usr.re=sol.re inner join cn cn on cn.id=usr.cn inner join usuario coo on coo.re=usr.supervisor inner join site site on site.id=sol.site inner join sma_fatura_tipo ftip on ftip.id=sol.tipo_fatura where sol.id='{$solicitacao}'";
    $sql_itens = "SELECT si.id as id, pa.numero as pa, pa.descricao as descricao, si.quantidade as quantidade, ptu.nome as unidade FROM sma_solicitacao_itens si inner join sma_pa pa on pa.id=si.pa inner join sma_pa_tipo_unidade ptu on ptu.id=pa.pa_tipo_unidade where si.solicitacao='{$solicitacao}'";

    ///TABELA
    $result = $mysqli->query($sql_itens);

    ///Dados
    $result2 = $mysqli->query($sql);

    $row2 = $result2->fetch_assoc();

    $table = "<table>"
        . "<thead><tr>"
        . "<th>ID</th>"
        . "<th>PA</th>"
        . "<th>ITEM</th>"
        . "<th>UNIDADE</th>"
        . "<th>QUANTIDADE</th>"
        . "</tr></thead>";

    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $pa_item = $row['descricao'];

        $table .= "<tr>";
        $table .= "<td>" . $row['id'] . "</td>";
        $table .= "<td>" . $row['pa'] . "</td>";
        $table .= "<td>" . strtoupper($pa_item) . "</td>";
        $table .= "<td>" . $row['unidade'] . "</td>";
        $table .= "<td>" . $row['quantidade'] . "</td>";
        $table .= "</tr>";
    }
    $tel = $row2['colaboradorTelefone'];
    if (strlen($tel) < 11) {
        $telefone = $tel;
    } else {
        $telefone = "(" . $tel[0] . $tel[1] . ") " . $tel[2] . "-" . $tel[3] . $tel[4] . $tel[5] . $tel[6] . "-" . $tel[7] . $tel[8] . $tel[9] . $tel[10];
    }

    $result->close();
    //$mysqli->close();
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
	 	<h1>Solicitação de Material - FATURA " . $row2['fatura'] . "</h1>
                <h2>ICOMON</h2>
	 	<p class='center sub-titulo'>Solicitação:<strong>" . $solicitacao . "</strong>
	 	</p>" . $table . "</div>" .
        "<p class='direita'> Data da envio: " . $row2['data'] . "</p>
	 	<p class='ln_assinatura'>Responsável pelo envio: <strong>" . $row2['colaboradorNome'] . "</strong>
<br>Telefone: " . $telefone . "
<br>E-mail:" . $row2['colaboradorEmail'] . "
<br>Endereço: " . $row2['colaboradorEndereco'] . "</p>
</body></html>";

    $body = $pagina . $html;

    return $body;
}
