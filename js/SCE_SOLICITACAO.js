$(document).ready(function () {
    listaPendente();

    $("#btDados").addClass("d-none");

    $("#tipo").change(function () {

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

    $("#tipo_colaborador").change(function () {


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
    $("#tipo_gmg").change(function () {


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

    $("#btDados").click(function () {

        $("#solicitacao_form").modal("show");
        var tipo = $("#tipo").val();

        $("#valor").val("");
        $("#saldoAtual").val("");
        $("#km").val("");
        $("#obs").val("");

        if (tipo === "1") {

            $("#detalheKM").removeClass("d-none");
            $("#formKM").removeClass("d-none");
            $("#divGmg").addClass("d-none");

            var colaborador = $("#tipo_colaborador").val();
            dadosColaborador(colaborador);
        } else {
            $("#detalheKM").addClass("d-none");
            $("#formKM").addClass("d-none");
            $("#divGmg").removeClass("d-none");

            var gmg = $("#tipo_gmg").val();
            dadosGMG(gmg);
        }
    });

    $("#btConfirma").click(function () {
        confirma();
    });
    $("#btSolicita").click(function () {
        solicita();
    });
    function sce_define_valor() {
        $(".sce_bt_modal_valor").click(function () {

            var id = $(this).attr('value');
            $("#sce_modal_valor").modal('show');

            aprovacao_exibir_detalhe(id);
        });
    }
    function aprovacao_exibir_detalhe(id) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "valor", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCESOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#defId").text(dados.id);
                $("#defRe").text(dados.re);
                $("#defNome").text(dados.nome);
                $("#defCartao").text(dados.cartao);
                $("#defKm").text(dados.km);
                $("#defSaldo").text(dados.saldo);
                $("#defObs").text(dados.obs);

            }
        });
    }
    function listaColaborador() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: 'colaborador' },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCESOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
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
            url: 'SCESOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
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


                    if (dados.placa === "N CADASTRADO") {
                        $("#detalheIdentificacao").append("<i class='icon-attention'></i> VEÍCULO NÃO CADASTRADO");
                        $("#detalheIdentificacao").addClass("text-danger font-weight-bold");
                        $("#btSolicita").attr("disabled", true);

                    } else {
                        $("#detalheIdentificacao").removeClass("text-danger font-weight-bold");
                        $("#detalheIdentificacao").append(dados.placa);
                        $("#detalheModelo").append(dados.vMarca + " - " + dados.vModelo + " ");
                        $("#btSolicita").attr("disabled", false);
                    }

                    $("#detalheCartao").append(dados.cartao);
                    $("#detalheRe").append(dados.re);
                    $("#detalheNome").append(dados.nome);
                    $("#detalheUltSol").append(dados.ultData + " - " + dados.ultValor);
                    $("#idAnterior").append(dados.idAnterior);
                    $("#detalheUltKM").append(km);
                    $("#detalheVlr_mes").append(dados.valorMes);
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
            url: 'SCESOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = "<option value=\"0\">Selecione um GMG</option>";
                    var linha = eval(dados);
                    for (var i = 0; i < linha.length; i++) {

                        linhas += "<option value=\"" + linha[i].re + "\">GMG_" + linha[i].tipo + "_" + linha[i].identificacao + " - " + linha[i].cn + "</option>";
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
        $("#idAnterior").text("");
        $("#solTempoAC").text("");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: 'dadosGMG', gmg: gmg },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCESOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function (dadosR) {

                var dados = dadosR.detalhe;

                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    $("#detalheRe").append(dados.re);
                    $("#detalheNome").append("GMG_" + dados.tipo + "_" + dados.gmg);
                    $("#detalheCartao").append(dados.cartao);
                    $("#detalheUltSol").append(dados.ultData + " - " + dados.ultValor);
                    $("#idAnterior").append(dados.idAnterior);
                    $("#detalheVlr_mes").append(dados.valor_solicitado);
                    $("#tituloTipo").text("para GMG");
                    $("#detalheModelo").append("GMG_" + dados.tipo + "_");
                    $("#detalheIdentificacao").append(dados.gmg);
                    $("#solTempoAC").text(dadosR.tempo);
                }
            }
        });
    }

    function confirma() {

        $("#retornoConfirmacao").text("");
        $("#retornoConfirmacao").removeClass();
        var id = $("#defId").text();
        var re = $("#defRe").text();
        var valor = $("#confValor").val();
        var obs1 = $("#defObs").text();
        var obs2 = $("#confObs").val();

//        alert(id + "-" + re + "-" + valor + "-" + obs1 + "-" + obs2);
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: {
                acao: 'confirma',
                id: id,
                colaborador: re,
                valor: valor,
                obs1: obs1,
                obs2: obs2,
            },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCESOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                if (dados.erro === "1") {

                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    var msg = "<i class='icon-attention'></i> " + dados.msg;

                    $("#retornoConfirmacao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");
                } else {

                    var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";
                    var msg = "<i class='icon-attention'></i> " + dados.msg;

                    $("#retornoConfirmacao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        window.location.reload();
                    });
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
            url: 'SCESOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                if (dados.erro === "1") {

                    var msg = dados.msg;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoSolicitacao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");
                } else {
                    var msg = dados.msg;
                    var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoSolicitacao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

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
            url: 'SCESOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
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
                    lista += "<th scope='col'>VALOR</th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody>";

                    var valor = "";
                    for (var i = 0; i < linha.length; i++) {

                        lista += "<tr id=\"ln" + linha[i].id + "\">";
                        if (linha[i].tipo === "1") {

                            lista += "<td>" + linha[i].colaborador + "</td>";
                        } else {
                            lista += "<td>GMG_" + linha[i].gTipo + "_" + linha[i].gmg + "</td>";

                        }
                        lista += "<td>" + linha[i].data + "</td>";

                        if (linha[i].status === "6") {

                            lista += "<td><button value='" + linha[i].id + "' class='sce_bt_modal_valor btn btn-primary btn-sm'>Definir</button></td>";
                        } else {
                            lista += "<td>" + linha[i].valor + "</td>";

                        }

                    }
                    lista += "</tbody>";
                    lista += "</table>";
                    $("#listaPendente").slideDown("fast").html(lista);
                    sce_define_valor();
                }
            }
        });
    }

});