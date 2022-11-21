$(document).ready(function () {
    $('#lc_obs').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9,.]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });

    $("#sc_cn").change(function () {

        var cn = $("#sc_cn").val();
        if (cn === "G") {
            $("#bt_xls").attr("disabled", false);
        } else {
            $("#bt_xls").attr("disabled", true);
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
        var txt = $("#sc_nome").val();

        var href = "XLSSGASALDO?acao=xls&cn=" + cn + "&txt=" + txt;

        window.open(href);
    });


    function form_cn() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaCN" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDOGERAL', //Definindo o arquivo onde serão buscados os dados
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
            url: 'SGASALDOGERAL', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var verifica = eval(dados);

                var classe = "bg-danger rounded font-weight-bold text-white p-2";
                var msg = "<i class='icon-attention-2'></i> Nenhum resultado encontrado.";

                $("#retornoFiltro").text("");
                $("#retornoFiltro").removeClass();

                if (verifica.length === 0) {
                    $("#bt_xls").attr("disabled", true);

                    $("#ListaSC").slideUp("fast").html("");

                    $("#retornoFiltro").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast");

                } else {
                    $("#bt_xls").attr("disabled", false);

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
                        lista += "<td style='text-align: center'><button value='" + linhas[i].re + "' class='btEstoque btn btn-sm btn-light border text-muted'><i class='bi bi-info-square'></i> Ver</button>";
                        lista += "</tr>";
                    }
                    lista += "</tbody>";
                    lista += "</div>";
                    lista += "</table>";

                    $("#ListaSC").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);
                    // $("#DivXls").slideDown("fast");
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
            $("#tituloEstoque").text(nome + " - RE: " + colaborador);

        });
    }

    function LCEstoque(colaborador) {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "LCEstoque", colaborador: colaborador },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDOGERAL', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = eval(dados.geral);
                var sma = eval(dados.sma);
                var sga = eval(dados.sga);

                var lista_g = "<div class=''>";
                lista_g += "<table class='table table-striped w-auto mt-1'>";
                lista_g += "<thead class='thead-dark'>";
                lista_g += "<tr>";
                lista_g += "<th style='text-align: center' scope='col'>TIPO</th>";
                lista_g += "<th style='text-align: center' scope='col'>M.Retirado (SMA)</th>";
                lista_g += "<th style='text-align: center' scope='col'>M. novo</th>";
               // lista_g += "<th style='text-align: center' scope='col'>M. declarado</th>";
                lista_g += "<th style='text-align: center' scope='col'>M. devolvido</th>";
                lista_g += "</tr>";
                lista_g += "</thead>";
                lista_g += "<tbody>";
                for (var i = 0; i < linhas.length; i++) {

                    var pre = linhas[i].pb_sga - linhas[i].sga;
                    var saldo = linhas[i].sma - parseInt(linhas[i].sga);

                    lista_g += "<tr id='linha" + linhas[i].id + "'>";
                    lista_g += "<td style='text-align: center'><i class='" + linhas[i].ico + "'></i>" + linhas[i].tipo + "</td>";
                    lista_g += "<td style='text-align: center'>" + linhas[i].sma + "</td>";
                    lista_g += "<td style='text-align: center'>" + saldo + "</td>";
                  //  lista_g += "<td style='text-align: center'>" + pre + "</td>";
                    lista_g += "<td style='text-align: center'>" + linhas[i].sga + "</td>";

                    lista_g += "</tr>";
                }
                lista_g += "</tbody>";
                lista_g += "</div>";
                lista_g += "</table>";

                $("#Estoque").slideUp("slow").html("").delay(100).slideDown("fast").html(lista_g);

                var lista_sma = "<div class='card border-light mt-2 p-1'>";
                lista_sma += "<table class='table table-sm table-striped w-auto mt-1'>";
                lista_sma += "<thead class='thead-dark'>";
                lista_sma += "<tr>";
                lista_sma += "<th style='text-align: center' scope='col'><small>PA</small></th>";
                lista_sma += "<th style='text-align: center' scope='col'><small>QTD TOTAL</small></th>";
                lista_sma += "<th style='text-align: center' scope='col'><small>DATA/HORA</small></th>";
                lista_sma += "</tr>";
                lista_sma += "</thead>";
                lista_sma += "<tbody>";
                for (var i = 0; i < sma.length; i++) {

                    lista_sma += "<tr id='linha" + sma[i].id + "'>";
                    lista_sma += "<td style='text-align: center'><small>" + sma[i].pa + "</small></td>";
                    lista_sma += "<td style='text-align: center'><small>" + sma[i].qtd + "</small></td>";
                    lista_sma += "<td style='text-align: center'><small>" + sma[i].dh + "</small></td>";
                    lista_sma += "</tr>";
                }
                lista_sma += "</tbody>";
                lista_sma += "</div>";
                lista_sma += "</table>";

                $("#EstoqueSMA").slideUp("slow").html("").delay(100).slideDown("fast").html(lista_sma);

                var lista_sga = "<div class='card border-light mt-2 p-1'>";
                lista_sga += "<table class='table table-sm table-striped w-auto mt-1'>";
                lista_sga += "<thead class='thead-dark'>";
                lista_sga += "<tr>";
                lista_sga += "<th style='text-align: center' scope='col'><small>TIPO</small></th>";
                lista_sga += "<th style='text-align: center' scope='col'><small>DECLARADO</small></th>";
                lista_sga += "<th style='text-align: center' scope='col'><small>ENTREGUE</small></th>";
                lista_sga += "<th style='text-align: center' scope='col'><small>DATA/HORA</small></th>";
                lista_sga += "</tr>";
                lista_sga += "</thead>";
                lista_sga += "<tbody>";
                for (var i = 0; i < sga.length; i++) {

                    lista_sga += "<tr id='linha" + sga[i].id + "'>";
                    lista_sga += "<td style='text-align: center'><small>" + sga[i].tipo + "</small></td>";
                    lista_sga += "<td style='text-align: center'><small>" + sga[i].declarado + "</small></td>";
                    lista_sga += "<td style='text-align: center'><small>" + sga[i].recebido + "</small></td>";
                    lista_sga += "<td style='text-align: center'><small>" + sga[i].dh + "</small></td>";
                    lista_sga += "</tr>";
                }
                lista_sga += "</tbody>";
                lista_sga += "</div>";
                lista_sga += "</table>";

                $("#EstoqueSGA").slideUp("slow").html("").delay(100).slideDown("fast").html(lista_sga);
            }
        });
    }
    function SGADetalhe(colaborador) {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SGADetalhe", colaborador: colaborador },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDOGERAL', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var sma = eval(dados.sma);
                var sga = eval(dados.sga);

                var lista_sma = "<div class='card border-light mt-2 p-1'>";
                lista_sma += "<table class='table table-striped w-auto mt-1'>";
                lista_sma += "<thead class='thead-dark'>";
                lista_sma += "<tr>";
                lista_sma += "<th style='text-align: center' scope='col'>PA</th>";
                lista_sma += "<th style='text-align: center' scope='col'>QTD</th>";
                lista_sma += "<th style='text-align: center' scope='col'>X</th>";
                lista_sma += "<th style='text-align: center' scope='col'>DATA/HORA</th>";
                lista_sma += "</tr>";
                lista_sma += "</thead>";
                lista_sma += "<tbody>";
                for (var i = 0; i < linhas.length; i++) {

                    lista_sma += "<tr id='linha" + linhas[i].id + "'>";
                    lista_sma += "<td style='text-align: center'>" + linhas[i].pa + "</td>";
                    lista_sma += "<td style='text-align: center'>" + linhas[i].qtd + "</td>";
                    lista_sma += "<td style='text-align: center'>" + linhas[i].x + "</td>";
                    lista_sma += "<td style='text-align: center'>" + linhas[i].dh + "</td>";
                    lista_sma += "</tr>";
                }
                lista_sma += "</tbody>";
                lista_sma += "</div>";
                lista_sma += "</table>";

                $("#Estoque").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);

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