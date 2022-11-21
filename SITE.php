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

    <script src="js/js_menu.js<?php echo $versao; ?>"></script>
    <script src="js/SITE.js<?php echo $versao; ?>"></script>

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
            <div id="pgSistema" class="rounded"><span class="navbar-brand">ADM</span></div>
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
        <div class="container theme-showcase mb-3" role="main">
            <div class="card border mt-2 p-1" style="display: none" id="site_formulario1">
                <div class="card-header font-weight-bold">Pesquisar SITE</div>
                <div class="row mt-2">
                    <div class="col">
                        <input class="form-control" type="text" id="siteTXT" placeholder="PESQUISAR...">
                    </div>
                    <div class="col">
                        <select class="form-control" id="siteCn"></select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <button id="btFiltra" class="btn btn-light border"><i class="icon-filter"></i> FILTRAR</button>
                    </div>
                    <div class="col-md mt-1">
                        <button type="button" class="btn btn-light border text-muted" id="bt_xls" disabled><i class="icon-download-2 text-success"></i> Exportar para .XLS</button>
                    </div>
                    <div class="col">
                        <button id="btFormNovo" class="btn btn-light border"><i class="icon-plus-circle"></i> NOVO</button>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="site_formulario_novo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo"><i class="icon-doc-new text-muted"></i> NOVO SITE</h5>
                        </div>
                        <div class="modal-body ml-2 mr-2">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <input class="form-control" maxlength="64" type="text" id="site_descricao" placeholder="DESCRIÇÃO (NOME DO SITE)">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <input class="form-control" type="text" maxlength="32" id="site_sigla" placeholder="SIGLA">
                                </div>
                                <div class="col-md mt-1">
                                    <select class="form-control" id="site_uf"></select>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <select class="form-control" id="site_tipo"></select>
                                </div>
                                <div class="col-md mt-1">
                                    <select class="form-control" id="site_cn"></select>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <select class="form-control" id="site_cidade"></select>
                                </div>
                                <div class="col-md mt-1 d-none" id="col_site_cidade_n">
                                    <input class="form-control border-danger text-danger" type="text" maxlength="32" id="site_cidade_n" placeholder="NOME DA CIDADE">
                                </div>
                                <div class="col-md mt-1">
                                    <select class="form-control" id="site_bairro"></select>
                                </div>
                                <div class="col-md mt-1 d-none" id="col_site_bairro_n">
                                    <input class="form-control border-danger text-danger" type="text" maxlength="32" id="site_bairro_n" placeholder="NOME DO BAIRRO">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <input onkeypress="return somenteNumeros(event)" class="form-control" maxlength="8" type="text" id="site_cep" placeholder="CEP">
                                </div>
                                <div class="col-md mt-1">
                                    <input class="form-control" maxlength="120" type="text" id="site_endereco" placeholder="ENDEREÇO (RUA...)">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <button class="btn btn-light border text-muted" id="bt_cadastro_SITE"><i class="icon-plus-circle text-success"></i> Cadastrar</button>
                                </div>
                                <div class="col-md mt-1">
                                    <button type="button" class="btn btn-light border text-muted" data-dismiss="modal" id="bt_cadastro_SITE_voltar"><i class="icon-reply-1"></i> Voltar</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="retornoNovoSite"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="site_formulario_edita" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo"><i class="icon-edit text-muted"></i> EDITAR SITE <span id="site_id" class="badge badge-pill badge-light text-secondary"></span></h5>
                        </div>
                        <div class="modal-body ml-2 mr-2">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <input class="form-control" maxlength="64" type="text" id="edita_site_descricao" placeholder="DESCRIÇÃO (NOME DO SITE)">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <input class="form-control" disabled type="text" maxlength="32" id="edita_site_sigla" placeholder="SIGLA">
                                </div>
                                <div class="col-md mt-1">
                                    <select class="form-control" id="edita_site_uf"></select>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <select class="form-control" id="edita_site_tipo"></select>
                                </div>
                                <div class="col-md mt-1">
                                    <select class="form-control" id="edita_site_cn"></select>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <select class="form-control" id="edita_site_cidade"></select>
                                </div>
                                <div class="col-md mt-1 d-none" id="col_site_cidade_e">
                                    <input class="form-control border-danger text-danger" type="text" maxlength="32" id="site_cidade_e" placeholder="NOME DA CIDADE">
                                </div>
                                <div class="col-md mt-1">
                                    <select class="form-control" id="edita_site_bairro"></select>
                                </div>
                                <div class="col-md mt-1 d-none" id="col_site_bairro_e">
                                    <input class="form-control border-danger text-danger" type="text" maxlength="32" id="site_bairro_e" placeholder="NOME DO BAIRRO">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <input onkeypress="return somenteNumeros(event)" class="form-control" maxlength="8" type="text" id="edita_site_cep" placeholder="CEP">
                                </div>
                                <div class="col-md mt-1">
                                    <input class="form-control" maxlength="120" type="text" id="edita_site_endereco" placeholder="ENDEREÇO (RUA...)">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <button class="btn btn-light border text-muted" id="bt_edita_SITE"><i class="icon-pencil-alt-1 text-success"></i> Editar</button>
                                </div>
                                <div class="col-md mt-1">
                                    <button type="button" class="btn btn-light border text-muted" data-dismiss="modal" id="bt_cadastro_SITE_voltar"><i class="icon-reply-1"></i> Voltar</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="retornoEditaSite"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: none;" id="ListaSITE"></div>
    </center>

</body>

</html>