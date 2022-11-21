$(document).ready(function() {


    // solicitacaoVerifica();

    $("#btnotaCadastra").click(function() {
        var site = $("#notaSite").text();
        var tipo = $("#notaTipo").val();
        var colaborador = $("#notaColaborador").val();
        var motivo = $("#notaMotivo").val();
        var os = $("#notaOs").val();
        var valor = $("#notaValor").val();
        var data = $("#notaData").val();
        var obs = $("#notaOBS").val();

        CriaNota(site, tipo, colaborador, motivo, os, valor, data, obs);
    });

    $(".btnotaVolta").click(function() {
        $("#notaOpc").slideDown("fast", function() {

            $("#notaFormulario").slideUp("fast");
            $("#notaListaPendente").slideUp("fast");
        });
    });

    $("#btNova").click(function() {

        SiteProcura();
        nota_tipo();
        nota_colaborador();
        nota_motivo();

        nota_qtd();

        $("#notaFormulario").modal("show");

    });
    $("#btPendente").click(function() {

        $("#notaOpc").slideUp("fast", function() {


            $("#notaListaPendente").slideDown("fast", function() {

                ListaNota();
                nota_qtd();
            });
        });
    });

    $("#btModalSite").click(function() {
        $("#pesquisaSITE").modal("show");

    });
    $(".btnotaVer").click(function() {

        $("#notaVer").modal("show");

    });

    $("#notaMotivo").change(function() {
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

    $("#formCancela").click(function() {
        var solicitacao = $("#NumeroSolicitacao").text();
        CancelaSolicitacao(solicitacao);
    });
    $("#formConclui").click(function() {
        var solicitacao = $("#NumeroSolicitacao").text();
        ConcluiSolicitacao(solicitacao);
    });

    $("#btRetornoPendente").click(function() {

        var href = "EXT_CADASTRO";
        window.location.href = href;
    });

    function SiteProcura() {

        $("#btProcuraSite").click(function() {

            $("#formDadosSite").addClass("d-none");

            var txt = $("#formSite").val();

            $("#listaSite").slideUp("fast", function() {

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
                        success: function(dados) {

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

        $(".btSelecionaSite").click(function() {
            var site = $(this).attr('value');

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "selecionaSite", site: site },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'EXTCADASTRO', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {

                    //  $("#formProcuraSite").slideUp("fast");
                    $("#pesquisaSITE").modal("hide");

                    $("#textoSite").text("");
                    $("#notaSite").text("");

                    $("#rowSite").slideDown("fast", function() {
                        $("#textoSite").append("").append(dados.tipo + " " + dados.sigla);
                        $("#notaSite").append("").append(dados.id);
                    });
                }
            });
        });
    }

    function nota_qtd() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "qtdNota" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'EXTCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

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
            url: 'EXTCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
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
            url: 'EXTCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = "<option value=\"0\">COLABORADOR</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].re + "\">" + linha[i].nome + "</option>";
                }
                $("#notaColaborador").slideDown("fast").html(linhas);
            }

        });
    }

    function nota_motivo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "notaMotivo" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'EXTCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = "<option value=\"0\">MOTIVO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#notaMotivo").slideDown("fast").html(linhas);
            }
        });
    }

    function CriaNota(site, tipo, colaborador, motivo, os, valor, data, obs) {

        $("#retornoNotaNova").text("");
        $("#retornoNotaNova").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "criaNota", site: site, tipo: tipo, colaborador: colaborador, motivo: motivo, os: os, valor: valor, data: data, obs: obs },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'EXTCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoNotaNova").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                } else {
                    var msg = "<i class='icon-ok-circle'></i> " + dados.msg;
                    var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoNotaNova").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

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
            url: 'EXTCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

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
        $(".btnotaVer").click(function() {

            $("#notaId").text("");

            var nota = $(this).attr('value');
            $("#notaId").append(nota);
            $("#notaVer").modal("show");

        });
    }

});