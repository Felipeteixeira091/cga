<?php
include "versao.php";
include_once "sc/l_sessao.php";

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

    <link type="text/css" rel="stylesheet" href="css/all.css<?php echo $versao; ?>" />

    <script src="js/js_menu.js<?php echo $versao; ?>"></script>
    <script src="js/PROCESSO.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">PROCESSO</span></div>
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
            <div class="card border mt-2 mb-2 p-1" id="site_formulario1">
                <div class="card-header font-weight-bold">Processos</div>
                <div class="row mt-2">
                    <div class="col">
                        <button id="btFormNovo" class="btn btn-light border"><i class='icon-plus-circle'></i> Cadastrar novo</button>
                    </div>
                </div>
                <hr>
                <div class="row m-2" id="lista"></div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="novo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo">Cadastro de processo</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <select class="form-control" id="novo_tipo"></select>
                                </div>
                                <div class="col-md mt-1">
                                    <input class="form-control" placeholder="NOME" maxlength="64" id="novo_nome">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <textarea class="form-control" style="resize:none" id="novo_desc" placeholder="DESCRIÇÃO"></textarea>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <div class="d-flex p-2 bg bg-light border rounded text-muted">Arquivo enviado: <span id="novo_arq">pendente</span></div>
                                </div>
                            </div>
                            <div id="divUpload" class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <form id="formFiles" name="formFiles" action="javascript:void(0);" enctype="multipart/form-data">
                                        <div class="row mt-1 ml-1 mr-1">
                                            <div class="col rounded">
                                                <div class="file-field">
                                                    <input class="btn btn-light border" type="file" name="file" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-1 ml-1 mr-1">
                                            <div class="col">
                                                <div class="file-field">
                                                    <button class="btn btn-light border" type="submit"><i class="icon-upload-1 text-info"></i> Enviar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-1 ml-1 mr-1">
                                            <div class="col">
                                                <div id="return"></div>
                                            </div>
                                            <div class="col ml-1">
                                                <div class="spq spinner-border spinner-border-sm text-danger d-none" role="status"></div>
                                                <div class="spq spinner-grow spinner-grow-sm text-warning d-none" role="status"></div>
                                                <div id="progressBar" class="alert alert-light text-danger"><span></span></div>
                                            </div>
                                        </div>
                                        <div class="col alert alert-ligth">
                                            <div id="retornoUpload"></div>
                                        </div>
                                    </form>
                                </div>
                                <script async src="js/PROCESSO_UPLOAD.js<?php echo $versao; ?>"></script>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <button class="btn btn-sm btn-dark" id="bt_novo"><i class="icon-edit"></i> Cadastrar</button>
                                </div>
                                <div class="col-md mt-1">
                                    <button type="button" class="btn btn-sm btn-dark" id="bt_div_upload"><i class="icon-upload-1"></i> Enviar arquivo</button>
                                </div>
                                <div class="col-md mt-1">
                                    <button type="button" class="btn btn-sm btn-dark" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="ModalRetorno_novo"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_permissao" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo">Definição de permissões</h5>
                        </div>
                        <div class="modal-body">
                            <div class="accordion" id="accordionExample">
                                <div class="card border-0">
                                    <div class="card-header border" id="headingOne">
                                        <h5 class="mb-0">
                                            <button class="btn btn-light btn-sm border" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                <i class="icon-plus-1"></i> Atribuir
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="row mt-1-sm">
                                                <div class="col-md mt-1">
                                                    <select id="formPermissaoTipo" class="form-control"></select>
                                                </div>
                                                <div class="col-md mt-1">
                                                    <select id="formPermissaoPagina" style="display:none" class="form-control"></select>
                                                    <select id="formPermissaoFuncao" style="display:none" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col mt-2" id="div_bt_add" style="display:none">
                                                    <button class="btn btn-sm btn-success" id="formPermissaoADD"><i class="icon-lock-open-1"></i> Adicionar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border-0 mt-1">
                                    <div class="card-header border" id="headingTwo">
                                        <h5 class="mb-0">
                                            <button class="btn btn-light btn-sm border collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                <i class="icon-lock-open-1"></i> Atribuídas
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div class="row mt-2">
                                                <div class="row" class="mt-3">
                                                    <div class="col" id="Permissoes"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="retornoPermissao"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal bd-example-modal-sm hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_processo_exclui" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo">Confirma exclusão?</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-1">
                                <div class="col rounded ml-3 mr-3 pb-1 text-muted">
                                    Confirma a exclusão do processo: <span id="modal_processo_nome" class='font-weight-bold'></span> ?
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col rounded ml-3 mr-3 pb-1 font-weight-bold text-muted">
                                    <button data-dismiss="modal" class="btn btn-sm btn-primary">Não</button>
                                </div>
                                <div class="col rounded ml-3 mr-3 pb-1 font-weight-bold text-muted">
                                    <button id="bt_modal_processo_exclui" value="0" class="btn btn-sm btn-danger">Sim</button>
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
    </center>

</body>

</html>