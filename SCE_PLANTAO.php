<?php
include "versao.php";
include_once "sc/l_sessao.php";

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
    <script src="js/USUARIO.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><?php echo $sistema; ?></a>
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
            <div class="card border mt-2 mb-2 p-1" style="display: none" id="site_formulario1">
                <div class="card-header font-weight-bold">Pesquisar Usuário</div>
                <div class="row mt-2">
                    <div class="col">
                        <input class="form-control" type="text" id="usrTXT" placeholder="PESQUISAR...">
                    </div>
                    <div class="col">
                        <select class="form-control" id="usrCn"></select>
                    </div>
                </div>
                <div class="row mt-2 mb-2">
                    <div class="col">
                        <button id="btFiltra" class="btn btn-light border"><i class='icon-search-5'></i> Filtrar</button>
                    </div>
                    <div class="col">
                        <button id="btFormNovo" class="btn btn-light border"><i class='icon-plus-circle'></i> Cadastrar Colaborador</button>
                    </div>
                    <div class="col">
                        <button id="btFormTrans" class="btn btn-light border"><i class='icon-loop'></i> Tranferências</button>
                    </div>
                </div>
                <div class="card-footer">
                    <div id="retornoUsuario"></div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="usr_formulario_novo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo">CADASTRO DE PLANTÃO</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <input type="text" class="form-control" id="PCadastroColaborados" placeholder="Pesquisa colaborador...">
                                </div>
                                <div class="col-md mt-1">
                                    <button id="btFiltra" class="btn btn-light border"><i class='icon-search-5'></i> Pesquisar</button>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md bg bg-light rounded border p-1 text-muted m-3 float-left">
                                    <span><i class="icon-user-2"></i> COLABORADOR:</span>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <span class="badge badge-pill badge-light float-left border mt-1 mb-1"><i class="icon-calendar-1 mt-1"></i> DATA</span>
                                    <input type="date" class="form-control" id="PCadastroData" placeholder="Data">
                                </div>
                                <div class="col-md mt-1">
                                    <span class="badge badge-pill badge-light float-left border mt-1 mb-1"><i class="icon-clock-2 mt-1"></i> HORA</span>
                                    <input type="time" class="form-control" id="PCadastroHora" placeholder="Hora">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <button class="btn btn-light border text-muted" id="bt_cadastro_USUARIO"><i class="icon-ok-circled-1 text-primary"></i> Cadastrar</button>
                                </div>
                                <div class="col-md mt-1">
                                    <button class="btn btn-light border text-muted" id="bt_cadastro_USUARIO_voltar" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="ModalRetorno_cadastro"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-danger" style="display:none" role="alert" id="erro"></div>
            <div class="alert alert-warning" style="display:none" role="alert" id="load"></div>
            <div class="alert alert-success" style="display:none" role="alert" id="sucesso"></div>
        </div>
        <div style="display: none;" id="ListaUSUARIO"></div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_frota" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Editar Frota</h5>
                    </div>
                    <div class="modal-body m-1">
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 align-middle bg-light border border rounded font-weight-bold">Colaborador: <span id="frotaAtualColaborador"></span> - <span id="frotaAtualRe"></span></div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 align-middle bg-info border border rounded font-weight-bold text-white">Alterar/Corrigir KM atual do veículo</div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 bg-light p-1 border border rounded">Veículo atual: <span id="frotaAtual"></span> - KM Atual: <span id="frotaAtualKm"></span></div>
                            <div class="col-md ml-md-1 mt-1 p-1 bg-light border border rounded">KM Corrigido: <input class="form-control" id="frotaNovoKm" placeholder="KM"></div>
                            <div class="frotaFerramenta col-md ml-md-1 mt-1 p-1 bg-light border border rounded d-none">
                                <button class="btn btn-info border mt-1" id="btFrotaAlteraKm"><i class='icon-exchange'></i> Alterar Km</button>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="frotaFerramenta col-md mt-1 p-1 bg-light border border rounded d-none">
                                <button class="btn btn-light border mt-1 text-danger" id="btFrotaRemove"><i class='icon-minus-circle'></i> Remover veículo atual</button>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 align-middle bg-dark border border rounded font-weight-bold text-white">Alterar veículo</div>
                        </div>
                        <div class="frotaFerramenta row mt-1-sm d-none">
                            <div class="col-md mt-1 p-1 align-middle bg-light border border rounded">
                                <input class="form-control" id="frotaTXT" placeholder="Pesquisar Novo Veículo...">
                            </div>
                            <div class="col-md ml-md-1 mt-1 p-1 align-middle bg-light border border rounded">
                                <button class="btn btn-light border mt-1" id="btfrotaPesquisa"><i class="icon-search-1"></i> Pesquisar</button>
                                <button class="btn btn-light border mt-1" id="btfrotaNova"><i class="icon-plus-circle"></i> Novo</button>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 align-middle">
                                <div id="listaFrotaPesquisa" class="table-responsive mt-3"></div>
                            </div>
                        </div>
                        <div id="rowFrotaResult">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 bg-light p-1 border border rounded">
                                    <span id="frotaPlaca"></span> <span id="frotaDados"></span>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 bg-light p-1 border border rounded">
                                    Colaborador: <span id="frotaNome"></span>
                                </div>
                                <div class="col-md ml-md-1 mt-1 p-1 bg-light border border rounded">
                                    Coordenador: <span id="frotaCoordenador"></span>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <button type="button" class="btn btn-sm btn-outline-success" id="btFrotaAtribui"><i class='icon-exchange'></i> Atribuir</button>
                                </div>
                            </div>
                        </div>
                        <div id="rowFrotaNovo">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 bg-light p-1 border border rounded font-weight-bold text-muted">
                                    Preencha os dados abaixo corretamente para cadastrar um novo veículo
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 bg-light p-1 border border rounded">
                                    <select class="form-control" id="formFrotaModelo"></select>
                                </div>
                                <div class="col-md-3 ml-md-1 mt-1 p-1 bg-light border border rounded">
                                    <input class="form-control" id="formFrotaNovoPlaca" maxlength="7" placeholder="PLACA">
                                </div>
                                <div class="col-md-2 ml-md-1 mt-1 p-1 bg-light border border rounded">
                                    <input class="form-control" id="formFrotaKm" placeholder="KM">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <button type="button" class="btn btn-sm btn-outline-info" id="btfrotaFormCadastra"><i class='icon-ok-circle'></i> Cadastra</button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-md mt-1">
                                <button type="button" id="btFechaFrota" class="btn btn-sm btn-outline-secondary" data-dismiss="modal"><i class="icon-cancel-circled-1"></i> Fechar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="ModalRetorno_frota"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_cartao" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Editar Cartão</h5>
                    </div>
                    <div class="modal-body m-1">
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 align-middle bg-light border border rounded">Colaborador: <span id="cartaoAtualColaborador"></span> - <span id="cartaoAtualRe"></span></div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 bg-light p-1 border border rounded">Cartão Atual: <span id="cartaoAtual"></span></div>
                            <div class="cartaoFerramenta col-md ml-md-1 mt-1 p-1 bg-light border border rounded d-none"><button class="btn btn-sm btn-outline-danger" id="btCartaoRemove"><i class='icon-minus-circle'></i> Remover</button></div>
                        </div>
                        <div class="cartaoFerramenta row mt-1-sm d-none">
                            <div class="col-md mt-1 p-1 align-middle bg-light border rounded">
                                <select class="form-control" id="cartaoNovoselect"></select>
                            </div>
                            <div class="col-md ml-md-1 mt-1 p-1 align-middle bg-light border border rounded">
                                <button class="btn btn-sm btn-outline-info" id="btCartaoAtribui">Atribuir</button>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 align-middle bg-light border border rounded">Alterar</div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md-4 mt-1">
                                <input class="form-control" placeholder="NOVO CARTÃO" maxlength="6" type="number" id="cartaoNovo">
                            </div>
                            <div class="col-md mt-1">
                                <input class="form-control" placeholder="MOTIVO DA TROCA" maxlength="32" type="text" id="cartaoMotivoTroca">
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <button type="button" class="btn btn-success" id="btCartaoAltera">Alterar</button>
                            </div>
                            <div class="col-md mt-1">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="ModalRetorno_cartao"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_edita" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Editar usuário</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1-sm">
                            <div class="col-md-2 mt-1">
                                <select class="form-control" id="editaEstado"></select>
                            </div>
                            <div class="col-md-2 mt-1">
                                <select class="form-control" type="text" id="editaCN"></select>
                            </div>
                            <div class="col-md-2 mt-1">
                                <input class="form-control" disabled placeholder="RE" maxlength="12" id="editaRe">
                            </div>
                            <div class="col-md mt-1">
                                <input class="form-control" placeholder="NOME" maxlength="64" id="editaNome">
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <input class="form-control" placeholder="E-MAIL" maxlength="64" id="editaEmail">
                            </div>
                            <div class="col-md-3 mt-1">
                                <input class="form-control" placeholder="TELEFONE" maxlength="12" id="editaTelefone">
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <select class="form-control" type="text" id="editaCargo"></select>
                            </div>
                            <div class="col-md mt-1">
                                <select class="form-control" type="text" id="editaCoordenador"></select>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <select class="form-control" type="text" id="editaAcesso"></select>
                            </div>
                            <div class="col-md mt-1">
                                <select class="form-control" type="text" id="editaAtivo"></select>
                            </div>
                            <div class="col-md mt-1">
                                <input class="form-control" placeholder="NOVA SENHA" type="password" maxlength="64" id="editaSenha">
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <button class="btn btn-success" id="bt_edita_USUARIO">Editar</button>
                            </div>
                            <div class="col-md mt-1">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
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
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_transferencia" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Colaboradores recebidos <span class="badge badge-pill badge-light text-secondary" id="colaboradorNumeroTransferencia"></span></h5>
                    </div>
                    <div class="modal-body ml-2 mr-2">
                        <div class="row mt-2-sm">
                            <div class="col">
                                <div id="colaboradorTransferenciaLista"></div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="ModalRetornoTransferencia"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_senha" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Resetar senha</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-1">
                            <div class="col bg-light border rounded ml-3 mr-3 pb-1 font-weight-bold text-muted">
                                COLABORADOR: <span id="modalSenha_nome"></span> RE: <span id="modalSenha_re"></span>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col bg-light border rounded ml-3 mr-3 pb-1 font-weight-bold text-muted">
                                E-mail cadastrado: <span class="text-danger" id="modalSenha_email"></span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <button class="btn btn-danger" id="senhaEditaConfirma"><i class='icon-exchange'></i><i class='icon-key-outline'></i> Confirmar</button>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="ModalRetornoEditaSenha"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </center>

</body>

</html>