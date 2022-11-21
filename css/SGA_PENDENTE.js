$(document).ready(function() {


    form_status();

    $("#btFiltra").click(function() {

        var data1 = $("#SolicitacaoData1").val();
        var data2 = $("#SolicitacaoData2").val();
        var txt = $("#SolicitacaoTXT").val();
        var status = $("#SolicitacaoStatus").val();
        var pa = $("input[name='opcAt']:checked").val();

        var date1 = new Date(data1);
        var date2 = new Date(data2);
        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
        var diffDias = Math.ceil(timeDiff / (1000 * 3600 * 24));

        $("#retornoFiltro").text("");
        $("#retornoFiltro").removeClass();
        $("#bt_xls").attr("disabled", true);
        $("#spanXls").empty();

        var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
        var ico = "<i class='icon-attention'></i>";

        $("#ListaSolicitacao").slideUp("fast");

        SolicitacaoProcura(txt, status, data1, data2);

    });

    function SolicitacaoProcura(txt, status, data1, data2) {

        var delay = 1200;

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "filtra", txt: txt, status: status, data1: data1, data2: data2 },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            beforeSend: function() {
                $("#btFiltra").attr("disabled", true);

                var classe = "bg-info rounded font-weight-bold text-white pt-2 pb-2";
                var ico = "<i class='icon-clock'></i>";
                var msg = "Aguarde...";
                $("#retornoFiltro").slideDown("fast").addClass(classe).append(ico + " " + msg);

            },
            success: function(dados) {

                setTimeout(function() {
                    var verifica = eval(dados);

                    if (verifica.length === 0) {

                        $("#retornoFiltro").text("");
                        $("#retornoFiltro").removeClass();

                        var tempo = 1500;
                        var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                        var ico = "<i class='icon-attention'></i>";
                        var msg = verifica.length + " Solicitações encontradas."

                        $("#retornoFiltro").slideDown("fast").addClass(classe).append(ico + " " + msg).delay(tempo).slideUp("fast", function() {

                            $("#btFiltra").attr("disabled", false);
                        });

                    } else {

                        $("#retornoFiltro").text("");
                        $("#retornoFiltro").removeClass();

                        var tempo = 1800;
                        var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";
                        var ico = "<i class='icon-ok-circled-1'></i>";
                        var msg = verifica.length + " Solicitações encontradas."

                        $("#retornoFiltro").slideDown("fast").addClass(classe).append(ico + " " + msg).delay(tempo).slideUp("fast", function() {

                            $("#btFiltra").attr("disabled", false);

                            // if (dias <= 180) {
                            //      $("#bt_xls").attr("disabled", false);
                            //  } else if (dias > 180) {
                            //      $("#spanXls").append("<i class='icon-info text-danger'></i> <span class='text-muted'>Você pode exportar até 180 dias(6 mêses) por vez.</span>");
                            //  }

                            var linhas = eval(dados);

                            var lista = "";
                            lista += "<div class='card border-light mt-2'>";
                            lista += "<div class='card-header font-weight-bold'>Resultado da busca</div>";
                            lista += "<table class='table table-striped w-auto'>";
                            lista += "<thead class='thead-dark'>";
                            lista += "<tr>";
                            lista += "<th scope='col' class='text-center'>PA</th>";
                            lista += "<th scope='col' class='text-center'>NOME</th>";
                            lista += "<th scope='col' class='text-center'>STATUS</th>";
                            lista += "<th scope='col' class='text-center'>DATA/HORA</th>";
                            lista += "<th scope='col' class='text-center'><i class='icon-popup text-light'></i> VER</th>";
                            lista += "</tr>";
                            lista += "</thead>";
                            lista += "<tbody>";
                            for (var i = 0; i < linhas.length; i++) {

                                var solicitante = linhas[i].nome.split(" ");

                                lista += "<tr id='linha" + linhas[i].id + "'>";
                                lista += "<td class='text-center'>" + linhas[i].pa + "</td>";
                                lista += "<td class='text-center'>" + solicitante[0] + " " + solicitante[1] + "|" + linhas[i].cn + "</td>";
                                //                              lista += "<td>" + linhas[i].itens + "</td>";
                                var status = "";

                                if (linhas[i].status === "PENDENTE") {
                                    status = "<i class='icon-clock text-info'></i> " + linhas[i].status;
                                } else if (linhas[i].status === "SOLICITADO") {
                                    status = "<i class='icon-attention-alt text-danger'></i> " + linhas[i].status;
                                } else if (linhas[i].status === "APROVADO") {
                                    status = "<i class='icon-ok-circled2 text-success'></i> " + linhas[i].status;
                                } else if (linhas[i].status === "EXPIRADO") {
                                    status = "<i class='icon-block text-secondary'></i> " + linhas[i].status;
                                } else if (linhas[i].status === "EDIÇÃO PENDENTE") {
                                    status = "<i class='icon-edit text-secondary'></i> " + linhas[i].status;
                                } else if (linhas[i].status === "EDIÇÃO EM CURSO") {
                                    status = "<i class='icon-edit text-info'></i> " + linhas[i].status;
                                } else if (linhas[i].status === "EDIÇÃO CONCLUÍDA") {
                                    status = "<i class='icon-edit text-success'></i> " + linhas[i].status;
                                } else {
                                    status = "<i class='icon-cancel-circled-1 text-muted'></i> " + linhas[i].status;
                                }

                                lista += "<td class='text-center'>" + status + "</td>";
                                lista += "<td class='text-center'>" + linhas[i].data + " " + linhas[i].hora + "</td>";
                                lista += "<td class='text-center'><button type='button' value='" + linhas[i].id + "' class='btDetalheSoliciatacao btn btn-outline-info btn-sm'><i class='icon-popup'></i> Ver</button></td>";
                                lista += "</tr>";
                            }
                            lista += "</tbody>";
                            lista += "</table>";
                            $("#ListaSolicitacao").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);

                            formModal();
                        });
                    }

                }, delay);
            }
        });
    }

    $("#formAprova").click(function() {
        var solicitacao = $("#NumeroSolicitacao").text();
        var obs = $("#divObs").val() + " | ";
        obs += $("#obs2").val();

        var msg = "<i class='icon-stopwatch'></i> Aguarde...";
        var classe = "bg bg-info rounded font-weight-bold text-white pt-2 pb-2";

        $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg);

        ConcluiSolicitacao(solicitacao, obs);

    });


    $("#formReprova").click(function() {
        var solicitacao = $("#NumeroSolicitacao").text();
        var obs = $("#divObs").val() + "|";
        if ($("#obs2").val() != "") {
            obs += "Motivo Negativa: " + $("#obs2").val();
        }
        var msg = "<i class='icon-stopwatch'></i> Aguarde...";
        var classe = "bg bg-info rounded font-weight-bold text-white pt-2 pb-2";
        $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg);

        ReprovaSolicitacao(solicitacao, obs);

    });
    $("#bt_xls").click(function() {
        var txt = $("#SolicitacaoTXT").val();
        var dataInicio = $("#SolicitacaoData1").val();
        var dataFim = $("#SolicitacaoData2").val();
        var status = $("#SolicitacaoStatus").val();
        var href = "XLSSMA?acao=xls&txt=" + txt + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim + "&status=" + status + "&pa=" + pa;

        window.open(href);
    });

    $("#formVolta").click(function() {

        $("#DetalheSolicitacao").slideUp("slow", function() {
            $("#ListaSolicitacao").slideDown("slow", function() {
                $("#pa_formulario1").slideDown("slow");
            });
        });
    });

    function formModal() {

        $(".btDetalheSoliciatacao").click(function() {
            var solicitacao = $(this).attr('value');
            SolicitacaoDetalhe(solicitacao);

            $("#DetalheSolicitacao").modal("show");

        });
    }



    function ConcluiSolicitacao(solicitacao, obs) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoConclui", solicitacao: solicitacao, obs: obs },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                $("#ModalRetorno").slideUp("fast").text("");
                $("#ModalRetorno").removeClass();

                var classe = "";
                var msg = "";
                if (dados.erro === "1") {

                    msg = "<i class='icon-attention'></i> " + dados.msg;
                    classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast");

                } else if (dados.erro === "0") {

                    msg = "<i class='icon-ok-1'></i> " + dados.msg;
                    classe = "bg bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function() {

                        $("#DetalheSolicitacao").modal("hide");
                        SolicitacaoProcura($("#SolicitacaoTXT").val(), $("#SolicitacaoStatus").val(), $("#SolicitacaoData1").val(), $("#SolicitacaoData2").val(), $("input[name='opcAt']:checked").val());
                    });

                }
            }
        });
    }

    function SolicitacaoDetalhe(solicitacao) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "detalhe", solicitacao: solicitacao },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var linhas = eval(dados.itens);

                $("#NumeroSolicitacao").text(dados.id);

                $("#detalheSolicitanteRe").text(solicitacao.re);
                $("#detalheSolicitanteNome").text(solicitacao.nome);

                $("#detalheCoordenadorNome").text(solicitacao.nome_c);
                $("#detalheCoordenadorRE").text(solicitacao.re_c);

                $("#detalheObs").text(solicitacao.Obs);

                var status = "";

                if (solicitacao.status === "PENDENTE") {
                    status = solicitacao.status + "<i class='icon-clock text-info'></i>";
                } else if (solicitacao.status === "SOLICITADO") {

                    $("#formAprova").show("fast");
                    $("#formReprova").show("fast");
                    $("#obs2").show("fast");

                    status = solicitacao.status + "<i class='icon-attention-alt text-danger'></i>";
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

                // $("#detalheBagagem").addClass("d-none");
                $("#detalheBagagem").empty();
                if (bagagem.length > 0) {
                    $("#btHistorico").removeClass("d-none");

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
                    $("#btHistorico").addClass("d-none");
                }
                $("#detalheLista").slideDown("slow").html(lista);

            }
        });
    }

    function form_status() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "statusLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = "<option value=\"0\">SELECIONE UM STATUS</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#SolicitacaoStatus").slideDown("fast").html(linhas);
            }
        });
    }
});