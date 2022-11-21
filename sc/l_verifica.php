<?php

include_once "l_sessao.php";

include_once "conf/conexao2.php";
include_once "json_encode.php";

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$acao = $txtTitulo['acao'];


if ($acao === "dados") {

    $dados = dadosColaborador($mysqli, $_SESSION['re']);
    echo JsonEncodePAcentos::converter($dados);
}

function dadosColaborador($mysqli, $re)
{
    $sql = "select u.re as re, u.nome as nome, u.email as email, u.telefone as telefone, c.nome as cargo, uu.nome as supervisor, cn.endereco as endereco from usuario u inner join cargo c on c.id=u.cargo inner join usuario uu on uu.re=u.supervisor inner join cn cn on cn.id=u.cn where u.re='{$re}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();

    $tel = $row['telefone'];
    $telefone = "(" . $tel[0] . $tel[1] . ") " . $tel[2] . "-" . $tel[3] . $tel[4] . $tel[5] . $tel[6] . "-" . $tel[7] . $tel[8] . $tel[9] . $tel[10];

    $arr = array(
        "re" => $row['re'],
        "nome" => $row['nome'],
        "cargo" => $row['cargo'],
        "email" => $row['email'],
        "telefone" => $telefone,
        "endereco" => $row['endereco'],
        "supervisor" => $row['supervisor']
    );
    return $arr;

    $result->close();
    $mysqli->close();
}
function sessao()
{

    if (!isset($_SESSION["nome"]) || !isset($_SESSION["email"]) || !isset($_SESSION["re"])) {


        return "nao";
    }
}
