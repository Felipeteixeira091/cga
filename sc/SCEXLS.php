<?php
$txtTitulo1 = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$txtTitulo = filter_input_array(INPUT_GET, FILTER_DEFAULT);

include_once "./conf/conexao2.php";
include_once "./json_encode.php";

require_once '../lib/PHPExcel/PHPExcel.php';
require_once '../lib/PHPMailer/PHPMailerAutoload.php';

session_start();
$re_sessao = $_SESSION["re"];
$uf_sessao = $_SESSION["uf"];

$acao = $txtTitulo['acao'];
$data = date('Y-m-d');
$hora = date('H:i');

$erro = "0";
$msg = "";

if ($acao === "xlsSolicitacao") {

    $coordenador = $txtTitulo['coordenador'];
    $status = $txtTitulo['status'];
    $data1 = $txtTitulo['data1'];
    $data2 = $txtTitulo['data2'];


    xls_solicitacao($mysqli, $coordenador, $status, $data1, $data2, $re_sessao, $uf_sessao);
} else 
if ($acao === "xlsFrota") {

    xls_frota($mysqli, $re_sessao, $uf_sessao);
}

function xls_solicitacao($mysqli, $coordenador, $status, $data1, $data2, $re_sessao, $uf_sessao)
{

    $erro = "1";
    if ($data1 === "" or $data2 === "") {

        $msg = "Você deve selecionar a data inicial e final.";
    } else {

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


        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB('#4682B4');

        $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));

        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($style);


        $where = "";

        //Permite Exportar todas as solicitações do sistema
        $p1 = permissaoVerifica($mysqli, "16", $re_sessao);

        //Permite Exportar todas as solicitações do UF
        $p2 = permissaoVerifica($mysqli, "31", $re_sessao);

        if ($p1 > 0) {
            $query = "select s.id as id, s.tipo as tipo, s.cartao as cartao, concat(vei.vei_marca,' ',vei.vei_modelo) as veiculo,s.km as km, u.re as re_colaborador, u.nome as nome_colaborador, co.re as re_coordenador, co.nome as nome_coordenador, ifnull(apr.re, 'ND') as re_aprovacao, ifnull(apr.nome,'COORDENADOR') as nome_aprovacao, s.identificacao as identificacao, g.identificacao as gmg, gt.nome as gTipo, concat('R$ ',format(s.valor,2,'de_DE')) as valor, s.data as data, s.hora as hora, concat('R$ ',format(s.saldo,2,'de_DE')) as saldo, ss.id as status, ss.nome as status_N from solicitacao s inner join usuario u on s.colaborador=u.re left join usuario co on co.re=s.solicitante left join gmg g on s.identificacao=g.codigo left join gmg_tipo gt on g.tipo=gt.id inner join solicitacao_status ss on ss.id=s.status left join solicitacao_historico sh on sh.id=s.aprovacao left join usuario apr on apr.re=sh.re left join frota fr on fr.placa=s.identificacao left join veiculo vei on vei.vei_id=fr.veiculo WHERE";
        } else if ($p2 > 0) {
            $query = "select s.id as id, s.tipo as tipo, s.cartao as cartao, concat(vei.vei_marca,' ',vei.vei_modelo) as veiculo,s.km as km, u.re as re_colaborador, u.nome as nome_colaborador, co.re as re_coordenador, co.nome as nome_coordenador, ifnull(apr.re, 'ND') as re_aprovacao, ifnull(apr.nome,'COORDENADOR') as nome_aprovacao, s.identificacao as identificacao, g.identificacao as gmg, gt.nome as gTipo, concat('R$ ',format(s.valor,2,'de_DE')) as valor, s.data as data, s.hora as hora, concat('R$ ',format(s.saldo,2,'de_DE')) as saldo, ss.id as status, ss.nome as status_N from solicitacao s inner join usuario u on s.colaborador=u.re left join usuario co on co.re=s.solicitante left join gmg g on s.identificacao=g.codigo left join gmg_tipo gt on g.tipo=gt.id inner join solicitacao_status ss on ss.id=s.status left join solicitacao_historico sh on sh.id=s.aprovacao left join usuario apr on apr.re=sh.re left join frota fr on fr.placa=s.identificacao left join veiculo vei on vei.vei_id=fr.veiculo WHERE s.uf='$uf_sessao'";
        } else {
            $query = "select s.id as id, s.tipo as tipo, s.cartao as cartao, concat(vei.vei_marca,' ',vei.vei_modelo) as veiculo,s.km as km, u.re as re_colaborador, u.nome as nome_colaborador, co.re as re_coordenador, co.nome as nome_coordenador, ifnull(apr.re, 'ND') as re_aprovacao, ifnull(apr.nome,'COORDENADOR') as nome_aprovacao, s.identificacao as identificacao, g.identificacao as gmg, gt.nome as gTipo, concat('R$ ',format(s.valor,2,'de_DE')) as valor, s.data as data, s.hora as hora, concat('R$ ',format(s.saldo,2,'de_DE')) as saldo, ss.id as status, ss.nome as status_N from solicitacao s inner join usuario u on s.colaborador=u.re left join usuario co on co.re=s.solicitante left join gmg g on s.identificacao=g.codigo left join gmg_tipo gt on g.tipo=gt.id inner join solicitacao_status ss on ss.id=s.status left join solicitacao_historico sh on sh.id=s.aprovacao left join usuario apr on apr.re=sh.re left join frota fr on fr.placa=s.identificacao left join veiculo vei on vei.vei_id=fr.veiculo WHERE s.uf='$uf_sessao' and s.solicitante='{$re_sessao}'";
        }
        $query = "select s.id as id, s.tipo as tipo, s.cartao as cartao, concat(vei.vei_marca,' ',vei.vei_modelo) as veiculo,s.km as km, u.re as re_colaborador, u.nome as nome_colaborador, co.re as re_coordenador, co.nome as nome_coordenador, ifnull(apr.re, 'ND') as re_aprovacao, ifnull(apr.nome,'COORDENADOR') as nome_aprovacao, s.identificacao as identificacao, g.identificacao as gmg, gt.nome as gTipo, concat('R$ ',format(s.valor,2,'de_DE')) as valor, s.data as data, s.hora as hora, concat('R$ ',format(s.saldo,2,'de_DE')) as saldo, ss.id as status, ss.nome as status_N from solicitacao s inner join usuario u on s.colaborador=u.re left join usuario co on co.re=s.solicitante left join gmg g on s.identificacao=g.codigo left join gmg_tipo gt on g.tipo=gt.id inner join solicitacao_status ss on ss.id=s.status left join solicitacao_historico sh on sh.id=s.aprovacao left join usuario apr on apr.re=sh.re left join frota fr on fr.placa=s.identificacao left join veiculo vei on vei.vei_id=fr.veiculo WHERE";
        
        if (substr($query, -5) != "WHERE") {
            $where .= " and";
        }
        if ($coordenador != 0) {
            $where .= " co.re='{$coordenador}'";
        }
        if ($where != "" and substr($where, -3) != "and") {
            $where .= " and";
        }
        if ($status != 0) {
            $where .= " ss.id='{$status}'";
        }
        if ($where != "" and substr($where, -3) != "and") {
            $where .= " and";
        }
        if ($data1 != "") {
            $where .= " s.data >= '{$data1}'";
        }
        if ($where != "" and substr($where, -3) != "and") {
            $where .= " and";
        }
        if ($data2 != "") {
            $where .= " s.data <= '{$data2}'";
        }
        if ($where != "" and substr($where, -3) != "and") {
            $where .= " and";
        }

        if (substr($where, -3) === "and") {
            $where =  substr($where, 0, (strlen($where) - 3));
        }

        $sql = $query . $where . " group by id order by tipo, nome_coordenador, nome_colaborador, identificacao";

        $linha = 2;

        $result = $mysqli->query($sql);

        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ID')    
        ->setCellValue('B1', 'CARTÃO')
            ->setCellValue('C1', 'RE')
            ->setCellValue('D1', 'COLABORADOR/EQUIPAMENTO')
            ->setCellValue('E1', 'VEÍCULO')
            ->setCellValue('F1', 'KM/ACOPLAMENTO')
            ->setCellValue('G1', 'RE COORDENADOR')
            ->setCellValue('H1', 'COORDENADOR')
            ->setCellValue('I1', 'APROVAÇÃO')
            ->setCellValue('J1', 'VALOR')
            ->setCellValue('K1', 'DATA')
            ->setCellValue('L1', 'HORA')
            ->setCellValue('M1', 'SALDO ATUAL DO CARTÃO')
            ->setCellValue('N1', 'STATUS');


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
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);

        while ($row = mysqli_fetch_array($result)) {

            $re = "";
            $nome = "";
            $veiculo = "";

            if ($row['tipo'] === "1") {
                $nome = $row['nome_colaborador'];
                $re = $row['re_colaborador'];
                $veiculo = "[".$row['identificacao']."]".$row['veiculo'] . " KM: " . $row['km'];
            } else {
                $nome = $row['gTipo'] . "_" . $row['gmg'];
                $re = $row['identificacao'];
                $veiculo = "ND";
            }

            $objPHPExcel->getActiveSheet()->getStyle('J' . $linha)->getNumberFormat()->setFormatCode("#,##0.00");
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $linha, $row['id'])
                ->setCellValue('B' . $linha, $row['cartao'])
                ->setCellValue('C' . $linha, $re)
                ->setCellValue('D' . $linha, $nome)
                ->setCellValue('E' . $linha, $veiculo)
                ->setCellValue('F' . $linha, $row['km'])
                ->setCellValue('G' . $linha, $row['re_coordenador'])
                ->setCellValue('H' . $linha, $row['nome_coordenador'])
                ->setCellValue('I' . $linha, $row['nome_aprovacao'])
                ->setCellValue('J' . $linha, $row['valor'])
                ->setCellValue('K' . $linha, $row['data'])
                ->setCellValue('L' . $linha, $row['hora'])
                ->setCellValue('M' . $linha, $row['saldo'])
                ->setCellValue('N' . $linha, $row['status_N']);

            $linha++;
        }
        $sheet = "SCE-" . date('Y-m-d H-i-s');
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
    }

    $arr = array("erro" => $erro, "msg" => $msg);

    echo JsonEncodePAcentos::converter($arr);
}
function xls_frota($mysqli, $re_sessao, $uf_sessao)
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


    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('#4682B4');

    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));

    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($style);
    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A:I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


    $where = "";

    //Permite Exportar todas as solicitações do UF
    $p = permissaoVerifica($mysqli, "32", $re_sessao);

    if ($p > 0) {
        $sql = "select f.placa as PLACA, concat(v.vei_marca,' ', v.vei_modelo) as VEICULO, f.km as KM, ifnull(u.re,'') as RE, ifnull(u.nome,'') as NOME, uU.re as RE_ULTIMO,uU.nome as NOME_ULTIMO, cn.nome as CN, if((select count(id) from usuario where frota=f.placa)=0,'DESATIVADO','ATIVADO') as STATUS from frota f left join usuario u on u.frota=f.placa left join usuario uU on uU.re=f.ultimo_colaborador left join cn on cn.id=uU.cn inner join veiculo v on v.vei_id=f.veiculo order by cn.uf, cn.id, uU.nome";
    } else {
        $sql = "select f.placa as PLACA, concat(v.vei_marca,' ', v.vei_modelo) as VEICULO, f.km as KM, ifnull(u.re,'') as RE, ifnull(u.nome,'') as NOME, uU.re as RE_ULTIMO,uU.nome as NOME_ULTIMO, cn.nome as CN, if((select count(id) from usuario where frota=f.placa)=0,'DESATIVADO','ATIVADO') as STATUS from frota f left join usuario u on u.frota=f.placa left join usuario uU on uU.re=f.ultimo_colaborador left join cn on cn.id=uU.cn inner join veiculo v on v.vei_id=f.veiculo WHERE uU.estado='$uf_sessao' order by cn.uf, cn.id, uU.nome";
    }

    $linha = 2;

    $result = $mysqli->query($sql);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'PLACA')
        ->setCellValue('B1', 'VEICULO')
        ->setCellValue('C1', 'KM')
        ->setCellValue('D1', 'STATUS')
        ->setCellValue('E1', 'RE')
        ->setCellValue('F1', 'NOME')
        ->setCellValue('G1', 'RE ÚLTIMO COLABORADOR')
        ->setCellValue('H1', 'NOME ÚLTIMO COLABORADOR')
        ->setCellValue('I1', 'CN');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

    while ($row = mysqli_fetch_array($result)) {

        if ($row['RE'] === 0) {
            $re = "";
        } else {
            $re = $row['RE'];
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['PLACA'])
            ->setCellValue('B' . $linha, $row['VEICULO'])
            ->setCellValue('C' . $linha, $row['KM'])
            ->setCellValue('D' . $linha, $row['STATUS'])
            ->setCellValue('E' . $linha, $re)
            ->setCellValue('F' . $linha, $row['NOME'])
            ->setCellValue('G' . $linha, $row['RE_ULTIMO'])
            ->setCellValue('H' . $linha, $row['NOME_ULTIMO'])
            ->setCellValue('I' . $linha, $row['CN']);

        $linha++;
    }
    $sheet = "FROTAS-" . date('Y-m-d H-i-s');
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
