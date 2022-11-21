$(document).ready(function() {

    $("#checkAll").click(function() {

        var qtd = checkedConta();

        if (qtd > 0) {

            $('.ck').each(function() {

                this.checked = false;
            });
        } else {
            $('.ck').each(function() {

                this.checked = true;
            });
        }
    });
    $("#elementoFiltraModal").click(function() {

        $("#ModalFiltro").modal("show");
        form_status();
        form_cn();
    });

    $("#filtraElemento").click(function() {

        var status = $("#filtroStatus").val();
        var cn = $("#filtroCN").val();
        var data1 = $("#filtroData1").val();
        var data2 = $("#filtroData2").val();

        Elemento_filtra(status, cn, data1, data2);
    });

    $("#btDownload").click(function() {

        var qtd = checkConta();
        if (qtd > 0) {

            var cn = $("#filtroCN").val();
            var status = $("#filtroStatus").val();
            var data1 = $("#filtroData1").val();
            var data2 = $("#filtroData2").val();
            var href = "CEPXLS?acao=exportaExcel&data1=" + data1 + "&data2=" + data2 + "&status=" + status + "&cn=" + cn;

            window.location.href = href;

        } else {

            alert("Necessário filtrar elementos para download");
        }

    });
    $("#btOpc").click(function() {

        $("#elementoLista").text("");

        $("#ModalOpc").modal("show");

        var qtd = checkedConta();
        var ids = checkIds().slice(0, -1);;

        var arr = ids.split("|");
        var bts = "";

        if (qtd === 0) {
            bts = "Nenhum elemento foi selecionado.";

            $("#btIniciaTratativa").attr("disabled", true);
            $("#btConcluiTratativa").attr("disabled", true);
            $("#btCancelaTratativa").attr("disabled", true);

            $("#elementoLista").text("").slideUp("fast", function() {
                $("#elementoSpan").append(bts).slideDown("fast");
            });
        } else {

            $("#btIniciaTratativa").attr("disabled", false);
            $("#btConcluiTratativa").attr("disabled", false);
            $("#btCancelaTratativa").attr("disabled", false);

            for (var i = 0; i < arr.length; i++) {

                var ativo = $("#ativo_" + arr[i]).text();

                bts += "<button class='btElementoOpc btn btn-sm text-muted border ml-1 mt-1' id='btElementoOpc" + arr[i] + "' value='" + arr[i] + "'>" + ativo + "</button>";
            }
            $("#elementoSpan").text("").slideUp("fast", function() {
                $("#elementoLista").append(bts).slideDown("fast");
            });
        }
    });
    $("#btIniciaTratativa").click(function() {

        var elemento = listIds("btElementoOpc");

        elementoUpdate(elemento, "inicia");

    });
    $("#btConcluiTratativa").click(function() {

        var elemento = listIds("btElementoOpc");

        elementoUpdate(elemento, "conclui");

    });
    $("#btCancelaTratativa").click(function() {

        var elemento = listIds("btElementoOpc");

        elementoUpdate(elemento, "cancela");

    });
    $("#concluirElemento").click(function() {

        $("#ModalRetornoConcluir").text("");
        $("#ModalRetornoConcluir").removeClass();

        var solicitacao = $("#viewID").text();

        ConcluiElemento(solicitacao);
    });

    function elementoUpdate(elemento, tipo) {

        $("#ModalRetornoOpc").text("");
        $("#ModalRetornoOpc").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "elementoUpdate", elemento: elemento, tipo: tipo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPELEMENTO', //Definindo o arquivo onde serão buscados os dados
            async: false,
            success: function(dados) {
                if (dados.erro === "1") {

                    classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    var msg = "<i class='icon-attention'></i> " + dados.msg;

                    $("#ModalRetornoOpc").slideDown("fast").addClass(classe).append(msg).delay("1900").slideUp("fast", function() {

                        $("#ModalOpc").modal("hide");
                    });

                } else {
                    classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";
                    var msg = "<i class='icon-play-2'></i> " + dados.msg;

                    $("#ModalRetornoOpc").slideDown("fast").addClass(classe).append(msg).delay("1900").slideUp("fast", function() {

                        $("#acao").slideUp("fast");
                        $("#ModalOpc").modal("hide");

                        Elemento_filtra($("#filtroStatus").val(), $("#filtroCN").val(), $("#filtroData1").val(), $("#filtroData2").val())
                    });
                }
            }
        });
    }

    function Elemento_filtra(status, cn, data1, data2) {

        $("#ModalRetornoFiltro").text("");
        $("#ModalRetornoFiltro").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "filtraElemento", status: status, cn: cn, data1: data1, data2: data2 },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPELEMENTO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {
                    classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    $("#ModalRetornoFiltro").slideDown("fast").addClass(classe).append(msg).delay("1900").slideUp("fast");

                } else {
                    var linhas = eval(dados.elemento);
                    classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    var msg = "<i class='icon-ok-circle'></i> " + dados.msg;
                    $("#ModalRetornoFiltro").slideDown("fast").addClass(classe).append(msg).delay("1900").slideUp("fast", function() {

                        $("#divCheckAll").removeClass("d-none");

                        $("#ModalFiltro").modal("hide");

                        var lista = "<table class='table table-striped table-sm'>";
                        lista += "<thead class='thead-dark'>";
                        lista += "<tr class='text-center'>";
                        lista += "<th scope='col'>ATIVO</th>";
                        lista += "<th scope='col'>SITE</th>";
                        lista += "<th scope='col'>ESTRUTURA</th>";
                        lista += "<th scope='col'>ELEMENTO</th>";
                        lista += "<th scope='col'>DATA - HORA</th>";
                        lista += "<th scope='col'>AVALIAÇÃO</th>";
                        lista += "<th scope='col'><i class='icon-popup' class='bt_aprovar'></i></th>";
                        lista += "<th scope='col'><i class='icon-check-1'></i></th>";
                        lista += "</tr>";
                        lista += "</thead>";
                        lista += "<tbody>";

                        for (var i = 0; i < linhas.length; i++) {

                            var est_n = "";
                            var elemento_n = "";
                            var ativo_pai = "";
                            var ativo = "";
                            var uf = linhas[i].uf;

                            if (linhas[i].estrutura_n === 0 || linhas[i].estrutura_n === "0") {
                                est_n = "";
                            } else {
                                est_n = linhas[i].estrutura_n;
                            }
                            if (linhas[i].elemento_n === 0 || linhas[i].elemento_n === "0") {
                                elemento_n = "";
                            } else {
                                elemento_n = linhas[i].elemento_n;
                            }

                            ativo_pai = linhas[i].site + "." + linhas[i].estrutura + est_n + "." + linhas[i].ativo_pai;

                            if (linhas[i].ePai === "1" || linhas[i].estrutura === "ELEMENTO_PAI") {

                                ativo_pai = linhas[i].site;
                                ativo = (linhas[i].site + "." + linhas[i].estrutura + est_n + "." + linhas[i].ativo_pai).replace(".ELEMENTO_PAI.", ".") + "<i class='icon-info-circled-alt text-danger'></i>";
                                linhas[i].estrutura = "ELEMENTO PAI";
                            } else
                            if (linhas[i].ativo_pai === "ESTRUTURA") {

                                ativo_pai = linhas[i].site + "." + linhas[i].estrutura + est_n;
                                ativo = ativo_pai + "." + linhas[i].excel + linhas[i].elemento_n;

                            } else
                            if (linhas[i].ativo_pai === "ESTRUTURA_FONTE") {

                                ativo_pai = linhas[i].site + "." + linhas[i].estrutura + est_n + ".FCC" + linhas[i].fcc;
                                ativo = ativo_pai + "." + linhas[i].excel + linhas[i].elemento_n;

                            } else
                            if (linhas[i].ativo_pai === "TX" || linhas[i].ativo_pai === "RF" || linhas[i].ativo_pai === "SAC1") {

                                ativo = linhas[i].site + "." + linhas[i].estrutura + est_n + "." + linhas[i].ativo_pai + "." + linhas[i].excel + linhas[i].elemento_n;
                            } else
                            if (linhas[i].ativo_pai === "CSP") {

                                ativo = linhas[i].site + "." + linhas[i].estrutura + "." + linhas[i].excel + linhas[i].elemento_n;

                                ativo_pai = linhas[i].site + "." + linhas[i].estrutura + est_n;
                            } else
                            if (linhas[i].ativo_pai === "GAB") {

                                ativo = linhas[i].site + "." + linhas[i].estrutura + est_n;

                                ativo_pai = linhas[i].site + "." + linhas[i].estrutura + est_n;
                            } else
                            if (linhas[i].ativo_pai === "SITE") {

                                ativo = linhas[i].site + "." + linhas[i].excel + linhas[i].elemento_n;

                                ativo_pai = linhas[i].site;
                            } else
                            if (linhas[i].ativo_pai === "CAP1") {

                                ativo = linhas[i].site + "." + linhas[i].estrutura + est_n + "." + linhas[i].ativo_pai + "." + linhas[i].excel + linhas[i].elemento_n;
                            } else {

                                ativo = linhas[i].site + "." + linhas[i].estrutura + est_n + "." + linhas[i].excel + linhas[i].elemento_n;

                                ativo_pai = linhas[i].site + "." + linhas[i].estrutura + est_n + "." + linhas[i].ativo_pai;
                            }

                            if (linhas[i].tipo_site === 1 || linhas[i].tipo_site === "1") {

                                ativo = "M."+uf+"." + ativo;
                                ativo_pai = "M."+uf+"." + ativo_pai;
                            } else {

                                ativo = "V2."+uf+"." + ativo;
                                ativo_pai = "V2."+uf+"." + ativo_pai;
                            }

                            lista += "<tr class='text-center' id='linhaElemento_" + linhas[i].id + "'>";
                            lista += "<td id='ativo_" + linhas[i].id + "'>" + ativo + "</td>";
                            lista += "<td>" + linhas[i].site + "</td>";
                            lista += "<td>" + linhas[i].estrutura + "</td>";
                            lista += "<td>" + linhas[i].elemento + "</td>";
                            lista += "<td>" + linhas[i].data + " - " + linhas[i].hora + "</td>";
                            lista += "<td id='statusLn" + linhas[i].id + "'>" + linhas[i].status + "</td>";
                            lista += "<td><button value='" + linhas[i].id + "' class='bt_form_view btn btn-outline-info btn-sm'><i class='icon-popup'></i> Ver</button></td>";
                            if (linhas[i].status === "CONCLUÍDO") {
                                lista += "<td><input type='checkbox' id='ck" + linhas[i].id + "' class='ck mt-1' value='" + linhas[i].id + "'/></td>";
                            } else {
                                lista += "<td><input type='checkbox' id='ck" + linhas[i].id + "' class='ck mt-1 border-success' value='" + linhas[i].id + "'/></td>";
                            }

                            lista += "</tr>";
                        }
                        lista += "</tbody>";
                        lista += "</table>";
                        $("#Lista").slideDown("slow").html(lista);

                        $("#acao").slideDown("fast");
                        modal_abrir_detalhe();

                    });
                }
            }
        });
    }

    function checkConta() {

        var nCheck = 0;
        $('.ck').each(function() {
            nCheck = nCheck + 1;
        });
        return nCheck;
    }

    function checkedConta() {

        var nCheck = 0;
        $('.ck').each(function() {
            if (this.checked) {
                nCheck = nCheck + 1;
            }
        });
        return nCheck;
    }

    function checkIds() {

        var idSoliciatacao = "";
        $('.ck').each(function() {
            if (this.checked) {
                idSoliciatacao = this.value + "|" + idSoliciatacao;
            }
        });
        return idSoliciatacao;
    }

    function listIds(classe) {

        var idSoliciatacao = "";
        $('.' + classe).each(function() {
            idSoliciatacao = this.value + "|" + idSoliciatacao;
        });
        return idSoliciatacao;
    }

    function modal_abrir_detalhe() {
        $(".bt_form_view").click(function() {
            var id = $(this).attr('value');
            var ativo = $("#ativo_" + id).text();

            $("#ModalView").modal("show");

            form_detalhe(id, ativo);
        });
    }

    function form_detalhe(id, ativo) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "elemento", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPELEMENTO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.status === "EM TRATATIVA") {

                    $("#concluirElemento").slideDown("fast");
                } else {
                    $("#concluirElemento").slideUp("fast");
                }

                $("#viewID").empty().text(dados.id);
                $("#viewAtivo").empty().text(ativo);
                $("#viewNome").empty().text(dados.nome);
                $("#viewCN").empty().text(dados.cn);
                $("#viewSite").empty().text(dados.site_tipo + " - " + dados.site);
                $("#viewEstrutura").empty().text(dados.estrutura);
                $("#viewNgabinete").empty().text(dados.Ngabinete);
                $("#viewElemento").empty().text(dados.elemento);
                $("#viewNelemento").empty().text(dados.Nelemento);
                $("#viewStatus").empty().text(dados.status);
                $("#viewObs").empty().text(dados.obs);
            }
        });
    }

    function form_cn() {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaCn" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPELEMENTO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = "<option value=\"0\">CN</option>";
                    var linha = eval(dados);
                    for (var i = 0; i < linha.length; i++) {
                        linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].cn + "</option>";
                    }
                    $("#filtroCN").slideDown("fast").html(linhas);
                }
            }
        });
    }

    function form_status() {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaStatus" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPELEMENTO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = "<option value=\"0\">STATUS</option>";
                    var linha = eval(dados);
                    for (var i = 0; i < linha.length; i++) {
                        linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].cn + "</option>";
                    }
                    $("#filtroStatus").slideDown("fast").html(linhas);
                }
            }
        });
    }

    function ConcluiElemento(solicitacao) {

        $("#ModalRetornoConcluir").text("");
        $("#ModalRetornoConcluir").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "elementoConclui", id: solicitacao },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPELEMENTO', //Definindo o arquivo onde serão buscados os dados
            async: false,
            success: function(dados) {
                if (dados.erro === "1") {

                    classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    var msg = "<i class='icon-attention'></i> " + dados.msg;

                    $("#ModalRetornoConcluir").slideDown("fast").addClass(classe).append(msg).delay("1900").slideUp("fast");

                } else {
                    classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";
                    var msg = "<i class='icon-ok-circle'></i> " + dados.msg;

                    $("#ModalRetornoConcluir").slideDown("fast").addClass(classe).append(msg).delay("1900").slideUp("fast", function() {

                        $("#ModalView").modal("hide");
                        $("#statusLn" + solicitacao).text("").append("CONCLUÍDO");
                    });
                }
            }
        });
    }
});