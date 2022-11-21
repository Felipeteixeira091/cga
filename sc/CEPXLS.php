<?php

include_once "./conf/conexao2.php";
include_once "./json_encode.php";
include_once "./l_sessao.php";
require_once '../lib/PHPExcel/PHPExcel.php';


$txtTitulo = filter_input_array(INPUT_GET, FILTER_DEFAULT);
$acao = "exportaExcel"; // $txtTitulo['acao'];
$re = $_SESSION['re'];

if ($acao === "exportaExcel") {

    $data1 = $txtTitulo['data1'];
    $data2 = $txtTitulo['data2'];
    $status = $txtTitulo['status'];
    $cn = $txtTitulo['cn'];

    downloadExcel($mysqli, $status, $cn, $data1, $data2, $re);
}

function downloadExcel($mysqli, $status, $cn, $data1, $data2, $re)
{
    $where = "";
    $gestao = regiao($mysqli, $re)['gestao'];

    if ($status != "0") {
        $where .= " status='{$status}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($cn != "0") {
        $where .= " sit.cn='{$cn}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($data1 != "") {
        $where .= " ele.data_cadastro >='{$data1}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }
    if ($data2 != "") {
        $where .= " ele.data_cadastro <='{$data2}'";
    }
    if ($where != "" and substr($where, -3) != "and") {
        $where .= " and";
    }

    if (substr($where, -3) === "and") {
        $where =  substr($where, 0, (strlen($where) - 3));
    }

    $sql = "select ele.id as id, sit.tipo as tipo_site, ele.ePai as ePai, sit.sigla as site, ifnull(se.sigla,'ELEMENTO_PAI') as estrutura, ele.estrutura_n as estrutura_n, sel.excel as excel, sel.ativo_pai as ativo_pai, sel.sigla as elemento, ele.elemento_n as elemento_n, ele.fcc as fcc, ele.data_cadastro as data, ele.hora_cadastro as hora, es.nome as status, ele.re as re, uf.sigla as uf, cn.nome as cn, cn.id as cn_id, un.nome as unidade from cep_elemento ele left join site sit on sit.id=ele.site inner join cn on cn.id=sit.cn inner join uf on uf.id=cn.uf left join cep_site_estrutura se on se.id=ele.estrutura left join cep_site_elemento sel on sel.id=ele.elemento left join cep_elemento_status es on es.id=ele.status inner join usuario u on u.re=ele.re inner join unidade_negocio un on un.id=cn.fk_unidade WHERE u.gestao='{$gestao}' and" . $where . " order by ele.data_cadastro, ele.hora_cadastro";
    //    $sql = "select ele.id as id, ele.ePai as ePai, usr.re as re, usr.nome as nome, cn.nome as cn, cn.id as cn_id, sit.tipo as tipo_site, sit.sigla as site, ifnull(se.sigla,'ELEMENTO_PAI') as estrutura, ele.estrutura_n as estrutura_n, sel.excel as excel, sel.ativo_pai as ativo_pai, sel.sigla as elemento, ele.elemento_n as elemento_n, ele.fcc as fcc, ele.data_cadastro as data, ele.hora_cadastro as hora, es.nome as status, ele.observacao as obs, uf.sigla as uf from cep_elemento ele inner join site sit on sit.id=ele.site inner join cn on cn.id=sit.cn inner join uf on uf.id=cn.uf left join cep_site_estrutura se on se.id=ele.estrutura left join cep_site_elemento sel on sel.id=ele.elemento left join cep_elemento_status es on es.id=ele.status inner join usuario usr on usr.re=ele.re WHERE" . $where . " order by ele.data_cadastro";

    xls($mysqli, $sql, $re);
}
function unidade($mysqli, $cn)
{
    $sql = "select nome as u from unidade_negocio WHERE cn='{$cn}'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();

    return $row['u'];
}
function xls($mysqli, $sql, $re)
{
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Felipe Teixeira 29819")
        ->setLastModifiedBy($re)
        ->setTitle("CEP: " . date("Y-m-d H:i"))
        ->setSubject("Relatorio")
        ->setDescription("CADASTRO DE ELEMENTOS CEP")
        ->setKeywords("ICOMON MG")
        ->setCategory("Preventivas");

    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('#4682B4');

    $style = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));

    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($style);

    $result = $mysqli->query($sql);

    $objPHPExcel->getActiveSheet()->getStyle('A:M')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'CN')
        ->setCellValue('B1', 'UNIDADE')
        ->setCellValue('C1', 'SITE')
        ->setCellValue('D1', 'ATIVO PAI')
        ->setCellValue('E1', 'ATIVO')
        ->setCellValue('F1', 'Nº ELEMENTO')
        ->setCellValue('G1', 'ELEMENTO')
        ->setCellValue('H1', 'ESTRUTURA')
        ->setCellValue('I1', 'Nº ESTRUTURA')
        ->setCellValue('J1', 'FCC')
        ->setCellValue('K1', 'STATUS')
        ->setCellValue('L1', 'DATA/HORA CADASTRO')
        ->setCellValue('M1', 'OBSERVAÇÕES');

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

    $site = "";

    $linha = 2;
    while ($row = mysqli_fetch_array($result)) {
        $unidade = $row['unidade']; //unidade($mysqli, $row['cn_id']);
        $uf = $row['uf'];
        $elemento_n = "";

        if ($row['elemento_n'] === 0 or $row['elemento_n'] === "0") {
            $elemento_n = "";
        } else {
            $elemento_n = $row['elemento_n'];
        }
        $est_n = "";

        if ($row['estrutura_n'] === 0 or $row['estrutura_n'] === "0") {
            $est_n = "";
        } else {
            $est_n = $row['estrutura_n'];
        }
        $ativo_pai = $row['site'] . "." . $row['estrutura'] . $est_n . "." . $row['ativo_pai'];

        if ($row['estrutura'] === "ELEMENTO_PAI") {

            $ativo_pai = $row['site'];

            $ativo = $ativo_pai . "." . $row['elemento'];
        } else
        if ($row['elemento'] === "RF" or $row['elemento'] === "TX") {

            $ativo_pai = $row['site'] . "." . $row['estrutura'] . $est_n;

            $ativo = $ativo_pai . "." . $row['elemento'];
        } else
        if ($row['elemento'] === "ELTE" or $row['elemento'] === "ELTI") {

            $ativo_pai = $row['site'];

            $ativo = $row['site'] . "." . $row['elemento'];
        } else 
        if ($row['elemento'] === "EV") {

            $ativo_pai = $row['site'] . "." . $row['excel'] . $elemento_n;

            $ativo = $row['site'] . "." . $row['excel'] . $elemento_n . "." . $row['elemento'];
        } else
        if ($row['elemento'] === "QCAB") {

            $ativo_pai = $row['site'] . "." . $row['ativo_pai'];

            $ativo = $row['site'] . "." . $row['ativo_pai'] . "." . $row['elemento'] . $elemento_n;
        } else
        if ($row['elemento'] === "QDG") {

            $ativo_pai = $row['site'];

            $ativo = $row['site'] . "." . $row['elemento'] . $elemento_n;
        } else
        if ($row['elemento'] === "QDGE" or $row['elemento'] === "QDGN") {

            $ativo_pai = $row['site'] . "." . $row['estrutura'] . $est_n;

            $ativo = $ativo_pai . "." . $row['elemento'] . $elemento_n;
        } else
        if ($row['elemento'] === "SPDA") {

            $ativo_pai = $row['site'];

            $ativo = $ativo_pai . "." . $row['elemento'];
        } else
        if ($row['elemento'] === "SDAI") {

            $ativo_pai = $row['site'] . "." . $row['estrutura'] . $est_n;

            $ativo = $ativo_pai . "." . $row['elemento'];
        } else
        if ($row['elemento'] === "SELF") {

            $ativo_pai = $row['site'] . "." . $row['estrutura'] . "." . $row['ativo_pai'];

            $ativo = $ativo_pai . "." . $row['elemento'] . $elemento_n;
        } else
        if ($row['elemento'] === "SAC") {

            $ativo_pai = $row['site'] . "." . $row['estrutura'];

            $ativo = $ativo_pai . "." . $row['elemento'] . $elemento_n;
        } else
            ////
            if ($row['estrutura'] === "ELEMENTO_PAI") {

                $ativo = $row['site'] . "." . $row['ativo_pai'];

                $ativo_pai = $row['site'];
            } else
        if ($row['ativo_pai'] === "ESTRUTURA") {

                $ativo = $row['site'] . "." . $row['estrutura'] . $est_n . "." . $row['excel'] . $elemento_n;

                $ativo_pai = $row['site'] . "." . $row['estrutura'] . $est_n;
            } else 
        if ($row['ativo_pai'] === "ESTRUTURA_FONTE") {

                $ativo = $row['site'] . "." . $row['estrutura'] . $est_n . ".FCC" . $row['fcc'] . "." . $row['excel'] . $elemento_n;

                $ativo_pai = $row['site'] . "." . $row['estrutura'] . $est_n . ".FCC" . $row['fcc'];
            } else
        if ($row['ativo_pai'] === "TX" or $row['ativo_pai'] === "RF" or $row['ativo_pai'] === "SAC1") {

                $ativo = $row['site'] . "." . $row['estrutura'] . $est_n . "." . $row['ativo_pai'] . "." . $row['excel'] . $elemento_n;
            } else
        if ($row['ativo_pai'] === "CSP" or $row['ativo_pai'] === "GAB") {

                $ativo = $row['site'] . "." . $row['estrutura'] . $est_n;

                $ativo_pai = $row['site'] . "." . $row['estrutura'] . $est_n;
            } else
        if ($row['ativo_pai'] === "SITE") {

                $ativo = $row['site'] . "." . $row['excel'] . $elemento_n;

                $ativo_pai = $row['site'];
            } else 
        if ($row['ativo_pai'] === "CAP1") {

                $ativo = $row['site'] . "." . $row['estrutura'] . $est_n . "." . $row['ativo_pai'] . "." . $row['excel'] . $elemento_n;
            } else {

                $ativo = $row['site'] . "." . $row['estrutura'] . $est_n . "." . $row['excel'] . $elemento_n;

                $ativo_pai = $row['site'] . "." . $row['estrutura'] . $est_n . "." . $row['ativo_pai'];
            }

        if ($row['tipo_site'] === 1 or $row['tipo_site'] === "1") {

            $ativo = "M." . $uf . "." . $ativo;
            $ativo_pai = "M." . $uf . "." . $ativo_pai;
            $site = $row['site'];
        } else {
            $ativo = "V2." . $uf . "." . $ativo;
            $ativo_pai = "V2." . $uf . "." . $ativo_pai;
            $unidade = "V2." . $unidade;
            $site = "V2." . $row['site'];
        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $linha, $row['cn'])
            ->setCellValue('B' . $linha, $unidade)
            ->setCellValue('C' . $linha, $site)
            ->setCellValue('D' . $linha, $ativo_pai)
            ->setCellValue('E' . $linha, $ativo)
            ->setCellValue('F' . $linha, $row['elemento_n'])
            ->setCellValue('G' . $linha, $row['elemento'])
            ->setCellValue('H' . $linha, $row['estrutura'])
            ->setCellValue('I' . $linha, $row['estrutura_n'])
            ->setCellValue('J' . $linha, $row['fcc'])
            ->setCellValue('K' . $linha, $row['status'])
            ->setCellValue('L' . $linha, $row['data'] . " " . $row['hora'])
            ->setCellValue('M' . $linha, $row['obs']);

        $linha++;
    }

    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type:   application/vnd.ms-excel');
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header('Content-Disposition: attachment;filename="CEP-' . date("Y-m-d H-i-s") . '.xls"');
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
