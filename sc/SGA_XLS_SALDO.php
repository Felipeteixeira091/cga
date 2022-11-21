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

$txt = $txtTitulo['txt']; //"detley"; //;
$cn = $txtTitulo['cn']; //"2020-01-01"; //


$txt = strtoupper($txt);
$where = "";

if ($cn > 0) {
    $where .= " u.cn='{$cn}'";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($txt != "") {
    $where .= " (u.re like '%" . $txt . "%' or u.nome like '%" . $txt . "%')";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if (substr($where, -3) === "and") {
    $where =  substr($where, 0, (strlen($where) - 3));
}

if (strlen($where) > 0) {
    $where =  "and" . $where;
}

if ($cn != "G") {

    $sql = "select cn.regiao as regiao from usuario u inner join cn on cn.id=u.cn where u.re='{$re}'";
    $result = $mysqli->query($sql)->fetch_array();
    $regiao = $result['regiao'];

    $where = " and cn.regiao='{$regiao}'";
}

$sql = "select cn.nome as cn, u.nome as nome, u.re as re, st.id as tipo_id, st.nome as tipo, st.ico as ico, sum(pa.multiplicador*ssi.quantidade) as sma, ifnull((SELECT sum(sb.qtd) FROM sga_baixa sb inner join sga s on s.id=sb.sga where s.re=ss.re_retirada and sb.tipo=st.id),0) as pb_sga, ifnull((SELECT sum(sb.qtd_entregue) FROM sga_baixa sb inner join sga s on s.id=sb.sga where s.re=ss.re_retirada and sb.tipo=st.id),0) as sga from sma_solicitacao_itens ssi inner join sma_solicitacao ss on ss.id=ssi.solicitacao inner join sma_pa pa on pa.id=ssi.pa inner join sga_tipo st on st.id=pa.sga_tipo inner join usuario u on u.re=ss.re_retirada inner join cn on cn.id=u.cn WHERE ss.data>='2021-09-01' and ss.tipo=1 and ss.status=3 and pa.sga=1 " . $where . " group by u.nome, st.nome";

exporta_xls($mysqli, $sql);

function exporta_xls($mysqli, $sql)
{
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Icomon")
        ->setLastModifiedBy("Felipe Teixeira")
        ->setTitle("Export: " . date("Y-m-d H:i"))
        ->setSubject("Relatorio")
        ->setDescription("Solicitações SGA")
        ->setKeywords("PHPExcel")
        ->setCategory("result file");

    $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('#4682B4');
    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));
    $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($style);

    $result = $mysqli->query($sql);

    $objPHPExcel->getActiveSheet()->getStyle('A:G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'NOME')
        ->setCellValue('B1', 'CN')
        ->setCellValue('C1', 'TIPO')
        ->setCellValue('D1', 'SALDO RETIRADO')
        ->setCellValue('E1', 'MATERIAL NOVO EM CAMPO')
        ->setCellValue('F1', 'PRÉ BAIXA')
        ->setCellValue('G1', 'DEVOLVIDO');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $linha = 2;
    while ($row = mysqli_fetch_array($result)) {

        //  $objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getNumberFormat()->setFormatCode("yyyy-mm-dd");
        //  $objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getNumberFormat()->setFormatCode("hh:mm:ss");


        $pre = $row['pb_sga'] - $row['sga'];
        $saldo = $row['sma'] - ($row['sga'] + $pre);

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['nome'])
            ->setCellValue('B' . $linha, $row['cn'])
            ->setCellValue('C' . $linha, $row['tipo'])
            ->setCellValue('D' . $linha, $row['sma'])
            ->setCellValue('E' . $linha, $saldo)
            ->setCellValue('F' . $linha, $pre)
            ->setCellValue('G' . $linha, $row['sga']);

        $linha++;
    }

    $objPHPExcel->getActiveSheet(0)->setTitle('SGA');
    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-Type:   application/vnd.ms-excel');
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header('Content-Disposition: attachment;filename="SGA-SALDO -' . date("Y-m-d H-i-s") . '.xls"');
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
