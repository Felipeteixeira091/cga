$(document).ready(function () {

    almoxLista();
    notaUnidade();
    uploadModal();

    $("#btFiltrar").click(function () {

        var unidade = $("#notaUnidade").val();
        var data1 = $("#data1").val();
        var data2 = $("#data2").val();

        $("#retornoNota").text("");
        $("#retornoNota").removeClass();

        $("#listaNota").slideUp("fast");
        if (data1 === "" && data2 === "" && unidade === "0") {


            var msg = "<i class='icon-attention'></i> Dados insuficientes.";
            var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
            $("#retornoNota").fadeIn(1200).addClass(classe).append(msg).delay(800).fadeOut(1000);

        } else {
            SolicitacaoProcura(unidade, data1, data2);
        }
    });

    $("#btConfirma").click(function () {

        var almox = $("#idAlmox").text();
        var item = $("#itemReciclaID").text();
        var qtd_almox = $("#itemQuantidade").text();
        var qtd_descarte = $("#qtdDescarte").val();

        reciclagemConfirma(almox, item, qtd_almox, qtd_descarte);
    });
    function reciclagemConfirma(almox, item, qtd_almox, qtd_descarte) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "reciclagem", almox: almox, item: item, qtd_almox: qtd_almox, qtd_descarte: qtd_descarte },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGADESCARTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#confirmacaoRetorno").slideUp("fast").text("");
                $("#confirmacaoRetorno").removeClass();

                var classe = "";
                var msg = "";
                if (dados.erro === "1") {

                    msg = "<i class='icon-attention'></i> " + dados.msg;
                    classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";

                    $("#confirmacaoRetorno").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast");

                } else if (dados.erro === "0") {

                    msg = "<i class='icon-ok-1'></i> " + dados.msg;
                    classe = "bg bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#confirmacaoRetorno").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function () {

                        $("#ModalReciclagem").modal("hide");
                    });
                    $("#qtdDescarte").val("");

                }
            }
        });
    }
    function formDetalhe() {

        $(".btDetalheSoliciatacao").click(function () {
            var id = $(this).attr('value');


            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "solicitacaoDetalhe", id: id },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SGADESCARTE', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    $("#DetalheDescarte").modal("show");

                    $("#idDescarte").text(id);
                    $("#detalheRe").text(dados.re);
                    $("#detalheNome").text(dados.nome);
                    $("#detalheData").text(dados.data + " " + dados.hora);
                    $("#detalheUnidade").text(dados.unidade);
                    $("#detalheTipo").text(dados.tipo);
                    $("#detalheQtd").text(dados.qtd);
                    var res = dados.qtd_almox - dados.qtd;
                    var percent = (res * 100) / dados.limite;
                    percent = Math.round(percent);
                    $("#detalheQtdA").text(res + " (" + percent + "%)");
                    if (dados.nota === "") {
                        $("#detalheBtnDown").addClass("d-none");
                        $("#detalheBtnUp").removeClass("d-none");
                        $("#detalheNota").text("Pendente");
                        $("#rowObs").addClass("d-none");
                    } else {
                        $("#detalheBtnUp").addClass("d-none");
                        $("#detalheBtnDown").removeClass("d-none");
                        $("#detalheBtnDown").val(dados.nota);
                        var tipo = dados.nota[33] + dados.nota[34] + dados.nota[35];
                        $("#detalheNota").text(tipo);
                        $("#rowObs").removeClass("d-none");
                        $("#detalheObs").text(obs);
                    }
                }

            });
            $("#detalheBtnDown").click(function () {
                var anexo = $("#detalheBtnDown").val();
                var href = "sga_anexo/" + anexo;

                window.open(href);
            });

        });

    }

    function SolicitacaoProcura(unidade, data1, data2) {

        var delay = 1200;

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoProcura", unidade: unidade, data1: data1, data2: data2 },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGADESCARTE', //Definindo o arquivo onde serão buscados os dados
            beforeSend: function () {
                $("#btFiltra").attr("disabled", true);

                var classe = "bg-info rounded font-weight-bold text-white pt-2 pb-2";
                var ico = "<i class='icon-clock'></i>";
                var msg = "Aguarde...";
                $("#retornoNota").slideDown("fast").addClass(classe).append(ico + " " + msg);

            },
            success: function (dados) {

                setTimeout(function () {
                    var verifica = eval(dados);

                    if (verifica.length === 0) {

                        $("#retornoNota").text("");
                        $("#retornoNota").removeClass();

                        var tempo = 1500;
                        var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                        var ico = "<i class='icon-attention'></i>";
                        var msg = verifica.length + " Solicitações encontradas."

                        $("#retornoNota").slideDown("fast").addClass(classe).append(ico + " " + msg).delay(tempo).slideUp("fast", function () {

                            $("#btFiltra").attr("disabled", false);
                        });

                    } else {

                        $("#retornoNota").text("");
                        $("#retornoNota").removeClass();

                        var tempo = 1800;
                        var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";
                        var ico = "<i class='icon-ok-circled-1'></i>";
                        var msg = verifica.length + " Solicitações encontradas."

                        $("#retornoNota").slideDown("fast").addClass(classe).append(ico + " " + msg).delay(tempo).slideUp("fast", function () {

                            $("#btFiltra").attr("disabled", false);

                            var linhas = eval(dados);

                            var lista = "";
                            lista += "<div class='card border-light mt-2'>";
                            lista += "<div class='card-header font-weight-bold'>Resultado da busca</div>";
                            lista += "<table class='table table w-auto'>";
                            lista += "<thead class='thead-dark'>";
                            lista += "<tr>";
                            lista += "<th scope='col' class='text-center'>ID</th>";
                            lista += "<th scope='col' class='text-center'>DATA</th>";
                            lista += "<th scope='col' class='text-center'>UNIDADE</th>";
                            lista += "<th scope='col' class='text-center'>TIPO</th>";
                            lista += "<th scope='col' class='text-center'>ARQUIVO</th>";
                            lista += "<th scope='col' class='text-center'>DETALHES</th>";
                            lista += "</tr>";
                            lista += "</thead>";
                            lista += "<tbody>";
                            for (var i = 0; i < linhas.length; i++) {

                                var arquivo = "";
                                if (linhas[i].nota === "") {
                                    arquivo = "<span class='text-danger text-nowrap font-weight-bold'>Pendente</span>";
                                } else {
                                    arquivo = "<span class='text-info text-nowrap font-weight-bold'>Concluído</span>";
                                }
                                lista += "<tr id='linha" + linhas[i].id + "'>";
                                lista += "<td>" + linhas[i].id + "</td>";
                                lista += "<td class='text-center'>" + linhas[i].data + " " + linhas[i].hora + "</td>";
                                lista += "<td class='text-center'>" + linhas[i].unidade + "</td>";
                                lista += "<td class='text-center'><i class='" + linhas[i].ico + "'></i>" + linhas[i].tipo + "</td>";
                                lista += "<td class='text-center' id='arquivo_" + linhas[i].id + "'>" + arquivo + "</td>";
                                lista += "<td class='text-center'><button type='button' value='" + linhas[i].id + "' class='btDetalheSoliciatacao btn btn-outline-info btn-sm'><i class='icon-popup'></i> Detalhes</button></td>";
                                lista += "</tr>";
                            }
                            lista += "</tbody>";
                            lista += "</table>";

                            $("#listaNota").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);
                            // formModal();

                            formDetalhe();

                            $(".btNota").click(function () {
                                var anexo = $(".btNota").val();
                                var href = "sga_anexo/" + anexo;

                                window.open(href);
                            });
                        });
                    }

                }, delay);
            }
        });
    }
    function almox_exibe() {

        $(".bt_almox").click(function () {
            var almox = $(this).attr('value');

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "residuoLista", almox: almox },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SGADESCARTE', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    var linhas = eval(dados);

                    var lista = "";

                    var almox = linhas[0].almox;
                    var almoxId = linhas[0].id;
                    $("#tituloAlmox").text(almox);
                    $("#idAlmox").text(almoxId);

                    for (var i = 0; i < linhas.length; i++) {

                        var saldo = linhas[i].qtd - linhas[i].descarte;
                        var percent = (saldo * 100) / linhas[i].limite;
                        var cor = "";
                        var barra = "";

                        if (saldo === 0) {
                            cor = "text-success";
                        } else
                            if (percent > 0 && percent <= 59) {
                                cor = "text-success";
                                cor2 = "text-success";
                            } else if (percent >= 1 && percent <= 60) {
                                barra = "bg-Warning";
                                cor = "text-white"
                                cor2 = "text-warning";
                            } else if (percent >= 61 && percent <= 100) {
                                barra = "bg-danger";
                                cor = "text-white";
                                cor2 = "text-danger";
                            }
                        percent = Math.round(percent);
                        lista += "<div class='row border mt-1 rounded pb-1'>";
                        lista += "<div class='col'>";
                        lista += "<span id='itemTipo" + linhas[i].idItem + "' class='text-muted'>" + linhas[i].tipo + "</span><div class='mt-2 progress'><div id='itemQuantidade" + linhas[i].idItem + "' class='progress-bar " + barra + "' role='progressbar' style='width: " + percent + "%;' aria-valuenow='" + saldo + "' aria-valuemin='0' aria-valuemax='" + linhas[i].limite + "'><span class='" + cor + "'>" + saldo + "</span></div></div>";
                        lista += "</div>";
                        lista += "<div class='col-2'>";
                        lista += "<span class='" + cor2 + "'>" + saldo + " (" + percent + "%)</span>";
                        lista += "</div>";
                        lista += "<div class='col-2'>";
                        lista += "<button value='" + linhas[i].idItem + "' class='bt_recicla btn btn-light btn-sm border text-muted m-2'><i class='icon-loop-1 text-muted'></i> Reciclar</button>";
                        lista += "</div>";
                        lista += "</div>";

                        if (saldo <= 0) {
                            $(".bt_recicla").attr("disabled", true);
                        } else {
                            $(".bt_recicla").attr("disabled", false);
                        }
                    }
                    lista += "";

                    $("#residuos").slideDown("slow").html(lista);
                    recicla();
                }
            });
            $("#modalAlmox").modal("show");
        });
    }

    function uploadModal() {

        $("#detalheBtnUp").click(function () {

            $("#ModalReciclagemUpload").modal("show");
        });

    }
    function recicla() {

        $(".bt_recicla").click(function () {

            var itemId = $(this).attr('value');
            var itemNome = $("#itemTipo" + itemId).text();
            var almox = $("#idAlmox").text();
            var itemQtd = $("#itemQuantidade" + itemId).text();

            $("#itemRecicla").text(itemNome);
            $("#itemQuantidade").text(itemQtd);
            $("#itemReciclaID").text(itemId);

            $("#ModalReciclagem").modal("show");
        });


    }
    function almoxLista() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "almoxLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGADESCARTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = eval(dados);

                var lista = "";
                lista += "<div id='elementoAtivo' class='row'>";
                for (var i = 0; i < linhas.length; i++) {

                    lista += "<button value='" + linhas[i].id + "' class='bt_almox btn btn-light btn-sm border text-muted m-2'><i class='icon-shop-1 text-info'></i> " + linhas[i].nome + "</button>";
                }

                lista += "</div>";
                $("#almoxLista").slideDown("slow").html(lista);

                almox_exibe();
            }
        });
    }
    function notaUnidade() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "notaUnidade" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGADESCARTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linha = eval(dados);
                var linhas = "<option value='0'>Unidade de descarte</option>";
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }

                $("#notaUnidade").slideDown("fast").html(linhas);

            }
        });
    }
});