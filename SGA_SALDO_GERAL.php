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

        <script src="js/SGA_SALDO_GERAL.js<?php echo $versao; ?>"></script>
        <script src="js/js_menu.js<?php echo $versao; ?>"></script>
        <link rel="stylesheet" href="css/menu.css<?php echo $versao; ?>">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

        <link rel="icon" href="css/ico.png<?php echo $versao; ?>">
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
            <div class="container theme-showcase" role="main">
                <div id="lc_formulario1" style="display:none" class="card border mt-2 p-1">
                    <div class="card-header font-weight-bold">Consulta de saldo SGA</div>
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
                                <button id="btFiltra" class="btn btn-light border"><i class="bi bi-filter"></i> Filtrar</button>
                            </div>
                            <div class="col-md">
                                <button type="button" class="btn btn-light border text-muted" id="bt_xls" disabled><i class="bi bi-filetype-xls text-success"></i> Exportar</button>
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
            </div>
            </div>
            <hr>
            <div class="center-block font-weight-bold col-6 alert alert-light" style="display:none" id="DivXls">
                <button type="button" class="btn btn-light border text-muted" id="bt_xls"><i class="bi bi-filetype-xls text-success"></i> Exportar</button>
            </div>
            <div style="display: none;" id="ListaSC"></div>
        </center>

    </body>

</html5>