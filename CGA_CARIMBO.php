<?php
include_once "sc/l_sessao.php";
include "versao.php";
?>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo $sistema ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <link rel="icon" href="css/ico.png">
    <link rel="stylesheet" href="css/all.css<?php echo $versao; ?>">

    <script src="js/js_menu.js<?php echo $versao; ?>"></script>
    <script src="js/CGA_CARIMBO.js<?php echo $versao; ?>"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="bi bi-postage-fill"></i> CGA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">
                        <a class="nav-link active" aria-current="page" href="Index"><i class="bi bi-house-fill text-info"></i> Início</a>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <div id="menuCanva"></div>
                        <li class="nav-item mt-3">
                            <a class="nav-link" id="btLogOut" href="#"><i class="bi bi-box-arrow-right text-danger"></i> Sair</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="container" style="margin-top: 70" role="main">
        <div class="card text-center w-70">
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
                        <div class="row mb-1">
                            <div class="col">
                                <div class="bg bg-light rounded border p-1 mt-1 mb-1">GA: <span id="span_ga_name"><?php echo $_SESSION['nome']; ?></span> <span id="span_ga_re"><?php echo $_SESSION['re']; ?></span></div>
                            </div>
                        </div>
                        <span class="text-muted">Preencha todas as informação abaixo conforme solicitado.</span>
                        <div id="modal_return" class="text-center"></div>
                        <div class="row">
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="input_ta" placeholder="Somente número" />
                                    <label for="input_ta">Informe o número do TA<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="input_select_status">
                                        <option aria-checked="">Selecione</option>
                                    </select>
                                    <label for="input_select_status">Selecione o status<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="input_os" placeholder="Somente número" />
                                    <label for="input_os">Número OS</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col">
                                <div class="input-group mb-1">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" aria-describedby="button-addon1" id="input_name_tec">
                                        <label for="input_name_tec">Pesquisar por nome...</label>
                                    </div>
                                    <button class="btn btn-primary" type="button" id="btn_search_tec">
                                        <i class="bi bi-search"></i> Pesquisar
                                    </button>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating">
                                    <span readonly class="form-control-plaintext">
                                        <small>
                                            <span id="span_tec_name"></span> <span id="span_tec_re"></span>
                                        </small>
                                    </span>
                                    <label for="floatingEmptyPlaintextInput">Técnico</label>
                                </div>
                            </div>
                        </div>
                        <div id="tableTec" class="table-responsive mt-1"></div>
                        <div class="row mb-1">
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input id="input_prediction" type="datetime-local" class="form-control">
                                    <label for="floatingInput">Previsão<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="input_select_responsibility">
                                        <option aria-checked="">Selecione</option>
                                        <option value="1">ICOMON</option>
                                        <option value="2">VIVO</option>
                                    </select>
                                    <label for="input_select_status">Responsabilidade<span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" style="resize:none" id="input_note"></textarea>
                                    <label for="floatingInput">Atualização do TA</label>
                                </div>
                            </div>
                            <div class="col-2">
                                <button type="button" id="btn_generate" class="btn btn-primary float-end">Gerar</button>
                            </div>
                        </div>
                        <div class=" row mb-1">
                            <div class="col">
                                <div class="shadow p-3 mb-3 mt-2 bg-body rounded" role="alert">
                                    <h5 class="alert-heading">Carimbo gerado</h5>
                                    <hr>
                                    <textarea disabled class="form-control" style="resize:none" id="output_text"></textarea>
                                    <hr>
                                    <button class="btn btn-primary" onclick="GeeksForGeeks()">Copiar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        function GeeksForGeeks() {
            document.getElementById("output_text").disabled = false;

            var copyGfGText = document.getElementById("output_text");
            copyGfGText.select();
            document.execCommand("copy");

            alert("informações copiadas com sucesso!");

            document.getElementById("output_text").disabled = true;

            window.location.reload();
            textarea.value = '';
        }
    </script>
</body>

</html>