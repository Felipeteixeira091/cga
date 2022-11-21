$(document).ready(function () {

    envio_filtra();
    $("#bt_envio_sol").click(function () {

        envio_conf();
    });

    function envio_filtra() {

        $("#bt_envio_sol").fadeOut("fast", function () {

            $("#span_load").fadeIn("fast");
        });
        var disp = dispositivo();
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "lista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCEENVIO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = eval(dados);
            

                if (linhas.length === 0) {
                    $("#bt_envio_sol").fadeOut("fast", function () {

                        $("#Lista").empty().slideDown("fast").addClass("text-danger").append("<i class='icon-attention text-danger'></i> Nenhuma solicitação para envio.");
                        $("#qtdTotal").append(0);
                        $("#valorTotal").append("R$ 0");
                        $("#bt_envio_sol").fadeOut("fast");
                        $("#span_load").fadeOut("fast");
                    });

                } else {
                    var vTcn = 0;
                    var qTcn = 0;

                    $("#bt_envio_sol").slideDown("fast");
                    var lista = "<table class='table table-sm table-striped mt-4'>";
                    lista += "<thead class='thead-dark'>";
                    lista += "<th style='text-align: center'>CARTÃO</th>";
                    lista += "<th style='text-align: center'>VALOR</th>";
                    lista += "<th style='text-align: center'>COORDENADOR</th>";
                    lista += "<th style='text-align: center'>FINALIDADE</th>";
                    lista += "<th style='text-align: center'>DATA SOLICITAÇÃO</th>";
                    lista += "<th style='text-align: center'>DATA APROVAÇÃO</th>";
                    lista += "<th style='text-align: center'>REPONSÁVEL APROVAÇÃO</th>";
                    lista += "<th style='text-align: center'><i class='icon-money'></i> ALTERA</th>";
                    lista += "</tr>";
                    lista += "</thead>";

                    for (var i = 0; i < linhas.length; i++) {

                        var finalidade = "";
                        if (linhas[i].tipo === "1") {
                            finalidade = linhas[i].colaborador;
                        } else {
                            finalidade = linhas[i].identificacao;
                        }

                        var nome = linhas[i].colaborador;
                        var nome_c = linhas[i].coordenador;
                        var nome_a = linhas[i].aprovResp;

                        var re = linhas[i].re;

                        if (espaco(nome) > 0) {
                            colaborador = nome.split(' ')[0] + " " + nome.split(' ')[1];
                        } else {
                            colaborador = nome;
                        }

                        if (espaco(nome_c) > 0) {
                            coordenador = nome_c.split(' ')[0] + " " + nome_c.split(' ')[1];
                        } else {
                            coordenador = nome_c;
                        }

                        if (espaco(nome_a) > 0) {
                            aprovador = nome_a.split(' ')[0] + " " + nome_a.split(' ')[1];
                        } else {
                            aprovador = nome_a;
                        }

                        vTcn += parseFloat(linhas[i].valor);
                        qTcn = i + 1;

                        colaborador = colaborador + " - " + re
                        lista += "<tr>";
                        lista += "<td style='text-align: center'><span>" + linhas[i].cartao + "</span></td>";
                        lista += "<td style='text-align: center'>R$ <span id='valor_atual_" + linhas[i].id + "'>" + linhas[i].valor.replace(".", ",") + "</span</td>";
                        lista += "<td style='text-align: center'><span>" + coordenador + "</span</td>";
                        lista += "<td style='text-align: center'><span>" + finalidade + "</span</td>";
                        lista += "<td style='text-align: center'><span>" + linhas[i].data + "</span</td>";
                        lista += "<td style='text-align: center'><span>" + linhas[i].aprovacao + "</span</td>";
                        lista += "<td style='text-align: center'><span>" + aprovador + "</span</td>";
                        var bt = "<button id='" + linhas[i].id + "' class='bt_altera_valor btn-sm btn-light border'><i class='icon-exchange'></i> Alterar</button>";
                        lista += "<td style='text-align: center'>" + bt + "</td>";
                        lista += "</tr>";
                    }

                    lista += "</table>";
                    var valor_total = vTcn;

                    $("#qtdTotal").append(qTcn);
                    $("#valorTotal").append("R$ " + valor_total);
                    $("#solicitacao_lista").slideDown("slow").html(lista);


                    $("#span_load").fadeOut("fast", function () {

                        $("#bt_envio_sol").fadeIn("fast");
                    });
                    altera_valor();
                }
            }
        });
    }

    function altera_valor() {
        $(".bt_altera_valor").click(function () {
            var id = $(this).attr('id');
            var valor_atual = $("#valor_atual_" + id).text();

            $("#solicitacaoAltera").modal('show');
            $("#valor_solicitado").text("R$ " + valor_atual);
            $("#id_solicitacao").text(id);
            update();
        });
    }

    function update() {
        $("#btAlteraValor").click(function () {
            $("#retornoAltera").text("");
            $("#retornoAltera").removeClass();

            var id = $("#id_solicitacao").text();
            var valor = $("#novo_valor").val();
            var valor_solicitado = $("#valor_solicitado").val();
            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "update", id: id, valor: valor, valor_solicitado: valor_solicitado },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SCEENVIO', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    $("#retornoAltera").slideUp("fast").text();
                    var classe = "";
                    if (dados.erro === "1") {
                        classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    } else {
                        classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";
                    }
                    $("#retornoAltera").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(2900).slideUp("fast", function () {

                        $("#solicitacaoAltera").modal("hide");
                        $("#valor_atual_" + id).text(valor);
                        window.location.replace("SCE_ENVIO");
                    });
                }
            });
        });
    }

    function envio_conf() {
        $("#solicitacao_lista").slideUp("fast").html("");

        $("#retornoEnvio").text("");
        $("#retornoEnvio").removeClass();

        classe = "bg bg-info rounded font-weight-bold text-white pt-2 pb-2";
        $("#retornoEnvio").empty().slideDown("fast").addClass(classe).append("<i class='icon-spin1 animate-spin'></i><p>Processando, aguarde...");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "envia" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCEENVIO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#retornoEnvio").slideUp("fast").text();
                $("#retornoEnvio").text("");
                $("#retornoEnvio").removeClass();

                var classe = "";
                if (dados.erro === "1") {
                    classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                } else {
                    classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";
                }
                $("#retornoEnvio").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1900).slideUp("fast", function () {

                    window.location.replace("SCE_ENVIO");
                });
            }
        });
    }

    function espaco(string) {
        var n = 0;
        var n = 0;
        for (i = 0; i < string.length; i++) {
            if (string[i] === " ") {
                n = n + 1;
            }
        }
        return n;
    }

    function dispositivo() {
        var ua = navigator.userAgent.toLowerCase();
        var uMobile = '';
        // === REDIRECIONAMENTO PARA iPhone, Windows Phone, Android, etc. ===
        // Lista de substrings a procurar para ser identificado como mobile WAP
        uMobile = '';
        uMobile += 'iphone;ipod;windows phone;android;iemobile 8';
        // Sapara os itens individualmente em um array
        v_uMobile = uMobile.split(';');
        // percorre todos os itens verificando se eh mobile
        var boolMovel = false;
        for (i = 0; i <= v_uMobile.length; i++) {
            if (ua.indexOf(v_uMobile[i]) != -1) {
                boolMovel = true;
            }
        }
        if (boolMovel == true) {
            return "movel";
        } else {
            return "computador";
        }
    }

});