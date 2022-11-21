<?php
include_once "sc/l_sessao.php";
include "versao.php";
?>
<html5>

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

        <link type="text/css" rel="stylesheet" href="css/all.css<?php echo $versao; ?>" />

        <script src="js/SCP_REGISTRO.js<?php echo $versao; ?>"></script>
        <script src="js/js_menu.js<?php echo $versao; ?>"></script>
        <link rel="stylesheet" href="css/menu.css<?php echo $versao; ?>">

        <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">
        <script>
            function somenteNumeros(e) {
                var charCode = e.charCode ? e.charCode : e.keyCode;
                // charCode 8 = backspace   
                // charCode 9 = tab
                if (charCode != 8 && charCode != 9) {
                    // charCode 48 equivale a 0   
                    // charCode 57 equivale a 9
                    if (charCode < 48 || charCode > 57) {
                        return false;
                    }
                }
            }

            function quantidade(num) {
                var er = /[^0-9,]/;
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
                <div id="pgSistema" class="rounded"><span class="navbar-brand">SCP</span></div>
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
                <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="scp_formulario_novo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalTitulo"><i class="icon-doc-add text-muted"></i> Novo solicitação de correção de ponto</h5>
                            </div>
                            <div class="modal-body">
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <button class="btn btn-light text-muted border" id="btModalSite"><i class="icon-search-1 text-info"></i> Selecionar site</button>
                                    </div>
                                </div>
                                <div class="row mt-1-sm" id="rowSite">
                                    <div class="col-md mt-1 bg bg-light border-bottom border-top">
                                        <span id="textoSite" class="text-muted">SELECIONE UM SITE ACIMA <i class="icon-up text-info"></i> </span> <span id="scp_site" class="badge badge-pill badge-light text-secondary"></span>
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <select class="form-control" id="scp_colaborador"></select>
                                    </div>
                                    <div class="col-md mt-1">
                                        <select class="form-control" id="scp_atividade"></select>
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <input onkeypress="return somenteNumeros(event)" class="form-control" type="text" maxlength="14" id="scp_os" placeholder="*" style="display: none;">
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <input class="form-control" pattern="^[a-zA-Z0-9]+$" title="Somente letras, números, ponto e vírgula" type="text" maxlength="140" id="scp_obs" placeholder="JUSTIFICATIVA MAX(140)">
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <span class="text-muted">DATA ENTRADA</span>
                                        <input class="form-control" type="date" maxlength="14" id="scp_data_1" placeholder="Data INICIAL">
                                    </div>
                                    <div class="col-md mt-1">
                                        <span class="text-muted">HORA ENTRADA</span>
                                        <input class="form-control" type="time" maxlength="140" id="scp_hora_1" placeholder="INÍCIO">
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <span class="text-muted">DATA SAÍDA</span>
                                        <input class="form-control" type="date" maxlength="14" id="scp_data_2" placeholder="Data FINAL">
                                    </div>
                                    <div class="col-md mt-1">
                                        <span class="text-muted">HORA SAÍDA</span>
                                        <input class="form-control" type="time" maxlength="140" id="scp_hora_2" placeholder="INÍCIO">
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <button class="btn btn-light border text-muted" id="bt_cadastro_scp"><i class="icon-ok-circled-1 text-success"></i> Cadastrar</button>
                                    </div>
                                    <div class="col-md mt-1">
                                        <button class="btn btn-light border text-muted" data-dismiss="modal" id="bt_cadastro_scp_voltar"><i class="icon-reply-1"></i> Voltar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="col alert alert-ligth">
                                    <div id="retornoLancamento"></div>
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
                                        <button type="button" class="btn btn-light border text-muted" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
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
                <div id="scp_formulario1" style="display:none" class="card border mt-2 p-1">
                    <div class="card-header font-weight-bold">Lançamento de ponto a corrigir</div>
                    <div class="card-body">
                        <div class="row mt-2">
                            <div class="col">
                                <span>DATA INICIAL</span>
                                <input type="date" class="form-control" id="scp_DataInicio" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col">
                                <span>DATA FINAL</span>
                                <input type="date" class="form-control" id="scp_DataFIm" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <button id="btFiltra" class="btn btn-light border"><i class="icon-filter"></i> Filtrar</button>
                            </div>
                            <div class="col">
                                <button id="btFormNovo" class="btn btn-light border"><i class="icon-plus-circle"></i> Novo Registro</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="retornoFiltro"></div>
                    </div>
                </div>
                <div class="modal fade" data-keyboard="false" data-backdrop="static" id="DetalheLancamento" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalTitulo">Solicitação de correção de ponto - ID: <span id="SCPId"></span></h5>
                            </div>
                            <div class="modal-body m-1">
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded">Registro: <span id="SCPDataRegistro"></span></div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded">Entrada: <span id="SCPEntrada"></span></div>
                                    <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded">Saída: <span id="SCPSaida"></span></div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded">CN/SITE: <span id="SCPCN_SITE"></span></div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded">JUSTIFICATIVA: <span id="SCPJustificativa"></span></div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded">OBSERVAÇÃO: <span id="SCPObs"></span></div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded">STATUS: <span id="SCPStatus"></span></div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <button class="btn btn-light border" data-dismiss="modal" id="bt_formVolta"><i class=" icon-reply-1"></i> Voltar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <div id="retornoSite"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" data-keyboard="false" data-backdrop="static" id="DetalheEstoque" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalTitulo">Meu estoque atual</h5>
                            </div>
                            <div class="modal-body m-1">
                                <div id="Estoque"></div>
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
            </div>
            </div>
            <hr>
            <div class="center-block font-weight-bold col-6 alert alert-light" style="display:none" id="DivXls">
                <button type="button" class="btn btn-light border text-muted" id="bt_xls"><i class="icon-download-2 text-success"></i> Exportar para .XLS</button>
            </div>
            <div style="display: none;" id="ListaSCP"></div>
        </center>

    </body>

</html5>