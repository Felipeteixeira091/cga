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

    <script src="js/js_menu.js<?php echo $versao; ?>"></script>
    <script src="js/PA.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/menu.css<?php echo $versao; ?>">
    <link rel="stylesheet" href="css/all.css<?php echo $versao; ?>">
    <script src="js/SMA_EDICAO.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">SMA</span></div>
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
        <div style="margin: 1px">
            <div class="container theme-showcase" role="main">
                <div id="pa_formulario0" style="display:" class="card border mt-2">
                    <div class="card-header font-weight-bold">Solicitações pendentes de alterações</div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="row mt-1-sm">
                                <div class="col text-muted">Solicitações pendentes de edição: <span id="pendentesEdicao"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="retornoLista"></div>
                    </div>
                </div>
                <div id="pa_formulario2" style="display:none" class="card border mt-2 p-1">
                    <div class="card-header font-weight-bold">Nova solicitação</div>
                    <div class="card-body">
                        <div class="row mt-1-sm">
                            <div style="text-align: center; margin-top: 2%; margin-bottom: 1%; font-weight: bold" class="col">Solicitação <span id="NumeroSolicitacao"></span> | Sobressalente: <span id="Sobressalente"></span></div>
                        </div>
                        <div class="row mt-1-sm">
                            <div style="text-align: center; margin-top: 2%; margin-bottom: 1%; font-weight: bold" class="col">Editando: <span id="EditNome"></span> | RE: <span id="EditRE"></span></div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <input class="btn_edit form-control" type="text" id="ItemTXT" placeholder="NOVO ITEM...">
                            </div>
                            <div class="col-md mt-1">
                                <button id="btFiltra1" class="btn_edit btn btn-sm btn-outline-info"><i class="icon-search-1"></i> Filtrar</button>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-1-sm">
                            <div class="col">
                                <button class="btn_edit btn btn-sm btn-outline-success" id="formConclui"><i class="icon-ok-circled-1"></i> Edição concluída</button>
                            </div>
                            <div class="col">
                                <button class="btn_edit btn btn-sm btn-outline-info" id="formCancela"><i class="icon-lock-open"></i> Liberar Solicitação</button>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-outline-secondary" id="formVolta"><i class="icon-reply-1"></i> Voltar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="retornoNova1"></div>
                    </div>
                </div>
                <div style="display: none;" id="ListaItensAdd"></div>
                <div style="display: none;" id="SolicitacaoItens"></div>
            </div>
            <div id="listaSolicitacao" class="table-responsive mt-3"></div>
    </center>

</body>

</html>