<?php
include_once "l_sessao.php";
$txtTitulo = filter_input_array(INPUT_GET, FILTER_DEFAULT);
include_once "./conf/conexao2.php";
include_once "./json_encode.php";
require '../lib/PHPExcel/PHPExcel.php';

$re_cadastro = $_SESSION["re"];
$uf_sessao = $_SESSION["uf"];
$cn_sessao = $_SESSION["cn"];
$data = date('Y-m-d');

$acao = $txtTitulo['acao'];
$erro = "0";
$msg = "";

if ($acao === "cnLista") {
    cnLista($mysqli, $uf_sessao, $cn_sessao, $re_cadastro);
} else
if ($acao === "cidadeLista") {
    $cn = $txtTitulo['cn'];
    cidadeLista($mysqli);
} else
if ($acao === "ufLista") {
    ufLista($mysqli);
} else
if ($acao === "bairroLista") {
    bairroLista($mysqli);
} else
if ($acao === "tipoLista") {
    tipoLista($mysqli);
} else 
if ($acao === "cadastroSITE") {
    $sigla = $cidade_n = addslashes(strtoupper($txtTitulo['sigla']));
    $descricao = $cidade_n = addslashes(strtoupper($txtTitulo['descricao']));
    $cn = $txtTitulo['cn'];
    $tipo = $txtTitulo['tipo'];
    $uf = $txtTitulo['uf'];
    $cidade = $txtTitulo['cidade'];
    $cidade_n = addslashes(strtoupper($txtTitulo['cidade_n']));
    $bairro = $txtTitulo['bairro'];
    $bairro_n = addslashes(strtoupper($txtTitulo['bairro_n']));
    $endereco = strtoupper(addslashes($txtTitulo['endereco']));
    $cep = $txtTitulo['cep'];
    cadastroSITE($mysqli, $sigla, $descricao, $cn, $tipo, $uf, $cidade, $cidade_n, $bairro, $bairro_n, $endereco, $cep, $re_cadastro, $data);
} else 
if ($acao === "editaSITE") {
    $id = $txtTitulo['id'];
    $sigla = strtoupper($txtTitulo['sigla']);
    $descricao = addslashes(strtoupper($txtTitulo['descricao']));
    $cn = $txtTitulo['cn'];
    $tipo = $txtTitulo['tipo'];
    $uf = $txtTitulo['uf'];
    $cidade = $txtTitulo['cidade'];
    $cidade_n = addslashes(strtoupper($txtTitulo['cidade_n']));
    $bairro = $txtTitulo['bairro'];
    $bairro_n = addslashes(strtoupper($txtTitulo['bairro_n']));
    $endereco = strtoupper(addslashes($txtTitulo['endereco']));
    $cep = $txtTitulo['cep'];
    editaSITE($mysqli, $id, $sigla, $descricao, $cn, $tipo, $uf, $cidade, $cidade_n, $bairro, $bairro_n, $endereco, $cep, $re_cadastro, $data);
} else 

