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

    <link rel="stylesheet" href="css/menu.css<?php echo $versao; ?>">
    <link rel="stylesheet" href="css/all.css<?php echo $versao; ?>">
    <script src="js/EXT_CADASTRO.js<?php echo $versao; ?>"></script>
    <link rel="stylesheet" href="css/upload.css<?php echo $versao; ?>">

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">
    <script language="javascript">
        function moeda(a, e, r, t) {
            let n = "",
                h = j = 0,
                u = tamanho2 = 0,
                l = ajd2 = "",
                o = window.Event ? t.which : t.keyCode;
            if (13 == o || 8 == o)
                return !0;
            if (n = String.fromCharCode(o),
                -1 == "0123456789".indexOf(n))
                return !1;
            for (u = a.value.length,
                h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
            ;
            for (l = ""; h < u; h++)
                -
                1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
            if (l += n,
                0 == (u = l.length) && (a.value = ""),
                1 == u && (a.value = "0" + r + "0" + l),
                2 == u && (a.value = "0" + r + l),
                u > 2) {
                for (ajd2 = "",
                    j = 0,
                    h = u - 3; h >= 0; h--)
                    3 == j && (ajd2 += e,
                        j = 0),
                    ajd2 += l.charAt(h),
                    j++;
                for (a.value = "",
                    tamanho2 = ajd2.length,
                    h = tamanho2 - 1; h >= 0; h--)
                    a.value += ajd2.charAt(h);
                a.value += r + l.substr(u - 2, u)
            }
            return !1
        }
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">EXT</span></div>
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
                <div class="objeto m-2">
                    <div id="notaOpc" class="card border mt-2 p-1">
                        <div class="card-header font-weight-bold">Notas</div>
                        <div class="card-body">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 mb-2">
                                    <button class="btn btn-light text-muted border" id="btNova"><i class="icon-plus-circle-1 text-info"></i> Cadastrar nota</button>
                                </div>
                                <div class="col-md mt-1 mb-2">
                                    <button class="btn btn-light text-muted border" id="btPendente"><i class="icon-attention-2 text-danger"></i> Pendentes</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                    <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="notaFormulario" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ModalTitulo"><i class="icon-doc-new text-muted"></i> Cadastro de nova NOTA</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <button class="btn btn-light text-muted border" id="btModalSite"><i class="icon-search-1"></i> Selecionar site</button>
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm border rounded ml-2 mr-2 mt-1 mb-2 p-1" id="rowSite" style="display: none;">
                                        <div class="col-md mt-1">
                                            <span class="font-weight-bold" id="textoSite"></span> <span class="badge badge-pill badge-light text-secondary" id="notaSite"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <select id="notaTipo" class="form-control"></select>
                                        </div>
                                        <div class="col-md mt-1">
                                            <select id="notaColaborador" class="form-control"></select>
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <select id="notaMotivo" class="form-control"></select>
                                        </div>
                                        <div class="col-md mt-1">
                                            <input type="text" id="notaOs" class="form-control" placeholder="NÚMERO" disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <input type="text" id="notaValor" class="form-control" placeholder="VALOR DA NOTA" onKeyPress="return(moeda(this,'.',',',event))">
                                        </div>
                                        <div class="col-md mt-1">
                                            <input type="date" id="notaData" class="form-control" placeholder="DATA">
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <textarea class="form-control" id="notaOBS" style="resize: none" placeholder="OBSERVAÇÕES..."></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-1-sm">
                                        <div class="col-md mt-1">
                                            <button class="btn btn-light text-muted border" id="btnotaCadastra"><i class="icon-ok-circled-1 text-success"></i> Cadastrar</button>
                                        </div>
                                        <div class="col-md mt-1">
                                            <button type="button" class="btn btn-light text-muted border" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="col alert alert-ligth">
                                        <div id="retornoNotaNova"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="notaListaPendente" style="display:none" class="card border mt-2 p-1">
                        <div class="card-header font-weight-bold">Notas pendentes <span class="badge badge-pill badge-light text-secondary border" id="notaQtd"></span></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div id="notaPendente"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md mt-1">
                                    <button class="btn btn-light text-muted border" id="btRetornoPendente"><i class="icon-reply"></i>Voltar</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div id="retornoNotaPendencia"></div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" data-keyboard="false" data-backdrop="static" id="pesquisaSITE" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalTitulo">Procurar site</h5>
                            </div>
                            <div class="modal-body">
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <input class="form-control" placeholder="Procurar site..." type="text" id="formSite">
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col-md mt-1">
                                        <button class="btn btn-outline-info" id="btProcuraSite"><i class="icon-search-1"></i> Procurar</button>
                                    </div>
                                    <div class="col-md mt-1">
                                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
                                    </div>
                                </div>
                                <div id="listaSite" class="table-responsive mt-3"></div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <div id="retornoSite"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="notaVer" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalTitulo">Enviar Nota</h5><span class="badge badge-pill badge-light text-secondary" id="notaId"></span>
                            </div>
                            <div class="modal-body">
                                <div class="row p-2">
                                    <div class="col"><span class="text-muted font-weight-bold">Arquivos permitidos: PDF, JPEG, JPG e PNG <i class="icon-attach-4 text-primary"></i></span></div>
                                </div>
                                <form id="formFiles" name="formFiles" action="javascript:void(0);" enctype="multipart/form-data">
                                    <div class="row p-2">
                                        <div class="col">
                                            <div class="file-field">
                                                <input class="btn btn-light border text-muted d-inline-block text-truncate" type="file" name="file" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col">
                                            <button class="btn btn-light border text-muted" type="submit"><i class='icon-upload-1 text-primary'></i> Enviar</button>
                                        </div>
                                        <div class="col">
                                            <button type="button" class="btn btn-light border text-muted" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
                                        </div>
                                    </div>
                                </form>
                                <script async src="js/EXT_UPLOAD.js<?php echo $versao; ?>"></script>
                                <div class="row">
                                    <div class="col">
                                        <div class="pl-4 pr-4" id="progressBar"><span></span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="col alert alert-ligth">
                                    <div id="retornoUpload"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </center>

</body>

</html>