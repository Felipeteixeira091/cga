$(document).ready(function() {

    $("#btLogin").click(function() {
        login();
    });

    function login() {

        var re = $("#re").val();
        var senha = $("#senha").val();

        $("#retornoLogin").text("");
        $("#retornoLogin").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { re: re, senha: senha },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'processaLogin', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                //     $("#formLogin").slideUp("fast", function () {
                $("#spin").fadeIn("slow").delay(700).fadeOut("slow", function() {
                    if (dados.erro === "1") {

                        var msg = "<i class='icon-attention'></i> " + dados.msg;
                        var classe = "bg-danger bg-gradient rounded font-weight-bold text-white pt-2 pb-2";
                        $("#retornoLogin").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                            $("#formLogin").slideDown("fast");
                        });
                    } else {
                        $("#login_corpo").slideUp("fast");
                        $("#email").val("");
                        $("#senha").val("");

                        var msg = "<i class='icon-lock-open'></i> " + dados.msg;
                        var classe = "bg-success bg-gradient rounded font-weight-bold text-white pt-2 pb-2";
                        $("#retornoLogin").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {
                            window.location.replace("index");
                        });
                    }
                });
            }
        });
    }
});