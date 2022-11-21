<?php
include_once "sc/l_sessao.php";
include "versao.php";
?>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo $sistema; ?></title>
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

    <script src="js/js_login.js<?php echo $versao; ?>"></script>
    <script src="js/js_menu.js<?php echo $versao; ?>"></script>
    <script src="js/ADM.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/menu.css">

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">ADM</span></div>
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
            <div style="margin: 2px;" class="objeto">

                <div style="display: none" class="card mt-3" id="formulario1">
                    <div class="card-header font-weight-bold">Ferramentas Administrativas</div>
                    <div class="card-body">
                        <div class="row mt-2">
                            <div class="col">
                                <button id="btFormCartao" class="btn btn-light border text-muted"><i class='icon-credit-card-1'></i> Desbloqueio de cartões</button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col bg bg-light text-muted text-center border">
                                <span>Relatórios .XLS</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col text-muted text-center pt-1 pb-1">
                                <button id="xlsUsuario" class="btn btn-light border text-muted"><i class='icon-download-2 text-success'></i> Usuários</button>
                            </div>
                            <div class="col text-muted text-center pt-1 pb-1">
                                <button id="xlsEquipamentos" class="btn btn-light border text-muted"><i class='icon-download-2 text-success'></i> Equipamentos</button>
                            </div>
                            <div class="col text-muted text-center pt-1 pb-1">
                                <button id="xlsFrota" class="btn btn-light border text-muted"><i class='icon-download-2 text-success'></i> Frotas</button>
                            </div>
                            <div class="col text-muted text-center pt-1 pb-1">
                                <button id="xlsGas" class="btn btn-light border text-muted"><i class='icon-download-2 text-success'></i> Saldo Gás</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer mt-2">
                        <div id="retornoFiltro"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_cartao" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">SOLICITAR DESBLOQUEIO DE CARTÕES</h5>
                    </div>
                    <div class="modal-body ml-2 mr-2">
                        <div class="row mt-2-sm">
                            <div class="col">
                                <div id="cartaoLista"></div>
                            </div>
                        </div>
                        <div class="collapseNotaDestinatarios collapse">
                            <div class="row mt-2-sm">
                                <div class="col-md mt-1 font-weight-bold text-muted rounded bg-light border">
                                    Destinatários já cadastrados
                                </div>
                            </div>
                            <div class="row mt-2-sm">
                                <div class="col mt-1">
                                    <div id="notaEnviarDestinatarioLista" class="text-monospace">Lista</div>
                                </div>
                            </div>
                        </div>
                        <div class="collapseNotaDestinatarios collapse">
                            <div class="row mt-2-sm">
                                <div class="col-md mt-1 font-weight-bold text-muted rounded bg-light border">
                                    Cadastrar novo
                                </div>
                            </div>
                            <div class="row mt-2" id="notaEnviarDestinatarioNovo">
                                <div class="col mt-1 align-middle">
                                    <input class="form-control" id="destNome" placeholder="NOME">
                                </div>
                                <div class="col mt-1 align-middle">
                                    <input class="form-control" id="destEmail" placeholder="E-MAIL">
                                </div>
                                <div class="col mt-1 align-middle">
                                    <select class="form-control" id="destTipo">
                                        <option value="0">TIPO</option>
                                        <option value="para">Para</option>
                                        <option value="cc">Cópia</option>
                                        <option value="cco">Cópia oculta</option>
                                    </select>
                                </div>
                                <div class="col mt-1 align-middle">
                                    <button id="cartaoDestAdd" class="btn btn-sm btn-outline-info"><i class='icon-plus-circled'></i> Adicionar</button>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="row mt-3">
                            <div class="col mt-1 align-middle">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                            <div class="col mt-1 align-middle">
                                <button id="btEnviaDestinatario" class="btn btn-outline-info btn-sm" type="button" data-toggle="collapse" data-target=".collapseNotaDestinatarios" aria-expanded="false" aria-controls="collapseExample">
                                    <i class="icon-down-small"></i><i class="icon-th-list-2"></i> Destinatários </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="ModalRetornoCartao"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="Lista" class="table-responsive mt-3"></div>
    </center>

</body>

</html>