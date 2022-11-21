$(document).ready(function () {


    $("#pa_formulario1").slideDown("fast");
    cnLista();

    $("#btNovo").click(function () {

        SiteProcura();
        nota_tipo();
        nota_motivo();
        $("#modalNovo").modal("show");

    });
    $("#btFiltraNota").click(function () {

        $("#retornoNotaPesquisa").text("");
        $("#retornoNotaPesquisa").removeClass();

        var data1 = $("#notaData1").val();
        var data2 = $("#notaData2").val();
        var txt = $("#notaTXT").val();
        var status = $("#notaStatus").val();

        $("#erro").text("");
        $("#notaLista").slideUp("fast");
        $("#bt_xls").attr("disabled", true);

        var tempo = 1500;
        var classe = "bg bg-danger rounded text-white font-weight-bold p-2";

        if (txt === "" && status === "0" && data1 === "" && data2 === "") {
            var msg = "<i class='icon-attention'></i> Informações insuficientes.";

            $("#retornoNotaPesquisa").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");
        } else {
            notaProcura(txt, 1, data1, data2);
        }
    });
    $("#btnotaCadastra").click(function () {
        var site = $("#notaSite").text();
        var tipo = $("#notaTipo").val();
        var motivo = $("#notaMotivo").val();
        var os = $("#notaOs").val();
        var arq = $("#md5Arquivo").val();
        var valor = $("#notaValor").val();
        var data = $("#notaData").val();
        var hora = $("#notaHora").val();
        var obs = $("#notaOBS").val();

        CriaNota(site, tipo, motivo, os, arq, valor, data, hora, obs);
    });

    $(".btnotaVolta").click(function () {
        $("#notaOpc").slideDown("fast", function () {

            $("#notaFormulario").slideUp("fast");
            $("#notaListaPendente").slideUp("fast");
        });
    });


    $("#btPendente").click(function () {

        $("#notaOpc").slideUp("fast", function () {


            $("#notaListaPendente").slideDown("fast", function () {

                ListaNota();
                nota_qtd();
            });
        });
    });

    $("#btModalSite").click(function () {
        $("#pesquisaSITE").modal("show");

    });
    $("#btModalArquivo").click(function () {

        $("#upload").modal("show");
        //    btModalArquivo
    });

    $("#notaMotivo").change(function () {
        var motv = $("#notaMotivo").val();

        if (motv < 5) {
            $("#notaOs").attr("disabled", false);
            var classe = "bg-danger rounded text-warning pt-2 pb-2";

            $("#notaOs").addClass(classe);

        } else {

            $("#notaOs").attr("disabled", true);
            var classe = "bg-danger rounded text-warning pt-2 pb-2";

            $("#notaOs").removeClass(classe);

        }

    });

    $("#formCancela").click(function () {
        var solicitacao = $("#NumeroSolicitacao").text();
        CancelaSolicitacao(solicitacao);
    });
    $("#formConclui").click(function () {
        var solicitacao = $("#NumeroSolicitacao").text();
        ConcluiSolicitacao(solicitacao);
    });

    $("#btRetornoPendente").click(function () {

        var href = "EXT_CADASTRO";
        window.location.href = href;
    });
    $("#bt_xls").click(function () {
        var txt = $("#notaTXT").val();
        var dataInicio = $("#notaData1").val();
        var dataFim = $("#notaData2").val();
        var cn = $("#cnLista").val();

        var href = "XLSCCC?acao=xls&txt=" + txt + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim + "&cn=" + cn;

        window.open(href);
    });
    function notaProcura(txt, cn, data1, data2) {

        $("#retornoNotaPesquisa").text("");
        $("#retornoNotaPesquisa").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "notaProcura", txt: txt, cn: cn, data1: data1, data2: data2 },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CCCCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var verifica = eval(dados);
                var resultados = verifica.length;

                if (resultados === 0) {

                    var tempo = 1500;
                    var classe = "bg-danger rounded text-white font-weight-bold p-2";
                    var msg = "<i class='icon-attention'></i> Nenhuma correspondência.";

                    $("#retornoNotaPesquisa").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");


                } else {
                    var tempo = 1500;
                    var classe = "bg-success rounded text-white font-weight-bold p-2";
                    var msg = "<i class='icon-hourglass'></i> " + resultados + " resultados encontrados.";

                    $("#retornoNotaPesquisa").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast", function () {

                        $("#bt_xls").attr("disabled", false);

                        var linhas = eval(dados);

                        var lista = "";
                        lista += "<div class='card border-light mt-2 p-1'>";
                        lista += "<div class='card-header font-weight-bold'>Resultado da busca</div>";
                        lista += "<table class='table table-striped w-auto'>";
                        lista += "<thead class='thead-dark'>";
                        lista += "<tr>";
                        lista += "<th scope='col'>ID</th>";
                        lista += "<th scope='col'>CN</th>";
                        lista += "<th scope='col'>VALOR</th>";
                        lista += "<th scope='col'>DATA</th>";
                        lista += "<th scope='col'>ABRIR</th>";
                        lista += "</tr>";
                        lista += "</thead>";
                        lista += "<tbody>";
                        for (var i = 0; i < linhas.length; i++) {
                            lista += "<tr id='linha" + linhas[i].id + "'>";
                            lista += "<td>" + linhas[i].id + "</td>";
                            lista += "<td>" + linhas[i].cn + "</td>";
                            lista += "<td >" + linhas[i].valor + "</td>";
                            lista += "<td >" + linhas[i].dataU + "</td>";
                            lista += "<td><button type='button' value='" + linhas[i].id + "' class='btDetalheNota btn btn-outline-info btn-sm'><i class='icon-popup'></i> Ver</button></td>";
                            lista += "</tr>";
                        }
                        lista += "</tbody>";
                        lista += "</table>";

                        $("#notaLista").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);

                        formModal();
                    });
                }
            }
        });
    }
    function formModal() {

        $(".btDetalheNota").click(function () {
            var nota = $(this).attr('value');

            NotaDetalhe(nota);

            $("#DetalheNota").modal("show");
        });
    }
    function NotaDetalhe(nota) {

        $("#formAprova").attr("disabled", true);
        $("#formReprova").attr("disabled", true);
        $("#formIlegivel").attr("disabled", true);
        $("#formAprova").empty();
        $("#formAprova").append("<i class='icon-ok-circled text-success'></i> Validar");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "notaDetalhe", nota: nota },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CCCCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var nota = dados.detalhe;

                $("#notaNumeroDetalhe").text(nota.id);
                $("#notaSolicitanteReDetalhe").text(nota.re);
                $("#notaSolicitanteNomeDetalhe").text(nota.nome);
                $("#notaDataDetalhe").text(nota.dataU);
                $("#notaTipoDetalhe").text(nota.tipo);
                $("#notaSiteDetalhe").text(nota.cn + " " + nota.site);
                $("#notaMotivoDetalhe").text(nota.motivo);
                $("#notaOsDetalhe").text(nota.os);
                $("#notaValorDetalhe").text(nota.valor);
                $("#notaStatusDetalhe").text(nota.status);
                $("#obs2").text(nota.obs);
                $("#cadastroDetalhe").text(nota.data + " " + nota.hora);

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
                        url: 'CCCCADASTRO', //Definindo o arquivo onde serão buscados os dados
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

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "selecionaSite", site: site },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'CCCCADASTRO', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    //  $("#formProcuraSite").slideUp("fast");
                    $("#pesquisaSITE").modal("hide");

                    $("#textoSite").text("");
                    $("#notaSite").text("");

                    $("#rowSite").slideDown("fast", function () {
                        $("#textoSite").append("").append(dados.tipo + " " + dados.sigla);
                        $("#notaSite").append("").append(dados.id);
                    });
                }
            });
        });
    }
    function nota_motivo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "notaMotivo" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CCCCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">MOTIVO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#notaMotivo").slideDown("fast").html(linhas);
            }
        });
    }
    function nota_tipo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "notaTipo" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CCCCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">TIPO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#notaTipo").slideDown("fast").html(linhas);
            }
        });
    }
    function nota_qtd() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "qtdNota" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CCCCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#notaQtd").text("");
                $("#notaQtd").append(dados.qtd);
            }

        });
    }

    function nota_tipo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "notaTipo" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CCCCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">TIPO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#notaTipo").slideDown("fast").html(linhas);
            }
        });
    }

    function nota_colaborador() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "notaColaborador" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CCCCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">COLABORADOR</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].re + "\">" + linha[i].nome + "</option>";
                }
                $("#notaColaborador").slideDown("fast").html(linhas);
            }

        });
    }
    function cnLista() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cnLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CCCCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">CN</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#cnLista").slideDown("fast").html(linhas);
            }
        });
    }
    function nota_motivo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "notaMotivo" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CCCCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">MOTIVO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#notaMotivo").slideDown("fast").html(linhas);
            }
        });
    }

    function CriaNota(site, tipo, motivo, os, arq, valor, data, hora, obs) {

        $("#retornoNotaNova").text("");
        $("#retornoNotaNova").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "criaNota", site: site, tipo: tipo, motivo: motivo, os: os, arq: arq, valor: valor, data: data, hora: hora, obs: obs },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CCCCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.erro === "1") {

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoNotaNova").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                } else {
                    var msg = "<i class='icon-ok-circle'></i> " + dados.msg;
                    var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoNotaNova").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        window.location.reload();
                    });
                }
            }
        });
    }

    function ListaNota() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaNota" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CCCCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var qtd = $("#notaQtd").text();
                var notas = dados.notas;

                var linhas = eval(notas);

                var lista = "";
                lista += "<div class='card border'>";
                lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                lista += "<thead class='bg bg-light text-muted'>";
                lista += "<tr>";
                lista += "<th scope='col' class='text-center'>DATA</th>";
                lista += "<th scope='col' class='text-center'>VALOR</th>";
                lista += "<th scope='col' class='text-center'>SITE</th>";
                lista += "<th scope='col' class='text-center'>STATUS</th>";
                lista += "<th scope='col' class='text-center'><i class='icon-popup'></i> VER</th>";
                lista += "</tr>";
                lista += "</thead>";
                lista += "<tbody>";
                for (var i = 0; i < linhas.length; i++) {
                    lista += "<tr>";
                    lista += "<td class='text-center'>" + linhas[i].data + "</td>";
                    lista += "<td class='text-center'> R$ " + linhas[i].valor + "</td>";
                    lista += "<td class='text-center'>" + linhas[i].site + "</td>";
                    lista += "<td class='text-center'>" + linhas[i].status + "</td>";
                    lista += "<td class='text-center'><button class='btnotaVer btn btn-light btn-sm border' value='" + linhas[i].id + "'><i class='icon-popup'></i> Ver</button></td>";
                    lista += "</tr>";
                }
                lista += "</tbody>";
                lista += "</table></div>";

                $("#notaPendente").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);

                VerNota();
            }
        });
    }

    function VerNota() {
        $(".btnotaVer").click(function () {

            $("#notaId").text("");

            var nota = $(this).attr('value');
            $("#notaId").append(nota);
            $("#notaVer").modal("show");

        });
    }

});