<?php

include 'conf/conexao2.php';
include 'json_encode.php';
/** Include PHPExcel */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once '../lib/PHPExcel/PHPExcel.php';

if (!isset($_SESSION)) {
    session_start();
}
// Verifica se existe os dados da sessão de login 
if (!isset($_SESSION["nome"]) || !isset($_SESSION["email"])) {
    header("Location: ../");
    exit;
}
$txtTitulo = filter_input_array(INPUT_GET, FILTER_DEFAULT);

$acao = $txtTitulo['acao'];
$re = $_SESSION['re'];
$uf = $_SESSION['uf'];

$txt = "";//$txtTitulo['txt'];
$data1 = "2021-09-01";//$txtTitulo['dataInicio'];
$data2 ="2021-10-30";$txtTitulo['dataFim'];
$cn = "";//$txtTitulo['cn'];

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
    $where .= " u.nome='{$txt}' or u.re like '%" . $txt . "%' or s.sigla like '%" . $txt . "%' or c.observacao like '%" . $txt . "%'";
}
if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($cn > 0) {
    $where .= " cn.nome='{$cn}'";
}
if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if (substr($where, -3) === "and") {
    $where =  substr($where, 0, (strlen($where) - 3));
}
$sql = "select c.id as id, concat('R$ ',c.valor) as valor, c.data_utilizacao as dataU, c.hora_utilizacao horaU, u.re as re, u.nome as nome, cn.nome as cn, et.nome as tipo, em.nome as motivo, c.os as os, s.sigla as site, c.observacao as obs, c.data as data, c.hora as hora from ccc c inner join usuario u on u.re=c.re inner join site s on s.id=c.site inner join cn on cn.id=s.cn inner join ext_nota_tipo as et on et.id=c.tipo inner join ext_nota_motivo em on em.id=c.motivo where" . $where . "";

exporta_xls($mysqli, $sql);

function exporta_xls($mysqli, $sql)
{
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Icomon")
        ->setLastModifiedBy("Felipe Teixeira")
        ->setTitle("Export: " . date("Y-m-d H:i"))
        ->setSubject("Relatorio")
        ->setDescription("Solicitações CCC")
        ->setKeywords("PHPExcel")
        ->setCategory("result file");

    $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('#4682B4');
    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));
    $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($style);

    $result = $mysqli->query($sql);

    $objPHPExcel->getActiveSheet()->getStyle('A:K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'DATA UTILIZAÇÃO')
        ->setCellValue('C1', 'CN')
        ->setCellValue('D1', 'SITE')
        ->setCellValue('E1', 'TIPO')
        ->setCellValue('F1', 'MOTIVO')
        ->setCellValue('G1', 'OS')
        ->setCellValue('H1', 'OBSERVAÇÃO')
        ->setCellValue('I1', 'NOME')
        ->setCellValue('J1', 'RE')
        ->setCellValue('K1', 'VALOR');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

    $linha = 2;
    while ($row = mysqli_fetch_array($result)) {

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['id'])
            ->setCellValue('B' . $linha, $row['dataU'])
            ->setCellValue('C' . $linha, $row['cn'])
            ->setCellValue('D' . $linha, $row['site'])
            ->setCellValue('E' . $linha, $row['tipo'])
            ->setCellValue('F' . $linha, $row['motivo'])
            ->setCellValue('G' . $linha, $row['os'])
            ->setCellValue('H' . $linha, $row['obs'])
            ->setCellValue('I' . $linha, $row['nome'])
            ->setCellValue('J' . $linha, $row['re'])
            ->setCellValue('K' . $linha, $row['valor']);

        $linha++;
    }

    $objPHPExcel->getActiveSheet(0)->setTitle('CARTÃO COORPORATIVO');
    $objPHPExcel->setActiveSheetIndex(0);

    //ITENS
    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-Type:   application/vnd.ms-excel');
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header('Content-Disposition: attachment;filename="CCC -' . date("Y-m-d H-i-s") . '.xls"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;

    $result->close();
    $mysqli->close();
}
