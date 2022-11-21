<?php
include_once "sc/l_sessao.php";
include "versao.php";
?>
<html5>

    <head>
        <meta charset="UTF-8">
        <title><?php echo $sistema ?></title>


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

        
        <script src="js/SCP_VALIDACAO.js<?php echo $versao; ?>"></script>
        <script src="js/js_menu.js<?php echo $versao; ?>"></script>
        <link rel="stylesheet" href="css/menu.css<?php echo $versao; ?>">

        <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">
        <link rel="icon" href="css/ico.png">
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
                <div id="scp_formulario1" style="display:none" class="card border mt-2 p-1">
                    <div class="card-header font-weight-bold">Ponto a corrigir</div>
                    <div class="card-body">
                        <div class="row mt-2">
                            <div class="col">
                                <select class="custom-select mr-sm-2" id="scp_cn"></select>
                            </div>
                            <div class="col">
                                <input class="form-control" type="text" id="scp_Txt" placeholder="PESQUISAR...">
                            </div>
                            <div class="col">
                            <select class="custom-select mr-sm-2" id="scp_status"></select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <span>DATA INICIAL</span>
                                <input type="date" class="custom-select mr-sm-2" id="scp_DataInicio" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col">
                                <span>DATA FINAL</span>
                                <input type="date" class="custom-select mr-sm-2" id="scp_DataFIm" value="<?php echo date('Y-m-d'); ?>">
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
                <div class="modal fade" data-keyboard="false" data-backdrop="static" id="DetalheLancamento" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalTitulo">Solicitação de correção de ponto - ID: <span id="SCPId"></span></h5>
                            </div>
                            <div class="modal-body m-1">
                            <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded"><span class="font-weight-bold">Colaborador:</span> <span id="SCPNome"></span></div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded"><span class="font-weight-bold">Registro:</span> <span id="SCPDataRegistro"></span></div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded"><span class="font-weight-bold">Entrada:</span> <span id="SCPEntrada"></span></div>
                                    <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded"><span class="font-weight-bold">Saída:</span> <span id="SCPSaida"></span></div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded"><span class="font-weight-bold">CN/SITE:</span> <span id="SCPCN_SITE"></span></div>
                                    <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded"><span class="font-weight-bold">ATIVIDADE:</span> <span id="SCPAtividade"></span></div>
                                    <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded"><span class="font-weight-bold">TA/TP/OS:</span> <span id="SCPOs"></span></div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded"><span class="font-weight-bold">JUSTIFICATIVA:</span> <span id="SCPJustificativa"></span></div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded"><span class="font-weight-bold text-danger">AVALIAÇÃO:</span> <span id="SCPObs"></span></div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded"><span class="font-weight-bold">STATUS:</span> <span id="SCPStatus"></span></div>
                                </div>
                                <hr>
                                <div class="row mt-1-sm">
                                    <input class="form-control" pattern="^[a-zA-Z0-9]+$" title="Somente letras, números, ponto e vírgula" type="text" maxlength="140" id="scp_obs" placeholder="AVALIAÇÃO MAX(140)">
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <button class="btn btn-light border" id="bt_validar"><i class="icon-ok-circled text-success"></i> VALIDAR</button>
                                    </div>
                                    <div class="col-md mt-1">
                                        <button class="btn btn-light border" id="bt_invalido"><i class="icon-cancel-circled-1 text-danger"></i> RECUSADO</button>
                                    </div>
                                    <div class="col-md mt-1">
                                        <button class="btn btn-light border" data-dismiss="modal" id="bt_formVolta"><i class="icon-reply-1 text-muted"></i> Voltar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <div class="col alert alert-ligth">
                                    <div id="retorno"></div>
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