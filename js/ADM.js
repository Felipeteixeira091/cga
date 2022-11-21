$(document).ready(function() {

    $("#formulario1").slideDown("fast");

    $("#btFormCartao").click(function() {

        $("#ModalRetornoCartao").text("");
        $("#ModalRetornoCartao").removeClass();

        $("#Modal_cartao").modal("show");
        form_cartaoDesbloqueio();

        cartaoDestinatario();
    });
    $("#cartaoDestAdd").click(function() {
        var nome = $("#destNome").val();
        var email = $("#destEmail").val();
        var tipo = $("#destTipo").val();

        cartaoDestinatarioAdd(nome, email, tipo);

    });
    $("#xlsUsuario").click(function() {

        $("#retornoFiltro").text("");
        $("#retornoFiltro").removeClass();

        var classe = "bg-info rounded text-white font-weight-bold p-2 mt-2";
        var msg = "<i class='icon-download-2'></i> Baixando arquivo, aguarde.";

        $("#retornoFiltro").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

        var href = "ADMXLS?acao=xlsUsuario";

        window.location.href = href;

    });
    $("#xlsEquipamentos").click(function() {

        $("#retornoFiltro").text("");
        $("#retornoFiltro").removeClass();

        var classe = "bg-info rounded text-white font-weight-bold p-2 mt-2";
        var msg = "<i class='icon-download-2'></i> Baixando arquivo, aguarde.";

        $("#retornoFiltro").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

        var href = "ADMXLS?acao=xlsEquipamentos";

        window.location.href = href;

    });
    $("#xlsFrota").click(function() {

        $("#retornoFiltro").text("");
        $("#retornoFiltro").removeClass();

        var classe = "bg-info rounded text-white font-weight-bold p-2 mt-2";
        var msg = "<i class='icon-download-2'></i> Baixando arquivo, aguarde.";

        $("#retornoFiltro").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");


        var href = "SCEXLS?acao=xlsFrota";

        window.location.href = href;

    });
    $("#xlsGas").click(function() {

        $("#retornoFiltro").text("");
        $("#retornoFiltro").removeClass();

        var classe = "bg-info rounded text-white font-weight-bold p-2 mt-2";
        var msg = "<i class='icon-download-2'></i> Baixando arquivo, aguarde.";

        $("#retornoFiltro").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");


        var href = "ADMXLS?acao=xlsGas";

        window.location.href = href;

    });

    function form_cartaoDesbloqueio() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cartaoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'scADM', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.length === 0) {

                    var tempo = 1500;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    var msg = "<i class='icon-attention-2'></i> Nenhum cartão pendente.";

                    $("#ModalRetornoCartao").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                } else {
                    var linhas = eval(dados);

                    var lista = "";
                    lista += "<div class='card border'>";
                    lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                    lista += "<thead class='bg bg-light text-muted'>";
                    lista += "<tr>";
                    lista += "<th scope='col' class='text-center'>CARTÃO</th>";
                    lista += "<th scope='col' class='text-center'>COLABORADOR/EQUIPAMENTO</th>";
                    lista += "<th scope='col' class='text-center'>COORDENADOR</th>";
                    lista += "<th scope='col' class='text-center'>CN</th>";
                    lista += "<th scope='col' class='text-center'>SOLICITAR DESBLOQUEIO <i class='icon-mail'></i></th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody>";
                    for (var i = 0; i < linhas.length; i++) {

                        var nome = "";
                        var coordenador = "";
                        var cn = "";

                        if (linhas[i].finalidade === "1") {
                            nome = linhas[i].colaborador_nome + " [" + linhas[i].colaborador_re + "]";
                            coo = linhas[i].coordenadorU.split(' ');
                            cn = linhas[i].cnU;
                        } else
                        if (linhas[i].finalidade === "2") {
                            nome = linhas[i].gmg;
                            coo = linhas[i].coordenadorG.split(' ');
                            cn = linhas[i].cnG;
                        }


                        lista += "<tr id='linhaCartao" + linhas[i].cartao + "'>";
                        lista += "<td class='text-center'>" + linhas[i].cartao + "</td>";
                        lista += "<td class='text-center'>" + nome + "</td>";
                        lista += "<td class='text-center'>" + coo[0] + "</td>";
                        lista += "<td class='text-center'>" + cn + "</td>";
                        lista += "<td class='text-center'><button class='btCartaoAprov btn btn-info btn-sm mr-1' value='" + linhas[i].cartao + "'><i class='icon-ok-circled2'></i> SIM</button>";
                        lista += "<button class='btCartaoRecus btn btn-danger btn-sm ml-1' value='" + linhas[i].cartao + "'><i class='icon-minus-circle'></i> NÃO</button></td>";
                        lista += "</tr>";
                    }
                    lista += "</tbody>";
                    lista += "</table></div>";
                    $("#cartaoLista").slideDown("fast").html(lista);

                    cartaoDesbloqueia();
                    cartaoNega();
                }
            }
        });
    }

    function cartaoDesbloqueia() {

        $(".btCartaoAprov").click(function() {
            var cartao = $(this).attr('value');

            $("#ModalRetornoCartao").text("");
            $("#ModalRetornoCartao").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: {
                    acao: "cartaoAprov",
                    cartao: cartao
                },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'scADM', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {

                    var msg = "";
                    if (dados.erro === "1") {

                        msg = "<i class='icon-warning'></i> " + dados.msg;

                        var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                        $("#ModalRetornoCartao").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");

                    } else {

                        msg = "<i class='icon-ok-circled2'></i> " + dados.msg;

                        var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                        $("#ModalRetornoCartao").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                            $("#linhaCartao" + cartao).slideUp("fast");
                        });
                    }
                }
            });
        });
    }

    function cartaoNega() {

        $(".btCartaoRecus").click(function() {
            var cartao = $(this).attr('value');

            $("#ModalRetornoCartao").text("");
            $("#ModalRetornoCartao").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "cartaoRecus", cartao: cartao },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'scADM', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {

                    var msg = "";

                    if (dados.erro === "1") {

                        msg = "<i class='icon-attention-2'></i> " + dados.msg;

                        var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                        $("#ModalRetornoTransferencia").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");

                    } else {
                        msg = "<i class='icon-ok-circled2'></i> " + dados.msg;
                        var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                        $("#ModalRetornoCartao").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                            $("#linhaCartao" + cartao).slideUp("fast");
                        });
                    }
                }
            });
        });
    }

    function cartaoDestinatario() {

        $("#notaEnviarDestinatarioLista").text("");
        $("#notaEnviarDestinatarioLista").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cartaoDestinatario" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'scADM', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var verifica = eval(dados);
                var resultados = verifica.length;

                if (resultados === 0) {
                    var classe = "bg-danger rounded text-white font-weight-bold p-2";
                    var msg = "<i class='icon-attention'></i> Nenhuma destinatário encontrado.";

                    $("#notaEnviarDestinatarioLista").slideDown("fast").addClass(classe).append(msg);

                } else {
                    var lista = "";
                    var linhas = eval(dados);

                    for (var i = 0; i < linhas.length; i++) {
                        var tipo = "<span class='badge badge-pill badge-light text-secondary'>" + linhas[i].tipo + "</span>";
                        lista += "<button value=" + linhas[i].id + " class='bt_removeEmail btn btn-light border ml-1 mt-1'><i class='icon-minus-circled text-danger'></i> " + tipo + linhas[i].nome + "<i class='icon-right-2'></i>" + linhas[i].email + "</button>";

                    }
                    $("#notaEnviarDestinatarioLista").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);

                    cartaoDestinatarioRemove();

                }
            }
        });
    }

    function cartaoDestinatarioAdd(nome, email, tipo) {

        $("#ModalRetornoCartao").text("");
        $("#ModalRetornoCartao").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cartaoDestinatarioAdd", nome: nome, email: email, tipo: tipo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'scADM', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2";
                    var msg = dados.msg;

                    $("#ModalRetornoCartao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                } else {
                    var msg = dados.msg;
                    var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#ModalRetornoCartao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        cartaoDestinatario();

                        $("#destNome").val("");
                        $("#destEmail").val("");

                        var opt = "option[value=" + 0 + "]";
                        $("#destTipo" + tipo).find(opt).attr("selected", "selected");
                    });
                }
            }
        });
    }

    function cartaoDestinatarioRemove() {

        $(".bt_removeEmail").click(function() {
            var id = $(this).attr('value');
            $("#ModalRetornoCartao").text("");
            $("#ModalRetornoCartao").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "cartaoDestinatarioRemove", id: id },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'scADM', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {
                    if (dados.erro === "1") {

                        var classe = "bg-danger rounded text-white font-weight-bold p-2";
                        var msg = dados.msg;

                        $("#ModalRetornoCartao").slideDown("fast").addClass(classe).append(msg);
                    } else {
                        var msg = dados.msg;
                        var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                        $("#ModalRetornoCartao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                            cartaoDestinatario();

                        });
                    }
                }
            });
        });
    }
});