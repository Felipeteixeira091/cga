$(document).ready(function () {

    $("#formulario1").slideDown("fast");

    dados_select("", "CN", 0);
    $("#btFiltra").click(function () {

        var txt = $("#TXT").val();
        var cn = $("#CN").val();

        $("#retornoFiltro").text("");
        $("#retornoFiltro").removeClass();

        var tempo = 1500;
        var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
        var msg = "<i class='icon-attention'></i> Informações insuficientes!";


        $("#Lista").slideUp("fast");
        if (txt === "" && cn === "0") {
            $("#retornoFiltro").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

        } else {
            Procura(txt, cn);
        }
    });

    $("#btFormNovo").click(function () {

        $("#Lista").slideUp("fast", function () {
            $("#Modal_novo").modal("show");

            dados_select("novo", "Estado", 0);
            dados_select("novo", "CN", 0);
            dados_select("novo", "Coordenador", 0);
            dados_select("novo", "Tipo", 0);
            dados_select("novo", "Ativo", 0);


            $("#tituloForm").text("Novo GMG");
            //  $("#formulario_novo").slideDown("fast");
            $("#bt_cadastro").slideDown("fast");
        });

    });
    $("#btFormTrans").click(function () {

        $("#ModalRetornoTransferencia").text("");
        $("#ModalRetornoTransferencia").removeClass();

        $("#Modal_transferencia").modal("show");
        form_transferecnia();
    });
    $("#bt_edita_GMG").click(function () {

        edita();
    });
    $("#bt_cadastro_GMG").click(function () {

        $("#ModalRetorno_novo").text("");
        $("#ModalRetorno_novo").removeClass();

        cadastro();
    });

    $("#bt_cadastro_voltar").click(function () {

        cadastro_voltar();
    });
    $("#bt_update").click(function () {

        $("#ModalRetorno_edita").text("");
        $("#ModalRetorno_edita").removeClass();
        update();
    });
    $("#btCartaoAltera").click(function () {

        $("#ModalRetorno_cartao").text("");
        $("#ModalRetorno_cartao").removeClass();
        $("#cartaoGMG").text("");

        cartaoAltera();
    });
    $("#btCartaoRemove").click(function () {

        $("#ModalRetorno_cartao").text("");
        $("#ModalRetorno_cartao").removeClass();

        cartaoRemove();
    });
    $("#btCartaoAtribui").click(function () {

        $("#ModalRetorno_cartao").text("");
        $("#ModalRetorno_cartao").removeClass();

        cartaoAtribui();
    });
    $("#btCartaoDesbloqueio").click(function () {

        $("#ModalRetorno_cartao").text("");
        $("#ModalRetorno_cartao").removeClass();

        cartaoDesbloqueio();
    });
    function cartaoDesbloqueio() {

        var id = $("#cartaoAtualID").text();
        var cartaoAtual = $("#cartaoAtual").text();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cartaoDesbloqueio", id: id, cartaoAtual: cartaoAtual },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetorno_cartao").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(2000).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_cartao").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1200).slideUp("fast", function () {

                        $("#Modal_cartao").modal("hide");
                    });
                }
            }
        });
    }
    function edita() {

        var cod = $("#editaCod").text();
        var uf = $("#editaEstado").val();
        var cn = $("#editaCN").val();
        var identificacao = $("#editaIdentificacao").val();
        var tipo = $("#editaTipo").val();
        var ativo = $("#editaAtivo").val();
        var coordenador = $("#editaCoordenador").val();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "editaGMG", cod: cod, uf: uf, cn: cn, identificacao: identificacao, tipo: tipo, ativo: ativo, coordenador: coordenador },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                if (dados.erro === "1") {

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetorno_edita").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");
                } else {

                    var msg = "<i class='icon-ok-circle-1'></i> " + dados.msg;
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_edita").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        //                        $(".statusLn" + solicitacao).text("CONCLUÍDO");
                        $("#Modal_edita").modal("hide");
                    });
                }
            }
        });
    }
    function formEdita() {

        $(".btEditaGMG").click(function () {
            var cod = $(this).attr('value');

            gmg_dados(cod);

            $("#Modal_edita").modal("show");

        });
    }
    function formEditaCartao() {

        $(".btCartao").click(function () {
            var cod = $(this).attr('value');

            $("#Lista").slideUp("fast");

            cartao_dados(cod);

            $("#Modal_cartao").modal("show");

        });
    }

    function cartao_dados(cod) {

        $("#cartaoAtual").text("");
        $("#cartaoAtualGMG").text("");
        $("#cartaoAtualID").text("");
        $("#cartaoNovo").val("");
        $("#cartaoMotivoTroca").val("");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "dadosCartao", cod: cod },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var cartao = dados.cartao;

                $("#cartaoAtual").append(cartao.cartao);
                $("#cartaoAtualGMG").append(cartao.gmg);
                $("#cartaoAtualID").append(cartao.cod);

                cartao_lista();

                if (dados.permissao > 0) {
                    $("#cartaoFerramenta").removeClass("d-none");
                } else {
                    $("#cartaoFerramenta").addClass("d-none");
                }
            }
        });
    }
    function cartaoAltera() {

        var cod = $("#cartaoAtualID").text();
        var cartaoAtual = $("#cartaoAtual").text();
        var cartaoNovo = $("#cartaoNovo").val();
        var cartaoMotivo = $("#cartaoMotivoTroca").val();

        var classe = "bg-info rounded text-white font-weight-bold p-2 mt-2";
        var msg = "<i class='bi bi-hourglass-split'></i> Por favor, aguarde...";
        $("#ModalRetorno_cartao").empty().slideDown("fast").addClass(classe).append(msg);

        $("#btCartaoAltera").attr("disabled", true);

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cartaoAltera", cod: cod, cartaoAtual: cartaoAtual, cartaoNovo: cartaoNovo, cartaoMotivo: cartaoMotivo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                $("#ModalRetorno_cartao").slideUp("fast", function () {

                    $("#ModalRetorno_cartao").text("");
                    $("#ModalRetorno_cartao").removeClass();

                    $("#btCartaoAltera").attr("disabled", false);
                });

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetorno_cartao").slideDown("fast").addClass(classe).html(dados.msg).delay(2000).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_cartao").slideDown("fast").addClass(classe).html(dados.msg).delay(2000).slideUp("fast", function () {

                        $("#Modal_cartao").modal("hide");
                });
    }
}
        });
    }
