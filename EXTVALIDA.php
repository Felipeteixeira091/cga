<?php

include "l_sessao.php";
include 'json_encode.php';

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$re = $_SESSION['re'];
date_default_timezone_set("America/Fortaleza");
$data = date('Y-m-d');
$hora = date('H:i', time());

$acao = $txtTitulo['acao'];

if ($acao === "notaStatus") {
    notaStatus($mysqli);
} else
if ($acao === "notaDetalhe") {

    $nota = $txtTitulo['nota'];

    notaDetalhe($mysqli, $nota);
} else
if ($acao === "notaEnviaLista") {

    notaEnviaLista($mysqli);
} else
if ($acao === "notaDestino") {
    notaDestino($mysqli, $re);
} else 
if ($acao === "notaDestinoRemove") {

    $id = $txtTitulo['id'];

    notaDestinoRemove($mysqli, $id);
} else
if ($acao === "notaDestinoAdd") {

    $nome = $txtTitulo['nome'];
    $email = $txtTitulo['email'];
    $tipo = $txtTitulo['tipo'];

    notaDestinatarioAdd($mysqli, $nome, $email, $tipo, $re);
} else
if ($acao === "notaProcura") {

    $txt = $txtTitulo['txt'];
    $status = $txtTitulo['status'];
    $data1 = $txtTitulo['data1'];
    $data2 = $txtTitulo['data2'];
    $acesso = "";

    notaProcura($mysqli, $txt, $data1, $data2, $status, $acesso, $re);
} else
if ($acao === "notaUpdate") {

    $nota = $txtTitulo['nota'];
    $status = $txtTitulo['status'];
    $obs = addslashes($txtTitulo['obs']);
    $dados = notaDados($mysqli, $nota);
    $erro = "1";

    //Validar notas
    $p = permissao($mysqli, "59", $re);

    //Aprovar notas
    $p1 = permissao($mysqli, "63", $re);

    if ($p1 === 1 and $status === "7") {
        $status = "3";
    }

    if ($p === 0) {
        $msg = "Você não tem permissão para validar notas.";
    } else
    if ($dados['status'] === "7" and $status === "7") {
        $msg = "Nota já válida, aguardando aprovação";
    } else {

        $update_nota = update_nota($mysqli, $re, $nota, $status, $obs);

        if ($update_nota === "1") {

            $erro = "0";
            nota_vida($mysqli, $nota, $data, $hora, $status, $re, $obs);
            $msg = "Nota atualizada com sucesso.";
        } else {
            $msg = "Erro ao validar/aprovar nota.";
        }
    }

    $retorno = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($retorno);
} else
if ($acao === "notaEnvia") {

    $retorno;
    $re_resp = $_SESSION["re"];

    $p = permissao($mysqli, "62", $re_resp);

    if ($p === 0) {

        $msg = "Você não tem permissão.";
        $retorno = array("erro" => "1", "msg" => $msg);
    } else {
        $notas = qtd_notas($mysqli);
        if ($notas['qtd'] > 0) {

            $body = monta_body($mysqli, $data, $re_resp);
            $email = email($mysqli, $re_resp, $body['body'], $body['notas']);
            $update = update($mysqli, $body['notas'], $re_resp, $data, $hora);

            $retorno = $email;
        } else {

            $retorno = $notas;
        }
    }

    echo JsonEncodePAcentos::converter($retorno);
    $mysqli->close();
}
function permissao($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
function update_nota($mysqli, $re, $nota, $status, $obs)
{

    if ($status === "5") {
        $sql = "update ext_nota set anexo='PENDENTE', movimentacao='{$re}', status='{$status}', obs='{$obs}' where id='{$nota}'";
    } else {
        $sql = "update ext_nota set movimentacao='{$re}', status='{$status}', obs='{$obs}' where id='{$nota}'";
    }
    if ($mysqli->query($sql)) {
        return "1";
    } else {
        return "0";
    }
}
function nota_vida($mysqli, $nota, $data, $hora, $status, $re, $obs)
{
    $sql = "insert into ext_nota_vida (nota, data, hora, status, re, obs) values ('{$nota}','{$data}', '{$hora}', '{$status}', '{$re}', '{$obs}')";
    $mysqli->query($sql);
}
function notaStatus($mysqli)
{
    $sql = "select id, nome from ext_nota_status order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function notaDetalhe($mysqli, $nota)
{
    $sql = "select n.id as id, cn.nome as cn, s.re as solicitante_re, s.nome as solicitante_nome, c.nome as colaborador_nome, c.re as colaborador_re, sit.sigla as site, n.data as data, n.hora as hora, n.dataNota as dataNota, n.status as IdStatus, ns.nome as status, concat('R$ ',n.valor) as valor, nt.nome as tipo, n.os as os, nm.nome as motivo, n.anexo as anexo from ext_nota n inner join usuario s on s.re=n.re inner join usuario c on c.re=n.colaborador inner join site sit on sit.id=n.site inner join ext_nota_status ns on ns.id=n.status inner join cn cn on cn.id=sit.cn inner join ext_nota_tipo nt on nt.id=n.tipo inner join ext_nota_motivo nm on nm.id=n.motivo where n.id=" . $nota . "";
    $sql_historico = "select nv.id as id, nv.nota as nota, nv.data as data, nv.hora as hora, ns.nome as status, u.nome as nome, nv.obs from ext_nota_vida nv inner join ext_nota_status ns on ns.id=nv.status inner join usuario u on u.re=nv.re where nota='{$nota}' order by data, hora";

    $res = $mysqli->query($sql);
    $detalhe = $res->fetch_assoc();

    $result3 = $mysqli->query($sql_historico);

    $hs = "<table class='table table-sm table-striped w-auto'>";
    $hs .= "<thead class='thead-dark'>";
    $hs .= "<tr>";
    $hs .= "<th scope='col'>STATUS</th>";
    $hs .= "<th scope='col'>DATA/HORA</th>";
    $hs .= "<th scope='col'>OBS</th>";
    $hs .= "</thead>";
    $hs .= "</tr>";
    while ($row3 = $result3->fetch_array(MYSQLI_ASSOC)) {

        $hs .= "<tr class='small'>";
        $hs .= "<td>" . $row3['status'] . "</td>";
        $hs .= "<td>" . $row3['data'] . " - " . $row3['hora'] . "</td>";
        $hs .= "<td>" . $row3['obs'] . "</td>";
        $hs .= "</tr>";
    }


    if ($hs == "") {
        $historico = "Solicitação sem histórico";
    } else {
        $historico = $hs;
    }

    $arr = array(
        "detalhe" => $detalhe,
        "historico" => $historico
    );

    $mysqli->close();

    echo JsonEncodePAcentos::converter($arr);
}
function notaEnviaLista($mysqli)
{
    $sql = "SELECT count(n.id) as qtd, SUM(REPLACE(REPLACE(n.valor, '.', ''), ',', '.')) as valor FROM ext_nota n WHERE n.status=3";
    $sqlLista = "select n.id as id, cn.nome as cn, s.re as solicitante_re, s.nome as solicitante_nome, c.nome as colaborador_nome, c.re as colaborador_re, sit.sigla as site, n.data as data, n.hora as hora, n.dataNota as dataNota, ns.nome as status, concat('R$ ',n.valor) as valor, nt.nome as tipo, n.os as os, nm.nome as motivo, n.anexo as anexo from ext_nota n inner join usuario s on s.re=n.re inner join usuario c on c.re=n.colaborador inner join site sit on sit.id=n.site inner join ext_nota_status ns on ns.id=n.status inner join cn cn on cn.id=sit.cn inner join ext_nota_tipo nt on nt.id=n.tipo inner join ext_nota_motivo nm on nm.id=n.motivo where n.status=3";

    $res = $mysqli->query($sql);
    $detalhe = $res->fetch_assoc();

    $result3 = $mysqli->query($sqlLista);

    $hs = "<table class='table table-sm table-striped w-auto'>";
    $hs .= "<thead class='thead-dark'>";
    $hs .= "<tr>";
    $hs .= "<th scope='col'>RE</th>";
    $hs .= "<th scope='col'>TIPO</th>";
    $hs .= "<th scope='col'>DATA</th>";
    $hs .= "<th scope='col'>VALOR</th>";
    $hs .= "</thead>";
    $hs .= "</tr>";
    while ($row3 = $result3->fetch_array(MYSQLI_ASSOC)) {

        $hs .= "<tr class='small'>";
        $hs .= "<td>" . $row3['colaborador_re'] . "</td>";
        $hs .= "<td>" . $row3['tipo'] . "</td>";
        $hs .= "<td>" . $row3['dataNota'] . "</td>";
        $hs .= "<td>" . $row3['valor'] . "</td>";
        $hs .= "</tr>";
    }
    if ($hs == "") {
        $lista = "Nenhuma solicitação a enviar.";
    } else {
        $lista = $hs;
    }

    $arr = array(
        "valorTotal"=> number_format($detalhe['valor'],2,',','.'),
        "detalhe" => $detalhe,
        "lista" => $lista
    );

    $mysqli->close();

    echo JsonEncodePAcentos::converter($arr);
}
function notaDestino($mysqli, $re)
{
    $sql = "select id, email, nome, colaborador, tipo from email_endereco where finalidade='nota' and colaborador=" . $re;

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function notaDestinoRemove($mysqli, $id)
{
    $sql = "delete from email_endereco WHERE id='{$id}'";
    $erro = "1";
    if ($mysqli->query($sql)) {

        $msg = "Destinatário removida com sucesso.";
        $erro = "0";
    } else {
        $msg = "Erro ao remover destinatário.";
    }

    $arr = array("erro" => $erro, "msg" => $msg);

    echo JsonEncodePAcentos::converter($arr);
}
function notaDestinatarioAdd($mysqli, $nome, $email, $tipo, $re)
{
    $vEmail = preg_match('/^[a-z0-9.]+@[a-z0-9]+\.[a-z]+(\.[a-z]+)?$/i', $email);

    $erro = "1";
    if (strlen($nome) < 5 or $nome === "" or !$nome) {
        $msg = "O nome informado é inválido.";
    } else
    if ($vEmail === 0) {
        $msg = "O e-mail informado é inválido.";
    } else
    if ($tipo === "0") {
        $msg = "Selecione um tipo de destinatário.";
    } else {

        $sql = "insert into email_endereco (email, nome, colaborador, tipo, finalidade) values ('{$email}','{$nome}','{$re}','{$tipo}','nota')";

        $mysqli->query($sql);

        $erro = "0";
        $msg = "Destinatário cadastrado com sucesso.";
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function notaProcura($mysqli, $txt, $data1, $data2, $status, $acesso, $re)
{

    $txt = strtoupper($txt);
    $where = "";

    if ($data1 != "") {
        $where .= " n.data >='{$data1}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($data2 != "") {
        $where .= " n.data <='{$data2}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($txt != "") {
        $where .= " s.nome='{$txt}' or s.re like '%" . $txt . "%' or c.nome like '%" . $txt . "%' or c.re like '%" . $txt . "%' or sit.sigla like '%" . $txt . "%'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($status > 0) {
        $where .= " n.status='{$status}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }
    $sql = "select n.id as id, cn.nome as cn, s.re as solicitante_re, s.nome as solicitante_nome, c.nome as colaborador_nome, c.re as colaborador_re, sit.sigla as site, n.data as data, n.hora as hora, n.dataNota as dataNota, ns.ico as ico, ns.nome as status, concat('R$ ',n.valor) as valor from ext_nota n inner join usuario s on s.re=n.re inner join usuario c on c.re=n.colaborador inner join site sit on sit.id=n.site inner join ext_nota_status ns on ns.id=n.status inner join cn cn on cn.id=sit.cn where" . $where . "";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
/////////////////  ---->ENVIO DE NOTAS<-------------///////////////////
function update($mysqli, $notas, $re_resp, $data, $hora)
{
    $qtd = count($notas);
    if ($qtd > 0) {

        for ($i = 0; $i < $qtd; $i++) {

            $nota = $notas[$i];
            $sql_up = "update ext_nota set status='6', obs='NOTA ENVIADA' where id='{$nota}'";
            $sql_vid = "insert into ext_nota_vida (nota, data, hora, status, re, obs) values ('{$nota}','{$data}', '{$hora}', '6', '{$re_resp}', 'NOTA ENVIADA')";

            $mysqli->query($sql_up);
            $mysqli->query($sql_vid);
        }
    }
    return $qtd;
}
function monta_body($mysqli, $date, $re_resp)
{

    $sql = "select n.id as id, cn.nome as cn, s.re as solicitante_re, s.nome as solicitante_nome, c.nome as colaborador_nome, c.re as colaborador_re, uc.nome as coordenador, c.re as colaborador_re, sit.sigla as site, n.data as data, n.hora as hora, n.dataNota as dataNota, ns.nome as status, n.valor as valor, nt.nome as tipo, n.os as os, nm.nome as motivo, n.anexo as anexo from ext_nota n inner join usuario s on s.re=n.re inner join usuario c on c.re=n.colaborador inner join usuario uc on uc.re=c.supervisor inner join site sit on sit.id=n.site inner join ext_nota_status ns on ns.id=n.status inner join cn cn on cn.id=sit.cn inner join ext_nota_tipo nt on nt.id=n.tipo inner join ext_nota_motivo nm on nm.id=n.motivo where n.status=3 order by c.nome asc";
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
        $table .= "<td>R$" . $row['valor'] . "</td>";
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
function email($mysqli, $re, $body, $notas)
{
    $qtd = count($notas);
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

    $destino = "select email, nome, colaborador, tipo from email_endereco where finalidade='nota' and colaborador=" . $re;

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

    $mail->isHTML(true);
    $mail->Subject = "REEMBOLSONS AUTORIZADOS, QUANTIDADE: " . $qtd; // Assunto e-mail
    $mail->Body = $body;
    $mail->AltBody = $body;

    $erro = "1";
    if (!$mail->send()) {
        $msg = "Notas não enviadas, erro:" . $mail->ErrorInfo;
    } else {
        $msg = $qtd . " Notas enviadas com sucesso.";
        $erro = "0";
    }
    $arr = array("erro" => $erro, "msg" => $msg);

    return $arr;
}
function qtd_notas($mysqli)
{
    $num = $mysqli->query("select id from ext_nota where status='3'")->num_rows;

    $msg = "";
    $erro = "1";
    if ($num === 0) {

        $msg = "Nenhuma nota pendente para envio.";
    } else {
        $msg = "Notas disponíveis para envio, " . $num . ".";
        $erro = "0";
    }

    $arr = array("erro" => $erro, "msg" => $msg, "qtd" => $num);

    return $arr;
}
function notaDados($mysqli, $nota)
{
    return $mysqli->query("select * from ext_nota where id='{$nota}'")->fetch_array(MYSQLI_ASSOC);
}