if ($acao === "SITEProcura") {
    $txt = $txtTitulo['txt'];
    $cn = $txtTitulo['cn'];

    if ($cn === "0" && $txt === "") {
        $erro = 1;
        $msg = "Informações incompletas!";
        $arr = array("erro" => $erro, "msg" => $msg);
        echo JsonEncodePAcentos::converter($arr);
    } else {
        siteProcura($mysqli, $txt, $cn, $uf_sessao, $re_cadastro);
    }
} else
if ($acao === "dados") {
    $site = $txtTitulo['site'];
    dados($mysqli, $site);
} else
if ($acao === "exportar") {

    $txt = $txtTitulo['txt'];
    $cn = $txtTitulo['cn'];

    $txt = strtoupper($txt);
    $where = "";

    if ($cn > 0) {
        $where .= " s.cn='{$cn}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($txt != "") {
        $where .= " (s.nome like '%" . $txt . "%' or s.sigla like '%" . $txt . "%' or uf.nome like '%" . $txt . "%')";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    $sql = "select s.id as id, s.descricao as nome, s.sigla as sigla, uf.nome as uf, st.nome as tipo, cn.nome as cn, c.nome as cidade, b.nome as bairro, s.cep as cep from site s left join cidade c on c.id=s.cidade inner join cn on cn.id=s.cn inner join uf on uf.id=s.estado inner join site_tipo st on st.id=s.tipo inner join bairro b on b.id=s.bairro where $where";

    exporta_xls($mysqli, $sql);
}

function exporta_xls($mysqli, $sql)
{
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Icomon")
        ->setLastModifiedBy("Felipe Teixeira")
        ->setTitle("Export: " . date("Y-m-d H:i"))
        ->setSubject("Relatorio")
        ->setDescription("Acoplamento de GMGs")
        ->setKeywords("PHPExcel")
        ->setCategory("result file");

    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('#4682B4');

    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));

    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($style);

    $result = $mysqli->query($sql);

    $objPHPExcel->getActiveSheet()->getStyle('A:I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'NOME')
        ->setCellValue('C1', 'SIGLA')
        ->setCellValue('D1', 'UF')
        ->setCellValue('E1', 'TIPO')
        ->setCellValue('F1', 'CN')
        ->setCellValue('G1', 'CIDADE')
        ->setCellValue('H1', 'BAIRRO')
        ->setCellValue('I1', 'CEP');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

    $linha = 2;
    while ($row = mysqli_fetch_array($result)) {

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['id'])
            ->setCellValue('B' . $linha, $row['nome'])
            ->setCellValue('C' . $linha, $row['sigla'])
            ->setCellValue('D' . $linha, $row['uf'])
            ->setCellValue('E' . $linha, $row['tipo'])
            ->setCellValue('F' . $linha, $row['cn'])
            ->setCellValue('G' . $linha, $row['cidade'])
            ->setCellValue('H' . $linha, $row['bairro'])
            ->setCellValue('I' . $linha, $row['cep']);
        $linha++;
    }
    $objPHPExcel->getActiveSheet()->setTitle('SBO');
    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-Type:   application/vnd.ms-excel');
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header('Content-Disposition: attachment;filename="SITES-' . date("Y-m-d H-i-s") . '.xls"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    $result->close();
}
function tipoLista($mysqli)

{
    $sql = "select id, nome from site_tipo";
    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}
function cnLista($mysqli, $uf_sessao, $cn_sessao, $re_sessao)

{
    $regiao = regiao($mysqli, $re_sessao)['regiao'];
    $sql = "select id, nome from cn where campo=1";
    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}

function ufLista($mysqli)

{
    $sql = "select id, sigla as nome from uf";
    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}

function cidadeLista($mysqli)
{
    $sql = "select id, nome from cidade order by nome";
    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}

function bairroLista($mysqli)

{
    $sql = "select id, nome from bairro order by nome";

    $myArray = array();
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
        echo JsonEncodePAcentos::converter($myArray);
    }
}

function dados($mysqli, $site)
{
    $sql = "select s.id as id, s.sigla as sigla, s.descricao as descricao, s.tipo as tipo, s.cn as cn, s.estado as uf, s.endereco as endereco, s.bairro as bairro, s.cidade as cidade, s.cep as cep from site s WHERE s.id='{$site}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();
    $result->close();

    echo JsonEncodePAcentos::converter($row);
    $mysqli->close();
}

function cadastroSITE($mysqli, $sigla, $descricao, $cn, $tipo, $uf, $cidade, $cidade_n, $bairro, $bairro_n, $endereco, $cep, $re_cadastro, $data)

