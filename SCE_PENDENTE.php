<?php
include_once "sc/l_sessao.php";
include "versao.php";
?>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo $sistema; ?></title>
    <link rel="icon" href="css/gas.png">

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
    <link rel="stylesheet" href="css/menus.css<?php echo $versao; ?>">
    <link rel="stylesheet" href="css/login.css<?php echo $versao; ?>">
    <script src="js/js_menu.js<?php echo $versao; ?>"></script>
    <script src="js/SCE_PENDENTE.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">
    <script>
        $(function() {
            $(".button-collapse").sideNav();
        });

        function somenteNumeros(num) {
            var er = /[^0-9.]/;
            er.lastIndex = 0;
            var campo = num;
            if (er.test(campo.value)) {
                campo.value = "";
            }
        }
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">SCE</span></div>
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
        <div class="modal hide fade in" data-keyboard="false" data-backdrop="static" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Buscando informações</h5>
                    </div>
                    <div class="modal-body">
                        <div id="ModalLoad" style="display:none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p>
                                <strong>Aguarde...</strong>
                        </div>
                        <div id="ModalSucess" style="display:none">
                            <div class="spinner-grow text-success" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p>
                                <strong>Concluído...</strong>
                        </div>
                    </div>
                    <div class="modal-footer" id="ModalFechar" style="display:none">
                        <button type="button" id="ModalFecharBt" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal hide fade in" data-keyboard="false" data-backdrop="static" id="solicitacao_detalhes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="card">
                        <div class="card-header font-weight-bold">Solicitação: <span id="solId"></span> <span id="dh" class="badge badge-light border"></span></div>
                        <div class="card-body">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 align-middle bg-light border border rounded">Coordenador: <span id="solCoordenador"></span></div>
                                <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded">Cartão: <span id="solCartao"></span></div>
                            </div>
                            <div class="divVeiculo row mt-1-sm">
                                <div class="col-md mt-1 align-middle bg-light border border rounded">Colaborador: <span id="solColaborador"></span></div>
                                <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded">Veículo: <span id="solVeiculo"></span></div>
                            </div>
                            <div class="divVeiculo row mt-1-sm">
                                <div class="col-md mt-1 align-middle bg-light border border rounded">KM Anterior: <span id="kmAnterior"></span></div>
                                <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded">KM Atual: <span id="kmAtual"></span></div>
                                <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded">Diferença KM: <span id="kmDif"></span></div>
                            </div>
                            <div class="divGmg row mt-1-sm">
                                <div class="col-md mt-1 align-middle bg-light border border rounded">GMG: <span id="solGmg"></span></div>
                                <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded">TIPO: <span id="solTipo"></span></div>
                            </div>
                            <div class="divGmg row mt-1-sm">
                                <div class="col-md mt-1 align-middle font-weight-bold alert-warning border border rounded">Tempo acoplado nos últimos 30 dias: <span id="solTempoAC"></span></div>
                            </div>
                            <div id="divVeiculo2" class="row mt-1-sm">
                                <div class="col-md mt-1 align-middle bg-light border border rounded">Valor solicitado: <span id="valorSolicitado"></span></div>
                                <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded">Solicitado no mês: <span id="valorMes"></span></div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 align-middle bg-light border border rounded">Status Atual: <span id="statusAtual"></span></div>

                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 align-middle bg-danger text-white font-weight-bold rounded">
                                    Ultima Solicitação: <span id="solUltima"></span>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 align-middle bg-light border text-danger font-weight-bold rounded">
                                    Observações: <span id="solObs"></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 align-middle rounded">
                                    <textarea id="obsInsert" class="form-control" rows="2" cols="50" placeholder="Observações" style="resize: none;"></textarea>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col mt-1 align-middle">
                                    <div id="sce_form_anexo"></div>
                                </div>
                            </div>
                            <div class="row mt-1-sm d-none">
                                <div class="col mt-1 align-middle">
                                    <input class="form-control" id="sce_aprov_valor" onkeyup="somenteNumeros(this);" placeholder="Valor da solicitação">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col mt-1 align-middle">
                                    <button id="btHistorico" class="btn btn-outline-primary btn-sm" type="button" data-toggle="collapse" data-target="#collapseHs" aria-expanded="false" aria-controls="collapseExample">
                                        <i class="icon-calendar-1"></i> Histórico
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="collapse mt-1" id="collapseHs">
                                        <div class="card">
                                            <div class="card-header">Histórico</div>
                                            <div class="card-body">
                                                <div id="historico" class="text-monospace"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col mt-1 align-middle">
                                    <button class="btn btn-outline-success m-1 btn-sm" disabled id="btSolAprova"><i class="icon-ok-circle"></i> Aprovar</button>
                                </div>
                                <div class="col mt-1 ml-md-1 align-middle">
                                    <button class="btn btn-outline-danger m-1 btn-sm" disabled id="btSolNega"><i class="icon-cancel-circle"></i> Negar</button>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col mt-1 align-middle">
                                    <button type="button" class="btn btn-outline-secondary m-1 btn-sm" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div id="retornoAprovacao"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container theme-showcase" role="main">
            <div class="card border mt-2 p-1" id="solicitacao_filtro">
                <div class="card-header font-weight-bold">Solicitações</div>
                <div class="card-body">
                    <div class="row mt-1-sm">
                        <div class="col-md mt-1 align-middle">
                            <select class="custom-select mr-sm-2" id="filtro_coordenador"></select>
                        </div>
                        <div class="col mt-1 ml-md-1 align-middle">
                            <select class="custom-select mr-sm-2" id="solicitacao_filtro_status"></select>
                        </div>
                    </div>
                    <div class="row mt-1-sm">
                        <div class="col-md mt-1 align-middle">
                            <span>Data inicial</span>
                            <input class="custom-select mr-sm-2" id="solicitacao_filtro_data1" value="<?php echo date('Y-m-d'); ?>" type="date"></select>
                        </div>
                        <div class="col mt-1 ml-md-1 align-middle">
                            <span>Data final</span>
                            <input class="custom-select mr-sm-2" id="solicitacao_filtro_data2" value="<?php echo date('Y-m-d'); ?>" type="date">
                        </div>
                    </div>
                    <div class="row mt-1-sm">
                        <div class="col-md mt-2 align-middle">
                            <a href="#" class="btn btn-secondary" id="solicitacao_filtro_botao"><i class="icon-filter"></i> Filtrar</a>
                        </div>
                        <div class="col-md mt-2 align-middle d-none" id="divXls">
                            <a href="#" class="btn btn-success" id="solicitacao_baixar_botao"><i class="icon-download-2"></i> Baixar .XLS</a>
                        </div>
                        <div class="col mt-2 ml-md-1 align-middle">
                            <button class="btn btn-danger" id="btEnvio"><i class="icon-mail-2"></i> Solicitações para envio: <span id="qtdEnvio"></span></button>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div id="retornoFiltro"></div>
                </div>
            </div>
            <div style="display:none" class="alert alert-danger" role="alert" id="erro"></div>
            <div style="display:none" class="alert alert-info text-center font-weight-bold" role="alert" id="load">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
                </div>
                <strong class="">Aguarde...</strong>
            </div>
        </div>
        <div class="table-responsive mt-2" id="solicitacao_lista"></div>
    </center>
</body>

</html>