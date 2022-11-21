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

if ($acao === "cnLista") {
    cnLista($mysqli, $re);
} else
if ($acao === "SiteProcura") {

    $txt = $txtTitulo['txt'];

    SiteProcura($mysqli, $txt);
} else
if ($acao === "notaMotivo") {

    notaMotivo($mysqli);
} else
if ($acao === "notaTipo") {
    notaTipo($mysqli);
} else
if ($acao === "selecionaSite") {

    $site = $txtTitulo['site'];
    SelecionaSite($mysqli, $site);
} else
if ($acao === "notaDetalhe") {

    $nota = $txtTitulo['nota'];

    notaDetalhe($mysqli, $nota);
} else
if ($acao === "notaProcura") {

    $txt = $txtTitulo['txt'];
    $data1 = $txtTitulo['data1'];
    $data2 = $txtTitulo['data2'];
    $cn = $txtTitulo['cn'];;

    notaProcura($mysqli, $txt, $data1, $data2, $cn, $re);
} else 
if ($acao === "criaNota") {

    $site = $txtTitulo['site'];
    $tipo = $txtTitulo['tipo'];
    $motivo = $txtTitulo['motivo'];
    $os = $txtTitulo['os'];
    $arq = $txtTitulo['arq'];
    $valor = $txtTitulo['valor'];
    $dataNota = $txtTitulo['data'];
    $horaNota = $txtTitulo['hora'];
    $obs =  addslashes($txtTitulo['obs']);

    notaCria($mysqli, $re, $data, $hora, $site, $tipo, $motivo, $os, $arq, $valor, $dataNota, $horaNota, $obs);
    $mysqli->close();
}

