$(document).ready(function () {

    dadosLogin();

    function dadosLogin() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "dados" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'verificaLogin', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    $("#dadosUsuario").slideUp("fast");
                    $("#dadosUsuario span").html("");
                    $("#formAlerasenha").slideUp("fast");

                    $("#sucesso").slideDown("fast").html("Carregando...").delay(800).slideUp("slow", function () {

                        //$("#comunicado_titulo").slideDown("fast");
                        //$("#comunicado_texto").slideDown("fast");
                        $("#dadosUsuario").slideDown("fast");

                        $("#uRe").text(dados.re);
                        $("#uNome").text(dados.nome);
                        $("#uEmail").text(dados.email);
                        $("#uTelefone").text(dados.telefone);
                        $("#uCargo").text(dados.cargo);
                        $("#uSupervisor").text(dados.supervisor);
                        $("#uEndereco").text(dados.endereco);

                        form_altera_senha();
                        form_altera_senha_outlook();
                        altera_senha();
                        altera_senha_outlook();
                    });
                }
            }
        });
    }

    function form_altera_senha() {
        $("#bt_form_altera_senha").click(function () {
            $("#dadosUsuario").slideUp("fast");
            $("#formAlerasenha").slideDown("fast");
        });
    }
    function form_altera_senha_outlook() {
        $("#bt_form_altera_senha_outlook").click(function () {
            $("#dadosUsuario").slideUp("fast");
            $("#formAlerasenhaOutlook").slideDown("fast");
        });
    }
    function altera_senha() {
        $("#btAlteraSenha").click(function () {
            $("#formAlerasenha").slideUp("fast");
            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "altera_senha", senha: $("#AlteraSenha").val(), senhaC: $("#AlteraSenhaC").val() },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'dadosAltera', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    if (dados.erro === "1") {
                        $("#erro").empty();
                        $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast", function () {
                            $("#formAlerasenha").slideDown("fast");
                        });
                    } else {
                        $("#sucesso").slideDown("fast").html(dados.msg).delay(800).slideUp("slow", function () {
                            window.location.replace("index");
                        });
                    }
                }
            });
        });
    }
    function altera_senha_outlook() {
        $("#btAlteraSenhaOutlook").click(function () {
            $("#formAlerasenhaOutlook").slideUp("fast");
            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "altera_senha_outlook", senha: $("#AlteraSenhaOutlook").val(), senhaC: $("#AlteraSenhaCOutlook").val() },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'dadosAltera', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {
                    if (dados.erro === "1") {
                        $("#erro").empty();
                        $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                    } else {
                        $("#sucesso").slideDown("fast").html(dados.msg).delay(800).slideUp("slow", function () {
                            window.location.replace("index");
                        });
                    }
                }
            });
        });
    }
}
);
