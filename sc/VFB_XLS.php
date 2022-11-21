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
$data1 = $txtTitulo['dataInicio']; //"2020-01-01"; //
$data2 = $txtTitulo['dataFim']; ///"2020-01-01"; //
$status = $txtTitulo['status']; //"3"; //

$txt = strtoupper($txt);
$where = "";

if ($data1 != "") {
    $where .= " vv.data >='{$data1}'";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($data2 != "") {
    $where .= " vv.data <='{$data2}'";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($txt != "") {
    $where .= " u.nome='{$txt}' or u.re like '%" . $txt . "%' or cn.nome like '%" . $txt . "%' or s.sigla like '%" . $txt . "%'";
}
if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($status > 0) {
    $where .= " vv.status='{$status}'";
}
if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if (substr($where, -3) === "and") {
    $where =  substr($where, 0, (strlen($where) - 3));
}
$sql = "select vv.id as id, u.nome as vNome, if(vv.moTipo=1,'ICOMON','FORNECEDOR') as mo, e.nome as executante, vv.data as data, vv.hora as hora, ifnull(vs.nome,'NÃO DEFINIDO') as segmento, s.sigla as site, vv.os as os, vv.valor as valor, vv.solicitacao as servico, vst.nome as status, vcl.seg as seg, vcl.perg1 as p1, vcl.perg2 as p2, vcl.perg3 as p3, vcl.perg4 as p4, vcl.perg5 as p5, vcl.perg6 as p6, vcl.perg7 as p7, vcl.perg8 as p8, vcl.perg9 as p9 from vfb_vistoria vv inner join site s on s.id=vv.site inner join usuario u on u.re=vv.responsavel inner join usuario e on e.re=vv.mo inner join cn on cn.id=s.cn inner join vfb_status vst on vst.id=vv.status left join vfb_checklist vcl on vcl.vfb=vv.id left join vfb_segmento vs on vs.id=vcl.seg where" . $where . "";
$sql2 = "select va.vistoria as vistoria, va.id as id, if(va.tipo=0,'TEXTO','ARQUIVO') as tipo, va.descricao as descricao, va.data as data, va.hora as hora from vfb_vistoria vv inner join site s on s.id=vv.site inner join usuario u on u.re=vv.responsavel inner join usuario e on e.re=vv.mo inner join cn on cn.id=s.cn inner join vfb_anexo va on va.vistoria=vv.id where" . $where . "";

exporta_xls($mysqli, $sql, $sql2);

function exporta_xls($mysqli, $sql, $sql2)
{
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Icomon")
        ->setLastModifiedBy("Felipe Teixeira")
        ->setTitle("Export: " . date("Y-m-d H:i"))
        ->setSubject("Relatorio")
        ->setDescription("Solicitações VFB")
        ->setKeywords("PHPExcel")
        ->setCategory("result file");

    $objPHPExcel->getActiveSheet()->getStyle('A1:R1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('#4682B4');
    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));
    $objPHPExcel->getActiveSheet()->getStyle('A1:R1')->applyFromArray($style);

    $result = $mysqli->query($sql);

    $objPHPExcel->getActiveSheet()->getStyle('A:R')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ID')
        ->setCellValue('B1', 'VISTORIADOR')
        ->setCellValue('C1', 'MO TIPO')
        ->setCellValue('D1', 'MÃO DE OBRA')
        ->setCellValue('E1', 'DATA')
        ->setCellValue('F1', 'HORA')
        ->setCellValue('G1', 'SEGMENTO')
        ->setCellValue('H1', 'SITE')
        ->setCellValue('I1', 'OS')
        ->setCellValue('J1', 'VALOR DA OBRA')
        ->setCellValue('K1', 'SERVIÇO AUTORIZADO')
        ->setCellValue('L1', 'STATUS')
        ->setCellValue('M1', 'Obra executada conforme solicitação?')
        ->setCellValue('N1', 'Obra executada gerou alguma falha secundaria no site?')
        ->setCellValue('O1', 'Todas as falhas identificadas na OS foram sanadas após execução da atividade?')
        ->setCellValue('P1', 'O material utilizado na execução da atividade oferece algum risco ( material reutilizado, fora das normas, etc)?')
        ->setCellValue('Q1', 'A atividade esta dentro do padrão de qualidade solicitado?')
        ->setCellValue('R1', 'Foram deixados resíduos (sujeira,restos de materiais) no local da obra?')
        ->setCellValue('S1', 'Nota de avaliação geral da obra');

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
    $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
    $linha = 2;
    while ($row = mysqli_fetch_array($result)) {

        $objPHPExcel->getActiveSheet()->getStyle('B' . $linha)->getNumberFormat()->setFormatCode("yyyy-mm-dd");
        $objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getNumberFormat()->setFormatCode("hh:mm:ss");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['id'])
            ->setCellValue('B' . $linha, $row['vNome'])
            ->setCellValue('C' . $linha, $row['mo'])
            ->setCellValue('D' . $linha, $row['executante'])
            ->setCellValue('E' . $linha, $row['data'])
            ->setCellValue('F' . $linha, $row['hora'])
            ->setCellValue('G' . $linha, $row['segmento'])
            ->setCellValue('H' . $linha, $row['site'])
            ->setCellValue('I' . $linha, $row['os'])
            ->setCellValue('J' . $linha, "R$ ".$row['valor'])
            ->setCellValue('K' . $linha, $row['servico'])
            ->setCellValue('L' . $linha, $row['status'])
            ->setCellValue('M' . $linha, $row['p1'])
            ->setCellValue('N' . $linha, $row['p2'])
            ->setCellValue('O' . $linha, $row['p3'])
            ->setCellValue('P' . $linha, $row['p4'])
            ->setCellValue('Q' . $linha, $row['p5'])
            ->setCellValue('R' . $linha, $row['p6'])
            ->setCellValue('S' . $linha, $row['p7']);
        $linha++;
    }
 
    $objPHPExcel->getActiveSheet(0)->setTitle('VFB');
    $objPHPExcel->setActiveSheetIndex(0);

    //ITENS
    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(1);
    $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('#4682B4');
    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));
    $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($style);

    $result2 = $mysqli->query($sql2);

    $objPHPExcel->getActiveSheet()->getStyle('A:D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A1', 'VISTORIA')
        ->setCellValue('B1', 'ID')
        ->setCellValue('C1', 'TIPO')
        ->setCellValue('D1', 'DESCRIÇÃO')
        ->setCellValue('E1', 'DATA')
        ->setCellValue('F1', 'HORA');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

    $linha = 2;
    while ($row = mysqli_fetch_array($result2)) {
        $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A' . $linha, $row['vistoria'])
            ->setCellValue('B' . $linha, $row['id'])
            ->setCellValue('C' . $linha, $row['tipo'])
            ->setCellValue('D' . $linha, $row['descricao'])
            ->setCellValue('E' . $linha, $row['data'])
            ->setCellValue('F' . $linha, $row['hora']);

            $objPHPExcel->getActiveSheet()->getStyle('E' . $linha)->getNumberFormat()->setFormatCode("yyyy-mm-dd");
            $objPHPExcel->getActiveSheet()->getStyle('F' . $linha)->getNumberFormat()->setFormatCode("hh:mm:ss");
      
            $linha++;
    }

    $sheet = $objPHPExcel->setActiveSheetIndex(1)->setTitle('HISTÓRICO');
    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-Type:   application/vnd.ms-excel');
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header('Content-Disposition: attachment;filename="VFB -' . date("Y-m-d H-i-s") . '.xls"');
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
