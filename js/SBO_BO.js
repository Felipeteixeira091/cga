$(document).ready(function () {

    iniciaTratativaBO();
    confirmaInformacoesBO();
    concluiTratativaBO();
    concluiTratativaBO_Prisma();
    BOCancela();
    cancelaTratativaBO();
    $("#formulario1").slideDown("fast");
    form_cn();
    form_status();

    $("#bo_cn").change(function () {

        var cn = $("#bo_cn").val();
        form_site(cn);
    });

    $("#btFiltra").click(function () {

        var cn = $("#bo_cn").val();
        var site = $("#bo_site").val();
        var status = $("#bo_status").val();
        var dataInicio = $("#bo_DataInicio").val();
        var dataFinal = $("#bo_DataFIm").val();

        $("#retornoFiltra").text("");
        $("#retornoFiltra").removeClass();

        $("#ListaBO").slideUp("fast");
        if (cn === "0" && site === "0" && status === "0" && !dataInicio && !dataFinal) {

            var msg = "<i class='bi bi-exclamation-octagon-fill'></i> Informações insuficientes.";
            var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
            $("#retornoFiltra").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

        } else {

            BOProcura(cn, site, status, dataInicio, dataFinal);
        }
    });
    $("#btFormNovo").click(function () {
        oculta();
        form_site("novo");
        form_fechadura_status();
        SiteProcura();
        $("#formulario_novo").modal("show");
    });

    $("#btnCadastro").click(function () {

        cadastro();
    });

    $("#btnCadastro_voltar").click(function () {

        oculta();
        window.location.replace("SBO_BO");
    });

    $("#bt_formVolta").click(function () {

        oculta();
        $("#ListaBO").slideDown("fast");
        $("#formulario1").slideDown("fast");

    });

    $("#btXls").click(function () {

        var cn = $("#bo_cn").val();
        var site = $("#bo_site").val();
        var status = $("#bo_status").val();
        var dataInicio = $("#bo_DataInicio").val();
        var dataFim = $("#bo_DataFIm").val();

        var href = "SBOXLS?acao=xls&cn=" + cn + "&site=" + site + "&status=" + status + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim;

        window.open(href);
    });

    $("#btModalSite").click(function () {
        $("#pesquisaSITE").modal("show");

    });

    $(".bt_editaOBS").click(function () {

        $("#obsEdita").val("");

        $("#form_editaObs").modal("show");
        var id = $(this).attr('value');

        $("#tituloCampo").text(id);

        var texto = $("#Detalhe_" + id).text();

        $("#obsEdita").val(texto);
    });
    $("#bt_obsEdita").click(function () {

        editaForm();
    });

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
                    var msg = "<i class='bi bi-exclamation-octagon-fill'></i> Necessário preencher o campo de busca.";

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
                                var msg = "<i class='bi bi-exclamation-octagon-fill'></i> Site não cadastrado.";
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

                    $("#textoSite").append("").append(dados.tipo + " " + dados.sigla);
                    $("#ac_site").append("").append(dados.id);

                }
            });
        });
    }

    function form_cn() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaCN" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SBOBO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = "<option value='0'>CN</option>";
                    var linha = eval(dados);
                    linhas += "<option value='v2'>VIVO2 MG</option>";
                    for (var i = 0; i < linha.length; i++) {
                        linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                    }
                    $("#bo_cn").slideDown("fast").html(linhas);

                }
            }
        });
    }

    function form_site(cn) {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaSITE", cn: cn },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SBOBO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = "<option value=\"0\">SITE</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].sigla + " - " + linha[i].cn + "</option>";
                }
                $("#bo_site").slideDown("fast").html(linhas);
            }
        });
    }

    function form_status() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaSTATUS" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SBOBO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = "<option value=\"0\">STATUS</option>";
                    var linha = eval(dados);
                    for (var i = 0; i < linha.length; i++) {
                        linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                    }

                    $("#bo_status").slideDown("fast").html(linhas);
                }
            }
        });
    }

    function form_fechadura_status() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaFechaduraStatus" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SBOBO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = "<option value=\"0\">STATUS FECHADURA</option>";
                    var linha = eval(dados);
                    for (var i = 0; i < linha.length; i++) {
                        linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                    }

                    $("#fechadura_bluetooth_status").slideDown("fast").html(linhas);
                }
            }
        });
    }
    function confirmaInformacoesBO() {

        $("#bt_confirma").click(function () {

            $("#bt_confirma").attr("disabled", false);
            $("#retornoDetalhe").text("");
            $("#retornoDetalhe").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "confirmaInformacoesBO", bo_id: $("#Detalhe_id").text() },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SBOBO', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    if (dados.erro === "1") {

                        var msg = "<i class='bi bi-exclamation-octagon-fill'></i> " + dados.msg;
                        var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoDetalhe").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {
                            $("#DetalheBO").modal("hide");

                        });
                    } else {

                        var msg = "<i class='bi bi-check-square'></i> " + dados.msg;
                        var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoDetalhe").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {
                            $("#DetalheBO").modal("hide");

                            BOProcura($("#bo_cn").val(), $("#bo_site").val(), $("#bo_status").val(), $("#bo_DataInicio").val(), $("#bo_DataFIm").val());
                        });
                    }
                    $("#bt_confirma").attr("disabled", false);
                }
            });

        });
    }
    function iniciaTratativaBO() {

        $("#bt_bt1").click(function () {

            $("#bt_bt1").attr("disabled", true);

            $("#retornoDetalhe").text("");
            $("#retornoDetalhe").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "iniciaTratativaBO", bo_id: $("#Detalhe_id").text() },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SBOBO', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    if (dados.erro === "1") {

                        var msg = "<i class='bi bi-exclamation-octagon-fill'></i> " + dados.msg;
                        var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoDetalhe").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {
                            $("#DetalheBO").modal("hide");

                        });
                    } else {

                        var msg = "<i class='bi bi-check-square'></i> " + dados.msg;
                        var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoDetalhe").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {
                            $("#DetalheBO").modal("hide");

                            BOProcura($("#bo_cn").val(), $("#bo_site").val(), $("#bo_status").val(), $("#bo_DataInicio").val(), $("#bo_DataFIm").val());
                        });
                    }
                    $("#bt_bt1").attr("disabled", false);
                }
            });

        });

    }

    function cancelaTratativaBO() {

        $("#bt_bt5").click(function () {

            $("#retornoDetalhe").text("");
            $("#retornoDetalhe").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "cancelaTratativaBO", bo_id: $("#Detalhe_id").text(), obs: $("#obsCancelamento").val() },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SBOBO', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    if (dados.erro === "1") {
                        var msg = "<i class='bi bi-exclamation-octagon-fill'></i> " + dados.msg;
                        var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoDetalhe").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {
                            //$("#DetalheBO").modal("hide");

                        });
                    } else {
                        var msg = "<i class='bi bi-check-square'></i> " + dados.msg;
                        var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoDetalhe").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {
                            $("#DetalheBO").modal("hide");

                            BOProcura($("#bo_cn").val(), $("#bo_site").val(), $("#bo_status").val(), $("#bo_DataInicio").val(), $("#bo_DataFIm").val());

                        });
                    }
                }
            });
        });
    }

    function concluiTratativaBO() {

        $("#bt_bt3").click(function () {

            $("#bt_bt3").attr("disabled", false);

            $("#retornoDetalhe").text("");
            $("#retornoDetalhe").removeClass();

            var pct = {
                acao: "statusUpdate",
                bo_id: $("#Detalhe_id").text(),
                sinistro: $("#bo_sinistro").val(),
                os: $("#bo_os").val(),
                status: 4
            }
            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: pct,
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SBOBO', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    if (dados.erro === "1") {
                        var msg = "<i class='bi bi-exclamation-octagon-fill'></i> " + dados.msg;
                        var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoDetalhe").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        });
                    } else {
                        var msg = "<i class='bi bi-check-square'></i> " + dados.msg;
                        var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoDetalhe").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {
                            $("#DetalheBO").modal("hide");

                            BOProcura($("#bo_cn").val(), $("#bo_site").val(), $("#bo_status").val(), $("#bo_DataInicio").val(), $("#bo_DataFIm").val());
                        });
                    }
                    $("#bt_bt3").attr("disabled", false);
                }
            });
        });
    }
    function concluiTratativaBO_Prisma() {

        $("#bt_bt6").click(function () {

            $("#retornoDetalhe").text("");
            $("#retornoDetalhe").removeClass();

            var pct = {
                acao: "statusUpdate",
                bo_id: $("#Detalhe_id").text(),
                sinistro: $("#bo_sinistro").val(),
                os: $("#bo_os").val(),
                status: 7
            }
            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: pct,
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SBOBO', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    if (dados.erro === "1") {
                        var msg = "<i class='bi bi-exclamation-octagon-fill'></i> " + dados.msg;
                        var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoDetalhe").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        });
                    } else {
                        var msg = "<i class='bi bi-check-square'></i> " + dados.msg;
                        var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoDetalhe").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {
                            $("#DetalheBO").modal("hide");

                            BOProcura($("#bo_cn").val(), $("#bo_site").val(), $("#bo_status").val(), $("#bo_DataInicio").val(), $("#bo_DataFIm").val());
                        });
                    }
                }
            });
        });
    }
    function BOCancela() {

        $("#bt_bt7").click(function () {

            $("#retornoDetalhe").text("");
            $("#retornoDetalhe").removeClass();

            var pct = {
                acao: "statusUpdate",
                bo_id: $("#Detalhe_id").text(),
                sinistro: $("#bo_sinistro").val(),
                os: $("#bo_os").val(),
                status: 5
            }
            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: pct,
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SBOBO', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    if (dados.erro === "1") {
                        var msg = "<i class='bi bi-exclamation-octagon-fill'></i> " + dados.msg;
                        var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoDetalhe").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        });
                    } else {
                        var msg = "<i class='bi bi-check-square'></i> " + dados.msg;
                        var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoDetalhe").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {
                            $("#DetalheBO").modal("hide");

                            BOProcura($("#bo_cn").val(), $("#bo_site").val(), $("#bo_status").val(), $("#bo_DataInicio").val(), $("#bo_DataFIm").val());
                        });
                    }
                }
            });
        });
    }
    function carregaFormUpload() {

        $("#bt_bt2").click(function () {

            $("#retornoDetalhe").text("");
            $("#retornoDetalhe").removeClass();

            $("#form_upload").modal("show");
        });
    }

    function BOProcura(cn, site, status, dataInicio, dataFinal) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "BOProcura", status: status, cn: cn, site: site, dataInicio: dataInicio, dataFinal: dataFinal },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SBOBO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var verifica = eval(dados);

                if (verifica.length === 0) {
                    $("#ListaBO").slideUp("fast").html("");

                    var msg = "<i class='bi bi-exclamation-octagon-fill'></i> Nenhuma correspondência.";
                    var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoFiltra").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                } else {
                    var linhas = eval(dados);

                    var lista = "";
                    lista += "<div class='card border-light mt-2 p-1'>";
                    lista += "<div class='card-header fw-bold'>Resultado da busca</div>";
                    lista += "<table class='table table-striped w-auto'>";
                    lista += "<thead class='thead-dark'>";
                    lista += "<tr>";
                    lista += "<th scope='col'>SITE</th>";
                    lista += "<th scope='col'>OS</th>";
                    lista += "<th scope='col'>SINISTRO</th>";
                    lista += "<th scope='col'>CN</th>";
                    lista += "<th scope='col'>DATA/HORA</th>";
                    lista += "<th scope='col'>STATUS</th>";
                    lista += "<th scope='col'>DETALHES</th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody>";
                    for (var i = 0; i < linhas.length; i++) {
                        lista += "<tr id='linha" + linhas[i].id + "'>";
                        lista += "<td>" + linhas[i].site + "</td>";
                        lista += "<td>" + linhas[i].os + "</td>";
                        lista += "<td>" + linhas[i].sinistro + "</td>";
                        lista += "<td>" + linhas[i].cn + "</td>";
                        lista += "<td>" + linhas[i].dh + "</td>";
                        lista += "<td><i class='" + linhas[i].ico + "'></i> " + linhas[i].status + "</td>";
                        lista += "<td><button value='" + linhas[i].id + "' class='btDetalhe btn btn-sm btn-light border text-muted'><i class='bi bi-eye'></i> Ver</button>";
                        lista += "</tr>";
                    }
                    lista += "</tbody>";
                    lista += "</table>";
                    lista += "</div>";

                    $("#ListaBO").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);
                    $("#DivXls").slideDown("fast");
                    detalhe();
                }
            }
        });
    }

    function detalhe() {

        $(".btDetalhe").click(function () {
            var id = $(this).attr('value');

            $("#anexoPdf").empty();
            $("#Detalhe_cidade").empty();
            $("#Detalhe_bairro").empty();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "Detalhe", id: id },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SBOBO', //Definindo o arquivo onde serão buscados os dados
                success: function (cab) {

                    oculta();
                    $("#DetalheBO").modal("show");
                    //   DetalheBO
                    var dados = cab.dados;

                    var hs = cab.hs;

                    $("#Historico_BO").html(hs);

                    var cidade = "";
                    var bairro = "";
                    if (dados.s_cidade === "NÃO INFORMADA") {
                        cidade = "<span class='text-danger font-weight-bold'>" + dados.s_cidade + "</span>";
                    } else {
                        cidade = dados.s_cidade;
                    }
                    if (dados.s_bairro === "NÃO INFORMADO") {
                        bairro = "<span class='text-danger font-weight-bold'>" + dados.s_bairro + "</span>";
                    } else {
                        bairro = dados.s_bairro;
                    }

                    $("#Detalhe_id").text(dados.id);
                    $("#Detalhe_site").text("[" + dados.cn + "] - " + dados.site + " - " + dados.s_descricao);
                    $("#Detalhe_ta").text(dados.ta);
                    $("#Detalhe_nome").text(dados.nome);
                    $("#Detalhe_c_nome").text(dados.nome_c);
                    $("#Detalhe_registro").text(dados.dh_registro);
                    $("#Detalhe_ocorrido").text(dados.dh_ocorrido);
                    $("#Detalhe_cidade").append(cidade);
                    $("#Detalhe_bairro").append(bairro);
                    $("#Detalhe_endereco").text(dados.s_endereco);
                    $("#Detalhe_cep").text(dados.s_cep);
                    $("#Detalhe_relato").text(dados.relato);
                    $("#Detalhe_furtado").text(dados.furtado);
                    $("#Detalhe_vandalizado").text(dados.vandalizado);
                    $("#Detalhe_sobra").text(dados.sobra);
                    $("#Detalhe_status").html("<i class='" + dados.ico + "'></i> " + dados.status);
                    $("#Detalhe_status_dh").text(dados.dh_status);
                    $("#Detalhe_status_id").text(dados.status_id);

                    if (dados.status === "CADASTRADO") {

                        $(".bt_editaOBS").removeClass("d-none");
                    } else {
                        $(".bt_editaOBS").addClass("d-none");
                    }
                    if (dados.status === "CANCELADO") {
                        $("#row_cancelamento").slideUp("fast");
                        $("#col_bt").slideUp("fast");
                        $("#Detalhe_txt_cancelamento").slideDown("fast");
                        $("#Detalhe_txt_cancelamento").text(dados.text_cancelamento);
                    } else {
                        $("#row_cancelamento").slideDown("fast");
                        $("#col_bt").slideDown("fast");
                        $("#Detalhe_txt_cancelamento").slideUp("fast");
                        $("#Detalhe_txt_cancelamento").empty();
                    }
                    $("#Detalhe_indisponibilidade").text(dados.indisp_inicio + " - " + dados.indisp_final + " - TOTAL: " + dados.indisp);
                    $("#Detalhe_indisponibilidade_mun").text(dados.indisp_municipio);
                    $("#Detalhe_indisponibilidade_ele").text(dados.indisp_elemento);

                    $("#Detalhe_bluethooth").text(dados.f_bluetooth);
                    $("#Detalhe_bluethooth_situacao").text(dados.f_bluetooth_status);
                    $("#Detalhe_modulobox").text(dados.modulo_box);
                    $("#Detalhe_baterialitio").text(dados.bateria);
                    $("#Detalhe_bo").text(dados.numero_bo);

                    $("#Detalhe_sinistro").text(dados.numero_sinistro);
                    $("#Detalhe_os").text(dados.os);

                    $("#bo_sinistro").val(dados.numero_sinistro);
                    $("#bo_os").val(dados.os);

                    $("#Detalhe_ta").text(dados.ta);
                    opc(dados.status_id);
                    carregaFormUpload();

                    if (dados.status === "ENVIADO") {

                        $("#bt_bt3").attr("disabled", false);
                    }
                    if (dados.anexo === "") {

                        $("#bt_bt4").attr("disabled", true);
                    } else {

                        $("#bt_bt4").attr("disabled", false);
                        $("#anexoPdf").text(dados.anexo);
                    }

                    $("#bt_bt4").click(function () {
                        var anexo = $("#anexoPdf").text();
                        var href = "sbo_pdf/" + anexo + ".PDF";
                        window.open(href);
                    });
                }
            });

        });
    }
    function opc(status) {

        $(".btOPC").slideUp("fast");

        if (status === "1") {
            $("#bt_bt1").slideDown("fast");
        } else
            if (status === "2") {
                $("#bt_bt2").slideDown("fast");
            } else
                if (status === "3") {
                    $("#bt_bt3").slideDown("fast");
                    $("#bt_bt6").slideDown("fast");
                    $("#bt_bt4").slideDown("fast");

                } else
                    if (status === "4") {
                        $("#bt_bt4").slideDown("fast");
                    } else
                        if (status === "7") {
                            $("#bt_bt3").slideDown("fast");
                        }
    }
    function editaForm() {

        $("#retornoEdita").text("");
        $("#retornoEdita").removeClass();

        var id = $("#Detalhe_id").text();
        var texto = $("#obsEdita").val();
        var campo = $("#tituloCampo").text();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: {
                acao: "editaForm",
                bo: id,
                texto: texto,
                campo: campo,
            },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SBOBO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                if (dados.erro === "1") {
                    var msg = "<i class='bi bi-exclamation-octagon-fill'></i> " + dados.msg;
                    var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoEdita").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");
                } else {
                    var msg = "<i class='bi bi-check-square'></i> " + dados.msg;
                    var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoEdita").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        window.location.replace("SBO_BO");
                    });
                }
            }
        });
    }
    function cadastro() {

        $("#ModalRetornoCadastro").text("");
        $("#ModalRetornoCadastro").removeClass();

        $("#btnCadastro").attr("disabled", true);

        var classe = "bg-info rounded font-weight-bold text-white pt-2 pb-2";
        var ico = "<i class='bi bi-hourglass-split'></i>";
        var msg = "Aguarde...";
        $("#ModalRetornoCadastro").slideDown("fast").addClass(classe).append(ico + " " + msg);

        var bo = {
            site: $("#ac_site").text(),
            ta: $("#ta").val(),
            os: $("#os").val(),
            dh_oc: $("#dhOc").val(),
            sinistro: $("#preSinistro").val(),
            inicio: $("#inicio").val(),
            final: $("#final").val(),
            municipio: $("#qtd_municipio").val(),
            elemento: $("#qtd_elemento").val(),
            f_bluetooth: $("#fechadura_bluetooth").val(),
            f_bluetooth_status: $("#fechadura_bluetooth_status").val(),
            modulo_box: $("#modulo_box").val(),
            bateria: $("#bateria").val(),
            furtado: $("#furtado").val(),
            vandalizado: $("#vandalizado").val(),
            sobra: $("#sobra").val(),
            relato: $("#relato").val()
        };

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: {
                acao: "cadastroBO",
                bo: bo
            },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SBOBO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#ModalRetornoCadastro").text("");
                $("#ModalRetornoCadastro").removeClass();

                if (dados.erro === "1") {
                    var msg = "<i class='bi bi-exclamation-octagon-fill'></i> " + dados.msg;
                    var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#ModalRetornoCadastro").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                    $("#btnCadastro").attr("disabled", false);
                } else {
                    var msg = "<i class='bi bi-check-square'></i> " + dados.msg;
                    var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#ModalRetornoCadastro").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        window.location.reload();
                        window.location.replace("SBO_BO");
                    });
                }
                $("#btnCadastro").attr("disabled", false);
            }
        });
    }

    function oculta() {

        $("#row_n_sinistro").slideUp("fast");
        $("#ListaBO").slideUp("fast");
        $("#DetalheBO").slideUp("fast");
        $("#DivXls").slideUp("fast");

    }
});