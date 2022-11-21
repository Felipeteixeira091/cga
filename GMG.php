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

    <link type="text/css" rel="stylesheet" href="css/all.css<?php echo $versao; ?>" />

    <script src="js/js_login.js<?php echo $versao; ?>"></script>
    <script src="js/js_menu.js<?php echo $versao; ?>"></script>
    <script src="js/GMG.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/menu.css">

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">ADM</span></div>
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
        <div class="container theme-showcase" role="main">
            <div style="margin: 2px;" class="objeto">

                <div style="display: none" class="card mt-3 p-1" id="formulario1">
                    <div class="card-header font-weight-bold">Pesquisar GMG</div>
                    <div class="row mt-2">
                        <div class="col">
                            <input class="form-control" type="text" id="TXT" placeholder="PESQUISAR...">
                        </div>
                        <div class="col">
                            <select class="form-control" id="CN"></select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <button id="btFiltra" class="btn btn-light border"><i class="icon-filter"></i> FILTRAR</button>
                        </div>
                        <div class="col">
                            <button id="btFormNovo" class="btn btn-light border"><i class="icon-plus-circle"></i> NOVO</button>
                        </div>
                        <div class="col">
                            <button id="btFormTrans" class="btn btn-light border"><i class='icon-loop'></i> Tranferências</button>
                        </div>
                    </div>
                    <div class="card-footer mt-2">
                        <div id="retornoFiltro"></div>
                    </div>
                    <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_cartao" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ModalTitulo">Editar Cartão</h5>
                                </div>
                                <div class="modal-body m-1">
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1 align-middle rounded badge badge-pill badge-light text-secondary"><span id="cartaoAtualGMG"></span> - <span id="cartaoAtualID"></span></div>
                                    </div>
                                    <div class="row mt-1-sm mb-1">
                                        <div class="col-md mt-1 bg-light p-1 border border rounded">Cartão Atual: <span id="cartaoAtual"></span></div>
                                        <div class=" col-md ml-md-1 mt-1 p-1 bg-light border border rounded d-none"><button class="btn btn-sm btn-outline-danger" id="btCartaoRemove"><i class='icon-minus-circle'></i> Remover</button></div>
                                    </div>
                                    <div id="accordion" class="rounded border-left border-top border-right">
                                        <div id="cartaoDesbloqueio">
                                            <div class="card-header" id="headingUm">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-light btn-sm text-muted border" data-toggle="collapse" data-target="#collapseUm" aria-expanded="true" aria-controls="collapseUm">
                                                        <i class="icon-lock-open text-primary"></i> Solicitar Desbloqueio
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseUm" class="collapse" aria-labelledby="headingUm" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1">
                                                            <span class="badge badge-pill badge-light text-secondary"> Após a solicitação o coordenador administrativo irá realizar o desbloqueio.</span>
                                                        </div>
                                                        <div class="col-md mt-1 mb-1">
                                                            <button class="btn btn-light text-muted border" id="btCartaoDesbloqueio"><i class="icon-lock-open text-success"></i> Solicitar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-header" id="headingDois">
                                            <h5 class="mb-0">
                                                <button class="btn btn-light btn-sm text-muted border" data-toggle="collapse" data-target="#collapseDois" aria-expanded="true" aria-controls="collapseDois">
                                                    <i class="icon-exchange text-primary"></i> Alterar Cartão
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseDois" class="collapse" aria-labelledby="headingDois" data-parent="#accordion">
                                            <div class="card-body">
                                                <div class="row mt-1-sm">
                                                    <div class="col-md-4 mt-1">
                                                        <input class="form-control" placeholder="NOVO CARTÃO" maxlength="6" type="number" id="cartaoNovo">
                                                    </div>
                                                    <div class="col-md mt-1">
                                                        <input class="form-control" placeholder="MOTIVO DA TROCA" maxlength="32" type="text" id="cartaoMotivoTroca">
                                                    </div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1">
                                                        <button type="button" class="btn btn-outline-success" id="btCartaoAltera"><i class="icon-exchange"></i> Alterar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="cartaoFerramenta" class="d-none">
                                            <div class="card-header" id="headingTres">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-light btn-sm text-muted border" data-toggle="collapse" data-target="#collapseTres" aria-expanded="true" aria-controls="collapseTres">
                                                        <i class="icon-link text-primary"></i> Atribuir cartão já cadastrado
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseTres" class="collapse" aria-labelledby="headingTres" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1 p-1 align-middle bg-light border rounded">
                                                            <select class="form-control" id="cartaoNovoselect"></select>
                                                        </div>
                                                        <div class="col-md ml-md-1 mt-1 p-1 align-middle bg-light border border rounded">
                                                            <button class="btn btn-sm btn-outline-info" id="btCartaoAtribui"><i class="icon-link"></i> Atribuir</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>





                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="icon-cancel-circle-2"></i> Fechar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="col alert alert-ligth">
                                        <div id="ModalRetorno_cartao"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_novo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ModalTituloNovo">Novo GMG/Equipamento</h5></span>
                                </div>
                                <div class="modal-body">
                                    <div class="row mt-1-sm">
                                        <div class="col-md-2 mt-1">
                                            <select class="form-control" id="novoEstado"></select>
                                        </div>
                                        <div class="col-md mt-1">
                                            <select class="form-control" type="text" id="novoCN"></select>
                                        </div>
                                        <div class="col-md mt-1">
                                            <input class="form-control" placeholder="IDENTIFICAÇÃO" maxlength="64" id="novoIdentificacao">
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <select class="form-control" type="text" id="novoTipo"></select>
                                        </div>
                                        <div class="col-md mt-1">
                                            <input class="form-control" type="text" id="novoCartao" placeholder="CARTÃO(CONTROLE)" maxlength="6">
                                        </div>
                                        <div class="col-md mt-1">
                                            <select class="form-control" type="text" id="novoCoordenador"></select>
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <button class="btn btn-success" id="bt_cadastro_GMG">Cadastrar</button>
                                        </div>
                                        <div class="col-md mt-1">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
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
                    <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_edita" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ModalTitulo">Editar <span id="editaGMG"></span></h5><span class="badge badge-pill badge-light text-secondary float-right" id="editaCod"></span>
                                </div>
                                <div class="modal-body">
                                    <div class="row mt-1-sm">
                                        <div class="col-md-2 mt-1">
                                            <select class="form-control" id="editaEstado"></select>
                                        </div>
                                        <div class="col-md mt-1">
                                            <select class="form-control" type="text" id="editaCN"></select>
                                        </div>
                                        <div class="col-md mt-1">
                                            <input class="form-control" placeholder="IDENTIFICAÇÃO" maxlength="64" id="editaIdentificacao">
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <select class="form-control" type="text" id="editaTipo"></select>
                                        </div>
                                        <div class="col-md mt-1">
                                            <select class="form-control" type="text" id="editaAtivo"></select>
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <select class="form-control" type="text" id="editaCoordenador"></select>
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <button class="btn btn-success" id="bt_edita_GMG">Editar</button>
                                        </div>
                                        <div class="col-md mt-1">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="col alert alert-ligth">
                                        <div id="ModalRetorno_edita"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_transferencia" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ModalTitulo">GMGs/EQUIPAMENTOS RECEBIDOS <span class="badge badge-pill badge-light text-secondary" id="gmgNumeroTransferencia"></span></h5>
                                </div>
                                <div class="modal-body ml-2 mr-2">
                                    <div class="row mt-2-sm">
                                        <div class="col">
                                            <div id="gmgTransferenciaLista"></div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="col alert alert-ligth">
                                        <div id="ModalRetornoTransferencia"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="Lista" class="table-responsive mt-3"></div>
    </center>

</body>

</html>