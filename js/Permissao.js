$(document).ready(function() {

    form_usuario();
    form_tipo();


    $("#formPermissaoPagina").change(function() {

        var p = $("#formPermissaoPagina").val();

        if (p > 0) {
            $("#div_bt_add").slideDown("fast");
        } 
    });
    $("#formPermissaoFuncao").change(function() {

        var p = $("#formPermissaoFuncao").val();

        if (p > 0) {
            $("#div_bt_add").slideDown("fast");
        }
    });
    $("#formPermissaoTipo").change(function() {

        var p = $("#formPermissaoTipo").val();

        if (p === "1") {

            $("#formPermissaoFuncao").slideUp("fast", function() {
                $("#formPermissaoPagina").slideDown("fast");
                form_pagina();
            });

        } else {
            $("#formPermissaoPagina").slideUp("fast", function() {
                $("#formPermissaoFuncao").slideDown("fast");
                form_funcao();
            });

        }
    });

    $("#formPermissaoUsuario").change(function() {

        var u = $("#formPermissaoUsuario").val();

        form_permissao(u);
    });

    $("#formPermissaoADD").click(function() {
        var tipo = $("#formPermissaoTipo").val();
        var funcao = $("#formPermissaoFuncao").val();
        var pagina = $("#formPermissaoPagina").val();
        var usuario = $("#formPermissaoUsuario").val();


        form_add_permissao(tipo, funcao, pagina, usuario);

    });

    function form_permissao(u) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "PermissaoLista", u: u },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PermissaoSC', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {
                    $("#erro").empty();
                    $("#erro").slideDown("fast").append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = "";
                    var linha = eval(dados);
                    for (var i = 0; i < linha.length; i++) {
                        var nome = "";
                        var tipo = linha[i].tipo;

                        if (tipo === "Pagina") {
                            nome = "<i class='icon-doc-1 text-primary'></i> [<b>" + linha[i].subP + "</b>] " + linha[i].pagina;
                        } else {
                            nome = "<i class='icon-cog text-primary'></i> [<b>" + linha[i].subF + "</b>] " + linha[i].funcao;
                        }

                        linhas += "<button value=" + linha[i].id + " class='bt_removePermissao btn btn-light border ml-1 mt-1 text-muted'><i class='icon-trash-4 text-danger'></i> " + nome + "</button>";
                    }
                    $("#Permissoes").slideDown("fast").html(linhas);
                }
                form_remove_permissao();
            }
        });
    }

    function form_usuario() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "PermissaoListaUsuario" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PermissaoSC', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var linhas = "<option value=\"0\">SELECIONE UM COLABORADOR</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].re + "\">" + linha[i].nome + " " + linha[i].cn + "</option>";
                }
                $("#formPermissaoUsuario").slideDown("fast").html(linhas);
            }
        });
    }

    function form_tipo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "PermissaoListaTipo" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PermissaoSC', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var linhas = "<option value=\"0\">SELECIONE UM TIPO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#formPermissaoTipo").slideDown("fast").html(linhas);
            }
        });
    }

    function form_pagina() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "PermissaoListaPagina" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PermissaoSC', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = "<option value=\"0\">SELECIONE UMA PAGINA</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].sub + "-" + linha[i].nome + "</option>";
                }
                $("#formPermissaoPagina").slideDown("fast").html(linhas);
            }
        });
    }

    function form_funcao() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "PermissaoListaFuncao" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PermissaoSC', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = "<option value=\"0\">SELECIONE UMA FUNCIONADLIDADE</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].sub + "-" + linha[i].nome + "</option>";
                }
                $("#formPermissaoFuncao").slideDown("fast").html(linhas);

            }
        });
    }

    function form_add_permissao(tipo, funcao, pagina, usuario) {

        $("#retornoPermissao").text("");
        $("#retornoPermissao").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "PermissaoAdd", t: tipo, f: funcao, p: pagina, u: usuario },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PermissaoSC', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {
                    var msg = dados.msg;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoPermissao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");
                } else {
                    var msg = dados.msg;
                    var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoPermissao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        var u = $("#formPermissaoUsuario").val();
                        form_permissao(u);
                    });
                }
            }
        });

    }

    function form_remove_permissao() {

        $(".bt_removePermissao").click(function() {

            $("#retornoPermissao").text("");
            $("#retornoPermissao").removeClass();

            var permissao = $(this).attr('value');

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "PermissaoRemove", permissao: permissao },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'PermissaoSC', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {
                    if (dados.erro === "1") {
                        var msg = dados.msg;
                        var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                        $("#retornoPermissao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");
                    } else {
                        var msg = dados.msg;
                        var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoPermissao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                            var u = $("#formPermissaoUsuario").val();
                            form_permissao(u);
                        });
                    }
                }
            });
        });
    }


});