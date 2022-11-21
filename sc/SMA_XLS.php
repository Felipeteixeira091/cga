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

$txt = $txtTitulo['txt'];
$data1 = $txtTitulo['dataInicio'];
$data2 = $txtTitulo['dataFim'];
$pa = $txtTitulo['pa'];
$status = $txtTitulo['status'];

$txt = strtoupper($txt);
$where = "";

if ($data1 != "") {
    $where .= " ss.data >='{$data1}'";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($data2 != "") {
    $where .= " ss.data <='{$data2}'";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($status != "0") {
    $where .= " ss.status='{$status}'";
}
if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($txt != "") {
    $where .= " (ss.re like '%" . $txt . "%' or s.sigla like '%" . $txt . "%' or ss.re like '%" . $txt . "%' or sp.descricao like '%" . $txt . "%' or sp.numero like '%" . $txt . "%' or sp.descricao like '%" . $txt . "%' or st.nome like '%" . $txt . "%' or solicitante.nome like '%" . $txt . "%')";
}
if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if (substr($where, -3) === "and") {
    $where =  substr($where, 0, (strlen($where) - 3));
}

$sql = "select ss.id as ID_SOLICITACAO, sss.nome as STATUS, ssi.id as ID_ITEM, DATE_FORMAT(ss.solicitacao, '%Y-%m-%d %H:%i:%s') as DATA, sp.numero as PA, sp.descricao as DESCRICAO, ssi.quantidade as QUANTIDADE, if(ss.sobressalente=0,'NÃO','SIM') as SOBRESSALENTE, sft.nome as FATURA, if(ss.tipo=1,'RETIRADA','DEVOLUÇÃO') as TIPO, ss.re_retirada as RE_RETIRADA, retirada.nome as NOME_RETIRADA, ss.re as RE_SOLICITANTE, solicitante.nome as NOME_SOLICITANTE, cn.nome as CN, s.sigla as SITE, ss.os as OS, ifnull(st.nome,'ND') as sga_tipo from sma_solicitacao_itens ssi inner join sma_solicitacao ss on ss.id=ssi.solicitacao inner join sma_pa sp on sp.id=ssi.pa inner join sma_fatura_tipo sft on sft.id=ss.tipo_fatura inner join site s on s.id=ss.site inner join usuario solicitante on solicitante.re=ss.re left join usuario retirada on retirada.re=ss.re_retirada left join cn on cn.id=retirada.cn left join sga_tipo st on st.id=sp.sga_tipo inner join sma_solicitacao_status sss on sss.id=ss.status WHERE " . $where . " order by ss.data, ss.hora";

exporta_xls($mysqli, $sql);

function exporta_xls($mysqli, $sql)
{
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Icomon")
        ->setLastModifiedBy("Felipe Teixeira")
        ->setTitle("Export: " . date("Y-m-d H:i"))
        ->setSubject("Relatorio")
        ->setDescription("Solicitações SMA")
        ->setKeywords("PHPExcel")
        ->setCategory("result file");

    $objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('#4682B4');
    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));
    $objPHPExcel->getActiveSheet()->getStyle('A1:P1')->applyFromArray($style);

    $result = $mysqli->query($sql);

    $objPHPExcel->getActiveSheet()->getStyle('A:P')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ID_SOLICITACAO')
        ->setCellValue('B1', 'ID_ITEM')
        ->setCellValue('C1', 'DATA/HORA')
        ->setCellValue('D1', 'PA')
        ->setCellValue('E1', 'DESCRICAO')
        ->setCellValue('F1', 'QUANTIDADE')
        ->setCellValue('G1', 'SOBRESSALENTE')
        ->setCellValue('H1', 'FATURA')
        ->setCellValue('I1', 'SGA')
        ->setCellValue('J1', 'TIPO')
        ->setCellValue('K1', 'RETIRADA')
        ->setCellValue('L1', 'SOLICITANTE')
        ->setCellValue('M1', 'CN')
        ->setCellValue('N1', 'SITE')
        ->setCellValue('O1', 'OS')
        ->setCellValue('P1', 'STATUS');

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
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
    $linha = 2;
    while ($row = mysqli_fetch_array($result)) {

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['ID_SOLICITACAO'])
            ->setCellValue('B' . $linha, $row['ID_ITEM'])
            ->setCellValue('C' . $linha, $row['DATA'])
            ->setCellValue('D' . $linha, $row['PA'])
            ->setCellValue('E' . $linha, $row['DESCRICAO'])
            ->setCellValue('F' . $linha, $row['QUANTIDADE'])
            ->setCellValue('G' . $linha, $row['SOBRESSALENTE'])
            ->setCellValue('H' . $linha, $row['FATURA'])
            ->setCellValue('I' . $linha, $row['sga_tipo'])
            ->setCellValue('J' . $linha, $row['TIPO'])
            ->setCellValue('K' . $linha, $row['NOME_RETIRADA'] . "[" . $row['RE_RETIRADA'] . "]")
            ->setCellValue('L' . $linha, $row['RE_SOLICITANTE'])
            ->setCellValue('M' . $linha, $row['CN'])
            ->setCellValue('N' . $linha, $row['SITE'])
            ->setCellValue('O' . $linha, $row['OS'])
            ->setCellValue('P' . $linha, $row['STATUS']);

        $linha++;
    }

    $objPHPExcel->getActiveSheet(0)->setTitle('SOLICITAÇÕES');
    $objPHPExcel->setActiveSheetIndex(0);

    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-Type:   application/vnd.ms-excel');
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header('Content-Disposition: attachment;filename="SMA -' . date("Y-m-d H-i-s") . '.xls"');
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
