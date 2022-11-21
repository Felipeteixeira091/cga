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

    <link rel="stylesheet" href="css/menus.css">
    <script src="js/js_menu.js"></script>
    <script src="js/js_login.js"></script>
    <script src="js/ENVIO.js<?php echo $versao; ?>"></script>

    <link type="text/css" rel="stylesheet" href="css/all.css<?php echo $versao; ?>" />
    <link rel="stylesheet" href="css/spin.css<?php echo $versao; ?>">
    <link rel="stylesheet" href="css/spin.css<?php echo $versao; ?>">
    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">
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
        <div class="container theme-showcase" style="margin-top: 2%" role="main">
            <div id="solicitacao_filtro" class="card border mt-2 p-1">
                <div class="card-header font-weight-bold text-center">Solicitações para envio</span></div>
                <div class="card-body">

                    <div id="Lista"></div>
                    <div class="row mt-1-sm">
                        <div class="col-md mt-1 align-middle bg-light border border rounded">
                            <span class="text-muted">Quantidade Total: <span id="qtdTotal"></span></span>
                        </div>
                        <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded">
                            <span class="text-muted">Valor total: <span id="valorTotal"></span></span>
                        </div>
                    </div>
                    <div class="row mb-3 mt-3">
                        <div class="col align-middle">
                            <a href="#" id="bt_envio_sol" class="btn btn-info"><i class="icon-mail-2"></i> Enviar</a>
                            <span id="span_load">
                                <div id="spin" class="text-muted">
                                    <i class='icon-spin1 animate-spin text-info'></i>
                                    <p>Carregando dados, aguarde...
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div id="retornoEnvio"></div>
                </div>
            </div>
            <div class="modal hide fade in" data-keyboard="false" data-backdrop="static" id="solicitacaoAltera" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="card">
                            <div class="card-header font-weight-bold">Solicitação: <span id="id_solicitacao"></span></div>
                            <div class="card-body">
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1 align-middle bg-light border border rounded">
                                        Valor Solicitado: <span id="valor_solicitado"></span>
                                    </div>
                                    <div class="col mt-1 align-middle">
                                        <input class="form-control" id="novo_valor" onkeyup="somenteNumeros(this);" placeholder="Novo Valor">
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col mt-1 align-middle">
                                        <button class="btn btn-outline-success m-1 btn-sm" id="btAlteraValor"><i class='icon-exchange'></i> Alterar</button>
                                    </div>
                                    <div class="col mt-1 align-middle">
                                        <button type="button" class="btn btn-outline-secondary m-1 btn-sm" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div id="retornoAltera"></div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
    </center>
    <div class="table-responsive" id="solicitacao_lista"></div>
</body>

</html>