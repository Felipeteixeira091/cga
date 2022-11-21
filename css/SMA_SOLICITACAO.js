$(document).ready(function() {


    solicitacaoVerifica();

    $("#formCriar").click(function() {
        var sigla = $("#formSigla").text();
        var os = $("#formOs").val();
        var osTipo = $("#formTipoOs").val();
        var tipo = $("#formSegmento").val();
        var almoxarifado = $("#formAlmox").val();
        var faturaTipo = $("#formTipoFatura").val();
        var retirada = $("#formRetira").val();
        var sobressalente = $("#formSobressalente").val();
        var obs = $("#formObs").val();


        CriaSolicitacao(sigla, tipo, faturaTipo, osTipo, almoxarifado, os, retirada, sobressalente, obs);
    });

    $("#formCancela").click(function() {
        var solicitacao = $("#NumeroSolicitacao").text();
        CancelaSolicitacao(solicitacao);
    });
    $("#formConclui").click(function() {
        var solicitacao = $("#NumeroSolicitacao").text();
        ConcluiSolicitacao(solicitacao);
    });

    function anexoDownload() {

        $(".AnexoBt").click(function() {
            var anexo = $(this).attr('value');

            var href = "sma_anexo/" + anexo;

            window.open(href);
        });
    }

    function SiteProcura() {

        $("#btProcuraSite").click(function() {

            $("#formDadosSite").addClass("d-none");

            var txt = $("#formSite").val();

            $("#listaSite").slideUp("fast", function() {

                $("#ModalRetorno").text("");
                $("#ModalRetorno").removeClass();

                if (txt.length === 0) {

                    var tempo = 1500;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    var msg = "<i class='icon-attention-2'></i> Necessário preencher o campo de busca.";

                    $("#retornoNova").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                } else {

                    $.ajax({
                        type: 'post', //Definimos o método HTTP usado
                        data: { acao: "SiteProcura", txt: txt },
                        dataType: 'json', //Definimos o tipo de retorno
                        url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
                        success: function(dados) {

                            if (dados.length === 0) {

                                var tempo = 1900;
                                var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                                var msg = "<i class='icon-attention-2'></i> Site não cadastrado, você será direcionado à tela de cadastro.";

                                $("#retornoNova").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast", function() {

                                    window.location.href = "SITE";
                                });

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
                                        tipo = "V" + tipo.split(' ')[1];
                                    }

                                    lista += "<tr id='linha" + linhas[i].id + "'>";
                                    lista += "<td class='text-center'>" + linhas[i].uf + "</td>";
                                    lista += "<td class='text-center'>" + tipo + " - " + sigla + "</td>";
                                    lista += "<td class='d-md-none text-center'>" + cidade + "</td>";
                                    lista += "<td class='d-md-none text-center '>" + descricao + "</td>";
                                    lista += "<td class='text-center'><button class='btSelecionaSite btn btn-secondary btn-sm' value='" + linhas[i].id + "'>Seleciona <i class='icon-target-2'></i></button></td>";
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
                url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {

                    $("#formProcuraSite").slideUp("fast");

                    $("#formNovo").slideDown("fast", function() {

                        $("#textoSite").append("").append(dados.tipo + " " + dados.sigla);
                        $("#formSigla").append("").append(dados.id);
                    });


                }
            });
        });
    }

    function solicitacaoVerifica() {

        $("#pa_formulario1").slideUp("fast");
        $("#pa_formulario2").slideUp("fast");
        $("#ListaItensAdd").slideUp("fast");
        $("#SolicitacaoItens").slideUp("fast");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoVerifica" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var ativa = dados.ativa;
                var id = dados.id;
                var sobressalente = dados.sobressalente;

                if (ativa === "sim") {
                    $("#SiteSolicitacao").text(dados.site);
                    $("#NumeroSolicitacao").text(id);
                    $("#pa_formulario2").slideDown("fast");
                    if (sobressalente === "1") {
                        $("#Sobressalente").text("SIM");

                    } else {
                        $("#Sobressalente").text("NÃO");
                    }
                    if (dados.site === "ERRO") {

                        $("#SiteSolicitacao").addClass("text-danger");

                        $(".fnovo").attr("disabled", true);

                        var msg = "<i class='icon-attention'></i> Houve um erro ao incluir o site, cancele a solicitação e crie novamente por favor."

                        var classe = "bg-warning text-danger rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoNova1").slideDown("fast").addClass(classe).append(msg);

                    } else {
                        $("#SiteSolicitacao").removeClass("text-danger");
                        $(".fnovo").attr("disabled", false);
                    }
                    ItemProcura();
                    ItemLista();
                } else {

                    $("#pa_formulario1").slideDown("fast");
                    SiteProcura();
                    form_usuario();
                    form_segmento();
                    form_faturaTipo();
                    form_osTipo();
                    form_almox();
                }
            }
        });
    }

    function ItemProcura() {

        $("#btFiltra1").click(function() {

            $("#retornoNova1").text("");
            $("#retornoNova1").removeClass();

            var txt = $("#ItemTXT").val();

            var sobressalente = $("#Sobressalente").text();

            $("#SolicitacaoItens").slideUp("fast");

            if (txt.length < 3) {
                $("#ListaItensAdd").slideUp("fast").val("");
                var msg = "<i class='icon-attention'></i> Nenhuma correspondência!";
                var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {
                    ItemLista();
                });

            } else {

                $.ajax({
                    type: 'post', //Definimos o método HTTP usado
                    data: { acao: "ItemProcura", txt: txt, sobressalente: sobressalente },
                    dataType: 'json', //Definimos o tipo de retorno
                    url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
                    success: function(dados) {
                        if (dados.length === 0) {

                            var msg = "<i class='icon-attention'></i> Nenhuma correspondência!";
                            var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                            $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                        } else {
                            var linhas = eval(dados);

                            var lista = "";
                            lista += "<div class='card border-light mt-2 p-1'>";
                            lista += "<div class='card-header font-weight-bold'>Resultado da busca</div>";
                            lista += "<table class='table table-striped w-auto'>";
                            lista += "<thead class='thead-dark'>";
                            lista += "<tr>";
                            lista += "<th scope='col'>PA</th>";
                            lista += "<th scope='col'>DESCRIÇÃO</th>";
                            lista += "<th scope='col'>TIPO</th>";
                            lista += "<th scope='col'>QTD</th>";
                            lista += "<th scope='col'>ADICIONAR</th>";
                            lista += "</tr>";
                            lista += "</thead>";
                            lista += "<tbody>";
                            for (var i = 0; i < linhas.length; i++) {
                                lista += "<tr id='linha" + linhas[i].id + "'>";
                                lista += "<td>" + linhas[i].numero + "</td>";
                                lista += "<td>" + linhas[i].descricao + "</td>";
                                lista += "<td>" + linhas[i].tipo + "</td>";
                                var qtd = "qtd_item" + linhas[i].id;
                                lista += "<td><input class='form-control' id=" + qtd + " value='1' type='number'></td>";
                                lista += "<td><button class='btAddItem btn btn-sm btn-outline-info' value='" + linhas[i].id + "'><i class='icon-plus-circled'></i> Adicionar</button></td>";
                                lista += "</tr>";
                            }
                            lista += "</tbody>";
                            lista += "</table></div>";
                            $("#ListaItensAdd").slideDown("fast").html(lista);
                            ItemAdd();
                        }
                    }
                });
            }
        });
    }

    function CriaSolicitacao(sigla, tipo, faturaTipo, osTipo, almoxarifado, os, retirada, sobressalente, obs) {

        $("#retornoNova").text("");
        $("#retornoNova").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "criaSolicitacao", sigla: sigla, tipo: tipo, faturaTipo: faturaTipo, osTipo: osTipo, almoxarifado: almoxarifado, os: os, retirada: retirada, sobressalente: sobressalente, obs: obs },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {

                    var msg = dados.msg;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoNova").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                } else {
                    var msg = dados.msg;
                    var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoNova").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        window.location.reload();
                    });
                }
            }
        });
    }

    function ConcluiSolicitacao(solicitacao) {

        $("#retornoNova1").text("");
        $("#retornoNova1").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoConclui", solicitacao: solicitacao },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                if (dados.erro === "1") {

                    var msg = dados.msg;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                } else {

                    var msg = dados.msg;
                    var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        window.location.reload();
                    });
                }

            }
        });
    }

    function CancelaSolicitacao(solicitacao) {

        $("#retornoNova1").text("");
        $("#retornoNova1").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "SolicitacaoCancela", solicitacao: solicitacao },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var msg = dados.msg;
                var classe = "bg-info rounded font-weight-bold text-white pt-2 pb-2";

                $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                    window.location.reload();
                });

            }
        });
    }

    function ItemAdd() {

        $(".btAddItem").click(function() {
            var pa = $(this).attr('value');

            var qtd = "qtd_item" + pa;
            var quantidade = $("#" + qtd).val();

            var solicitacao = $("#NumeroSolicitacao").text();

            $("#retornoNova1").text("");
            $("#retornoNova1").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "ItemAdd", solicitacao: solicitacao, pa: pa, quantidade: quantidade },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {
                    if (dados.erro === "1") {

                        var msg = dados.msg;
                        var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                        $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");
                    } else {

                        var msg = "<i class='icon-ok-circle-1'></i> " + dados.msg;
                        var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                        $("#ListaItensAdd").slideUp("slow");

                        $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                            $("#ItemTXT").val("");
                            $("#ItemQuantidade").val("");
                            ItemLista();
                        });
                    }
                }
            });
        });
    }

    function ItemLista() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "ItensSolicitacao" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = eval(dados);

                $("#SolicitacaoItens").slideUp("fast");

                var lista = "";
                if (linhas.length > 0) {
                    lista += "<div class='card border mt-2 p-1'>";
                    lista += "<div class='card-header font-weight-bold'>Itens já adicionados à solicitação</div>";

                    lista += "<div class='row mt-1'>";

                    for (var i = 0; i < linhas.length; i++) {

                        var anexo = "";

                        if (linhas[i].anexo === "nd") {
                            anexo = "<i class='icon-attention-2 text-danger'></i>"
                        } else {
                            anexo = "<button value='" + linhas[i].anexo + "' class='AnexoBt btn btn-sm btn-outline-info ml-2'><i class='icon-download-2 text-info'></i> Ver</button>"
                        }

                        lista += "<div class='col' id='pa" + linhas[i].id + "'>";
                        lista += "<div class='card bg-light mb-2' style='max-width: 18rem;'>";
                        lista += "<div class='card-header'><span class='text-muted font-weight-bold' id='nPa" + linhas[i].id + "'>" + linhas[i].pa + "</span></div>";
                        lista += "<div class='card-body'>";
                        lista += "<ul class='list-group list'>";
                        lista += "<li class='list-group-item text-left'><i class='icon-cd'></i> <b>DESCRIÇÃO:</b> " + linhas[i].descricao + "</li>";
                        lista += "<li class='list-group-item text-left'><i class='icon-cd'></i><b>QUANTIDADE:</b> " + linhas[i].quantidade + "</li>";
                        lista += "<li class='list-group-item text-left'><i class='icon-cd'></i><b><i class='icon-attach-3 text-info'></i>:</b> " + anexo + "</li>";
                        lista += "</ul>";
                        lista += "</div>";

                        var upload = "";

                        if ($("#Sobressalente").text() === "SIM") {
                            upload = "<button value='" + linhas[i].id + "' class='btUpload btn btn-sm btn-outline-info ml-2'><i class='icon-upload-1'></i> Anexo</button>";
                        }
                        lista += "<div class='card-footer text-muted'><button value='" + linhas[i].id + "' class='btRemoveItem btn btn-sm btn-outline-danger'><i class='icon-minus-circled'></i> Remover</button>";
                        lista += upload;
                        lista += "</div>";
                        lista += "</div>";
                        lista += "</div>";
                    }
                    $("#SolicitacaoItens").slideDown("fast").html(lista);
                    ItenRemove();
                    ItenAnexo();
                    anexoDownload();
                }
            }
        });
    }

    function ItenAnexo() {

        $(".btUpload").click(function() {
            var id = $(this).attr('value');

            $("#itemId").text(id);
            $("#itemPA").val($("#nPa" + id).text());
            $("#form_upload").modal("show");
        });
    }

    function ItenRemove() {

        $(".btRemoveItem").click(function() {
            var linha = $(this).attr('value');

            $("#retornoNova1").text("");
            $("#retornoNova1").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "ItemRemove", id: linha },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {

                    var msg = "<i class='icon-ok-circle-1'></i> " + dados.msg;
                    var classe = "bg-info rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoNova1").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        $("#pa" + linha).fadeOut("fast");
                        $("#ItemTXT").val("");
                        $("#ItemQuantidade").val("");
                        ItemLista();
                    });

                }
            });
        });
    }

    function form_almox() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "almoxLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = "<option value=\"0\">ALMOXARIFADO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#formAlmox").slideDown("fast").html(linhas);
            }

        });
    }

    function form_usuario() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "UsuarioLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = "<option value=\"0\">QUEM RETIRA</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].re + "\">" + linha[i].nome + " - " + linha[i].cn + "</option>";
                }
                $("#formRetira").slideDown("fast").html(linhas);
            }
        });
    }

    function form_segmento() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "segmentoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = "<option value=\"0\">TIPO DE SOLICITAÇÃO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#formSegmento").slideDown("fast").html(linhas);
            }
        });
    }

    function form_osTipo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "osTipoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var linhas = "<option value=\"0\">TIPO DE ATIVIDADE</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#formTipoOs").slideDown("fast").html(linhas);

            }
        });
    }

    function form_faturaTipo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "faturaTipoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SMASOLICITACAO', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = "<option value=\"0\">FATURA</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">FATURA " + linha[i].nome + "</option>";
                }
                $("#formTipoFatura").slideDown("fast").html(linhas);
            }
        });
    }
});