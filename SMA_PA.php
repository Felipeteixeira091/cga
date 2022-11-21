<?php
include "versao.php";
include_once "sc/l_sessao.php";
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
    <script src="js/SMA_PA.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/menu.css">

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
            <div id="pgSistema" class="rounded"><span class="navbar-brand">SMA</span></div>
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
            <div style="margin: 2px;" class="objeto">
                <div id="pa_formulario1" class="card border mt-2 p-1">
                    <div class="card-header font-weight-bold">Pesquisar PA</div>
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col">
                                <input class="form-control" type="text" id="paTXT" placeholder="PESQUISAR...">
                            </div>
                            <div class="col">
                                <select class="form-control" id="paTipo"></select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">

                                <button id="btFiltra" class="btn btn-light border text-muted"><i class="icon-filter text-primary"></i> Filtrar</button>
                            </div>
                            <div class="col">
                                <button id="btFormNovo" class="btn btn-light border text-muted"><i class="icon-plus-circle text-primary"></i> PA</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="retornoProcurar"></div>
                    </div>
                </div>
                <div style="display: none" id="pa_formulario_novo" class="card border mt-2 p-1">
                    <div class="card-header font-weight-bold">Novo PA</div>
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col">
                                <input class="form-control" type="number" id="pa_numero" placeholder="PA (SOMENTE NÚMEROS)">
                            </div>
                            <div class="col">
                                <input class="form-control" type="text" id="pa_descricao" placeholder="NOME PA">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <select class="form-control" id="pa_tipo"></select>
                            </div>
                            <div class="col">
                                <input class="form-control" type="text" maxlength="64" id="pa_observacoes" placeholder="OBSERVAÇÕES">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <select class="form-control" id="pa_sobressalente">
                                    <option value="2">SOBRESSALENTE</option>
                                    <option value="0">NÃO</option>
                                    <option value="1">SIM</option>
                                </select>
                            </div>
                            <div class="col">
                                <select class="form-control" id="pa_tipo_unidade"></select>
                            </div>
                            <div class="col">
                                <select class="form-control" id="pa_gas"></select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <button class="btn btn-success" id="bt_cadastro_PA">Cadastrar</button>
                            </div>
                            <div class="col">
                                <button class="btn btn-secondary" id="bt_cadastro_PA_voltar">Voltar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="retornoCadastro"></div>
                    </div>
                </div>
            </div>
            <div class="alert alert-danger" style="display:none" role="alert" id="erro"></div>
            <div class="alert alert-warning" style="display:none" role="alert" id="load"></div>
            <div class="alert alert-success mt-5" style="display:none" role="alert" id="sucesso"></div>
        </div>
        <div style="display: none;" class="mt-3" id="ListaPA"></div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_edita" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Editar PA ID: <span id="editaId"></span></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col border alert-ligth p-2 m-2 rounded text-muted font-weight-bold">
                                CADASTRO: <span id="editaNome"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input class="form-control" placeholder="PA" maxlength="14" id="editaPa">
                            </div>
                            <div class="col">
                                <input class="form-control" placeholder="DESCRIÇÃO" maxlength="63" id="editaDescricao">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <select class="form-control" type="text" id="editaTipo"></select>
                            </div>
                            <div class="col">
                                <textarea class="form-control" style="resize: none" placeholder="OBSERVAÇÕES" maxlength="120" id="editaObs"></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <select class="form-control" type="text" id="editaSobressalente">
                                    <option value="ND">SOBRESSALENTE</option>
                                    <option value="0">NÃO</option>
                                    <option value="1">SIM</option>
                                </select>
                            </div>
                            <div class="col">
                                <select class="form-control" type="text" id="editaMedida"></select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <select class="form-control" type="text" id="editaGas"></select>
                            </div>
                            <div class="col">
                                <select class="form-control" id="editaStatus">
                                    <option value="ND">STATUS</option>
                                    <option value="0">PENDENTE</option>
                                    <option value="1">ATIVO</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <button class="btn btn-light border text-muted" id="bt_edita_PA"><i class="icon-edit text-primary"></i> Editar</button>
                            </div>
                            <div class="col">
                                <button class="btn btn-light border text-muted" data-dismiss="modal"><i class="icon-reply-1 text-danger"></i> Voltar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="ModalRetorno_edita"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </center>

</body>

</html>