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

$re = $_SESSION["re"];
$uf_sessao = $_SESSION["uf"];

$txtTitulo = filter_input_array(INPUT_GET, FILTER_DEFAULT);

$acao = $txtTitulo['acao'];

if ($acao === "xlsl") {

    $dataInicio = $txtTitulo['dataInicio'];
    $dataFim = $txtTitulo['dataFim'];
    $cn = $txtTitulo['cn'];

    $p = permissaoVerifica($mysqli, "78", $re);

    $usuario = "";
    if ($p === 0) {
        $usuario = " l.re='{$re}' and ";
    }
    $where = "";

    if ($cn != "0") {
        $where .= " s.cn='{$cn}'";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($dataInicio != "0") {
        $where .= " '{$dataInicio}' <= l.prisma_data";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if ($dataFim != "") {
        $where .= " '{$dataFim}'  >= l.prisma_data";
    }

    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    $where = $usuario . $where;
    $sql = "select l.id as id, l.prisma_os os, l.data as data_lancamento, l.hora as hora_lancamento, l.prisma_data as os_data, l.prisma_hora as os_hora, gt.nome as gas_tipo, l.qtd_kg as qtd, s.sigla as site, cn.nome as cn, u.re as re, u.nome as nome, c.re as re_coordenador, c.nome as nome_coordenador from gas_lancamento l left join site s on s.id=l.site inner join usuario u on u.re=l.re inner join usuario c on c.re=u.supervisor left join cn on cn.id=u.cn inner join gas_tipo gt on gt.id=l.tipo WHERE" . $where . " order by os_data, os_hora asc";

    exporta_xls($mysqli, $sql);
} else 
if ($acao === "xlss") {

    $cn = $txtTitulo['cn'];

    if ($cn === "G") {
        $regiao = "";
    } else {
        $regiao = " and cn.id='{$cn}'";
    }

    $sql = "select cn.nome as cn, u.re as re, u.nome as nome, gt.nome as gas,gb.kg as quantidade, gb.data as data, gb.hora as hora from gas_tipo gt left join gas_bagagem gb on gb.tipo=gt.id left join usuario u on u.re=gb.re inner join cn on cn.id=u.cn where gb.kg>0" . $regiao . " order by cn, nome, gas";

    exporta_saldo($mysqli, $sql);
}
function exporta_saldo($mysqli, $sql)
{
    $erro = "1";

    $objPHPExcel = new PHPExcel();

    header('Content-Type:   application/vnd.ms-excel');
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    //  header('Content-Disposition: attachment;filename="SCE-' . date("Y-m-d H-i-s") . '.xls"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objPHPExcel->getProperties()->setCreator("Icomon")
        ->setLastModifiedBy("Felipe Teixeira")
        ->setTitle("Exportação")
        ->setSubject("Office 2013 XLSX Test Document")
        ->setDescription("Exportação de solicitações")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");

    $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('#238E23');

    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));

    $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($style);
    $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A:F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $linha = 2;

    $result = $mysqli->query($sql);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'CN')
        ->setCellValue('B1', 'RE')
        ->setCellValue('C1', 'NOME')
        ->setCellValue('D1', 'GÁS')
        ->setCellValue('E1', 'SALDO')
        ->setCellValue('F1', 'ÚLTIMA MODIFICAÇÃO');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

    while ($row = mysqli_fetch_array($result)) {

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['cn'])
            ->setCellValue('B' . $linha, $row['re'])
            ->setCellValue('C' . $linha, $row['nome'])
            ->setCellValue('D' . $linha, $row['gas'])
            ->setCellValue('E' . $linha, $row['quantidade'])
            ->setCellValue('F' . $linha, $row['data'] . " " . $row['hora']);

        $linha++;
    }
    $sheet = "SALDO GÁS - " . date('Y-m-d H-i-s');
    $objPHPExcel->getActiveSheet()->setTitle($sheet);

    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-Disposition: attachment;filename=' . $sheet . ".xls");

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');


    if ($objWriter->save('php://output')) {
        $erro = "0";
        $msg = "Exportação realizada com sucesso.";
    }
    //   exit;

    $result->close();
    $mysqli->close();

    $arr = array("erro" => $erro, "msg" => $msg);

    echo JsonEncodePAcentos::converter($arr);
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


    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('#4682B4');

    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));

    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($style);

    $result = $mysqli->query($sql);

    $objPHPExcel->getActiveSheet()->getStyle('A:H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'OS')
        ->setCellValue('B1', 'LANÇAMENTO')
        ->setCellValue('C1', 'SITE')
        ->setCellValue('D1', 'CN')
        ->setCellValue('E1', 'RE TÉCNICO')
        ->setCellValue('F1', 'TÉCNICO')
        ->setCellValue('G1', 'GÁS')
        ->setCellValue('H1', 'QTD');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

    $linha = 2;
    while ($row = mysqli_fetch_array($result)) {
        //   $objPHPExcel->getActiveSheet()->getStyle('G' . $linha)->getNumberFormat()->setFormatCode("#,##0.00");
        //     $objPHPExcel->getActiveSheet()->getStyle('D' . $linha)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);


        //        $operacao = '=CONCATENATE(I'.$linha.'," ",J'.$linha.')-CONCATENATE(G'.$linha.'," ",H'.$linha.')';


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['os'])
            ->setCellValue('B' . $linha, $row['data_lancamento'])
            ->setCellValue('C' . $linha, $row['site'])
            ->setCellValue('D' . $linha, $row['cn'])
            ->setCellValue('E' . $linha, $row['re'])
            ->setCellValue('F' . $linha, $row['nome'])
            ->setCellValue('G' . $linha, $row['gas_tipo'])
            ->setCellValue('H' . $linha, $row['qtd']);

        $linha++;
    }
    $objPHPExcel->getActiveSheet()->setTitle('XLS');
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type:   application/vnd.ms-excel');
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header('Content-Disposition: attachment;filename="UTILIZAÇÃO DE GÁS-' . date("Y-m-d H-i-s") . '.xls"');
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
function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
