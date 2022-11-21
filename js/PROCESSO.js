$(document).ready(function () {
    lista();

    function processo_exclui() {
        $(".bt_processo_exclui").click(function () {
            var id = $(this).attr('value');

            $("#modal_processo_nome").text($("#processo_nome_" + id).text());
            $("#bt_modal_processo_exclui").val(id);
            $("#Modal_processo_exclui").modal("show");

        });
    }
    $("#bt_modal_processo_exclui").click(function () {

        var id = $("#bt_modal_processo_exclui").val();

        processo_exclui_confirma(id);

    });
    function processo_exclui_confirma(id) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "exclui", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PROCESSOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var msg = dados.msg;

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetornoExclusao").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");

                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetornoExclusao").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        $("#card_processo_" + id).fadeOut("fast");
                        //$("#novo").modal("hide");
                        //window.location.replace("PROCESSO_ICO");
                    });
                }
            }
        });
    }
    $("#btFormNovo").click(function () {
        $("#lista").slideUp("fast", function () {

            $("#divUpload").slideUp("fast");
            $("#novo").modal("show");
        });

        form_tipo();
    });

    $("#bt_div_upload").click(function () {

        $("#divUpload").slideToggle("fast");

    })

    $("#bt_novo").click(function () {

        var processo = {
            tipo: $("#novo_tipo").val(),
            nome: $("#novo_nome").val(),
            desc: $("#novo_desc").val(),
            anexo: $("#novo_arq").text()
        };
        novo(processo);
    })

    function novo(processo) {

        $("#ModalRetorno_novo").text("");
        $("#ModalRetorno_novo").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "novo", processo: processo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PROCESSOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {


                if (dados.erro === "1") {
                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_novo").empty().slideDown("fast").addClass(classe).html(msg).delay(2000).slideUp("fast");

                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";
                    var msg = "<i class='icon-ok-circle'></i> " + dados.msg;

                    $("#ModalRetorno_novo").empty().slideDown("fast").addClass(classe).html(msg).delay(1200).slideUp("fast", function () {

                        $("#Modal_processo_exclui").modal("hide");
                        window.location.replace("PROCESSO_ICO");
                    });
                }
            }
        });
    }

    function lista() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "lista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PROCESSOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linha = eval(dados.processo);
                var p_exclui = dados.p;
                var body = "";
                for (var i = 0; i < linha.length; i++) {
                    body += "<div class='col-sm-6 mt-2' id='card_processo_" + linha[i].id + "'>";
                    body += "<div class='card'>";
                    body += "<div class='card-body'>";
                    body += "<h5 class='card-title' id='processo_nome_" + linha[i].id + "'>" + linha[i].nome + "</h5>";
                    body += "<p class='card-text'>" + linha[i].descricao + "</p>";
                    body += "<a href='processo/" + linha[i].anexo + "' target='_blank' class='btn btn-sm btn-dark'>Acessar</a>";

                    if (p_exclui > 0) {
                        body += "<button value='" + linha[i].id + "' class='bt_processo_exclui btn btn-sm btn-dark ml-2'>Excluir</button>";
                    }

                    body += "</div>";
                    body += "</div>";
                    body += "</div>";
                }
                $("#lista").html(body);
                processo_exclui();
            }
        });
    }
    function form_tipo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "tipo" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PROCESSOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">TIPO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#novo_tipo").slideDown("fast").html(linhas);
            }
        });
    }
});