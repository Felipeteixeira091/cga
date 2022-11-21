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
    <script src="js/CCC_CADASTRO.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">CCC</span></div>
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

                <div id="pa_formulario1" style="display:none" class="card border mt-2 p-1">
                    <div class="card-header font-weight-bold"><i class='icon-credit-card text-muted'></i> Informar utilização de cartão coorporativo</div>

                    <div class="row mt-3">
                        <div class="col">
                            <input type="date" class="form-control" id="notaData1" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" id="notaData2" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <input class="form-control" type="text" id="notaTXT" placeholder="PESQUISAR SOLICITAÇÃO...">
                        </div>
                        <div class="col">
                            <select class="form-control" id="cnLista"></select>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col">
                            <button id="btFiltraNota" class="btn btn-light border text-muted"><i class='icon-filter text-primary'></i> Filtrar</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-light border text-muted" id="bt_xls" disabled><i class="icon-download-2 text-success"></i> Exportar para .XLS</button>
                        </div>
                        <div class="col">
                            <button id="btNovo" class="btn btn-light border text-muted"><i class='icon-doc-add text-success'></i> Novo registo</button>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="retornoNotaPesquisa"></div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="modalNovo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo"><i class='icon-credit-card text-muted'></i> Informar utilização de cartão</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <button class="btn btn-light text-muted border" id="btModalSite"><i class="icon-search-1"></i> Selecionar site</button>
                                </div>
                                <div class="col-md mt-1">
                                    <button class="btn btn-light text-muted border" id="btModalArquivo"><i class="icon-upload-1 text-info"></i> Enviar Arquivo</button>
                                </div>
                            </div>
                            <div class="row mt-1-sm border rounded ml-2 mr-2 mt-1 mb-2 p-1" id="rowSite" style="display: none;">
                                <div class="col-md mt-1">
                                    <span class="font-weight-bold" id="textoSite"></span> <span class="badge badge-pill badge-light text-secondary" id="notaSite"></span>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <select id="notaTipo" class="form-control"></select>
                                </div>
                                <div class="col-md mt-1">
                                    <select id="notaMotivo" class="form-control"></select>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <input type="text" id="notaOs" class="form-control" placeholder="NÚMERO" disabled>
                                </div>
                                <div class="col-md mt-1">
                                    <input type="text" id="md5Arquivo" class="form-control" placeholder="Envie o arquivo" disabled>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <input type="text" id="notaValor" class="form-control" placeholder="VALOR" onKeyPress="return(moeda(this,'.',',',event))">
                                </div>
                                <div class="col-md mt-1">
                                    <input type="date" id="notaData" class="form-control" placeholder="DATA">
                                </div>
                                <div class="col-md mt-1">
                                    <input type="time" id="notaHora" class="form-control" placeholder="Hora">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <textarea class="form-control" id="notaOBS" style="resize: none" placeholder="OBSERVAÇÕES..."></textarea>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <button class="btn btn-light text-muted border" id="btnotaCadastra"><i class="icon-ok-circled-1 text-success"></i> Cadastrar</button>
                                </div>
                                <div class="col-md mt-1">
                                    <button type="button" class="btn btn-light text-muted border" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="retornoNotaNova"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" data-keyboard="false" data-backdrop="static" id="pesquisaSITE" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo">Procurar site</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <input class="form-control" placeholder="Procurar site..." type="text" id="formSite">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <button class="btn btn-outline-info" id="btProcuraSite"><i class="icon-search-1"></i> Procurar</button>
                                </div>
                                <div class="col-md mt-1">
                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
                                </div>
                            </div>
                            <div id="listaSite" class="table-responsive mt-3"></div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <div id="retornoSite"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="upload" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo">Enviar arquivo</h5><span class="badge badge-pill badge-light text-secondary" id="notaId"></span>
                        </div>
                        <div class="modal-body">
                            <div class="row p-2">
                                <div class="col"><span class="text-muted font-weight-bold">Arquivos permitidos: PDF, JPEG, JPG e PNG <i class="icon-attach-4 text-primary"></i></span></div>
                            </div>
                            <form id="formFiles" name="formFiles" action="javascript:void(0);" enctype="multipart/form-data">
                                <div class="row p-2">
                                    <div class="col">
                                        <div class="file-field">
                                            <input class="btn btn-light border text-muted d-inline-block text-truncate" type="file" name="file" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col">
                                        <button class="btn btn-light border text-muted" type="submit"><i class='icon-upload-1 text-primary'></i> Enviar</button>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-light border text-muted" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
                                    </div>
                                </div>
                            </form>
                            <script async src="js/CCC_UPLOAD.js<?php echo $versao; ?>"></script>
                            <div class="row">
                                <div class="col">
                                    <div class="pl-4 pr-4" id="progressBar"><span></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="retornoUpload"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="DetalheNota" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo">Custo gerado com cartão coorporativo: <span class="badge badge-pill badge-light text-secondary" id="notaNumeroDetalhe"></span> <span class="badge badge-pill badge-light text-secondary" id="cadastroDetalhe"></span></h5>
                        </div>
                        <div class="modal-body ml-2 mr-2">
                            <div class="row mt-2 mb-1">
                                <div class="col-md mt-1 font-weight-bold rounded bg-light border">Dados do colaborador</div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Nome:</span> <span id="notaSolicitanteNomeDetalhe"></span> - <span id="notaSolicitanteReDetalhe"></span></div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Data utilização:</span> <span id="notaDataDetalhe"></span></div>
                                <div class="col-md mt-1 ml-1 rounded bg-light border"><span class="font-weight-bold">Tipo de custo:</span> <span id="notaTipoDetalhe"></span></div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Site:</span> <span id="notaSiteDetalhe"></span></div>
                                <div class="col-md mt-1 ml-1 rounded bg-light border"><span class="font-weight-bold">Motivo:</span> <span id="notaMotivoDetalhe"></span></div>
                                <div class="col-md mt-1 ml-1 rounded bg-light border"><span class="font-weight-bold">OS:</span> <span id="notaOsDetalhe"></span></div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold"><i class="icon-money-1"></i> Valor da nota:</span> R$ <span id="notaValorDetalhe"></span></div>
                            </div>
                            <div class="row mt-2-sm">
                                <div class="col-md mt-1 alert-light rounded border">
                                    <textarea class="form-control mt-1 mb-1 border border-light" placeholder="Observação..." disabled id="obs2" style="resize: none"></textarea>
                                </div>
                            </div>
                            <div class="row mt-2-sm">
                                <div class="col-md mt-1">
                                    <button class="btn btn-light text-muted border" data-dismiss="modal"><i class='icon-reply-1'></i> Fechar</button>
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
        <div style="display: none;" id="notaLista"></div>
    </center>

</body>

</html>