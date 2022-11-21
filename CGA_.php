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

    <script src="js/js_menu.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/menu.css<?php echo $versao; ?>">
    <link rel="stylesheet" href="css/all.css<?php echo $versao; ?>">
    <script src="js/CGA.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">CEP</span></div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto" id="nav">
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid" style="margin-top: 70" role="main">
        <div class="card text-center">
            <div class="header fw-bold">
                Carimbo
            </div>
            <div class="card-body">
                <h5 class="card-title">Gerar informação padronizada</h5>
                <h6 class="card-subtitle mb-2 text-muted ">Clique no botão abaixo para abrir o formulário</h6>
                <button class="btn btn-primary btn-sm " role="button" id="btn_open_modal_stamp"><i class="bi bi-blockquote-left"></i> Gerar novo carimbo</button>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="modal_new_stamp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitle">Criação de carimbo para atualização</h5>
                    </div>
                    <div class="modal-body">
                        <span class="text-muted">Preencha todas as informação abaixo conforme solicitado.</span>
                        <form>
                            <div class="row mb-3">
                                <div class="col alert alert-danger fw-bold">A data de atualização será automática</div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="input_ta" placeholder="Somente número" />
                                <label for="floatingInput">Informe o número do TA</label>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col">
                                    <select class="form-select" id="input_select_status">
                                        <option aria-checked="">Selecione</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <button class="btn btn-primary" type="button" id="btn_search_tec">
                                            <i class="bi bi-search"></i> <i class="bi bi-person-plus-fill"></i> Técnico <span id="span_re_tec"></span>
                                        </button>
                                        <input type="text" class="form-control" placeholder="" aria-describedby="button-addon1" id="input_name_tec" value="" />
                                    </div>
                                    <div id="tableTec" class="table-responsive mt-3"></div>
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="floatingInput" disabled placeholder="Nome GA" value="<?php echo $_SESSION['nome']; ?>" />
                                        <label for="floatingInput">Nome do GA</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" style="resize:none"></textarea>
                                        <label for="floatingInput">Atualização do TA</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary shos">Gerar</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="modal_return" class="text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>