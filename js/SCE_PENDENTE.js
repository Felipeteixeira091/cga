$(document).ready(function () {

    coordenador();
    aprovacao_status();
    qtdEnvio();

    $("#btEnvio").click(function () {

        window.location.replace("SCE_ENVIO");
    });
    $("#solicitacao_filtro_botao").click(function () {

        var status = $("#solicitacao_filtro_status").val();
        var coordenador = $("#filtro_coordenador").val();
        var data1 = $("#solicitacao_filtro_data1").val();
        var data2 = $("#solicitacao_filtro_data2").val();

        aprovacao_filtra(status, coordenador, data1, data2);
    });
    $("#solicitacao_baixar_botao").click(function () {

        $("#retornoFiltro").text("");
        $("#retornoFiltro").removeClass();

        var status = $("#solicitacao_filtro_status").val();
        var coordenador = $("#filtro_coordenador").val();
        var data1 = $("#solicitacao_filtro_data1").val();
        var data2 = $("#solicitacao_filtro_data2").val();

        if (status === 0 && coordenador === 0 && data1 === "" && data2 === "") {

            var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
            var msg = "<i class='icon-attention'></i> Necessário selecionar infromações do filtro.";

        } else {
            var href = "SCEXLS?acao=xlsSolicitacao&data1=" + data1 + "&data2=" + data2 + "&status=" + status + "&coordenador=" + coordenador + "";

            window.location.href = href;

            var classe = "bg-info rounded text-white font-weight-bold p-2 mt-2";
            var msg = "<i class='icon-download-2'></i> Baixando arquivo, aguarde.";
        }

        $("#retornoFiltro").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

    });
    $("#btSolAprova").click(function () {
        var id = $("#solId").text();
        var obs = $("#obsInsert").val();
        var valor = $("#sce_aprov_valor").val();

        update(id, obs, "2", valor);
        qtdEnvio();

    });
    $("#btSolNega").click(function () {
        var id = $("#solId").text();
        var obs = $("#obsInsert").val();
        var valor = $("#sce_aprov_valor").val();

        update(id, obs, "3", valor);
        qtdEnvio();
    });

    $("#aprova_solic_botao_voltar").click(function () {
        $(".solicitacao_lista").slideUp("fast").html("");
        $("#solicitacao_filtro").slideDown("fast");

    });

    function qtdEnvio() {
        $("#qtdEnvio").text("");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "qtdEnvio" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCEENVIO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#qtdEnvio").append(dados.qtd);
            }
        });
    }

    function aprovacao_abrir_detalhe() {
        $(".bt_aprovar_lista").click(function () {
            var id = $(this).attr('value');
            $("#solicitacao_detalhes").modal('show');

            aprovacao_exibir_detalhe(id);
        });
    }

    function aprovacao_exibir_detalhe(id) {

        $("#divGmg").removeClass("d-none");
        $(".divVeiculo").removeClass("d-none");
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "detalhes", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCEPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var detalhe = dados.detalhe;

                $("#solicitacao_lista").slideUp("fast");
                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var tipo = detalhe.tipo;

                    $("#solId").html(detalhe.id);
                    $("#dh").html(detalhe.data + " " + detalhe.hora);
                    $("#solCartao").html(detalhe.cartao);
                    if (tipo === "1") {
                        $(".divGmg").addClass("d-none");

                        $("#solCoordenador").html(detalhe.coordenador_nome);
                        $("#solColaborador").html(detalhe.colaborador_nome);
                        $("#kmAnterior").html(dados.kmAnt);
                        $("#kmDif").html(dados.km_diferenca);
                        $("#kmAtual").html(detalhe.km);
                        $("#solVeiculo").html(detalhe.vPlaca + "  - " + detalhe.vMarca + " " + detalhe.vModelo);

                    } else {
                        $("#solCoordenador").html(detalhe.colaborador_nome);
                        $(".divVeiculo").addClass("d-none");
                        $("#solTempoAC").html(dados.tempo);
                        $("#solTipo").html(detalhe.gTipo);
                        $("#solGmg").html(detalhe.gmg);
                    }
                    $("#valorSolicitado").html(detalhe.valor);
                    $("#valorMes").html(detalhe.valorMes_colaborador);
                    $("#solUltima").html(detalhe.antData + " - " + detalhe.antValor);
                    $("#solObs").html(detalhe.obs);
                    $("#historico").html(dados.historico);
                    if (detalhe.status === "PENDENTE" || detalhe.status === "PENDENTE COORDENADOR") {

                        $("#btSolAprova").attr("disabled", false);
                        $("#btSolNega").attr("disabled", false);

                    } else if (detalhe.status === "ENVIADO") {

                        $("#btSolAprova").attr("disabled", true);
                        $("#btSolNega").attr("disabled", true);

                    } else if (detalhe.status === "APROVADO") {

                        $("#btSolAprova").attr("disabled", true);
                        $("#btSolNega").attr("disabled", true);
                    } else {

                        $("#btSolAprova").attr("disabled", true);
                        $("#btSolNega").attr("disabled", true);
                    }

                    if (detalhe.status === "PENDENTE COORDENADOR") {

                        sce_dados_anexo(detalhe.id);
                        $("#sce_aprov_valor").slideDown("fast").val(0);
                    } else {
                        $("#sce_form_anexo").slideup("fast");
                        $("#sce_aprov_valor").slideUp("fast").val(0);
                    }
                    $("#d_status_atual_id").text(detalhe.status_atual_id);

                    $("#solicitacao_detalhes").slideDown("fast");
                    var texto = dados.solicitacao_historico;
                    detalhes_status();
                }
            }
        });
    }
    function sce_dados_anexo(sce) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "anexo", sce: sce },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCECOLABORADOR', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = "Anexos: ";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {

                    linhas += "<a href='sce/" + linha[i].arquivo + "' target='_blank' class='btn btn-sm btn-dark ml-2'><i class='icon-photo-1'></i> " + linha[i].tipo + "</a>";
                }
                $("#sce_form_anexo").slideDown("fast").html(linhas);
            }
        });

    }
    function coordenador() {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "lista_coordenador" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCEPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">Selecione um coordenador</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].re + "\">" + linha[i].nome + "</option>";
                }
                $("#filtro_coordenador").slideDown("fast").html(linhas);
            }
        });
    }

    function aprovacao_status() {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "lista_status" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCEPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">Selecione o status</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {

                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#solicitacao_filtro_status").slideDown("fast").html(linhas);
            }
        });
    }

    function detalhes_status() {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "lista_status" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCEPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">Selecione o status</option>";
                var linha = eval(dados);
                var status_atual = $("#d_status_atual_id").text();

                for (var i = 0; i < linha.length; i++) {

                    if (linha[i].id < 4 && linha[i].id != status_atual) {
                        linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                    }
                }
                $("#aprova_solicitacao_select").slideDown("fast").html(linhas);
            }
        });
    }

    function aprovacao_filtra(status, coordenador, data1, data2) {

        $("#divXls").addClass("d-none");

        $("#ModalSucess").slideUp("fast");
        $("#ModalFechar").slideUp("fast");
        $("#ModalLoad").slideDown("fast");
        $("#Modal").modal('show');

        $("#solicitacao_detalhes").slideUp("fast");
        $("#solicitacao_filtro").slideDown("fast");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "filtra", status: status, coordenador: coordenador, data1: data1, data2: data2 },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCEPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var n_dados = dados.length;
                var txt = "";
                var html_original = $("#ModalSucess").html();

                $("#ModalLoad").slideUp("fast");

                if (n_dados === 0) {
                    txt = $("#ModalSucess").html() + "<p><span class='font-weight-bold text-danger'>Nenhum registro correspondente.</span>";

                } else {
                    txt = $("#ModalSucess").html() + "<p><span class=''><span class='badge badge-success'>" + n_dados + "</span> registros encontrados.</span>";

                    $("#divXls").removeClass("d-none");
                }

                $("#ModalSucess").slideDown("fast").html(txt);
                $("#ModalFechar").slideDown("fast");

                $("#ModalFecharBt").click(function () {

                    if (n_dados > 0) {

                        var coordenador_re = $("#filtro_coordenador").val();
                        var colaborador_re = $("#solicitacao_filtro_colaborador").val();
                        var status_id = $("#solicitacao_filtro_status").val();

                        var href = "exporta?acao=export_xls_ger&data1=" + data1 + "&data2=" + data2 + "&status=" + status_id + "&colaborador=" + colaborador_re + "&coordenador=" + coordenador_re + "";

                        var linhas = eval(dados);
                        var lista = "<table class='table table-striped table-hover'>";

                        //   lista += "<center><a id='bt_xls' href='" + href + "'><button class='btn btn-outline-success btn-sm mb-2'><i class='icon-download-3 h3'></i> Exportar</button></a></center>";
                        lista += "<thead class='thead-dark'>";
                        lista += "<tr>";
                        lista += "<th style='text-align: center' scope='col'>CARTÃO</th>";
                        lista += "<th style='text-align: center' scope='col'>COLABORADOR</th>";
                        lista += "<th style='text-align: center' scope='col'>COORDENADOR</th>";
                        lista += "<th style='text-align: center' scope='col'>VALOR</th>";
                        lista += "<th style='text-align: center' scope='col'>SOLICITADO</th>";
                        lista += "<th style='text-align: center' scope='col'>STATUS</th>";
                        lista += "<th style='text-align: center'><i class='icon-popup'></i></th>";
                        lista += "</tr>";
                        lista += "</thead>";
                        lista += "<tbody class='table table-striped table-hover'>";
                        for (var i = 0; i < linhas.length; i++) {

                            var status_N = "<span class='font-weight-bold text-muted'>" + linhas[i].status_N + "</span>"

                            if (linhas[i].status === "1") {
                                status = "<i class='icon-warning text-danger'></i>" + status_N;
                            } else if (linhas[i].status === "4") {
                                status = "<i class='icon-credit-card text-success'></i>" + status_N;
                            } else if (linhas[i].status === "2") {
                                status = "<i class='icon-ok-circled2 text-info'></i>" + status_N;
                            } else {
                                status = "<i class='icon-cancel-circled-1 text-muted'></i>" + status_N;
                            }
                            var nome = "";
                            var coordenador_re = "";
                            var coordenador_nome = "";
                            if (linhas[i].tipo === "1") {
                                nome = linhas[i].colaborador;
                                coordenador_nome = linhas[i].nome_coordenador;
                            } else {
                                nome = linhas[i].gTipo + "_" + linhas[i].gmg;
                                coordenador_nome = linhas[i].colaborador;
                            }

                            lista += "<tr>";
                            lista += "<td style='text-align: center'>" + linhas[i].cartao + "</td>";
                            lista += "<td style='text-align: center'>" + nome + "</td>";
                            lista += "<td style='text-align: center'>" + coordenador_nome + "</td>";
                            lista += "<td style='text-align: center'>" + linhas[i].valor + "</td>";
                            lista += "<td style='text-align: center'>" + linhas[i].data + " - " + linhas[i].hora + "</td>";
                            lista += "<td style='text-align: center'>" + status + "</td>";
                            lista += "<td style='text-align: center'><button value='" + linhas[i].id + "' type='button' class='bt_aprovar_lista btn btn-outline-info btn-sm'><i class='icon-popup'></i> Ver</button></td>";
                            lista += "</tr>";
                        }
                        lista += "</tbody>";
                        lista += "</table>";


                        $("#solicitacao_lista").slideDown("slow").html(lista);
                        aprovacao_abrir_detalhe();
                    } else {
                        $("#solicitacao_lista").slideUp("fast").empty();
                    }

                    $("#ModalSucess").html(html_original);
                });
            }
        });
    }

    function update(id, obs, status, valor) {

        $("#retornoAprovacao").text("");
        $("#retornoAprovacao").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "update", id: id, obs: obs, status: status, valor: valor },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCEPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                $("#retornoAprovacao").slideUp("fast").text();
                var classe = "";
                if (dados.erro === "1") {
                    classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                } else if (dados.erro === "0") {
                    classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";
                }
                $("#retornoAprovacao").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1900).slideUp("fast", function () {

                    $("#solicitacao_detalhes").modal("hide");

                });
                  aprovacao_filtra($("#solicitacao_filtro_status").val(), $("#filtro_coordenador").val(), $("#solicitacao_filtro_data1").val(), $("#solicitacao_filtro_data2").val())
            }
        });
    }
});