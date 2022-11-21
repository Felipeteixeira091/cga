$(document).ready(function () {

    //Elemento_lista();

    // LCEstoque();

    $("#btSaldo").click(function () {
        
        LCEstoque();
        $("#DetalheEstoque").modal("show");

    });

    $("#btModalSite").click(function () {
        $("#pesquisaSITE").modal("show");

    });

    $("#btModalBaixa").click(function () {

        $("#modalBaixa").modal("show");

        verifica();
        form_tipo();
        SiteProcura();

    });
    $("#btAdd").click(function () {

        var id = $("#id_sga").text();
        var tipo = $("#tipoDescarte").val();
        var qtd = $("#qtdBaixa").val();
        add(id, tipo, qtd);
    });

    $("#tipoDescarte").change(function () {

        var id = $("#id_sga").text();
        var tipo = $("#tipoDescarte").val();

        saldo(id, tipo);
    });
    $("#modalVolta").click(function () {
        $("#detalheAlerta").slideUp("fast").removeClass(classe).text("");
    });
    function LCEstoque() {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "LCEstoque", colaborador: "nd" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDOGERAL', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = eval(dados.geral);
                var sma = eval(dados.sma);
                var sga = eval(dados.sga);

                var lista_g = "<div class=''>";
                lista_g += "<table class='table table-striped w-auto mt-1'>";
                lista_g += "<thead class='thead-dark'>";
                lista_g += "<tr>";
                lista_g += "<th style='text-align: center' scope='col'>TIPO</th>";
                lista_g += "<th style='text-align: center' scope='col'>M.Retirado (SMA)</th>";
                lista_g += "<th style='text-align: center' scope='col'>M. novo</th>";
                //lista_g += "<th style='text-align: center' scope='col'>M. declarado</th>";
                lista_g += "<th style='text-align: center' scope='col'>M. devolvido</th>";
                lista_g += "</tr>";
                lista_g += "</thead>";
                lista_g += "<tbody>";
                for (var i = 0; i < linhas.length; i++) {

                    var pre = linhas[i].pb_sga - linhas[i].sga;
                    var saldo = linhas[i].sma - (parseInt(linhas[i].sga) + parseInt(pre));

                    lista_g += "<tr id='linha" + linhas[i].id + "'>";
                    lista_g += "<td style='text-align: center'><i class='" + linhas[i].ico + "'></i>" + linhas[i].tipo + "</td>";
                    lista_g += "<td style='text-align: center'>" + linhas[i].sma + "</td>";
                    lista_g += "<td style='text-align: center'>" + saldo + "</td>";
                    //lista_g += "<td style='text-align: center'>" + pre + "</td>";
                    lista_g += "<td style='text-align: center'>" + linhas[i].sga + "</td>";

                    lista_g += "</tr>";
                }
                lista_g += "</tbody>";
                lista_g += "</div>";
                lista_g += "</table>";

                $("#Estoque").slideUp("slow").html("").delay(100).slideDown("fast").html(lista_g);

                var lista_sma = "<div class='card border-light mt-2 p-1'>";
                lista_sma += "<table class='table table-sm table-striped w-auto mt-1'>";
                lista_sma += "<thead class='thead-dark'>";
                lista_sma += "<tr>";
                lista_sma += "<th style='text-align: center' scope='col'><small>PA</small></th>";
                lista_sma += "<th style='text-align: center' scope='col'><small>QTD TOTAL</small></th>";
                lista_sma += "<th style='text-align: center' scope='col'><small>DATA/HORA</small></th>";
                lista_sma += "</tr>";
                lista_sma += "</thead>";
                lista_sma += "<tbody>";
                for (var i = 0; i < sma.length; i++) {

                    lista_sma += "<tr id='linha" + sma[i].id + "'>";
                    lista_sma += "<td style='text-align: center'><small>" + sma[i].pa + "</small></td>";
                    lista_sma += "<td style='text-align: center'><small>" + sma[i].qtd + "</small></td>";
                    lista_sma += "<td style='text-align: center'><small>" + sma[i].dh + "</small></td>";
                    lista_sma += "</tr>";
                }
                lista_sma += "</tbody>";
                lista_sma += "</div>";
                lista_sma += "</table>";

                $("#EstoqueSMA").slideUp("slow").html("").delay(100).slideDown("fast").html(lista_sma);

                var lista_sga = "<div class='card border-light mt-2 p-1'>";
                lista_sga += "<table class='table table-sm table-striped w-auto mt-1'>";
                lista_sga += "<thead class='thead-dark'>";
                lista_sga += "<tr>";
                lista_sga += "<th style='text-align: center' scope='col'><small>TIPO</small></th>";
                lista_sga += "<th style='text-align: center' scope='col'><small>DECLARADO</small></th>";
                lista_sga += "<th style='text-align: center' scope='col'><small>ENTREGUE</small></th>";
                lista_sga += "<th style='text-align: center' scope='col'><small>DATA/HORA</small></th>";
                lista_sga += "</tr>";
                lista_sga += "</thead>";
                lista_sga += "<tbody>";
                for (var i = 0; i < sga.length; i++) {

                    lista_sga += "<tr id='linha" + sga[i].id + "'>";
                    lista_sga += "<td style='text-align: center'><small>" + sga[i].tipo + "</small></td>";
                    lista_sga += "<td style='text-align: center'><small>" + sga[i].declarado + "</small></td>";
                    lista_sga += "<td style='text-align: center'><small>" + sga[i].recebido + "</small></td>";
                    lista_sga += "<td style='text-align: center'><small>" + sga[i].dh + "</small></td>";
                    lista_sga += "</tr>";
                }
                lista_sga += "</tbody>";
                lista_sga += "</div>";
                lista_sga += "</table>";

                $("#EstoqueSGA").slideUp("slow").html("").delay(100).slideDown("fast").html(lista_sga);
            }
        });
    }
    function verifica() {

        $("#id_sga").text("0");
        $("#vfbSite").text("");
        $("#textoSite").text("");
        $("#os").val("");

        var opt = "option[value=" + 0 + "]";
        $("#atividade").find(opt).attr("selected", "selected");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "verifica" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var ativo = dados.ativo;
                var sga = dados.sga;


                if (ativo === "nd") {

                    $("#sga_itens").slideUp("fast");
                    $("#btCria").slideDown("fast");
                    $("#btCancela").slideUp("fast");

                } else {

                    $("#sga_itens").slideDown("fast");

                    $("#btCria").slideUp("fast");
                    $("#btCancela").slideDown("fast");

                    $("#id_sga").text(sga.id);
                    $("#vfbSite").text(sga.id_site);
                    $("#textoSite").text(sga.site);
                    $("#os").val(sga.os);

                    var opt = "option[value=" + 1 + "]";
                    $("#atividade").find(opt).attr("selected", "selected");
                }

            }
        });
    }

    function saldo(id, tipo) {

        $("#qtdBaixa").val("");

        if (tipo === "5") {

            var classe = "col rounded bg-danger rounded font-weight-bold text-white p-2 m-2";
            var msg = "<i class='icon-attention-2'></i> Deve ser informado em LITROS.";

            $("#detalheAlerta").slideDown("fast").addClass(classe).append(msg);

        } else {
            $("#detalheAlerta").slideUp("fast").removeClass(classe).text("");

        }

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "saldo", id: id, tipo: tipo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#qtdBaixa").val(dados.saldo);
            }
        });
    }

    function add(id, tipo, qtd) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "add", id: id, tipo: tipo, qtd: qtd },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";
                var msg = dados.msg;
                if (dados.erro === "1") {
                    $("#retornoSolicitacao").empty().slideDown("fast").addClass(classe).append(msg).delay(2500).slideUp("fast", function () {

                    });
                } else {
                    msg = "<i class='icon-ok-1'></i> " + msg;
                    classe = "bg bg-success rounded text-white font-weight-bold p-2 mt-2";
                    $("#retornoSolicitacao").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function () {

                        //  window.location.replace("SGA_SALDO");
                    });
                }
            }
        });
    }

    function Elemento_lista() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaElemento" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = eval(dados);

                var lista = "<div class='bg bg-dark rounded font-weight-bold text-white mb-2'>Ítens disponíveis para baixa</div>";

                lista += "<div id='elementoAtivo' class='row'>";

                for (var i = 0; i < linhas.length; i++) {

                    lista += "<div class='col'>";
                    lista += "<div class='card bg-light mb-2' style='max-width: 18rem;'>";
                    lista += "<div class='card-header'><span class='text-muted font-weight-bold' id='pa_" + linhas[i].id + "'>" + linhas[i].nome + "</span></div>";
                    lista += "<div class='card-body'>";
                    lista += "<ul class='list-group list'>";

                    var saldo = linhas[i].sma - linhas[i].baixa;
                    lista += "<li id='eListaEstrutura_" + linhas[i].id + "' class='list-group-item text-left'><i class='" + linhas[i].ico + "'></i><span id='descPA_id" + linhas[i].id + "'>" + linhas[i].descricao + "</span></li>";
                    lista += "<li class='list-group-item text-left'><span class='badge badge-light text-muted border'>SOLICITADO: <span id='qtd_" + linhas[i].id + "'>" + linhas[i].sma + "</span></span></b></li>";
                    lista += "<li class='list-group-item text-left'><span class='badge badge-light text-muted border'>PRÉ-BAIXA: <span id='qtdPB_" + linhas[i].id + "'>" + linhas[i].pBaixa + "</span></span></b></li>";
                    lista += "<li class='list-group-item text-left'><span class='badge badge-light text-muted border'>BAIXADO: <span id='qtdB_" + linhas[i].id + "'>" + linhas[i].baixa + "</span></span></b></li>";
                    lista += "<li class='list-group-item text-left'><span class='badge badge-light text-muted border'>SALDO: <span id='qtdS_" + linhas[i].id + "'>" + saldo + "</span></span></b></li>";
                    lista += "</ul>";
                    lista += "</div>";

                    lista += "</div>";
                    lista += "</div>";

                }

                lista += "</div>";
                $("#listaSaldo").slideDown("slow").html(lista);

                // modal_baixa();
            }
        });
    }

    $("#btCria").click(function () {

        var site = $("#vfbSite").text();
        var tipo = $("#atividade").val();
        var os = $("#os").val();

        Cria(site, tipo, os);

    });
    $("#btConclui").click(function () {

        var id = $("#id_sga").text();
        Conclui(id);

    });
    $("#btCancela").click(function () {

        var id = $("#id_sga").text();
        Cancela(id);

    });

    function form_tipo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "tipoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">SELECIONE UM ÍTEM</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#tipoDescarte").slideDown("fast").html(linhas);
            }
        });
    }

    function SiteProcura() {

        $("#btProcuraSite").click(function () {

            $("#formDadosSite").addClass("d-none");

            var txt = $("#formSite").val();

            $("#listaSite").slideUp("fast", function () {

                $("#retornoSite").text("");
                $("#retornoSite").removeClass();

                if (txt.length === 0) {

                    var tempo = 1500;
                    var classe = "bg-danger rounded font-weight-bold text-white p-2";
                    var msg = "<i class='icon-attention-2'></i> Necessário preencher o campo de busca.";

                    $("#retornoSite").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                } else {
                    $.ajax({
                        type: 'post', //Definimos o método HTTP usado
                        data: { acao: "SiteProcura", txt: txt },
                        dataType: 'json', //Definimos o tipo de retorno
                        url: 'EXTCADASTRO', //Definindo o arquivo onde serão buscados os dados
                        success: function (dados) {

                            if (dados.length === 0) {

                                var tempo = 1500;
                                var classe = "bg-danger rounded font-weight-bold text-white p-2";
                                var msg = "<i class='icon-attention-2'></i> Site não cadastrado.";

                                $("#retornoSite").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                            } else {
                                var linhas = eval(dados);

                                var lista = "";
                                lista += "<div class='card border'>";
                                lista += "<div class='card-header text-muted bg-light font-weight-bold'>Resultado da busca</div>";
                                lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                                lista += "<thead class='bg bg-light text-muted'>";
                                lista += "<tr>";
                                lista += "<th scope='col' class='text-center'>UF</th>";
                                lista += "<th scope='col' class='text-center'>SIGLA</th>";
                                lista += "<th scope='col' class='d-md-none text-center'>CIDADE <i class='icon-location'></i></th>";
                                lista += "<th scope='col' class='d-md-none text-center'>DESCRIÇÃO <i class='icon-doc-text'></i></th>";
                                lista += "<th scope='col' class='text-center'>SELECIONE <i class='icon-target-2'></i></th>";
                                lista += "</tr>";
                                lista += "</thead>";
                                lista += "<tbody>";
                                for (var i = 0; i < linhas.length; i++) {

                                    var tipo = linhas[i].tipo;
                                    var cidade = linhas[i].cidade; //doDestacaTexto(linhas[i].cidade, txt);
                                    var sigla = linhas[i].sigla; //doDestacaTexto(linhas[i].sigla, txt);
                                    var descricao = linhas[i].descricao; //doDestacaTexto(linhas[i].descricao, txt);


                                    if (tipo === "ADM") {
                                        tipo = "ADM";
                                    } else {
                                        tipo = "V - " + tipo.split(' ')[1];
                                    }

                                    lista += "<tr id='linha" + linhas[i].id + "'>";
                                    lista += "<td class='text-center'>" + linhas[i].uf + "</td>";
                                    lista += "<td class='text-center'>" + tipo + " - " + sigla + "</td>";
                                    lista += "<td class='d-md-none text-center'>" + cidade + "</td>";
                                    lista += "<td class='d-md-none text-center '>" + descricao + "</td>";
                                    lista += "<td class='text-center'><button class='btSelecionaSite btn btn-light btn-sm border' value='" + linhas[i].id + "'>Seleciona <i class='icon-target-2'></i></button></td>";
                                    lista += "</tr>";
                                }
                                lista += "</tbody>";
                                lista += "</table></div>";
                                $("#listaSite").slideDown("fast").html(lista);

                                SelecionaSite();
                            }
                        }
                    });
                }
            });
        });
    }

    function SelecionaSite() {

        $(".btSelecionaSite").click(function () {
            var site = $(this).attr('value');

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "selecionaSite", site: site },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'EXTCADASTRO', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    //  $("#formProcuraSite").slideUp("fast");
                    $("#pesquisaSITE").modal("hide");

                    $("#textoSite").text("");
                    $("#vfbSite").text("");

                    $("#rowSite").slideDown("fast", function () {
                        $("#textoSite").append("").append(dados.tipo + " " + dados.sigla);
                        $("#vfbSite").append("").append(dados.id);
                    });
                }
            });
        });
    }

    function Cria(site, tipo, os) {

        $("#retornoSolicitacao").slideUp("fast").text("");
        $("#retornoSolicitacao").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cria", site: site, tipo: tipo, os: os },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";
                var msg = dados.msg;
                if (dados.erro === "1") {
                    $("#retornoSolicitacao").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function () {

                    });
                } else {
                    msg = "<i class='icon-ok-1'></i> " + msg;
                    classe = "bg bg-success rounded text-white font-weight-bold p-2 mt-2";
                    $("#retornoSolicitacao").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function () {

                        verifica();
                    });
                }
            }
        });
    }

    function Conclui(id) {

        $("#retornoSolicitacao").slideUp("fast").text("");
        $("#retornoSolicitacao").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "conclui", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";
                var msg = dados.msg;
                if (dados.erro === "1") {
                    $("#retornoSolicitacao").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function () {

                    });
                } else {
                    msg = "<i class='icon-ok-1'></i> " + msg;
                    classe = "bg bg-success rounded text-white font-weight-bold p-2 mt-2";
                    $("#retornoSolicitacao").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function () {

                        window.location.replace("SGA_SALDO");
                    });
                }
            }
        });
    }

    function Cancela(id) {

        $("#retornoSolicitacao").slideUp("fast").text("");
        $("#retornoSolicitacao").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cancela", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";
                var msg = dados.msg;
                if (dados.erro === "1") {
                    $("#retornoSolicitacao").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function () {

                    });
                } else {
                    msg = "<i class='icon-ok-1'></i> " + msg;
                    classe = "bg bg-success rounded text-white font-weight-bold p-2 mt-2";
                    $("#retornoSolicitacao").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function () {

                        window.location.replace("SGA_SALDO");
                    });
                }
            }
        });
    }
});