<?php
include_once "conf/conexao2.php";
include_once "json_encode.php";

session_start();
if (!isset($_SESSION["nome"]) || !isset($_SESSION["email"])) {
    header("Location: ../");
    exit;
}
$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$acao = $txtTitulo['acao'];

date_default_timezone_set('America/Sao_Paulo');
$data = date('Y-m-d');
$re = $_SESSION['re'];

if ($acao === "trocaSenha") {
    $senha1 = $txtTitulo['senha1'];
    $senha2 = $txtTitulo['senha2'];
    $tipo = $txtTitulo['tipo'];

    $erro = "1";
    if ($tipo === "tipo") {
        $msg_erro = "Necessário selecionar o tipo de senha que deseja alterar.";
    } else
    if (empty($senha1)) {
        $msg_erro = "Necessário informar a senha.";
    } else
    if (empty($senha2)) {
        $msg_erro = "Necessário informar a confirmação de senha.";
    } else
    if ($senha1 != $senha2) {
        $msg_erro = "A senha e confirmação de senha não conferem.";
    } else {
        if ($tipo === "oem") {
            altera_senha($mysqli, $re, $senha1);

            $msg_erro = "Senha alterada com sucesso.";

        } else if ($tipo === "outlook") {

            altera_senha_outlook($mysqli, $re, $senha1);
            $msg_erro = "Senha outlook alterada com sucesso.";
        }

        $erro = "0";
    }
    $arr = array("erro" => $erro, "msg" => $msg_erro);
    echo JsonEncodePAcentos::converter($arr);
}
function altera_senha($mysqli, $re, $senha)
{
    $senha = md5($senha);

    $sql = "update usuario set senha='{$senha}' WHERE re='{$re}'";

    $mysqli->query($sql);
}
function altera_senha_outlook($mysqli, $re, $senha)
{

    $verifica = outlookVerifica($mysqli, $re);

    if ($verifica > 0) {

        $sql = "update email set ema_senha='{$senha}' WHERE ema_re='{$re}'";
    } else {
        $sql = "insert into email (ema_re, ema_senha, ema_smtp) value ('{$re}', '{$senha}', 'smtp.office365.com')";
    }

    $mysqli->query($sql);
}
function outlookVerifica($mysqli, $re)
{
    $sql = "select ema_id from email where ema_re='{$re}'";
    $num = $mysqli->query($sql)->num_rows;
    return $num;
}
