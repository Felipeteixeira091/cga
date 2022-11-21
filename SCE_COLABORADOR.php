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
    <script src="js/SCE_COLABORADOR.js<?php echo $versao; ?>"></script>
    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">SCE</span></div>
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
                <div class="card border mt-2 p-1">
                    <div class="card-header font-weight-bold">Solicitação de combustível</div>
                    <div class="form-row mt-1">
                        <div class="col">
                            <select class="custom-select" id="sce_select_periodo"></select>
                        </div>
                        <div class="col">
                            <button id="sce_btn_filtra" class="btn btn-primary"><i class='icon-filter'></i> Filtrar</button>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col">
                            <button value="novo" id="nota_btn_nova" class="btn btn-primary"><i class='icon-doc-add'></i> Nova</button>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="sce_retorno_procura"></div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="sce_form_solicitacao" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo">Nova solicitação de combustível</h5>
                        </div>
                        <div class="card mt-1">
                            <div class="card-body">
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 bg-light border rounded">
                                        <span class="text-muted"><span id="detalheNome"></span><span class="badge badge-light text-right" id="detalheRe"></span></span>
                                    </div>
                                    <div class="col-md-3 mt-1 ml-md-1 align-middle bg-light border rounded">
                                        <i class="icon-credit-card"></i> <span class="text-muted"><span id="detalheCartao"></span></span>
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded">
                                        <i class='icon-calendar text-danger'></i><span class="text-muted">Última solicitação aprovada:<span class="badge badge-light text-muted text-right"> ID: <span id='idAnterior'></span></span> <span id="datalheUltSol"></span></span>
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded">
                                        <i class='icon-attention text-danger'></i> <span class="text-muted">Valor mês: <span id="detalheVlr_mes"></span></span>
                                    </div>
                                    <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded">
                                        <span class="text-muted"><span id="detalheModelo"></span> <span id="detalheIdentificacao"></span></span>
                                    </div>
                                    <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded">
                                        <span class="text-muted">Último KM: <span id="detalheUltKM"></span></span>
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <input class="form-control" id="sce_atual_saldo" onkeyup="somenteNumeros(this);" placeholder="Saldo atual">
                                    </div>
                                    <div class="col-md mt-1" id="formKM">
                                        <input class="form-control" id="sce_atual_km" onkeyup="somenteNumeros(this);" placeholder="Km Atual">
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <textarea class="form-control" placeholder="Observações" maxlength="140" id="sce_atual_obs" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div id="sce_form_solicita" class="row mt-2">
                                    <div class="col">
                                        <button class="btn btn-sm btn-primary" id="sce_btn_solicita"><i class="icon-ok-circled-1"></i> SOLICITAR <span class="badge badge-sm" id="sce_badge_id"></span></button>
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-sm btn-primary" data-dismiss="modal"><i class="icon-cancel-circled-1"></i> FECHAR</button>
                                    </div>
                                </div>
                                <div id="sce_form_upload" class="row mt-2">
                                    <div class="col">
                                        <button class="btn btn-sm btn-primary" id="sce_btn_anexo"><i class="icon-doc-add"></i> ADICIONAR ANEXO</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mt-2">
                                    <div class="col">
                                        <div id="sce_form_anexo"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div id="retornoSolicitacao"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade bd-example-modal-lg" data-keyboard="false" data-backdrop="static" id="sce_modal_upload" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Enviar arquivo</span></h5>
                        </div>
                        <div class="modal-body">
                            <form id="formFiles" name="formFiles" action="javascript:void(0);" enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="col">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="file" required>
                                            <label class="custom-file-label" for="validatedCustomFile"><i class='icon-attach-2'></i> Selecione o arquivo</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row mt-2">
                                    <div class="col">
                                        <select id="sce_upload_tipo" class="custom-select">
                                            <option selected value="0">Selecione o tipo</option>
                                            <option value="painel">Foto do painel</option>
                                            <option value="saldo">Print do saldo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mt-2">
                                    <div class="col">
                                        <div class="file-field">
                                            <button class="btn btn-primary btn-sm" type="submit"><i class="icon-upload-1"></i> Enviar</button>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-primary btn-sm" data-dismiss="modal"><i class='icon-cancel-circle-2'></i> Fechar</button>
                                    </div>
                                </div>
                                <div class="row mt-1 ml-1 mr-1">
                                    <div class="col bg bg-white">
                                        <div id="return"></div>
                                    </div>
                                    <div class="col bg bg-white ml-1">
                                        <div class="progress-bar bg-info rounded" id="progressBar"><span class="badge m-1"></span></div>
                                    </div>
                                </div>
                            </form>
                            <script async src="js/SCE_UPLOAD.js<?php echo $versao; ?>"></script>

                        </div>
                        <div class="modal-footer>">
                            <div class="col alert alert-ligth">
                                <div id="retornoUpload"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: none;" id="sce_table_lista"></div>
    </center>

</body>

</html>