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
    <script src="js/SGA_SALDO.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

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
        <?php
        ?>
        <div style="margin: 1px">
            <div class="container theme-showcase" role="main">
                <div class="objeto m-2">
                    <div id="pa_formulario1" class="card border mt-2">
                        <div class="card-header font-weight-bold">Saldo</div>
                        <div class="card-body">
                            <div class="card-body">
                                <div class="row mt-3">
                                    <div class="col">
                                        <button class="btn btn-primary border" id="btSaldo"><i class="bi bi-wallet2"></i> Meu Saldo</button>
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-primary border" id="btModalBaixa"><i class="bi bi-recycle"></i> Informar descarte</button>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <div id="listaSaldo"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div id="retornoNova"></div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" data-keyboard="false" data-backdrop="static" id="DetalheEstoque" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalTitulo">Saldo SGA <span id="tituloEstoque"></span></h5>
                            </div>
                            <div class="modal-body m-1">
                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <h2 class="mb-0">
                                                <button type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="btn btn-primary btn-sm"><i class="bi bi-bag-fill"></i> Saldo geral</button>
                                            </h2>
                                        </div>

                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div id="Estoque"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <h2 class="mb-0">
                                                <button type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" class="btn btn-primary btn-sm"><i class="bi bi-shop"></i> Itens retirados no SMA</button>
                                            </h2>
                                        </div>
                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div id="EstoqueSMA"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingThree">
                                            <h2 class="mb-0">
                                                <button type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="btn btn-primary btn-sm"><i class="bi bi-recycle"></i> Itens baixados no SGA</button>
                                            </h2>
                                        </div>
                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div id="EstoqueSGA"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col">
                                        <button class="btn btn-light border" data-dismiss="modal"><i class=" icon-reply-1"></i> Voltar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <div id=""></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="modalBaixa" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalTitulo"><i class="icon-down text-muted"></i> Descarte SGA </h5><span class="badge badge-pill" id="id_sga"></span>
                            </div>
                            <div class="modal-body">
                                <div class="border rounded pb-2">
                                    <div class="bg bg-dark m-1 text-white rounded-top">
                                        Dados da solicitação
                                    </div>
                                    <div class="row mt-1-sm ml-2 mr-2 mt-1 mb-2 p-1" id="rowSite">
                                        <div class="col-md mt-1">
                                            <button class="btn btn-light text-muted border" id="btModalSite"><i class="icon-search-1"></i> Selecionar site</button>
                                        </div>
                                        <div class="col-md mt-1">
                                            <span class="font-weight-bold" id="textoSite"></span> <span class="badge badge-pill badge-light text-secondary" id="vfbSite"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md ml-1 mt-1 mr-1">
                                            <select class="form-control" id="atividade">
                                                <option value=0>Tipo de atividade</option>
                                                <option value=1>Corretiva</option>
                                                <option value=2>Preventiva</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 mt-1 mr-1 ml-1">
                                            <input type="number" id="os" class="form-control" placeholder="NÚMERO OS">
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1 mr-1 ml-1">
                                            <button class="btn btn-light btn-sm text-muted border" id="btCria"><i class="icon-doc-add text-info"></i> Criar solicitação</button>
                                            <button class="btn btn-light btn-sm text-muted border" id="btCancela"><i class="icon-block-3 text-danger"></i> Cancelar solicitação</button>
                                        </div>
                                    </div>
                                </div>
                                <div id="sga_itens" class="border rounded mt-1 mb-1">
                                    <div class="bg bg-dark text-white rounded-top m-1">
                                        Ítens da solicitação
                                    </div>
                                    <div class="row mt-1-sm ml-2 mr-2 mt-1 mb-2 p-1">
                                        <div class="col-md ml-1 mt-1">
                                            <select class="form-control" id="tipoDescarte"></select>
                                        </div>
                                        <div class="col-md mt-1">
                                            <input type="number" id="qtdBaixa" class="form-control" placeholder="QTD">
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div id="detalheAlerta"></div>
                                    </div>
                                    <div class="row mt-1-sm mb-2">
                                        <div class="col-md mt-1">
                                            <button class="btn btn-light btn-sm text-muted border" id="btAdd"><i class="icon-edit text-info"></i> Adicionar/Alterar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <button class="btn btn-light text-muted border" id="btConclui"><i class="icon-ok-circled-1 text-success"></i> Concluir</button>
                                    </div>
                                    <div class="col-md mt-1">
                                        <button type="button" class="btn btn-light text-muted border" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="col alert alert-ligth">
                                    <div id="retornoSolicitacao"></div>
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
                                <div class="col alert alert-ligth">
                                    <div id="retornoSite"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </center>
</body>

</html>