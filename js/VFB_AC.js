$(document).ready(function () {

    function difference(date1) {

        var date2 = new Date();

        const date1utc = Date.UTC(date1.getFullYear(), date1.getMonth(), date1.getDate());
        const date2utc = Date.UTC(date2.getFullYear(), date2.getMonth(), date2.getDate());
        day = 1000 * 60 * 60 * 24;
        return (date2utc - date1utc) / day
    }

    vfb_status();
    $("#pa_formulario1").slideDown("fast");

    $("#btFiltravfb").click(function () {

        $("#retornoNotaPesquisa").text("");
        $("#retornoNotaPesquisa").removeClass();

        var data1 = $("#vfbData1").val();
        var data2 = $("#vfbData2").val();
        var txt = $("#vfbTXT").val();
        var status = $("#vfbStatus").val();

        $("#erro").text("");
        $("#vfbLista").slideUp("fast");
        $("#bt_xls").attr("disabled", true);

        var tempo = 1500;
        var classe = "bg bg-danger rounded text-white font-weight-bold p-2";

        if (txt === "" && status === "0" && data1 === "" && data2 === "") {
            var msg = "<i class='icon-attention'></i> Informações insuficientes.";

            $("#retornovfbPesquisa").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");
        } else {
            vfbProcura(txt, status, data1, data2);
        }
    });
    $("#btNovo").click(function () {

        SiteProcura();
        colaborador();

        $("#ModalNovovfb").modal("show");
    });
    $("#btModalSite").click(function () {
        $("#pesquisaSITE").modal("show");

    });

    $("#btvfbCadastra").click(function () {
        var site = $("#vfbSite").text();
        var os = $("#vfbOS").val();
        var valor = $("#vfbValor").val();
        var colaborador = $("#vfbColaborador").val();
        var moTipo = $("#vfbMObra").val();
        var mo = $("#vfbMObraRE").val();
        var obs = $("#vfbOBS").val();

        CriaVfb(site, os, valor, colaborador, moTipo, mo, obs);
    });
    $("#vfbMObra").change(function () {

        executante($("#vfbMObra").val());
    });

    $("#formAprova").click(function () {

        $("#ModalRetorno").text("");
        $("#ModalRetorno").removeClass();

        var vfb = $("#vfbNumeroDetalhe").text();
        var obs = $("#obs2").val();

        var classe = "bg-info rounded p-1 font-weight-bold text-white";
        var tempo = 1500;
        var msg = "<i class='icon-stopwatch'></i> Aguarde...";
        $("#ModalRetorno").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast", function () {
            update(vfb, "3", obs);
        });

    });
    $("#formReprova").click(function () {

        $("#ModalRetorno").text("");
        $("#ModalRetorno").removeClass();

        var vfb = $("#vfbNumeroDetalhe").text();
        var obs = $("#obs2").val();

        var classe = "bg-info rounded p-1 font-weight-bold text-white";
        var tempo = 1500;
        var msg = "<i class='icon-stopwatch'></i> Aguarde...";
        $("#ModalRetorno").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast", function () {
            update(vfb, "4", obs);
        });
    });
    $("#formCorrecao").click(function () {

        $("#ModalRetorno").text("");
        $("#ModalRetorno").removeClass();

        var vfb = $("#vfbNumeroDetalhe").text();
        var obs = $("#obs2").val();

        var classe = "bg-info rounded p-1 font-weight-bold text-white";
        var tempo = 1500;
        var msg = "<i class='icon-stopwatch'></i> Aguarde...";
        $("#ModalRetorno").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast", function () {
            update(vfb, "5", obs);
        });
    });
    $("#formVolta").click(function () {

        $("#DetalheSolicitacao").slideUp("slow", function () {
            $("#ListaSolicitacao").slideDown("slow", function () {

                $("#pa_formulario1").slideDown("slow");
            });
        });
    });
    $("#bt_xls").click(function () {
        var txt = $("#vfbTXT").val();
        var dataInicio = $("#vfbData1").val();
        var dataFim = $("#vfbData2").val();
        var status = $("#vfbStatus").val();

        var href = "XLSVFB?acao=xls&txt=" + txt + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim + "&status=" + status;

        window.open(href);
    });
    $("#notaDestAdd").click(function () {
        var nome = $("#destNome").val();
        var email = $("#destEmail").val();
        var tipo = $("#destTipo").val();

        NotaDestinoAdd(nome, email, tipo);

    });

    function formModal() {

        $(".btDetalheNota").click(function () {
            var v = $(this).attr('value');

            VfbDetalhe(v);

            $("#DetalheVfb").modal("show");
        });
    }

    function colaborador() {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "vfbColaborador" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'VFBAC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">VISTORIADOR</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].re + "\">" + linha[i].nome + "</option>";
                }
                $("#vfbColaborador").slideDown("fast").html(linhas);
            }

        });
    }

    function executante(mo) {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "vfbExec", mo: mo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'VFBAC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">EXECUTANTE</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].re + "\">" + linha[i].nome + "</option>";
                }
                $("#vfbMObraRE").slideDown("fast").html(linhas);
            }

        });
    }
    $("#notaAnexoBt").click(function () {
        var nota = $(this).attr('value');

        var href = "ext_nota/" + nota;


        window.open(href);
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

    function CriaVfb(site, os, valor, colaborador, moTipo, mo, obs) {

        $("#retornoVfbNova").text("");
        $("#retornoVfbNova").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "criavfb", site: site, os: os, valor: valor, colaborador: colaborador, moTipo: moTipo, mo: mo, obs: obs },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'VFBAC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.erro === "1") {

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoVfbNova").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                } else {
                    var msg = "<i class='icon-ok-circle'></i> " + dados.msg;
                    var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoVfbNova").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        window.location.reload();
                    });
                }
            }
        });
    }

    function vfbProcura(txt, status, data1, data2) {

        $("#retornovfbPesquisa").text("");
        $("#retornovfbPesquisa").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "vfbProcura", txt: txt, status: status, data1: data1, data2: data2 },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'VFBAC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var verifica = eval(dados);
                var resultados = verifica.length;
                if (resultados === 0) {
                    $("#bt_xls").attr("disabled", true);

                    var tempo = 1500;
                    var classe = "bg-danger rounded text-white font-weight-bold p-2";
                    var msg = "<i class='icon-attention'></i> Nenhuma correspondência.";

                    $("#retornovfbPesquisa").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                } else {
                    var tempo = 1500;
                    var classe = "bg-success rounded text-white font-weight-bold p-2";
                    var msg = "<i class='icon-hourglass'></i> " + resultados + " resultados encontrados.";

                    $("#retornovfbPesquisa").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast", function () {

                        $("#bt_xls").attr("disabled", false);

                        var linhas = eval(dados);

                        var lista = "";
                        lista += "<div class='card border-light mt-2 p-1'>";
                        lista += "<div class='card-header font-weight-bold'>Resultado da busca</div>";
                        lista += "<table class='table table-striped w-auto'>";
                        lista += "<thead class='thead-dark'>";
                        lista += "<tr>";
                        lista += "<th scope='col'>SLA</th>";
                        lista += "<th scope='col'>OS</th>";
                        lista += "<th scope='col'>CN/SITE</th>";
                        lista += "<th scope='col'>DATA</th>";
                        lista += "<th scope='col'>STATUS</th>";
                        lista += "<th scope='col'>ABRIR</th>";
                        lista += "</tr>";
                        lista += "</thead>";
                        lista += "<tbody>";
                        for (var i = 0; i < linhas.length; i++) {

                            lista += "<tr id='linha" + linhas[i].id + "'>";
                            lista += "<td>" + linhas[i].dias + "</td>";
                            lista += "<td>" + linhas[i].os + "</td>";
                            lista += "<td>" + linhas[i].cn + "/" + linhas[i].site + "</td>";
                            lista += "<td >" + linhas[i].data + "</td>";
                            lista += "<td><i class='" + linhas[i].ico + "'></i> " + linhas[i].statusTxt + "</td>";
                            lista += "<td><button type='button' value='" + linhas[i].id + "' class='btDetalheNota btn btn-outline-info btn-sm'><i class='icon-popup'></i> Ver</button></td>";
                            lista += "</tr>";
                        }
                        lista += "</tbody>";
                        lista += "</table>";

                        $("#vfbLista").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);

                        formModal();
                    });
                }
            }
        });
    }

    function update(vfb, status, obs) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "vfbUpdate", vfb: vfb, status: status, obs: obs },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'VFBAC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#ModalRetorno").slideUp("fast").text();

                var classe = "";
                var msg = "";

                if (dados.erro === "1") {
                    classe = "bg bg-danger rounded text-white font-weight-bold p-2";
                    msg = "<i class='icon-warning'></i> " + dados.msg;

                    $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast");

                } else if (dados.erro === "0") {
                    classe = "bg bg-success rounded text-white font-weight-bold p-2";
                    msg = "<i class='icon-ok-circle'></i> " + dados.msg;

                    $("#ModalRetorno").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function () {

                        $("#DetalheVfb").modal("hide");
                        vfbProcura($("#vfbTXT").val(), $("#vfbStatus").val(), $("#vfbData1").val(), $("#vfbData2").val());

                        $("#obs2").val("");

                    });
                }
            }
        });
    }

    function VfbDetalhe(vfb) {

        $("#formAprova").attr("disabled", true);
        $("#formReprova").attr("disabled", true);
        $("#formCorrecao").attr("disabled", true);
        $("#formAprova").empty();
        $("#formAprova").append("<i class='icon-ok-circled text-success'></i> Validar");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "vfbDetalhe", vfb: vfb },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'VFBAC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var v = dados.detalhe;
                var historico = dados.historico;
                var anexo = dados.anexo;
                var checklist = dados.checklist;

                $("#vfbNumeroDetalhe").text(v.id);
                $("#vfbReDetalhe").text(v.re);
                $("#vfbNomeDetalhe").text(v.nome);
                $("#vfbDataDetalhe").text(v.data);
                $("#vfbHoraDetalhe").text(v.hora);
                $("#vfbSiteDetalhe").text(v.site);
                $("#vfbOsDetalhe").text(v.os);
                $("#vfbMOTipoDetalhe").text(v.mo);
                $("#vfbValorDetalhe").text(v.valor);
                $("#vfbMODetalhe").text(v.meNome + " - " + v.meRe);
                $("#vfbOrientacao").text(v.solicitacao);
                $("#vfbSegmento").text(v.segmento);

                $("#obs2").val("");

                $("#vfbStatusDetalhe").text(v.statusTxt);
                $("#vfbSLADetalhe").text(v.dias);

                $("#historico").slideDown("slow").html(historico);

                if (checklist === "nd") {
                    $("#checklist").slideUp("fast");
                    $("#checklistErro").slideDown("fast");
                } else {
                    $("#checklist").slideDown("fast");
                    $("#checklistErro").slideUp("fast");
                }
                $("input[name=seg][value='" + checklist.seg + "']").prop("checked", true);
                $("input[name=perg1][value='" + checklist.perg1 + "']").prop("checked", true);
                $("input[name=perg2][value='" + checklist.perg2 + "']").prop("checked", true);
                $("input[name=perg3][value='" + checklist.perg3 + "']").prop("checked", true);
                $("input[name=perg4][value='" + checklist.perg4 + "']").prop("checked", true);
                $("input[name=perg5][value='" + checklist.perg5 + "']").prop("checked", true);
                $("input[name=perg6][value='" + checklist.perg6 + "']").prop("checked", true);
                $("input[name=perg7][value='" + checklist.perg7 + "']").prop("checked", true);
                $("input[name=perg8][value='" + checklist.perg8 + "']").prop("checked", true);

                var opt = "option[value=" + checklist.perg9 + "]";
                $("#perg9").find(opt).attr("selected", "selected");

                ////////////////////////////////////////
                var notas = anexo;

                var linhas = eval(notas);

                var lista = "";
                lista += "<div class='card border'>";
                lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                lista += "<thead class='bg bg-light text-muted'>";
                lista += "<tr>";
                lista += "<th scope='col' class='text-center'>DATA/HORA</th>";
                lista += "<th scope='col' class='text-center'>TIPO</th>";
                lista += "<th scope='col' class='text-center'>DESCRIÇÃO</th>";
                lista += "</tr>";
                lista += "</thead>";
                lista += "<tbody>";
                for (var i = 0; i < linhas.length; i++) {

                    var bt = "";
                    if (linhas[i].codigo === "") {
                        bt = "<button type='button' disabled class='btn btn-sm btn-light border text-muted'><i class='icon-comment'></i> TXT</button>";
                    } else {

                        let anyString = linhas[i].codigo;
                        let cod = anyString.substring(anyString.length - 3)

                        bt = "<a href='vfb_anexo/" + linhas[i].codigo + "' target='_blank'><button type='button' class='btn btn-sm btn-light border text-muted'><i class='icon-attach-4'></i> " + cod + "</button></a>";
                    }

                    lista += "<tr id='linha" + linhas[i].id + "'>";
                    lista += "<td class='text-center'>" + linhas[i].data + " " + linhas[i].hora + "</td>";
                    lista += "<td class='text-center'>" + bt + "</td>";
                    lista += "<td class='text-center'>" + linhas[i].descricao + "</td>";
                    lista += "</tr>";
                }
                lista += "</tbody>";
                lista += "</table></div>";

                $("#anexo").slideDown("slow").html(lista);
                ////////////////////////////////////////

                if (v.statusTxt === "EM ANDAMENTO") {
                    $("#formAprova").attr("disabled", false);
                    $("#formReprova").attr("disabled", false);
                    $("#formCorrecao").attr("disabled", false);

                    $("#obs2").attr("disabled", false);
                } else {
                    $("#formAprova").attr("disabled", true);
                    $("#formReprova").attr("disabled", true);
                    $("#obs2").attr("disabled", true);
                }
            }
        });
    }

    function vfb_status() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "vfbStatus" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'VFBAC', //Definindo o arquivo onde serão buscados os dados
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
                    $("#vfbStatus").slideDown("fast").html(linhas);
                }
            }
        });
    }
});