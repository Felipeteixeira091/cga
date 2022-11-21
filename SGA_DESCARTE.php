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
    <script src="js/SGA_DESCARTE.js<?php echo $versao; ?>"></script>

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
        <div style="margin: 1px">
            <div class="container theme-showcase" role="main">
                <div style="margin: 2px;" class="objeto">
                    <div class="card border mt-2 p-1">
                        <div class="card-header font-weight-bold">Unidades de descarte de resíduos <span class="d-none" id="spanid">| ID: </span><span class="badge badge-info" id="spanIdPai"></span></div>
                        <div class="card-body">
                            <div id="accordion">
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="mb-0">
                                            <button class="btn btn-light btn-sm text-muted border" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                <i class="icon-shop-1 text-primary"></i> Unidades de descarte
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row p-2">
                                                <div class="col">
                                                    <div class="ml-3 mr-3" id="almoxLista"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header" id="headingTwo">
                                        <h5 class="mb-0">
                                            <button class="btn btn-light btn-sm text-muted border" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                                <i class="icon-note text-primary"></i> Notas
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row p-2">
                                                <div class="col">
                                                    <span class="text-muted font-weight-bold"><i class="icon-loop-1 text-success"></i> Reciclagens realizadas</span>
                                                </div>
                                            </div>
                                            <div class="row mt-1-sm">
                                                <div class="col-md">
                                                    <select id="notaUnidade" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="row mt-1-sm">
                                                <div class="col-md mt-1 rounded">
                                                    <input class="form-control" type="date" value="<?php echo date('2021-10-01'); ?>" id="data1">
                                                </div>
                                                <div class="col mt-1 rounded">
                                                    <input class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>" id="data2">
                                                </div>
                                            </div>
                                            <div class="row mt-1-sm">
                                                <div class="col-md mt-1">
                                                    <button class="btn btn-light btn-sm text-muted border" id="btFiltrar"><i class="icon-search-5 text-info"></i> Filtrar</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div id="retornoNota"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="modalAlmox" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Acompanhamento de resíduos para descarte <span class="badge badge-light border" id="addElementoPai"></span></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1">
                            <div class="col bg bg-dark rounded ml-3 mr-3 pb-1 font-weight-bold text-white">
                                <span id="tituloAlmox"></span><span class="d-none" id="idAlmox"></span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col alert alert-ligth">
                                <div id="residuos"></div>
                            </div>
                        </div>
                        <div class="row mt-1">
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

        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="ModalReciclagem" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Informar descarte<span class="badge badge-light border" id=""></span> <span class="badge badge-light text-muted border" id=""></span></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1">
                            <div class="col bg bg-dark rounded ml-3 mr-3 pb-1 font-weight-bold text-white">
                                Reciclar: <span id="itemRecicla"></span> <span class="d-none" id="itemReciclaID"></span>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col bg bg-light rounded ml-3 mr-3 pb-1 text-muted border">
                                Disponível para reciclagem: <span id="itemQuantidade"></span>
                            </div>
                        </div>
                        <div class="row mt-1 ml-1 mr-1">
                            <div class="col bg bg-white rounded">
                                <input class="btn btn-light border" type="text" id="qtdDescarte" name="qtd" placeholder="Informe a qtd" required>
                            </div>
                            <div class="col bg bg-white rounded ml-1">
                                <button type="button" id="btConfirma" class="btn btn-sm btn-light border text-muted"><i class='icon-loop-1 text-success'></i> Confirmar</button>
                            </div>
                        </div>
                        <div class="row mt-1 ml-1 mr-1">
                            <div class="col bg bg-info rounded m-2 text-white">
                                Após o recebimento do comprovante de reciclagem, necessário anexar.
                            </div>
                        </div>
                        <div class="row mt-1 ml-1 mr-1">
                            <div class="col bg bg-white">
                                <button type="button" class="btn btn-sm btn-light border text-muted" data-dismiss="modal"><i class='icon-reply-1'></i> Voltar</button>
                            </div>
                        </div>
                        <div class="row mt-1 ml-1 mr-1">
                            <div class="col bg bg-white">
                                <div id="return"></div>
                            </div>
                            <div class="col bg bg-white ml-1">
                                <div id="progressBar"><span></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="confirmacaoRetorno"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" data-keyboard="false" data-backdrop="static" id="DetalheDescarte" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Detalhes do descarte ID: <span id="idDescarte"></span></h5>
                    </div>
                    <div class="modal-body m-1">
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Responsável pelo descarte:</span> <span id="detalheNome"></span> - <span id="detalheRe"></span></div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Data/Hora:</span> <span id="detalheData"></span></div>
                            <div class="col-md mt-1 ml-1 rounded bg-light border"><span class="font-weight-bold">Unidade:</span> <span id="detalheUnidade"></span></div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Tipo de resíduo:</span> <span id="detalheTipo"></span></div>
                            <div class="col-md mt-1 ml-1 rounded bg-light border"><span class="font-weight-bold">Descarte:</span> <span id="detalheQtd"></span></div>
                            <div class="col-md mt-1 ml-1 rounded bg-light border"><span class="font-weight-bold">Restante:</span> <span id="detalheQtdA"></span></div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Tipo de arquivo:</span> <span id="detalheNota"></span></div>
                            <div class="col-md mt-1 ml-1 rounded bg-light border">
                                <button type="button" id="detalheBtnDown" value="" class="btn btn-light  btn-sm border text-muted m-2 d-none"><i class="icon-download-2 text-success"></i> Baixar</button>
                                <button type="button" id="detalheBtnUp" value="" class="btn btn-light  btn-sm border text-muted m-2 d-none"><i class="icon-upload-1 text-info"></i> Enviar arquivo</button>
                            </div>
                        </div>
                        <div class="row mt-1-sm d-none" id="rowObs">
                            <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Observações:</span> <span id="detalheObs"></span></div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 rounded bg-light border">
                                <button type="button" class="btn btn-light btn-sm border text-muted m-2" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col alert alert-ligth">
                        <div id="retornoUpload1"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="ModalReciclagemUpload" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Enviar arquivo<span class="badge badge-light border" id=""></span> <span class="badge badge-light text-muted border" id=""></span></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1">
                            <div class="col bg bg-light rounded text-muted border m-1">
                              <textarea id="observacao" class="form-control m-1" style="overflow:auto;resize:none" rows="3" cols="15" placeholder="Observações (Opcional)"></textarea>
                            </div>
                        </div>
                        <form id="formFiles" name="formFiles" action="javascript:void(0);" enctype="multipart/form-data">
                            <div class="row mt-1 ml-1 mr-1">
                                <div class="col bg bg-white rounded">
                                    <div class="file-field">
                                        <input class="btn btn-light border" type="file" name="file" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1 ml-1 mr-1">
                                <div class="col bg bg-white">
                                    <div class="file-field">
                                        <button class="btn btn-sm btn-light border" type="submit"><i class="icon-upload-1 text-info"></i> Enviar</button>
                                    </div>
                                </div>
                                <div class="col bg bg-white">
                                    <div class="file-field">
                                        <button type="button" class="btn btn-sm btn-light border text-muted" data-dismiss="modal"><i class='icon-reply-1'></i> Voltar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1 ml-1 mr-1">
                                <div class="col bg bg-white">
                                    <div id="return"></div>
                                </div>
                                <div class="col bg bg-white ml-1">
                                    <div id="progressBar"><span></span></div>
                                </div>
                            </div>
                        </form>
                        <script async src="js/SGA_DESCARTE_UPLOAD.js<?php echo $versao; ?>"></script>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="retornoUpload"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: none;" id="listaNota"></div>
    </center>

</body>

</html>