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
    <script src="js/PA.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/menu.css<?php echo $versao; ?>">
    <link rel="stylesheet" href="css/all.css<?php echo $versao; ?>">
    <script src="js/SMA_SOLICITACAO.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">SMA</span></div>
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
                <div class="objeto m-2">
                    <div id="pa_formulario1" style="display:none" class="card border mt-2">
                        <div class="card-header font-weight-bold">Nova solicitação</div>
                        <div class="card-body">
                            <div class="card-body">
                                <div id="formProcuraSite" class="row mt-3 mb-3">
                                    <div class="col">
                                        <input class="form-control" placeholder="Procurar site..." type="text" id="formSite">
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-light border text-muted" id="btProcuraSite"><i class="icon-search-1"></i> Pesquisar site</button>
                                    </div>
                                    <div id="listaSite" class="table-responsive mt-3"></div>
                                </div>
                                <div id="formNovo" style="display: none;">
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1 border font-weight-bold text-muted rounded ">
                                            SITE: <span id="textoSite" value=""></span> ID: <span class="badge badge-light border" id="formSigla"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <select class="form-control" type="text" id="formSegmento"></select>
                                        </div>
                                        <div class="col-md mt-1">
                                            <select class="form-control" type="text" id="formTipoFatura"></select>
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <select class="form-control" type="text" id="formTipoOs"></select>
                                        </div>
                                        <div class="col-md mt-1">
                                            <input class="form-control" type="number" id="formOs" placeholder="TA/TP/OS">
                                        </div>
                                        <div class="col-md mt-1">
                                            <select class="form-control" type="text" id="formAlmox"></select>
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <select class="form-control" type="text" id="formSobressalente">
                                                <option value="2">SOBRESSALENTE</option>
                                                <option value="0">NÃO</option>
                                                <option value="1">SIM</option>
                                            </select>
                                        </div>
                                        <div class="col-md mt-1">
                                            <select class="form-control" type="text" id="formRede">
                                                <option value="0">REDE</option>
                                                <option value="1">Rede móvel</option>
                                                <option value="2">Rede Fixa</option>
                                            </select>
                                        </div>
                                        <div class="col-md mt-1">
                                            <select class="form-control" type="text" id="formRetira"></select>
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <textarea class="form-control" style="resize: none" id="formObs" placeholder="Observações"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <button class="btn btn-success" id="formCriar">Criar solicitação</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div id="retornoNova"></div>
                        </div>
                    </div>
                    <div id="pa_formulario2" style="display:none" class="card border mt-2 p-1">
                        <div class="card-header font-weight-bold">Nova solicitação</div>
                        <div class="card-body">
                            <div class="row mt-1-sm">
                                <div style="text-align: center; margin-top: 2%; margin-bottom: 1%; font-weight: bold" class="col">Solicitação <span id="NumeroSolicitacao"></span> | Sobressalente: <span id="Sobressalente"></span></div>
                            </div>
                            <div class="row mt-1-sm">
                                <div style="text-align: center; margin-top: 2%; margin-bottom: 1%; font-weight: bold" class="col">SITE: <span id="SiteSolicitacao"></span></div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <input class="fnovo form-control" type="text" id="ItemTXT" placeholder="NOVO ITEM...">
                                </div>
                                <div class="col-md mt-1">
                                    <button id="btFiltra1" class="fnovo btn btn-sm btn-outline-info"><i class="icon-search-1"></i> Filtrar</button>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-1-sm">
                                <div class="col">
                                    <button class="fnovo btn btn-sm btn-outline-success" id="formConclui"><i class="icon-ok-circled-1"></i> Concluir</button>
                                </div>
                                <div class="col">
                                    <button class="btn btn-sm btn-outline-danger" id="formCancela"><i class="icon-cancel-circled-1"></i> Cancelar</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div id="retornoNova1"></div>
                        </div> 
                    </div>
                    <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="form_upload" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ModalTitulo">Carregar Arquivo</span><span id="tipoUpload">1</span><span class="badge badge-light border ml-2">
                                            <div id="itemId"></div>
                                        </span></h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row mt-1-sm">
                                        <form id="formFiles" name="formFiles" action="javascript:void(0);" enctype="multipart/form-data">
                                            <div class="row mt-1 ml-1 mr-1">
                                                <div class="col bg bg-white rounded">
                                                    <div class="file-field">
                                                        <input class="btn btn-light border" type="file" name="file" required>
                                                    </div>
                                                </div>
                                                <div class="col bg bg-white rounded ml-1">
                                                    <input class="btn btn-light border" type="text" name="itemPA" placeholder="PA" id="itemPA" required disabled>
                                                </div>
                                                <div class="col bg bg-white">
                                                    <div class="file-field">
                                                        <button class="btn btn-light border" type="submit"><i class="icon-upload-1 text-info"></i> Enviar</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-1 ml-1 mr-1">
                                                <div class="col-md mt-1">
                                                    <button class="btn btn-light border text-muted" data-dismiss="modal"><i class='icon-reply-1'></i> Voltar</button>
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
                                        <script async src="js/SMA_UPLOAD.js<?php echo $versao; ?>"></script>
                                    </div>
                                </div>
                                <div class="modal-footer>">
                                    <div class="col alert alert-ligth">
                                        <div id="retornoUpload"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="display: none;" id="ListaItensAdd"></div>
                    <div style="display: none;" id="SolicitacaoItens"></div>
                </div>

            </div>
    </center>

</body>

</html>