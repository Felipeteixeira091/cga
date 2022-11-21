$(document).ready(function () {
    //  intervalo = setInterval(m, 30000);
    menuCanva();
    var sistema = $("#pgSistema").text();

    menu(sistema);

    function menuCanva() {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "menucanva" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'menu_php', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var sub = eval(dados.sub);
                var menu = eval(dados.menu);
                var sub_m = "";
                for (var i = 0; i < sub.length; i++) {
                    sub_m += '<li class="nav-item dropdown">';
                    sub_m += '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">' + sub[i].sub + '</a >';

                    sub_m += '<ul class="dropdown-menu dropdown-menu-dark">';
                    for (var j = 0; j < menu.length; j++) {
                        if (menu[j].pag_sub === sub[i].sub) {
                            sub_m += '<li><a class="dropdown-item" href="' + menu[j].pag_link + '">' + menu[j].pag_nome + '</a></li>';
                        }
                    }
                    sub_m += '</ul>';
                    sub_m += '</li>';
                }
                $("#menuCanva").slideDown("slow").html(sub_m);
                logOut();
            }
        });
    }
    function menu(sistema) {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "menu", sistema: sistema },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'menu_php', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = eval(dados.menu);
                var usuario = dados.usuario;

                var lista = "<li class='nav-item'><a class='nav-link' href='index'><i class='bi bi-house'></i> INÍCIO</a></li>";
                for (var i = 0; i < linhas.length; i++) {
                    if (linhas[i].link === "ListaSolicitacao") {
                        lista += "<li class='nav-item'><a class='nav-link' href='" + linhas[i].link + "'><span class='' id='notf_pendente'></span> " + linhas[i].nome + "</a></li>";
                    } else {
                        lista += "<li class='nav-item'><a class='nav-link' href='" + linhas[i].link + "'>" + linhas[i].nome + "</a></li>";
                    }
                }
                lista += "<li class='nav-item'><a class='nav-link' href='#'><i class='bi bi-person-circle text-info'></i> : " + usuario.re + " </a></li>";
                lista += "<li class='nav-itembtn float-end'><a class='nav-link' id='btLogOut' href='#'><i class='bi bi-box-arrow-right text-danger'></i> SAIR</a></li>";

                $("#nav").slideDown("slow").html(lista);
                $("#menu-mobile").slideDown("slow").html(lista);
                logOut();
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
                success: function () {
                    window.location.replace("telaLogin");
                }
            });
        });
    }
});