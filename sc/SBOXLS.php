<?php
session_start();

include 'conf/conexao2.php';
include 'json_encode.php';
/** Include PHPExcel */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require '../lib/PHPExcel/PHPExcel.php';

if (!isset($_SESSION)) { }
// Verifica se existe os dados da sessão de login 
if (!isset($_SESSION["nome"]) || !isset($_SESSION["email"])) {
    header("Location: ../");
    exit;
}
$txtTitulo = filter_input_array(INPUT_GET, FILTER_DEFAULT);

$cn = $txtTitulo['cn'];
$site = $txtTitulo['site'];
$status = $txtTitulo['status'];
$dataInicio = $txtTitulo['dataInicio'];
$dataFim = $txtTitulo['dataFim'];

$where = "";

if ($cn > 0) {
    $where .= " site.cn='{$cn}'";
}
if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}
if ($site > 0) {
    $where .= " bo.site='{$site}'";
}
if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}
if ($status > 0) {
    $where .= " bo.status='{$status}'";
}
if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}
if ($dataInicio != "") {
    $where .= " DATE(bo.dh) >= '{$dataInicio}'";
}
if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}
if ($dataFim != "") {
    $where .= " DATE(bo.dh) <= '{$dataFim}'";
}
if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}
if (substr($where, -3) === "and") {
    $where =  substr($where, 0, (strlen($where) - 3));
}

$sql = "select bo.id as id, bo.re as re_solicitante, usr.nome as nome_solicitante, (select dh from sbo_bo_historico where bo=bo.id and status=bo.status limit 1) as dh, site.sigla as site, cn.nome as cn, bs.nome as status, bo.ta as ta, bo.os as os, bo.numero_bo as numero_bo, bo.numero_sinistro as numero_sinistro, bo.dh_indisp_inicio as dhInicio, bo.dh_indisp_fim as dhFinal, bo.tempo_indisp as tempo_indisp, bo.indisp_elemento as elemento, bo.indisp_municipio as municipio, ifnull(if(bo.f_bluetooth=1,'SIM','NÃO'),'ND') as fechadura_b, UPPER(ifnull(fs.nome,'ND')) as fechadura_status, ifnull(if(bo.modulo_box=1,'SIM','NÃO'),'ND') as bateria_resinada, ifnull(if(bo.bateria=1,'SIM','NÃO'),'ND') as bateria_ion, bo.relato as relato, cid.nome as cidade, bai.nome as bairro, site.endereco as endereco, site.cep as cep from sbo_bo bo left join usuario usr on usr.re=bo.re left join site site on site.id=bo.site left join cn cn on cn.id=site.cn left join sbo_bo_status bs on bs.id=bo.status left join sbo_fechadura_status fs on fs.id=bo.f_bluetooth_status left join cidade cid on cid.id=site.cidade left join bairro bai on bai.id=site.bairro WHERE" . $where . " order by dh";
$sql2 = "select site.cn as CN, bo.status as STATUS_BO, bh.dh as DATA_BO, bh.bo as BO, bh.re as RE, bh.dh as dh, bs.nome as STATUS, u.nome as NOME_STATUS from sbo_bo_historico bh inner join sbo_bo_status bs on bs.id=bh.status inner join sbo_bo bo on bo.id=bh.bo inner join site site on site.id=bo.site inner join usuario u on u.re=bh.re WHERE" . $where . " order by bh.id, bh.dh";


exporta_xls($mysqli, $sql, $sql2);

