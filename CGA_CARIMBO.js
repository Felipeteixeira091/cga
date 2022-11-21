$(document).ready(function () {
    form_input_os();

    $("#btn_open_modal_stamp").click(function () {

        form_status();
        tecSearch();
        $("#modal_new_stamp").modal("show");
    });
    function form_input_os() {
        $("#input_os").val(0);
        $("#input_select_status").change(function () {

            var status = $("#input_select_status").val();
            if (status != 4) {
                $("#input_os").val(0);
                $("#input_os").attr("disabled", true);
            } else {
                $("#input_os").val("");
                $("#input_os").attr("disabled", false);
            }
        });
    }
    $("#btn_generate").click(function () {


        var data = {
            acao: "generate",
            prediction: $("#input_prediction").val(),
            status: $("#input_select_status").val(),
            status_text: $("#input_select_status :selected").text(),
            os: $("#input_os").val(),
            technician_re: $("#span_tec_re").text(),
            technician_name: $("#span_tec_name").text(),
            note: $("#input_note").val(),
            ga_re: $("#span_ga_re").text(),
            ga_name: $("#span_ga_name").text(),
            responsibility: $("#input_select_responsibility").val(),
            responsibility_text: $("#input_select_responsibility :selected").text()
        }

        stamp_generate(data);
    });

    function stamp_generate(data) {
        $.ajax({
            type: "post",
            url: "srcCarimbo",
            data: data,
            dataType: "json",
            success: function (response) {

                if (response.erro = 1) {

                    var tempo = 1500;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    var msg = "<i class='icon-attention-2'></i> Necessário preencher o campo de previsão.";

                    $("#modal_return").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                } else {
                    $("#output_text").val(response.string);
                }

            }
        });

    }
    function form_status() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "lista_status" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'srcCarimbo', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">SELECIONE UM STATUS</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].descricao.toUpperCase() + "</option>";
                }
                $("#input_select_status").slideDown("fast").html(linhas);
            }
        });
    }
    function tecSearch() {

        $("#btn_search_tec").click(function () {

            var txt = $("#input_name_tec").val();

            $("#tableTec").slideUp("fast", function () {

                if (txt.length === 0) {

                    var tempo = 1500;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    var msg = "<i class='icon-attention-2'></i> Necessário preencher o campo de busca.";

                    $("#modal_return").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                } else {

                    $.ajax({
                        type: 'post', //Definimos o método HTTP usado
                        data: { acao: "searchTec", txt: txt },
                        dataType: 'json', //Definimos o tipo de retorno
                        url: 'srcCarimbo', //Definindo o arquivo onde serão buscados os dados
                        success: function (dados) {

                            if (dados.length === 0) {

                                var tempo = 1900;
                                var classe = "bg-danger rounded fw-bold text-white pt-2 pb-2";
                                var msg = "<i class='bi bi-exclamation-circle-fill'></i> Nenhum colaborador localizado.";
                                $("#modal_return").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");
                            } else {
                                var linhas = eval(dados);

                                var lista = "";
                                lista += "<div class='card border'>";
                                lista += "<div class='card-header text-muted text-center bg-light fw-bold'>Resultado da busca</div>";
                                lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                                lista += "<thead class='bg bg-light text-muted'>";
                                lista += "<tr>";
                                lista += "<th scope='col' class='text-center'>UF</th>";
                                lista += "<th scope='col' class='text-center'>NOME</th>";
                                lista += "<th scope='col' class='text-center'>SELECIONAR</i></th>";
                                lista += "</tr>";
                                lista += "</thead>";
                                lista += "<tbody>";
                                for (var i = 0; i < linhas.length; i++) {

                                    lista += "<tr id='linha" + linhas[i].re + "'>";
                                    lista += "<td class='text-center'>" + linhas[i].uf + "</td>";
                                    lista += "<td class='text-center'><span id='name" + linhas[i].re + "'>" + linhas[i].name + "</span></td>";
                                    lista += "<td class='text-center'><button class='btn_select_tec btn btn-primary btn-sm' value='" + linhas[i].re + "'><i class='bi bi-person-check-fill'></i> Seleciona</button></td>";

                                    lista += "</tr>";
                                }
                                lista += "</tbody>";
                                lista += "</table></div>";
                                $("#tableTec").slideDown("fast").html(lista);
                                selectTec();
                            }
                        }
                    });
                }
            });
        });
    }

    function selectTec() {

        $(".btn_select_tec").click(function () {
            var re = $(this).attr('value');
            var name = $("#name" + re).text();

            $("#tableTec").slideUp("fast", function () {

                $("#span_tec_name").text(name);
                $("#span_tec_re").text(re);
            });
        });
    }
});