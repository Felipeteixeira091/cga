$(document).ready(function () {
    nota_verifica();
    obter_lista("fornecedor1", 0);
    obter_lista("status1", 0);
    $("#btFiltraNota").click(function () {

        $("#retornoNotaPesquisa").text("");
        $("#retornoNotaPesquisa").removeClass();

        var data1 = $("#notaData1").val();
        var data2 = $("#notaData2").val();
        var status = $("#nota_lista_status1").val();
        var fornecedor = $("#nota_lista_fornecedor1").val();
        var txt = $("#notaTXT").val();

        $("#erro").text("");
        $("#notaLista").slideUp("fast");
        $("#bt_xls").attr("disabled", true);

        var tempo = 1500;
        var classe = "bg bg-danger rounded text-white font-weight-bold p-2";

        if (txt === "" && data1 === "" && data2 === "") {
            var msg = "<i class='icon-attention'></i> Informações insuficientes.";

            $("#retornoNotaPesquisa").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");
        } else {
            notaProcura(txt, data1, data2, status, fornecedor);
        }
    });

    $("#nota_btn_nova").click(function () {

        $("#nota_modal_nova").modal("show");
        nota_dados(0);
    });
    $("#nota_btn_upload").click(function () {

        $("#nota_modal_upload").modal("show");
    });

    $("#nota_btn_cadastra").click(function () {

        var dados = {
            acao: "nova",
            fornecedor: $("#nota_lista_fornecedor").val(),
            tipo: $("#nota_lista_tipo_nota").val(),
            pedido: $("#nota_nova_pedido").val()
        };

        $("#nota_modal_footer").text("");
        $("#nota_modal_footer").removeClass();

        $.ajax({
            type: 'post',
            data: dados,
            dataType: 'json',
            url: 'EXTVALIDA',
            success: function (dados) {
                var tempo = 1500;
                var nota = dados.nota;

                if (dados.erro === "1") {
                    var classe = "bg-danger rounded text-white font-weight-bold p-2";
                    var msg = "<i class='icon-attention'></i> " + dados.msg;

                    $("#nota_modal_footer").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                    $("#nota_btn_anexa").attr("disabled", true);
                    $("#nota_btn_cadastra").attr("disabled", false);

                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2";
                    var msg = "<i class='icon-attention'></i> " + dados.msg;

                    $("#nota_modal_footer").slideDown("fast").addClass(classe).append(dados.msg).delay(tempo).slideUp("fast", function () {
                       
                        $("#nota_input_responsavel").val(nota.nome);
                        $("#nota_badge_id").text(nota.id);
                        $("#nota_nova_pedido").val(nota.pedido);
                        $("#nota_btn_update").val("2");

                        $("#nota_lista_fornecedor").attr("disabled", true);
                        $("#nota_lista_tipo").attr("disabled", true);
                        $("#nota_nova_pedido").attr("disabled", true);
                        $("#nota_input_responsavel").attr("disabled", true);

                        $("#nota_btn_upload").attr("disabled", false);
                        $("#nota_btn_update").attr("disabled", false);
                        $("#nota_btn_cadastra").attr("disabled", true);
                    });
                }
            }
        });
        nota_verifica();
    });
    $("#nota_btn_update").click(function () {

        var id = $("#nota_badge_id").text();
        var status = $(this).attr('value');

        nota_update(status, id);
    });

    $("#nota_btn_deleta").click(function () {

        var texto = "de " + $('select[name="nota_lista_tipo_nota"] option:selected').text() + " do fornecedor " + $('select[name="nota_lista_fornecedor"] option:selected').text();
        $("#modal_nota_nome").text(texto);

        $("#Modal_nota_deleta").modal("show");
    });

    $("#bt_modal_nota_deleta").click(function () {

        var id = $("#nota_badge_id").text();

        nota_deleta_confirma(id);

    });
    function nota_deleta_confirma(id) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "deleta", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'EXTVALIDA', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var msg = dados.msg;

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetornoExclusao").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");

                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetornoExclusao").empty().slideDown("fast").addClass(classe).append(msg).delay(2200).slideUp("fast", function () {

                        $("#Modal_nota_deleta").modal("hide");
                        $("#nota_modal_nova").modal("hide");

                        $("#linha" + id).hide("fast");
                        //$("#novo").modal("hide");
                    });
                }
            }
        });
    }
    function nota_obter() {
        $(".nota_btn_obter").click(function () {
            var id = $(this).attr('value');
            nota_dados(id);
            $("#nota_modal_nova").modal("show");
        });
    }
    function nota_verifica() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "nota_verifica" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'EXTVALIDA', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#nota_btn_nova").val(dados.tipo);

            }
        });
    }
    function nota_dados(id) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "nota_obter", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'EXTVALIDA', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var nota = eval(dados.dados);

                if (dados.existe === "s" && nota.status === "1") {

                    $("#nota_modal_novo_titulo").text("Nota " + nota.id);
                    $("#nota_btn_update").text("Concluir solicitação");

                    // Listar fornecedores cadastrados
                    obter_lista("fornecedor", nota.fornecedor);
                    $("#nota_lista_fornecedor").attr("disabled", true);

                    // Listar tipo de nota
                    obter_lista("tipo_nota", nota.tipo);
                    $("#nota_lista_tipo_nota").attr("disabled", true);

                    $("#nota_span_status").text(nota.status_span);
                    $("#nota_input_responsavel").val(nota.nome);
                    $("#nota_input_responsavel").attr("disabled", true);

                    $("#nota_badge_id").text(nota.id);

                    $("#nota_btn_upload").attr("disabled", false);
                    $("#nota_btn_update").attr("disabled", false);
                    $("#nota_btn_update").text("Solicitar cadastro");
                    $("#nota_btn_update").val("2");
                    $("#nota_btn_cadastra").attr("disabled", true);

                    $("#nota_nova_pedido").val(nota.pedido);
                    $("#nota_nova_pedido").attr("disabled", true);

                    obter_anexo(nota.id);

                } else if (dados.existe === "s" && nota.status === "2") {

                    $("#nota_modal_novo_titulo").text("Nota " + nota.id);

                    // Listar fornecedores cadastrados
                    obter_lista("fornecedor", nota.fornecedor);
                    $("#nota_lista_fornecedor").attr("disabled", true);

                    // Listar tipo de nota
                    obter_lista("tipo_nota", nota.tipo);
                    $("#nota_lista_tipo_nota").attr("disabled", true);

                    $("#nota_span_status").text(nota.status_span);

                    $("#nota_input_responsavel").val(nota.nome);
                    $("#nota_input_responsavel").attr("disabled", true);

                    $("#nota_badge_id").text(nota.id);

                    $("#nota_btn_upload").attr("disabled", true);
                    $("#nota_btn_update").attr("disabled", false);
                    $("#nota_btn_update").text("Concluir");
                    $("#nota_btn_update").val("3");

                    $("#nota_btn_cadastra").attr("disabled", true);

                    $("#nota_nova_pedido").val(nota.pedido);
                    $("#nota_nova_pedido").attr("disabled", true);

                    obter_anexo(nota.id);
                } else if (dados.existe === "s" && nota.status === "3") {

                    $("#nota_modal_novo_titulo").text("Nota " + nota.id);

                    // Listar fornecedores cadastrados
                    obter_lista("fornecedor", nota.fornecedor);
                    $("#nota_lista_fornecedor").attr("disabled", true);

                    // Listar tipo de nota
                    obter_lista("tipo_nota", nota.tipo);
                    $("#nota_lista_tipo_nota").attr("disabled", true);

                    $("#nota_span_status").text(nota.status_span);

                    $("#nota_input_responsavel").val(nota.nome);
                    $("#nota_input_responsavel").attr("disabled", true);

                    $("#nota_badge_id").text(nota.id);

                    $("#nota_btn_upload").attr("disabled", true);
                    $("#nota_btn_update").attr("disabled", true);
                    $("#nota_btn_update").text("Finalizado");
                    $("#nota_btn_update").val("3");

                    $("#nota_btn_cadastra").attr("disabled", true);

                    $("#nota_nova_pedido").val(nota.pedido);
                    $("#nota_nova_pedido").attr("disabled", true);

                    obter_anexo(nota.id);
                } else {

                    $("#nota_modal_novo_titulo").text("Cadastro de nova nota");

                    // Listar fornecedores cadastrados
                    obter_lista("fornecedor", 0);
                    $("#nota_lista_fornecedor").attr("disabled", false);

                    // Listar tipo de nota
                    obter_lista("tipo_nota", 0);
                    $("#nota_lista_tipo_nota").attr("disabled", false);

                    $("#nota_input_responsavel").val(dados.nome);
                    $("#nota_input_responsavel").attr("disabled", true);

                    $("#nota_badge_id").text("");

                    $("#nota_span_status").text(nota.status_span);

                    $("#nota_btn_upload").attr("disabled", true);
                    $("#nota_btn_update").attr("disabled", true);
                    $("#nota_btn_cadastra").attr("disabled", false);

                    $("#nota_nova_pedido").val(nota.pedido);
                    $("#nota_nova_pedido").attr("disabled", false);

                    $("#nota_modal_arquivos").html("<span class'badge badge-danger'>Vazio.</span>");
                }
                if (dados.permissao === 0) {
                    $("#nota_btn_deleta").slideUp("fast");
                } else {
                    $("#nota_btn_deleta").slideDown("fast");
                }
            }
        });
    }
    function nota_update(status, id) {

        var dados = {
            acao: "update",
            status: status,
            id: id
        };

        $("#nota_modal_footer").text("");
        $("#nota_modal_footer").removeClass();

        $.ajax({
            type: 'post',
            data: dados,
            dataType: 'json',
            url: 'EXTVALIDA',
            success: function (dados) {
                var tempo = 1500;
                var nota = dados.nota;
                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2";
                    var msg = "<i class='icon-attention'></i> " + dados.msg;

                    $("#nota_modal_footer").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2";
                    var msg = "<i class='icon-attention'></i> " + dados.msg;

                    $("#nota_modal_footer").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast", function () {

                        $("#nota_modal_nova").modal("hide");
                        window.location.reload();
                    });
                }
            }
        });
    }
    function formModal(id) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "nota_obter", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'EXTVALIDA', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var nota = dados.dados;

                $("#nota_input_responsavel").val(nota.nome);
                $("#nota_badge_id").text(nota.id);

                // Listar fornecedores cadastrados
                obter_lista("fornecedor", nota.fornecedor, 0);
                // Listar tipo de nota
                obter_lista("tipo_nota", nota.tipo, 0)
                //Lista Anexos
                obter_anexo(nota.id);

                $("#nota_lista_fornecedor").attr("disabled", true);
                $("#nota_lista_tipo_nota").attr("disabled", true);

                $("#nota_btn_anexa").attr("disabled", true);
                $("#nota_btn_cadastra").attr("disabled", true);
                $("#nota_btn_update").attr("disabled", true);
            }
        });
    }
    function obter_lista(tipo, id) {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "lista", tipo: tipo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'EXTVALIDA', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = eval(dados);
                var lista = "<option value=0>SELECIONE</option>";
                for (var i = 0; i < linhas.length; i++) {
                    lista += "<option value=" + linhas[i].cod + ">" + linhas[i].txt + "</option>";
                }
                $("#nota_lista_" + tipo).html(lista);

                var opt = "option[value=" + id + "]";
                $("#nota_lista_" + tipo).find(opt).attr("selected", "selected");
            }
        });
    }
    function obter_anexo(id) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "lista", tipo: "anexo", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'EXTVALIDA', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = eval(dados);

                var lista = "";
                lista += "<div class='card border'>";
                lista += "<div class='card-header text-muted bg-light font-weight-bold'>Resultado da busca</div>";
                lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                lista += "<thead class='bg bg-light text-muted'>";
                lista += "<tr>";
                lista += "<th scope='col' class='text-center'>ARQUIVO</th>";
                lista += "<th scope='col' class='text-center'>DATA/HORA</th>";
                lista += "<th scope='col' class='text-center'>BAIXAR</i></th>";
                lista += "</tr>";
                lista += "</thead>";
                lista += "<tbody>";
                for (var i = 0; i < linhas.length; i++) {
                    lista += "<tr id='linha" + linhas[i].id + "'>";
                    lista += "<td class='text-center'>" + linhas[i].nome + "</td>";
                    lista += "<td class='text-center'>" + linhas[i].dh + "</td>";
                    lista += "<td class='text-center'><a download target='_blank' href='nota/" + linhas[i].arquivo + "'><button type='button' class='btn btn-sm btn-primary m-1'><i class='icon-attach-4'></i> Abrir</button></a></td>";
                    lista += "</tr>";
                }
                lista += "</tbody>";
                lista += "</table></div>";
                $("#nota_modal_arquivos").slideDown("fast").html(lista);
            }
        });
    }
    $("#bt_xls").click(function () {
        var txt = $("#notaTXT").val();
        var dataInicio = $("#notaData1").val();
        var dataFim = $("#notaData2").val();
        var href = "XLSEFOLHA?acao=xls&txt=" + txt + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim + "&status=" + status;

        window.open(href);
    });
    function notaProcura(txt, data1, data2, status, fornecedor) {

        $("#retornoNotaPesquisa").text("");
        $("#retornoNotaPesquisa").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "notaProcura", txt: txt, data1: data1, data2: data2, status: status, fornecedor: fornecedor },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'EXTVALIDA', //Definindo o arquivo onde serão buscados os dados
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
                        lista += "<th scope='col'>PEDIDO</th>";
                        lista += "<th scope='col'>DATA/HORA</th>";
                        lista += "<th scope='col'>STATUS</th>";
                        lista += "<th scope='col'>FORNECEDOR</th>";
                        lista += "<th scope='col'>ABRIR</th>";
                        lista += "</tr>";
                        lista += "</thead>";
                        lista += "<tbody>";
                        for (var i = 0; i < linhas.length; i++) {
                            lista += "<tr id='linha" + linhas[i].id + "'>";
                            lista += "<td>" + linhas[i].id + "</td>";
                            lista += "<td>" + linhas[i].pedido + "</td>";
                            lista += "<td>" + linhas[i].dh + "</td>";
                            lista += "<td>" + linhas[i].status + "</td>";
                            lista += "<td>" + linhas[i].fornecedor + "</td>";
                            lista += "<td><button type='button' value='" + linhas[i].id + "' class='nota_btn_obter btn btn-outline-info btn-sm'><i class='icon-popup'></i> Ver</button></td>";
                            lista += "</tr>";
                        }
                        lista += "</tbody>";
                        lista += "</table>";

                        $("#notaLista").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);

                        formModal();
                        nota_obter();
                    });
                }
            }
        });
    }
});