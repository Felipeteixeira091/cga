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
    <script src="js/EXT_VALIDA.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">NOTA</span></div>
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
                    <div class="card-header font-weight-bold">Histórico de notas</div>

                    <div class="form-row mt-1">
                        <div class="col">
                            <input type="date" class="form-control" id="notaData1" value="<?php echo '2022-08-09' ?>">
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" id="notaData2" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col">
                            <input class="form-control" type="text" id="notaTXT" placeholder="PESQUISAR...">
                        </div>
                        <div class="col">
                            <select class="form-control" id="nota_lista_fornecedor1"></select>
                        </div>
                        <div class="col">
                            <select class="form-control" id="nota_lista_status1"></select>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col">
                            <button id="btFiltraNota" class="btn btn-primary"><i class='icon-filter'></i> Filtrar</button>
                        </div>
                        <div class="col">
                            <button value="novo" id="nota_btn_nova" class="btn btn-primary"><i class='icon-doc-add'></i> Nova</button>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="retornoNotaPesquisa"></div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="nota_modal_nova" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" role="document">
                        <div class="modal-header">
                            <h5 class="modal-title" id="nota_modal_novo_titulo">Cadastro de nota <span class="badge badge-pill badge-light text-secondary"></span></h5>
                        </div>
                        <div class="modal-body ml-2 mr-2">
                            <div class="accordion accordion-sm" id="accordionExample">
                                <div class="card border-0">
                                    <div class="card-header border" id="heading1">
                                        <h5 class="mb-0">
                                            <button class="btn btn-light btn-sm" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                                <i class="icon-doc-5"></i> Cadastro
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapse1" class="collapse show" aria-labelledby="heading1" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="form-row">
                                                <div class="col-md mb-3">
                                                    <label class="badge">Fornecedor</label>
                                                    <select name="nota_lista_fornecedor" id="nota_lista_fornecedor" class="custom-select">
                                                    </select>
                                                </div>
                                                <div class="col-md mb-3">
                                                    <label class="badge">Tipo de nota</label>
                                                    <select name="nota_lista_tipo_nota" id="nota_lista_tipo_nota" class="custom-select">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md mb-3">
                                                    <label class="badge">Nº Pedido</label>
                                                    <input type="number" id="nota_nova_pedido" class="form-control">
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="col-md mb-3">
                                                    <label class="badge">Responsável pela inclusão</label>
                                                    <input id="nota_input_responsavel" type="text" disabled class="form-control" placeholder="Nome" required>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md mb-3">
                                                    <button id="nota_btn_cadastra" class="btn btn-primary" type="submit"><span id="nota_badge_id" class="badge badge-light"></span> Cadastra informações</button>
                                                </div>
                                                <div class="col-md mb-3">
                                                    <button id="nota_btn_update" value="0" class="btn btn-primary" type="submit">Concluir Solicitação</button>
                                                </div>
                                                <div class="col-md mb-3">
                                                    <button id="nota_btn_upload" class="btn btn-primary" type="submit">Anexar arquivos</button>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md md-3">
                                                    <span id="nota_span_status" class="badge m-1"></span>
                                                </div>
                                            </div>
                                            <div class="form-row" style="display:none;">
                                                <div class="col-md md-3">
                                                    <button id="nota_btn_deleta" class="btn btn-primary" type="submit">Deletar registro</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border-0 mt-1">
                                    <div class="card-header border rounded" id="headingTwo">
                                        <h5 class="mb-0">
                                            <button class="btn btn-light btn-sm collapsed" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                                <i class="icon-attach-2"></i> Arquivos
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="col" id="nota_modal_arquivos"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row mt-2">
                                <div class="col-md mb-3">
                                    <button class="btn btn-light text-muted border" data-dismiss="modal"><i class='icon-cancel-circle-2 text-danger'></i> Fechar</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="nota_modal_footer"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-sm hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_nota_deleta" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo">Confirma exclusão?</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-1">
                                <div class="col rounded ml-3 mr-3 pb-1 text-muted">
                                    Confirma a exclusão da nota <span id="modal_nota_nome" class='font-weight-bold'></span> ?
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col rounded ml-3 mr-3 pb-1 font-weight-bold text-muted">
                                    <button data-dismiss="modal" class="btn btn-sm btn-primary">Não</button>
                                </div>
                                <div class="col rounded ml-3 mr-3 pb-1 font-weight-bold text-muted">
                                    <button id="bt_modal_nota_deleta" value="0" class="btn btn-sm btn-danger">Sim</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="ModalRetornoExclusao"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade bd-example-modal-lg" data-keyboard="false" data-backdrop="static" id="nota_modal_upload" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Carregar Arquivo PDF</span></h5>
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
                                        <input class="form-control" type="text" id="nota_arquivo" placeholder="Nome do arquivo" required>
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
                                        <div class="progress-bar bg-warning rounded" id="progressBar"><span class="badge m-1"></span></div>
                                    </div>
                                </div>
                            </form>
                            <script async src="js/NOTA_UPLOAD.js<?php echo $versao; ?>"></script>

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
        <div style="display: none;" id="notaLista"></div>
    </center>

</body>

</html>