{
    $erro = "1";

    $p = permissaoVerifica($mysqli, "68", $re_cadastro);
    $cidade_n = strtoupper(preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($cidade_n))));
    $bairro_n = strtoupper(preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($bairro_n))));


    $sigla = strtoupper(preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($sigla))));
    $descricao = strtoupper(preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($descricao))));

    $vCidade = $mysqli->query("select nome from cidade where nome='{$cidade_n}' and cn='{$cn}' and uf='{$uf}'")->num_rows;
    $vBairro = $mysqli->query("select nome from bairro where nome='{$bairro_n}'")->num_rows;


    if ($p === 0) {
        $msg = "Você não tem permissão para cadastrar site.";
    } else
    if ($sigla === "" || !$sigla) {
        $msg = "Necessário informar a sigla do site.";
    } else
    if (strlen($sigla) < 3) {
        $msg = "A sigla do site é inválida.";
    } else
    if (!$descricao || $descricao === "") {
        $msg = "Necessário informar o nome do site.";
    } else
    if ($tipo === "0") {
        $msg = "Necessário selecionar o tipo do site.";
    } else
    if ($uf === "0") {
        $msg = "Necessário selecionar o estado.";
    } else
    if ($cn === "0") {
        $msg = "Necessário selecionar o CN do site.";
    } else
    if ($cidade === "0") {
        $msg = "Necessário selecionar a cidade.";
    } else
    if ($cidade === "N" and (strlen($cidade_n) < 3 or $cidade_n === "")) {
        $msg = "Necessário digitar o nome da nova cidade.";
    } else
    if ($cidade === "N" and $vCidade > 0) {
        $msg = "A cidade informada como nova, já está cadastrada.";
    } else
    if ($bairro === "0") {
        $msg = "Necessário selecionar o bairro do site.";
    } else
    if ($bairro === "N" and (strlen($bairro_n) < 3 or $bairro_n === "")) {
        $msg = "Necessário digitar o nome do novo bairro.";
    } else
    if ($cidade === "N" and $vBairro > 0) {
        $msg = "O bairro informada como novo, já está cadastrado.";
    } else
    if (strlen($cep) < 8) {
        $msg = "O CEP informado é inválido.";
    } else
    if (strlen($endereco) < 5) {
        $msg = "O Endereço informado é inválido.";
    } else
    if (siglaVerifica($mysqli, $sigla, $tipo, $uf) > 0) {
        $erro = "2";
        $msg = "O site informado já está cadastrado.";
    } else {



        if ($cidade === "N") {
            $mysqli->query("insert into cidade (nome, cn, uf) values ('{$cidade_n}', '{$cn}', '{$uf}')");
            $cidade = $mysqli->insert_id;
        }

        if ($bairro === "N") {
            $mysqli->query("insert into bairro (nome) values ('{$bairro_n}')");
            $bairro = $mysqli->insert_id;
        }

        $sql = "insert into site (sigla, descricao, tipo, cn, estado, endereco, bairro, cidade, cep, cadastro, data) values ('{$sigla}', '{$descricao}','{$tipo}','{$cn}', '{$uf}', '{$endereco}', '{$bairro}','{$cidade}', '{$cep}','{$re_cadastro}','{$data}')";
        $mysqli->query($sql);

        $erro = "0";
        $msg = "Site cadastrado com sucesso.";
    }



    $arr = array("erro" => $erro, "msg" => $msg);
    echo JsonEncodePAcentos::converter($arr);
}

function editaSITE($mysqli, $id, $sigla, $descricao, $cn, $tipo, $uf, $cidade, $cidade_n, $bairro, $bairro_n, $endereco, $cep, $re_cadastro, $data)