function SiteProcura($mysqli, $txt)
{
    $txt = strtoupper($txt);

    $sql = "SELECT s.id as id, s.sigla as sigla, s.descricao as descricao, st.nome as tipo, cn.nome as cn, uf.sigla as uf FROM site s inner join site_tipo st on st.id=s.tipo inner join cn cn on cn.id=s.cn inner join uf on uf.id=s.estado WHERE s.sigla like '%" . $txt . "%'";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function cnLista($mysqli, $re)
{
    $sql = "select cn.regiao as regiao from usuario u inner join cn on cn.id=u.cn where u.re='{$re}'";
    $result = $mysqli->query($sql)->fetch_array();
    $regiao = $result['regiao'];

    $sql = "select id, nome from cn where regiao='{$regiao}' order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function notaTipo($mysqli)
{
    $sql = "select id, nome from ext_nota_tipo order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function notaMotivo($mysqli)
{
    $sql = "select id, nome from ext_nota_motivo order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function SelecionaSite($mysqli, $site)
{
    $sql = "SELECT s.id as id, s.sigla as sigla, st.nome as tipo FROM site s inner join site_tipo st on st.id=s.tipo WHERE s.id='{$site}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $result->close();

    echo JsonEncodePAcentos::converter($row);

    $mysqli->close();
}
function permissao($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
function notaCria($mysqli, $re, $data, $hora, $site, $tipo, $motivo, $os, $arq, $valor, $dataNota, $horaNota, $obs)
{
    $erro = "1";

    $p = permissao($mysqli, "87", $re);

    if ($p === 0) {

        $msg = "Você não tem permissão.";
    } else

    if ($site === "0" || $site === "" || !$site) {
        $msg = "Necessário selecionar o site relacionado ao custo gerado.";
    } else
    if (!$arq || strlen($arq) <= 20) {
        $msg = "Necessário carregar o arquivo relacionado à nota.";
    } else
    if ($tipo === "0") {
        $msg = "Necessário selecionar o tipo de custo.";
    } else
    if ($motivo === "0") {
        $msg = "Necessário selecionar o motivo do custo gerado.";
    } else 
    if ($motivo === "1" && $os === "") {
        $msg = "Necessário informar o número do <b>TA</b> relacionado ao custo gerado.";
    } else 
    if ($motivo === "2" && $os === "") {
        $msg = "Necessário informar o número do <b>TP</b> relacionado ao custo gerado.";
    } else 
    if ($motivo === "3" && $os === "") {
        $msg = "Necessário informar o número da <b>OS</b> relacionado ao custo gerado.";
    } else
    if ($motivo === "4" && $os === "") {
        $msg = "Necessário informar o número da <b>OS</b> relacionado ao custo gerado.";
    } else
    if (empty($valor) or $valor === 0) {
        $msg = "Necessário informar o valor utilizado.";
    } else
    if (!$dataNota || ValidaData($dataNota) === "0") {
        $msg = "Necessário selecionar informar a data da utilização.";
    } else
    if (!$horaNota  || $horaNota === "0") {
        $msg = "Necessário selecionar informar a hora da utilização.";
    } else
    if ($obs === "" || strlen($obs) < 10) {
        $msg = "A observação informada é inválida.";
    } else {
        $erro = "0";
        $msg = "informação cadastrada com sucesso.";

        $sql = "insert into ccc(re, tipo, motivo, os, site, data, hora, data_utilizacao, hora_utilizacao, valor, observacao) values('{$re}', '{$tipo}', '{$motivo}','{$os}', '{$site}', '{$data}', '{$hora}', '{$dataNota}','{$horaNota}', '{$valor}','{$obs}')";
        $mysqli->query($sql);
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function ValidaData($dat)
{
    $data = explode("-", "$dat"); // fatia a string $dat em pedados, usando / como referência
    $y = $data[0];
    $m = $data[1];
    $d = $data[2];

    $res = checkdate($m, $d, $y);
    if ($res == 1) {
        return "1";
    } else {
        return "0";
    }
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
function notaDetalhe($mysqli, $id)
{
    $sql = "select c.id as id, concat('R$ ',c.valor) as valor, c.data_utilizacao as dataU, c.hora_utilizacao horaU, u.re as re, u.nome as nome, cn.nome as cn, et.nome as tipo, em.nome as motivo, c.os as os, s.sigla as site, c.observacao as obs, c.data as data, c.hora as hora from ccc c inner join usuario u on u.re=c.re inner join site s on s.id=c.site inner join cn on cn.id=s.cn inner join ext_nota_tipo as et on et.id=c.tipo inner join ext_nota_motivo em on em.id=c.motivo where c.id='{$id}'";

    $res = $mysqli->query($sql);
    $detalhe = $res->fetch_assoc();


    $arr = array(
        "detalhe" => $detalhe
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
        "valorTotal" => number_format($detalhe['valor'], 2, ',', '.'),
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
function notaProcura($mysqli, $txt, $data1, $data2, $cn, $re)
{

    $txt = strtoupper($txt);
    $where = "";

    if ($data1 != "") {
        $where .= " c.data_utilizacao >='{$data1}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($data2 != "") {
        $where .= " c.data_utilizacao <='{$data2}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($txt != "") {
        $where .= " u.nome='{$txt}' or u.re like '%" . $txt . "%' or c.observacao like '%" . $txt . "%'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }
    $sql = "select c.id as id, concat('R$ ',c.valor) as valor, c.data_utilizacao as dataU, c.hora_utilizacao horaU, u.re as re, u.nome as nome, cn.nome as cn from ccc c inner join usuario u on u.re=c.re inner join site s on s.id=c.site inner join cn on cn.id=s.cn where" . $where . "";

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

    $valor_total = $mysqli->query("SELECT count(n.id) as qtd, SUM(REPLACE(REPLACE(n.valor, '.', ''), ',', '.')) as valor FROM ext_nota n WHERE n.status=3")->fetch_assoc();

    $tabela = $mysqli->query($sql);
    $result2 = $mysqli->query($sql_dados);
    $row2 = $result2->fetch_assoc();
    $tel = $row2['telefone'];
    $telefone = "(" . $tel[0] . $tel[1] . ") " . $tel[2] . "-" . $tel[3] . $tel[4] . $tel[5] . $tel[6] . "-" . $tel[7] . $tel[8] . $tel[9] . $tel[10];

    $table = "<table style='width: 100%; font-size: 12px; color: #444444; margin-top: 2px; margin-left: auto; margin-right: auto;border: solid 1px #17A2B8; border-collapse: collapse'>"
        . "<thead><tr>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>RE</th>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>NOME</th>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>COORDENADOR</th>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>DATA</th>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>TIPO</th>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>MOTIVO</th>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>OS/TA</th>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>SITE</th>"
        . "<th style='font-size: 14px;font-weight: normal;background-color: #17A2B8;color:#FFFFFF;border: none;'>VALOR</th>"
        . "</tr></thead>";

    $total_geral = number_format($valor_total['valor'], 2, ',', '.');

    $notas = array();

    $c = 1;
    while ($row = $tabela->fetch_array(MYSQLI_ASSOC)) {

        if ($c === 1) {
            $cor = "#C7D9E7";
            $c = 2;
        } else {
            $cor = "#EEEEEE";
            $c = 1;
        }

        $table .= "<tr>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>" . $row['colaborador_re'] . "</td>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>" . strtoupper(utf8_encode($row['colaborador_nome'])) . "</td>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>" . strtoupper(utf8_encode($row['coordenador'])) . "</td>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>" . $row['dataNota'] . "</td>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>" . $row['tipo'] . "</td>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>" . $row['motivo'] . "</td>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>" . $row['os'] . "</td>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>" . $row['site'] . "</td>";
        $table .= "<td style='text-align: center; font-size: 12px;font-weight: normal;background-color: " . $cor . ";color:#444444;border: none;'>R$" . $row['valor'] . "</td>";
        $table .= "</tr>";

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
        border: 0px;
        padding: 4px;
        line-height: 10px;
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
    .center{
        text-align: center;
    }
          </style>
        </head>
        <body>';

    $html = "
         <div class='palco'>
             <h1>Notas aprovadas</h1>
             <p style='text-align: center; font-size: 18px'><strong>VALOR TOTAL:</strong><span style='color:#FF4D4D'> R$ " . $total_geral . "</span> | <strong>QUANTIDADE:</strong> <span style='color:#FF4D4D'>" . $valor_total['qtd'] . "</span>
             </p>" . $table . "</div>" .
        "<p style='text-align: right;color:#878a85;'> Data da envio: " . $date . "</p>
             <span style='color:#878a85;line-height: 10px;font-size: 11px;'><p>Responsável pelo envio: <strong>" . $row2['nome'] . "</strong>
    <br>Telefone: " . $telefone . "
    <br>E-mail:" . $row2['email'] . "</span>
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
    $mail->Subject = "REEMBOLSOS AUTORIZADOS, QUANTIDADE: " . $qtd; // Assunto e-mail
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
