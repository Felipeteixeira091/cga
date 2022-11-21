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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="js/popper.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <link rel="icon" href="css/ico.png">
    <link rel="stylesheet" href="css/menus.css<?php echo $versao; ?>">
    <link rel="stylesheet" href="css/all.css<?php echo $versao; ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/js_index.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">
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
        <div class="container theme-showcase" style="margin-top: 2px" role="main">
            <div class="card">
                <div class="card-header bg bg-dark text-white font-weight-bold">
                    <span class="float-left d-none d-lg-block">ICOMON O&M</span>
                    <span class="text-light"><small><i class="icon-user text-info"></i> <?php echo explode(" ", $_SESSION['nome'])[0] . " " . explode(" ", $_SESSION['nome'])[1] . " " . $_SESSION['re'] ?></small></span>
                    <button class="btn btn-dark btn-sm text-muted float-right" id="btLogOut"><i class="icon-logout-1 text-danger"></i> Sair</button>
                    <button class="btn btn-dark btn-sm text-muted float-right" id="btFormSenha"><i class="icon-key-outline text-warning"></i> Alterar Senha</button>
                </div>
                <div class="card-body">
                    <div id="menuCGA" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col bg bg-dark text-white border rounded">
                                CGA
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-muted text-center">
                                <small>Geração de carimbo Sigitm.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col bg rounded">
                                <div id="pagCGA">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menuGERAL">
                        <div class="row">
                            <div class="col bg bg-dark text-white border rounded">
                                Gerenciamento
                            </div>
                        </div>
                        <div class="row">
                            <div class="col bg rounded">
                                <div id="pagGERAL">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menuSCP" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col bg bg-dark text-white border rounded">
                                SCP
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-muted text-center">
                                <small>Solicitação de correção de ponto.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col bg rounded">
                                <div id="pagSCP">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menuPSF" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col bg bg-dark text-white border rounded">
                                PSF
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-muted text-center">
                                <small>Portal de sinistro de frota.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col bg rounded">
                                <div id="pagPSF">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menuSCE" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col bg bg-dark text-white border rounded">
                                SCE
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-muted text-center">
                                <small>Solicitação de combustivel.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col bg rounded">
                                <div id="pagSCE">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menuSMA" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col bg bg-dark text-white border rounded">
                                SMA
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-muted text-center">
                                <small>Solicitação de material.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col bg rounded">
                                <div id="pagSMA">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menuVFB" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col bg bg-dark text-white border rounded">
                                VFB
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-muted text-center">
                                <small>Vistoria fatura B.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col bg rounded">
                                <div id="pagVFB">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menuCEP" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col bg bg-dark text-white border rounded">
                                CEP
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-muted text-center">
                                <small>Cadastro de elementos.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col bg rounded">
                                <div id="pagCEP">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menuSGA" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col bg bg-dark text-white border rounded">
                                SGA
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-muted text-center">
                                <small>Descarte de ítens.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col bg rounded">
                                <div id="pagSGA">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menuEXT" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col bg bg-dark text-white border rounded">
                                EXTRA FOLHA
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-muted text-center">
                                <small>Cadastro de notas para reembolso.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col bg rounded">
                                <div id="pagEXT">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menuCCC" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col bg bg-dark text-white border rounded">
                                Controle de custo cartão corporativo
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-muted text-center">
                                <small><i class='icon-credit-card'></i> <i class='icon-chart-bar text-info'></i></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col bg rounded">
                                <div id="pagCCC">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menuGAS" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col bg bg-dark text-white border rounded">
                                GÁS
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-muted text-center">
                                <small>Registro de utilização de Gás.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col bg rounded">
                                <div id="pagGAS">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menuGMG" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col bg bg-dark text-white border rounded">
                                GMG
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-muted text-center">
                                <small>Registro de acoplamento de GMG.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col bg rounded">
                                <div id="pagGMG">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menuSBO" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col bg bg-dark text-white border rounded">
                                SBO
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-muted text-center">
                                <small>Registro de Boletins de ocorrência.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col bg rounded">
                                <div id="pagSBO">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div id="retornoIndex"></div>
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
                                <button type="button" class="btn btn-light btn-sm text-muted border float-lg-right" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
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
                                <button type="button" class="btn btn-light text-muted border" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
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
                        <div class="row mt-1-sm m-1">
                            <div class="col-md-2 mt-1 bg bg-light border rounded p-1 text-muted">
                                <span class='font-weight-bold'>UF:</span> <span id="dadosUF"></span>
                            </div>
                            <div class="col-md-2 mt-1 ml-md-1 bg bg-light border rounded p-1 text-muted">
                                <span class='font-weight-bold'>CN:</span> <span id="dadosCN"></span>
                            </div>
                            <div class="col-md-2 mt-1 ml-md-1 bg bg-light border rounded p-1 text-muted">
                                <span class='font-weight-bold'>RE:</span> <span id="dadosRE"></span>
                            </div>
                            <div class="col-md mt-1 ml-md-1 bg bg-light border rounded p-1 text-muted">
                                <span class='font-weight-bold'>NOME:</span> <small><span id="dadosNOME"></span></small>
                            </div>
                        </div>
                        <div class="row mt-1-sm ml-1 mr-1">
                            <div class="col-md mt-1 bg bg-light border rounded p-1 text-muted">
                                <span class='font-weight-bold'>E-MAIL:</span> <span id="dadosEMAIL"></span>
                            </div>
                            <div class="col-md-4 mt-1 ml-md-1 bg bg-light border rounded p-1 text-muted">
                                <span class='font-weight-bold'>TELFONE:</span> <span id="dadosTELFONE"></span>
                            </div>
                        </div>
                        <div class="row mt-1-sm ml-1 mr-1">
                            <div class="col-md-5 mt-1 bg bg-light border rounded p-1 text-muted">
                                <span class='font-weight-bold'>CARGO:</span> <span id="dadosCARGO"></span>
                            </div>
                            <div class="col-md mt-1 ml-md-1 bg bg-light border rounded p-1 text-muted">
                                <span class='font-weight-bold'>COORDENADOR:</span> <span id="dadosCOORDENADOR"></span>
                            </div>
                        </div>
                        <div class="row mt-1-sm ml-1 mr-1">
                            <div class="col-md-5 mt-1 bg bg-light border rounded p-1 text-muted">
                                <span class='font-weight-bold'><i class="icon-credit-card text-success"></i>:</span> <span id="dadosCARTAO"></span>
                            </div>
                            <div class="col-md mt-1 ml-md-1 bg bg-light border rounded p-1 text-muted">
                                <span class='font-weight-bold'>VEÍCULO:</span> <span id="dadosVEICULO"></span>
                            </div>
                        </div>
                        <div class="row mt-1-sm ml-1 mr-1">
                            <div class="col-md mt-1 rounded p-1">
                                <button type="button" class="btn btn-light btn-sm text-muted border" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
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
                                <a href="https://www.linkedin.com/in/felipe-teixeira-84b864140/" target="_blank"><button type="button" class="btn btn-lg btn-light custom text-left text-muted"><span><i class="icon-linkedin-1 text-primary"></i> Linkedin</span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <a href="https://api.whatsapp.com/send?phone=5531975271101" target="_blank"><button type="button" class="btn btn-lg btn-light custom text-left text-muted"><span><i class=" icon-phone-1 text-success"></i> <small> 31 9 7527-1101</small></span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <a href="#telegram"><button type="button" class="btn btn-lg btn-light custom text-left text-muted"><span><i class="icon-paper-plane-1 text-info"></i> <small> 31 9 7527-1101</small></span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <a href="mailto:felipeteixeira091@gmail.com"><button type="button" class="btn btn-lg btn-light custom text-left text-muted"><span><i class=" icon-gmail text-danger"></i> <small>felipeteixeira091@gmail.com</small></span></button></a>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <button type="button" class="btn btn-light btn-sm text-muted border float-lg-right" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
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
        <small class="text-muted">ICOMON © Todos os direitos reservados. Desenvolvido por <a id="btlinktr" href="#"> Felipe Teixeira</a></small>
    </center>
</body>

</html>