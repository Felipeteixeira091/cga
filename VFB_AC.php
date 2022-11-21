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
    <script src="js/VFB_AC.js<?php echo $versao; ?>"></script>

    <link rel="stylesheet" href="css/fontello/css/fontello.css<?php echo $versao; ?>">

</head>

<body>
    <script>
        function somenteNumeros(num) {
            var er = /[^0-9.]/;
            er.lastIndex = 0;
            var campo = num;
            if (er.test(campo.value)) {
                campo.value = "";
            }
        }

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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div id="pgSistema" class="rounded"><span class="navbar-brand">VFB</span></div>
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
            <div style="margin: 2px;" class="objeto">
                <div id="pa_formulario1" style="display:none" class="card border mt-2 p-1">
                    <div class="card-header font-weight-bold">Acompanhamento de vistorias Fatura B</div>
                    <div class="row mt-3">
                        <div class="col">
                            <input type="date" class="form-control" id="vfbData1" value="<?php echo "2021-05-01"; ?>">
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" id="vfbData2" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <input class="form-control" type="text" id="vfbTXT" placeholder="PESQUISAR RE, SITE, OS...">
                        </div>
                        <div class="col">
                            <select class="form-control" id="vfbStatus"></select>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col">
                            <button id="btFiltravfb" class="btn btn-light border text-muted"><i class='icon-filter text-primary'></i> Filtrar</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-light border text-muted" id="bt_xls" disabled><i class="icon-download-2 text-success"></i> Exportar para .XLS</button>
                        </div>
                        <div class="col">
                            <button id="btNovo" class="btn btn-light border text-muted"><i class='icon-camera text-info'></i> Solicitar vistoria</button>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="retornovfbPesquisa"></div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="ModalNovovfb" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo"><i class="icon-doc-new text-muted"></i> Nova solicitação de vistoria</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1"> 
                                    <button class="btn btn-light text-muted border" id="btModalSite"><i class="icon-search-1"></i> Selecionar site</button>
                                </div>
                            </div>
                            <div class="row mt-1-sm border rounded ml-2 mr-2 mt-1 mb-2 p-1" id="rowSite" style="display: none;">
                                <div class="col-md mt-1">
                                    <span class="font-weight-bold" id="textoSite"></span> <span class="badge badge-pill badge-light text-secondary" id="vfbSite"></span>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <input type="text" id="vfbOS" onkeyup="somenteNumeros(this);" class="form-control" placeholder="OS Prisma">
                                </div>
                                <div class="col-md mt-1">
                                    <input type="text" id="vfbValor" onKeyPress="return(moeda(this,'.',',',event))" class="form-control" placeholder="Valor da Obra">
                                </div>
                                <div class="col-md mt-1">
                                    <select id="vfbColaborador" class="form-control"></select>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <select id="vfbMObra" class="form-control">
                                        <option selected value="0">MÃO DE OBRA</option>
                                        <option value="1">ICOMON</option>
                                        <option value="2">FORNECEDOR</option>
                                    </select>
                                </div>
                                <div class="col-md mt-1">
                                    <select id="vfbMObraRE" class="form-control"></select>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <textarea class="form-control" id="vfbOBS" style="resize: none" placeholder="Orientação"></textarea>
                                </div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1">
                                    <button class="btn btn-light text-muted border" id="btvfbCadastra"><i class="icon-ok-circled-1 text-success"></i> Cadastrar</button>
                                </div>
                                <div class="col-md mt-1">
                                    <button type="button" class="btn btn-light text-muted border" data-dismiss="modal"><i class="icon-reply"></i> Voltar</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="retornoVfbNova"></div>
                            </div>
                        </div>
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
                            <div class="col alert alert-ligth">
                                <div id="retornoSite"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal bd-example-modal-lg hide fade in" data-keyboard="false" data-backdrop="static" id="DetalheVfb" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalTitulo">Vistoria: <span class="badge badge-pill badge-light text-secondary" id="vfbNumeroDetalhe"></span></h5>
                        </div>
                        <div class="modal-body ml-2 mr-2">
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Vistoriador:</span> <span id="vfbNomeDetalhe"></span> - <span id="vfbReDetalhe"></span></div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Mão de obra:</span> <span id="vfbMOTipoDetalhe"></span></div>
                                <div class="col-md mt-1 ml-1 rounded bg-light border"><span class="font-weight-bold">Executante:</span> <span id="vfbMODetalhe"></span></div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Data:</span> <span id="vfbDataDetalhe"></span> <span id="vfbHoraDetalhe"></span></div>
                                <div class="col-md mt-1 ml-1 rounded bg-light border"><span class="font-weight-bold">Segmento:</span> <span id="vfbSegmento"></span> <span id="vfbHoraDetalhe"></span></div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Site:</span> <span id="vfbSiteDetalhe"></span></div>
                                <div class="col-md mt-1 ml-1 rounded bg-light border"><span class="font-weight-bold">OS Prisma:</span> <span id="vfbOsDetalhe"></span></div>
                                <div class="col-md mt-1 ml-1 rounded bg-light border"><span class="font-weight-bold">Valor da obra:</span> R$ <span id="vfbValorDetalhe"></span></div>
                            </div>
                            <div class="row mt-1-sm">
                                <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Serviço aprovado:</span> <span id="vfbOrientacao"></span></div>
                            </div>
                            <div class="row mt-1-sm mb-2">
                                <div class="col-md mt-1 font-weight-bold rounded bg-light border"><span class="font-weight-bold">Status atual:</span> <span id="vfbStatusDetalhe"></span></div>
                                <div class="col-md mt-1 font-weight-bold ml-1 rounded bg-light border"><span class="font-weight-bold">SLA:</span> <span id="vfbSLADetalhe" class="text-danger"></span></div>
                            </div>
                            <div id="accordion">
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="mb-0">
                                            <button class="btn btn-light btn-sm text-muted border" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                <i class="icon-clock-3 text-primary"></i> Histórico
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row p-2">
                                                <div class="col"><span class="text-muted font-weight-bold"><i class="icon-info-1 text-danger"></i> Histórico da vistoria</span></div>
                                            </div>
                                            <div class="row mt-1-sm">
                                                <div class="col-md mt-1 rounded">
                                                    <div id="historico"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header" id="headingTwo">
                                        <h5 class="mb-0">
                                            <button class="btn btn-light btn-sm text-muted border" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                                <i class="icon-doc-2 text-primary"></i> Documentos
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row p-2">
                                                <div class="col">
                                                    <span class="text-muted font-weight-bold"><i class="icon-info-1 text-danger"></i> Documentos e informações da vistoria</span>
                                                </div>
                                            </div>
                                            <div class="row mt-1-sm">
                                                <div class="col-md mt-1 rounded bg-light border">
                                                    <div id="anexo"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header" id="headingTre">
                                        <h5 class="mb-0">
                                            <button class="btn btn-light btn-sm text-muted border collapsed" data-toggle="collapse" data-target="#collapseTre" aria-expanded="false" aria-controls="collapseTre">
                                                <i class="icon-list-2 text-primary"></i> Checklist
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTre" class="collapse" aria-labelledby="headingTre" data-parent="#accordion">
                                        <div class="card-body">
                                            <div id="checklistErro"><span class="badge badge-pill badge-light text-danger">Checklist não preenchido.</span></div>
                                            <div id="checklist">
                                                <div class="row p-2">
                                                    <div class="col"><span class="text-muted font-weight-bold"><i class="icon-list-2 text-primary"></i> Check list de vistoria</span></div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Segmento de atividade:</span></div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border">
                                                        <input disabled type="radio" name="seg" value="1"> Climatização
                                                        <input disabled type="radio" name="seg" value="2"> GMG
                                                        <input disabled type="radio" name="seg" value="3"> Serralheria
                                                        <input disabled type="radio" name="seg" value="4"> Proteção
                                                        <input disabled type="radio" name="seg" value="5"> Zeladoria
                                                        <input disabled type="radio" name="seg" value="6"> Baterias
                                                        <input disabled type="radio" name="seg" value="7"> Recuperação de Vandalismo
                                                        <input disabled type="radio" name="seg" value="8"> Equipamento
                                                    </div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Checklists - Da Execução</span></div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border"><span class="">
                                                            1. Obra executada conforme solicitação?
                                                        </span></div>
                                                    <div class="col-md-2 mt-1 rounded bg-light border"><input disabled type="radio" name="perg1" value="1"> Sim</div>
                                                    <div class="col-md-2 mt-1 rounded bg-light border"><input disabled type="radio" name="perg1" value="2"> Não</div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border"><span class="">
                                                            2. Obra executada gerou alguma falha secundaria no site?
                                                        </span></div>
                                                    <div class="col-md-2 mt-1 rounded bg-light border"><input disabled type="radio" name="perg2" value="1"> Sim</div>
                                                    <div class="col-md-2 mt-1 rounded bg-light border"><input disabled type="radio" name="perg2" value="2"> Não</div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border"><span class="font-weight-bold">Checklist de Vistoria da atividade conforme escopo</span></div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border"><span class="">
                                                            1. Todas as falhas identificadas na OS foram sanadas após execução da atividade?
                                                        </span></div>
                                                    <div class="col-md-2 mt-1 rounded bg-light border"><input disabled type="radio" name="perg3" value="1"> Sim</div>
                                                    <div class="col-md-2 mt-1 rounded bg-light border"><input disabled type="radio" name="perg3" value="2"> Não</div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border"><span class="">
                                                            2. O material utilizado na execução da atividade oferece algum risco ( material reutilizado, fora das normas, etc)?
                                                        </span></div>
                                                    <div class="col-md-2 mt-1 rounded bg-light border"><input disabled type="radio" name="perg4" value="1"> Sim</div>
                                                    <div class="col-md-2 mt-1 rounded bg-light border"><input disabled type="radio" name="perg4" value="2"> Não</div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border"><span class="">
                                                            3. A atividade esta dentro do padrão de qualidade solicitado?
                                                        </span></div>
                                                    <div class="col-md-2 mt-1 rounded bg-light border"><input disabled type="radio" name="perg5" value="1"> Sim</div>
                                                    <div class="col-md-2 mt-1 rounded bg-light border"><input disabled type="radio" name="perg5" value="2"> Não</div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border"><span class="">
                                                            4. Foram deixados resíduos (sujeira,restos de materiais) no local da obra?
                                                        </span></div>
                                                    <div class="col-md-2 mt-1 rounded bg-light border"><input disabled type="radio" name="perg6" value="1"> Sim</div>
                                                    <div class="col-md-2 mt-1 rounded bg-light border"><input disabled type="radio" name="perg6" value="2"> Não</div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border font-weight-bold"><span class="">
                                                            Deixe sua nota de avaliação geral sobre a obra.
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row mt-1-sm">
                                                    <div class="col-md mt-1 rounded bg-light border">
                                                        <input disabled type="radio" name="perg7" value="0"> 0
                                                        <input disabled type="radio" name="perg7" value="1"> 1
                                                        <input disabled type="radio" name="perg7" value="2"> 2
                                                        <input disabled type="radio" name="perg7" value="3"> 3
                                                        <input disabled type="radio" name="perg7" value="4"> 4
                                                        <input disabled type="radio" name="perg7" value="5"> 5
                                                        <input disabled type="radio" name="perg7" value="6"> 6
                                                        <input disabled type="radio" name="perg7" value="7"> 7
                                                        <input disabled type="radio" name="perg7" value="8"> 8
                                                        <input disabled type="radio" name="perg7" value="9"> 9
                                                        <input disabled type="radio" name="perg7" value="10"> 10
                                                    </div>
                                                </div>
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
                            <div class="row mt-2-sm">
                                <div class="col-md mt-1 alert-light rounded border">
                                    <textarea class="form-control mt-1 mb-1 border border-light" placeholder="Observação..." id="obs2" style="resize: none"></textarea>
                                </div>
                            </div>
                            <div class="row mt-2-sm d-none">
                                <div class="col-md mt-1">
                                    <button class="btn btn-light text-muted border" id="formAprova"><i class='icon-ok-circled text-success'></i> Validar</button>
                                </div>
                                <div class="col-md mt-1">
                                    <button class="btn btn-light text-muted border" id="formReprova"><i class='icon-minus-circled text-danger'></i> Reprovar</button>
                                </div>
                                <div class="col-md mt-1">
                                    <button class="btn btn-light text-muted border" data-dismiss="modal"><i class='icon-reply-1'></i> Fechar</button>
                                </div>
                            </div>
                            <div class="row mt-2-sm">
                                <div class="col-md mt-1">
                                    <button class="btn btn-light text-muted border" data-dismiss="modal"><i class='icon-reply-1'></i> Fechar</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col alert alert-ligth">
                                <div id="ModalRetorno"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div style="display: none;" id="vfbLista"></div>
    </center>

</body>

</html>