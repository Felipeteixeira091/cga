<?php
include_once "sc/l_sessao.php";
include "versao.php";
?>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo $sistema ?></title>
    <link rel="icon" href="css/ico.png">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="js/popper.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="js/bootstrap.min.js"></script>


    <script src="js/js_menu.js<?php echo $versao; ?>"></script>
    <link rel="stylesheet" href="css/menu.css<?php echo $versao; ?>">
    <link rel="stylesheet" href="css/all.css<?php echo $versao; ?>">
    <script src="js/SGA_PENDENTE.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">SGA</span></div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto" id="nav">
                </ul>
            </div>
        </div>
    </nav>
    <center>
        <div class="container theme-showcase" role="main">

            <div style="margin: 2px;" class="objeto">
                <div id="pa_formulario1" class="card border mt-2">
                    <div class="card-header font-weight-bold">Baixas solicitadas</div>
                    <div class="card-body">
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <input type="date" class="form-control" id="SolicitacaoData1" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md mt-1">
                                <input type="date" class="form-control" id="SolicitacaoData2" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <input class="form-control" type="text" id="SolicitacaoTXT" placeholder="PESQUISAR SOLICITAÇÃO...">
                            </div>
                            <div class="col-md mt-1">
                                <select class="form-control" id="SolicitacaoStatus"></select>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <button id="btFiltra" class="btn btn-light border"><i class="icon-search-1"></i> Filtrar</button>
                            </div>
                            <div class="col-md mt-1">
                                <button type="button" class="btn btn-light border text-muted" id="bt_xls" disabled><i class="icon-download-2 text-success"></i> Exportar para .XLS</button>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <span id="spanXls"></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="retornoFiltro"></div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="DetalheSolicitacao" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo">Validar baixa solicitada: <span id="NumeroSolicitacao"></span></h5>
                        </div>
                        <div class="modal-body ml-2 mr-2">
                            <div class="row mt-2 mb-1">
                                <div class="col font-weight-bold rounded alert-secondary">Dados do solicitante</div>
                            </div>
                            <div class="row mt-1">
                                <div class="col font-weight-bold rounded alert-secondary">Solicitante: <span id="detalheSolicitanteNome"></span> - <span id="detalheSolicitanteRe"></span></div>
                            </div>
                            <div class="row mt-1">
                                <div class="col rounded alert-secondary"><i class="icon-commerical-building text-secondary"></i> Almoxarifado: <span id="detalheAmox"></span></div>
                                <div class="col ml-1 rounded alert-secondary">Coordenador: <span id="detalheCoordenadorNome"></span> - <span id="detalheCoordenadorRE"></span></div>
                            </div>
                            <div class="row mt-1 mb-1">
                                <div class="col font-weight-bold rounded alert-secondary">STATUS ATUAL: <span id="detalheStatus"></span></div>
                            </div>
                            <div class="row mt-1">
                                <div class="col rounded alert-secondary">Site: <span id="detalheSite"></span></div>
                                <div class="col ml-1 rounded alert-secondary">Atividade: <span id="detalheAtividade"></span></div>
                                <div class="col ml-1 rounded alert-secondary">OS: <span id="detalheOs"></span></div>
                            </div>
                            <div class="row mt-1">
                                <div class="col rounded alert alert-secondary text-sm-left text-muted">OBSERVAÇÕES: <span id="detalheObs"></span></div>
                            </div>
                            <div class="row mt-1">
                                <div class="col ml-1 rounded">
                                    <select class="form-control" id="sItens"></select>
                                </div>
                                <div class="col ml-1 rounded">
                                    <div class="col rounded alert-secondary"><span id="detalhePA"></span></div>
                                </div>
                            </div>
                            <div id="detalheItem">
                                <div class="row mt-1">
                                    <div class="col rounded alert-secondary">Baixa solicitada: <span id="detalheQtdBaixa"></span> | Baixa Válida: <span id="detalheQtdValida"></span></div>
                                </div>
                                <div class="row mt-1">
                                    <div id="detalheAlerta" class="col alert alert-danger alert-dismissible fade show" role="alert">
                                        Lembrete: Óleo lubrificante, deve sempre ser preenchido em <strong>LITROS</strong>.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="row mt-2" style="display: none;" id="rBaixa">
                                    <div class="col">
                                        <input type="number" placeholder="QTD Recebida" class="form-control" id="qtdEntregue">
                                    </div>
                                    <div class="col-4 ml-1 rounded">
                                        <button class="btn btn-light btn-sm text-muted border mt-1" id="formRecebido"><i class='icon-check'></i> Recebido</button>
                                    </div>
                                    <div class="col-2 ml-1 rounded">
                                        <button class="btn btn-light btn-sm text-muted border mt-1" id="btFinalizar"><i class='icon-check text-success'></i> Finalizar</button>
                                    </div>
                                </div>
                                <div class="row mt-2 border rounded pt-1" style="display: none;" id="rConclui">
                                    <div class="col rounded">
                                        <textarea class="form-control mt-1 mb-1 border border-light" placeholder="Observação..." id="obs2" style="resize: none"></textarea>
                                    </div>
                                    <div class="col ml-1 rounded">
                                        <select class="form-control" id="SolicitacaoAlmox"></select>
                                    </div>
                                    <div class="col-2 ml-1 rounded">
                                        <button type="button" id="btConcluir" class="btn btn-sm btn-light text-muted border"><i class='icon-ok-circle text-success'></i> Concluir</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col ml-1 rounded">
                                    <button type="button" class="btn btn-sm btn-light text-muted border" id="modalVolta" data-dismiss="modal"><i class='icon-reply-1'></i> Voltar</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="ModalRetorno"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div style="display: none;" id="ListaSolicitacao"></div>
    </center>

</body>

</html>