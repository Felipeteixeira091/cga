<?php
session_start();

$txtTitulo = filter_input_array(INPUT_POST, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";

$re_sessao = $_SESSION["re"];
$uf_sessao = $_SESSION["uf"];

$acao = $txtTitulo['acao'];
$data = date('Y-m-d');
$hora = date('H:i');


if ($acao === "meus_dados") {

    meus_dados($mysqli, $re_sessao);
}
function meus_dados($mysqli, $re)
{
    $sql = "select u.re as re, u.nome as nome, u.email as email, u.telefone as telefone, ifnull(u.cartao,'S/CARTÃO') as cartao, f.placa as placa, v.vei_marca as vMarca, v.vei_modelo vModelo, co.nome as coordenador, uf.sigla as uf, cn.nome as cn, ca.nome as cargo from usuario u inner join usuario co on co.re=u.supervisor inner join uf on uf.id=u.estado inner join cn on cn.id=u.cn inner join cargo ca on ca.id=u.cargo left join frota f on f.placa=u.frota left join veiculo v on v.vei_id=f.veiculo where u.re='{$re}'";

    $result = $mysqli->query($sql);
    //$row = $result->fetch_array();
    $row = $result->fetch_array(MYSQLI_ASSOC);

    $result->close();

    echo JsonEncodePAcentos::converter($row);

    $mysqli->close();
}
