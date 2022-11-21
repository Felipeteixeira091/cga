$(document).ready(function () {

    form_status();

    $("#btFiltra").click(function () {

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
        $("#spanXls").empty();

        var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
        var ico = "<i class='icon-attention'></i>";

        $("#ListaSolicitacao").slideUp("fast");

        SolicitacaoProcura(txt, status, data1, data2);

    });
    $("#formCarrega").click(function () {
        var item = $("#sItens").val();
        var id = $("#NumeroSolicitacao").text();
        carrega(id, item);
    });

    function carrega(id, item) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "carrega", item: item, id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (sga) {

                $("#detalhePA").html("");
                $("#detalheQtdBaixa").text("");
                $("#detalheQtdValida").text("");

                $("#obs2").val("");
                $("#qtdEntregue").val("");

                $("#detalheItem").slideDown("fast", function () {

                    $("#detalhePA").slideDown("fast").html("<i class='" + sga.ico + "'></i> " + sga.item);
                    $("#detalheQtdBaixa").slideDown("fast").text(sga.qtd);

                    var valido = "";
                    if (sga.qtdV === "") {
                        valido = "PENDENTE";
                    } else {
                        valido = sga.qtdV;
                    }
                    //$("#detalheQtdValida").text(valido);

                    $("#detalheQtdValida").slideDown("fast").text(valido);
                });
            }
        });
    }

    function SolicitacaoProcura(txt, status, data1, data2) {

        var delay = 1200;

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "filtra", txt: txt, status: status, data1: data1, data2: data2 },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            beforeSend: function () {
                $("#btFiltra").attr("disabled", true);

                var classe = "bg-info rounded font-weight-bold text-white pt-2 pb-2";
                var ico = "<i class='icon-clock'></i>";
                var msg = "Aguarde...";
                $("#retornoFiltro").slideDown("fast").addClass(classe).append(ico + " " + msg);

            },
            success: function (dados) {

                setTimeout(function () {
                    var verifica = eval(dados);

                    if (verifica.length === 0) {

                        $("#retornoFiltro").text("");
                        $("#retornoFiltro").removeClass();

                        var tempo = 1500;
                        var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                        var ico = "<i class='icon-attention'></i>";
                        var msg = verifica.length + " Solicitações encontradas."

                        $("#retornoFiltro").slideDown("fast").addClass(classe).append(ico + " " + msg).delay(tempo).slideUp("fast", function () {

                            $("#btFiltra").attr("disabled", false);
                        });

                    } else {

                        $("#retornoFiltro").text("");
                        $("#retornoFiltro").removeClass();

                        var tempo = 1800;
                        var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";
                        var ico = "<i class='icon-ok-circled-1'></i>";
                        var msg = verifica.length + " Solicitações encontradas."

                        if (verifica.length > 0) {

                            $("#bt_xls").attr("disabled", false);
                        } else {
                            $("#bt_xls").attr("disabled", true);
                        }
                        $("#retornoFiltro").slideDown("fast").addClass(classe).append(ico + " " + msg).delay(tempo).slideUp("fast", function () {

                            $("#btFiltra").attr("disabled", false);

                            var linhas = eval(dados);

                            var lista = "";
                            lista += "<div class='card border-light mt-2'>";
                            lista += "<div class='card-header font-weight-bold'>Resultado da busca</div>";
                            lista += "<table class='table table-striped w-auto'>";
                            lista += "<thead class='thead-dark'>";
                            lista += "<tr>";
                            lista += "<th scope='col' class='text-center'>SITE</th>";
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
                                lista += "<td class='text-center'>" + linhas[i].site + "</td>";
                                lista += "<td class='text-center'>" + solicitante[0] + " " + solicitante[1] + "|" + linhas[i].cn + "</td>";
                                //                              lista += "<td>" + linhas[i].itens + "</td>";
                                var status = "";

                                if (linhas[i].status === "PENDENTE") {
                                    status = "<i class='icon-clock text-info'></i> " + linhas[i].status;
                                } else if (linhas[i].status === "EM PREENCHIMENTO") {
                                    status = "<i class='icon-edit text-danger'></i> " + linhas[i].status;
                                } else if (linhas[i].status === "CONCLUÍDO") {
                                    status = "<i class='icon-ok-circled2 text-success'></i> " + linhas[i].status;
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

    $("#formRecebido").click(function () {
        var solicitacao = $("#sItens").val();
        var item = $("#sItens").val();
        var qtd = $("#qtdEntregue").val();
        var baixa = $("#detalheQtdBaixa").text();

        var msg = "<i class='icon-stopwatch'></i> Aguarde...";
        var classe = "bg bg-info rounded font-weight-bold text-white pt-2 pb-2";

        $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg);

        validar(solicitacao, item, qtd, baixa);
    });

    $("#bt_xls").click(function () {
        var txt = $("#SolicitacaoTXT").val();
        var dataInicio = $("#SolicitacaoData1").val();
        var dataFim = $("#SolicitacaoData2").val();
        var status = $("#SolicitacaoStatus").val();
        var href = "XLSSGA?acao=xls&txt=" + txt + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim + "&status=" + status;

        window.open(href);
    });

    $("#formVolta").click(function () {

        $("#DetalheSolicitacao").slideUp("slow", function () {
            $("#ListaSolicitacao").slideDown("slow", function () {
                $("#pa_formulario1").slideDown("slow");
            });
        });
    });
    $("#sItens").change(function () {

        var id = $("#NumeroSolicitacao").text();
        var tipo = $("#sItens").val();

        saldo(id, tipo);
    });
    $("#btFinalizar").click(function () {

        $("#rBaixa").slideUp("fast", function () {
            $("#rConclui").slideDown("fast");
        });

    });
    $("#btConcluir").click(function () {


        var id = $("#NumeroSolicitacao").text();
        var almox = $("#SolicitacaoAlmox").val();
        var obs = $("#obs2").val();

        conclui(id, almox, obs);
    });
    $("#modalVolta").click(function () {

    });
    function saldo(id, tipo) {

        $("#detalheQtdBaixa").val("");
        $("#detalheQtdValida").val("");
        $("#detalhePA").html("");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "saldobaixa", id: id, tipo: tipo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#detalheQtdBaixa").text(dados.pbaixa);
                $("#detalheQtdValida").text(dados.baixa);
                $("#detalhePA").html("<i class='" + dados.ico + "'></i> " + dados.descricao);
            }
        });
    }

    function formModal() {

        $(".btDetalheSoliciatacao").click(function () {
            var solicitacao = $(this).attr('value');
            SolicitacaoDetalhe(solicitacao);

            $("#detalhePA").html("");
            $("#detalheQtdBaixa").text("");
            $("#detalheQtdValida").text("");

            $("#obs2").val("");
            $("#qtdEntregue").val("");

            $("#DetalheSolicitacao").modal("show");
        });
    }

    function conclui(id, almox, obs) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "conclui", solicitacao: id, almox: almox, obs: obs },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

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

                    $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function () {

                        $("#DetalheSolicitacao").modal("hide");
                        SolicitacaoProcura($("#SolicitacaoTXT").val(), $("#SolicitacaoStatus").val(), $("#SolicitacaoData1").val(), $("#SolicitacaoData2").val());
                    });
                }
            }
        });
    }

    function validar(solicitacao, item, qtd, baixa) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "valida", solicitacao: solicitacao, item: item, qtd: qtd, baixa: baixa },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

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

                    $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function () {

                        var item = "<i class='icon-article-1 text-danger'></i> Selecione um ítem a ser verificado.";
                        $("#detalhePA").html(item);

                        var opt = "option[value=" + 0 + "]";
                        $("#sItens").find(opt).attr("selected", "selected");

                        // $("#DetalheSolicitacao").modal("hide");
                        //   SolicitacaoProcura($("#SolicitacaoTXT").val(), $("#SolicitacaoStatus").val(), $("#SolicitacaoData1").val(), $("#SolicitacaoData2").val());
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
            success: function (dados) {

                var sga = dados.sga;
                var linha = eval(dados.item);

                $("#NumeroSolicitacao").text(sga.id);
                $("#detalheSolicitanteRe").text(sga.re);
                $("#detalheSolicitanteNome").text(sga.nome);
                $("#detalheAmox").text(sga.almoxarifado);
                $("#detalheCoordenadorNome").text(sga.nome_c);
                $("#detalheCoordenadorRE").text(sga.re_c);
                $("#detalheOs").text(sga.os);

                var item = "<i class='icon-article-1 text-danger'></i> Selecione um ítem a ser verificado.";

                if (sga.status === "PENDENTE") {
                    var status = sga.status + "<i class='icon-clock text-info'></i>";

                    $("#rConclui").slideUp("fast", function () {
                        $("#rBaixa").slideDown("fast");
                    });

                    $("#btFinalizar").attr("disabled", false);

                    $("#SolicitacaoAlmox").attr("disabled", false);
                    $("#formRecebido").attr("disabled", false);
                    $("#qtdEntregue").attr("disabled", false);
                    $("#obs2").attr("disabled", false);

                } else if (sga.status === "CONCLUÍDO") {

                    $("#rBaixa").slideUp("fast", function () {
                        $("#rConclui").slideUp("fast");
                    });

                    $("#btFinalizar").attr("disabled", true);

                    $("#SolicitacaoAlmox").attr("disabled", true);
                    $("#formRecebido").attr("disabled", true);
                    $("#qtdEntregue").attr("disabled", true);
                    $("#obs2").attr("disabled", true);

                    status = sga.status + "<i class='icon-ok-circled2 text-info'></i>";
                } else if (sga.status === "EM PREENCHIMENTO") {

                    $("#rConclui").slideUp("fast");
                    $("#rBaixa").slideUp("fast");

                    $("#btFinalizar").attr("disabled", true);

                    status = sga.status + "<i class='icon-edit text-info'></i>";
                }
                $("#detalheStatus").html(status);

                $("#detalheSite").text(sga.site);
                $("#detalheAtividade").text(sga.atividade);
                $("#detalheOs").text(sga.os);


                var linhas = "<option value=\"0\">ÍTENS DEVOLVIDOS</option>";
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }

                $("#sItens").slideDown("fast").html(linhas);
                $("#detalhePA").html(item);
                $("#detalheQtdBaixa").text(sga.qtd);

                form_almox();
            }
        });
    }

    function form_status() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "statusLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">SELECIONE UM STATUS</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#SolicitacaoStatus").slideDown("fast").html(linhas);
            }
        });
    }

    function form_almox() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "almoxLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGAPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">ALMOXARIFADO RECEBEDOR</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#SolicitacaoAlmox").slideDown("fast").html(linhas);
            }
        });
    }
});