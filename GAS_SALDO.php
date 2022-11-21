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

        <script src="js/GAS_SALDO.js<?php echo $versao; ?>"></script>
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
                <div id="pgSistema" class="rounded"><span class="navbar-brand">GÁS</span></div>
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
                                <h5 class="modal-title" id="ModalTitulo"><i class="icon-fire-station text-muted"></i> Novo Registro de utilização de gás</h5>
                            </div>
                            <div class="modal-body">
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <button class="btn btn-light text-muted border" id="btModalSite"><i class="icon-search-1 text-info"></i> Selecionar site</button>
                                    </div>
                                </div>
                                <div class="row mt-1-sm" id="rowSite">
                                    <div class="col-md mt-1 bg bg-light border-bottom border-top">
                                        <span id="textoSite" class="text-muted">SELECIONE UM SITE ACIMA <i class="icon-up text-info"></i> </span> <span id="lc_site" class="badge badge-pill badge-light text-secondary"></span>
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <select class="form-control" id="lc_gas"></select>
                                    </div>
                                    <div class="col-md mt-1">
                                        <input onkeyup="quantidade(this);" class="form-control" type="text" maxlength="14" id="lc_qtd" placeholder="QUANTIDADE (Kg) *">
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <input onkeypress="return somenteNumeros(event)" class="form-control" type="text" maxlength="14" id="lc_os" placeholder="OS PRSIMA *">
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <input class="form-control" pattern="^[a-zA-Z0-9]+$" title="Somente letras, números, ponto e vírgula" type="text" maxlength="140" id="lc_obs" placeholder="OBSERVAÇÕES MAX(140)">
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <span>DATA</span>
                                        <input class="form-control" type="date" maxlength="14" id="lc_data" placeholder="Data INICIAL">
                                    </div>
                                    <div class="col-md mt-1">
                                        <span>HORA</span>
                                        <input class="form-control" type="time" maxlength="140" id="lc_hora" placeholder="INÍCIO">
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <button class="btn btn-light border text-muted" id="bt_cadastro_lc"><i class="icon-ok-circled-1 text-success"></i> Cadastrar</button>
                                    </div>
                                    <div class="col-md mt-1">
                                        <button class="btn btn-light border text-muted" data-dismiss="modal" id="bt_cadastro_lc_voltar"><i class="icon-reply-1"></i> Voltar</button>
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
                <div id="lc_formulario1" style="display:none" class="card border mt-2 p-1">
                    <div class="card-header font-weight-bold">Saldo de gás</div>
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col">
                                <input type="text" class="form-control" id="sc_nome" placeholder="Pesquisa por nome...">
                            </div>
                            <div class="col">
                                <select class="form-control" id="sc_cn"></select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <button id="btFiltra" class="btn btn-light border"><i class="icon-filter"></i> Filtrar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="retornoFiltro"></div>
                    </div>
                </div>
                <div class="modal fade" data-keyboard="false" data-backdrop="static" id="DetalheEstoque" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalTitulo">Estoque de gás <span id="tituloEstoque"></span></h5>
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
                <div class="modal fade" data-keyboard="false" data-backdrop="static" id="DetalheAltera" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalTituloAltera">SALDO <span id="tipoGas"></span><span class="d-none" id="idBag"></span></h5>
                            </div>
                            <div class="modal-body m-1">
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        SALDO ATUAL: <span id="saldoAtualModal"></span>
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <input onkeyup="quantidade(this);" class="form-control" type="text" maxlength="14" id="saldo_correto" placeholder="INSIRA O SALDO CORRETO *">
                                    </div>
                                    <div class="col-md mt-1">
                                        <button class="btn btn-light border" id="btAlteraSaldo"><i class="icon-loop-1 text-info"></i> Corrigir</button>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <button class="btn btn-light border" data-dismiss="modal"><i class=" icon-reply-1"></i> Voltar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <div class="col alert alert-ligth">
                                    <div id="retornoModalCorrige"></div>
                                </div>
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
            <div style="display: none;" id="ListaSC"></div>
        </center>

    </body>

</html5>