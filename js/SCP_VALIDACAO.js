$(document).ready(function() {
    $('#lc_obs').on('keypress', function(event) {
        var regex = new RegExp("^[a-zA-Z0-9,. ]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });

    $("#scp_formulario1").slideDown("fast");
    form_cn();
    form_status();

    $("#btFiltra").click(function() {

        var classe = "bg-danger rounded font-weight-bold text-white p-2";
        var msg = "<i class='icon-attention-2'></i> Informações insuficientes.";
        $("#retornoFiltro").text("");
        $("#retornoFiltro").removeClass();

        var cn = $("#scp_cn").val();
        var txt = $("#scp_Txt").val();
        var dataInicio = $("#scp_DataInicio").val();
        var dataFinal = $("#scp_DataFIm").val();
        var status = $("#scp_status").val();

        $("#ListaSCP").slideUp("fast");
        if (cn === "0" && txt === "" && !dataInicio && !dataFinal) {

            $("#retornoFiltro").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast");

        } else {
            SCProcura(cn, txt, dataInicio, dataFinal, status);
        }
    });

    $("#bt_validar").click(function() {
        validar(2);
    });
    $("#bt_invalido").click(function() {
        validar(3);
    });

    $("#bt_formVolta").click(function() {
        oculta();
        SCProcura($("#scp_cn").val(), $("#scp_Txt").val(), $("#scp_DataInicio").val(), $("#scp_DataFIm").val(), $("#scp_Status").val());

    });

    $("#bt_xls").click(function() {
        var cn = $("#scp_cn").val();
        var dataInicio = $("#scp_DataInicio").val();
        var dataFim = $("#scp_DataFIm").val();

        var href = "SCPXLS?acao=xls&cn=" + cn + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim;

        window.open(href);
    });

    function validar(status) {

        var id = $("#SCPId").text();
        var obs = $("#scp_obs").val();

        var delay = 2000;

        $("#retorno").text("");
        $("#retorno").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SCPValida", id: id, status: status, obs: obs },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCPVALIDACAO', //Definindo o arquivo onde serão buscados os dados
            beforeSend: function() {

                $("#bt_validar").attr("disabled", true);
                $("#bt_invalido").attr("disabled", true);

                var classe = "bg-info rounded font-weight-bold text-white pt-2 pb-2";
                var ico = "<i class='icon-clock'></i>";
                var msg = "Aguarde...";
                $("#retorno").slideDown("fast").addClass(classe).append(ico + " " + msg);

            },
            success: function(dados) {

                setTimeout(function() {


                    if (dados.erro === "1") {

                        $("#retorno").text("");
                        $("#retorno").removeClass();

                        var tempo = 2000;
                        var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                        var ico = "<i class='icon-attention'></i>";

                        $("#retorno").slideDown("fast").addClass(classe).append(ico + " " + dados.msg).delay(tempo).slideUp("fast", function() {

                            $("#bt_validar").attr("disabled", false);
                            $("#bt_invalido").attr("disabled", false);
                        });

                    } else {

                        $("#retorno").text("");
                        $("#retorno").removeClass();

                        var tempo = 1800;
                        var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";
                        var ico = "<i class='icon-ok-circled-1'></i>";

                        $("#retorno").slideDown("fast").addClass(classe).append(ico + " " + dados.msg).delay(tempo).slideUp("fast", function() {
                            $("#DetalheLancamento").modal('hide');

                            SCProcura($("#scp_cn").val(), $("#scp_Txt").val(), $("#scp_DataInicio").val(), $("#scp_DataFIm").val());

                            //  window.location.replace("SCP_VALIDACAO");
                        });
                    }
                }, delay);
            }
        });
    }

    function form_cn() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaCN" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCPVALIDACAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var linhas = "<option value=\"0\">CN</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#scp_cn").slideDown("fast").html(linhas);

            }
        });
    }

    function form_status() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaStatus" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCPVALIDACAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var linhas = "<option value=\"0\">STATUS</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#scp_status").slideDown("fast").html(linhas);

            }
        });
    }

    function SCProcura(cn, txt, dataInicio, dataFinal, status) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SCPProcura", cn: cn, txt: txt, dataInicio: dataInicio, dataFinal: dataFinal, status: status },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCPVALIDACAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var verifica = eval(dados);

                var classe = "bg-danger rounded font-weight-bold text-white p-2";
                var msg = "<i class='icon-attention-2'></i> Nenhum resultado encontrado.";

                $("#retornoFiltro").text("");
                $("#retornoFiltro").removeClass();

                if (verifica.length === 0) {
                    $("#ListaSCP").slideUp("fast").html("");

                    $("#retornoFiltro").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast");

                } else {
                    var linhas = eval(dados);

                    var lista = "<div class='card border-light mt-2 p-1'>";
                    lista += "<div class='card-header font-weight-bold'>Resultado da busca</div>";
                    lista += "<table class='table table-striped w-auto mt-1'>";
                    lista += "<thead class='thead-dark'>";
                    lista += "<tr>";
                    lista += "<th style='text-align: center' scope='col'>SITE</th>";
                    lista += "<th style='text-align: center' scope='col'>NOME</th>";
                    lista += "<th style='text-align: center' scope='col'>DATA</th>";
                    lista += "<th style='text-align: center' scope='col'>STATUS</th>";
                    lista += "<th style='text-align: center' scope='col'>VER</th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody>";
                    for (var i = 0; i < linhas.length; i++) {

                        var nome = linhas[i].nome.split(" ");

                        lista += "<tr id='linha" + linhas[i].id + "'>";
                        lista += "<td style='text-align: center'>" + linhas[i].site + "</td>";
                        lista += "<td style='text-align: center'>" + nome[0] + " " + nome[1] + "</td>";
                        lista += "<td style='text-align: center'>" + linhas[i].data + "</td>";
                        lista += "<td style='text-align: center'>" + linhas[i].status + "</td>";
                        lista += "<td style='text-align: center'><button value='" + linhas[i].id + "' class='btDetalheLancamento btn btn-sm btn-light border text-muted'><i class='icon-popup text-info'></i> Ver</button>";
                        lista += "</tr>";
                    }
                    lista += "</tbody>";
                    lista += "</div>";
                    lista += "</table>";

                    $("#ListaSCP").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);
                    $("#DivXls").slideDown("fast");
                    lancamentotoDetalhe();

                }
            }
        });
    }

    function lancamentotoDetalhe() {

        $(".btDetalheLancamento").click(function() {

            var id = $(this).attr('value');
            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "SCPDetalhe", id: id },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SCPVALIDACAO', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {

                    oculta();
                    $("#DetalheLancamento").slideUp("fast");

                    $("#SCPId").text(dados.id);
                    $("#SCPAtividade").text(dados.atividade);
                    $("#SCPNome").text(dados.nome);
                    $("#SCPOs").text(dados.os);
                    $("#SCPCN_SITE").text(dados.cn + "/" + dados.site);
                    $("#SCPDataRegistro").text(dados.data + " " + dados.hora);
                    $("#SCPEntrada").text(dados.data1 + " " + dados.hora1);
                    $("#SCPSaida").text(dados.data2 + " " + dados.hora2);
                    $("#SCPJustificativa").text(dados.justificativa);

                    $("#SCPStatus").text(dados.status);

                    if (dados.status != "SOLICITADO") {
                        $("#SCPObs").text(dados.avaliacao);

                        $("#scp_obs").text("");
                        $("#scp_obs").slideUp("fast");


                        $("#bt_validar").attr("disabled", true);
                        $("#bt_invalido").attr("disabled", true);

                    } else {
                        $("#SCPObs").text("PENDENTE");
                        $("#scp_obs").text("");
                        $("#scp_obs").slideDown("fast");
                        $(".rowObs").slideDown("fast");

                        $("#bt_validar").attr("disabled", false);
                        $("#bt_invalido").attr("disabled", false);

                    }
                    $("#DetalheLancamento").modal("show");

                }
            });
        });
    }

    function oculta() {

        $("#ac_formulario_novo").slideUp("fast");
        $("#ac_formulario1").slideUp("fast");
        $("#ListaSCP").slideUp("fast");
        $("#DetalheLancamento").slideUp("fast");
        $("#DivXls").slideUp("fast");
    }
});