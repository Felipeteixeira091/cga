$(document).ready(function() {

    $("#pa_formulario1").slideDown("fast");
    form_tipo();

    $("#btFiltra").click(function() {

        var txt = $("#paTXT").val();
        var tipo = $("#paTipo").val();

        $("#retornoProcurar").text("");
        $("#retornoProcurar").removeClass();

        $("#ListaPA").slideUp("fast");
        if (txt === "" && tipo === "0") {


            var msg = "<i class='icon-attention'></i> Dados insuficientes.";
            var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
            $("#retornoProcurar").fadeIn(1200).addClass(classe).append(msg).delay(800).fadeOut(1000);

        } else {
            PaProcura(txt, tipo);
        }
    });

    $("#btFormNovo").click(function() {
        $("#pa_formulario1").slideUp("fast", function() {

            $("#ListaPA").slideUp("fast");
            $("#pa_formulario_novo").slideDown("fast");
        });
        form_tipo();
        form_tipo_unidade();
        form_gas();
    });

    $("#bt_cadastro_PA").click(function() {

        cadastro();
    });
    $("#bt_edita_PA").click(function() {

        edita();
    });

    $("#bt_cadastro_PA_voltar").click(function() {

        cadastro_PA_voltar();
    });

    function formEdita() {

        $(".btEditaPA").click(function() {
            var id = $(this).attr('value');

            pa_dados(id);

            $("#Modal_edita").modal("show");

        });
    }

    function pa_dados(id) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "dados", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPA', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                dados_select("Tipo", dados.tipo);
                dados_select("Medida", dados.unidade);
                dados_select("Gas", dados.gas);

                $("#editaId").text(dados.id);
                $("#editaNome").text(dados.cadastro);
                $("#editaPa").val(dados.numero);
                $("#editaDescricao").val(dados.descricao);
                $("#editaObs").val(dados.obs);
                $("#editaStatus").val(dados.status);
                $("#editaSobressalente").val(dados.sobressalente);
            }
        });
    }

    function dados_select(tipo, select) {

        var acao = "detalhe_" + tipo;
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: acao },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPA', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (tipo === "Gas") {
                    var linhas = "<option value=\"ND\">É um gás</option>";
                    linhas += "<option value=\"0\">NÃO SE APLICA</option>";
                } else {
                    var linhas = "<option value=\"ND\">Selecione</option>";
                }

                var linha = eval(dados);

                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }

                $("#edita" + tipo).slideDown("fast").html(linhas);

                var opt = "option[value=" + select + "]";
                $("#edita" + tipo).find(opt).attr("selected", "selected");

            }
        });
    }

    function cadastro_PA_voltar() {

        $("#pa_formulario_novo").slideUp("fast", function() {

            window.location.replace("SMA_PA");
        });
    }

    function form_tipo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "tipoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPA', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = "<option value=\"0\">TODOS</option>";
                    var linha = eval(dados);
                    for (var i = 0; i < linha.length; i++) {
                        linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                    }
                    $("#paTipo").slideDown("fast").html(linhas);
                    $("#pa_tipo").slideDown("fast").html(linhas);
                }
            }
        });
    }

    function form_tipo_unidade() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "tipoUnidadeLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPA', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = "<option value=\"0\">MEDIDA</option>";
                    var linha = eval(dados);
                    for (var i = 0; i < linha.length; i++) {
                        linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                    }
                    $("#pa_tipo_unidade").slideDown("fast").html(linhas);
                }
            }
        });
    }

    function form_gas() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "tipoGasLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPA', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = "<option value=\"ND\">É UM GÁS?</option>";
                    linhas += "<option value=\"0\">NÃO SE APLICA</option>";
                    var linha = eval(dados);
                    for (var i = 0; i < linha.length; i++) {
                        linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                    }
                    $("#pa_gas").slideDown("fast").html(linhas);
                }
            }
        });
    }

    function PaProcura(txt, tipo) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "PAProcura", txt: txt, tipo: tipo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPA', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {
                    var msg = dados.msg;

                    $("#ListaPA").slideUp(1200, function() {

                        var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                        $("#retornoProcurar").fadeIn(1200).addClass(classe).append(msg).delay(800).fadeOut(1000);
                    });

                } else {
                    var linhas = eval(dados);

                    var lista = "";
                    lista += "<table class='table table-striped w-auto'>";
                    lista += "<thead class='thead-dark'>";
                    lista += "<tr>";
                    lista += "<th scope='col'>PA</th>";
                    lista += "<th scope='col'>DESCRIÇÃO</th>";
                    lista += "<th scope='col'>TIPO</th>";
                    lista += "<th scope='col'>SOBRESSALENTE</th>";
                    lista += "<th scope='col' style='text-align: center'><i class='icon-popup'></i></th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody>";
                    for (var i = 0; i < linhas.length; i++) {
                        lista += "<tr id='linha" + linhas[i].id + "'>";
                        lista += "<td>" + linhas[i].numero + "</td>";
                        lista += "<td>" + linhas[i].descricao + "</td>";
                        lista += "<td>" + linhas[i].tipo + "</td>";
                        lista += "<td>" + linhas[i].sobressalente + "</td>";
                        lista += "<td style='text-align: center'><button value='" + linhas[i].id + "' class='btEditaPA btn btn-outline-info btn-sm'><i class='icon-popup'></i> Ver</button></td>";
                        lista += "</tr>";
                    }
                    lista += "</tbody>";
                    lista += "</table>";
                    $("#ListaPA").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);
                    formEdita();
                }
            }
        });
    }

    function cadastro() {

        var numero = $("#pa_numero").val();
        var descricao = $("#pa_descricao").val();
        var tipo = $("#pa_tipo").val();
        var observacoes = $("#pa_observacoes").val();
        var sobressalente = $("#pa_sobressalente").val();
        var medida = $("#pa_tipo_unidade").val();
        var gas = $("#pa_gas").val();
        var status = "0";

        $("#retornoCadastro").text("");
        $("#retornoCadastro").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cadastroPA", numero: numero, descricao: descricao, tipo: tipo, observacoes: observacoes, sobressalente: sobressalente, medida: medida, gas: gas, status: status },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPA', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {

                    var msg = dados.msg;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoCadastro").fadeIn(1200).addClass(classe).append(msg).delay(800).fadeOut(1000);

                } else {

                    var msg = dados.msg;
                    var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoCadastro").fadeIn(1200).addClass(classe).append(msg).delay(800).fadeOut(1200, function() {

                        window.location.replace("SMA_PA");
                    });
                }
            }
        });
    }

    function edita() {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: {
                acao: "editaPA",
                id: $("#editaId").text(),
                numero: $("#editaPa").val(),
                descricao: $("#editaDescricao").val(),
                tipo: $("#editaTipo").val(),
                obs: $("#editaObs").val(),
                sobressalente: $("#editaSobressalente").val(),
                medida: $("#editaMedida").val(),
                gas: $("#editaGas").val(),
                status: $("#editaStatus").val()
            },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMAPA', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_edita").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");
                } else {

                    var msg = "<i class='icon-ok-1'></i> " + dados.msg;
                    var classe = "bg bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_edita").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        $("#ListaPA").slideUp("fast");
                        $("#Modal_edita").modal("hide");
                    });
                }
            }
        });
    }
});