{

    $erro = "1";
    $cidade_n = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($cidade_n)));
    $bairro_n = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($bairro_n)));
    $descricao = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($descricao)));

    $vCidade = $mysqli->query("select nome from cidade where nome='{$cidade_n}' and cn='{$cn}' and uf='{$uf}'")->num_rows;
    $vBairro = $mysqli->query("select nome from bairro where nome='{$bairro_n}'")->num_rows;

    $p = permissaoVerifica($mysqli, "69", $re_cadastro);

    if ($p === 0) {

        $msg = "Você não tem permissão para cadastrar site.";
    } else

    if ($sigla === "" || !$sigla) {

        $msg = "Necessário informar a sigla do site.";
    } else 

    if (strlen($sigla) < 3) {

        $msg = "A sigla do site é inválida.";
    } else

    if (!$descricao || $descricao === "") {

        $msg = "Necessário informar o nome do site.";
    } else

    if ($tipo === "0") {

        $msg = "Necessário selecionar o tipo do site.";
    } else

    if ($uf === "0") {

        $msg = "Necessário selecionar o estado.";
    } else

    if ($cn === "0") {

        $msg = "Necessário selecionar o CN do site.";
    } else

    if ($cidade === "0") {

        $msg = "Necessário selecionar a cidade.";
    } else

    if ($cidade === "N" and (strlen($cidade_n) < 3 or $cidade_n === "")) {

        $msg = "Necessário digitar o nome da nova cidade.";
    } else

    if ($cidade === "N" and $vCidade > 0) {

        $msg = "A cidade informada como nova, já está cadastrada.";
    } else

    if ($bairro === "0") {

        $msg = "Necessário selecionar o bairro do site.";
    } else

    if ($bairro === "N" and (strlen($bairro_n) < 3 or $bairro_n === "")) {

        $msg = "Necessário digitar o nome do novo bairro.";
    } else

    if ($cidade === "N" and $vBairro > 0) {

        $msg = "O bairro informada como novo, já está cadastrado.";
    } else

    if (strlen($cep) < 8) {

        $msg = "O CEP informado é inválido.";
    } else

    if (strlen($endereco) < 5) {

        $msg = "O Endereço informado é inválido.";
    } else {



        if ($cidade === "N") {



            $mysqli->query("insert into cidade (nome, cn, uf) values ('{$cidade_n}', '{$cn}', '{$uf}')");

            $cidade = $mysqli->insert_id;
        }

        if ($bairro === "N") {



            $mysqli->query("insert into bairro (nome) values ('{$bairro_n}')");

            $bairro = $mysqli->insert_id;
        }



        $sql = "update site set descricao='{$descricao}', tipo='{$tipo}', cn='{$cn}', estado='{$uf}', endereco='{$endereco}', bairro='{$bairro}', cidade='{$cidade}', cep='{$cep}' where id='{$id}'";

        $sql = anti_injection($sql);



        $mysqli->query($sql);



        $erro = "0";

        $msg = "Site editado com sucesso.";
    }



    $arr = array("erro" => $erro, "msg" => $msg);

    echo JsonEncodePAcentos::converter($arr);
}

function siglaVerifica($mysqli, $sigla, $tipo, $uf)

{



    $num = $mysqli->query("select sigla from site where sigla='{$sigla}' and tipo='{$tipo}' and estado='{$uf}'")->num_rows;

    return $num;
}

function siteProcura($mysqli, $txt, $cn, $uf_sessao, $re)

{

    $regiao = regiao($mysqli, $re)['regiao'];

    $p = permissaoVerifica($mysqli, "77", $re);

    $txt = strtoupper($txt);

    $where = "";


    if ($cn != "0") {

        $where .= " cn.id='{$cn}'";
    }

    if ($where != "" and substr($where, -3) != "and") {

        $where .= " and";
    }

    if ($txt != "") {

        $where = " (site.sigla like '%" . $txt . "%' or site.descricao like '%" . $txt . "%')";
    }

    if ($where != "" and substr($where, -3) != "and") {

        $where .= " and";
    }

    if (substr($where, -3) === "and") {

        $where =  substr($where, 0, (strlen($where) - 3));
    }

    if ($p === 0) {

        $where = $where . " and cn.regiao='{$regiao}'";
    }

    $sql = "select site.id as id, site.sigla as sigla, site.descricao as descricao, cn.nome as cn, right(tipo.nome,1) as tipo from site site inner join site_tipo tipo on tipo.id=site.tipo inner join cn cn on cn.id=site.cn where " . $where . " order by cn asc";


    $myArray = array();

    if ($result = $mysqli->query($sql)) {

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

            $myArray[] = $row;
        }

        echo JsonEncodePAcentos::converter($myArray);
    }
}

function anti_injection($sql)

{

    $sql = trim($sql); // limpa espaços vazios

    $sql = strip_tags($sql); // tira tags html e php

    // $sql = addslashes($sql); //  adiciona barras invertidas a um string

    return $sql;
}

function permissaoVerifica($mysqli, $funcao, $re)

{

    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;

    return $num;
}