function exporta_xls($mysqli, $sql, $sql2)
{
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Icomon")
        ->setLastModifiedBy("Felipe Teixeira")
        ->setTitle("Export: " . date("Y-m-d H:i"))
        ->setSubject("Relatorio")
        ->setDescription("Acoplamento de GMGs")
        ->setKeywords("PHPExcel")
        ->setCategory("result file");

    $objPHPExcel->getActiveSheet()->getStyle('A1:Y1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('#4682B4');

    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));

    $objPHPExcel->getActiveSheet()->getStyle('A1:Y1')->applyFromArray($style);

    $result = $mysqli->query($sql);

    $objPHPExcel->getActiveSheet()->getStyle('A:Y')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:Y1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'SINISTRO')
        ->setCellValue('C1', 'RE_SOLICITANTE')
        ->setCellValue('D1', 'NOME_SOLICITANTE')
        ->setCellValue('E1', 'DATA/HORA')
        ->setCellValue('F1', 'SITE')
        ->setCellValue('G1', 'CN')
        ->setCellValue('H1', 'STATUS_ATUAL')
        ->setCellValue('I1', 'TA')
        ->setCellValue('J1', 'OS')
        ->setCellValue('K1', 'NÚMERO BO')
        ->setCellValue('L1', 'INÍCIO INDISP.')
        ->setCellValue('M1', 'FIM INDISP.')
        ->setCellValue('N1', 'TEMPO TOTAL')
        ->setCellValue('O1', 'MUN. AFETADOS')
        ->setCellValue('P1', 'ELEM. AFETADOS')
        ->setCellValue('Q1', 'FECHADURA BLUETOOTH')
        ->setCellValue('R1', 'STATUS FECHADURA BLUETOOTH')
        ->setCellValue('S1', 'BATERIA RESINADA')
        ->setCellValue('T1', 'BATERIA ION-LITÍO')
        ->setCellValue('U1', 'RELATO')
        ->setCellValue('V1', 'CIDADE')
        ->setCellValue('W1', 'BAIRRO')
        ->setCellValue('X1', 'ENDEREÇO')
        ->setCellValue('Y1', 'CEP');

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
    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);

    $linha = 2;
    while ($row = mysqli_fetch_array($result)) {
        $objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);

        //$tempo_indisp =calcula_indisponibilidade($row['dInicio'], $row['hInicio'], $row['dFinal'], $row['hFinal']);

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['id'])
            ->setCellValue('B' . $linha, $row['numero_sinistro'])
            ->setCellValue('C' . $linha, $row['re_solicitante'])
            ->setCellValue('D' . $linha, $row['nome_solicitante'])
            ->setCellValue('E' . $linha, $row['dh'])
            ->setCellValue('F' . $linha, $row['site'])
            ->setCellValue('G' . $linha, $row['cn'])
            ->setCellValue('H' . $linha, $row['status'])
            ->setCellValue('I' . $linha, $row['ta'])
            ->setCellValue('J' . $linha, $row['os'])
            ->setCellValue('K' . $linha, $row['numero_bo'])
            ->setCellValue('L' . $linha, $row['dhInicio'])
            ->setCellValue('M' . $linha, $row['dhFinal'])
            ->setCellValue('N' . $linha, $row['tempo_indisp'])
            ->setCellValue('O' . $linha, $row['municipio'])
            ->setCellValue('P' . $linha, $row['elemento'])
            ->setCellValue('Q' . $linha, $row['fechadura_b'])
            ->setCellValue('R' . $linha, $row['fechadura_status'])
            ->setCellValue('S' . $linha, $row['bateria_resinada'])
            ->setCellValue('T' . $linha, $row['bateria_ion'])
            ->setCellValue('U' . $linha, $row['relato'])
            ->setCellValue('V' . $linha, $row['cidade'])
            ->setCellValue('W' . $linha, $row['bairro'])
            ->setCellValue('X' . $linha, $row['endereco'])
            ->setCellValue('Y' . $linha, $row['cep']);

        $linha++;
    }
    $result->close();
    $objPHPExcel->getActiveSheet()->setTitle('SBO');
    $objPHPExcel->setActiveSheetIndex(0);

    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Histórico');
    $objPHPExcel->addSheet($myWorkSheet, 1);
    $objPHPExcel->setActiveSheetIndex(1);

    $result = $mysqli->query($sql2);

    $objPHPExcel->getActiveSheet(1)->getStyle('A1:E1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('#4682B4');

    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));

    $objPHPExcel->getActiveSheet(1)->getStyle('A1:E1')->applyFromArray($style);

    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A1', 'BO')
        ->setCellValue('B1', 'DATA/HORA')
        ->setCellValue('C1', 'STATUS')
        ->setCellValue('D1', 'RESPONSÁVEL');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

    $linha = 2;
    
    
    while ($row = mysqli_fetch_array($result)) {
        $objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);


        $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A' . $linha, $row['BO'])
            ->setCellValue('B' . $linha, $row['dh'])
            ->setCellValue('C' . $linha, $row['STATUS'])
            ->setCellValue('D' . $linha, $row['NOME_STATUS']);

        $linha++;
    }

    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-Type:   application/vnd.ms-excel');
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header('Content-Disposition: attachment;filename="SBO-' . date("Y-m-d H-i-s") . '.xls"');
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
function calcula_indisponibilidade($data_1, $hora_1, $data_2, $hora_2)
{
    $diferenca = abs(strtotime($data_1 . ' ' . $hora_1) - strtotime($data_2 . ' ' . $hora_2));

    $horas = explode(".", $diferenca / 3600)[0];
    if ($horas < 10) {
        $horas = "0" . $horas;
    }
    $minutos = $diferenca / 60 % 60;
    if ($minutos < 10) {
        $minutos = "0" . $minutos;
    }
    return $horas . ":" . $minutos;
}