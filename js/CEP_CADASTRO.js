$(document).ready(function() {

    solicitacaoVerifica();
    dataCadastro();

    function dataCadastro() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "dataCadastro" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var msg = "<i class='icon-attention-2'></i> Data limite para solicitações de novos elementos " + dados.data + ".";
                $("#dataLimite").html(msg);
            }
        });
    }


    $("#formCancela").click(function() {
        var solicitacao = $("#NumeroSolicitacao").text();
        CancelaSolicitacao(solicitacao);
    });
    $("#formCriaSolicitacao").click(function() {
        var site = $("#spanIdSite").text();
        CriaSolicitacao(site);
    });
    $("#formElementoTipo").change(function() {
        var tipo = $("#formElementoTipo").val();

        form_elemento(tipo);
    });

    $("#formNovoElemento").click(function() {

        $("#Modal").modal("show");

        var pai = $("#spanIdPai").text();
        var site = $("#spanSigla").text();
        var id_site = $("#spanIdSite").text();

        $("#addElementoSite").text(site);
        $("#addElementoPai").text(pai);
        $("#addElementoIdSite").text(id_site);

        form_estrutura();
        form_tipo();

        $("#formEstrutura").change(function() {
            var estr = $("#formEstrutura").val();
            if (estr === "2") {

                $("#formNgabinete").addClass("alert-danger");
            } else {
                $("#formNgabinete").removeClass("alert-danger");
            }
        });
        $("#formElemento").change(function() {
            var ele = $("#formElemento").val();

            if (ele > 0) {

                $("#formNelemento").addClass("alert-danger text-danger");
            } else {
                $("#formNelemento").removeClass("alert-danger text-danger");
            }

            if (ele === "23") {

                $("#formObs").addClass("alert-info border border-primary");
            } else {
                $("#formObs").removeClass("alert-info border border-primary");
            }

            if (ele === "2" || ele === "32") {

                $("#formNFCC").val("");
                $("#col_fcc").show("fast");

                $("#formNFCC").addClass("alert-danger text-danger");

            } else {

                $("#col_fcc").hide("fast");
                $("#formNFCC").val("");

            }

        });
    });
    $("#formConcluiSolicitacao").click(function() {

        $("#ModalConcluiCadastro").modal("show");

        var pai = $("#spanIdPai").text();
        var id_geral = $("#spanIdPai").text();
        var site = $("#spanSigla").text();
        var qtd = $("#formConcluiSolicitacao").val();

        $("#concluiElementoPai").text(site);
        $("#concluiElementoIDG").text(pai);
        $("#concluiQtdElementos").text(qtd);

        if ($("#concluiQtdElementos").text() === "0") {

            $("#formConcluirCadastro").addClass("d-none");
            $("#ModalRetornoConclui").addClass("text-danger font-weight-bold");
            $("#ModalRetornoConclui").text("Essa solicitação não pode ser concluída, nenhum elemento inserido.");

        } else {
            $("#ModalRetornoConclui").removeClass("text-danger font-weight-bold").text("");
            $("#formConcluirCadastro").removeClass("d-none");
        }
        var tl = "";
        for (var i = 1; i < parseInt(qtd) + 1; i++) {

            var ativo = $("#eLAtivo" + i).text();
            // var ativo_pai = $("#eLAtivoP" + i).text();

            tl += "<div class='row border rounded m-1 bg-light'>";
            tl += "<span class='badge border-right bg-secondary text-white'>" + i + "</span><div class='col text-left'>" + ativo + "</div>";
            tl += "</div>";
        }
        tl += "";

        $("#concluiElementos").html(tl);

    });
    $("#formCancelaSolicitacao").click(function() {

        $("#ModalCancelaCadastro").modal("show");

        var pai = $("#spanIdPai").text();
        var id_geral = $("#spanIdPai").text();
        var site = $("#spanSigla").text();
        var qtd = $("#formConcluiSolicitacao").val();

        $("#cancelaElementoPai").text(site);
        $("#cancelaElementoIDG").text(pai);
        $("#cancelaQtdElementos").text(qtd);

        var tl = "";
        for (var i = 1; i < parseInt(qtd) + 1; i++) {

            var ativo = $("#eLAtivo" + i).text();
            var ativo_pai = $("#eLAtivoP" + i).text();

            tl += "<div class='row border rounded m-1 bg-light'>";
            tl += "<span class='badge border-right bg-secondary text-white'>" + i + "</span><div class='col text-left'>" + ativo + "</div>";
            tl += "</div>";
        }
        tl += "";

        $("#cancelaElementos").html(tl);

    });
    $("#formAddElemento").click(function() {

        var elemento = new Array();

        elemento = {

            pai: $("#addElementoPai").text(),
            site: $("#addElementoIdSite").text(),
            estrutura: $("#formEstrutura").val(),
            Ngabinete: $("#formNgabinete").val(),
            TipoElemento: $("#formElementoTipo").val(),
            elemento: $("#formElemento").val(),
            Nelemento: $("#formNelemento").val(),
            NFcc: $("#formNFCC").val(),
            obs: $("#formObs").val()
        }

        add_elemento(elemento);
    });
    $("#formExcluiElemento").click(function() {

        var elemento = $("#excluiElementoIDG").text();

        deleta_elemento(elemento);
    });
    $("#formConcluirCadastro").click(function() {

        var pai = $("#concluiElementoIDG").text();

        conclui_elemento(pai);
    });
    $("#formCancelarCadastro").click(function() {

        var pai = $("#cancelaElementoIDG").text();

        cancela_cadastro(pai);
    });

    function solicitacaoVerifica() {

        $("#pa_formulario1").slideUp("fast");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoVerifica" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.ativa === "nd") {
                    $("#pa_formulario1").slideDown("fast");

                    $("#formDadosSite").addClass("d-none");
                    $("#formProcuraSite").removeClass("d-none");

                    SiteProcura();
                } else {
                    $("#pa_formulario1").slideDown("fast");
                    $("#formDadosSite").removeClass("d-none");
                    $("#formProcuraSite").addClass("d-none");
                    $("#formCriaSolicitacao").addClass("d-none");

                    $("#spanIdPai").text(dados.id)
                    $("#spanIdSite").text(dados.idSite);
                    $("#spanSigla").text(dados.tipo + " - " + dados.sigla);
                    $("#spanNome").text(dados.descricao);
                    $("#spanEndereco").text(dados.endereco);
                    $("#formBotoes").removeClass("d-none");
                    $("#spanid").removeClass("d-none");

                    Elemento_lista();
                }
            }
        });
    }

    function SiteProcura() {

        $("#btProcuraSite").click(function() {


            $("#formDadosSite").addClass("d-none");

            var txt = $("#formSigla").val();

            $("#listaSite").slideUp("fast", function() {

                $("#retornoNovoElemento").text("");
                $("#retornoNovoElemento").removeClass();

                if (txt.length < 1) {

                    var tempo = 1500;
                    var classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    var msg = "<i class='icon-attention-2'></i> Necessário preencher o campo de busca.";

                    $("#retornoNovoElemento").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                } else {

                    $.ajax({
                        type: 'post', //Definimos o método HTTP usado
                        data: { acao: "SiteProcura", txt: txt },
                        dataType: 'json', //Definimos o tipo de retorno
                        url: 'CEPCADASTRO', //Definindo o arquivo onde serão buscados os dados
                        success: function(dados) {
                            if (dados.length < 1) {

                                msg = "<i class='icon-attention'></i> Nenhum site encontrado.";
                                classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";

                                $("#retornoNovoElemento").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast");

                            } else {
                                var linhas = eval(dados);

                                var lista = "";
                                lista += "<div class='card border'>";
                                lista += "<div class='card-header text-white bg-dark font-weight-bold'>Resultado da busca</div>";
                                lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                                lista += "<thead class='bg bg-dark text-white'>";
                                lista += "<tr>";
                                lista += "<th scope='col' class='text-center'>SIGLA</th>";
                                lista += "<th scope='col' class='text-center'>CN <i class='icon-location'></i></th>";
                                lista += "<th scope='col' class='text-center'>SELECIONE <i class='icon-target-2'></i></th>";
                                lista += "</tr>";
                                lista += "</thead>";
                                lista += "<tbody>";
                                for (var i = 0; i < linhas.length; i++) {

                                    var tipo = linhas[i].tipo;
                                    var cn = linhas[i].cn; //doDestacaTexto(linhas[i].cidade, txt);
                                    var sigla = linhas[i].sigla; //doDestacaTexto(linhas[i].sigla, txt);

                                    tipo = tipo.split(' ')[1];
                                    lista += "<tr id='linha" + linhas[i].id + "'>";
                                    lista += "<td class='text-center'>V" + tipo + " - " + sigla + "</td>";
                                    lista += "<td class='text-center'>" + cn + "</td>";
                                    lista += "<td class='text-center'><button class='btSelecionaSite btn btn-secondary btn-sm' value='" + linhas[i].id + "'>Seleciona <i class='icon-target-2'></i></button></td>";
                                    lista += "</tr>";
                                }
                                lista += "</tbody>";
                                lista += "</table></div>";
                                $("#listaSite").slideDown("fast").html(lista);

                                //Chama função para Add item
                                SelecionaSite();
                            }
                        }
                    });
                }
            });

        });
    }

    function doDestacaTexto(Texto, termoBusca) {

        /*******************************************************************/
        // CASO QUEIRA MODIFICAR O ESTILO DA MARCAÇÃO ALTERE ESSAS VARIÁVEIS
        /*******************************************************************/

        inicioTag = "<span class='bg bg-info font-weight-bold text-white'>";
        fimTag = "</span";

        var novoTexto = "";
        var i = -1;
        var lcTermoBusca = termoBusca.toLowerCase();
        var lcTexto = Texto.toLowerCase();

        while (Texto.length > 0) {
            i = lcTexto.indexOf(lcTermoBusca, i + 1);
            if (i < 0) {
                novoTexto += Texto;
                Texto = "";
            } else {
                if (Texto.lastIndexOf(">", i) >= Texto.lastIndexOf("<", i)) {
                    if (lcTexto.lastIndexOf("/script>", i) >= lcTexto.lastIndexOf("<script", i)) {
                        novoTexto += Texto.substring(0, i) + inicioTag + Texto.substr(i, termoBusca.length) + fimTag;
                        Texto = Texto.substr(i + termoBusca.length);
                        lcTexto = Texto.toLowerCase();
                        i = -1;
                    }
                }
            }
        }
        return novoTexto;
    }

    function CriaSolicitacao(site) {

        $("#retornoNovoElemento").slideUp("fast").text("");
        $("#retornoNovoElemento").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "criaSolicitacao", site: site },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var classe = "";
                var msg = "";
                var beta = dados.beta;
                if (dados.erro === "1") {

                    //       msg = "<i class='icon-attention'></i> " + dados.msg;
                    //          classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    //             if (beta === 0) {
                    //                    $("#retornoNovoElemento").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function() {
                    //                           var href = "https://cep.solicitacaooem.com.br/";
                    //                            window.location.href = href;
                    //                         });
                    //                    } else {
                    $("#retornoNovoElemento").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function() {
                        solicitacaoVerifica();
                    });
                    //                  }
                } else {
                    msg = "<i class='icon-ok-1'></i> " + dados.msg;
                    classe = "bg bg-success rounded text-white font-weight-bold p-2 mt-2";
                    $("#retornoNovoElemento").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function() {

                        solicitacaoVerifica();
                    });
                }
            }
        });
    }

    function SelecionaSite() {

        $(".btSelecionaSite").click(function() {
            var site = $(this).attr('value');

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "verificaDadosSite", site: site },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'CEPCADASTRO', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {
                    if (dados.erro === "1") {

                        var msg = "<i class='icon-ok-1'></i> " + dados.msg;
                        var classe = "bg bg-success rounded text-white font-weight-bold p-2 mt-2";

                        $("#retornoNovoElemento").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast");

                    } else {
                        $("#listaSite").slideUp("slow");
                        $("#formDadosSite").removeClass("d-none");
                        $("#spanIdSite").text(dados.id);
                        $("#spanSigla").text(dados.tipo + " - " + dados.sigla);
                        $("#spanNome").text(dados.descricao);
                        $("#spanEndereco").text(dados.endereco);
                    }
                }
            });
        });
    }

    function form_estrutura() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaEstrutura" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = "<option value=\"0\">ESTRUTURA</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].sigla + "</option>";
                }
                $("#formEstrutura").slideDown("fast").html(linhas);
            }
        });
    }

    function form_tipo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaTipo" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = "<option value=\"0\">TIPO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#formElementoTipo").slideDown("fast").html(linhas);
            }
        });
    }

    function form_elemento(tipo) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaElemento", tipo: tipo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = "<option value=\"0\">ELEMENTO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].sigla + "</option>";
                }
                $("#formElemento").slideDown("fast").html(linhas);
            }
        });
    }

    function add_elemento(elemento) {

        $("#ModalRetorno").text("");
        $("#ModalRetorno").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "addElemento", elemento: elemento },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1" || dados.erro === "2") {
                    var tempo = 1500;
                    var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    var msg = "<i class='icon-attention'></i> " + dados.msg;

                    if (dados.erro === "2") {
                        tempo = 37000;
                    }

                    $("#ModalRetorno").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");
                } else {

                    var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";
                    var msg = "<i class='icon-ok-circle'></i> " + dados.msg;

                    $("#listaElemento").slideUp("fast", function() {
                        $("#ModalRetorno").slideDown("fast").addClass(classe).append(msg).delay(1600).slideUp("fast", function() {

                            var opt = "option[value=0]";
                            $("#formEstrutura").find(opt).attr("selected", "selected");

                            $("#formNgabinete").val("");

                            $("#formElemento").find(opt).attr("selected", "selected");

                            $("#formNelemento").val("");
                            $("#formObs").val("")

                            $("#Modal").modal("hide");
                            Elemento_lista();
                        });
                    });
                }
            }
        });
    }

    function modal_exclui_elemento() {
        $(".bt_Modal_exluiElemento").click(function() {
            var id = $(this).attr('value');

            $("#ModalExcluiElemento").modal("show");

            var id = $("#eListaElementoIDG_" + id).text();
            var estr = $("#eListaEstrutura_" + id).text();
            var elem = $("#eListaElemento_" + id).text();
            var site = $("#spanSigla").text();

            $("#excluiElementoPai").text(estr.split(': ')[1] + "." + elem.split(': ')[1]);
            $("#excluiElementoSite").text(site);
            $("#excluiElementoIDG").text(id);

        });
    }

    function cancela_cadastro(pai) {

        $("#ModalRetornoCancela").text("");
        $("#ModalRetornoCancela").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoCancela", pai: pai },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";

                    $("#ModalRetornoCancela").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast");
                } else {

                    var msg = "<i class='icon-ok-1'></i> " + dados.msg;
                    var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#listaElemento").slideUp("fast", function() {
                        $("#ModalRetornoCancela").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast", function() {

                            $("#spanIdPai").text("").fadeOut("fast");

                            $("#ModalCancelaCadastro").modal("hide");

                            location.reload();
                        });
                    });
                }
            }
        });
    }

    function conclui_elemento(pai) {

        $("#ModalRetornoConclui").text("");
        $("#ModalRetornoConclui").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "concluiElemento", pai: pai },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetornoConclui").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast");
                } else {
                    var msg = "<i class='icon-ok-1'></i> " + dados.msg;
                    var classe = "bg bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#listaElemento").slideUp("fast", function() {
                        $("#ModalRetornoConclui").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast", function() {

                            $("#spanIdPai").text("").fadeOut("fast");

                            $("#ModalConcluiCadastro").modal("hide");

                            location.reload();
                        });
                    });
                }
            }
        });
    }

    function deleta_elemento(elemento) {

        $("#ModalRetornoExcluir").text("");
        $("#ModalRetornoExcluir").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "excluiElemento", elemento: elemento },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg bg-danger rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetornoExcluir").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast");
                } else {
                    var msg = "<i class='icon-ok-1'></i> " + dados.msg;
                    var classe = "bg bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#listaElemento").slideUp("fast", function() {
                        $("#ModalRetornoExcluir").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast", function() {


                            $("#ModalExcluiElemento").modal("hide");
                            Elemento_lista();
                        });
                    });
                }
            }
        });
    }

    function Elemento_lista() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaElementoCadastrados" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'CEPCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {

                    var classe = "alert-danger rounded";
                    $("#ModalRetornoFiltro").slideDown("fast").addClass(classe).append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var linhas = eval(dados);

                    if (linhas.length === 0) {

                        $("#ModalRetornoFiltro").slideUp("fast").empty();

                        $("#ModalRetornoFiltro").slideUp("fast").removeClass();
                        var classe = "alert-danger rounded";
                        $("#ModalRetornoFiltro").slideDown("fast").addClass(classe).append("Nenhum resultado!").delay(1500).slideUp("fast");

                    } else {
                        $("#ModalFiltro").modal("hide");

                        var lista = "<div class='bg bg-dark rounded font-weight-bold text-white mb-2'>Lista de Elementos</div>";

                        lista += "<div id='elementoAtivo' class='row'>";
                        var qtd = 0;
                        for (var i = 0; i < linhas.length; i++) {
                            var est_n = "";
                            var elemento_n = "";
                            var ativo_pai = "";
                            var ativo = "";
                            var ePai = linhas[i].ePai;
                            var uf = linhas[i].uf;

                            if (linhas[i].estrutura_n === 0 || linhas[i].estrutura_n === "0") {
                                est_n = "";
                            } else {
                                est_n = linhas[i].estrutura_n;
                            }
                            if (linhas[i].elemento_n === 0 || linhas[i].elemento_n === "0") {
                                elemento_n = "";
                            } else {
                                elemento_n = linhas[i].elemento_n;
                            }
                            ativo_pai = linhas[i].site + "." + linhas[i].estrutura + est_n + "." + linhas[i].ativo_pai;

                            if (linhas[i].elemento === "SAC") {

                                ativo_pai = linhas[i].site + "." + linhas[i].estrutura + est_n;

                                ativo = ativo_pai + "." + linhas[i].elemento + elemento_n;

                            } else
                            if (linhas[i].elemento === "RF" || linhas[i].elemento === "TX") {

                                ativo_pai = linhas[i].site + "." + linhas[i].estrutura + est_n;

                                ativo = ativo_pai + "." + linhas[i].elemento;

                            } else
                            if (linhas[i].elemento === "ELTE" || linhas[i].elemento === "ELTI") {

                                ativo_pai = linhas[i].site;

                                ativo = ativo_pai + "." + linhas[i].elemento;

                            } else
                            if (linhas[i].elemento === "EV") {

                                ativo_pai = linhas[i].site + "." + linhas[i].excel + elemento_n;

                                ativo = ativo_pai + "." + linhas[i].elemento;
                            } else
                            if (linhas[i].elemento === "QCAB" || linhas[i].elemento === "QDG") {

                                ativo_pai = linhas[i].site + "." + linhas[i].ativo_pai;

                                ativo = ativo_pai + "." + linhas[i].elemento + elemento_n;
                            } else
                            if (linhas[i].elemento === "QDGE" || linhas[i].elemento === "QDGN") {

                                ativo_pai = linhas[i].site + "." + linhas[i].estrutura + linhas[i].estrutura_n;

                                ativo = ativo_pai + "." + linhas[i].elemento + elemento_n;
                            } else
                            if (linhas[i].elemento === "SPDA") {

                                ativo_pai = linhas[i].site;

                                ativo = ativo_pai + "." + linhas[i].elemento;
                            } else
                            if (linhas[i].elemento === "SDAI") {

                                ativo_pai = linhas[i].site + "." + linhas[i].estrutura + linhas[i].estrutura_n;

                                ativo = ativo_pai + "." + linhas[i].elemento;
                            } else
                            if (linhas[i].estrutura === "ELEMENTO_PAI") {

                                ativo = linhas[i].site + "." + linhas[i].ativo_pai;

                                ativo_pai = linhas[i].site;
                            } else
                            if (linhas[i].ativo_pai === "ESTRUTURA") {

                                ativo_pai = linhas[i].site + "." + linhas[i].estrutura + est_n;
                                ativo = ativo_pai + "." + linhas[i].excel + elemento_n;

                            } else
                            if (linhas[i].ativo_pai === "ESTRUTURA_FONTE") {

                                ativo_pai = linhas[i].site + "." + linhas[i].estrutura + est_n + ".FCC" + linhas[i].fcc;
                                ativo = ativo_pai + "." + linhas[i].excel + elemento_n;

                            } else
                            if (linhas[i].ativo_pai === "TX" || linhas[i].ativo_pai === "RF" || linhas[i].ativo_pai === "SAC1") {

                                ativo = linhas[i].site + "." + linhas[i].estrutura + est_n + "." + linhas[i].ativo_pai + "." + linhas[i].excel + elemento_n;
                            } else
                            if (linhas[i].ativo_pai === "CSP") {

                                ativo = linhas[i].site + "." + linhas[i].estrutura + "." + linhas[i].excel + elemento_n;

                                ativo_pai = linhas[i].site + "." + linhas[i].estrutura + est_n;
                            } else
                            if (linhas[i].ativo_pai === "GAB") {

                                ativo = linhas[i].site + "." + linhas[i].estrutura + est_n;

                                ativo_pai = linhas[i].site + "." + linhas[i].estrutura + est_n;
                            } else
                            if (linhas[i].ativo_pai === "SITE") {

                                ativo = linhas[i].site + "." + linhas[i].excel + elemento_n;

                                ativo_pai = linhas[i].site;
                            } else
                            if (linhas[i].ativo_pai === "CAP1") {

                                ativo = linhas[i].site + "." + linhas[i].estrutura + est_n + "." + linhas[i].ativo_pai + "." + linhas[i].excel + elemento_n;
                            } else {

                                ativo = linhas[i].site + "." + linhas[i].estrutura + est_n + "." + linhas[i].excel + elemento_n;

                                ativo_pai = linhas[i].site + "." + linhas[i].estrutura + est_n + "." + linhas[i].ativo_pai;
                            }

                            if (linhas[i].tipo_site === 1 || linhas[i].tipo_site === "1") {

                                ativo = "M."+uf+"." + ativo;
                                ativo_pai = "M."+uf+"." + ativo_pai;
                            } else {

                                ativo = "V2."+uf+"." + ativo;
                                ativo_pai = "V2."+uf+"." + ativo_pai;
                            }

                            if (ePai === "1") {
                                classeExibir = "d-none ";
                            } else {
                                classeExibir = "";
                                qtd = (parseInt(i) + 1);

                            }
                            lista += "<div class='col'>";
                            lista += "<div class='" + classeExibir + "card bg-light mb-2' style='max-width: 18rem;'>";
                            lista += "<div class='card-header'><span class='text-muted font-weight-bold'></span>" + linhas[i].data + " - " + linhas[i].hora + "</div>";
                            lista += "<div class='card-body'>";
                            lista += "<ul class='list-group list'>";
                            lista += "<li id='eListaEstrutura_" + linhas[i].id + "' class='list-group-item text-left'><i class='icon-cd'></i> <b>ESTRUTURA: </b><span id='eLEstrutura" + qtd + "'>" + linhas[i].estrutura + est_n + "</span></li>";
                            lista += "<li id='eListaElemento_" + linhas[i].id + "' class='list-group-item text-left'><i class='icon-cd'></i> <b>ELEMENTO: </b><span id='eLElemento" + qtd + "'>" + linhas[i].elemento + elemento_n + "</span></li>";
                            lista += "<li class='list-group-item text-left'><i class='icon-cd'></i> <b>DESCRIÇÃO:</b> " + linhas[i].descricao + "</li>";
                            lista += "<li class='list-group-item text-left'><span class='badge badge-light text-muted border'>ID GERAL: <span id='eListaElementoIDG_" + linhas[i].id + "'>" + linhas[i].id + "</span></span></b></li>";
                            lista += "<li class='list-group-item text-left'><span class='badge badge-light text-muted border'>ATIVO PAI: <span id='eLAtivoP" + qtd + "'>" + ativo_pai + "</span></span></b></li>";
                            lista += "<li class='list-group-item text-left'><span class='badge badge-light text-muted border'>ATIVO: <span id='eLAtivo" + qtd + "'>" + ativo + "</span></span></b></li>";
                            lista += "</ul>";
                            lista += "</div>";

                            lista += "<div class='elementoComum card-footer text-muted'><button value='" + linhas[i].id + "' class='bt_Modal_exluiElemento btn btn-light btn-sm border text-muted'><i class='icon-trash-4 text-danger'></i> Remover</button></div>";

                            lista += "</div>";
                            lista += "</div>";


                        }

                        $("#formConcluiSolicitacao").val(qtd);

                        lista += "</div>";
                        $("#listaElemento").slideDown("slow").html(lista);

                        modal_exclui_elemento();
                    }
                }
            }
        });
    }
});