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
$dataInicio = $txtTitulo['dataInicio'];
$dataFim = $txtTitulo['dataFim'];
$cn = $txtTitulo['cn'];

$where = "";

    if ($cn != "0") {
        $where .= " cn.id='{$cn}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($dataInicio != "") {
        $where .= " '{$dataInicio}' <= scp.data1";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($dataFim != "") {
        $where .= " '{$dataFim}' >= scp.data1";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }
$sql="select scp.id as id, cn.nome as cn, site.sigla as site, stipo.nome as site_tipo, ativ.nome as atividade, ativ.id as ativ_id, scp.os as os, scp.data as data, scp.hora as hora, scp.data1 as data1, scp.hora1 as hora1,scp.data2 as data2, scp.hora2 as hora2, scp.obs as justificativa, scp.avaliacao as avaliacao, u.nome as nome, u.re as re, u.telefone as telefone, st.nome as status from scp_registro scp inner join site on site.id=scp.site inner join scp_atividade ativ on ativ.id=scp.atividade inner join usuario u on u.re=scp.re inner join site_tipo stipo on stipo.id=site.tipo inner join scp_status st on st.id=scp.status inner join cn on cn.id=site.cn where" . $where;

exporta_xls($mysqli, $sql);

function exporta_xls($mysqli, $sql)
{
  // echo $sql;
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Icomon")
        ->setLastModifiedBy("Felipe Teixeira")
        ->setTitle("Export: " . date("Y-m-d H:i"))
        ->setSubject("Relatorio")
        ->setDescription("Solicitações de correção de ponto")
        ->setKeywords("PHPExcel")
        ->setCategory("result file");


    $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('#4682B4');

    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));

    $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($style);

    $result = $mysqli->query($sql);

    $objPHPExcel->getActiveSheet()->getStyle('A:N')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'COLABORADOR')
        ->setCellValue('C1', 'DATA REGISTRO')
        ->setCellValue('D1', 'HORA REGISTRO')
        ->setCellValue('E1', 'DATA ENTRADA')
        ->setCellValue('F1', 'HORA ENTRADA')
        ->setCellValue('G1', 'DATA SAÍDA')
        ->setCellValue('H1', 'HORA SAÍDA')
        ->setCellValue('I1', 'CN/SITE')
        ->setCellValue('J1', 'ATIVIDADE')
        ->setCellValue('K1', 'TA/TP/OS')
        ->setCellValue('L1', 'JUSTIFICATIVA')
        ->setCellValue('M1', 'STATUS')
        ->setCellValue('N1', 'AVALIAÇÃO');

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

    $linha = 2;
    while ($row = mysqli_fetch_array($result)) {

        $objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);
        $objPHPExcel->getActiveSheet()->getStyle('G' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);
        
      //  $operacao = '=CONCATENATE(I'.$linha.'," ",J'.$linha.')-CONCATENATE(G'.$linha.'," ",H'.$linha.')';
        
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['id'])
            ->setCellValue('B' . $linha, $row['nome'])
            ->setCellValue('C' . $linha, $row['data'])
            ->setCellValue('D' . $linha, $row['hora'])
            ->setCellValue('E' . $linha, $row['data1'])
            ->setCellValue('F' . $linha, $row['hora1'])
            ->setCellValue('G' . $linha, $row['data2'])
            ->setCellValue('H' . $linha, $row['hora2'])
            ->setCellValue('I' . $linha, $row['cn']."/".$row['site'])
            ->setCellValue('J' . $linha, $row['atividade'])
            ->setCellValue('K' . $linha, $row['os'])
            ->setCellValue('L' . $linha, $row['justificativa'])
            ->setCellValue('M' . $linha, $row['status'])
            ->setCellValue('N' . $linha, $row['avaliacao']);

        $linha++;
    }
    $objPHPExcel->getActiveSheet()->setTitle('SCP');
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type:   application/vnd.ms-excel');
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header('Content-Disposition: attachment;filename="SCP -' . date("Y-m-d H-i-s") . '.xls"');
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
