<?php

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";

require_once '../lib/PHPMailer/PHPMailerAutoload.php';

session_start();
$re_cadastro = $_SESSION["re"];
$data = date('Y-m-d');
$hora = date('H:i', time());

$acao = $txtTitulo['acao'];

$erro = "0";
$msg = "";


if ($acao === "dados") {

    $id = $txtTitulo['id'];
    dados($mysqli, $id);
} else
if ($acao === "detalhe_Tipo") {

    tipoLista($mysqli);
} else
if ($acao === "detalhe_Medida") {

    tipoUnidadeLista($mysqli);
} else
if ($acao === "detalhe_Gas") {

    tipoGasLista($mysqli);
} else
if ($acao === "detalhe_Sobressalente") {

    sobressalenteLista($mysqli);
} else
if ($acao === "tipoLista") {

    tipoLista($mysqli);
} else
if ($acao === "tipoUnidadeLista") {
    tipoUnidadeLista($mysqli);
} else
if ($acao === "tipoGasLista") {
    tipoGasLista($mysqli);
} else
if ($acao === "editaPA") {

    editaPA(
        $mysqli,
        $re_cadastro,
        $txtTitulo['id'],
        $txtTitulo['numero'],
        strtoupper(str_replace($especial, $remove, $txtTitulo['descricao'])),
        $txtTitulo['tipo'],
        $txtTitulo['sobressalente'],
        $txtTitulo['medida'],
        $txtTitulo['gas'],
        $txtTitulo['obs'],
        $txtTitulo['status']
    );
} else
if ($acao === "cadastroPA") {

    $especial = array("'", ";", "/");
    $remove   = array("", ":", "");

    cadastroPA(
        $mysqli,
        $txtTitulo['numero'],
        strtoupper(str_replace($especial, $remove, $txtTitulo['descricao'])),
        $txtTitulo['tipo'],
        ucfirst(strtolower($txtTitulo['observacoes'])),
        $re_cadastro,
        $txtTitulo['sobressalente'],
        $txtTitulo['medida'],
        $txtTitulo['gas'],
        $txtTitulo['status'],
        $data,
        $hora
    );
} else 
if ($acao === "PAProcura") {

    $txt = $txtTitulo['txt'];
    $tipo = $txtTitulo['tipo'];
    paProcura($mysqli, $txt, $tipo);
}
function editaPA($mysqli, $re, $id, $pa, $descricao, $tipo, $sobressalente, $medida, $gas, $obs, $status)
{

    $erro = "1";

    $p = permissaoVerifica($mysqli, "42", $re);

    if ($p === 0) {

        $msg = "Você não tem permissão para editar PA's.";
    } else {

        $sql = "SELECT pa.id as id, pa.numero as numero, pa.descricao as descricao, pa.observacoes as obs, pa.tipo as tipo, pa.sobressalente as sobressalente, pa.pa_tipo_unidade as unidade, pa.status as status FROM sma_pa pa WHERE pa.id='{$id}'";

        $result = $mysqli->query($sql);
        $dados = $result->fetch_array();

        $verifica_pa = $mysqli->query("select pa from sma_solicitacao_itens where pa='{$id}'")->num_rows;


        if ($verifica_pa > 0 and $pa <> $dados['numero']) {

            $msg = "Esse número de PA não pode ser alterado, já existem <b>" . $verifica_pa . "</b> solicitação(ões) com o mesmo.";
        } else
        if ($pa <> $dados['numero'] and paVerifica($mysqli, $pa) > 0) {

            $result_erro = $mysqli->query("SELECT pa.id as id, pa.numero as numero, pa.descricao as descricao, pa.observacoes as obs, pa.tipo as tipo, pa.sobressalente as sobressalente, pa.pa_tipo_unidade as unidade FROM sma_pa pa WHERE pa.numero='{$pa}'");
            $dados_erro = $result_erro->fetch_array();

            $msg = "Esse número de PA está atribuído ao ítem:<br> <b> " . $dados_erro['descricao'] . "</b>.";
        } else {

            if ($gas === "ND") {

                $msg = "O ítem de código <b> " . $pa . "</b>, é um tipo de GÁS?";
            } else {
                if ($descricao === "ND") {
                    $descricao = $dados['descricao'];
                }
                if ($tipo === "ND") {
                    $tipo = $dados['tipo'];
                }
                if ($sobressalente === "ND") {
                    $sobressalente = $dados['sobressalente'];
                }
                if ($medida === "ND") {
                    $medida = $dados['unidade'];
                }
                if ($status === "ND") {
                    $status = $dados['status'];
                }
                if ($obs === "" || !$obs) {
                    $obs = $dados['obs'];
                }
                $erro = "0";
                $msg = "O ítem de código <b> " . $pa . "</b>, foi editado com sucesso.";

                $sql_edita = "update sma_pa set numero='{$pa}', descricao='{$descricao}', tipo='{$tipo}', sobressalente='{$sobressalente}', pa_tipo_unidade='{$medida}', gas='{$gas}', observacoes='{$obs}', status='{$status}' where id='{$id}'";
                $mysqli->query($sql_edita);
            }
        }
    }
    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function dados($mysqli, $id)
{
    $sql = "SELECT pa.id as id, pa.numero as numero, pa.descricao as descricao, pa.observacoes as obs, pa.tipo as tipo, pa.sobressalente as sobressalente, pa.pa_tipo_unidade as unidade, pa.status as status, u.nome as cadastro, pa.gas as gas FROM sma_pa pa inner join usuario u on u.re=pa.re WHERE pa.id='{$id}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    $result->close();

    echo JsonEncodePAcentos::converter($row);

    $mysqli->close();
}
function sobressalenteLista($mysqli)
{
    $sql = "select id, nome from sma_sobressalente";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function tipoLista($mysqli)
{
    $sql = "select id, nome from sma_pa_tipo";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function tipoUnidadeLista($mysqli)
{
    $sql = "select id, nome from sma_pa_tipo_unidade";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function tipoGasLista($mysqli)
{
    $sql = "select id, nome from gas_tipo order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function cadastroPA($mysqli, $numero, $descricao, $tipo, $observacao, $re_cadastro, $sobressalente, $medida, $gas, $status, $data, $hora)
{
    $erro = "1";
    $p = permissaoVerifica($mysqli, "75", $re_cadastro);

    if ($p === 0) {
        $status = "0";
    } else {
        $status = "1";
    }

    if (!$numero || $numero === "") {
        $msg = "<i class='icon-attention'></i> Necessário informar o número do PA.";
    } else
    if (paVerifica($mysqli, $numero) > 0) {

        $msg = "<i class='icon-attention'></i> PA já cadastrado.";
    } else
    if (!$descricao || $descricao === "") {
        $msg = "<i class='icon-attention'></i> Necessário o nome do PA.";
    } else
    if ($tipo === "0") {
        $msg = "<i class='icon-attention'></i> Necessário informar o tipo do PA.";
    } else 
    if ($sobressalente === "2") {

        $msg = "<i class='icon-attention'></i> Necessário informar se o PA é sobressalente.";
    } else
    if ($medida === "0") {
        $msg = "<i class='icon-attention'></i> Necessário informaro a unidade de medida do PA.";
    } else
    if ($gas === "ND") {
        $msg = "<i class='icon-attention'></i> O PA é um tipo de GÁS?";
    } else
    if ($status === "ND") {
        $msg = "<i class='icon-attention'></i> Necessário informar o status do PA.";
    } else {
        $pa = "PA" . $numero;
        $sql = "insert into sma_pa (numero, descricao, tipo, sobressalente, observacoes, re, pa_tipo_unidade, status) values ('{$pa}', '{$descricao}', '{$tipo}','{$sobressalente}','{$observacao}', '{$re_cadastro}', '{$medida}', '{$status}')";
        $mysqli->query($sql);

        $suporte = array(
            "colaborador" => dados_colaborador($mysqli, $re_cadastro),
            "pa" => $numero,
            "descricao" => $descricao
        );

        $body = bodyHtml($suporte, $data, $hora);

        enviar($suporte, $body, $numero, $mysqli);

        $erro = "0";

        if ($p > 0) {
            $msg = "<i class='icon-ok-1'></i> PA cadastrado com sucesso.";
        } else {
            $msg = "<i class='icon-clock'></i> Aguarde aprovação.";
        }
    }

    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}
function dados_colaborador($mysqli, $re)
{
    $sql = "SELECT u.re as re, u.nome as nome, u.email as email, u.telefone as telefone FROM usuario u WHERE re='{$re}'";

    $result = $mysqli->query($sql);
    $arr = $result->fetch_array();

    $result->close();
    return $arr;

    $mysqli->close();
}
function bodyHtml($dados, $data, $hora)
{
    $colaborador = $dados['colaborador'];

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
    text-decoration: underline;
    text-align: center;
    font-weight: bold
}
.direita{
    text-align: right;
    color:#878a85;
}
.dados_PA {font-weight: bold;text-align: center;}
.dados_PA_status {font-weight: bold; color: red;}

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
         <h1>CADASTRO DE NOVO PA</h1>
         <h2>Solicitante: " . $colaborador['nome'] . "</h2>
	 	<p class='sub-titulo'>Dados do PA</p>
         <p class='dados_PA'>PA: " . $dados['pa'] . "</p>
         <p class='dados_PA'>DESCRIÇÃO: " . $dados['descricao'] . "</p>
         <p class='dados_PA_status'>Aguarde aprovação.</p>
         </div>" .
        "<p class='direita'> Data/Hora da solicitação: " . $data . " " . $hora . "</p>
</body></html>";

    $body = $pagina . $html;

    return $body;
}
function enviar($suporte, $body, $pa, $mysqli)
{
    $solicitante = $suporte['colaborador'];

    $email_suporte = "suporte@solicitacaooem.com.br"; //$dados['servidorSmtp']; // Specify main and backup SMTP servers
    $nome_suporte = "SUPORTE SMA";
    $senha_suporte = "&fKC0FcYp]]8"; //    $dados['servidorSenha'];              // SMTP password
    $smtp_suporte = "mail.solicitacaooem.com.br"; //
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
    $mail->addCC("adsantos@icomontecnologia.com.br", "Alana Deivlan");

    $mail->addAddress($solicitante['email'], $solicitante['nome']);

    $mail->addReplyTo($solicitante['email'], 'ReplyTo'); //Endereço de resposta


    if ($result = $mysqli->query("select p.colaborador as re, u.nome as nome, u.email as email from permissao p inner join usuario u on u.re=p.colaborador WHERE p.funcao=67")) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

            $mail->addBCC($row['email'], $row['nome']);
        }
    }

    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = "Cadastro PA: " . $pa;
    $mail->Body = $body;
    $mail->AltBody = $body;

    if (!$mail->send()) {
        $msg_r = "Solicitação não enviada, erro, verifique a senha do seu e-mail na tela inicial." . $mail->ErrorInfo;
        $erro = "1";
    } else {
        $msg_r = "Solicitação enviada com sucesso!";
        $erro = "0";
    }
    $arr = array("erro" => $erro, "msg" => $msg_r);

    return $arr;
}

function paVerifica($mysqli, $pa)
{

    $pa1 = "PA" . $pa;

    $num = $mysqli->query("select numero from sma_pa where numero='{$pa}' or numero='{$pa1}'")->num_rows;
    return $num;
}
function paProcura($mysqli, $txt, $tipo)
{
    $txt = strtoupper($txt);
    $where = "";
    if ($txt === "PENDENTE*") {
        $where = " pa.status='0'";
    } else
    if ($txt === "" && $tipo != "0") {

        $where = " pa.tipo='{$tipo}'";
    } else 
if ($txt != "" && $tipo != "") {

        $where = " pa.tipo='{$tipo}' or pa.numero like '%" . $txt . "%' or pa.descricao like '%" . $txt . "%' or tip.nome like '%" . $txt . "%'";
    }

    $sql = "select pa.id as id, pa.numero as numero, pa.descricao as descricao, tip.nome as tipo, if(pa.sobressalente=1,'SIM','NÃO') as sobressalente, u.nome as cadastro from sma_pa pa inner join sma_pa_tipo tip on tip.id=pa.tipo inner join usuario u on u.re=pa.re WHERE" . $where . " order by tipo, numero asc";

    $num = $mysqli->query($sql)->num_rows;

    if ($num === 0) {

        $myArray = array("erro" => "1", "msg" => "<i class='icon-attention'></i> Nenhuma correspondência.");
    } else {
        $myArray = array();
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $myArray[] = $row;
            }
        }
    }

    echo JsonEncodePAcentos::converter($myArray);
}
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
