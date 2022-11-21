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
    if ($data1 < "2021-09-01") {
        $data1 = "2021-09-01";
    }
    $where .= " s.data >='{$data1}'";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($data2 != "") {
    $where .= " s.data <='{$data2}'";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($txt != "") {
    $where .= " (u.re like '%" . $txt . "%' or u.nome like '%" . $txt . "%' or site.sigla like '%" . $txt . "%')";
    $txt= "(u.re like '%" . $txt . "%' or u.nome like '%" . $txt . "%' or site.sigla like '%" . $txt . "%')";
}

if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if ($status != "0") {
    $where .= " s.status='{$status}'";
}
if ($where != "" and substr($where, -3) != "and") {
    $where .= " and";
}

if (substr($where, -3) === "and") {
    $where =  substr($where, 0, (strlen($where) - 3));
}

$sql = "select s.id as id, s.data as data, s.hora as hora, ss.nome as status, site.sigla as site, cn.nome as cn, if(s.tipo=1,'CORRETIVA','PREVENTIVA') as atividade, s.os as os_prisma, u.nome as nome, u.re as re, c.nome as coordenador_nome, c.re as coordenador_re, ifnull(sa.nome,'PENDENTE') as almoxarifado  from sga s inner join sga_status ss on ss.id=s.status inner join usuario u on u.re=s.re inner join site on site.id=s.site inner join cn on cn.id=u.cn left join sma_almoxarifado sa on sa.id=s.almoxarifado inner join usuario c on c.re=u.supervisor WHERE" . $where;
$sql_itens = "select s.id as id, st.nome as tipo, sb.qtd as baixa_solicitada, sb.qtd_entregue as baixa_valida from sga s inner join sga_status ss on ss.id=s.status inner join usuario u on u.re=s.re inner join site on site.id=s.site inner join cn on cn.id=u.cn inner join sga_baixa sb on s.id=sb.sga inner join sga_tipo st on st.id=sb.tipo WHERE " . $where;
$sql_sma = "select u.re as re_retirada, u.nome as nome_retirada, pa.numero as pa, pa.descricao as nome, st.nome as tipo, ssi.quantidade as qtd_retirado, site.sigla sigla, s.data as data from sma_solicitacao_itens ssi inner join sma_solicitacao s on s.id=ssi.solicitacao inner join sma_pa pa on pa.id=ssi.pa inner join sga_tipo st on st.id=pa.sga_tipo inner join usuario u on u.re=s.re_retirada inner join site on site.id=s.site WHERE s.status='3' and s.tipo=1 and s.data >='{$data1}' and s.data <='{$data2}'";

exporta_xls($mysqli, $sql, $sql_itens, $sql_sma);

function exporta_xls($mysqli, $sql, $sql_itens, $sql_sma)
{
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Icomon")
        ->setLastModifiedBy("Felipe Teixeira")
        ->setTitle("Export: " . date("Y-m-d H:i"))
        ->setSubject("Relatorio")
        ->setDescription("Solicitações SGA")
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
        ->setCellValue('B1', 'DATA')
        ->setCellValue('C1', 'HORA')
        ->setCellValue('D1', 'STATUS')
        ->setCellValue('E1', 'SITE')
        ->setCellValue('F1', 'CN')
        ->setCellValue('G1', 'ATIVIDADE')
        ->setCellValue('H1', 'OS')
        ->setCellValue('I1', 'NOME SOLICITANTE')
        ->setCellValue('J1', 'RE SOLICITANTE')
        ->setCellValue('K1', 'COORDENADOR')
        ->setCellValue('L1', 'ALMOXARIFADO');

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
        $objPHPExcel->getActiveSheet()->getStyle('C' . $linha)->getNumberFormat()->setFormatCode("hh:mm:ss");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['id'])
            ->setCellValue('B' . $linha, $row['data'])
            ->setCellValue('C' . $linha, $row['hora'])
            ->setCellValue('D' . $linha, $row['status'])
            ->setCellValue('E' . $linha, $row['site'])
            ->setCellValue('F' . $linha, $row['cn'])
            ->setCellValue('G' . $linha, $row['atividade'])
            ->setCellValue('H' . $linha, $row['os_prisma'])
            ->setCellValue('I' . $linha, $row['nome'])
            ->setCellValue('J' . $linha, $row['re'])
            ->setCellValue('K' . $linha, $row['coordenador_nome'])
            ->setCellValue('L' . $linha, $row['almoxarifado']);

        $linha++;
    }

    $objPHPExcel->getActiveSheet(0)->setTitle('SGA');
    $objPHPExcel->setActiveSheetIndex(0);

    //ITENS
    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(1);
    $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('#4682B4');
    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));
    $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($style);

    $result2 = $mysqli->query($sql_itens);

    $objPHPExcel->getActiveSheet()->getStyle('A:D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A1', 'SOLICITAÇÃO')
        ->setCellValue('B1', 'TIPO')
        ->setCellValue('C1', 'BAIXA SOLICITADA')
        ->setCellValue('D1', 'BAIXA CONFIRMADA');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

    $linha = 2;
    while ($row = mysqli_fetch_array($result2)) {
        $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A' . $linha, $row['id'])
            ->setCellValue('B' . $linha, $row['tipo'])
            ->setCellValue('C' . $linha, $row['baixa_solicitada'])
            ->setCellValue('D' . $linha, $row['baixa_valida']);
        $linha++;
    }

    $objPHPExcel->setActiveSheetIndex(1)->setTitle('ITENS SGA');
    $objPHPExcel->setActiveSheetIndex(0);

    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex(2)->setTitle('SMA');
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A1', 'PA')
        ->setCellValue('B1', 'NOME PA')
        ->setCellValue('C1', 'TIPO')
        ->setCellValue('D1', 'QUANTIDADE')
        ->setCellValue('E1', 'RE RETIRADO')
        ->setCellValue('F1', 'NOME RETIRADO')
        ->setCellValue('G1', 'SITE')
        ->setCellValue('H1', 'DATA');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

    $objPHPExcel->getActiveSheet()->getStyle('A:H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('#4682B4');
    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));
    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($style);

    $linha = 2;
    $result3 = $mysqli->query($sql_sma);
    while ($row = mysqli_fetch_array($result3)) {
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A' . $linha, $row['pa'])
            ->setCellValue('B' . $linha, $row['nome'])
            ->setCellValue('C' . $linha, $row['tipo'])
            ->setCellValue('D' . $linha, $row['qtd_retirado'])
            ->setCellValue('E' . $linha, $row['re_retirada'])
            ->setCellValue('F' . $linha, $row['nome_retirada'])
            ->setCellValue('G' . $linha, $row['sigla'])
            ->setCellValue('H' . $linha, $row['data']);
        $linha++;
    }
    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-Type:   application/vnd.ms-excel');
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header('Content-Disposition: attachment;filename="SGA -' . date("Y-m-d H-i-s") . '.xls"');
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
