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
$status = $txtTitulo['status'];

$txt = strtoupper($txt);
$where = "";

if ($data1 != "") {
    $where .= " n.dataNota >='{$data1}'";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($data2 != "") {
    $where .= " n.dataNota <='{$data2}'";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($txt != "") {
    $where .= " s.nome='{$txt}' or s.re like '%" . $txt . "%' or c.nome like '%" . $txt . "%' or c.re like '%" . $txt . "%' or sit.sigla like '%" . $txt . "%'";
}
if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($status > 0) {
    $where .= " n.status='{$status}'";
}
if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if (substr($where, -3) === "and") {
    $where =  substr($where, 0, (strlen($where) - 3));
}
$sql = "select n.id as id, cn.nome as cn, s.re as solicitante_re, s.nome as solicitante_nome, c.nome as colaborador_nome, c.re as colaborador_re, sit.sigla as site, n.data as data, n.hora as hora, n.dataNota as dataNota, ent.nome as tipo, n.obs as obs, enm.nome as motivo, ns.nome as status, concat('R$ ',n.valor) as valor from ext_nota n inner join usuario s on s.re=n.re inner join usuario c on c.re=n.colaborador inner join site sit on sit.id=n.site inner join ext_nota_status ns on ns.id=n.status inner join cn cn on cn.id=sit.cn inner join ext_nota_tipo ent on ent.id=n.tipo inner join ext_nota_motivo enm on enm.id=n.motivo where" . $where . "";

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

    $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('#4682B4');
    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));
    $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($style);

    $result = $mysqli->query($sql);

    $objPHPExcel->getActiveSheet()->getStyle('A:L')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'DATA CADASTRO')
        ->setCellValue('C1', 'CN')
        ->setCellValue('D1', 'SITE')
        ->setCellValue('E1', 'DATA NOTA')
        ->setCellValue('F1', 'TIPO NOTA')
        ->setCellValue('G1', 'OBSERVAÇÃO')
        ->setCellValue('H1', 'MOTIVO NOTA')
        ->setCellValue('I1', 'STATUS')
        ->setCellValue('J1', 'VALOR')
        ->setCellValue('K1', 'SOLICITANTE')
        ->setCellValue('L1', 'BENEFICIADO');

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

    $linha = 2;
    while ($row = mysqli_fetch_array($result)) {

        $objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getNumberFormat()->setFormatCode("yyyy-mm-dd");

        $result2 = $mysqli->query("SELECT v.obs as obs, v.data, v.hora as hora, s.nome as status FROM ext_nota_vida v left join ext_nota_status s on s.id=v.status where v.nota='{$row['id']}'");

        $obs = "";
        while ($rowObs = mysqli_fetch_array($result2)) {

            if ($rowObs['obs'] != "") {
                $obs .= $rowObs['data'] . $rowObs['hora'] . " " . $rowObs['status'] . ":" . $rowObs['obs'] . " | ";
            }
        }


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['id'])
            ->setCellValue('B' . $linha, $row['data'])
            ->setCellValue('C' . $linha, $row['cn'])
            ->setCellValue('D' . $linha, $row['site'])
            ->setCellValue('E' . $linha, $row['dataNota'])
            ->setCellValue('F' . $linha, $row['tipo'])
            ->setCellValue('G' . $linha, $obs)
            ->setCellValue('H' . $linha, $row['motivo'])
            ->setCellValue('I' . $linha, $row['status'])
            ->setCellValue('J' . $linha, $row['valor'])
            ->setCellValue('K' . $linha, $row['solicitante_nome'])
            ->setCellValue('L' . $linha, $row['colaborador_nome']);

        $linha++;
    }

    $objPHPExcel->getActiveSheet(0)->setTitle('NOTAS');
    $objPHPExcel->setActiveSheetIndex(0);

    //ITENS
    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-Type:   application/vnd.ms-excel');
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header('Content-Disposition: attachment;filename="E-FOLHA -' . date("Y-m-d H-i-s") . '.xls"');
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
