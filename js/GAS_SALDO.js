$(document).ready(function () {
    $('#lc_obs').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9,.]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });

    $("#lc_formulario1").slideDown("fast");
    form_cn();

    $("#btFiltra").click(function () {

        var classe = "bg-danger rounded font-weight-bold text-white p-2";
        var msg = "<i class='icon-attention-2'></i> Informações insuficientes.";
        $("#retornoFiltro").text("");
        $("#retornoFiltro").removeClass();

        var cn = $("#sc_cn").val();
        var nome = $("#sc_nome").val();

        $("#ListaSC").slideUp("fast");
        if (cn === "0" && nome === "") {

            $("#retornoFiltro").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast");

        } else {
            SCProcura(cn, nome);
        }
    });
    $("#btFormNovo").click(function () {

        oculta();

        $("#ac_formulario_novo").modal("show");

        SiteProcura();
        form_gas();
    });
    $("#btModalSite").click(function () {
        $("#pesquisaSITE").modal("show");

    });

    $("#bt_cadastro_lc").click(function () {

        cadastro();
    });

    $("#bt_cadastro_lc_voltar").click(function () {

        oculta();
        window.location.replace("GAS_LANCAMENTO");
    });

    $("#bt_formVolta").click(function () {

        oculta();
        $("#ListaSC").slideDown("fast");

    });

    $("#bt_xls").click(function () {
        var cn = $("#sc_cn").val();
        var href = "GASXLS?acao=xlss&cn=" + cn;

        window.open(href);
    });

    $("#btAlteraSaldo").click(function () {

        var id = $("#idBag").text();
        var novoSaldo = $("#saldo_correto").val();
        confirmaAlteracao(id, novoSaldo);

    });
    function confirmaAlteracao(id, novoSaldo) {

        $("#retornoModalCorrige").text("");
        $("#retornoModalCorrige").removeClass();
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "alteraSaldo", id: id, novoSaldo: novoSaldo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'GASSALDO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#retornoModalCorrige").slideDown("fast").addClass(classe).append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#retornoModalCorrige").slideDown("fast").addClass(classe).append(dados.msg).delay(1500).slideUp("fast", function () {

                        $("#idBag").text("").fadeOut("fast");
                        $("#saldo_correto").text("");

                        location.reload();

                        $("#DetalheAltera").modal("hide");

                    });

                }
            }
        });
    }
    function form_gas() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaGAS" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'GASLANCAMENTO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">TIPO DE GÁS UTILIZADO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#lc_gas").slideDown("fast").html(linhas);
            }
        });
    }

    function form_cn() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaCN" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'GASSALDO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = "<option value=\"0\">CN</option>";
                    linhas += "<option value=\"G\">GERAL</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#sc_cn").slideDown("fast").html(linhas);

            }
        });
    }

    function SCProcura(cn, nome) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SCProcura", cn: cn, nome: nome },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'GASSALDO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var verifica = eval(dados);

                var classe = "bg-danger rounded font-weight-bold text-white p-2";
                var msg = "<i class='icon-attention-2'></i> Nenhum resultado encontrado.";

                $("#retornoFiltro").text("");
                $("#retornoFiltro").removeClass();

                if (verifica.length === 0) {
                    $("#ListaSC").slideUp("fast").html("");

                    $("#retornoFiltro").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast");

                } else {
                    var linhas = eval(dados);

                    var lista = "<div class='card border-light mt-2 p-1'>";
                    lista += "<table class='table table-striped w-auto mt-1'>";
                    lista += "<thead class='thead-dark'>";
                    lista += "<tr>";
                    lista += "<th style='text-align: center' scope='col'>NOME</th>";
                    lista += "<th style='text-align: center' scope='col'>CN</th>";
                    lista += "<th style='text-align: center' scope='col'>COORDENADOR</th>";
                    lista += "<th style='text-align: center' scope='col'>VER</th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody>";
                    for (var i = 0; i < linhas.length; i++) {
                        lista += "<tr id='linha" + linhas[i].id + "'>";
                        lista += "<td id='nome" + linhas[i].re + "' style='text-align: center'>" + linhas[i].nome + "</td>";
                        lista += "<td style='text-align: center'>" + linhas[i].cn + "</td>";
                        lista += "<td style='text-align: center'>" + linhas[i].coNome + "</td>";
                        lista += "<td style='text-align: center'><button value='" + linhas[i].re + "' class='btEstoque btn btn-sm btn-light border text-muted'><i class='icon-popup text-info'></i> Saldo</button>";
                        lista += "</tr>";
                    }
                    lista += "</tbody>";
                    lista += "</div>";
                    lista += "</table>";

                    $("#ListaSC").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);
                    $("#DivXls").slideDown("fast");
                    verBagagem();
                }
            }
        });
    }

    function verBagagem() {
        $(".btEstoque").click(function () {
            var colaborador = $(this).attr('value');
            var nome = $("#nome" + colaborador).text();

            LCEstoque(colaborador);
            $("#DetalheEstoque").modal("show");
            $("#tituloEstoque").text(nome);

        });
    }
    function modalCorrige() {
        $(".btCorrige").click(function () {
            var id = $(this).attr('value');
            $("#DetalheAltera").modal("show");
            saldo(id);

        });
    }
    function LCEstoque(colaborador) {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "LCEstoque", colaborador: colaborador },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'GASSALDO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var verifica = eval(dados);

                var classe = "bg-info rounded font-weight-bold text-white p-2";
                var msg = "<i class='icon-attention-2'></i> Estoque vazio.";

                $("#Estoque").text("");
                $("#Estoque").removeClass();

                if (verifica.length === 0) {

                    $("#Estoque").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast");

                } else {
                    var linhas = eval(dados);

                    var lista = "<div class='card border-light mt-2 p-1'>";
                    lista += "<table class='table table-striped w-auto mt-1'>";
                    lista += "<thead class='thead-dark'>";
                    lista += "<tr>";
                    lista += "<th style='text-align: center' scope='col'>TIPO</th>";
                    lista += "<th style='text-align: center' scope='col'>KG</th>";
                    lista += "<th style='text-align: center' scope='col'>CORREÇÃO</th>";
                    lista += "<th style='text-align: center' scope='col'>ÚLTIMA MODIFICAÇÃO</th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody>";
                    for (var i = 0; i < linhas.length; i++) {
                        lista += "<tr id='linha" + linhas[i].id + "'>";
                        lista += "<td style='text-align: center'>" + linhas[i].tipo + "</td>";
                        lista += "<td id='kg_" + linhas[i].id + "' style='text-align: center'>" + linhas[i].kg + "</td>";
                        lista += "<td style='text-align: center'><button class='btCorrige btn btn-sm btn-info btn-sm mr-1' value='" + linhas[i].id + "'><i class='icon-wrench-1'></i> CORRIGIR</button></td>";
                        lista += "<td id='data_" + linhas[i].id + "' style='text-align: center'>" + linhas[i].data + "</td>";
                        lista += "</tr>";
                    }
                    lista += "</tbody>";
                    lista += "</div>";
                    lista += "</table>";

                    $("#Estoque").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);
                    modalCorrige();
                }
            }
        });
    }
    function saldo(id) {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "saldo", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'GASSALDO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#saldoAtualModal").text("");
                $("#saldoAtualModal").text(dados.kg);

                $("#tipoGas").text("");
                $("#tipoGas").text(dados.tipo);
                $("#idBag").text("");
                $("#idBag").text(dados.id);
            }
        });
    }
    function oculta() {

        $("#ac_formulario_novo").slideUp("fast");
        $("#ac_formulario1").slideUp("fast");
        $("#ListaSC").slideUp("fast");
        $("#DetalheLancamento").slideUp("fast");
        $("#DivXls").slideUp("fast");
    }
});