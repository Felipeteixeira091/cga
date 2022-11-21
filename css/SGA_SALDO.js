$(document).ready(function() {

    Elemento_lista();


    $("#btModalSite").click(function() {
        $("#pesquisaSITE").modal("show");

    });
    $("#btModalBaixa").click(function() {

        $("#modalBaixa").modal("show");

        verifica();
        form_tipo();
        SiteProcura();

    });

    function verifica() {

        var opt = "option[value=" + 0 + "]";
        $("#atividade").find(opt).attr("selected", "selected");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "verifica" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var ativo = dados.ativo;

                if (ativo === "nd") {


                } else {

                    var sga = dados.sga;


                    $("#id_sga").text(sga.id);
                    $("#vfbSite").text(sga.id_site);
                    $("#textoSite").text(sga.site);
                    $("#os").val(sga.os);

                    var opt = "option[value=" + 1 + "]";
                    $("#atividade").find(opt).attr("selected", "selected");
                }
            }
        });
    }

    function Elemento_lista() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaElemento" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var linhas = eval(dados);

                var lista = "<div class='bg bg-dark rounded font-weight-bold text-white mb-2'>Ítens disponíveis para baixa</div>";

                lista += "<div id='elementoAtivo' class='row'>";

                for (var i = 0; i < linhas.length; i++) {

                    lista += "<div class='col'>";
                    lista += "<div class='card bg-light mb-2' style='max-width: 18rem;'>";
                    lista += "<div class='card-header'><span class='text-muted font-weight-bold' id='pa_" + linhas[i].id + "'>" + linhas[i].sgaTipo + "</span></div>";
                    lista += "<div class='card-body'>";
                    lista += "<ul class='list-group list'>";

                    var saldo = linhas[i].qtd - linhas[i].baixado;
                    lista += "<li id='eListaEstrutura_" + linhas[i].id + "' class='list-group-item text-left'><i class='" + linhas[i].ico + "'></i><span id='descPA_id" + linhas[i].id + "'>" + linhas[i].sgaDescricao + "</span></li>";
                    lista += "<li class='list-group-item text-left'><span class='badge badge-light text-muted border'>SOLICITADO: <span id='qtd_" + linhas[i].id + "'>" + linhas[i].qtd + "</span></span></b></li>";
                    lista += "<li class='list-group-item text-left'><span class='badge badge-light text-muted border'>PRÉ-BAIXA: <span id='qtdPB_" + linhas[i].id + "'>" + linhas[i].pbaixa + "</span></span></b></li>";
                    lista += "<li class='list-group-item text-left'><span class='badge badge-light text-muted border'>BAIXADO: <span id='qtdB_" + linhas[i].id + "'>" + linhas[i].baixado + "</span></span></b></li>";
                    lista += "<li class='list-group-item text-left'><span class='badge badge-light text-muted border'>SALDO: <span id='qtdS_" + linhas[i].id + "'>" + saldo + "</span></span></b></li>";
                    lista += "</ul>";
                    lista += "</div>";
                    if (saldo < 1) {
                        lista += "<div class='elementoComum card-footer text-muted'><button disabled id='btBaixa_" + linhas[i].id + "' value='" + linhas[i].id + "' class='bt_Modal_baixa btn btn-light btn-sm border text-muted'><i class='icon-down text-danger'></i> Baixar</button></div>";
                    } else {
                        lista += "<div class='elementoComum card-footer text-muted'><button id='btBaixa_" + linhas[i].id + "' value='" + linhas[i].id + "' class='bt_Modal_baixa btn btn-light btn-sm border text-muted'><i class='icon-down text-danger'></i> Baixar</button></div>";
                    }


                    lista += "</div>";
                    lista += "</div>";

                }

                lista += "</div>";
                $("#listaSaldo").slideDown("slow").html(lista);

                // modal_baixa();
            }
        });
    }

    $("#btCria").click(function() {

        var site = $("#vfbSite").text();
        var tipo = $("#atividade").val();
        var os = $("#os").val();

        Cria(site, tipo, os);

    });
    $("#btBaixa").click(function() {

        var site = $("#vfbSite").text();
        var pa = $("#IDPA").text();
        var qtdBaixa = $("#qtdBaixa").val();
        var qtdPendente = $("#qtdPendente").text();
        var atividade = $("#atividade").val();
        var os = $("#os").val();

        Baixar(site, pa, qtdBaixa, qtdPendente, atividade, os);

    });

    function form_tipo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "tipoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = "<option value=\"0\">SELECIONE UM ÍTEM</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#tipoDescarte").slideDown("fast").html(linhas);
            }
        });
    }

    function SiteProcura() {

        $("#btProcuraSite").click(function() {

            $("#formDadosSite").addClass("d-none");

            var txt = $("#formSite").val();

            $("#listaSite").slideUp("fast", function() {

                $("#retornoSite").text("");
                $("#retornoSite").removeClass();

                if (txt.length === 0) {

                    var tempo = 1500;
                    var classe = "bg-danger rounded font-weight-bold text-white p-2";
                    var msg = "<i class='icon-attention-2'></i> Necessário preencher o campo de busca.";

                    $("#retornoSite").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                } else {
                    $.ajax({
                        type: 'post', //Definimos o método HTTP usado
                        data: { acao: "SiteProcura", txt: txt },
                        dataType: 'json', //Definimos o tipo de retorno
                        url: 'EXTCADASTRO', //Definindo o arquivo onde serão buscados os dados
                        success: function(dados) {

                            if (dados.length === 0) {

                                var tempo = 1500;
                                var classe = "bg-danger rounded font-weight-bold text-white p-2";
                                var msg = "<i class='icon-attention-2'></i> Site não cadastrado.";

                                $("#retornoSite").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                            } else {
                                var linhas = eval(dados);

                                var lista = "";
                                lista += "<div class='card border'>";
                                lista += "<div class='card-header text-muted bg-light font-weight-bold'>Resultado da busca</div>";
                                lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                                lista += "<thead class='bg bg-light text-muted'>";
                                lista += "<tr>";
                                lista += "<th scope='col' class='text-center'>UF</th>";
                                lista += "<th scope='col' class='text-center'>SIGLA</th>";
                                lista += "<th scope='col' class='d-md-none text-center'>CIDADE <i class='icon-location'></i></th>";
                                lista += "<th scope='col' class='d-md-none text-center'>DESCRIÇÃO <i class='icon-doc-text'></i></th>";
                                lista += "<th scope='col' class='text-center'>SELECIONE <i class='icon-target-2'></i></th>";
                                lista += "</tr>";
                                lista += "</thead>";
                                lista += "<tbody>";
                                for (var i = 0; i < linhas.length; i++) {

                                    var tipo = linhas[i].tipo;
                                    var cidade = linhas[i].cidade; //doDestacaTexto(linhas[i].cidade, txt);
                                    var sigla = linhas[i].sigla; //doDestacaTexto(linhas[i].sigla, txt);
                                    var descricao = linhas[i].descricao; //doDestacaTexto(linhas[i].descricao, txt);


                                    if (tipo === "ADM") {
                                        tipo = "ADM";
                                    } else {
                                        tipo = "V - " + tipo.split(' ')[1];
                                    }

                                    lista += "<tr id='linha" + linhas[i].id + "'>";
                                    lista += "<td class='text-center'>" + linhas[i].uf + "</td>";
                                    lista += "<td class='text-center'>" + tipo + " - " + sigla + "</td>";
                                    lista += "<td class='d-md-none text-center'>" + cidade + "</td>";
                                    lista += "<td class='d-md-none text-center '>" + descricao + "</td>";
                                    lista += "<td class='text-center'><button class='btSelecionaSite btn btn-light btn-sm border' value='" + linhas[i].id + "'>Seleciona <i class='icon-target-2'></i></button></td>";
                                    lista += "</tr>";
                                }
                                lista += "</tbody>";
                                lista += "</table></div>";
                                $("#listaSite").slideDown("fast").html(lista);

                                SelecionaSite();
                            }
                        }
                    });
                }
            });
        });
    }

    function SelecionaSite() {

        $(".btSelecionaSite").click(function() {
            var site = $(this).attr('value');

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "selecionaSite", site: site },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'EXTCADASTRO', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {

                    //  $("#formProcuraSite").slideUp("fast");
                    $("#pesquisaSITE").modal("hide");

                    $("#textoSite").text("");
                    $("#vfbSite").text("");

                    $("#rowSite").slideDown("fast", function() {
                        $("#textoSite").append("").append(dados.tipo + " " + dados.sigla);
                        $("#vfbSite").append("").append(dados.id);
                    });
                }
            });
        });
    }

    function Cria(site, tipo, os) {

        $("#retornoSolicitacao").slideUp("fast").text("");
        $("#retornoSolicitacao").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cria", site: site, tipo: tipo, os: os },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";
                var msg = dados.msg;
                if (dados.erro === "1") {
                    $("#retornoSolicitacao").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function() {

                    });
                } else {
                    msg = "<i class='icon-ok-1'></i> " + msg;
                    classe = "bg bg-success rounded text-white font-weight-bold p-2 mt-2";
                    $("#retornoSolicitacao").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function() {

                        window.location.replace("SGA_SALDO");
                    });
                }
            }
        });
    }

    function Baixar(site, pa, qtdBaixa, qtdPendente, atividade, os) {

        $("#retornoSolicitacao").slideUp("fast").text("");
        $("#retornoSolicitacao").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "baixa", site: site, pa: pa, qtdBaixa: qtdBaixa, qtdPendente: qtdPendente, atividade: atividade, os: os },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGASALDO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";
                var msg = dados.msg;
                if (dados.erro === "1") {
                    $("#retornoSolicitacao").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function() {

                    });
                } else {
                    msg = "<i class='icon-ok-1'></i> " + msg;
                    classe = "bg bg-success rounded text-white font-weight-bold p-2 mt-2";
                    $("#retornoSolicitacao").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function() {

                        window.location.replace("SGA_SALDO");
                    });
                }
            }
        });
    }
});