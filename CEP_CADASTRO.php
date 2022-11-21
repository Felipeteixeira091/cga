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
    <script src="js/CEP_CADASTRO.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">CEP</span></div>
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
    <div class="alert alert-danger font-weight-bold" role="alert" id="dataLimite"></div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Novo elemento <span class="badge badge-light border" id="addElementoPai"></span></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1">
                            <div class="col bg bg-dark rounded ml-3 mr-3 pb-1 font-weight-bold text-white">
                                SITE: <span id="addElementoSite"></span> - ID: <span class="badge badge-light border" id="addElementoIdSite"></span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <select class="form-control" type="text" id="formEstrutura"></select>
                            </div>
                            <div class="col">
                                <input type="number" id="formNgabinete" class="form-control" placeholder="Nº Gabinete">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <select class="form-control" type="text" id="formElementoTipo"></select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <select class="form-control" type="text" id="formElemento"></select>
                            </div>
                            <div class="col" style="display: none" id="col_fcc">
                                <input type="number" id="formNFCC" class="form-control" placeholder="Nº FCC">
                            </div>
                            <div class="col">
                                <input type="number" id="formNelemento" class="form-control" placeholder="Nº Elemento">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <textarea class="form-control" style="resize: none" id="formObs" placeholder="Observações"></textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <button class="btn btn-light border text-muted" id="formAddElemento"><i class="icon-doc-add text-info"></i> Adicionar</button>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-light border text-muted" data-dismiss="modal"><i class='icon-reply-1'></i> Voltar</button>
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
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="ModalExcluiElemento" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Exluir elemento: <span class="badge badge-light border" id="excluiElementoPai"></span> <span class="badge badge-light text-muted border" id="excluiElementoIDG"></span></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1">
                            <div class="col bg bg-dark rounded ml-3 mr-3 pb-1 font-weight-bold text-white">
                                SITE: <span id="excluiElementoSite"></span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <button class="btn btn-light border text-muted" id="formExcluiElemento"><i class='icon-minus-circled text-danger'></i> Confirmar</button>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-light border text-muted" data-dismiss="modal"><i class='icon-reply-1'></i> Voltar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="ModalRetornoExcluir"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="ModalConcluiCadastro" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Concluir Cadastro: <span class="badge badge-light border" id="concluiElementoPai"></span> <span class="badge badge-light text-muted border" id="concluiElementoIDG"></span></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1">
                            <div class="col bg bg-dark rounded ml-3 mr-3 pb-1 font-weight-bold text-white border">
                                QUANTIDADE DE ELEMENTOS: <span id="concluiQtdElementos"></span>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col text-muted">
                                <div id="concluiElementos"></div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col bg-light border rounded ml-3 mr-3 pb-1 font-weight-bold text-muted">
                                Clicando em "SIM", os ítens acima vão ser cadastrados.<br>
                                <span class="text-success">CONFIRMAR CADASTRO ?</span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <button class="btn btn-light border text-muted" id="formConcluirCadastro"><i class="icon-ok-circle text-success"></i> SIM</button>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-light border text-muted" data-dismiss="modal"><i class='icon-reply-1'></i> Voltar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="ModalRetornoConclui"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="ModalCancelaCadastro" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Cancelar Cadastro <span class="badge badge-light border" id="cancelaElementoPai"></span> <span class="badge badge-light text-muted border" id="cancelaElementoIDG"></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1">
                            <div class="col bg bg-dark rounded ml-3 mr-3 pb-1 font-weight-bold text-white border">
                                QUANTIDADE DE ELEMENTOS: <span id="cancelaQtdElementos"></span>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col text-muted">
                                <div id="cancelaElementos"></div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col bg-light border rounded ml-3 mr-3 pb-1 font-weight-bold text-muted">
                                Clicando em "SIM", os ítens acima não vão ser cadastrados.<br>
                                <span class="text-danger">CONFIRMAR CANCELAMENTO ?</span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <button class="btn btn-light border text-muted" id="formCancelarCadastro"><i class='icon-minus-circled text-danger'></i> SIM</button>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-light border text-muted" data-dismiss="modal"><i class='icon-reply-1'></i> Voltar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="ModalRetornoCancela"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin: 1px">
            <div class="container theme-showcase" role="main">
                <div style="margin: 2px;" class="objeto">
                
                    <div id="pa_formulario1" style="display:none" class="card border mt-2 p-1">
                        <div class="card-header font-weight-bold">Novo cadastro de elemento <span class="d-none" id="spanid">| ID: </span><span class="badge badge-info" id="spanIdPai"></span></div>

                        <div class="card-body">
                            <div id="formProcuraSite" class="row mt-3 d-none">
                                <div class="col">
                                    <input class="form-control" placeholder="Procurar site..." type="text" id="formSigla">
                                </div>
                                <div class="col">
                                    <button class="btn btn-light border text-muted" id="btProcuraSite"><i class="icon-search-1"></i> Procurar</button>
                                </div>
                            </div>
                            <div id="formDadosSite" class='d-none'>
                                <div class="row mt-2">
                                    <div class="col alert-secondary rounded text-muted">
                                        <span class="font-weight-bold">SITE:</span> <span id="spanSigla"></span>
                                        - ID: <span class="badge badge-light" id="spanIdSite"></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col alert-secondary rounded text-muted">
                                        <span class="font-weight-bold">NOME SITE:</span> <span id="spanNome"></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col alert-secondary rounded text-muted">
                                        <span class="font-weight-bold">ENDEREÇO:</span> <span id="spanEndereco"></span>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <button class="btn btn-light border text-muted" id="formCriaSolicitacao"><i class="icon-plus-circle text-success"></i> Criar solicitação</button>
                                    </div>
                                </div>
                                <div id="formBotoes" class="row mt-3 d-none">
                                    <div class="col mt-1">
                                        <button class="btn btn-light border text-muted" value="0" id="formConcluiSolicitacao"><i class="icon-ok-circle text-success"></i> Concluir</button>
                                    </div>
                                    <div class="col mt-1">
                                        <button class="btn btn-light border text-muted" id="formNovoElemento"><i class="icon-doc-add text-info"></i> Novo Elemento</button>
                                    </div>
                                    <div class="col mt-1">
                                        <button class="btn btn-light border text-muted" id="formCancelaSolicitacao"><i class="icon-cancel-circle text-danger"></i> Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="listaSite" class="table-responsive"></div>
                        <div id="listaElemento"></div>
                        <div class="card-footer">
                            <div id="retornoNovoElemento"></div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-danger" role="alert" id="erro"></div>
                <div class="alert alert-success" role="alert" id="sucesso"></div>
            </div>
            <div style="display: none;" id="ListaItensAdd"></div>
            <div style="display: none;" id="SolicitacaoItens"></div>
        </div>
    </center>

</body>

</html>