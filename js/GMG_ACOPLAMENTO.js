$(document).ready(function () {

    $("#ac_formulario1").slideDown("fast");
    form_cn();

    $("#btFiltra").click(function () {

        var cn = $("#ac_cn").val();
        var txt = $("#ac_txt").val();
        var dataInicio = $("#ac_DataInicio").val();
        var dataFinal = $("#ac_DataFIm").val();

        $("#erro").text("");
        $("#ListaAC").slideUp("fast");
        if (cn === "0" && !dataInicio && !dataFinal) {
            $("#erro").slideDown("fast").append("Informações insuficientes.").delay(1500).slideUp("fast");
        } else {
            ACProcura(cn, dataInicio, dataFinal, txt);
        }
    });
    $("#btFormNovo").click(function () {

        oculta();

        $("#ac_formulario_novo").modal("show");

        SiteProcura();
        form_gmg();
    });
    $("#btModalSite").click(function () {
        $("#pesquisaSITE").modal("show");

    });

    $("#bt_cadastro_ac").click(function () {

        cadastro();
    });

    $("#bt_cadastro_ac_voltar").click(function () {

        oculta();
        window.location.replace("GMG_ACOPLAMENTO");

    });

    $("#bt_formVolta").click(function () {

        oculta();
        $("#ListaAC").slideDown("fast");
        $("#ac_formulario1").slideDown("fast");

    });

    $("#bt_xls").click(function () {
        var cn = $("#ac_cn").val();
        var dataInicio = $("#ac_DataInicio").val();
        var dataFim = $("#ac_DataFIm").val();
        var txt = $("#ac_txt").val();

        var href = "GMGXLS?acao=xls&cn=" + cn + "&acao=xls&txt=" + txt + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim;

        window.open(href);
    });

    function form_gmg() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaGMG" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'GMGACOPLAMENTO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">GMG</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#ac_gmg").slideDown("fast").html(linhas);
            }
        });
    }

    function form_cn() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaCN" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'GMGACOPLAMENTO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = "<option value=\"0\">CN</option>";
                    var linha = eval(dados);
                    for (var i = 0; i < linha.length; i++) {
                        linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                    }
                    $("#ac_cn").slideDown("fast").html(linhas);
                }
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
                                var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
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

            $("#rowSite").slideUp("fast");

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "selecionaSite", site: site },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'EXTCADASTRO', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    //  $("#formProcuraSite").slideUp("fast");
                    $("#pesquisaSITE").modal("hide");

                    $("#textoSite").text("");
                    $("#ac_site").text("");

                    $("#rowSite").slideDown("fast", function () {
                        $("#textoSite").append("").append(dados.tipo + " " + dados.sigla);
                        $("#ac_site").append("").append(dados.id);
                    });
                }
            });
        });
    }

    function ACProcura(cn, dataInicio, dataFinal, txt) {

        var delay = 900;

        $("#retornoFiltro").text("");
        $("#retornoFiltro").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "ACProcura", cn: cn, dataInicio: dataInicio, dataFinal: dataFinal, txt: txt },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'GMGACOPLAMENTO', //Definindo o arquivo onde serão buscados os dados
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

                            $("#retornoFiltro").text("");
                            $("#retornoFiltro").removeClass();

                            $("#btFiltra").attr("disabled", false);
                        });

                    } else {

                        $("#retornoFiltro").text("");
                        $("#retornoFiltro").removeClass();

                        var tempo = 1800;
                        var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";
                        var ico = "<i class='icon-ok-circled-1'></i>";
                        var msg = verifica.length + " Solicitações encontradas."

                        $("#retornoFiltro").slideDown("fast").addClass(classe).append(ico + " " + msg).delay(tempo).slideUp("fast", function () {

                            $("#btFiltra").attr("disabled", false);


                            var linhas = eval(dados);

                            var lista = "<div class='card border-light mt-2 p-1'>";
                            lista += "<div class='card-header font-weight-bold'>Resultado da busca</div>";
                            lista += "<table class='table table-striped w-auto mt-1'>";
                            lista += "<thead class='thead-dark'>";
                            lista += "<tr>";
                            lista += "<th scope='col'>GMG</th>";
                            lista += "<th scope='col'>SITE</th>";
                            lista += "<th scope='col'>DATA</th>";
                            lista += "<th scope='col'>VER</th>";
                            lista += "</tr>";
                            lista += "</thead>";
                            lista += "<tbody>";
                            for (var i = 0; i < linhas.length; i++) {
                                lista += "<tr id='linha" + linhas[i].id + "'>";
                                lista += "<td>" + linhas[i].gmg + "</td>";
                                lista += "<td>" + linhas[i].site + "</td>";
                                lista += "<td>" + linhas[i].data + "</td>";
                                lista += "<td><button value='" + linhas[i].id + "' class='btDetalheAcoplamento btn btn-sm btn-light border text-muted'><i class='icon-popup text-info'></i> Ver</button>";
                                lista += "</tr>";
                            }

                            lista += "</tbody>";
                            lista += "</table>";

                            $("#ListaAC").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);
                            $("#DivXls").slideDown("fast");
                            acoplamentoDetalhe();
                        });
                    }

                }, delay);
            }

        });
    }

    function acoplamentoDetalhe() {

        $(".btDetalheAcoplamento").click(function () {
            var id = $(this).attr('value');

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "ACDetalhe", id: id },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'GMGACOPLAMENTO', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    oculta();
                    $("#DetalheAcoplamento").slideUp("fast");

                    $("#ACId").text(dados.id);
                    $("#ACGMG").text(dados.gmg);
                    $("#ACTecnicoNOME").text(dados.nome);
                    $("#ACTecnicoRE").text(dados.re);
                    $("#ACCoordenadorNOME").text(dados.nomeCoordenador);
                    $("#ACCoordenadorRE").text(dados.reCoordenador);
                    $("#ACRegistro").text(dados.data + "/" + dados.hora);
                    $("#ACInicio").text(dados.data_inicio + "/" + dados.hora_inicio);
                    $("#ACFinal").text(dados.data_final + "/" + dados.hora_final);
                    $("#ACCN_SITE").text(dados.cn + "/" + dados.site);
                    $("#ACCN_OBS").text(dados.obs);
                    $("#DetalheAcoplamento").slideDown("fast");

                }
            });
        });
    }

    function cadastro() {

        var gmg = $("#ac_gmg").val();
        var site = $("#ac_site").text();
        var ta = $("#ac_ta").val();
        var observacoes = $("#ac_observacoes").val();
        var data_inicio = $("#ac_data_inicio").val();
        var hora_inicio = $("#ac_hora_inicio").val();
        var data_final = $("#ac_data_final").val();
        var hora_final = $("#ac_hora_final").val();
        var delay = 2000;

        $("#retornoAcoplamentoNova").text("");
        $("#retornoAcoplamentoNova").removeClass();


        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cadastroAC", gmg: gmg, site: site, ta: ta, observacoes: observacoes, data_inicio: data_inicio, hora_inicio: hora_inicio, data_final: data_final, hora_final: hora_final },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'GMGACOPLAMENTO', //Definindo o arquivo onde serão buscados os dados
            beforeSend: function () {
                $("#bt_cadastro_ac").attr("disabled", true);

                var classe = "bg-info rounded font-weight-bold text-white pt-2 pb-2";
                var ico = "<i class='icon-clock'></i>";
                var msg = "Aguarde...";
                $("#retornoAcoplamentoNova").slideDown("fast").addClass(classe).append(ico + " " + msg);

            },
            success: function (dados) {

                setTimeout(function () {

                    if (dados.erro === "1") {

                        $("#retornoAcoplamentoNova").text("");
                        $("#retornoAcoplamentoNova").removeClass();

                        var tempo = 2000;
                        var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                        var ico = "<i class='icon-attention'></i>";

                        $("#retornoAcoplamentoNova").slideDown("fast").addClass(classe).append(ico + " " + dados.msg).delay(tempo).slideUp("fast", function () {

                            $("#bt_cadastro_ac").attr("disabled", false);
                        });

                    } else {

                        $("#retornoAcoplamentoNova").text("");
                        $("#retornoAcoplamentoNova").removeClass();

                        var tempo = 1800;
                        var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";
                        var ico = "<i class='icon-ok-circled-1'></i>";

                        $("#retornoAcoplamentoNova").slideDown("fast").addClass(classe).append(ico + " " + dados.msg).delay(tempo).slideUp("fast", function () {
                            window.location.replace("GMG_ACOPLAMENTO");
                        });
                    }
                }, delay);
            }
        });
    }

    function oculta() {

        $("#ac_formulario_novo").slideUp("fast");
        $("#ac_formulario1").slideUp("fast");
        $("#ListaAC").slideUp("fast");
        $("#DetalheAcoplamento").slideUp("fast");
        $("#DivXls").slideUp("fast");
    }
});