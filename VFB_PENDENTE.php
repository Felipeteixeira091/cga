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
    <script src="js/VFB_PENDENTE.js<?php echo $versao; ?>"></script>
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

        function LimitaTamanho(campo, limite) {
            valor = eval("document.forms[0]." + campo + ".value");
            limite = parseInt(limite);
            if (valor.length > limite) {
                erroValue = ("O Valor máximo do campo é " + limite + " caracteres.");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">VFB</span></div>
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
                    <div id="vfbListaPendente" style="display:none" class="card border mt-2 p-1">
                        <div class="card-header font-weight-bold">Vistorias Pendentes<span class="badge badge-pill badge-light text-secondary border" id="notaQtd"></span></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div id="notaPendente"></div>
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
                                <h5 class="modal-title" id="ModalTitulo">Informações da vistoria</h5><span class="badge badge-pill badge-light text-secondary" id="vfbId"></span>
                            </div>
                            <div class="modal-body">
                                <div class="row mt-1-sm">
                                    <div class="col mt-1 align-middle">

                                    </div>
                                </div>
                                <div id="accordion">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <h5 class="mb-0">
                                                <button class="btn btn-light btn-sm text-muted border" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    <i class="icon-doc-2 text-primary"></i> Detalhes
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                            <div class="card-body">
                                                <div class="row p-2">
                                                    <div class="col"><span class="text-muted font-weight-bold"><i class="icon-info-1 text-danger"></i> Orientações para vistoria</span></div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Vistoriador:</span> <span id="vfbNomeDetalhe"></span> - <span id="vfbReDetalhe"></span></div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Mão de obra:</span> <span id="vfbMOTipoDetalhe"></span></div>
                                                    <div class="col-md mt-1 ml-1 rounded bg-light border"><span class="font-weight-bold">Executante:</span> <span id="vfbMODetalhe"></span></div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Data:</span> <span id="vfbDataDetalhe"></span> <span id="vfbHoraDetalhe"></span></div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 align-middle bg-light border border rounded"><span class="font-weight-bold">Site:</span> <span id="vfbSiteDetalhe"></span></div>
                                                    <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded"><span class="font-weight-bold">OS Prisma:</span> <span id="vfbOsDetalhe"></span></div>
                                                    <div class="col-md mt-1 ml-md-1 align-middle bg-light border border rounded"><span class="font-weight-bold">Valor da obra:</span> R$ <span id="vfbValorDetalhe"></span></div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Serviço aprovado:</span> <span id="vfbOrientacao"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="form2" class="m-1 border rounded" style="display: none;">
                                        <div class="row mt-2">
                                            <div class="col">
                                                <textarea class="form-control mt-1 mb-1 border border-light" placeholder="Considerações..." id="obs2" style="resize: none"></textarea>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col ml-1 align-middle mb-1">
                                                <span class="d-none" id="idStatus"></span><span class="badge badge-pill badge-light text-secondary mr-2" id="status"></span>
                                                <button type="button" id="btConcluir" class="btn btn-sm btn-light text-muted border"><i class='icon-plane'></i> Enviar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="formularios">
                                        <div class="card mt-1">
                                            <div class="card-header" id="headingTwo">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-light btn-sm text-muted border collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                        <i class="icon-list-2 text-primary"></i> Checklist
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row p-2">
                                                        <div class="col"><span class="text-muted font-weight-bold"><i class="icon-list-2 text-primary"></i> Check list de vistoria</span></div>
                                                    </div>
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Segmento de atividade:</span></div>
                                                    </div>
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1 rounded bg-light border">
                                                            <input type="radio" name="seg" value="1"> Climatização
                                                            <input type="radio" name="seg" value="2"> GMG
                                                            <input type="radio" name="seg" value="3"> Serralheria
                                                            <input type="radio" name="seg" value="4"> Proteção
                                                            <input type="radio" name="seg" value="5"> Zeladoria
                                                            <input type="radio" name="seg" value="6"> Baterias
                                                            <input type="radio" name="seg" value="7"> Recuperação de Vandalismo
                                                            <input type="radio" name="seg" value="8"> Equipamento
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Checklists - Da Execução</span></div>
                                                    </div>
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1 rounded bg-light border"><span class="">
                                                                1. Obra executada conforme solicitação?
                                                            </span></div>
                                                        <div class="col-md-2 mt-1 rounded bg-light border"><input type="radio" name="perg1" value="1"> Sim</div>
                                                        <div class="col-md-2 mt-1 rounded bg-light border"><input type="radio" name="perg1" value="2"> Não</div>
                                                    </div>
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1 rounded bg-light border"><span class="">
                                                                2. Obra executada gerou alguma falha secundaria no site?
                                                            </span></div>
                                                        <div class="col-md-2 mt-1 rounded bg-light border"><input type="radio" name="perg2" value="1"> Sim</div>
                                                        <div class="col-md-2 mt-1 rounded bg-light border"><input type="radio" name="perg2" value="2"> Não</div>
                                                    </div>
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Checklist de Vistoria da atividade conforme escopo</span></div>
                                                    </div>
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1 rounded bg-light border"><span class="">
                                                                1. Todas as falhas identificadas na OS foram sanadas após execução da atividade?
                                                            </span></div>
                                                        <div class="col-md-2 mt-1 rounded bg-light border"><input type="radio" name="perg3" value="1"> Sim</div>
                                                        <div class="col-md-2 mt-1 rounded bg-light border"><input type="radio" name="perg3" value="2"> Não</div>
                                                    </div>
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1 rounded bg-light border"><span class="">
                                                                2. O material utilizado na execução da atividade oferece algum risco ( material reutilizado, fora das normas, etc)?
                                                            </span></div>
                                                        <div class="col-md-2 mt-1 rounded bg-light border"><input type="radio" name="perg4" value="1"> Sim</div>
                                                        <div class="col-md-2 mt-1 rounded bg-light border"><input type="radio" name="perg4" value="2"> Não</div>
                                                    </div>
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1 rounded bg-light border"><span class="">
                                                                3. A atividade esta dentro do padrão de qualidade solicitado?
                                                            </span></div>
                                                        <div class="col-md-2 mt-1 rounded bg-light border"><input type="radio" name="perg5" value="1"> Sim</div>
                                                        <div class="col-md-2 mt-1 rounded bg-light border"><input type="radio" name="perg5" value="2"> Não</div>
                                                    </div>
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1 rounded bg-light border"><span class="">
                                                                4. Foram deixados resíduos (sujeira,restos de materiais) no local da obra?
                                                            </span></div>
                                                        <div class="col-md-2 mt-1 rounded bg-light border"><input type="radio" name="perg6" value="1"> Sim</div>
                                                        <div class="col-md-2 mt-1 rounded bg-light border"><input type="radio" name="perg6" value="2"> Não</div>
                                                    </div>
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1 rounded bg-light border font-weight-bold"><span class="">
                                                                Deixe sua nota de avaliação geral sobre a obra.
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1-sm">
                                                        <div class="col-md mt-1 rounded bg-light border">
                                                            <input type="radio" name="perg7" value="0"> 0
                                                            <input type="radio" name="perg7" value="1"> 1
                                                            <input type="radio" name="perg7" value="2"> 2
                                                            <input type="radio" name="perg7" value="3"> 3
                                                            <input type="radio" name="perg7" value="4"> 4
                                                            <input type="radio" name="perg7" value="5"> 5
                                                            <input type="radio" name="perg7" value="6"> 6
                                                            <input type="radio" name="perg7" value="7"> 7
                                                            <input type="radio" name="perg7" value="8"> 8
                                                            <input type="radio" name="perg7" value="9"> 9
                                                            <input type="radio" name="perg7" value="10"> 10
                                                        </div>
                                                    </div>
                                                    <div class="row mt-1">
                                                        <div class="col">
                                                            <button id="btCheckList" type="button" class="btn btn-light border text-muted"><i class="icon-ok-circled-1 text-success"></i> Enviar check List</button>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-1-sm">
                                                        <div class="col alert alert-ligth">
                                                            <div id="retornoChecklist"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mt-1">
                                            <div class="card-header" id="headingThree">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-light btn-sm text-muted border" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                        <i class="icon-up text-primary"></i> Anexar Arquivos
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row p-2">
                                                        <div class="col"><span class="text-muted font-weight-bold"><i class="icon-attach-4 text-primary"></i> Arquivos permitidos: <span class="text-danger">PDF</span>, <span class="text-success">XLS</span>, <span class="text-success">XLSX</span>, JPEG, JPG e PNG</span></div>
                                                    </div>
                                                    <form id="formFiles" name="formFiles" action="javascript:void(0);" enctype="multipart/form-data">
                                                        <div class="row p-2">
                                                            <div class="col">
                                                                <div class="custom-file file-field">
                                                                    <input class="btn btn-light border text-muted d-inline-block text-truncate" type="file" name="file" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-1">
                                                            <div class="col-md mt-1">
                                                                <textarea class="form-control btn-outline-info text-danger" id="descricao" style="resize: none" placeholder="Descrição do arquivo (OBRIGATÓRIO)"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-1">
                                                            <div class="col">
                                                                <button class="btn btn-light border text-muted" type="submit"><i class='icon-upload-1 text-primary'></i> Enviar</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mt-1">
                                            <div class="card-header" id="headingFive">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-light btn-sm text-muted border" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                                        <i class="icon-comment-inv text-primary"></i> Inserir Observações
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row p-2">
                                                        <div class="col"><span class="text-muted font-weight-bold"><i class="icon-comment-inv text-primary"></i> Número máximo de 140 caracteres por observação</span></div>
                                                    </div>
                                                    <div class="row p-2">
                                                        <div class="col">
                                                            <textarea class="form-control" id="novaObs" placeholder="Observações..." style="resize: none" maxlength="140"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col alert alert-ligth">
                                                        <div id="retornoObs"></div>
                                                    </div>
                                                    <div class="row p-2">
                                                        <div class="col">
                                                            <button id="btInserirObs" type="button" class="btn btn-light border text-muted"><i class="icon-pencil-alt-1 text-secondary"></i> Inserir</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mt-1">
                                            <div class="card-header" id="headingSix">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-light btn-sm text-muted border" data-toggle="collapse" data-target="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                                                        <i class="icon-doc-inv text-primary"></i> Documentos
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div id="anexo" class="text-monospace"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1-sm mb-1">
                                    <div class="col-md mt-1 ml-md-1 align-middle">
                                        <button id="btAprovado" value="3" type="button" class="btStatus btn btn-sm btn-light border text-muted"><i class="icon-ok-circled-1 text-success"></i> Aprovado</button>
                                    </div>
                                    <div class="col-md mt-1 ml-md-1 align-middle d-none">
                                        <button id="btCorrecao" value="5" type="button" class="btStatus btn btn-sm btn-light border text-muted"><i class="icon-edit text-info"></i> Necessário correção</button>
                                    </div>
                                    <div class="col-md mt-1 ml-md-1 align-middle">
                                        <button id="btReprovado" value="4" type="button" class="btStatus btn btn-sm btn-light border text-muted"><i class="icon-cancel-circle-1 text-danger"></i> Reprovado</button>
                                    </div>
                                </div>
                                <div class="row mt-1-sm">
                                    <div class="col">
                                        <button type="button" class="btn btn-sm btn-light border text-muted" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
                                    </div>
                                </div>

                                <script async src="js/VFB_UPLOAD.js<?php echo $versao; ?>"></script>
                                <div id="anexo" class="text-monospace"></div>
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