$(document).ready(function () {
    $('#lc_obs').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9,. ]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });

    $("#scp_atividade").change(function () {

        var tipo = $("#scp_atividade").val();
        var placeholder = "*";


        $("#scp_os").val("").slideUp("fast");

        if (tipo === "4") {
            placeholder = "OS PRISMA*";
            $("#scp_os").val("").slideDown("fast");

        } else if (tipo === "2") {
            placeholder = "NÚMERO TA*";
            $("#scp_os").val("").slideDown("fast");

        } else if (tipo === "3") {
            placeholder = "NÚMERO TP*";
            $("#scp_os").val("").slideDown("fast");

        } else {
            placeholder = "NÃO SE APLICA";
            $("#scp_os").val("").slideUp("fast");

        }

        $("#scp_os").attr({ placeholder: placeholder });

    });



    $("#scp_formulario1").slideDown("fast");
    form_cn();

    $("#btFiltra").click(function () {

        var classe = "bg-danger rounded font-weight-bold text-white p-2";
        var msg = "<i class='icon-attention-2'></i> Informações insuficientes.";
        $("#retornoFiltro").text("");
        $("#retornoFiltro").removeClass();

        var dataInicio = $("#scp_DataInicio").val();
        var dataFinal = $("#scp_DataFIm").val();

        $("#ListaSCP").slideUp("fast");
        if (!dataInicio && !dataFinal) {

            $("#retornoFiltro").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast");

        } else {
            SCProcura(dataInicio, dataFinal);
        }
    });
    $("#btFormNovo").click(function () {

        oculta();

        $("#scp_formulario_novo").modal("show");

        SiteProcura();
        form_colaborador();
        form_gas();
    });
    $("#btModalSite").click(function () {
        $("#pesquisaSITE").modal("show");

    });

    $("#bt_cadastro_scp").click(function () {

        cadastro();
    });

    $("#bt_cadastro_scp_voltar").click(function () {

        oculta();
        window.location.replace("SCP_REGISTRO");
    });

    $("#bt_formVolta").click(function () {

        oculta();
        $("#ListaSCP").slideDown("fast");

    });

    $("#bt_xls").click(function () {
        var cn = $("#lc_cn").val();
        var dataInicio = $("#lc_DataInicio").val();
        var dataFim = $("#lc_DataFIm").val();

        var href = "SCPXLS?acao=xls&cn=" + cn + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim;

        window.open(href);
    });

    function form_gas() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaAtividade" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCPREGISTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">ATIVIDADE</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#scp_atividade").slideDown("fast").html(linhas);
            }
        });
    }
    function form_colaborador() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaColaborador" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCPREGISTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">COLABORADOR</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].re + "\">" + linha[i].nome + "</option>";
                }
                $("#scp_colaborador").slideDown("fast").html(linhas);
            }
        });
    }
    function form_cn() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaCN" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCPREGISTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = "<option value=\"0\">CN</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#lc_cn").slideDown("fast").html(linhas);

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
                    $("#scp_site").text("");

                    $("#rowSite").slideDown("fast", function () {
                        $("#textoSite").append("").append(dados.tipo + " " + dados.sigla);
                        $("#scp_site").append("").append(dados.id);
                    });
                }
            });
        });
    }

    function SCProcura(dataInicio, dataFinal) {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SCPProcura", dataInicio: dataInicio, dataFinal: dataFinal },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCPREGISTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var verifica = eval(dados);

                var classe = "bg-danger rounded font-weight-bold text-white p-2";
                var msg = "<i class='icon-attention-2'></i> Nenhum resultado encontrado.";

                $("#retornoFiltro").text("");
                $("#retornoFiltro").removeClass();

                if (verifica.length === 0) {
                    $("#ListaSCP").slideUp("fast").html("");

                    $("#retornoFiltro").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast");

                } else {
                    var linhas = eval(dados);

                    var lista = "<div class='card border-light mt-2 p-1'>";
                    lista += "<div class='card-header font-weight-bold'>Resultado da busca</div>";
                    lista += "<table class='table table-striped w-auto mt-1'>";
                    lista += "<thead class='thead-dark'>";
                    lista += "<tr>";
                    lista += "<th style='text-align: center' scope='col'>SITE</th>";
                    lista += "<th style='text-align: center' scope='col'>ATIVIDADE</th>";
                    lista += "<th style='text-align: center' scope='col'>DATA</th>";
                    lista += "<th style='text-align: center' scope='col'>STATUS</th>";
                    lista += "<th style='text-align: center' scope='col'>VER</th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody>";
                    for (var i = 0; i < linhas.length; i++) {
                        lista += "<tr id='linha" + linhas[i].id + "'>";
                        lista += "<td style='text-align: center'>" + linhas[i].site + "</td>";
                        lista += "<td style='text-align: center'>" + linhas[i].atividade + "</td>";
                        lista += "<td style='text-align: center'>" + linhas[i].data + "</td>";
                        lista += "<td style='text-align: center'>" + linhas[i].status + "</td>";
                        lista += "<td style='text-align: center'><button value='" + linhas[i].id + "' class='btDetalheLancamento btn btn-sm btn-light border text-muted'><i class='icon-popup text-info'></i> Ver</button>";
                        lista += "</tr>";
                    }
                    lista += "</tbody>";
                    lista += "</div>";
                    lista += "</table>";

                    $("#ListaSCP").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);
                    // $("#DivXls").slideDown("fast");
                    lancamentotoDetalhe();

                }
            }
        });
    }

    function lancamentotoDetalhe() {

        $(".btDetalheLancamento").click(function () {
            var id = $(this).attr('value');
            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "SCPDetalhe", id: id },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SCPREGISTRO', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {
                    oculta();
                    $("#DetalheLancamento").slideUp("fast");

                    $("#SCPId").text(dados.id);
                    $("#SCPCN_SITE").text(dados.cn + "/" + dados.site);
                    $("#SCPDataRegistro").text(dados.data + " " + dados.hora);
                    $("#SCPEntrada").text(dados.data1 + " " + dados.hora1);
                    $("#SCPSaida").text(dados.data2 + " " + dados.hora2);
                    $("#SCPJustificativa").text(dados.justificativa);
                    $("#SCPStatus").text(dados.status);
                    $("#SCPObs").text(dados.avaliacao);


                    $("#DetalheLancamento").modal("show");

                }
            });
        });
    }

    function cadastro() {

        var site = $("#scp_site").text();
        var atividade = $("#scp_atividade").val();
        var colaborador = $("#scp_colaborador").val();
        var os = $("#scp_os").val();
        var obs = $("#scp_obs").val();
        var data1 = $("#scp_data_1").val();
        var hora1 = $("#scp_hora_1").val();
        var data2 = $("#scp_data_2").val();
        var hora2 = $("#scp_hora_2").val();
        var delay = 2000;

        $("#retornoLancamento").text("");
        $("#retornoLancamento").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cadastroSCP", site: site, atividade: atividade, colaborador: colaborador, os: os, obs: obs, data1: data1, hora1: hora1, data2: data2, hora2: hora2 },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCPREGISTRO', //Definindo o arquivo onde serão buscados os dados
            beforeSend: function () {

                $("#bt_cadastro_scp").attr("disabled", true);

                var classe = "bg-info rounded font-weight-bold text-white pt-2 pb-2";
                var ico = "<i class='icon-clock'></i>";
                var msg = "Aguarde...";
                $("#retornoLancamento").slideDown("fast").addClass(classe).append(ico + " " + msg);

            },
            success: function (dados) {

                setTimeout(function () {


                    if (dados.erro === "1") {

                        $("#retornoLancamento").text("");
                        $("#retornoLancamento").removeClass();

                        var tempo = 2000;
                        var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                        var ico = "<i class='icon-attention'></i>";

                        $("#retornoLancamento").slideDown("fast").addClass(classe).append(ico + " " + dados.msg).delay(tempo).slideUp("fast", function () {

                            $("#bt_cadastro_scp").attr("disabled", false);
                        });
                    } else {

                        $("#retornoLancamento").text("");
                        $("#retornoLancamento").removeClass();

                        var tempo = 1800;
                        var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";
                        var ico = "<i class='icon-ok-circled-1'></i>";

                        $("#retornoLancamento").slideDown("fast").addClass(classe).append(ico + " " + dados.msg).delay(tempo).slideUp("fast", function () {
                            window.location.replace("SCP_REGISTRO");
                        });
                    }
                }, delay);
            }
        });
    }

    function oculta() {

        $("#ac_formulario_novo").slideUp("fast");
        $("#ac_formulario1").slideUp("fast");
        $("#ListaSCP").slideUp("fast");
        $("#DetalheLancamento").slideUp("fast");
        $("#DivXls").slideUp("fast");
    }
});