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
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/all.css">
    <script src="js/js_menu.js<?php echo $versao; ?>"></script>

    <script src="js/Permissao.js<?php echo $versao; ?>"></script>
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
            </div>
            <div class="alert alert-danger" role="alert" id="erro"></div>
            <div class="alert alert-success" role="alert" id="sucesso"></div>
            <div id="formPermissao">
                <div id="formPermissao" class="card border mt-2 p-1">
                    <div class="card-header font-weight-bold">Permissões</div>
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col">
                                <input class="form-control" type="text" placeholder="Pesquisar por nome ou RE" id="txt"></input>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <select id="formPermissaoUsuario" class="form-control"></select>
                            </div>
                            <div class="col">
                                <select id="formPermissaoTipo" class="form-control"></select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <select id="formPermissaoPagina" style="display:none" class="form-control"></select>
                                <select id="formPermissaoFuncao" style="display:none" class="form-control"></select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col mt-2" id="div_bt_add" style="display:none">
                                <button class="btn btn-success" id="formPermissaoADD">Adicionar</button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="row" class="mt-3">
                                <div class="col" id="Permissoes"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="retornoPermissao"></div>
                    </div>
                </div>

    </center>

</body>

</html>