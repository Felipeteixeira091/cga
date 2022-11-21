$(document).ready(function () {

    $('input[name=options][value=0]').attr('checked', 'checked');

    $("input[name='options']").each(function () {
        if ($(this).val() !== 0) {
            $(this).prop("checked", false);
            $(".options").removeClass("active");
        } else {
            $(this).prop("checked", true);
            $(".options").addClass("active");
        }
    });

    function ItenAnexo() {

        $("#btUpload").click(function () {
            var id = $("#NumeroSolicitacao").text();

            $("#itemId").text(id);
            $("#form_upload").modal("show");
        });
    }

    $("#pa_formulario1").slideDown("fast");
    form_status();

    $("#btFiltra").click(function () {

        var data1 = $("#SolicitacaoData1").val();
        var data2 = $("#SolicitacaoData2").val();
        var txt = $("#SolicitacaoTXT").val();
        var status = $("#SolicitacaoStatus").val();
        // var pa = $("input[name='options']:checked").val();

        var date1 = new Date(data1);
        var date2 = new Date(data2);
        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
        var diffDias = Math.ceil(timeDiff / (1000 * 3600 * 24));
        var diffMeses = diffDias / 30;

        $("#retornoFiltro").text("");
        $("#retornoFiltro").removeClass();
        $("#bt_xls").attr("disabled", true);
        $("#spanXls").empty();

        var classe = "bg-danger bg-gradient rounded fw-bold text-white pt-2 pb-2";
        var ico = "<i class='bi bi-exclamation-circle'></i>";

        $("#ListaSolicitacao").slideUp("fast");
        if (data1 === "" || data2 === "") {
            $("#retornoFiltro").slideDown("fast").addClass(classe).append("<i class='bi bi-exclamation-circle'></i> A data inicial e final são obrigatórias.").delay(1500).slideUp("fast");
        } else
            if (txt === "" && status === "0") {
                $("#retornoFiltro").slideDown("fast").addClass(classe).append("<i class='bi bi-exclamation-circle'></i> Informações insuficientes.").delay(1500).slideUp("fast");
            } else {
                SolicitacaoProcura(txt, status, data1, data2, diffDias);
            }
    });

    $("#formAprova").click(function () {
        var solicitacao = $("#NumeroSolicitacao").text();
        var obs1 = $("#detalheObs").text();
        var obs2 = $("#obs2").val();

        var obs = obs1 + obs2;

        var relatorio = $("input[name='options']:checked").val();
        if (!relatorio) {
            relatorio = 0;
        }
        var msg = "<i class='icon-stopwatch'></i> Aguarde...";
        var classe = "bg bg-primary bg-gradient rounded fw-bold text-white pt-2 pb-2";

        $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg);

        ConcluiSolicitacao(solicitacao, obs, relatorio);

    });
    $("#formAprova_r").click(function () {
        var solicitacao = $("#NumeroSolicitacao").text();

        var msg = "<i class='icon-stopwatch'></i> Aguarde...";
        var classe = "bg bg-primary bg-gradient rounded fw-bold text-white pt-2 pb-2";

        $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg);

        relatorio_status(solicitacao, 14);
    });
    $("#formReprova_r").click(function () {
        var solicitacao = $("#NumeroSolicitacao").text();

        var msg = "<i class='icon-stopwatch'></i> Aguarde...";
        var classe = "bg bg-primary bg-gradient rounded fw-bold text-white pt-2 pb-2";

        $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg);

        relatorio_status(solicitacao, 13);
    });

    $("#formEdita").click(function () {
        var solicitacao = $("#NumeroSolicitacao").text();
        var obs = $("#obs2").val();

        var msg = "<i class='icon-stopwatch'></i> Aguarde...";
        var classe = "bg bg-primary bg-gradient rounded fw-bold text-white pt-2 pb-2";

        $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg);

        EditaSolicitacao(solicitacao, obs);

    });

    $("#formReprova").click(function () {
        var solicitacao = $("#NumeroSolicitacao").text();

        var obs = $("#obs2").val();

        var msg = "<i class='icon-stopwatch'></i> Aguarde...";
        var classe = "bg bg-primary bg-gradient rounded fw-bold text-white pt-2 pb-2";
        $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg);

        ReprovaSolicitacao(solicitacao, obs);

    });
    $("#bt_xls").click(function () {
        var txt = $("#SolicitacaoTXT").val();
        var dataInicio = $("#SolicitacaoData1").val();
        var dataFim = $("#SolicitacaoData2").val();
        var status = $("#SolicitacaoStatus").val();
        var pa = $("input[name='opcAt']:checked").val();
        var href = "XLSSMA?acao=xls&txt=" + txt + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim + "&status=" + status + "&pa=" + pa;

        window.open(href);
    });

    $("#formVolta").click(function () {

        $("#DetalheSolicitacao").slideUp("slow", function () {
            $("#ListaSolicitacao").slideDown("slow", function () {
                $("#pa_formulario1").slideDown("slow");
            });
        });
    });
    function historico() {
        $(".btHistorico").click(function () {

            var solicitacao = $(this).attr('value');

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "historicoLista", solicitacao: solicitacao },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SMAPENDENTE', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    var linhas = eval(dados);
                    var lista = "<div class='card border-light mt-2 p-1'>";
                    lista += "<table class='table table w-auto mt-1 table-sm'>";
                    lista += "<thead class='thead-dark'>";
                    lista += "<tr>";
                    lista += "<th style='text-align: center' scope='col'>DATA</th>";
                    lista += "<th style='text-align: center' scope='col'>STATUS</th>";
                    lista += "<th style='text-align: center' scope='col'>RESPONSÁVEL</th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody>";
                    for (var i = 0; i < linhas.length; i++) {
                        var nome = linhas[i].nome.split(" ");

                        lista += "<tr id='linha" + linhas[i].id + "'>";
                        lista += "<td style='text-align: center'>" + linhas[i].atualizacao + "</td>";
                        lista += "<td style='text-align: center'><i class='" + linhas[i].ico + "'></i> " + linhas[i].status + "</td>";
                        lista += "<td style='text-align: center'>" + nome[0] + " " + nome[1] + "[" + linhas[i].re + "]</td>";
                        lista += "</tr>";
                    }
                    lista += "</tbody>";
                    lista += "</div>";
                    lista += "</table>";

                    $("#modal_historico").modal("show");

                    $("#historico").slideDown("slow").html(lista);
                }
            });
        });
    }
    ///-----------------------------------------------------------------
    function LCEstoque(colaborador) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "LCEstoque", colaborador: colaborador },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDOGERAL', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var verifica = eval(dados.sga);

                var classe = "bg-info rounded fw-bold text-white p-2";
                var msg = "<i class='icon-attention-2'></i> Estoque vazio.";

                $("#Estoque").text("");
                $("#Estoque").removeClass();

                if (verifica.length === 0) {

                    $("#Estoque").slideDown("fast").addClass(classe).append(msg);

                } else {
                    var linhas = eval(dados.geral);
                    var lista = "<div class='card border-light mt-2 p-1'>";
                    lista += "<table class='table table w-auto mt-1 table-sm'>";
                    lista += "<thead class='thead-dark'>";
                    lista += "<tr>";
                    lista += "<th style='text-align: center' scope='col'>TIPO</th>";
                    lista += "<th style='text-align: center' scope='col'><i class='icon-basket-1 text-warning'></i> Saldo retirado</th>";
                    lista += "<th style='text-align: center' scope='col'><i class='icon-user text-success'></i> Material novo em campo</th>";
                    lista += "<th style='text-align: center' scope='col'><i class='icon-attention-2 text-danger'></i> Pré-Baixa</th>";
                    lista += "<th style='text-align: center' scope='col'><i class='icon-shop-1 text-info'></i> Devolvido</th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody>";
                    for (var i = 0; i < linhas.length; i++) {

                        var pre = linhas[i].pb_sga - linhas[i].sga;
                        //                    var saldo = linhas[i].sma - (parseInt(linhas[i].sga) + parseInt(pre));
                        var saldo = linhas[i].sma - parseInt(linhas[i].sga);

                        lista += "<tr id='linha" + linhas[i].id + "'>";
                        lista += "<td style='text-align: center'><i class='" + linhas[i].ico + "'></i>" + linhas[i].tipo + "</td>";
                        lista += "<td style='text-align: center'>" + linhas[i].sma + "</td>";
                        lista += "<td style='text-align: center'>" + saldo + "</td>";
                        lista += "<td style='text-align: center'>" + pre + "</td>";
                        lista += "<td style='text-align: center'>" + linhas[i].sga + "</td>";
                        lista += "</tr>";
                    }
                    lista += "</tbody>";
                    lista += "</div>";
                    lista += "</table>";
                    $("#Estoque").slideDown("slow").html(lista);
                }
            }
        });
    }
    ///-----------------------------------SGA------------------------

    function formModal() {

        $(".btDetalheSoliciatacao").click(function () {
            var solicitacao = $(this).attr('value');
            SolicitacaoDetalhe(solicitacao);

            $("#DetalheSolicitacao").modal("show");

        });
    }
    function SolicitacaoProcura(txt, status, data1, data2, dias) {

        var delay = 1200;

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoProcura", txt: txt, status: status, data1: data1, data2: data2 },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            beforeSend: function () {
                $("#btFiltra").attr("disabled", true);

                var classe = "bg-primary bg-gradient rounded fw-bold text-white pt-2 pb-2";
                var ico = "<i class='bi bi-hourglass-split'></i>";
                var msg = "Aguarde...";
                $("#retornoFiltro").slideDown("fast").addClass(classe).append(ico + " " + msg);

            },
            success: function (dados) {
                //               setTimeout(function () {
                var verifica = eval(dados);
                if (verifica.length === 0) {

                    $("#retornoFiltro").text("");
                    $("#retornoFiltro").removeClass();

                    var tempo = 1500;
                    var classe = "bg-danger bg-gradient rounded fw-bold text-white pt-2 pb-2";
                    var ico = "<i class='bi bi-exclamation-circle'></i>";
                    var msg = verifica.length + " Solicitações encontradas."

                    $("#retornoFiltro").slideDown("fast").addClass(classe).append(ico + " " + msg).delay(tempo).slideUp("fast", function () {

                        $("#btFiltra").attr("disabled", false);
                    });

                } else {

                    $("#retornoFiltro").text("");
                    $("#retornoFiltro").removeClass();

                    var tempo = 1800;
                    var classe = "bg-success bg-gradient rounded fw-bold text-white pt-2 pb-2";
                    var ico = "<i class='bi bi-exclamation-circle'></i>";
                    var msg = verifica.length + " Solicitações encontradas."

                    $("#retornoFiltro").slideDown("fast").addClass(classe).append(ico + " " + msg).delay(tempo).slideUp("fast", function () {

                        $("#btFiltra").attr("disabled", false);
                        $("#bt_xls").attr("disabled", false);

                        var linhas = eval(dados);

                        var lista = "";
                        lista += "<div class='card border-light mt-2 p-1'>";
                        lista += "<div class='card-header fw-bold'>Resultado da busca</div>";
                        lista += "<table class='table table-striped w-auto'>";
                        lista += "<thead class='thead-dark'>";
                        lista += "<tr class='text-center'>";
                        lista += "<th scope='col'>ID</th>";
                        lista += "<th scope='col'>SOLICITANTE</th>";
                        lista += "<th scope='col'>SITE</th>";
                        lista += "<th scope='col'>SOBRESSALENTE</th>";
                        lista += "<th scope='col'>DATA/HORA</th>";
                        lista += "<th scope='col'>STATUS</th>";
                        lista += "<th scope='col'>HISTÓRICO</th>";
                        lista += "<th scope='col'>DETALHES</th>";
                        lista += "</tr>";
                        lista += "</thead>";
                        lista += "<tbody>";
                        for (var i = 0; i < linhas.length; i++) {

                            var solicitante = linhas[i].NOME_SOLICITANTE.split(" ");
                            var status = "<i class='" + linhas[i].ICO + "'></i> " + linhas[i].STATUS;

                            lista += "<tr class='text-center' id='linha" + linhas[i].ID_SOLICITACAO + "'>";
                            lista += "<td>" + linhas[i].ID_SOLICITACAO + "</td>";
                            lista += "<td>" + solicitante[0] + " " + solicitante[1] + "</td>";
                            lista += "<td>[" + linhas[i].CN + "] " + linhas[i].SITE + "</td>";
                            lista += "<td>" + linhas[i].SOBRESSALENTE + "</td>";
                            lista += "<td>" + linhas[i].SOLICITACAO + "</td>";
                            lista += "<td>" + status + "</td>";
                            lista += "<td><button type='button' value='" + linhas[i].ID_SOLICITACAO + "' class='btHistorico btn btn-sm btn-light border'><i class='bi bi-clock-history'></i> Histórico</button></td>";
                            lista += "<td><button type='button' value='" + linhas[i].ID_SOLICITACAO + "' class='btDetalheSoliciatacao btn btn-sm btn-light border'><i class='bi bi-eye'></i> Ver</button></td>";
                            lista += "</tr>";
                        }
                        lista += "</tbody>";
                        lista += "</table>";

                        $("#ListaSolicitacao").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);

                        formModal();
                        historico();
                    });
                }
                //               }, delay);
            }
        });
    }

    function ConcluiSolicitacao(solicitacao, obs, relatorio) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoConclui", solicitacao: solicitacao, obs: obs, relatorio: relatorio },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#ModalRetorno").slideUp("fast");
                $("#ModalRetorno").text("");
                $("#ModalRetorno").removeClass();

                var classe = "";
                var msg = "";
                if (dados.erro === "1") {

                    msg = "<i class='bi bi-exclamation-circle'></i> " + dados.msg;
                    classe = "bg bg-danger bg-gradient rounded text-white fw-bold p-2 mt-2";

                    $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg).delay(1300).slideUp("fast");

                } else {

                    msg = "<i class='icon-ok-1'></i> " + dados.msg;
                    classe = "bg bg-success bg-gradient rounded text-white fw-bold p-2 mt-2";

                    $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg).delay(1300).slideUp("fast", function () {

                        $("#DetalheSolicitacao").modal("hide");
                        SolicitacaoProcura($("#SolicitacaoTXT").val(), $("#SolicitacaoStatus").val(), $("#SolicitacaoData1").val(), $("#SolicitacaoData2").val(), $("input[name='opcAt']:checked").val());
                    });

                }
            }
        });
    }
    function relatorio_status(solicitacao, status) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "relatorio", solicitacao: solicitacao, status: status },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#ModalRetorno").slideUp("fast");
                $("#ModalRetorno").text("");
                $("#ModalRetorno").removeClass();

                var classe = "";
                var msg = "";
                if (dados.erro === "1") {

                    msg = "<i class='bi bi-exclamation-circle'></i> " + dados.msg;
                    classe = "bg bg-danger bg-gradient rounded text-white fw-bold p-2 mt-2";

                    $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg).delay(1300).slideUp("fast");

                } else {

                    msg = "<i class='icon-ok-1'></i> " + dados.msg;
                    classe = "bg bg-success bg-gradient rounded text-white fw-bold p-2 mt-2";

                    $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg).delay(1300).slideUp("fast", function () {

                        $("#DetalheSolicitacao").modal("hide");
                        SolicitacaoProcura($("#SolicitacaoTXT").val(), $("#SolicitacaoStatus").val(), $("#SolicitacaoData1").val(), $("#SolicitacaoData2").val(), $("input[name='opcAt']:checked").val());
                    });

                }
            }
        });
    }
    function EditaSolicitacao(solicitacao, obs) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoEdita", solicitacao: solicitacao, obs: obs },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#ModalRetorno").slideUp("fast");
                $("#ModalRetorno").text("");
                $("#ModalRetorno").removeClass();

                var classe = "";
                if (dados.erro === "1") {
                    classe = "bg bg-danger bg-gradient rounded text-white fw-bold p-2 mt-2";

                    $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1900).slideUp("fast");

                } else if (dados.erro === "0") {

                    classe = "bg bg-success bg-gradient rounded text-white fw-bold p-2 mt-2";

                    $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1900).slideUp("fast", function () {

                        $("#DetalheSolicitacao").modal("hide");
                        SolicitacaoProcura($("#SolicitacaoTXT").val(), $("#SolicitacaoStatus").val(), $("#SolicitacaoData1").val(), $("#SolicitacaoData2").val(), $("input[name='opcAt']:checked").val());
                    });

                }
            }
        });
    }

    function ReprovaSolicitacao(solicitacao, obs) {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoReprova", solicitacao: solicitacao, obs: obs },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#ModalRetorno").slideUp("fast");
                $("#ModalRetorno").text("");
                $("#ModalRetorno").removeClass();

                var msg = "";
                var classe = "";
                if (dados.erro === "1") {
                    msg = "<i class='bi bi-exclamation-circle'></i> " + dados.msg;
                    classe = "bg bg-danger bg-gradient rounded text-white fw-bold p-2 mt-2";

                    $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast");

                } else if (dados.erro === "0") {

                    msg = "<i class='icon-ok-1'></i> " + dados.msg;
                    classe = "bg bg-danger bg-gradient rounded text-white fw-bold p-2 mt-2";

                    $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function () {

                        $("#DetalheSolicitacao").modal("hide");
                        SolicitacaoProcura($("#SolicitacaoTXT").val(), $("#SolicitacaoStatus").val(), $("#SolicitacaoData1").val(), $("#SolicitacaoData2").val(), $("input[name='opcAt']:checked").val());

                    });
                }
            }
        });
    }

    function SolicitacaoDetalhe(solicitacao) {

        $("#headingOne").collapse("hide");
        $("#headingSGA").collapse("hide");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoDetalhe", solicitacao: solicitacao },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = eval(dados.itens);
                var solicitacao = dados.solicitacao;
                var bagagem = dados.bagagem;
                var linhas1 = eval(dados.bagagem);

                $("#NumeroSolicitacao").text(solicitacao.solicitacao);
                if (solicitacao.sobressalente === "1") {
                    $("#Sobressalente").text("SIM");
                } else {
                    $("#Sobressalente").text("NÃO");
                }
                $("#detalheSolicitanteRe").text(solicitacao.sRe);
                $("#detalheSolicitanteNome").text(solicitacao.sNome);
                $("#detalheNome").text(solicitacao.ColaboradorNome);
                $("#detalheRE").text(solicitacao.ColaboradorRE);
                $("#detalheCoordenadorNome").text(solicitacao.CoordenadorNome);
                $("#detalheCoordenadorRE").text(solicitacao.CoordenadorRE);
                $("#detalhePrazo").text(solicitacao.Prazo);
                $("#detalheOs").text(solicitacao.Os);
                $("#detalheSigla").text(solicitacao.Sigla);
                $("#detalheEndereco").text(solicitacao.Endereco);
                $("#detalheFatura").text(solicitacao.Fatura);
                $("#detalheObs").text(solicitacao.Obs);
                $("#btnConclusao").val(solicitacao.anexo);
                $("#itemId").text(solicitacao.solicitacao);

                var bt = "";
                if (solicitacao.anexo === "nd" || solicitacao.anexo === "") {
                    bt = "Indisponível";
                } else {
                    bt = "<a href='sma_anexo/" + solicitacao.anexo + "'><button type='button' class='btn btn-sm btn-primary m-1'><i class='icon-attach-4'></i> Baixar</button></a>";
                }

                $("#btnConclusao").html(bt);

                if (solicitacao.status === "APROVADO") {

                    $("#detalheAprovacao").text(" - RESP.:" + solicitacao.aprovNome);
                } else {
                    $("#detalheAprovacao").text("");
                }

                var status = solicitacao.status + "<i class='" + solicitacao.ico + "'></i>";

                if (solicitacao.status === "RELATÓRIO RECUSADO") {

                    $("#formAprova_r").hide("fast");
                    $("#formReprova_r").hide("fast");

                    $("#formAprova").hide("fast");
                    $("#formReprova").hide("fast");
                    $("#obs2").hide("fast");
                    $("#rowConclusao").hide();
                    $("#collapsed_5").show();
                } else if (solicitacao.status === "RELATÓRIO CONCLUÍDO") {

                    $("#formAprova_r").hide("fast");
                    $("#formReprova_r").hide("fast");

                    $("#formAprova").hide("fast");
                    $("#formReprova").hide("fast");
                    $("#obs2").hide("fast");
                    $("#rowConclusao").hide();
                    $("#collapsed_5").hide();
                } else if (solicitacao.status === "RELATÓRIO ENVIADO") {

                    $("#formAprova_r").show("fast");
                    $("#formReprova_r").show("fast");
                    $("#formAprova").hide("fast");
                    $("#formReprova").hide("fast");
                    $("#obs2").hide("fast");
                    $("#rowConclusao").hide();
                    $("#collapsed_5").show();
                } else if (solicitacao.status === "PENDENTE RELATÓRIO") {

                    $("#formAprova_r").hide("fast");
                    $("#formReprova_r").hide("fast");
                    $("#formAprova").hide("fast");
                    $("#formReprova").hide("fast");
                    $("#obs2").hide("fast");
                    $("#rowConclusao").hide();
                    $("#collapsed_5").show();
                } else if (solicitacao.status === "PENDENTE") {

                    $("#formAprova_r").hide("fast");
                    $("#formReprova_r").hide("fast");
                    $("#formAprova").hide("fast");
                    $("#formReprova").hide("fast");

                } else if (solicitacao.status === "SOLICITADO") {

                    $("#formAprova").show("fast");
                    $("#formReprova").show("fast");
                    $("#formAprova_r").hide("fast");
                    $("#formReprova_r").hide("fast");

                    $("#rowConclusao").show();
                    $("#collapsed_5").hide();
                    $("#obs2").show("fast");

                } else if (solicitacao.status === "APROVADO") {

                    $("#formAprova").hide("fast");
                    $("#formReprova").hide("fast");
                    $("#formAprova_r").hide("fast");
                    $("#formReprova_r").hide("fast");

                    $("#obs2").hide("fast");
                    $("#rowConclusao").hide();
                    $("#collapsed_5").hide();
                } else if (solicitacao.status === "EXPIRADO") {

                    $("#formAprova").hide("fast");
                    $("#formReprova").hide("fast");
                    $("#formAprova_r").hide("fast");
                    $("#formReprova_r").hide("fast");
                    $("#obs2").hide("fast");
                    $("#rowConclusao").hide();
                    $("#collapsed_5").hide();
                } else if (solicitacao.status === "NEGADO") {

                    $("#formAprova").hide("fast");
                    $("#formReprova").hide("fast");
                    $("#formAprova_r").hide("fast");
                    $("#formReprova_r").hide("fast");
                    $("#obs2").hide("fast");
                    $("#rowConclusao").hide();
                    $("#collapsed_5").hide();
                } else if (solicitacao.status === "PENDENTE") {

                    $("#formAprova").hide("fast");
                    $("#formReprova").hide("fast");
                    $("#formAprova_r").hide("fast");
                    $("#formReprova_r").hide("fast");
                    $("#obs2").hide("fast");
                    $("#rowConclusao").addClass("d-none");
                    $("#collapsed_5").addClass("d-none");
                }

                $("#detalheStatus").html(status);
                $("#detalheTipo").text(solicitacao.tipo);
                $("#obs2").val("");

                var obs = solicitacao.Obs;
                if (obs.length > 0 && obs != "" && obs != " ") {
                    $("#divObs").addClass("border border-danger");
                } else {
                    $("#divObs").removeClass("border border-danger");
                }

                var lista = "";
                for (var i = 0; i < linhas.length; i++) {
                    var descricao = "";

                    if (linhas[i].anexo === "nd") {
                        descricao = linhas[i].descricao;
                    } else {
                        descricao = "<a href='sma_anexo/" + linhas[i].anexo + "' target='_blank'><button type='button' class='btn btn-sm btn-light border text-muted'><i class='icon-attach-4'></i> " + linhas[i].descricao + "</button></a>";
                    }

                    lista += "<tr class='text-center' id='linha" + linhas[i].id + "'>";
                    lista += "<td>" + linhas[i].id + "</td>";
                    lista += "<td>" + linhas[i].pa + "</td>";
                    lista += "<td>" + descricao + "</td>";
                    lista += "<td>" + linhas[i].unidade + "</td>";
                    lista += "<td>" + linhas[i].quantidade + "</td>";
                    lista += "</tr>";
                }

                $("#detalheBagagem").empty();
                if (bagagem.length > 0) {
                    $("#headingOne").removeClass("d-none");

                    var lista1 = "<table class='table table-striped w-auto table-sm'>";
                    lista1 += "<thead class='thead-dark'>";
                    lista1 += "<tr>";
                    lista1 += "<th style='text-align: center' scope='col'>ID</th>";
                    lista1 += "<th style='text-align: center' scope='col'>TIPO</th>";
                    lista1 += "<th style='text-align: center' scope='col'>ULTIMA ALTERAÇÃO</th>";
                    lista1 += "<th style='text-align: center' scope='col'>KG</th>";
                    lista1 += "</tr>";
                    lista1 += "</thead>";
                    for (var i = 0; i < linhas1.length; i++) {
                        lista1 += "<tr class='text-center' id='linha" + linhas1[i].id + "'>";
                        lista1 += "<td>" + linhas1[i].id + "</td>";
                        lista1 += "<td>" + linhas1[i].tipo + "</td>";
                        lista1 += "<td>" + linhas1[i].data + " " + linhas1[i].hora + "</td>";
                        lista1 += "<td>" + linhas1[i].kg + "</td>";
                        lista1 += "</tr>";
                    }
                    lista1 += "</table>";
                    $("#detalheBagagem").append(lista1);
                } else {

                    var classe = "bg-success bg-gradient rounded fw-bold text-white p-2";
                    var msg = "<i class='icon-attention-2'></i> Estoque de gás vazio.";

                    $("#Estoque").text("");
                    $("#Estoque").removeClass();

                    $("#detalheBagagem").slideDown("fast").addClass(classe).append(msg);

                }
                $("#detalheLista").slideDown("slow").html(lista);

                $("#collapse").collapse("show");

                LCEstoque(solicitacao.ColaboradorRE);

                ItenAnexo();
            }
        });
    }


    function form_status() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "statusLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">SELECIONE UM STATUS</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#SolicitacaoStatus").slideDown("fast").html(linhas);

                $("#SolicitacaoStatus").val(1);

            }
        });
    }
});