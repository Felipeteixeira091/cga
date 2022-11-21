$(document).ready(function() {

    SolicitacaoLista();
    qtd();

    $("#formVolta").click(function() {
        window.location.href = 'SMA_EDICAO';
    });
    $("#formCancela").click(function() {
        var solicitacao = $("#NumeroSolicitacao").text();
        CancelaSolicitacao(solicitacao);
    });
    $("#formConclui").click(function() {
        var solicitacao = $("#NumeroSolicitacao").text();
        ConcluiSolicitacao(solicitacao);
    });

    function formEdita() {
        $(".btAbreSolicitacao").click(function() {
            var id = $(this).attr('value');

            solicitacaoVerifica(id);
        });
    }

    function qtd() {

        $("#retornoNova1").text("");
        $("#retornoNova1").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "editaConta" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAEDICAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var qtd = dados.qtd;
                var classe = "font-weight-bold text-muted";

                $("#pendentesEdicao").slideDown("fast").addClass(classe).append(qtd);
            }
        });
    }

    function SolicitacaoLista() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "solicitacaoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAEDICAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var verifica = eval(dados);

                if (verifica.length === 0) {

                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    var ico = "<i class='icon-attention'></i>";
                    var msg = " Nenhuma correspondência encontrada."

                    $("#ModalRetornoSite").slideDown("fast").addClass(classe).append(ico + msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = eval(dados);

                    var lista = "";
                    lista += "<table class='table table-striped w-auto'>";
                    lista += "<thead class='thead-dark'>";
                    lista += "<tr>";
                    lista += "<th scope='col'>ID</th>";
                    lista += "<th scope='col'>SITE</th>";
                    lista += "<th scope='col'>HORÁRIO</th>";
                    lista += "<th scope='col'>EDITA</th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody>";
                    for (var i = 0; i < linhas.length; i++) {
                        lista += "<tr id='linha" + linhas[i].id + "'>";
                        lista += "<td>" + linhas[i].id + "</td>";
                        lista += "<td>[" + linhas[i].cn + "]" + linhas[i].sigla + "</td>";
                        lista += "<td>" + linhas[i].data + " " + linhas[i].hora + "</td>";
                        lista += "<td> <button value='" + linhas[i].id + "' class='btAbreSolicitacao btn btn-outline-info btn-sm'><i class='icon-popup'></i> EDITA</button>";
                        lista += "</tr>";
                    }
                    lista += "</tbody>";
                    lista += "</table>";

                    $("#listaSolicitacao").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);

                    formEdita();
                }
            }
        });
    }

    function solicitacaoVerifica(id) {

        $("#pa_formulario1").slideUp("fast");
        $("#pa_formulario2").slideUp("fast");
        $("#ListaItensAdd").slideUp("fast");
        $("#SolicitacaoItens").slideUp("fast");
        $("#listaSolicitacao").slideUp("fast");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoVerifica", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAEDICAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var ativa = dados.ativa;
                var scID = dados.id;

                var sobressalente = dados.sobressalente;

                if (ativa === "sim") {
                    $("#NumeroSolicitacao").text(scID);
                    $("#EditRE").text(dados.re);
                    $("#EditNome").text(dados.nome);

                    if (sobressalente === "1") {
                        $("#Sobressalente").text("SIM");

                    } else {
                        $("#Sobressalente").text("NÃO");
                    }

                    $("#pa_formulario2").slideDown("fast");
                    ItemProcura();
                    ItemLista(dados.edicao);
                }
                if (dados.edicao === "0") {
                    $(".btn_edit").attr("disabled", true);
                }
            }
        });
    }

    function ItemProcura() {

        $("#btFiltra1").click(function() {

            $("#retornoNova1").text("");
            $("#retornoNova1").removeClass();

            var txt = $("#ItemTXT").val();

            var sobressalente = $("#Sobressalente").text();

            $("#SolicitacaoItens").slideUp("fast");

            if (txt.length < 3) {
                $("#ListaItensAdd").slideUp("fast").val("");
                var msg = "<i class='icon-attention'></i> Nenhuma correspondência!";
                var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {
                    ItemLista();
                });

            } else {
                $.ajax({
                    type: 'post', //Definimos o método HTTP usado
                    data: { acao: "ItemProcura", txt: txt, sobressalente: sobressalente },
                    dataType: 'json', //Definimos o tipo de retorno
                    url: 'SMAEDICAO', //Definindo o arquivo onde serão buscados os dados
                    success: function(dados) {
                        if (dados.length === 0) {

                            var msg = "<i class='icon-attention'></i> Nenhuma correspondência!";
                            var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                            $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                        } else {
                            var linhas = eval(dados);

                            var lista = "";
                            lista += "<div class='card border-light mt-2 p-1'>";
                            lista += "<div class='card-header font-weight-bold'>Resultado da busca</div>";
                            lista += "<table class='table table-striped w-auto'>";
                            lista += "<thead class='thead-dark'>";
                            lista += "<tr>";
                            lista += "<th scope='col'>PA</th>";
                            lista += "<th scope='col'>DESCRIÇÃO</th>";
                            lista += "<th scope='col'>TIPO</th>";
                            lista += "<th scope='col'>QTD</th>";
                            lista += "<th scope='col'>ADICIONAR</th>";
                            lista += "</tr>";
                            lista += "</thead>";
                            lista += "<tbody>";
                            for (var i = 0; i < linhas.length; i++) {
                                lista += "<tr id='linha" + linhas[i].id + "'>";
                                lista += "<td>" + linhas[i].numero + "</td>";
                                lista += "<td>" + linhas[i].descricao + "</td>";
                                lista += "<td>" + linhas[i].tipo + "</td>";
                                var qtd = "qtd_item" + linhas[i].id;
                                lista += "<td><input class='form-control' id=" + qtd + " value='1' type='number'></td>";
                                lista += "<td><button class='btAddItem btn btn-sm btn-outline-info' value='" + linhas[i].id + "'><i class='icon-plus-circled'></i> Adicionar</button></td>";
                                lista += "</tr>";
                            }
                            lista += "</tbody>";
                            lista += "</table></div>";
                            $("#ListaItensAdd").slideDown("fast").html(lista);
                            ItemAdd();
                        }
                    }
                });
            }
        });
    }

    function ConcluiSolicitacao(solicitacao) {

        $("#retornoNova1").text("");
        $("#retornoNova1").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoConclui", solicitacao: solicitacao },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAEDICAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {

                    var msg = dados.msg;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                } else {

                    var msg = dados.msg;
                    var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        window.location.reload();
                    });
                }

            }
        });
    }

    function CancelaSolicitacao(solicitacao) {

        $("#retornoNova1").text("");
        $("#retornoNova1").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoCancela", solicitacao: solicitacao },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAEDICAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var msg = dados.msg;
                var classe = "bg-info rounded font-weight-bold text-white pt-2 pb-2";

                $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                    window.location.reload();
                });
            }
        });
    }

    function ItemAdd() {

        $(".btAddItem").click(function() {
            var pa = $(this).attr('value');

            var qtd = "qtd_item" + pa;
            var quantidade = $("#" + qtd).val();

            var solicitacao = $("#NumeroSolicitacao").text();

            $("#retornoNova1").text("");
            $("#retornoNova1").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "ItemAdd", solicitacao: solicitacao, pa: pa, quantidade: quantidade },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SMAEDICAO', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {
                    if (dados.erro === "1") {

                        var msg = dados.msg;
                        var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                        $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");
                    } else {

                        var msg = "<i class='icon-ok-circle-1'></i> " + dados.msg;
                        var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                        $("#ListaItensAdd").slideUp("slow");

                        $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                            $("#ItemTXT").val("");
                            $("#ItemQuantidade").val("");
                            ItemLista();
                        });
                    }
                }
            });
        });
    }

    function ItemLista(edicao) {

        var id = $("#NumeroSolicitacao").text();
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "ItensSolicitacao", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAEDICAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = eval(dados);

                $("#SolicitacaoItens").slideUp("fast");

                var lista = "";
                if (linhas.length > 0) {
                    lista += "<div class='card border mt-2 p-1'>";
                    lista += "<div class='card-header font-weight-bold'>Itens já adicionados à solicitação</div>";

                    lista += "<div class='row mt-1'>";

                    for (var i = 0; i < linhas.length; i++) {

                        lista += "<div class='col' id='pa" + linhas[i].id + "'>";
                        lista += "<div class='card bg-light mb-2' style='max-width: 18rem;'>";
                        lista += "<div class='card-header'><span class='text-muted font-weight-bold'>" + linhas[i].pa + "</span></div>";
                        lista += "<div class='card-body'>";
                        lista += "<ul class='list-group list'>";
                        lista += "<li class='list-group-item text-left'><i class='icon-cd'></i> <b>DESCRIÇÃO:</b> " + linhas[i].descricao + "</li>";
                        lista += "<li class='list-group-item text-left'><i class='icon-cd'></i><b>QUANTIDADE:</b> " + linhas[i].quantidade + "</li>";
                        lista += "</ul>";
                        lista += "</div>";

                        lista += "<div class='card-footer text-muted'><button value='" + linhas[i].id + "' class='btRemoveItem btn btn-sm btn-outline-danger'><i class='icon-minus-circled'></i> Remover</button></div>";
                        lista += "</div>";
                        lista += "</div>";
                    }
                    $("#SolicitacaoItens").slideDown("fast").html(lista);
                    ItenRemove();
                }
                if (edicao === "0") {
                    $(".btRemoveItem").attr("disabled", true);
                }
            }
        });
    }

    function ItenRemove() {

        $(".btRemoveItem").click(function() {
            var linha = $(this).attr('value');

            $("#retornoNova1").text("");
            $("#retornoNova1").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "ItemRemove", id: linha },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SMAEDICAO', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {

                    var msg = "<i class='icon-ok-circle-1'></i> " + dados.msg;
                    var classe = "bg-info rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        $("#pa" + linha).fadeOut("fast");
                        $("#ItemTXT").val("");
                        $("#ItemQuantidade").val("");
                        ItemLista();
                    });

                }
            });
        });
    }
});