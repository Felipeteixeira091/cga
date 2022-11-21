$(document).ready(function() {
    listaPendente();

    $("#btDados").addClass("d-none");

    $("#tipo").change(function() {

        $("#tipo_colaborador").addClass("d-none");
        $("#tipo_gmg").addClass("d-none");

        var tipo = $("#tipo").val();

        if (tipo === "0") {
            $("#btDados").addClass("d-none");
        }

        if (tipo === "1") {
            $("#tipo_colaborador").removeClass("d-none");
            listaColaborador();
        } else
        if (tipo === "2") {
            $("#tipo_gmg").removeClass("d-none");
            listaGMG();
        } else {
            $("#tipo_gmg").addClass("d-none");
            $("#tipo_colaborador").addClass("d-none");
        }

    });
    $("#tipo_colaborador").change(function() {


        var tipo = $("#tipo").val();
        var tGMG = $("#tipo_gmg").val();
        var tColaborador = $("#tipo_colaborador").val();

        if (tipo === "0") {
            $("#btDados").addClass("d-none");
        } else
        if (tGMG === "0" || tColaborador === "0") {
            $("#btDados").addClass("d-none");
        } else {
            $("#btDados").removeClass("d-none");
        }

    });
    $("#tipo_gmg").change(function() {


        var tipo = $("#tipo").val();
        var tGMG = $("#tipo_gmg").val();
        var tColaborador = $("#tipo_colaborador").val();

        if (tipo === "0") {
            $("#btDados").addClass("d-none");
        } else
        if (tGMG === "0" || tColaborador === "0") {
            $("#btDados").addClass("d-none");
        } else {
            $("#btDados").removeClass("d-none");
        }

    });

    $("#btDados").click(function() {

        $("#solicitacao_form").modal("show");
        var tipo = $("#tipo").val();

        $("#valor").val("");
        $("#saldoAtual").val("");
        $("#km").val("");
        $("#obs").val("");

        if (tipo === "1") {

            $("#detalheKM").removeClass("d-none");
            $("#formKM").removeClass("d-none");

            var colaborador = $("#tipo_colaborador").val();
            dadosColaborador(colaborador);
        } else {
            $("#detalheKM").addClass("d-none");
            $("#formKM").addClass("d-none");
            var gmg = $("#tipo_gmg").val();
            dadosGMG(gmg);
        }
    });

    $("#btSolicita").click(function() {
        solicita();
    });

    function listaColaborador() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: 'colaborador' },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCESPLANTAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = "<option value=\"0\">Selecione um colaborador</option>";
                    var linha = eval(dados);
                    for (var i = 0; i < linha.length; i++) {

                        linhas += "<option value=\"" + linha[i].re + "\">" + linha[i].nome + "</option>";
                    }
                    $("#tipo_colaborador").slideDown("fast").html(linhas);
                }
            }
        });
    }

    function dadosColaborador(colaborador) {

        $("#detalheRe").text("");
        $("#detalheNome").text("");
        $("#detalheCartao").text("");
        $("#detalheUltSol").text("");
        $("#detalheUltKM").text("");
        $("#detalheIdentificacao").text("");
        $("#detalheModelo").text("");
        $("#detalheVlr_mes").text("");
        $("#tituloTipo").text("");
        $("#idAnterior").text("");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: 'dadosColaborador', colaborador: colaborador },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCESPLANTAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {

                    //                    var rDados = eval(dados);
                    var km = "";
                    if (dados.froKm != dados.ultKm) {
                        km = dados.froKm;

                    } else {
                        km = dados.ultKm;
                    }
                    $("#detalheRe").append(dados.re);
                    $("#detalheNome").append(dados.nome);
                    $("#detalheCartao").append(dados.cartao);
                    $("#detalheUltSol").append(dados.ultData + " - " + dados.ultValor);
                    $("#idAnterior").append(dados.idAnterior);
                    $("#detalheUltKM").append(km);
                    $("#detalheVlr_mes").append(dados.valorMes);
                    $("#detalheIdentificacao").append(dados.placa);
                    $("#detalheModelo").append(dados.vMarca + " - " + dados.vModelo + " ");
                    $("#tituloTipo").text("para colaborador");
                }
            }
        });
    }

    function listaGMG() {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: 'gmg' },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCESPLANTAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = "<option value=\"0\">Selecione um GMG</option>";
                    var linha = eval(dados);
                    for (var i = 0; i < linha.length; i++) {

                        linhas += "<option value=\"" + linha[i].re + "\">" + linha[i].identificacao + "</option>";
                    }
                    $("#tipo_gmg").slideDown("fast").html(linhas);
                }
            }
        });
    }

    function dadosGMG(gmg) {

        $("#detalheRe").text("");
        $("#detalheNome").text("");
        $("#detalheCartao").text("");
        $("#detalheUltSol").text("");
        $("#detalhePlaca").text("");
        $("#detalheModelo").text("");
        $("#detalheVlr_mes").text("");
        $("#tituloTipo").text("");
        $("#detalheIdentificacao").text("");
        $("#detalheModelo").text("");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: 'dadosGMG', gmg: gmg },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCESPLANTAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var rDados = eval(dados);
                    $("#detalheRe").append(dados.re);
                    $("#detalheNome").append("GMG_" + dados.tipo + "_" + dados.gmg);
                    $("#detalheCartao").append(dados.cartao);
                    $("#detalheUltSol").append(dados.ultData + " - " + dados.ultValor);
                    $("#idAnterior").append(dados.idAnterior);
                    $("#detalheVlr_mes").append(dados.valor_solicitado);
                    $("#tituloTipo").text("para GMG");
                    $("#detalheModelo").append("GMG_" + dados.tipo + "_");
                    $("#detalheIdentificacao").append(dados.gmg);
                }
            }
        });
    }

    function solicita() {

        $("#retornoSolicitacao").text("");
        $("#retornoSolicitacao").removeClass();
        var tipo = $("#tipo").val();
        var idAnterior = $("#idAnterior").text();
        var re = $("#detalheRe").text();
        var cartao = $("#detalheCartao").text();
        var identificacao = $("#detalheIdentificacao").text();
        var saldo = $("#saldoAtual").val();
        var valor = $("#valor").val();
        var obs = $("#obs").val();
        var ultKM = "0";
        var km = "";

        if (tipo === "1") {
            re = $("#detalheRe").text();
            ultKM = $("#detalheUltKM").text();
            km = $("#km").val();
        }
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: {
                acao: 'solicita',
                tipo: tipo,
                re: re,
                idAnterior: idAnterior,
                cartao: cartao,
                identificacao: identificacao,
                saldo: saldo,
                valor: valor,
                obs: obs,
                ultKM: ultKM,
                km: km
            },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCESPLANTAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {

                    var msg = dados.msg;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoSolicitacao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");
                } else {
                    var msg = dados.msg;
                    var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoSolicitacao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        window.location.reload();
                    });
                }
            }
        });

    }

    function listaPendente() {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: 'listaPendente' },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCESPLANTAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {

                    var linha = eval(dados);
                    var lista = "<table class='table table-sm table-striped'>";
                    lista += "<thead>";
                    lista += "<tr>";
                    lista += "<th scope='col'>COLABORADOR</th>";
                    lista += "<th scope='col'>DATA</th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody>";
                    for (var i = 0; i < linha.length; i++) {

                        lista += "<tr id=\"ln" + linha[i].id + "\">";
                        if (linha[i].tipo === "1") {

                            lista += "<td>" + linha[i].colaborador + "</td>";
                        } else {
                            lista += "<td>GMG_" + linha[i].gTipo + "_" + linha[i].gmg + "</td>";

                        }
                        lista += "<td>" + linha[i].data + "</td>";

                    }
                    lista += "</tbody>";
                    lista += "</table>";
                    $("#listaPendente").slideDown("fast").html(lista);
                }
            }
        });
    }

});