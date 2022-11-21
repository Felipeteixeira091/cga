$(document).ready(function () {


    var tempo_ms = 30000;
    intervalo = setInterval(m, tempo_ms);

    function m() {
        // /    sol_pendente();
        //   sol_enviar();
    }

    $("input[name='opcAt']").each(function () {
        if ($(this).val() !== 0) {
            $(this).prop("checked", false);
            $(".opcAt").removeClass("active");
        } else {
            $(this).prop("checked", true);
            $(".opcAt").addClass("active");
        }
    });

    menu();


    logOut();

    function href() {

        $(".bt_link").click(function () {

            var link = $(this).attr('value');

            window.location.href = link;
        });
    }
    $("#btFormDados").click(function () {
        $("#modalDados").modal("show");

        meus_dados();

    });
    $("#btTutorial").click(function () {
        $("#modalTutorial").modal("show");
    });

    $("#btlinktr").click(function () {

        $("#linktr").modal("show");

    });

    $("#btFormSenha").click(function () {

        $("#modalAlteraSenha").modal("show");
    });

    $("#btAlteraSenha").click(function () {

        var senha1 = $("#senha1").val();
        var senha2 = $("#senha2").val();
        var tipo = $("input[name='opcAt']:checked").val();

        if (!tipo) {
            tipo = "tipo";
        } else {
            tipo = $("input[name='opcAt']:checked").val();
        }
        trocaSenha(senha1, senha2, tipo);
    });
    $("#btFormSenha").click(function () {

        $("#modalAlteraSenha").modal("show");
    });

    function trocaSenha(senha1, senha2, tipo) {

        $("#retornoTrocaSenha").text("");
        $("#retornoTrocaSenha").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "trocaSenha", senha1: senha1, senha2: senha2, tipo: tipo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'dadosAltera', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    var msg = "<i class='icon-attention-2'></i> " + dados.msg;
                    $("#retornoTrocaSenha").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";
                    var msg = "<i class='icon-ok-circle'></i> " + dados.msg;
                    $("#retornoTrocaSenha").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        $("#senha1").val("");
                        $("#senha2").val("");

                        $("#modalAlteraSenha").modal("hide");

                    });
                }
            }
        });
    }

    function menu() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "menu" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'menu_php2', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = eval(dados);

                var menu = "";
                for (var i = 0; i < linhas.length; i++) {

                    menu += "<div class='col'>";
                    menu += "<div class='card bg-light mb-2' style='width: 18rem'>";
                    menu += "<div class='card-header'><span class='text-muted font-weight-bold'>" + linhas[i].sub + "</span></div>";

                    menu += "<div class='card-body'>";
                    menu += "<span id='desc_" + linhas[i].sub + "'>" + linhas[i].descricao + "</span>";
                    menu += "</div>";

                    menu += "<div class='card-footer text-muted'><button class='bt_acesso btn btn-sm btn-light border ms-1 mt-1 text-muted' value='" + linhas[i].sub + "'><i class='bi bi-view-list'></i> Lista opções</button></div>";

                    menu += "</div>";
                    menu += "</div>";
                    menu += "</div>";

                    //menu += "<button class='bt_acesso btn btn-sm btn-light border ml-1 mt-1 text-muted' value='" + linhas[i].sub + "'>" + linhas[i].sub + "</button>";

                }

                $("#acesso").html(menu);
                $(".bt_acesso").click(function () {

                    var sub = $(this).attr('value');

                    subMenu(sub);
                    $("#modalSub").modal("show");
                });
            }
        });
    }
    function subMenu(sub) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "sub", sub: sub },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'menu_php2', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = eval(dados);
                var menu = "";
                for (var i = 0; i < linhas.length; i++) {

                    menu += "<button class='bt_link btn btn-sm btn-light border ms-1 mt-1 text-muted' value='" + linhas[i].link + "'>" + linhas[i].nome + "</button>";

                }
                $("#ModalTitulo").text(sub);
                var desc = $("#desc_" + sub).text();
                $("#ModalDesc").text(desc);
                $("#subMenu").html(menu);
                href();
            }
        });
    }

    function meus_dados() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "meus_dados" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'indexSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var telefone = dados.telefone;
                telefone = "(" + telefone[0] + telefone[1] + ") " + telefone[2] + " " + telefone[3] + telefone[4] + telefone[5] + telefone[6] + "-" + telefone[7] + telefone[8] + telefone[9] + telefone[10];

                $("#dadosUF").text(dados.uf);
                $("#dadosCN").text(dados.cn);
                $("#dadosRE").text(dados.re);
                $("#dadosNOME").text(dados.nome);
                $("#dadosEMAIL").text(dados.email);
                $("#dadosCARTAO").text(dados.cartao);
                if (dados.placa === null) {
                    $("#dadosVEICULO").text("S/VEÍCULO");
                } else {
                    $("#dadosVEICULO").text(dados.placa + " - " + dados.vMarca + " " + dados.vModelo);
                }

                $("#dadosTELFONE").text(telefone);
                $("#dadosCARGO").text(dados.cargo);
                $("#dadosCOORDENADOR").text(dados.coordenador);
            }
        });
    }

    function logOut() {
        $("#btLogOut").click(function () {

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "logOut" },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'logOut', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    var classe = "";
                    var msg = "";
                    if (dados.erro === "1") {
                        classe = "bg bg-danger rounded text-white font-weight-bold p-2";
                        msg = dados.msg;
                        $("#retornoIndex").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1900).slideUp("fast");
                    } else if (dados.erro === "0") {

                        classe = "bg bg-dark border rounded text-muted font-weight-bold p-2";
                        msg = "<i class='icon-lock-2 text-danger'></i> " + dados.msg;

                        $("#acesso").slideUp("fast", function () {
                            $("#retornoIndex").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function () {

                                window.location.href = "telaLogin";

                            });
                        });

                    }
                }
            });
        });
    }
});