function cartao_lista() {

    $.ajax({
        type: 'post', //Definimos o método HTTP usado
        data: { acao: "cartaoLista" },
        dataType: 'json', //Definimos o tipo de retorno
        url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
        success: function (dados) {

            var linha = eval(dados);

            var linhas = "<option valur='0'>CARTÕES JÁ CADASTRADOS</option>";
            for (var i = 0; i < linha.length; i++) {
                linhas += "<option value=" + linha[i].cartao + ">" + linha[i].cartao + "</option>";
            }
            $("#cartaoNovoselect").html(linhas);
        }
    });
}
function cartaoRemove() {

    var cod = $("#cartaoAtualCod").text();
    var cartaoAtual = $("#cartaoAtual").text();

    $.ajax({
        type: 'post', //Definimos o método HTTP usado
        data: { acao: "cartaoRemove", cod: cod, cartaoAtual: cartaoAtual },
        dataType: 'json', //Definimos o tipo de retorno
        url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
        success: function (dados) {

            if (dados.erro === "1") {

                var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                $("#ModalRetorno_cartao").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(2000).slideUp("fast");
            } else {
                var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                $("#ModalRetorno_cartao").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1200).slideUp("fast", function () {

                    $("#Modal_cartao").modal("hide");
                });
            }
        }
    });
}
function cartaoAtribui() {

    $.ajax({
        type: 'post', //Definimos o método HTTP usado
        data: { acao: "cartaoAtribui", cod: $("#cartaoAtualID").text(), cartaoAtual: $("#cartaoAtual").text(), cartaoNovo: $("#cartaoNovoselect").val() },
        dataType: 'json', //Definimos o tipo de retorno
        url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
        success: function (dados) {

            if (dados.erro === "1") {

                var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                $("#ModalRetorno_cartao").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(2000).slideUp("fast");
            } else {
                var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                $("#ModalRetorno_cartao").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1200).slideUp("fast", function () {

                    $("#Modal_cartao").modal("hide");
                });
            }
        }
    });
}
function cadastro_voltar() {

    $("#formulario_novo").modal("hide", function () {

        window.location.replace("GMG");
    });
}
function Procura(txt, cn) {

    $("#retornoFiltro").text("");
    $("#retornoFiltro").removeClass();

    $.ajax({
        type: 'post', //Definimos o método HTTP usado
        data: { acao: "procura", txt: txt, cn: cn },
        dataType: 'json', //Definimos o tipo de retorno
        url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
        success: function (dados) {
            var verifica = eval(dados);

            if (verifica.length === 0) {
                $("#Lista").slideUp("fast").html("");

                var tempo = 1500;
                var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                var msg = "<i class='icon-attention'></i> Nenhuma correspondência!";

                $("#retornoFiltro").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

            } else {
                var linhas = eval(dados);

                var lista = "";
                lista += "<table class='table table-striped w-auto'>";
                lista += "<thead class='thead-dark'>";
                lista += "<tr>";
                lista += "<th scope='col' class='text-center'>NOME</th>";
                lista += "<th scope='col' class='text-center'>CARTÃO</th>";
                lista += "<th scope='col' class='text-center'>STATUS</th>";
                lista += "<th scope='col' class='text-center'>CN</th>";
                lista += "<th scope='col' class='text-center'>EDITA</th>";
                lista += "</tr>";
                lista += "</thead>";
                lista += "<tbody>";
                for (var i = 0; i < linhas.length; i++) {

                    lista += "<tr id='linha" + linhas[i].id + "'>";
                    lista += "<td class='text-center'>" + linhas[i].tipo + "_" + linhas[i].nome + "</td>";
                    lista += "<td  class='text-center' ><button class='btCartao btn btn-outline-secondary btn-sm' value='" + linhas[i].codigo + "'><i class='icon-credit-card'></i> " + linhas[i].cartao + "</button></td>";
                    lista += "<td class='text-center'>" + linhas[i].status + "</td>";
                    lista += "<td class='text-center'>" + linhas[i].cn + "</td>";
                    lista += "<td  class='text-center' ><button class='btEditaGMG btn btn-outline-secondary btn-sm' value='" + linhas[i].codigo + "'><i class='icon-pencil'></i> Editar</button></td>";
                    lista += "</tr>";
                }
                lista += "</tbody>";
                lista += "</table>";

                $("#Lista").slideDown("fast").html(lista);

                formEdita();
                formEditaCartao();
            }
        }
    });
}
function gmg_dados(cod) {

    $.ajax({
        type: 'post', //Definimos o método HTTP usado
        data: { acao: "dados", cod: cod },
        dataType: 'json', //Definimos o tipo de retorno
        url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
        success: function (dados) {
            dados_select("edita", "Estado", dados.estado);
            dados_select("edita", "CN", dados.cn);
            $("#editaCod").text(dados.cod);
            $("#editaIdentificacao").val(dados.identificacao);
            dados_select("edita", "Tipo", dados.tipo);
            dados_select("edita", "Coordenador", dados.coordenador);
            dados_select("edita", "Ativo", dados.ativo);
            $("#editaGMG").text(dados.tipoNome + "_" + dados.identificacao);
        }
    });
}
function dados_select(form, tipo, select) {

    var acao = tipo + "Lista";
    $.ajax({
        type: 'post', //Definimos o método HTTP usado
        data: { acao: acao },
        dataType: 'json', //Definimos o tipo de retorno
        url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
        success: function (dados) {

            var linhas = "";
            var linha = eval(dados);

            if (tipo === "Estado") {
                linhas += "<option value=\"0\">UF</option>";
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].sigla + "</option>";
                }
            } else if (tipo === "CN") {
                linhas += "<option value=\"0\">CN</option>";
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
            } else if (tipo === "Tipo") {
                linhas += "<option value=\"0\">TIPO</option>";
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
            } else if (tipo === "Coordenador") {
                linhas += "<option value=\"0\">COORDENADOR</option>";
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].re + "\">" + linha[i].nome + "</option>";
                }
            } else if (tipo === "Ativo") {
                linhas += "<option value=\"0\">STATUS</option>";
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
            }

            $("#" + form + tipo).slideDown("fast").html(linhas);



            var opt = "option[value=" + select + "]";
            $("#edita" + tipo).find(opt).attr("selected", "selected");
        }
    });
}
function cadastro() {

    var uf = $("#novoEstado").val();
    var cn = $("#novoCN").val();
    var identificacao = $("#novoIdentificacao").val();
    var tipo = $("#novoTipo").val();
    var cartao = $("#novoCartao").val();
    var coordenador = $("#novoCoordenador").val();

    $.ajax({
        type: 'post', //Definimos o método HTTP usado
        data: { acao: "cadastroGMG", uf: uf, cn: cn, identificacao: identificacao, tipo: tipo, cartao: cartao, coordenador: coordenador },
        dataType: 'json', //Definimos o tipo de retorno
        url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
        success: function (dados) {

            var tempo = 1500;

            if (dados.erro === "1") {

                var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                var msg = "<i class='icon-attention'></i> " + dados.msg;

                if (dados.tempo > 0) {
                    tempo = dados.tempo;
                }

                $("#ModalRetorno_novo").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");
            } else {
                var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";
                var msg = "<i class='icon-ok-circle-1'></i>  " + dados.msg;

                $("#ModalRetorno_novo").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("slow", function () {
                    window.location.replace("GMG");
                });
            }
        }
    });
}
function form_transferecnia() {

    $.ajax({
        type: 'post', //Definimos o método HTTP usado
        data: { acao: "transferenciaLista" },
        dataType: 'json', //Definimos o tipo de retorno
        url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
        success: function (dados) {
            if (dados.length === 0) {

                var tempo = 1500;
                var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                var msg = "<i class='icon-attention-2'></i> Nenhum ítem transferido.";

                $("#ModalRetornoTransferencia").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

            } else {
                var linhas = eval(dados);

                var lista = "";
                lista += "<div class='card border'>";
                lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                lista += "<thead class='bg bg-light text-muted'>";
                lista += "<tr>";
                lista += "<th scope='col' class='text-center'>IDENTIFICAÇÃO</th>";
                lista += "<th scope='col' class='text-center'>COORDENADOR ATUAL</th>";
                lista += "<th scope='col' class='text-center'>CN ATUAL</th>";
                lista += "<th scope='col' class='text-center'>ACEITAR <i class='icon-ok-circled2'></i></th>";
                lista += "</tr>";
                lista += "</thead>";
                lista += "<tbody>";
                for (var i = 0; i < linhas.length; i++) {

                    var coordenador = linhas[i].coordenador.split(' ');

                    lista += "<tr id='linhaTrans" + linhas[i].cod + "'>";
                    lista += "<td class='text-center'>" + linhas[i].identificacao + "</td>";
                    lista += "<td class='text-center'>" + coordenador[0] + "</td>";
                    lista += "<td class='text-center'>" + linhas[i].cn + "</td>";
                    lista += "<td class='text-center'><button class='btAceitaGmg btn btn-info btn-sm mr-1' value='" + linhas[i].cod + "'><i class='icon-ok-circled2'></i> SIM</button>";
                    lista += "<button class='btRecusaGmg btn btn-danger btn-sm ml-1' value='" + linhas[i].cod + "'><i class='icon-minus-circle'></i> NÃO</button></td>";
                    lista += "</tr>";
                }
                lista += "</tbody>";
                lista += "</table></div>";
                $("#gmgTransferenciaLista").slideDown("fast").html(lista);

                aceitaGmg();
                recusaGmg();
            }
        }
    });
}
function aceitaGmg() {

    $(".btAceitaGmg").click(function () {
        var cod = $(this).attr('value');

        $("#ModalRetornoTransferencia").text("");
        $("#ModalRetornoTransferencia").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "transfereGmg", tipo: "aceita", cod: cod },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var msg = dados.msg;

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetornoTransferencia").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");

                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetornoTransferencia").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        $("#linhaTrans" + cod).slideUp("fast");
                    });
                }
            }
        });
    });
}
function recusaGmg() {

    $(".btRecusaGmg").click(function () {
        var cod = $(this).attr('value');

        $("#ModalRetornoTransferencia").text("");
        $("#ModalRetornoTransferencia").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "transfereGmg", tipo: "recusa", cod: cod },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var msg = "";

                if (dados.erro === "1") {

                    msg = "<i class='icon-attention-2'></i> " + dados.msg;

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetornoTransferencia").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");

                } else {
                    msg = "<i class='icon-ok-circled2'></i> " + dados.msg;
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetornoTransferencia").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        $("#linhaTrans" + cod).slideUp("fast");
                    });
                }
            }
        });
    });
}
function update() {

    var id = $("#id").text();
    var nome = $("#nome").val();
    var cn = $("#n_cn").val();
    var fabricante = $("#fabricante").val();
    var tipo = $("#tipo").val();
    var kva = $("#kva").val();
    var status = $("#status").val();

    $.ajax({
        type: 'post', //Definimos o método HTTP usado
        data: {
            acao: "update",
            id: id,
            nome: nome,
            cn: cn,
            fabricante: fabricante,
            tipo: tipo,
            kva: kva,
            status: status
        },
        dataType: 'json', //Definimos o tipo de retorno
        url: 'scGMG', //Definindo o arquivo onde serão buscados os dados
        success: function (dados) {

            if (dados.erro === "1") {

                var tempo = 1500;
                var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                var msg = "<i class='icon-attention'></i> ".dados.msg;

                $("#ModalRetorno_edita").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");
            } else {
                var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";
                var msg = "<i class='icon-ok-circle-1'></i>  " + dados.msg;

                $("#ModalRetorno_edita").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("slow", function () {
                    window.location.replace("GMG");
                });
            }
        }
    });
}
}
);
