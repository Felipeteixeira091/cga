$(document).ready(function () {

    $("#site_formulario1").slideDown("fast");
    form_cn("procura");

    $("#btFiltra").click(function () {

        $("#ModalRetornoSite").text("");
        $("#ModalRetornoSite").removeClass();
        $("#bt_xls").attr("disabled", true);

        var txt = $("#siteTXT").val();
        var cn = $("#siteCn").val();

        $("#ListaSITE").slideUp("fast");
        if (txt === "" && cn === "0") {

            var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
            var ico = "<i class='icon-attention'></i>";
            var msg = " Informações insuficientes."

            $("#ModalRetornoSite").slideDown("fast").addClass(classe).append(ico + msg).delay(1500).slideUp("fast");

        } else {
            SiteProcura(txt, cn);
        }
    });

    $("#btFormNovo").click(function () {

        $("#site_formulario_novo").modal("show");
        form_cn("novo");
        form_uf("novo");
        form_tipo("novo");
        form_bairro("novo");
    });

    $("#site_cn").change(function () {
        var cn = $("#site_cn").val();
        form_cidade(cn, "novo");
    });

    $("#edita_site_cn").change(function () {
        var cn = $("#edita_site_cn").val();
        form_cidade(cn, "edita");
    });

    $("#site_cidade").change(function () {
        var vcidade = $("#site_cidade").val();
        if (vcidade === "N") {
            $("#col_site_cidade_n").removeClass("d-none");
            $("#site_cidade_n").val("");
        } else {
            $("#col_site_cidade_n").addClass("d-none");
            $("#site_cidade_n").val("");
        }
    });
    $("#edita_site_cidade").change(function () {
        var vcidade = $("#edita_site_cidade").val();
        if (vcidade === "N") {
            $("#col_site_cidade_e").removeClass("d-none");
            $("#site_cidade_e").val("");
        } else {
            $("#col_site_cidade_e").addClass("d-none");
            $("#site_cidade_e").val("");
        }
    });


    $("#site_bairro").change(function () {
        var vcidade = $("#site_bairro").val();
        if (vcidade === "N") {
            $("#col_site_bairro_n").removeClass("d-none");
            $("#site_bairro_n").val("");
        } else {
            $("#col_site_bairro_n").addClass("d-none");
            $("#site_bairro_n").val("");
        }
    });
    $("#edita_site_bairro").change(function () {
        var vcidade = $("#edita_site_bairro").val();
        if (vcidade === "N") {
            $("#col_site_bairro_e").removeClass("d-none");
            $("#site_bairro_e").val("");
        } else {
            $("#col_site_bairro_e").addClass("d-none");
            $("#site_bairro_e").val("");
        }
    });

    $("#bt_cadastro_SITE").click(function () {

        cadastro();
    });
    $("#bt_edita_SITE").click(function () {

        edita();
    });

    $("#bt_cadastro_SITE_voltar").click(function () {

        cadastro_SITE_voltar();
    });
    $("#bt_xls").click(function () {

        var cn = $("#siteCn").val();
        var txt = $("#siteTXT").val();

        var href = "SITESC?acao=exportar&cn=" + cn + "&txt=" + txt;

        window.open(href);
    });

    function formEdita() {

        $(".btDetalheSite").click(function () {
            var site = $(this).attr('value');

            site_dados(site);

            $("#site_formulario_edita").modal("show");

        });
    }

    function cadastro_SITE_voltar() {

        $("#site_formulario_novo").slideUp("fast", function () {

            window.location.replace("SITE");
        });
    }

    function form_uf(tipo) {

        $.ajax({
            type: 'get', //Definimos o método HTTP usado
            data: { acao: "ufLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SITESC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">SELECIONE UM UF</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                if (tipo === "novo") {
                    $("#site_uf").slideDown("fast").html(linhas);
                } else {
                    $("#edita_site_uf").slideDown("fast").html(linhas);
                }

            }
        });
    }

    function form_cn(tipo) {

        $.ajax({
            type: 'get', //Definimos o método HTTP usado
            data: { acao: "cnLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SITESC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">SELECIONE UM CN</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                if (tipo === "procura") {
                    $("#siteCn").slideDown("fast").html(linhas);
                } else
                    if (tipo === "novo") {
                        $("#site_cn").slideDown("fast").html(linhas);
                    } else {
                        $("#edita_site_cn").slideDown("fast").html(linhas);
                    }
            }
        });
    }

    function form_cidade(cn, tipo) {

        $.ajax({
            type: 'get', //Definimos o método HTTP usado
            data: { acao: "cidadeLista", cn: cn },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SITESC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">CIDADE</option>";
                linhas += "<option value=\"N\">NÃO CADASTRADA</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                if (tipo === "novo") {
                    $("#site_cidade").slideDown("fast").html(linhas);
                } else {
                    $("#edita_site_cidade").slideDown("fast").html(linhas);
                }
            }
        });
    }

    function form_bairro(tipo) {

        $.ajax({
            type: 'get', //Definimos o método HTTP usado
            data: { acao: "bairroLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SITESC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">SELECIONE UM BAIRRO</option>";
                linhas += "<option value=\"N\">NÃO CADASTRADO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                if (tipo === "novo") {
                    $("#site_bairro").slideDown("fast").html(linhas);
                } else {
                    $("#edita_site_bairro").slideDown("fast").html(linhas);
                }
            }
        });
    }

    function form_tipo(tipo) {

        $.ajax({
            type: 'get', //Definimos o método HTTP usado
            data: { acao: "tipoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SITESC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">SELECIONE UM TIPO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                if (tipo === "novo") {
                    $("#site_tipo").slideDown("fast").html(linhas);
                } else {
                    $("#edita_site_tipo").slideDown("fast").html(linhas);
                }
            }
        });
    }

    function SiteProcura(txt, cn) {

        $.ajax({
            type: 'get', //Definimos o método HTTP usado
            data: { acao: "SITEProcura", txt: txt, cn: cn },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SITESC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var verifica = eval(dados);

                if (verifica.length === 0) {

                    $("#bt_xls").attr("disabled", true);

                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    var ico = "<i class='icon-attention'></i>";
                    var msg = " Nenhuma correspondência encontrada."

                    $("#ModalRetornoSite").slideDown("fast").addClass(classe).append(ico + msg).delay(1500).slideUp("fast");
                } else {

                    $("#bt_xls").attr("disabled", false);
                    var linhas = eval(dados);

                    var lista = "";
                    lista += "<table class='table table-striped w-auto'>";
                    lista += "<thead class='thead-dark'>";
                    lista += "<tr>";
                    lista += "<th scope='col'>SIGLA</th>";
                    lista += "<th scope='col'>DESCRIÇÃO</th>";
                    lista += "<th scope='col'>CN</th>";
                    lista += "<th scope='col'>EDITA</th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody>";
                    for (var i = 0; i < linhas.length; i++) {
                        lista += "<tr id='linha" + linhas[i].id + "'>";
                        lista += "<td>V" + linhas[i].tipo + "-" + linhas[i].sigla + "</td>";
                        lista += "<td>" + linhas[i].descricao + "</td>";
                        lista += "<td>" + linhas[i].cn + "</td>";
                        lista += "<td> <button value='" + linhas[i].id + "' class='btDetalheSite btn btn-outline-info btn-sm'><i class='icon-popup'></i> Ver</button>";
                        lista += "</tr>";
                    }
                    lista += "</tbody>";
                    lista += "</table>";

                    $("#ListaSITE").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);

                    formEdita();
                }
            }
        });
    }

    function cadastro() {

        var sigla = $("#site_sigla").val();
        var descricao = $("#site_descricao").val();
        var tipo = $("#site_tipo").val();
        var cn = $("#site_cn").val();
        var uf = $("#site_uf").val();
        var cidade = $("#site_cidade").val();
        var cidade_n = $("#site_cidade_n").val();
        var bairro = $("#site_bairro").val();
        var bairro_n = $("#site_bairro_n").val();
        var endereco = $("#site_endereco").val();
        var cep = $("#site_cep").val();

        $("#retornoNovoSite").text("");
        $("#retornoNovoSite").removeClass();

        $.ajax({
            type: 'get', //Definimos o método HTTP usado
            data: { acao: "cadastroSITE", sigla: sigla, descricao: descricao, tipo: tipo, cn: cn, uf: uf, cidade: cidade, cidade_n: cidade_n, bairro: bairro, bairro_n: bairro_n, endereco: endereco, cep: cep },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SITESC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                if (dados.erro != "0") {

                    if (dados.erro = "2") {
                        var tempo = 2800;
                    } else {
                        var tempo = 1400;
                    }
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    var ico = "<i class='icon-attention'></i>";

                    $("#retornoNovoSite").slideDown("fast").addClass(classe).append(ico + " " + dados.msg).delay(tempo).slideUp("fast");

                } else {
                    var tempo = 1800;
                    var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";
                    var ico = "<i class='icon-ok-circled-1'></i>";

                    $("#retornoNovoSite").slideDown("fast").addClass(classe).append(ico + " " + dados.msg).delay(tempo).slideUp("fast", function () {
                        window.location.replace("SITE");
                    });
                }
            }
        });
    }

    function edita() {

        var id = $("#site_id").text();
        var sigla = $("#edita_site_sigla").val();
        var descricao = $("#edita_site_descricao").val();
        var tipo = $("#edita_site_tipo").val();
        var cn = $("#edita_site_cn").val();
        var uf = $("#edita_site_uf").val();
        var cidade = $("#edita_site_cidade").val();
        var cidade_n = $("#site_cidade_e").val();
        var bairro = $("#edita_site_bairro").val();
        var bairro_n = $("#site_bairro_e").val();
        var endereco = $("#edita_site_endereco").val();
        var cep = $("#edita_site_cep").val();

        $("#retornoEditaSite").text("");
        $("#retornoEditaSite").removeClass();

        $.ajax({
            type: 'get', //Definimos o método HTTP usado
            data: { acao: "editaSITE", id: id, sigla: sigla, descricao: descricao, tipo: tipo, cn: cn, uf: uf, cidade: cidade, cidade_n: cidade_n, bairro: bairro, bairro_n: bairro_n, endereco: endereco, cep: cep },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SITESC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                if (dados.erro != "0") {

                    if (dados.erro = "2") {
                        var tempo = 2800;
                    } else {
                        var tempo = 1400;
                    }

                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    var ico = "<i class='icon-attention'></i>";

                    $("#retornoEditaSite").slideDown("fast").addClass(classe).append(ico + " " + dados.msg).delay(tempo).slideUp("fast");

                } else {
                    var tempo = 1800;
                    var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";
                    var ico = "<i class='icon-ok-circled-1'></i>";

                    $("#retornoEditaSite").slideDown("fast").addClass(classe).append(ico + " " + dados.msg).delay(tempo).slideUp("fast", function () {
                        window.location.replace("SITE");
                    });
                }
            }
        });
    }

    function site_dados(site) {

        $.ajax({
            type: 'get', //Definimos o método HTTP usado
            data: { acao: "dados", site: site },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SITESC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#site_id").text(dados.id);
                $("#edita_site_sigla").val(dados.sigla);
                $("#edita_site_cep").val(dados.cep);
                $("#edita_site_descricao").val(dados.descricao);
                $("#edita_site_endereco").val(dados.endereco);

                dados_select("uf", dados.uf);
                dados_select("cn", dados.cn);
                dados_select("tipo", dados.tipo);
                dados_select("cidade", dados.cidade, dados.cn);
                dados_select("bairro", dados.bairro, dados.cn);

            }
        });
    }

    function dados_select(tipo, select, p) {

        var acao = tipo + "Lista";
        $.ajax({
            type: 'get', //Definimos o método HTTP usado
            data: { acao: acao, "cn": p },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SITESC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">" + tipo.toUpperCase() + "</option>";
                if (tipo === "cidade") {
                    linhas += "<option value=\"N\">NÃO CADASTRADA</option>";
                } else
                    if (tipo === "bairro") {
                        linhas += "<option value=\"N\">NÃO CADASTRADO</option>";
                    }
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#edita_site_" + tipo).slideDown("fast").html(linhas);

                var opt = "option[value=" + select + "]";
                $("#edita_site_" + tipo).find(opt).attr("selected", "selected");

            }
        });


    }
});