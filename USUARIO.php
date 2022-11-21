<?php
include "versao.php";
include_once "sc/l_sessao.php";

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
    <link rel="stylesheet" href="css/menus.css<?php echo $versao; ?>">
    <link rel="stylesheet" href="css/all.css<?php echo $versao; ?>">
    <script src="js/js_menu.js<?php echo $versao; ?>"></script>
    <script src="js/USUARIO.js<?php echo $versao; ?>"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">ADM</span></div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-toggle="#navbarResponsive" aria-bs-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
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
                <div class="card-header fw-bold">Pesquisar Usuário</div>
                <div class="row mt-2">
                    <div class="col">
                        <input class="form-control" type="text" id="usrTXT" placeholder="PESQUISAR...">
                    </div>
                    <div class="col">
                        <select class="form-control" id="usrCn"></select>
                    </div>
                </div>
                <div class="row mt-2 mb-2">
                    <div class="col mt-1">
                        <button id="btFiltra" class="btn btn-light border"><i class='icon-search-5'></i> Filtrar</button>
                    </div>
                    <div class="col mt-1">
                        <button id="btFormNovo" class="btn btn-light border"><i class='icon-plus-circle'></i> Cadastrar Colaborador</button>
                    </div>
                    <div class="col mt-1">
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
                            <h5 class="modal-title" id="ModalTitulo">Novo Usuário</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <select class="form-control" id="usr_estado"> </select>
                                </div>
                                <div class="col-md mt-1">
                                    <select class="form-control" id="usr_cn"></select>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md-2 mt-1">
                                    <input class="form-control" type="text" maxlength="7" id="usr_re" placeholder="RE">
                                </div>
                                <div class="col-md mt-1">
                                    <input class="form-control" maxlength="128" type="text" id="usr_nome" placeholder="NOME">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <select class="form-control" id="usr_combustivel">
                                        <option value="0">UTILIZA CARTÃO DE COMBUSTÍVEL ?</option>
                                        <option value="1">NÃO</option>
                                        <option value="2">SIM</option>
                                    </select>
                                </div>
                                <div class="col-md mt-1">
                                    <input class="form-control" type="text" id="usr_cartao" placeholder="CARTÃO(CONTROLE)" maxlength="6">
                                </div>
                                <div class="col-md mt-1">
                                    <input class="form-control" maxlength="64" type="text" id="usr_email_cad" placeholder="E-MAIL">
                                </div>
                                <div class="col-md-3 mt-1">
                                    <input class="form-control" maxlength="12" type="text" id="usr_telefone" placeholder="TELEFONE">
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <select class="form-control" id="usr_cargo"></select>
                                </div>
                                <div class="col-md mt-1">
                                    <select class="form-control" id="usr_acesso">
                                        <option value="0">ACESSO AO SISTEMA</option>
                                        <option value="1">NÃO</option>
                                        <option value="2">SIM</option>data
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <select class="form-control" id="usr_coordenador"></select>
                                </div>
                                <div class="col-md mt-1">
                                    <select class="form-control" id="usr_gestao"></select>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <button class="btn btn-light border text-muted" id="bt_cadastro_USUARIO"><i class='icon-ok-circled text-success'></i> Cadastrar</button>
                                </div>
                                <div class="col-md mt-1">
                                    <button class="btn btn-light border text-muted" id="bt_cadastro_USUARIO_voltar" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
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
            <div style="display: none;" id="ListaUSUARIO" class="table-responsive-sm rounded"></div>
        </div>
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
                                    <button type="button" class="btn btn-sm btn-outline-info" id="btfrotaFormCadastra"><i class='icon-ok-circle'></i> Cadastrar</button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-md mt-1">
                                <button type="button" id="btFechaFrota" class="btn btn-light text-muted border" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
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
                        <h5 class="modal-title" id="ModalTitulo">Editar Cartão: <span id="cartaoAtual"></span></h5>
                    </div>
                    <div class="modal-body m-1">
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1 align-middle rounded badge badge-pill badge-light text-secondary">Colaborador: <span id="cartaoAtualColaborador"></span> - <span id="cartaoAtualRe"></span></div>
                        </div>
                        <div class="row mt-1-sm mb-1">
                            <div class="cartaoFerramenta col-md ml-md-1 mt-1 p-1 bg-light border border rounded"><button class="btn btn-sm btn-outline-danger" id="btCartaoRemove"><i class='icon-minus-circle'></i> Remover</button></div>
                        </div>
                        <div class="accordion" id="accordionExample2">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Solicitar desbloqueio
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample2">
                                    <div class="accordion-body">
                                        <div class="row mt-1-sm">
                                            <div class="col-md">
                                                <span class="badge badge-pill badge-light text-secondary"> Após a solicitação o coordenador administrativo irá realizar o desbloqueio.</span>
                                            </div>
                                            <div class="col-md mb-1">
                                                <button class="btn btn-light text-muted border" id="btCartaoDesbloqueio"><i class="icon-lock-open text-success"></i> Solicitar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Alterar/Adicionar Cartão
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample2">
                                    <div class="accordion-body">
                                        <div class="row mt-1-sm">
                                            <div class="col-md-4 mt-1">
                                                <input class="form-control" placeholder="NOVO CARTÃO" maxlength="6" type="number" id="cartaoNovo">
                                            </div>
                                            <div class="col-md mt-1">
                                                <input class="form-control" placeholder="MOTIVO DA TROCA" maxlength="32" type="text" id="cartaoMotivoTroca">
                                            </div>
                                            <div class="col-md mt-1">
                                                <button class="btn btn-sm btn-primary" id="btCartaoAltera"><i class="icon-link"></i> Alterar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Atribuir cartão já cadastrado
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample2">
                                    <div class="accordion-body">
                                        <div class="row mt-1-sm">
                                            <div class="col-md p-1 align-middle">
                                                <select class="form-select" id="cartaoNovoselect"></select>
                                            </div>
                                            <div class="col-md ml-md-1 mt-1 p-1 align-middle">
                                                <button class="btn btn-sm btn-primary" id="btCartaoAtribui"><i class="icon-link"></i> Atribuir</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <button type="button" class="btn btn-light text-muted border" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
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
                            <div class="col-md mt-1">
                                <select class="form-control" type="text" id="editaGestao"></select>
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
                                <button class="btn btn-sm btn-success" id="bt_edita_USUARIO"><i class="icon-edit"></i> Salvar alterações</button>
                            </div>
                            <div class="col-md mt-1">
                                <button type="button" class="btn btn-sm btn-danger" id="bt_modal_permissao"><i class="icon-lock-1"></i> Permissões</button>
                            </div>
                            <div class="col-md mt-1">
                                <button type="button" class="btn btn-light text-muted border" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
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
        <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="Modal_permissao" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalTitulo">Definição de permissões</h5>
                    </div>
                    <div class="modal-body">
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-bs-controls="collapseOne">
                                            Atribuir
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="row mt-1-sm">
                                            <div class="col-md mt-1">
                                                <select id="formPermissaoTipo" class="form-control"></select>
                                            </div>
                                            <div class="col-md mt-1">
                                                <select id="formPermissaoPagina" style="display:none" class="form-control"></select>
                                                <select id="formPermissaoFuncao" style="display:none" class="form-control"></select>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col mt-2" id="div_bt_add" style="display:none">
                                                <button class="btn btn-sm btn-success" id="formPermissaoADD"><i class="icon-lock-open-1"></i> Adicionar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card border-0 mt-1">
                                <div class="card-header border" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-bs-controls="collapseTwo">
                                            Atribuídas
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="row mt-2">
                                            <div class="row" class="mt-3">
                                                <div class="col" id="Permissoes"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <button type="button" class="btn btn-light text-muted border" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col alert alert-ligth">
                            <div id="retornoPermissao"></div>
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
                                <button type="button" class="btn btn-light text-muted border" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
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
                                <button type="button" class="btn btn-light text-muted border" data-bs-dismiss="modal"><i class="bi bi-x"></i> Fechar</button>
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