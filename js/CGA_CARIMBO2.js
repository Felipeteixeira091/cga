$(document).ready(function () {
    $("#btn_open_modal_stamp").click(function () {

        form_status();
        tecSearch();
        $("#modal_new_stamp").modal("show");
    });
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
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].descricao + "</option>";
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
                                    lista += "<td class='text-center'><span id='name" + linhas[i].re + "'>" + linhas[i].nome + "</span></td>";
                                    lista += "<td class='text-center'><button class='btn btn-primary btn-sm'><i class='bi bi-person-check-fill'></i> Seleciona</button></td>";

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

                alert(re + name);
                $("#input_name_tec").val(name);
                $("#span_re_tec").text(re);
            });
        });
    }
});