<?php
$txtTitulo1 = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$txtTitulo = filter_input_array(INPUT_GET, FILTER_DEFAULT);

include_once "./conf/conexao2.php";

include "l_sessao.php";
include_once "./json_encode.php";

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

$re_sessao = $_SESSION["re"];
$uf_sessao = $_SESSION["uf"];

$acao = $txtTitulo['acao'];
$data = date('Y-m-d');
$hora = date('H:i');

$erro = "0";
$msg = "";

if ($acao === "xlsUsuario") {

    $regiao = regiao($mysqli, $re_sessao);
    xls_usuario($mysqli, $re_sessao, $regiao['gestao']);
} else
if ($acao === "xlsEquipamentos") {
    xls_equipamentos($mysqli, $re_sessao, $uf_sessao);
} else
if ($acao === "xlsGas") {
    xls_gas($mysqli, $re_sessao, $uf_sessao);
}
function xls_gas($mysqli, $re_sessao, $uf_sessao)
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


    //Permite Exportar todas as solicitações do UF
    $p = permissaoVerifica($mysqli, "79", $re_sessao);

    if ($p === 0) {
        $sql = "select cn.nome as cn, u.re as re, u.nome as nome, gt.nome as gas,gb.kg as quantidade, gb.data as data, gb.hora as hora from gas_tipo gt left join gas_bagagem gb on gb.tipo=gt.id left join usuario u on u.re=gb.re inner join cn on cn.id=u.cn where gb.kg>0 and u.estado='{$uf_sessao}' order by cn, nome, gas";
    } else {
        $sql = "select cn.nome as cn, u.re as re, u.nome as nome, gt.nome as gas,gb.kg as quantidade, gb.data as data, gb.hora as hora from gas_tipo gt left join gas_bagagem gb on gb.tipo=gt.id left join usuario u on u.re=gb.re inner join cn on cn.id=u.cn where gb.kg>0 order by cn, nome, gas";
    }
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
function xls_usuario($mysqli, $re_sessao, $gestao)
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

    $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('#4682B4');

    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));

    $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($style);
    $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A:K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


    //Permite Exportar todas as solicitações do UF
    $p = permissaoVerifica($mysqli, "72", $re_sessao);

    if ($p === 0) {
        $sql = "select g.nome as gestao, u.re as re, u.nome as nome, u.telefone as telefone, u.email as email, ca.nome as cargo, co.nome as coordenador, if(u.cartao='','S/CARTÃO',u.cartao) as cartao, cn.nome as cn, uf.sigla as uf, if(u.ativo=1,'INATIVO',if(u.ativo=2,'ATIVO','INVÁLIDO')) as status from usuario u left join usuario co on co.re=u.supervisor left join cn on cn.id=u.cn left join uf on uf.id=u.estado left join cargo inner join gestao g on g.id=u.gestao ca on ca.id=u.cargo where u.gestao='{$gestao}'";
    } else {
        $sql = "select g.nome as gestao, u.re as re, u.nome as nome, u.telefone as telefone, u.email as email, ca.nome as cargo, co.nome as coordenador, if(u.cartao='','S/CARTÃO',u.cartao) as cartao, cn.nome as cn, uf.sigla as uf, if(u.ativo=1,'INATIVO',if(u.ativo=2,'ATIVO','INVÁLIDO')) as status from usuario u left join usuario co on co.re=u.supervisor left join cn on cn.id=u.cn left join uf on uf.id=u.estado left join cargo ca on ca.id=u.cargo inner join gestao g on g.id=u.gestao";
    }
    $linha = 2;

    $result = $mysqli->query($sql);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'RE')
        ->setCellValue('B1', 'NOME')
        ->setCellValue('C1', 'TELEFONE')
        ->setCellValue('D1', 'E-MAIL')
        ->setCellValue('E1', 'CARTÃO')
        ->setCellValue('F1', 'CARGO')
        ->setCellValue('G1', 'COORDENDOR')
        ->setCellValue('H1', 'CN')
        ->setCellValue('I1', 'UF')
        ->setCellValue('J1', 'GESTÃO')
        ->setCellValue('K1', 'STATUS');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
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

    while ($row = mysqli_fetch_array($result)) {

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['re'])
            ->setCellValue('B' . $linha, $row['nome'])
            ->setCellValue('C' . $linha, $row['telefone'])
            ->setCellValue('D' . $linha, $row['email'])
            ->setCellValue('E' . $linha, $row['cartao'])
            ->setCellValue('F' . $linha, $row['cargo'])
            ->setCellValue('G' . $linha, $row['coordenador'])
            ->setCellValue('H' . $linha, $row['cn'])
            ->setCellValue('I' . $linha, $row['uf'])
            ->setCellValue('J' . $linha, $row['gestao'])
            ->setCellValue('K' . $linha, $row['status']);

        $linha++;
    }
    $sheet = "USUÁRIO - " . date('Y-m-d H-i-s');
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
function xls_equipamentos($mysqli, $re_sessao, $uf_sessao)
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

    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('#4682B4');

    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));

    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($style);
    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A:H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


    //Permite Exportar todas as solicitações do UF
    $p = permissaoVerifica($mysqli, "72", $re_sessao);

    if ($p === 0) {
        $sql = "select g.codigo as codigo, gt.nome as tipo, g.identificacao as identificacao, if(g.cartao='','S/CARTAO',g.cartao) as cartao, co.nome as coordenador, cn.nome as cn, uf.sigla as uf, gs.nome as status from gmg g inner join gmg_tipo gt on gt.id=g.tipo left join gmg_status gs on gs.id=g.status left join usuario co on co.re=g.supervisor left join cn on cn.id=g.cn left join uf on uf.id=g.estado where g.teste=0 and g.estado='{$uf_sessao}'";
    } else {
        $sql = "select g.codigo as codigo, gt.nome as tipo, g.identificacao as identificacao, if(g.cartao='','S/CARTAO',g.cartao) as cartao, co.nome as coordenador, cn.nome as cn, uf.sigla as uf, gs.nome as status from gmg g inner join gmg_tipo gt on gt.id=g.tipo left join gmg_status gs on gs.id=g.status left join usuario co on co.re=g.supervisor left join cn on cn.id=g.cn left join uf on uf.id=g.estado where g.teste=0";
    }
    $linha = 2;

    $result = $mysqli->query($sql);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'CÓDIGO')
        ->setCellValue('B1', 'TIPO')
        ->setCellValue('C1', 'IDENTIFICAÇÃO')
        ->setCellValue('D1', 'CARTÃO')
        ->setCellValue('E1', 'COORDENADOR')
        ->setCellValue('F1', 'CN')
        ->setCellValue('G1', 'UF')
        ->setCellValue('H1', 'STATUS');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

    while ($row = mysqli_fetch_array($result)) {

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['codigo'])
            ->setCellValue('B' . $linha, $row['tipo'])
            ->setCellValue('C' . $linha, $row['identificacao'])
            ->setCellValue('D' . $linha, $row['cartao'])
            ->setCellValue('E' . $linha, $row['coordenador'])
            ->setCellValue('F' . $linha, $row['cn'])
            ->setCellValue('G' . $linha, $row['uf'])
            ->setCellValue('H' . $linha, $row['status']);

        $linha++;
        // echo $row['codigo'].$row['tipo'].$row['identificacao'].$row['cartao'].$row['coordenador'].$row['cn'].$row['uf'].$row['status']."<br>";
    }
    $sheet = "EQP - " . date('Y-m-d H-i-s');
    $objPHPExcel->getActiveSheet()->setTitle($sheet);

    $objPHPExcel->setActiveSheetIndex(0);

    header('Content-Disposition: attachment;filename=' . $sheet . ".xls");

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');


    if ($objWriter->save('php://output')) {
        $erro = "0";
        $msg = "Exportação realizada com sucesso.";
    }
    exit;

    $result->close();
    $mysqli->close();

    $arr = array("erro" => $erro, "msg" => $msg);

    echo JsonEncodePAcentos::converter($arr);
}

function permissaoVerifica($mysqli, $funcao, $re)
{
    $num = $mysqli->query("select id from permissao where funcao='{$funcao}' and colaborador='{$re}'")->num_rows;
    return $num;
}
