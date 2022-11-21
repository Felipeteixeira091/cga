$(document).ready(function () {

    sce_select_periodo();

    $("#sce_btn_filtra").click(function () {

        var periodo = $("#sce_select_periodo").val();

        $("#sce_table_lista").slideUp("fast");
        sce_solicitacao_procura(periodo);

    });
    $("#nota_btn_nova").click(function () {

        sce_dados_solicitacao();
        $("#sce_form_solicitacao").modal("show");

    });
    $("#sce_btn_solicita").click(function () {

        sce_solicita();
    });
    $("#sce_btn_anexo").click(function () {

        $("#sce_modal_upload").modal("show");
    });
    $("#sce_select_anexo").change(function () {


        //sce_anexo();
    });

    function sce_solicita() {

        var obj = {
            id: $("#idAnterior").text(),
            saldo: $("#sce_atual_saldo").val(),
            km: $("#sce_atual_km").val(),
            obs: $("#sce_atual_obs").val()
        }

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "solicita", obj: obj },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCECOLABORADOR', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#retornoSolicitacao").removeClass();
                $("#retornoSolicitacao").text("");

                if (dados.erro === "1") {

                    var msg = dados.msg;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoSolicitacao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");
                } else {
                    var msg = dados.msg;
                    var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoSolicitacao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        sce_dados_solicitacao();
                        //window.location.reload();
                    });
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

                var linhas = "";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {

                    linhas += "<a href='sce/" + linha[i].arquivo + "' target='_blank' class='btn btn-sm btn-dark ml-2'><i class='icon-photo-1'></i> " + linha[i].tipo + "</a>";
                }
                $("#sce_form_anexo").slideDown("fast").html(linhas);
            }
        });

    }
    function sce_dados_solicitacao() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "dados" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCECOLABORADOR', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.tipo === "pendente") {
                    var pendente = dados.pendente;

                    $("#sce_badge_id").text(pendente.id);

                    $("#sce_btn_solicita").attr("disabled", true);
                    $("#sce_atual_saldo").val(pendente.saldo).attr("disabled", true);
                    $("#sce_atual_km").val(pendente.km).attr("disabled", true);
                    $("#sce_atual_obs").val(pendente.obs).attr("disabled", true);

                    sce_dados_anexo(pendente.id);

                } else {

                    $("#sce_btn_solicita").attr("disabled", false);
                    $("#sce_atual_saldo").val("").attr("disabled", false);
                    $("#sce_atual_km").val("").attr("disabled", false);
                    $("#sce_atual_obs").val("").attr("disabled",false);

                }
                var detalhe = dados.detalhe;

                $("#detalheRe").text(detalhe.re);
                $("#detalheNome").text(detalhe.nome);
                $("#detalheCartao").text(detalhe.cartao);
                $("#idAnterior").text(detalhe.id);
                $("#datalheUltSol").text(detalhe.data);
                $("#detalheUltKM").text(detalhe.UltKm);
                $("#detalheModelo").text(detalhe.veiculo);
                $("#detalheIdentificacao").text(detalhe.placa);
                $("#detalheVlr_mes").text(detalhe.valor);

            }
        });
    }
    function sce_select_periodo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "periodo" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCECOLABORADOR', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = "<option value=\"0\">Selecione um período</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].anoMes + "\">" + linha[i].anoMes + "</option>";
                }
                $("#sce_select_periodo").slideDown("fast").html(linhas);
            }
        });
    }
    function sce_solicitacao_procura(periodo) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "procura", periodo: periodo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCECOLABORADOR', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var verifica = eval(dados);

                if (verifica.length === 0) {

                    $("#sce_retorno_procura").text("");
                    $("#sce_retorno_procura").removeClass();

                    var tempo = 1500;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    var ico = "<i class='icon-attention'></i>";
                    var msg = "Nenhuma solicitação encontrada para o período selecionado."

                    $("#sce_retorno_procura").slideDown("fast").addClass(classe).append(ico + " " + msg).delay(tempo).slideUp("fast", function () {

                        $("#sce_btn_filtra").attr("disabled", false);
                    });

                } else {

                    $("#sce_retorno_procura").text("");
                    $("#sce_retorno_procura").removeClass();

                    var tempo = 1800;
                    var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";
                    var ico = "<i class='icon-ok-circled-1'></i>";
                    var msg = verifica.length + " Solicitações encontradas."

                    $("#sce_retorno_procura").slideDown("fast").addClass(classe).append(ico + " " + msg).delay(tempo).slideUp("fast", function () {

                        $("#sce_btn_filtra").attr("disabled", false);
                        var linhas = eval(dados);

                        var lista = "";
                        lista += "<div class='card border-light mt-2'>";
                        lista += "<div class='card-header font-weight-bold'>Resultado da busca</div>";
                        lista += "<table class='table table w-auto'>";
                        lista += "<thead class='thead-dark'>";
                        lista += "<tr>";
                        lista += "<th scope='col' class='text-center'>DATA</th>";
                        lista += "<th scope='col' class='text-center'>VALOR</th>";
                        lista += "<th scope='col' class='text-center'>CARTÃO</th>";
                        lista += "<th scope='col' class='text-center'><i class='icon-popup text-light'></i> VER</th>";
                        lista += "</tr>";
                        lista += "</thead>";
                        lista += "<tbody>";
                        for (var i = 0; i < linhas.length; i++) {

                            lista += "<tr id='linha" + linhas[i].id + "'>";
                            lista += "<td class='text-center'>" + linhas[i].data + "</td>";
                            lista += "<td class='text-center'>" + linhas[i].valor + "</td>";
                            lista += "<td class='text-center'>" + linhas[i].cartao + "</td>"
                            lista += "<td class='text-center'><button type='button' value='" + linhas[i].id + "' class='btn_sce_solicitacao btn btn-outline-info btn-sm'><i class='icon-popup'></i> Detalhes</button></td>";
                            lista += "</tr>";
                        }
                        lista += "</tbody>";
                        lista += "</table>";
                        $("#sce_table_lista").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);
                        formModal();
                    });
                }
            }
        });
    }

});