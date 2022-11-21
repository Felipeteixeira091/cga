$(document).ready(function () {
    $("#btn_open_modal_stamp").click(function () {

        form_status();
        SiteProcura();
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
    function SiteProcura() {

        $("#btn_search_tec").click(function () {

            var txt = $("#input_name_tec").val();

            $("#tableTec").slideUp("fast", function () {

                if (txt.length < 1) {

                    var tempo = 1500;
                    var classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    var msg = "<i class='icon-attention-2'></i> Necessário preencher o campo de busca.";

                    $("#modal_return").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                } else {

                    $.ajax({
                        type: 'post', //Definimos o método HTTP usado
                        data: { acao: "searchTec", txt: txt },
                        dataType: 'json', //Definimos o tipo de retorno
                        url: 'srcCarimbo', //Definindo o arquivo onde serão buscados os dados
                        success: function (dados) {
                            if (dados.length < 1) {

                                msg = "<i class='icon-attention'></i> Nenhum site encontrado.";
                                classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";

                                $("#modal_return").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast");

                            } else {
                                var linhas = eval(dados);

                                var lista = "";
                                lista += "<div class='card border'>";
                                lista += "<div class='card-header text-white bg-dark font-weight-bold'>Resultado da busca</div>";
                                lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                                lista += "<thead class='bg bg-dark text-white'>";
                                lista += "<tr>";
                                lista += "<th scope='col' class='text-center'>UF</th>";
                                lista += "<th scope='col' class='text-center'>NOME</th>";
                                lista += "<th scope='col' class='text-center'>SELECIONE <i class='icon-target-2'></i></th>";
                                lista += "</tr>";
                                lista += "</thead>";
                                lista += "<tbody>";
                                for (var i = 0; i < linhas.length; i++) {

                                    lista += "<tr id='linha" + linhas[i].re + "'>";
                                    lista += "<td class='text-center'>" + linhas[i].uf + "</td>";
                                    lista += "<td class='text-center'>" + linhas[i].name + "</td>";
                                    lista += "<td class='text-center'><button class='btSelecionaSite btn btn-secondary btn-sm' value='" + linhas[i].re + "'>Seleciona <i class='icon-target-2'></i></button></td>";
                                    lista += "</tr>";
                                }
                                lista += "</tbody>";
                                lista += "</table></div>";
                                $("#tableTec").slideDown("fast").html(lista);

                                //Chama função para Add item

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