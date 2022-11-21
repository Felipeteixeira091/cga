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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

    <link rel="stylesheet" href="css/all.css<?php echo $versao; ?>">
    <link rel="stylesheet" href="css/upload.css<?php echo $versao; ?>">
    <script src="js/js_menu.js<?php echo $versao; ?>"></script>

    <script src="js/SBO_BO.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">SBO</span></div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
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
            <div id="formulario1" style="display:none" class="card border mt-2 p-1">
                <div class="card-header fw-bold">Boletins de ocorrência</div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col">
                            <select class="form-control" id="bo_cn">
                                <option>Carregando...</option>
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-control" id="bo_site">
                                <option>Carregando...</option>
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-control" id="bo_status">
                                <option>Carregando...</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <span>DATA INICIAL</span>
                            <input type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control" id="bo_DataInicio">
                        </div>
                        <div class="col">
                            <span>DATA FINAL</span>
                            <input type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control" id="bo_DataFIm">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <button id="btFiltra" class="btn btn-primary"><i class="bi bi-filter"></i> Filtrar</button>
                        </div>
                        <div class="col" id="DivXls" style="display:none">
                            <button class="btn btn-primary" id="btXls"><i class="bi bi-filetype-xls"></i> Exportar</button>
                        </div>
                        <div class="col">
                            <button id="btFormNovo" class="btn btn-primary"><i class="bi bi-file-earmark-plus"></i> Novo BO</button>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div id="retornoFiltra"></div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="formulario_novo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" role="document">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo"><i class="icon-doc-new text-muted"></i> Novo boletim de ocorrência</h5>
                        </div>
                        <div class="modal-body ml-2 mr-2">
                            <div id="accordion" class="rounded border-left border-top border-right">

                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-light btn-block text-muted border" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <i class="icon-location text-primary"></i> Site + TA
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-bs-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row mt-1-sm">
                                            <div class="col-md mt-1">
                                                <button class="btn btn-light text-muted border" id="btModalSite"><i class="icon-search-1"></i> Selecionar site</button>
                                            </div>
                                            <div class="col-md mt-1 mb-1 bg bg-light border rounded">
                                                <span id="textoSite">SELECIONE UM SITE</span> <span id="ac_site" class="badge badge-pill badge-light text-secondary"></span>
                                            </div>
                                            <div class="col-md mt-1">
                                                <input type="number" id="ta" maxlength="9" class="form-control alert-danger" placeholder="N° TA">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="btn btn-light btn-block text-muted border  mt-1" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            <i class="icon-police text-primary"></i> Dados BO
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row pt-3">
                                            <div class="col">
                                                <span class="badge badge-dark m-1">Data/Hora do ocorrido</span>
                                                <input type="datetime-local" class="form-control" id="dhOc">
                                            </div>
                                        </div>
                                        <div class="row pt-3">
                                            <div class="col-md mt-1">
                                                <input type="number" id="os" maxlength="9" class="form-control alert-danger" placeholder="N° OS">
                                            </div>
                                            <div class="col-md mt-1">
                                                <input type="number" class="form-control" id="preSinistro" placeholder="Número de sinistro">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-header" id="headingTre">
                                    <h5 class="mb-0">
                                        <button class="btn btn-light btn-block text-muted border  mt-1" data-bs-toggle="collapse" data-bs-target="#collapseTre" aria-expanded="true" aria-controls="collapseTre">
                                            <i class="icon-signal-1 text-danger"></i> Indisponibilidade
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseTre" class="collapse" aria-labelledby="headingTre" data-bs-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row pt-1 pb-1">
                                            <div class="col pt-1">
                                                <span class="badge badge-dark m-1">Início</span>
                                                <input type="datetime-local" class="form-control pt-1" id="inicio">
                                            </div>
                                            <div class="col">
                                                <span class="badge badge-dark m-1">Fim</span>
                                                <input type="datetime-local" class="form-control pt-1" id="final">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row pt-1">
                                            <div class="col">
                                                <input type="number" class="form-control" placeholder="Municipios afetados" id="qtd_municipio">
                                            </div>
                                            <div class="col">
                                                <input type="number" class="form-control" placeholder="Elementos afetados" id="qtd_elemento">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-header" id="headingfour">
                                    <h5 class="mb-0">
                                        <button class="btn btn-light btn-block text-muted border  mt-1" data-bs-toggle="collapse" data-bs-target="#collapsefour" aria-expanded="true" aria-controls="collapsefour">
                                            <i class="icon-key-outline text-primary"></i><i class="icon-flash-1 text-success"></i> Fechadura e Bateria
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapsefour" class="collapse" aria-labelledby="headingfour" data-bs-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row mt-2 pt-1 pb-1 alert rounded">
                                            <div class="col">

                                                <select class="form-control" id="fechadura_bluetooth">
                                                    <option value="ND">FECHADURA BLUETOOTH</option>
                                                    <option value="1">SIM</option>
                                                    <option value="2">NÃO</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <select class="form-control" id="fechadura_bluetooth_status">
                                                    <option>Carregando...</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row pt-1 pb-1 alert rounded">
                                            <div class="col">
                                                <select class="form-control" id="modulo_box">
                                                    <option value="ND">BATERIA RESINADA</option>
                                                    <option value="1">SIM</option>
                                                    <option value="2">NÃO</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <select class="form-control" id="bateria">
                                                    <option value="ND">BATERIA ION-LITÍO</option>
                                                    <option value="1">SIM</option>
                                                    <option value="2">NÃO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-header" id="headingfive">
                                    <h5 class="mb-0">
                                        <button class="btn btn-light btn-block text-muted border mt-1" data-bs-toggle="collapse" data-bs-target="#collapsefive" aria-expanded="true" aria-controls="collapsefive">
                                            <i class="icon-pencil-alt-1 text-primary"></i> Campos digitáveis
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapsefive" class="collapse border" aria-labelledby="headingfive" data-bs-parent="#accordion">
                                    <div class="card-body">
                                        <div class="row pt-1">
                                            <div class="col">
                                                <textarea class="form-control" placeholder="Itens furtados" id="furtado" style="resize: none;"></textarea>
                                            </div>
                                        </div>
                                        <div class="row pt-1">
                                            <div class="col">
                                                <textarea class="form-control" placeholder="Itens vandalizados" id="vandalizado" style="resize: none;"></textarea>
                                            </div>
                                        </div>
                                        <div class="row pt-1">
                                            <div class="col">
                                                <textarea class="form-control" placeholder="Sobras de material vandalizado" id="sobra" style="resize: none;"></textarea>
                                            </div>
                                        </div>
                                        <div class="row pt-1">
                                            <div class="col">
                                                <textarea class="form-control" placeholder="Observações..." id="relato" style="resize: none;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row pt-3">
                                <div class="col">
                                    <button class="btn btn-light text-muted border" id="btnCadastro"><i class="icon-plus-circle text-success"></i> Cadastrar</button>
                                </div>
                                <div class="col">
                                    <button type="button" id="btnCadastro_voltar" class="btn btn-light text-muted border" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="ModalRetornoCadastro"></div>
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
                                    <button class="btn btn-light border" id="btProcuraSite"><i class="icon-search-1 text-info"></i> Procurar</button>
                                </div>
                                <div class="col-md mt-1">
                                    <button type="button" class="btn btn-light border text-muted" data-bs-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
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
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="DetalheBO" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">BOLETIM Nº: <span id="Detalhe_id"></span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#mais" role="tab" aria-controls="mais" aria-selected="false"><i class="bi bi-info-circle"></i> + INFORMAÇÕES</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#confirma" role="tab" aria-controls="confirma" aria-selected="false"><i class="bi bi-check-circle"></i> CONFIRMA</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#cancela" role="tab" aria-controls="cancela" aria-selected="false"><i class="bi bi-x-circle"></i> Cancelamento</a>
                            </li>
                        </ul>
                        <div class="modal-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="accordion" id="accordionExample">
                                        <div class="card border-0">
                                            <div class="card-header border" id="heading1">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-light btn-sm fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                                        Detalhes
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapse1" class="collapse show" aria-labelledby="heading1" data-bs-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col bg bg-dark rounded p-1 fw-bold text-white">SITE: <span id="Detalhe_site" class="text-muted"></span></div>
                                                    </div>
                                                    <div class="row mt-1 ml-1 mr-1">
                                                        <div class="col bg bg-light rounded border">
                                                            Registro: <span id="Detalhe_nome" class="text-muted"></span> <span id="Detalhe_re" class="text-muted"></span>
                                                        </div>
                                                        <div class="col bg bg-light rounded border ms-1">
                                                            Coordenador: <span id="Detalhe_c_nome" class="text-muted"></span> <span id="Detalhe_c_re" class="text-muted"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1 ml-1 mr-1">
                                                        <div class="col bg bg-light rounded border">Data/Hora registro: <span id="Detalhe_registro" class="text-muted"></span></div>
                                                        <div class="col bg bg-light rounded border ms-1">Data/Hora ocorrido: <span id="Detalhe_ocorrido" class="text-muted"></span></div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col bg bg-dark rounded p-1 fw-bold text-white"><i class="bi bi-pin-map-fill"></i> LOCALIZAÇÃO</div>
                                                    </div>
                                                    <div class="row mt-1 ml-1 mr-1">
                                                        <div class="col bg bg-light rounded border">CIDADE: <span id="Detalhe_cidade" class="text-muted"></span></div>
                                                        <div class="col bg bg-light rounded border ms-1">BAIRRO: <span id="Detalhe_bairro" class="text-muted"></span></div>
                                                    </div>
                                                    <div class="row mt-1 ml-1 mr-1">
                                                        <div class="col bg bg-light rounded border">ENDEREÇO: <span id="Detalhe_endereco" class="text-muted"></span></div>
                                                        <div class="col bg bg-light rounded border ms-1">CEP: <span id="Detalhe_cep" class="text-muted"></span></div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col bg bg-dark rounded p-1 fw-bold text-white"><i class="bi bi-info-circle"></i> INFORMAÇÕES</div>
                                                    </div>
                                                    <div class="row mt-1 ml-1 mr-1">
                                                        <div class="col bg bg-light rounded border">INDISPONIBILIDADE: <span id="Detalhe_indisponibilidade" class="text-muted"></span></div>
                                                    </div>
                                                    <div class="row mt-1 ml-1 mr-1">
                                                        <div class="col bg bg-light rounded border">MUNICÍPIOS AFETADOS: <span id="Detalhe_indisponibilidade_mun" class="text-muted"></span></div>
                                                        <div class="col bg bg-light rounded border ms-1">ELEMENTOS AFETADOS: <span id="Detalhe_indisponibilidade_ele" class="text-muted"></span></div>
                                                    </div>
                                                    <div class="row mt-1 ml-1 mr-1">
                                                        <div class="col bg bg-light rounded border">FECHADURA BLUETHOOTH: <span id="Detalhe_bluethooth" class="text-muted"></span></div>
                                                        <div class="col bg bg-light rounded border ms-1">SITUAÇÃO: <span id="Detalhe_bluethooth_situacao" class="text-muted"></span></div>
                                                    </div>
                                                    <div class="row mt-1 ml-1 mr-1">
                                                        <div class="col bg bg-light rounded border">BATERIA RESINADA (POLÍMERO): <span id="Detalhe_modulobox" class="text-muted"></span></div>
                                                        <div class="col bg bg-light rounded border ms-1">BATERIA ION-LITÍO: <span id="Detalhe_baterialitio" class="text-muted"></span></div>
                                                    </div>
                                                    <div class="row mt-1 ms-1 me-1 border p-1 rounded">
                                                        <div class="col">
                                                            <input class="form-control alert-danger" type="text" id="bo_os" placeholder="Nº da OS">
                                                        </div>
                                                        <div class="col">
                                                            <input class="form-control" type="text" id="bo_sinistro" placeholder="Nº do sinistro">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1 ms-1 me-1">
                                                        <div class="col bg bg-light rounded border">
                                                            Número BO: <span id="Detalhe_bo"></span>
                                                        </div>
                                                        <div class="col bg bg-light rounded border ms-1">
                                                            Número sinistro: <span id="Detalhe_sinistro" class="text-muted"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1 ms-1 mr-1">
                                                        <div class="col bg bg-light rounded border text-center">
                                                            Número TA: <span id="Detalhe_ta" class="text-muted"></span>
                                                        </div>
                                                        <div class="col bg bg-white rounded border text-center ms-1 pt-1 pb-1">
                                                            <b>Número OS: </b> <span id="Detalhe_os" class="text-muted"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1 ms-1 mr-1">
                                                        <div class="col bg bg-white rounded border text-center pt-1 pb-1">
                                                            <button type="button" class="btn btn-light btn-sm border" id="bt_bt4"><i class="icon-download-2 text-info"></i> Download BO</button>
                                                            <span class="badge badge-light" id="anexoPdf"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1 ms-1 me-1">
                                                        <div class="col bg bg-light rounded border text-center">
                                                            STATUS ATUAL: <span id="Detalhe_status" class="text-muted"></span> <span id="Detalhe_status_dh" class="text-muted"></span><span class="d-none" id="Detalhe_status_id"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card border-0 mt-1">
                                            <div class="card-header border rounded" id="headingTwo">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-light btn-sm collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                                        <i class="bi bi-clock-history"></i> Histórico
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapse2" class="collapse" aria-labelledby="heading2" data-bs-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="col" id="Historico_BO"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="mais" role="tabpanel" aria-labelledby="mais-tab">
                                    <div class="row mt-1 ml-1 mr-1">
                                        <div class="col bg bg-light border text-muted p-1 rounded"><i class="icon-comment-inv-alt2 text-muted"></i> FURTADO: <span id="Detalhe_furtado"></span></div>
                                        <button type="button" class="bt_editaOBS btn btn-sm btn-primary ml-1" value="furtado"><i class="icon-edit"></i> Editar</button>
                                    </div>
                                    <div class="row mt-1 ml-1 mr-1">
                                        <div class="col bg bg-light border text-muted p-1 rounded"><i class="icon-comment-inv-alt2 text-muted"></i> VANDALIZADO: <span id="Detalhe_vandalizado"></span></div>
                                        <button type="button" class="bt_editaOBS btn btn-sm btn-primary ml-1" value="vandalizado"><i class="icon-edit"></i> Editar</button>
                                    </div>
                                    <div class="row mt-1 ml-1 mr-1">
                                        <div class="col bg bg-light border text-muted p-1 rounded"><i class="icon-comment-inv-alt2 text-muted"></i> SOBRAS: <span id="Detalhe_sobra"></span></div>
                                        <button type="button" class="bt_editaOBS btn btn-sm btn-primary ml-1" value="sobra"><i class="icon-edit"></i> Editar</button>
                                    </div>
                                    <div class="row mt-1 ml-1 mr-1">
                                        <div class="col bg bg-light border text-danger p-1 rounded"><i class="icon-comment-inv-alt2 text-muted"></i> RELATO: <span id="Detalhe_relato"></span></div>
                                        <button type="button" class="bt_editaOBS btn btn-sm btn-primary ml-1" value="relato"><i class="icon-edit"></i> Editar</button>
                                    </div>
                                    <div class="row mt-1 ml-1 mr-1">
                                        <div class="col">
                                            <button type="button" class="btn btn btn-sm btn-primary ml-1" id="bt_confirma"><i class="icon-check text-success"></i> Confirmar informações</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="confirma" role="tabpanel" aria-labelledby="Opções">
                                    <div class="row">
                                        <div id="col_bt" class="col text-center">
                                            <button type="button" class="btOPC btn btn-sm btn-primary" id="bt_bt1"><i class="bi bi-play-circle"></i> Iniciar tratativa</button>
                                            <button type="button" class="btOPC btn btn-sm btn-primary" id="bt_bt2"><i class="bi bi-file-earmark-plus"></i> Carregar boletim</button>
                                            <button type="button" class="btOPC btn btn-sm btn-primary" id="bt_bt3"><i class="bi bi-check-circle"></i> Concluir</button>
                                            <button type="button" class="btOPC btn btn-sm btn-primary" id="bt_bt6"><i class="bi bi-check-circle"></i> Fila prisma</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="cancela" role="tabpanel" aria-labelledby="Opções">
                                    <div id="Detalhe_txt_cancelamento" class="col alert alert-danger ml-1">
                                    </div>
                                    <div class="row mt-1 ml-1 mr-1" id="row_cancelamento">
                                        <div class="col text-center pt-1 pb-1">
                                            <div class="form-floating">
                                                <textarea class="form-control" placeholder="Observação de cancelamento" id="obsCancelamento" style="resize: none"></textarea>
                                                <label for="obsCancelamento">Observação de cancelamento</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-1 ml-1 mr-1" id="row_cancelamento">
                                        <div class="col text-center ml-1">
                                            <button type="button" class="btn btn-primary" id="bt_bt5"><i class="bi bi-x-circle"></i> Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-2">
                                <div class="col">
                                    <button type="button" id="bt_formVolta" class="float-end btn btn-light text-muted border" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="retornoDetalhe"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="form_editaObs" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar campo </span><span id="tituloCampo"></span></h5>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-1-sm">
                                <div class="col bg bg-white rounded ml-1 mb-2">
                                    <textarea class="form-control" placeholder="..." id="obsEdita" style="resize: none"></textarea>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col">
                                    <button type="button" id="bt_obsEdita" value="nd" class="btn btn-light text-muted border"><i class='icon-edit'></i> Edita</button>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-light text-muted border" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer>">
                            <div class="col alert alert-ligth">
                                <div id="retornoEdita"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="form_upload" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo">Carregar Arquivo PDF</span></h5>
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
                                            <input class="btn btn-light border" type="text" name="bo" placeholder="Nº BO" required>
                                        </div>
                                        <div class="col bg bg-white">
                                            <div class="file-field">
                                                <button class="btn btn-light border" type="submit"><i class="icon-upload-1 text-info"></i> Enviar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-1 ml-1 mr-1">
                                        <div class="col">
                                            <div class="spinner-grow spinner-grow-sm text-primary  d-none" id="spinnerUpload" role="status"></div>
                                        </div>
                                        <div class="col ml-1">
                                            <div id="progressBar" class="bg bg-primary bg-gradient text-white rounded shadow"><span></span></div>
                                        </div>
                                    </div>
                                </form>
                                <script async src="js/SBO_UPLOAD.js<?php echo $versao; ?>"></script>
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
        </div>
        <div style="display: none;" id="ListaBO"></div>
    </center>
</body>

</html>