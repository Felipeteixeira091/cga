<?php
include "./acoes/login/login_verifica.php";
include "versao.php";
?>
<html>

<head>
    <meta charset="UTF-8">
    <title>SCE - <?php echo $_SESSION['empresa']; ?></title>
    <link rel="icon" href="css/gas.png">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="js/popper.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="js/js_menu2.js<?php echo $versao; ?>"></script>
    <script src="js/js_login.js<?php echo $versao; ?>"></script>
    <script src="js/js_objeto_lista.js<?php echo $versao; ?>"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>

    <link type="text/css" rel="stylesheet" href="css/all.css<?php echo $versao; ?>" />

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">
</head>
<script>
    function somenteNumeros(num) {
        var er = /[^0-9.]/;
        er.lastIndex = 0;
        var campo = num;
        if (er.test(campo.value)) {
            campo.value = "";
        }
    }
</script>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#"><?php echo $sistema; ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto" id="nav">
                </ul>
            </div>
        </div>
    </nav>
    <div style="margin: 1px">
        <center>
            <div class="container theme-showcase" role="main">
                <div style="margin: 2px;" class="objeto">
                    <table>
                        <tr class='table-light'>
                            <div style="text-align: center; margin-top: 2%; margin-bottom: 1%; font-weight: bold" id="objetoTitulo">Objetos</div>
                            <div style="text-align: center; margin-top: 2%; margin-bottom: 1%; font-weight: bold" id="objetoXls"></div>
                        </tr>
                    </table>
                </div>
                <div class="solicitacao_filtro">
                    <center><select class="form-control" id="objeto_filtro_tipo"></select></center>
                </div>
                <div style="margin-top: 5px" class="col-12">
                    <button style="display: none;margin-top: 2%;margin-bottom: 2%" class="btn btn-secondary" id="objeto_lista_bt_voltar">Voltar</button>
                </div>
                <div class="alert alert-danger" role="alert" id="erro"></div>
                <div class="alert alert-success" role="alert" id="sucesso"></div>
            </div>
            <div class="table-responsive" style="display: none;" id="objetoColaborador"></div>
            <div style="display: none" class="objetoCartao"></div>
            <div style="display: none" class="objetoVeiculo"></div>
            <div style="display: none" class="objetoFrota"></div>
            <div style="display: none" class="objetoPermissao"></div>
            <div style="display: none" class="objetoPermissao"></div>
            <div class="container theme-showcase" role="main">
                <div class="card" id="formObjetoColaborador" style="display: none">
                    <div class="card-header font-weight-bold text-dark">Editar Colaborador - <span id="edicao_colaborador_re"></span></div>
                    <div class="card-body">
                        <div class="row mt-1-sm">
                            <div class="col">
                                <input class="form-control" type="text" maxlength="63" id="edicao_colaborador_nome" placeholder="NOME">
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <select class="form-control" id="edicao_colaborador_gerencia"></select>
                            </div>
                            <div class="col-md mt-1">
                                <select class="form-control" id="edicao_colaborador_coordenacao"></select>
                            </div>
                            <div class="col-md mt-1">
                                <select class="form-control" id="edicao_colaborador_funcao"></select>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <select class="form-control" id="edicao_colaborador_empresa"></select>
                            </div>
                            <div class="col-md mt-1">
                                <select class="form-control" id="edicao_colaborador_cidade"></select>
                            </div>
                        </div>
                        <div class="row mt-1-sm">
                            <div class="col-md mt-1">
                                <input class="form-control" type="text" maxlength="11" id="edicao_colaborador_Telefone" placeholder="TELEFONE">
                            </div>
                            <div class="col-md mt-1">
                                <input class="form-control" type="text" maxlength="31" id="edicao_colaborador_email" placeholder="E-MAIL">
                            </div>
                            <div class="col-md mt-1">
                                <select class="form-control" id="edicao_colaborador_acesso"></select>
                            </div>
                            <div class="col-md mt-1">
                                <select class="form-control" id="edicao_colaborador_status"></select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <button class="btn btn-success" id="edita_colaborador_bt_edita">Salvar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                    <div id="retornoColaborador"></div>
                    </div>
                </div>
                <div class="card" id="formObjetoCartao" style="display: none">
                    <div class="card-header font-weight-bold text-dark">Editar Cartão - <span id="edicao_cartao_controle"></span></div>
                    <div class="card-body">
                        <div class="row mt-1">
                            <div class="col">
                                <span class="form-control" disabled id="edicao_cartao_colaborador_atual"></span>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col">
                                <select class="form-control" id="edicao_cartao_colaborador"></select>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col">
                                <label>Informe o novo KM abaixo</label>
                                <input class="form-control" id="edicao_cartao_km" type="text" maxlength="9" onkeyup="somenteNumeros(this)">
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col">
                                <button class="btn btn-success" id="edita_cartao_bt_edita">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="formObjetoFrota" style="display: none">
                    <div class="form-row">
                        <div style="margin-top: 5px" class="col-12">
                            <label id="edicao_frota_placa"></label>
                        </div>
                        <div style="margin-top: 5px" class="col-12">
                            <label id="edicao_frota_colaborador_atual"></label>
                            <label id="edicao_frota_veiculo_atual"></label>
                        </div>
                        <div style="margin-top: 5px" class="col-12">
                            <select class="form-control" id="edicao_frota_colaborador"></select>
                        </div>
                        <div style="margin-top: 5px" class="col-12">
                            <select class="form-control" id="edicao_frota_veiculo"></select>
                        </div>
                        <div style="margin-top: 5px" class="col-12">
                            <button class="btn btn-success" id="edita_frota_bt_edita">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </center>
    </div>
</body>

</html>