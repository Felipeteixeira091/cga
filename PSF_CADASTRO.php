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
    <script src="js/PSF_CADASTRO.js<?php echo $versao; ?>"></script>
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
            <div id="pgSistema" class="rounded"><span class="navbar-brand">PSF</span></div>
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
        <div style="margin: 1px">
            <div class="container theme-showcase" role="main">
                <div class="objeto m-2">
                    <div id="notaOpc" class="card border mt-2 p-1">
                        <div class="card-header font-weight-bold">Notas</div>
                        <div class="card-body">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 mb-2">
                                    <button class="btn btn-light text-muted border" id="btNova"><i class="icon-plus-circle-1 text-danger"></i> Novo sinistro</button>
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
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">SINISTRO</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">ANEXO</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">OBSERVAÇÕES</a>
                                    </li>
                                </ul>
                                <div class="modal-header">
                                <span class="badge badge-secondary" id="id"></span>
                                </div>
                                <div class="modal-body">
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                            <div class="row mt-1-sm">
                                                <div class="col-md mt-1">
                                                    <select id="tipo" class="form-control"></select>
                                                </div>
                                                <div class="col-md mt-1">
                                                    <select id="veiculo" class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="row mt-1-sm">
                                                <div class="col-md mt-1">
                                                    <input type="date" id="psfData" class="form-control" placeholder="DATA">
                                                </div>
                                                <div class="col-md mt-1">
                                                    <input type="time" id="psfHora" class="form-control" placeholder="HORA">
                                                </div>
                                            </div>
                                            <div class="row mt-1-sm" id="rowObs">
                                                <div class="col-md mt-1">
                                                    <textarea class="form-control" id="obs" style="resize: none" placeholder="OBSERVAÇÕES..."></textarea>
                                                </div>
                                            </div>
                                            <div class="row mt-1-sm">
                                                <div class="col-md mt-1">
                                                    <button class="btn btn-light text-muted border" id="btCadastro"><i class="icon-ok-circled-1 text-success"></i> Cadastrar</button>
                                                </div>
                                                <div class="col-md mt-1">
                                                    <button type="button" class="btn btn-light text-muted border" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="col alert alert-ligth">
                                        <div id="retornoCadastro"></div>
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