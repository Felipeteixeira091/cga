<?php
include_once "sc/l_sessao.php";
include "versao.php";
?>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo $sistema; ?></title>
    <link rel="icon" href="css/ico.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/jquery.min.js"></script>

    <script src="js/materialize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>

    <script src="js/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>


    <script src="js/bootstrap.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script src="js/js_menu.js<?php echo $versao; ?>"></script>
    <link rel="stylesheet" href="css/all.css<?php echo $versao; ?>">
    <script src="js/CEP_ELEMENTO.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">CEP</span></div>
<span class="d-xl-none text-light" id="logado_sm"></span>
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
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="ModalView" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Elemento cadastrado - Nº: <span id="viewID"></span></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 ml-1 mr-1 bg bg-light border rounded text-muted font-weight-bold">
                                <span>RESPONSÁVEL: </span><span id="viewNome"></span>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 ml-1 mr-1 bg bg-light border rounded text-muted font-weight-bold">
                                <span class="font-weight-bold">ATIVO: </span><span id="viewAtivo"></span>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 bg bg-light border rounded ml-1">
                                <span>CN: </span><span id="viewCN"></span>
                            </div>
                            <div class="col-md mt-1 mr-1 bg bg-light border rounded ml-1">
                                <span>SITE: </span><span id="viewSite"></span>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 bg bg-light border rounded ml-1">
                                <span>ESTRUTURA: </span><span id="viewEstrutura"></span>
                            </div>
                            <div class="col-md mt-1 mr-1 bg bg-light border rounded ml-1">
                                <span>Nº GABINETE: </span> <span id="viewNgabinete"></span>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 bg bg-light border rounded ml-1">
                                <span>ELEMENTO: </span><span id="viewElemento"></span>
                            </div>
                            <div class="col-md mt-1 mr-1 bg bg-light border rounded ml-1">
                                <span>Nº ELEMENTO: </span><span id="viewNelemento"></span>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 mr-1 bg bg-light border rounded ml-1">
                                <span class="font-weight-bold">STATUS: </span><span id="viewStatus"></span>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 mr-1 bg bg-light border rounded ml-1">
                                <span>OBSERVAÇÕES: </span>
                                <hr><span id="viewObs"></span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md mt-1">
                                <button class="btn btn-light text-muted border" id="concluirElemento"><i class='icon-ok-circle text-success'></i> Concluir</button>
                            </div>
                            <div class="col-md mt-1">
                                <button class="btn btn-light text-muted border" id="" data-dismiss="modal"><i class='icon-reply'></i> Voltar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="ModalRetornoConcluir"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="ModalFiltro" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Filtrar Elementos cadastrados</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 rounded ml-1">
                                <select class="form-control" id="filtroCN"></select>
                            </div>
                            <div class="col-md mt-1 rounded ml-1">
                                <select class="form-control" id="filtroStatus"></select>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 rounded ml-1">
                                <label class="text-muted"><i class='icon-down-small'></i> Data inicial</label>
                                <input class="form-control" type="date" id="filtroData1">
                            </div>
                            <div class="col-md mt-1 mr-1 rounded ml-1">
                                <label class="text-muted"><i class='icon-down-small'></i> Data final</label>
                                <input class="form-control" type="date" id="filtroData2">
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 rounded ml-1">
                                <button class="btn btn-light text-muted border" id="filtraElemento"><i class='icon-filter text-primary'></i> Filtrar</button>
                            </div>
                            <div class="col-md mt-1 mr-1 rounded ml-1">
                                <button class="btn btn-light text-muted border" data-dismiss="modal"><i class='icon-reply'></i> Voltar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="ModalRetornoFiltro"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="ModalOpc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><span id="titulo_modal_status"></span><span id="contador"></span></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col bg-light p-1 ml-2 mr-2 border rounded text-muted font-weight-bold">
                                <span>A ação será aplicada aos elementos abaixo.</span>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col bg-light m-2 p-2 border rounded">
                                <div id="elementoLista"></div>
                                <div class="text-danger" id="elementoSpan"></div>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <button class="btn btn-light text-muted border" id="btIniciaTratativa"><i class='icon-play-2 text-info'></i> Iniciar</button>
                            </div>
                            <div class="col-md mt-1">
                                <button class="btn btn-light text-muted border" id="btConcluiTratativa"><i class='icon-ok-circle text-success'></i> Concluir</button>
                            </div>
                            <div class="col-md mt-1">
                                <button class="btn btn-light text-muted border" id="btCancelaTratativa"><i class='icon-minus-circled text-danger'></i> Cancelar</button>
                            </div>
                            <div class="col-md mt-1">
                                <button class="btn btn-light text-muted border" id="btStatus_confirma" data-dismiss="modal"><i class='icon-reply'></i> Voltar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="ModalRetornoOpc"></div>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin: 1px">
            <div class="container m-1 theme-showcase" role="main">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        ELEMENTOS
                    </div>
                    <div class="card-body">
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 d-none" id="divCheckAll">
                                <button class="btn btn-light text-muted border" id="checkAll"><i class='icon-check-1 text-primary'></i> Marcar todos</button>
                            </div>
                            <div class="col-md mt-1">
                                <button class="btn btn-light text-muted border" id="elementoFiltraModal"><i class="icon-filter text-primary"></i> Filtrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card m-1" id="acao" style="display: none">
            <div class="card-header font-weight-bold">AÇÕES</div>
            <div class="card-body">
                <div class="row mt-1 mb-1 bg-light pt-2 pb-2">
                    <div class="col">
                        <button class="btn btn-light text-muted border" id="btDownload"><i class='icon-download-2 text-success'></i> Baixar itens filtrados</button>
                    </div>
                    <div class="col">
                        <button class="btn btn-light text-muted border" id="btOpc"><i class='icon-cog text-info'></i> Mais opções</button>
                    </div>
                </div>
            </div>
            <div class="mt-2" id="Lista"></div>
        </div>
    </center>

</body>

</html>