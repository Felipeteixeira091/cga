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

$acao = @$txtTitulo['acao'];
$txt = @$txtTitulo['txt'];
$dataInicio = @$txtTitulo['dataInicio'];
$dataFim = @$txtTitulo['dataFim'];
$cn = @$txtTitulo['cn'];

//////////////////////////////////////////////////////////////////////////////////////////////q
$where = "";

if ($cn != "0" and $cn != "") {
    $where .= " s.cn='{$cn}'";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($txt != "") {
    $where .= " g.identificacao like '%" . $txt . "%' or s.sigla like '%" . $txt . "%'";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($dataInicio != "0") {
    $where .= " '{$dataInicio}' <= ac.data_inicio";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($dataFim != "") {
    $where .= " '{$dataFim}'  >= ac.data_inicio";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if (substr($where, -3) === "and") {
    $where =  substr($where, 0, (strlen($where) - 3));
}


$sql = "select g.codigo as cod_gmg, g.identificacao gmg, ac.re as colaborador, ac.ta as ta, s.sigla site, c.nome as cn, ac.data as data, ac.hora as hora, ac.data_inicio data_inicio, ac.hora_inicio hora_inicio, ac.data_final data_final, ac.hora_final hora_final from gmg_acoplamento ac inner join gmg g on g.codigo=ac.gmg_codigo inner join site s on s.id=ac.site inner join cn c on c.id=s.cn WHERE " . $where;


exporta_xls($mysqli, $sql);

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


    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('#4682B4');

    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));

    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($style);

    $result = $mysqli->query($sql);

    $objPHPExcel->getActiveSheet()->getStyle('A:M')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'GMG')
        ->setCellValue('B1', 'COLABORADOR')
        ->setCellValue('C1', 'SITE')
        ->setCellValue('D1', 'CN')
        ->setCellValue('E1', 'HORA REGISTRO')
        ->setCellValue('F1', 'HORA REGISTRO')
        ->setCellValue('G1', 'DATA INICIO')
        ->setCellValue('H1', 'HORA INICIO')
        ->setCellValue('I1', 'DATA FINAL')
        ->setCellValue('J1', 'HORA FINAL')
        ->setCellValue('K1', 'TEMPO OPERAÇÃO')
        ->setCellValue('L1', 'ID GMG')
        ->setCellValue('M1', 'TA');

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

    $linha = 2;
    while ($row = mysqli_fetch_array($result)) {
        $objPHPExcel->getActiveSheet()->getStyle('K' . $linha)->getNumberFormat()->setFormatCode("[HH]:MM");
        //   $objPHPExcel->getActiveSheet()->getStyle('K' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);


        $operacao = '=CONCATENATE(I' . $linha . '," ",J' . $linha . ')-CONCATENATE(G' . $linha . '," ",H' . $linha . ')';

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['gmg'])
            ->setCellValue('B' . $linha, $row['colaborador'])
            ->setCellValue('C' . $linha, $row['site'])
            ->setCellValue('D' . $linha, $row['cn'])
            ->setCellValue('E' . $linha, $row['data'])
            ->setCellValue('F' . $linha, $row['hora'])
            ->setCellValue('G' . $linha, $row['data_inicio'])
            ->setCellValue('H' . $linha, $row['hora_inicio'])
            ->setCellValue('I' . $linha, $row['data_final'])
            ->setCellValue('J' . $linha, $row['hora_final'])
            ->setCellValue('K' . $linha, $operacao)
            ->setCellValue('L' . $linha, $row['cod_gmg'])
            ->setCellValue('M' . $linha, $row['ta']);

        $linha++;
    }
    $objPHPExcel->getActiveSheet()->setTitle('XLS');
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type:   application/vnd.ms-excel');
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header('Content-Disposition: attachment;filename="ACOPLAMENTO GMG-' . date("Y-m-d H-i-s") . '.xls"');
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
