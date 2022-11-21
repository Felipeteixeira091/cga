<?php
include_once "sc/l_sessao.php";
include "versao.php";
?>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo $sistema ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <link rel="icon" href="css/ico.png">
    <link rel="stylesheet" href="css/all.css<?php echo $versao; ?>">

    <script src="js/js_menu.js<?php echo $versao; ?>"></script>
    <script src="js/SMA_PENDENTE.js<?php echo $versao; ?>"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">SMA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">
                        <a class="nav-link active" aria-current="page" href="Index"><i class="bi bi-house-fill text-info"></i> Início</a>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <div id="menuCanva"></div>
                        <li class="nav-item mt-3">
                            <a class="nav-link" id="btLogOut" href="#"><i class="bi bi-box-arrow-right text-danger"></i> Sair</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <center>
        <div class="container-fluid" style="margin-top: 70" role="main">
            <div style="margin: 10px;" class="objeto">
                <div id="pa_formulario1" style="display:none" class="card border mt-2">
                    <div class="card-header fw-bold">Solicitações</div>
                    <div class="card-body">
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <input type="date" class="form-control" id="SolicitacaoData1" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md mt-1">
                                <input type="date" class="form-control" id="SolicitacaoData2" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <input class="form-control" type="text" id="SolicitacaoTXT" placeholder="PESQUISAR SOLICITAÇÃO...">
                            </div>
                            <div class="col-md mt-1">
                                <select class="form-control" id="SolicitacaoStatus"></select>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <button id="btFiltra" class="btn btn-light border"><i class="bi bi-search"></i> Filtrar</button>
                            </div>
                            <div class="col-md mt-1">
                                <button type="button" class="btn btn-light border text-muted" id="bt_xls" disabled><i class="bi bi-filetype-xls text-success"></i> Exportar para .XLS</button>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <span id="spanXls"></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="retornoFiltro"></div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="modal_historico" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                Histórico da solicitação <span class="badge badge-light border ml-2"></span>
                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-1-sm">
                                <div class="col">
                                    <div id="historico"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer>">
                            <div class="col alert alert-ligth">
                                <button type="button" id="btVolta" class="btn btn-light text-muted border" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="DetalheSolicitacao" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo">Solicitação de Material Web - <span id="NumeroSolicitacao"></span> | Sobressalente: <span id="Sobressalente"></span></h5>
                        </div>
                        <div class="modal-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-bs-controls="collapseOne">
                                                        <i class="bi bi-clipboard-data"></i> Dados da solicitação
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row mt-2 mb-1">
                                                        <div class="col fw-bold rounded border bg bg-ligth">Solicitante</div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col fw-bold rounded border bg bg-ligth">Solicitante SMA: <span id="detalheSolicitanteNome"></span> - <span id="detalheSolicitanteRe"></span></div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col rounded  border bg bg-ligth">Retirada Almox: <span id="detalheNome"></span> - <span id="detalheRE"></span></div>
                                                        <div class="col ms-1 rounded border bg bg-ligth">Coordenador: <span id="detalheCoordenadorNome"></span> - <span id="detalheCoordenadorRE"></span></div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col rounded border bg bg-ligth">PRAZO: <span id="detalhePrazo"></span></div>
                                                        <div class="col ms-1 rounded border bg bg-ligth">NÚMERO TA/TP/OS: <span id="detalheOs"></span></div>
                                                        <div class="col ms-1 rounded border bg bg-ligth">SIGLA SITE: <span id="detalheSigla"></span></div>
                                                    </div>
                                                    <div class="row mt-1 mb-1">
                                                        <div class="col fw-bold rounded border bg bg-ligth">STATUS ATUAL: <span id="detalheStatus"></span> <span id="detalheAprovacao"></span></div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col rounded border bg bg-ligth">FATURA: <span id="detalheFatura"></span></div>
                                                        <div class="col ms-1 rounded border bg bg-ligth">TIPO: <span id="detalheTipo"></span></div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col rounded fw-bold border bg bg-ligth"><i class="icon-newspaper"></i> Relatório de conclusão: <div id="btnConclusao"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1-sm">
                                                        <div class="col">
                                                            <table class="table table-striped table-sm border rounded-bottom mt-2">
                                                                <thead class="thead text-center">
                                                                    <th scope='col'>ID</th>
                                                                    <th scope='col'>PA</th>
                                                                    <th scope='col'><i class="icon-cart"></i> ITEM</th>
                                                                    <th scope='col'>UNIDADE</th>
                                                                    <th scope='col'>QUANTIDADE</th>
                                                                </thead>
                                                                <tbody id="detalheLista"></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div id="divObs" class="col rounded alert alert-secondary text-sm-left text-muted">OBSERVAÇÕES: <span id="detalheObs"></span></div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col rounded alert-secondary">ENDEREÇO: <span id="detalheEndereco"></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mt-1">
                                            <div class="card-header" id="headingThree">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-bs-controls="collapseThree">
                                                        <i class="bi bi-recycle"></i> SGA</a>
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1">
                                                            <div id="Estoque" class="text-monospace"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mt-1">
                                            <div class="card-header" id="headingFour">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-bs-controls="collapseFour">
                                                        <i class="bi bi-fire text-info text-gradient"></i> GÁS</a>
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1">
                                                            <div id="detalheBagagem" class="text-monospace"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mt-1" id="collapsed_5">
                                            <div class="card-header" id="headingFive">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-bs-controls="collapseFive">
                                                        <i class="icon-archive-1"></i> Relatório de conclusão</a>
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <form id="formFiles" name="formFiles" action="javascript:void(0);" enctype="multipart/form-data">
                                                        <div class="row">
                                                            <div class="col">
                                                                <span class="text-white" id="tipoUpload">2</span><span class="badge badge-light">Solicitação: <span id="itemId"></span></span>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-1 ml-1 mr-1">
                                                            <div class="col bg bg-white rounded">
                                                                <div class="file-field">
                                                                    <input class="btn btn-light border" type="file" name="file" required>
                                                                </div>
                                                            </div>
                                                            <div class="col bg bg-white rounded ml-1">
                                                                <input class="btn btn-light border" type="text" name="itemPA" placeholder="PA" value="conclusao" id="itemPA" required disabled>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col alert-light rounded border">
                                                                <textarea class="form-control mt-1 mb-1 border border-light" placeholder="Observação..." id="obsUpload" style="resize: none"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-1 ml-1 mr-1">
                                                            <div class="col bg bg-white">
                                                                <div class="file-field">
                                                                    <button class="btn btn-light border" type="submit"><i class="icon-upload-1 text-info"></i> Enviar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-1 ml-1 mr-1">
                                                            <div class="col bg bg-white">
                                                                <div id="return"></div>
                                                            </div>
                                                            <div class="col bg bg-white ml-1">
                                                                <div class="spq spinner-border spinner-border-sm text-danger d-none" role="status"></div>
                                                                <div class="spq spinner-grow spinner-grow-sm text-warning d-none" role="status"></div>
                                                                <div id="progressBar" class="alert alert-light text-danger"><span></span></div>
                                                            </div>
                                                        </div>
                                                        <div class="col alert alert-ligth">
                                                            <div id="retornoUpload"></div>
                                                        </div>
                                                    </form>
                                                    <script async src="js/SMA_UPLOAD.js<?php echo $versao; ?>"></script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col alert-light rounded border">
                                            <textarea class="form-control mt-1 mb-1 border border-light" placeholder="Observação..." id="obs2" style="resize: none"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2" id="rowConclusao">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            Relatório de conclusão
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="options" id="exampleRadios1" value="1" checked>
                                                <label class="form-check-label float-start" for="exampleRadios1">
                                                    Relatório de conclusão necessário
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="options" id="exampleRadios2" value="2">
                                                <label class="form-check-label float-start" for="exampleRadios2">
                                                    Relatório de conclusão <b>NÃO</b> necessário
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <button class="btn btn-light text-muted border" id="formAprova"><i class='icon-ok-circled text-success'></i> Aprovar</button>
                                </div>
                                <div class="col">
                                    <button class="btn btn-light text-muted border" id="formReprova"><i class='icon-minus-circled text-danger'></i> Reprovar</button>
                                </div>
                                <div class="col">
                                    <button class="btn btn-light text-muted border" id="formAprova_r"><i class='icon-doc-text text-success'></i> Aprovar relatório</button>
                                </div>
                                <div class="col">
                                    <button class="btn btn-light text-muted border" id="formReprova_r"><i class='icon-doc-text text-danger'></i> Reprovar relatório</button>
                                </div>
                                <div class="col">
                                    <button class="btn btn-light text-muted border" id="formEdita"><i class='icon-edit text-info'></i> Editar</button>
                                </div>
                                <div class="col">
                                    <button type="button" id="btVolta" class="btn btn-light text-muted border" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
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
            <div style="display: none;" id="ListaSolicitacao" class="table-responsive-sm rounded"></div>
        </div>
    </center>

</body>

</html>