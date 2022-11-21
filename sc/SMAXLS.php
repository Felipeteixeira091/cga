<?php
function obterSolicitacao($mysqli, $solicitacao)
{
    $sql = "SELECT sol.id as solicitacao, sol.tipo as tipo, sol.obs as obs, stipo.msg as msg, sol.re as colaboradorRe, col.nome as colaboradorNome, cn.endereco as Endereco, coo.supervisor as coordenadorRe, coo.nome as coordenadorNome, DATE_FORMAT(sol.solicitacao, '%Y-%m-%d') as Prazo, sol.os as Os, sit.sigla as Sigla, sit.descricao as descricao_site, sol.status as status, almox.nome as almoxNome, almox.email as almoxEmail, almox.copiaNome1 as copiaNome1, almox.copiaEmail1 as copiaEmail1,almox.copiaNome2 as copiaNome2, almox.copiaEmail2 as copiaEmail2,almox.copiaNome3 as copiaNome3, almox.copiaEmail3 as copiaEmail3,almox.copiaNome4 as copiaNome4, almox.copiaEmail4 as copiaEmail4,almox.copiaNome5 as copiaNome5, almox.copiaEmail5 as copiaEmail5,almox.copiaNome6 as copiaNome6, almox.copiaEmail6 as copiaEmail6, almox.copiaEmail7 as copiaEmail7, ret.nome as nomeRetirada, ret.re as reRetirada, retC.nome as nomeCretirada, retC.re as reCretirada, sol.sobressalente as sobressalente, ifnull(sseg.nome, 'INDEFINIDO') segmento FROM sma_solicitacao sol inner join usuario col on col.re=sol.re inner join usuario coo on coo.re=col.supervisor inner join site sit on sit.id=sol.site inner join sma_solicitacao_tiposegmento stipo on stipo.id=sol.tipo inner join sma_almoxarifado almox on almox.id=sol.almoxarifado inner join usuario ret on ret.re=sol.re_retirada inner join usuario retC on retC.re=ret.supervisor inner join cn on cn.id=col.cn left join sma_segmento sseg on sseg.id=sol.rede where sol.id='{$solicitacao}'";
    $sql_itens = "SELECT si.id as id, pa.numero as pa, pa.descricao as descricao, si.quantidade as quantidade, ptu.nome as unidade FROM sma_solicitacao_itens si inner join sma_pa pa on pa.id=si.pa inner join sma_pa_tipo_unidade ptu on ptu.id=pa.pa_tipo_unidade where si.solicitacao='{$solicitacao}'";


    $myArray = array();
    if ($result = $mysqli->query($sql_itens)) {
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $myArray[] = $row;
        }
    }

    $res = $mysqli->query($sql);
    $r = $res->fetch_assoc();

    $arr = array(
        "solicitacao" => $r,
        "itens" => $myArray
    );

    $result->close();

    return $arr;
}
function databaseToExcel($dados)
{

    $cab = $dados['solicitacao'];
    $item = $dados['itens'];
    $tipo = $cab['tipo'];


    $phpExcel = new PHPExcel();
    //Configure the sheet


    $phpExcel->getActiveSheet()->setTitle('XLS');
    $phpExcel->setActiveSheetIndex(0);
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

    $phpExcel->getProperties()->setCreator("Felipe Teixeira - Icomon")
        ->setLastModifiedBy("Felipe Teixeira")
        ->setTitle("Exportação")
        ->setSubject("Office 2013 XLSX Test Document")
        ->setDescription("Exportação de solicitações")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("XLS");

    $phpExcel->getActiveSheet()->getPageSetup()
        ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    $phpExcel->getActiveSheet()->getPageSetup()
        ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

    $phpExcel->getActiveSheet()->getPageMargins()->setHeader(0.8);
    $phpExcel->getActiveSheet()->getPageMargins()->setTop(0);
    $phpExcel->getActiveSheet()->getPageMargins()->setRight(0);
    $phpExcel->getActiveSheet()->getPageMargins()->setLeft(0);
    $phpExcel->getActiveSheet()->getPageMargins()->setBottom(0);
    $phpExcel->getActiveSheet()->getPageMargins()->setFooter(0.8);
    $phpExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(true);
    $phpExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
    $phpExcel->getActiveSheet()->getPageSetup()->setPrintArea('A1:AH50');

    $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(1.3);
    $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(4.0);
    $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(1.3);
    $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(11.9);
    $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(2.8);
    $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12.6);
    $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(2.0);
    $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12.5);
    $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(2.3);
    $phpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(2.3);
    $phpExcel->getActiveSheet()->getColumnDimension('K')->setWidth(1.7);
    $phpExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8.6);
    $phpExcel->getActiveSheet()->getColumnDimension('M')->setWidth(0.85);
    $phpExcel->getActiveSheet()->getColumnDimension('N')->setWidth(3.9);
    $phpExcel->getActiveSheet()->getColumnDimension('O')->setWidth(0.7);
    $phpExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12.3);
    $phpExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(1.0);
    $phpExcel->getActiveSheet()->getColumnDimension('R')->setWidth(1.0);
    $phpExcel->getActiveSheet()->getColumnDimension('S')->setWidth(3.6);
    $phpExcel->getActiveSheet()->getColumnDimension('T')->setWidth(1.7);
    $phpExcel->getActiveSheet()->getColumnDimension('U')->setWidth(11.1);
    $phpExcel->getActiveSheet()->getColumnDimension('V')->setWidth(1.3);
    $phpExcel->getActiveSheet()->getColumnDimension('W')->setWidth(1.7);
    $phpExcel->getActiveSheet()->getColumnDimension('X')->setWidth(5.0);
    $phpExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(5.0);
    $phpExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(5.0);
    $phpExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(5.0);
    $phpExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(6.6);
    $phpExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(14.1);
    $phpExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(1.7);
    $phpExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(9.1);
    $phpExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(1.8);
    $phpExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(5.9);
    $phpExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(1.7);

    $phpExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(12);
    $phpExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(7.5);
    $phpExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(21);
    $phpExcel->getActiveSheet()->getRowDimension(4)->setRowHeight(21.75);
    $phpExcel->getActiveSheet()->getRowDimension(5)->setRowHeight(7.5);
    $phpExcel->getActiveSheet()->getRowDimension(6)->setRowHeight(15.75);
    $phpExcel->getActiveSheet()->getRowDimension(7)->setRowHeight(15);
    $phpExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(6.75);
    $phpExcel->getActiveSheet()->getRowDimension(9)->setRowHeight(15.75);
    $phpExcel->getActiveSheet()->getRowDimension(10)->setRowHeight(6.75);
    $phpExcel->getActiveSheet()->getRowDimension(11)->setRowHeight(15);
    $phpExcel->getActiveSheet()->getRowDimension(12)->setRowHeight(6);
    $phpExcel->getActiveSheet()->getRowDimension(13)->setRowHeight(7.5);
    $phpExcel->getActiveSheet()->getRowDimension(14)->setRowHeight(17.25);
    $phpExcel->getActiveSheet()->getRowDimension(15)->setRowHeight(9);
    $phpExcel->getActiveSheet()->getRowDimension(16)->setRowHeight(3);
    $phpExcel->getActiveSheet()->getRowDimension(17)->setRowHeight(16.5);
    $phpExcel->getActiveSheet()->getRowDimension(29)->setRowHeight(15.75);
    $phpExcel->getActiveSheet()->getRowDimension(30)->setRowHeight(27);
    $phpExcel->getActiveSheet()->getRowDimension(31)->setRowHeight(18.75);
    $phpExcel->getActiveSheet()->getRowDimension(32)->setRowHeight(16.5);
    $phpExcel->getActiveSheet()->getRowDimension(33)->setRowHeight(15.75);
    $phpExcel->getActiveSheet()->getRowDimension(34)->setRowHeight(15.75);
    $phpExcel->getActiveSheet()->getRowDimension(35)->setRowHeight(15.75);
    $phpExcel->getActiveSheet()->getRowDimension(34)->setRowHeight(15.75);
    $phpExcel->getActiveSheet()->getRowDimension(39)->setRowHeight(21);
    $phpExcel->getActiveSheet()->getRowDimension(41)->setRowHeight(19.5);
    $phpExcel->getActiveSheet()->getRowDimension(42)->setRowHeight(18.75);
    $phpExcel->getActiveSheet()->getRowDimension(41)->setRowHeight(19.5);


    $BStyle = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE
            )
        )
    );
    $BStyleLn = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    );
    $BStyleLn2 = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    );
    $style_centrarlizar = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPEXcel_style_alignment::VERTICAL_CENTER,
        )
    );


    $phpExcel->getActiveSheet()->getStyle('A1:AH50')->applyFromArray($BStyle);
    $phpExcel->getActiveSheet()->getStyle('A1:AH50')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFFFF');
    $phpExcel->getActiveSheet()->getStyle('A1:AH3')->applyFromArray($BStyle);
    $phpExcel->getActiveSheet()->mergeCells('G1:AB3');

    $phpExcel->getActiveSheet()->mergeCells('AE1:AG1');
    $phpExcel->getActiveSheet()->mergeCells('AE3:AG3');

    $phpExcel->getActiveSheet()->getStyle('A4:AH5')->applyFromArray($BStyle);
    $phpExcel->getActiveSheet()->mergeCells('B4:AG5');

    $phpExcel->getActiveSheet()->getStyle('A6:AH12')->applyFromArray($BStyle);
    $phpExcel->getActiveSheet()->getStyle('A6:AH12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFC0C0C0');

    $phpExcel->getActiveSheet()->getStyle('A17:Q29')->applyFromArray($BStyle);
    $phpExcel->getActiveSheet()->getStyle('A17:Q29')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID); //->getStartColor()->setARGB('FFC0C0C0');

    $phpExcel->getActiveSheet()->getStyle('R17:AH29')->applyFromArray($BStyle);
    $phpExcel->getActiveSheet()->getStyle('R17:AH29')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID); //->getStartColor()->setARGB('FFC0C0C0');


    $phpExcel->getActiveSheet()->getStyle('E7:H7')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');
    $phpExcel->getActiveSheet()->getStyle('L7')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');
    $phpExcel->getActiveSheet()->getStyle('N7:P7')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFFFF');
    $phpExcel->getActiveSheet()->getStyle('R7:U7')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');
    $phpExcel->getActiveSheet()->getStyle('AA7:AG7')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');
    $phpExcel->getActiveSheet()->getStyle('G9:I9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');
    $phpExcel->getActiveSheet()->getStyle('N9:S9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');
    $phpExcel->getActiveSheet()->getStyle('X9:AG9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');
    $phpExcel->getActiveSheet()->getStyle('E11:W11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');
    $phpExcel->getActiveSheet()->getStyle('AA11:AG11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');

    $phpExcel->getActiveSheet()->mergeCells('E7:I7');
    $phpExcel->getActiveSheet()->mergeCells('R7:U7');
    $phpExcel->getActiveSheet()->mergeCells('G9:I9');
    $phpExcel->getActiveSheet()->mergeCells('N9:S9');
    $phpExcel->getActiveSheet()->mergeCells('X9:AG9');
    $phpExcel->getActiveSheet()->mergeCells('E11:W11');
    $phpExcel->getActiveSheet()->mergeCells('A11:AG11');

    $phpExcel->getActiveSheet()->mergeCells('A14:P14');
    $phpExcel->getActiveSheet()->getStyle('A14:P14')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');
    $phpExcel->getActiveSheet()->getStyle('A14:P14')->applyFromArray($BStyle);

    $phpExcel->getActiveSheet()->mergeCells('S14:AH14');
    $phpExcel->getActiveSheet()->getStyle('S14:AH14')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');
    $phpExcel->getActiveSheet()->getStyle('S14:AH14')->applyFromArray($BStyle);

    $phpExcel->getActiveSheet()->mergeCells('F17:L17');
    $phpExcel->getActiveSheet()->mergeCells('W17:AC17');
    $phpExcel->getActiveSheet()->getStyle('F17:L17')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF333333');
    $phpExcel->getActiveSheet()->getStyle('F17:L17')->applyFromArray($BStyle);

    $phpExcel->getActiveSheet()->mergeCells('A30:AH30');
    $phpExcel->getActiveSheet()->getStyle('A30:AH30')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $phpExcel->getActiveSheet()->getStyle('A30:AH30')->applyFromArray($BStyle);
    $phpExcel->getActiveSheet()->getStyle("A30")->getFont()->setSize(20)->setBold(true);
    $phpExcel->getActiveSheet()->getStyle('A30')->applyFromArray($style_centrarlizar);

    $phpExcel->getActiveSheet()->getStyle('W17:AC17')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF333333');
    $phpExcel->getActiveSheet()->getStyle('W17:AC17')->applyFromArray($BStyle);

    $phpExcel->getActiveSheet()->getStyle('B17')->applyFromArray($BStyle)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF333333');
    $phpExcel->getActiveSheet()->getStyle('D17')->applyFromArray($BStyle)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF333333');
    $phpExcel->getActiveSheet()->getStyle('N17')->applyFromArray($BStyle)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF333333');
    $phpExcel->getActiveSheet()->getStyle('P17')->applyFromArray($BStyle)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF333333');
    $phpExcel->getActiveSheet()->getStyle('S17')->applyFromArray($BStyle)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF333333');
    $phpExcel->getActiveSheet()->getStyle('U17')->applyFromArray($BStyle)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF333333');
    $phpExcel->getActiveSheet()->getStyle('AE17')->applyFromArray($BStyle)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF333333');
    $phpExcel->getActiveSheet()->getStyle('AG17')->applyFromArray($BStyle)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF333333');


    $phpExcel->getActiveSheet()->getStyle('F17:L17')->applyFromArray($BStyle)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF333333');
    $phpExcel->getActiveSheet()->getStyle('W17:AC17')->applyFromArray($BStyle)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF333333');

    $phpExcel->getActiveSheet()->getStyle('A6:AH12')->getFont()->setSize(10)->setBold(true);


    $fonte_branco = array('font'  => array('color' => array('rgb' => 'FFFFFF'),));

    $phpExcel->getActiveSheet()->mergeCells('A36:AH36');
    $phpExcel->getActiveSheet()->mergeCells('A37:AH37');
    $phpExcel->getActiveSheet()->mergeCells('A38:AH38');

    $phpExcel->getActiveSheet()->mergeCells('C11:D11');
    $phpExcel->getActiveSheet()->mergeCells('X11:Z11');
    $phpExcel->getActiveSheet()->mergeCells('AA11:AG11');

    $phpExcel->getActiveSheet()->getStyle('A36:AH36')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $phpExcel->getActiveSheet()->getStyle('A37:AH37')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $phpExcel->getActiveSheet()->getStyle('A38:AH38')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    $phpExcel->getActiveSheet()->getStyle('A36:AH36')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $phpExcel->getActiveSheet()->getStyle('A37:AH37')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $phpExcel->getActiveSheet()->getStyle('A38:AH38')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


    $phpExcel->getActiveSheet()->getStyle('F32:N32')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');
    $phpExcel->getActiveSheet()->getStyle('S32:AC32')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');
    $phpExcel->getActiveSheet()->getStyle('G34:N34')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');
    $phpExcel->getActiveSheet()->getStyle('V34:AC34')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF969696');

    for ($i = 19; $i <= 28; $i++) {


        $phpExcel->getActiveSheet()->getStyle('B' . $i . ':B' . $i)->applyFromArray($BStyleLn);
        $phpExcel->getActiveSheet()->getStyle('B' . $i . ':B' . $i)->getFont()->setSize(11)->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('D' . $i . ':D' . $i)->applyFromArray($BStyleLn);
        $phpExcel->getActiveSheet()->getStyle('D' . $i . ':D' . $i)->getFont()->setSize(10);
        $phpExcel->getActiveSheet()->mergeCells('F' . $i . ':L' . $i);
        $phpExcel->getActiveSheet()->getStyle('F' . $i . ':L' . $i)->applyFromArray($BStyleLn);
        $phpExcel->getActiveSheet()->getStyle('F' . $i . ':L' . $i)->getFont()->setSize(10)->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('N' . $i . ':N' . $i)->applyFromArray($BStyleLn);
        $phpExcel->getActiveSheet()->getStyle('P' . $i . ':P' . $i)->applyFromArray($BStyleLn);
        $phpExcel->getActiveSheet()->getStyle('P' . $i . ':P' . $i)->getFont()->setSize(10)->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('N' . $i . ':N' . $i)->getFont()->setSize(10)->setBold(true);

        $phpExcel->getActiveSheet()->getStyle('S' . $i . ':S' . $i)->applyFromArray($BStyleLn);
        $phpExcel->getActiveSheet()->getStyle('S' . $i . ':S' . $i)->getFont()->setSize(11)->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('U' . $i . ':U' . $i)->applyFromArray($BStyleLn);
        $phpExcel->getActiveSheet()->getStyle('U' . $i . ':U' . $i)->getFont()->setSize(10);
        $phpExcel->getActiveSheet()->mergeCells('W' . $i . ':AC' . $i);
        $phpExcel->getActiveSheet()->getStyle('W' . $i . ':AC' . $i)->applyFromArray($BStyleLn);
        $phpExcel->getActiveSheet()->getStyle('W' . $i . ':AC' . $i)->getFont()->setSize(10)->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('AE' . $i . ':AE' . $i)->applyFromArray($BStyleLn);
        $phpExcel->getActiveSheet()->getStyle('AE' . $i . ':AE' . $i)->getFont()->setSize(10)->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('AG' . $i . ':AG' . $i)->applyFromArray($BStyleLn);
        $phpExcel->getActiveSheet()->getStyle('AG' . $i . ':AG' . $i)->getFont()->setSize(10)->setBold(true);

        $j = $i - 18;

        $phpExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $i, $j)
            ->setCellValue('S' . $i, $j);
    }

    $i = 19;

    if ($tipo === "1") {

        $cpa = "D";
        $cpb = "F";
        $cpc = "N";
        $cpd = "P";
    } else {

        $cpa = "U";
        $cpb = "W";
        $cpc = "AE";
        $cpd = "AG";
    }
    foreach ($item as $pa => $descricao) {

        $p =  $item[$pa]['pa'];
        $d =  $item[$pa]['descricao'];
        $q =  $item[$pa]['quantidade'];
        $u =  $item[$pa]['unidade'];


        $phpExcel->setActiveSheetIndex(0)
            ->setCellValue($cpa . $i, $p)
            ->setCellValue($cpb . $i, $d)
            ->setCellValue($cpc . $i, $u)
            ->setCellValue($cpd . $i, $q);
        $i++;
    }

    $phpExcel->getActiveSheet()->getStyle('B18:AG28')->applyFromArray($style_centrarlizar);

    $phpExcel->getActiveSheet()->getStyle("G1")->getFont()->setSize(26)->setBold(true);
    $phpExcel->getActiveSheet()->getStyle('G1')->applyFromArray($style_centrarlizar);

    $phpExcel->getActiveSheet()->getStyle("B4")->getFont()->setSize(20)->setBold(true);
    $phpExcel->getActiveSheet()->getStyle('B4')->applyFromArray($style_centrarlizar);

    $phpExcel->getActiveSheet()->getStyle("A31:AH50")->getFont()->setSize(11)->setBold(true);

    $phpExcel->getActiveSheet()->getStyle('P39:AF39')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $phpExcel->getActiveSheet()->getStyle('A41:AH41')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOTTED);

    $phpExcel->getActiveSheet()->getStyle("A14:S14")->getfont()->setSize(11)->setBold(true);
    $phpExcel->getActiveSheet()->getStyle('A14:S14')->applyFromArray($style_centrarlizar);

    $phpExcel->getActiveSheet()->getStyle("B17:AG17")->getfont()->setSize(11)->setBold(true);
    $phpExcel->getActiveSheet()->getStyle('B17:AG17')->applyFromArray($style_centrarlizar)->applyFromArray($fonte_branco);

    $phpExcel->getActiveSheet()->mergeCells('AE50:AG50');
    $phpExcel->getActiveSheet()->getStyle("AE50")->getfont()->setSize(11)->setBold(true);

    $phpExcel->getActiveSheet()->getStyle("AE1:AE3")->getfont()->setSize(11)->setBold(true);
    $phpExcel->getActiveSheet()->getStyle('AE1:AE3')->applyFromArray($style_centrarlizar);

    $phpExcel->getActiveSheet()->getStyle('A35:AH35')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOUBLE);
    $phpExcel->getActiveSheet()->getStyle('A50:AH50')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    $phpExcel->setActiveSheetIndex(0)
        ->setCellValue('G1', 'MOVIMENTAÇÃO DE MATERIAIS WEB - O&M')
        ->setCellValue('AE1', 'Data: 19/10/2020')
        ->setCellValue('AE3', 'Revisão 0')
        ->setCellValue('B4', 'DADOS SOLICITANTE')
        ->setCellValue('C7', 'SOLICITANTE:')
        ->setCellValue('E7', $cab['nomeRetirada'])
        ->setCellValue('J7', 'RE:')
        ->setCellValue('L7', $cab['reRetirada'])
        ->setCellValue('N7', 'NUMERO TA/TP:')
        ->setCellValue('R7', $cab['Os'])
        ->setCellValue('X7', 'COORDENAÇÃO:')
        ->setCellValue('AA7', $cab['nomeCretirada'] . '-' . $cab['reCretirada'])
        ->setCellValue('C9', 'PRAZO DE ATENDIMENTO:')
        ->setCellValue('G9', $cab['Prazo'])
        ->setCellValue('K9', 'SIGLA SITE:')
        ->setCellValue('N9', $cab['Sigla'])
        ->setCellValue('U9', 'DESCRIÇÃO SITE:')
        ->setCellValue('X9', $cab['descricao_site'])
        ->setCellValue('AA11', $cab['segmento'])
        ->setCellValue('C11', 'ENDEREÇO:')
        ->setCellValue('E11', $cab['Endereco'])
        ->setCellValue('X11', 'SEGMENTO/TIPO:')
        ->setCellValue('A14', 'ITENS INSTALADOS')
        ->setCellValue('S14', 'ITENS RETIRADOS/A SEREM DEVOLVIDOS')
        ->setCellValue('F17', 'DESCRIÇÃO')
        ->setCellValue('W17', 'DESCRIÇÃO')
        ->setCellValue('B17', 'IT')
        ->setCellValue('D17', 'NET')
        ->setCellValue('N17', 'UN.')
        ->setCellValue('P17', 'QTDE.')
        ->setCellValue('S17', 'IT')
        ->setCellValue('U17', 'NET')
        ->setCellValue('AE17', 'UN.')
        ->setCellValue('AG17', 'QTDE.')
        ->setCellValue('D31', 'UNIDADE EM ESTOQUE:')
        ->setCellValue('H31', '(    ) SIM')
        ->setCellValue('I31', '(    ) NÃO')
        ->setCellValue('P31', 'NECESSÁRIO LOGÍSTICA:')
        ->setCellValue('X31', '(    ) SIM')
        ->setCellValue('Z31', '(    ) NÃO')
        ->setCellValue('A30', 'SOLICITAÇÃO DE LOGÍSITICA')
        ->setCellValue('D32', 'BASE ORIGEM:')
        ->setCellValue('P32', 'BASE DESTINO:')
        ->setCellValue('D34', 'TOTAL DE KM RODADO:')
        ->setCellValue('P34', 'PONTO DE ENCONTRO:')
        ->setCellValue('B35', 'OBS:')
        ->setCellValue('D39', 'DATA:')
        ->setCellValue('L39', 'ASSINATURA')
        ->setCellValue('D40', 'PLACA DO VEÍCULO TRANSPORTADOR:')
        ->setCellValue('F43', 'RECEBIDO POR ___________________________________________________  RE: _____________________')
        ->setCellValue('D45', 'DATA: _________________  ASSINATURA: ______________________________________________________')
        ->setCellValue('D47', 'PLACA DO VEÍCULO TRANSPORTADOR:');
//        ->setCellValue('AE50', 'Rev.1 20.09.19');

    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('test_img');
    $objDrawing->setDescription('test_img');
    $objDrawing->setPath('Temp/icomon.png');
    $objDrawing->setCoordinates('D1');
    //setOffsetX works properly
    $objDrawing->setOffsetX(0);
    $objDrawing->setOffsetY(6);
    //set width, height
    //$objDrawing->setWidth(100);
    //$objDrawing->setHeight(35);
    $objDrawing->setWorksheet($phpExcel->getActiveSheet());

    
    $caminho = '../sma_xls/';
    $nome = "SOLICITACAO_".$cab['solicitacao']."_".$cab['Sigla'] . ".xls";
    $writer = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    $writer->save($caminho . $nome);
    //    $redirect = JURI::base() . $dir . $filename;
    //   header('Location: ' . $redirect);

    $filename = $caminho . $nome;

    return $filename;
    //    if (file_exists($filename)) {
    //        echo "O arquivo $nome foi criado".$cab['Endereco'];
    //    } else {
    //        echo "O arquivo $nome não foi criado";
    //    }
}
