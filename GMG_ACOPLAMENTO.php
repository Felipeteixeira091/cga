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

    <link type="text/css" rel="stylesheet" href="css/all.css<?php echo $versao; ?>" />

    <script src="js/GMG_ACOPLAMENTO.js<?php echo $versao; ?>"></script>
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
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">GMG</span></div>
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
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="ac_formulario_novo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo"><i class="icon-plug text-muted"></i> Novo Acoplamento</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <select class="form-control" id="ac_gmg"></select>
                                </div>
                                <div class="col-md mt-1">
                                    <button class="btn btn-light text-muted border" id="btModalSite"><i class="icon-search-1 text-danger"></i> Selecionar site</button>
                                </div>
                            </div>
                            <div class="row mt-1-sm" id="rowSite">
                                <div class="col-md mt-1 bg bg-light border-bottom border-top">
                                    <span id="textoSite" class="text-muted">SELECIONE UM SITE ACIMA <i class="icon-up text-danger"></i> </span> <span id="ac_site" class="badge badge-pill badge-light text-secondary"></span>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <input onkeypress="return somenteNumeros(event)" class="form-control" type="text" maxlength="14" id="ac_ta" placeholder="TA *">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <input class="form-control" type="text" maxlength="140" id="ac_observacoes" placeholder="OBSERVAÇÕES">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <span>DATA INICIAL</span>
                                    <input class="form-control" type="date" maxlength="14" id="ac_data_inicio" placeholder="Data INICIAL">
                                </div>
                                <div class="col-md mt-1">
                                    <span>HORA INICIAL</span>
                                    <input class="form-control" type="time" maxlength="140" id="ac_hora_inicio" placeholder="INÍCIO">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <span>DATA FINAL</span>
                                    <input class="form-control" type="date" maxlength="14" id="ac_data_final" placeholder="Data INICIAL">
                                </div>
                                <div class="col-md mt-1">
                                    <span>HORA FINAL</span>
                                    <input class="form-control" type="time" maxlength="140" id="ac_hora_final" placeholder="FIM">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <button class="btn btn-light border text-muted" id="bt_cadastro_ac"><i class="icon-ok-circled-1 text-success"></i> Cadastrar</button>
                                </div>
                                <div class="col-md mt-1">
                                    <button class="btn btn-light border text-muted" id="bt_cadastro_ac_voltar"><i class="icon-reply-1"></i> Voltar</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="retornoAcoplamentoNova"></div>
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
            <div id="ac_formulario1" style="display:none" class="card border mt-2">
                <div class="card-header font-weight-bold">Acoplamento</div>
                <div class="card-body">
                    <div class="row mt-1-sm">
                        <div class="col-md mt-1">
                            <select class="form-control" id="ac_cn"></select>
                        </div>
                        <div class="col-md mt-1">
                            <input type="text" class="form-control" id="ac_txt" placeholder="IDENTIFICAÇÃO, etc...">
                        </div>
                    </div>
                    <div class="row mt-1-sm">
                        <div class="col-md mt-1">
                            <span>DATA INICIAL</span>
                            <input type="date" class="form-control" id="ac_DataInicio" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md mt-1">
                            <span>DATA FINAL</span>
                            <input type="date" class="form-control" id="ac_DataFIm" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="row mt-1-sm">
                        <div class="col-md mt-1">
                            <button id="btFiltra" class="btn btn-light border"><i class="icon-filter"></i> Filtrar</button>
                        </div>
                        <div class="col-md mt-1">
                            <button id="btFormNovo" class="btn btn-light border"><i class="icon-plus-circle"></i> Novo Acoplamento</button>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div id="retornoFiltro"></div>
                </div>
            </div>
            <div id="DetalheAcoplamento" style="display:none" class="card border-light mt-2 p-1">
                <div class="card-header font-weight-bold">Acoplamento de GMG - ID:<span id="ACId"></span></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col alert alert-secondary">GMG: <span id="ACGMG"></span></div>
                    </div>
                    <div class="row mt-1">
                        <div class="col alert alert-secondary">
                            Técnico responsável: <span id="ACTecnicoNOME"></span> - <span id="ACTecnicoRE"></span>
                        </div>
                        <div class="col alert alert-secondary ml-1">
                            Coordenador: <span id="ACCoordenadorNOME"></span> - <span id="ACCoordenadorRE"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col alert alert-secondary">Data/Hora registro: <span id="ACRegistro"></span></div>
                        <div class="col ml-1 alert alert-secondary">Início: <span id="ACInicio"></span></div>
                        <div class="col ml-1 alert alert-secondary">Final: <span id="ACFinal"></span></div>
                    </div>
                    <div class="row">
                        <div class="col alert alert-secondary">CN/SITE: <span id="ACCN_SITE"></span></div>
                    </div>
                    <div class="row">
                        <div class="col alert alert-secondary">OBSERVAÇÕES: <span id="ACCN_OBS"></span></div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-secondary" id="bt_formVolta">Voltar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="alert alert-danger" style="display:none" role="alert" id="erro"></div>
        <div class="alert alert-warning" style="display:none" role="alert" id="load"></div>
        <div class="alert alert-success" style="display:none" role="alert" id="sucesso"></div>
        <hr>
        <div class="center-block font-weight-bold col-6 alert alert-light" style="display:none" id="DivXls">
            <button type="button" class="btn btn-light border text-muted" id="bt_xls"><i class="icon-download-2 text-success"></i> Exportar para .XLS</button>
        </div>
        <div style="display: none;" id="ListaAC"></div>
    </center>

</body>

</html>