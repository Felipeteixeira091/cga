<?php

include_once("sc/l_sessao.php");
include "versao.php";
?>
<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <title>ICOMON</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

    <link rel="icon" href="css/ico.png">
    <link rel="stylesheet" href="css/menus.css<?php echo $versao; ?>">
    <link rel="stylesheet" href="css/all.css<?php echo $versao; ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/js_index2.js<?php echo $versao; ?>"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <style>
        .custom {
            width: 280px !important;
        }

        .customBt {
            min-width: 150px !important;
        }
    </style>
</head>

<body>
    <center>
        <div class="container mb-5" style="margin-top: 2px" role="main">
            <div class="card">
                <div class="card-header bg bg-dark text-white font-weight-bold shadow">
                    <span class="text-muted">
                        <small>
                            <?php echo explode(" ", $_SESSION['nome'])[0] . " " . explode(" ", $_SESSION['nome'])[1] . " " . $_SESSION['re'] ?>
                        </small>
                    </span>
                    <hr>
                    <div class="mt-1">
                        <button class="btn btn-dark btn-sm text-muted float-right" id="btFormSenha"><i class="bi bi-key-fill text-warning"></i> Alterar Senha</button>
                        <button class="btn btn-dark btn-sm text-muted float-right" id="btFormDados"><i class="bi bi-person-circle"></i> Meus dados</button>
                        <button class="btn btn-dark btn-sm text-muted float-end" id="btLogOut"><i class="bi bi-box-arrow-right text-danger"></i> </span> Sair</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="menuGERAL">


                        <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Backdrop with scrolling</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <p>Try scrolling the rest of the page to see this option in action.</p>
                            </div>
                        </div>
                        <div class="row mt-2" id="acesso"></div>
                    </div>
                </div>
                <div class="card-footer">
                    <div id="retornoIndex"></div>
                </div>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="modalSub" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="icon-menu-outline text-muted"></i> <span id="ModalTitulo"></span></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 mb-1 bg bg-ligth">
                                <div id="ModalDesc"></div>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 mb-1 bg bg-ligth">
                                <div id="subMenu"></div>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <button type="button" class="btn btn-light btn-sm text-muted border float-lg-right" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="retornoMenu"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="modalTutorial" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo"><i class="icon-info-1 text-success"></i> Dúvidas frequentes</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <span class='text-muted'>Abaixo temos informações sobre algumas das principais dúvidas.</span>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <a href="download/Orientações Basicas de Excel.pdf" target="_blank"><button type="button" class="btn btn-md btn-light custom text-left text-muted"><span><i class="icon-question text-primary"></i> Orientações básicas de <span class='text-success'>Excel</span></span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <a href="download/Config_Email_Quiosque_POP.DOCX" target="_blank"><button type="button" class="btn btn-md btn-light custom text-left text-muted"><span><i class="icon-question text-primary"></i> Configurar e-mail</span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <a href="download/Gerar senha de aplicativo.pptx" target="_blank"><button type="button" class="btn btn-md btn-light custom text-left text-muted"><span><i class="icon-question text-primary"></i> Gerar senha de aplicativo</span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <a href="download/Cadastrar novo colaborador.pptx" target="_blank"><button type="button" class="btn btn-md btn-light custom text-left text-muted"><span><i class="icon-question text-primary"></i> Novo colaborador</span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <a href="download/Atribuir veículo ao colaborador.pptx" target="_blank"><button type="button" class="btn btn-md btn-light custom text-left text-muted"><span><i class="icon-question text-primary"></i> Atribuir veículo</span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <a href="download/Atribuir NOVO veículo ao colaborador.pptx" target="_blank"><button type="button" class="btn btn-md btn-light custom text-left text-muted"><span><i class="icon-question text-primary"></i> Atribuir novo veículo</span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <a href="download/Alterar cartão colaborador.pdf" target="_blank"><button type="button" class="btn btn-md btn-light custom text-left text-muted"><span><i class="icon-question text-primary"></i> Novo cartão ao colaborador</span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <a href="download/Processo_de_Acesso_ao_SCE.pptx" target="_blank"><button type="button" class="btn btn-md btn-light custom text-left text-muted"><span><i class="icon-question text-primary"></i> Tutorial geral</span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <button type="button" class="btn btn-light btn-sm text-muted border float-lg-right" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
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
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="modalAlteraSenha" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo"><i class="icon-exchange text-muted"></i> Alterar Senha</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="opcAt btn btn-danger">
                                        <input type="radio" disabled name="opcAt" value="0" checked>TIPO DE SENHA
                                    </label>
                                    <label class="btn btn-light border">
                                        <input type="radio" name="opcAt" value="oem">SISTEMA ICOMON
                                    </label>
                                    <label class="btn btn-light border">
                                        <input type="radio" name="opcAt" value="outlook">OUTLOOK
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <input class="form-control" placeholder="NOVA SENHA" type="password" id="senha1">
                            </div>
                            <div class="col-md mt-1">
                                <input class="form-control" placeholder="REPETIR SENHA" type="password" id="senha2">
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <button class="btn btn-light text-muted border" id="btAlteraSenha"><i class="icon-exchange text-success"></i> Alterar</button>
                            </div>
                            <div class="col-md mt-1">
                                <button type="button" class="btn btn-light text-muted border" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="retornoTrocaSenha"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-muted" id="ModalTitulo"><i class="icon-user text-info"></i> Meus dados</h5>
                    </div>
                    <div class="modal-body">
                        <div class="container text-center">
                            <div class="row">
                                <div class="col bg bg-light border rounded mb-1">
                                    <span class='font-weight-bold'>NOME:</span> <small><span id="dadosNOME"></span></small>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 bg bg-light border rounded">
                                    <span class='font-weight-bold'>RE:</span> <span id="dadosRE"></span>
                                </div>
                                <div class="col-md mt-1 bg bg-light border ms-md-1 rounded">
                                    <span class='font-weight-bold'>CN:</span> <span id="dadosCN"></span>
                                </div>
                                <div class="col-md mt-1 bg bg-light border ms-md-1 rounded">
                                    <span class='font-weight-bold'>UF:</span> <span id="dadosUF"></span>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 bg bg-light border rounded">
                                    <span class='font-weight-bold'><i class="bi bi-mailbox"></i> :</span> <span id="dadosEMAIL"></span>
                                </div>
                                <div class="col-md mt-1 bg bg-light border ms-md-1 rounded">
                                    <span class='font-weight-bold'><i class="bi bi-phone"></i> :</span> <span id="dadosTELFONE"></span>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 bg bg-light border rounded">
                                    <span class='font-weight-bold'>CARGO:</span> <span id="dadosCARGO"></span>
                                </div>
                                <div class="col-md mt-1 bg bg-light border  ms-md-1 rounded">
                                    <span class='font-weight-bold'>COORDENADOR:</span> <span id="dadosCOORDENADOR"></span>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 bg bg-light border rounded">
                                    <span class='font-weight-bold'><i class="bi bi-credit-card"></i> :</span> <span id="dadosCARTAO"></span>
                                </div>
                                <div class="col-md mt-1 bg bg-light border  ms-md-1 rounded">
                                    <span class='font-weight-bold'><i class="bi bi-car-front-fill"></i> :</span> <span id="dadosVEICULO"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1-sm ml-1 mr-1">
                            <div class="col-md mt-1 rounded p-1">
                                <button type="button" class="btn btn-light btn-sm text-muted border" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="retornoTrocaSenha"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="linktr" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo"><i class="icon-tree text-success"></i></h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <a href="https://www.linkedin.com/in/felipe-teixeira-84b864140/" target="_blank"><button type="button" class="btn btn-lg btn-light custom text-left text-muted"><span><i class="bi bi-linkedin"></i> Linkedin</span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <a href="https://api.whatsapp.com/send?phone=5531975271101" target="_blank"><button type="button" class="btn btn-lg btn-light custom text-left text-muted"><span><i class="bi bi-whatsapp"></i> <small> 31 9 7527-1101</small></span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <a href="mailto:felipeteixeira091@gmail.com"><button type="button" class="btn btn-lg btn-light custom text-left text-muted"><span><i class="bi bi-envelope-fill"></i> <small>felipeteixeira091@gmail.com</small></span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <button type="button" class="btn btn-light btn-sm text-muted border float-lg-right" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
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
    </center>
    <footer class="text-center mb-1">
        <small class="text-muted">ICOMON © Todos os direitos reservados. Desenvolvido por <a id="btlinktr" href="#"> Felipe Teixeira <i class="bi bi-linkedin"></i></a></small>
    </footer>
</body>

</html>