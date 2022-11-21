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

    <script src="js/js_menu.js<?php echo $versao; ?>"></script>
    <script src="js/SPLANTAO.js<?php echo $versao; ?>"></script>

    <link type="text/css" rel="stylesheet" href="css/all.css<?php echo $versao; ?>" />

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">
</head>

<body>
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div class="rounded"><a class="navbar-brand" href="#"><?php echo $sistema; ?></a></div>
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
            <div class="card mt-2">
                <div class="card-header font-weight-bold">Selecionar Colaborador/GMG</div>
                <div class="card-body">
                    <div class="bg bg-light rounded m-2 border text-danger">Registro de solicitações realizadas em finais de semana e feriados.</div>
                    <div class="row">
                        <div class="col">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <select class="form-control mt-2" id="tipo">
                                <option value="0">TIPO</option>
                                <option value="1">COLABORADOR</option>
                                <option value="2">GMG</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <select class="d-none form-control mt-2" id="tipo_colaborador"></select>
                            <select class="d-none form-control mt-2" id="tipo_gmg"></select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <button id="btDados" class="btn btn-outline-info"><i class="icon-database-1"></i> Obter dados</button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col text-light bg bg-dark font-weight-bold">
                            SOLICITAÇÕES PENDENTES
                        </div>
                    </div>
                    <div class="row mt-1-sm">
                        <div class="col border">
                            <div id="listaPendente"></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div id="retornoLista"></div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="solicitacao_form" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="card mt-2 p-1">
                            <div class="card-header font-weight-bold">Nova solicitação <span id="tituloTipo"></span></div>
                            <div class="card-body">
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 bg-light border rounded">
                                        <span class="text-muted"><span id="detalheNome"></span><span class="badge badge-light text-right" id="detalheRe"></span></span>
                                    </div>
                                    <div class="col-md-3 mt-1 ml-md-1 align-middle bg-light border rounded">
                                        <i class="icon-credit-card"></i> <span class="text-muted"><span id="detalheCartao"></span></span>
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded">
                                        <i class='icon-calendar text-danger'></i><span class="text-muted">Última solicitação:<span class="badge badge-light text-muted text-right">ID<span id='idAnterior'></span></span> <span id="detalheUltSol"></span></span>
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded">
                                        <i class='icon-attention text-danger'></i> <span class="text-muted">Valor mês: <span id="detalheVlr_mes"></span></span>
                                    </div>
                                    <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded">
                                        <span class="text-muted"><span id="detalheModelo"></span><span id="detalheIdentificacao"></span></span>
                                    </div>
                                </div>
                                <div class="row mt-1-sm" id="detalheKM">
                                    <div class="col-md mt-1 align-middle bg-light border rounded">
                                        <span class="text-muted">Último KM: <span id="detalheUltKM"></span></span>
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <input class="form-control" id="valor" onkeyup="somenteNumeros(this);" placeholder="Valor solicitado">
                                    </div>
                                    <div class="col-md mt-1">
                                        <input class="form-control" id="saldoAtual" onkeyup="somenteNumeros(this);" placeholder="Saldo atual">
                                    </div>
                                    <div class="col-md mt-1" id="formKM">
                                        <input class="form-control" id="km" onkeyup="somenteNumeros(this);" placeholder="Km Atual">
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <textarea class="form-control" placeholder="Observações" maxlength="140" id="obs" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <button class="btn btn-sm btn-outline-success" id="btSolicita"><i class="icon-ok-circled-1"></i> SOLICITAR</button>
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-sm btn-outline-secondary" data-dismiss="modal"><i class="icon-cancel-circled-1"></i> CANCELAR</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div id="retornoSolicitacao"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </center>
</body>

</html>