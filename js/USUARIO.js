$(document).ready(function () {

    var isMobile = false;
    if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) ||
        /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) {
        isMobile = true;
    }

    $("#site_formulario1").slideDown("fast");
    verifica_permissao();
    form_cn();
    form_coordenador("editaCoordenador");
    form_cargo();
    form_gestao();
    form_estado();
    form_tipo();
    $("#formPermissaoTipo").change(function () {

        var p = $("#formPermissaoTipo").val();

        if (p === "1") {

            $("#formPermissaoFuncao").slideUp("fast", function () {
                $("#formPermissaoPagina").slideDown("fast");
                form_pagina();
            });

        } else {
            $("#formPermissaoPagina").slideUp("fast", function () {
                $("#formPermissaoFuncao").slideDown("fast");
                form_funcao();
            });
        }
    });

    $("#btFiltra").click(function () {

        var txt = $("#usrTXT").val();
        var cn = $("#usrCn").val();

        $("#retornoUsuario").text("");
        $("#retornoUsuario").removeClass();

        var tempo = 1500;
        var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
        var msg = "<i class='icon-attention'></i> Informações insuficientes!";


        $("#ListaUSUARIO").slideUp("fast");
        if (txt === "" && cn === "0") {
            $("#retornoUsuario").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

        } else {
            UsuarioProcura(txt, cn, isMobile);
        }
    });

    $("#btFormNovo").click(function () {
        $("#site_formulario1").slideUp("fast", function () {

            $("#usr_formulario_novo").slideDown("fast");
            $("#usr_formulario_novo").modal("show");
        });

        $("#usr_re").val("");
        $("#usr_nome").val("");
        $("#usr_estado").val();
        $("#usr_email_cad").val("");
        $("#usr_telefone").val("");

        form_cn();
        form_coordenador("usr_coordenador");
    });
    $("#btFormTrans").click(function () {

        $("#ModalRetornoTransferencia").text("");
        $("#ModalRetornoTransferencia").removeClass();

        $("#Modal_transferencia").modal("show");
        form_transferecnia();
    });
    $("#bt_cadastro_USUARIO").click(function () {

        $("#ModalRetorno_cadastro").text("");
        $("#ModalRetorno_cadastro").removeClass();

        cadastro();
    });

    $("#bt_edita_USUARIO").click(function () {

        $("#ModalRetorno_edita").text("");
        $("#ModalRetorno_edita").removeClass();
        edita();
    });

    $("#btCartaoAltera").click(function () {

        $("#ModalRetorno_cartao").text("");
        $("#ModalRetorno_cartao").removeClass();

        cartaoAltera();
    });
    $("#btCartaoAtribui").click(function () {

        $("#ModalRetorno_cartao").text("");
        $("#ModalRetorno_cartao").removeClass();

        cartaoAtribui();
    });
    $("#btCartaoRemove").click(function () {

        $("#ModalRetorno_cartao").text("");
        $("#ModalRetorno_cartao").removeClass();

        cartaoRemove();
    });
    $("#btCartaoDesbloqueio").click(function () {

        $("#ModalRetorno_cartao").text("");
        $("#ModalRetorno_cartao").removeClass();

        cartaoDesbloqueio();
    });
    $("#btFrotaAlteraKm").click(function () {

        $("#ModalRetorno_frota").text("");
        $("#ModalRetorno_frota").removeClass();

        frotaAlteraKm();
    });

    $("#btFrotaRemove").click(function () {

        $("#ModalRetorno_frota").text("");
        $("#ModalRetorno_frota").removeClass();

        frotaRemove();
    });
    $("#btFrotaAtribui").click(function () {

        $("#ModalRetorno_frota").text("");
        $("#ModalRetorno_frota").removeClass();

        frotaAtribui();
    });
    $("#bt_cadastro_USUARIO_voltar").click(function () {

        cadastro_USUARIO_voltar();
    });

    $("#senhaEditaConfirma").click(function () {

        var re = $("#modalSenha_re").text();

        confirmaEditaSenha(re);
    });

    $("#btfrotaPesquisa").click(function () {

        var txt = $("#frotaTXT").val();
        var frotaAtual = $("#frotaAtual").text();

        $("#rowFrotaResult").slideUp("fast");
        $("#rowFrotaNovo").slideUp("fast");

        frota_pesquisa(txt, frotaAtual);

    });
    $("#btfrotaNova").click(function () {

        $("#rowFrotaResult").slideUp("fast", function () {
            $("#listaFrotaPesquisa").slideUp("fast");
            $("#frotaDados").text("");
            $("#frotaPlaca").text("");
            $("#rowFrotaNovo").slideDown("fast");

            veiculo_lista();
        });
    });
    $("#btFechaFrota").click(function () {

        $("#rowFrotaResult").slideUp("fast", function () {
            $("#listaFrotaPesquisa").slideUp("fast");
            $("#frotaDados").text("");
            $("#frotaPlaca").text("");
        });
    });
    $("#btfrotaFormCadastra").click(function () {

        var re = $("#frotaAtualRe").text();
        var frotaNova = $("#formFrotaNovoPlaca").val();
        var frotaAtual = $("#frotaAtual").text();
        var veiculo = $("#formFrotaModelo").val();
        var km = $("#formFrotaKm").val();

        frotaCadastro(re, frotaAtual, frotaNova, veiculo, km);

    });

    function formEdita() {

        $(".btEditaUsuario").click(function () {
            var re = $(this).attr('value');

            usuario_dados(re);

            $("#Modal_edita").modal("show");

        });
    }

    $("#bt_modal_permissao").click(function () {

        $("#Modal_permissao").modal("show");

        var u = $("#editaRe").val();
        form_permissao(u);

    });
    $("#formPermissaoPagina").change(function () {

        var p = $("#formPermissaoPagina").val();

        if (p > 0) {
            $("#div_bt_add").slideDown("fast");
        }
    });
    $("#formPermissaoFuncao").change(function () {

        var p = $("#formPermissaoFuncao").val();

        if (p > 0) {
            $("#div_bt_add").slideDown("fast");
        }
    });
    $("#formPermissaoADD").click(function () {
        var tipo = $("#formPermissaoTipo").val();
        var funcao = $("#formPermissaoFuncao").val();
        var pagina = $("#formPermissaoPagina").val();
        var usuario = $("#editaRe").val();

        form_add_permissao(tipo, funcao, pagina, usuario);

    });
    function form_add_permissao(tipo, funcao, pagina, usuario) {

        $("#retornoPermissao").text("");
        $("#retornoPermissao").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "PermissaoAdd", t: tipo, f: funcao, p: pagina, u: usuario },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PermissaoSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                if (dados.erro === "1") {
                    var msg = dados.msg;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoPermissao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");
                } else {
                    var msg = dados.msg;
                    var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoPermissao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        var u = $("#editaRe").val();
                        form_permissao(u);
                    });
                }
            }
        });

    }
    function verifica_permissao() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "verificaPermissao" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PermissaoSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.permissao != 0) {

                    $("#bt_modal_permissao").attr("disabled", false);
                } else {
                    $("#bt_modal_permissao").attr("disabled", true);

                }
            }
        });
    }
    function form_permissao(u) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "PermissaoLista", u: u },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PermissaoSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

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

                        linhas += "<button value=" + linha[i].id + " class='bt_removePermissao btn btn-sm btn-light border ml-1 mt-1 text-muted'><i class='icon-trash-4 text-danger'></i> " + nome + "</button>";
                    }
                    $("#Permissoes").slideDown("fast").html(linhas);
                }
                form_remove_permissao();
            }
        });
    }
    function form_pagina() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "PermissaoListaPagina" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PermissaoSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
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
            success: function (dados) {
                var linhas = "<option value=\"0\">SELECIONE UMA FUNCIONADLIDADE</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].sub + "-" + linha[i].nome + "</option>";
                }
                $("#formPermissaoFuncao").slideDown("fast").html(linhas);

            }
        });
    }
    function form_tipo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "PermissaoListaTipo" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PermissaoSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = "<option value=\"0\">SELECIONE UM TIPO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#formPermissaoTipo").slideDown("fast").html(linhas);
            }
        });
    }
    function form_remove_permissao() {

        $(".bt_removePermissao").click(function () {

            $("#retornoPermissao").text("");
            $("#retornoPermissao").removeClass();

            var permissao = $(this).attr('value');

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "PermissaoRemove", permissao: permissao },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'PermissaoSC', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {
                    if (dados.erro === "1") {
                        var msg = dados.msg;
                        var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                        $("#retornoPermissao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");
                    } else {
                        var msg = dados.msg;
                        var classe = "bg-success rounded font-weight-bold text-white pt-2 pb-2";

                        $("#retornoPermissao").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                            var u = $("#editaRe").val();
                            form_permissao(u);
                        });
                    }
                }
            });
        });
    }

    function formEditaCartao() {

        $(".btCartao").click(function () {
            var re = $(this).attr('value');

            $("#btfrotaFormNovo").slideDown("fast");

            $("#ListaUSUARIO").slideUp("fast");

            cartao_dados(re);

            $("#Modal_cartao").modal("show");
            $("#btCartaoRemove").removeClass("d-none");

        });
    }

    function formEditaFrota() {

        $(".btFrota").click(function () {
            var re = $(this).attr('value');

            $("#btfrotaFormNovo").slideDown("fast");
            $(".frotaFormNovo").addClass("d-none");
            $("#ListaUSUARIO").slideUp("fast");

            $("#rowFrotaResult").slideUp("fast");
            $("#rowFrotaNovo").slideUp("fast");

            frota_dados(re);

            $("#Modal_frota").modal("show");

        });
    }

    function frota_dados(re) {

        $("#frotaAtual").text("");
        $("#frotaAtualKm").text("");
        $("#frotaNovoKm").val("");
        $("#frotaAtualColaborador").text("");
        $("#frotaAtualRe").text("");
        $("#frotaNovo").val("");
        $("#frotaMotivoTroca").val("");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "dadosFrota", re: re },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var frota = dados.frota;

                $("#frotaAtual").append(frota.placa);
                $("#frotaAtualColaborador").append(frota.nome);
                $("#frotaAtualRe").append(frota.re);
                $("#frotaAtualKm").append(frota.km);

                //  frota_pesquisa();

                if (dados.permissao != 0) {
                    $(".frotaFerramenta").removeClass("d-none");
                }
            }
        });
    }

    function cartao_dados(re) {

        $("#cartaoAtual").text("");
        $("#cartaoAtualColaborador").text("");
        $("#cartaoAtualRe").text("");
        $("#cartaoNovo").val("");
        $("#cartaoMotivoTroca").val("");

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "dadosCartao", re: re },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var cartao = dados.cartao;

                $("#cartaoAtual").append(cartao.cartao);
                $("#cartaoAtualColaborador").append(cartao.nome);
                $("#cartaoAtualRe").append(cartao.re);

                cartao_lista();

                if (dados.permissao1 > 0) {
                    $("#cartaoFerramenta").slideDown("fast");
                }
                if (dados.permissao2 > 0) {
                    $("#cartaoDesbloqueio").slideDown("fast");
                }
            }
        });
    }

    function cartao_lista() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cartaoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
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

    function form_transferecnia() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "transferenciaLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                if (dados.length === 0) {

                    var tempo = 1500;
                    var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    var msg = "<i class='icon-attention-2'></i> Nenhum colaborador transferido.";

                    $("#ModalRetornoTransferencia").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                } else {
                    var linhas = eval(dados);

                    var lista = "";
                    lista += "<div class='card border'>";
                    lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                    lista += "<thead class='bg bg-light text-muted'>";
                    lista += "<tr>";
                    lista += "<th scope='col' class='text-center'>COLABORADOR</th>";
                    lista += "<th scope='col' class='text-center'>COORDENADOR ATUAL</th>";
                    lista += "<th scope='col' class='text-center'>CN ATUAL</th>";
                    lista += "<th scope='col' class='text-center'>ACEITAR <i class='icon-ok-circled2'></i></th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody class='table table-striped table-hover'>";
                    for (var i = 0; i < linhas.length; i++) {

                        var colaborador = linhas[i].nome.split(' ');
                        var coordenador = linhas[i].coordenador.split(' ');

                        lista += "<tr id='linhaTrans" + linhas[i].re + "'>";
                        lista += "<td class='text-center'>" + colaborador[0] + " - RE:" + linhas[i].re + "</td>";
                        lista += "<td class='text-center'>" + coordenador[0] + "</td>";
                        lista += "<td class='text-center'>" + linhas[i].cn + "</td>";
                        lista += "<td class='text-center'><button class='btAceitaColaborador btn btn-info btn-sm mr-1' value='" + linhas[i].re + "'><i class='icon-ok-circled2'></i> SIM</button>";
                        lista += "<button class='btRecusaColaborador btn btn-danger btn-sm ml-1' value='" + linhas[i].re + "'><i class='icon-minus-circle'></i> NÃO</button></td>";
                        lista += "</tr>";
                    }
                    lista += "</tbody>";
                    lista += "</table></div>";
                    $("#colaboradorTransferenciaLista").slideDown("fast").html(lista);

                    aceitaColaborador();
                    recusaColaborador();
                }
            }
        });
    }

    function aceitaColaborador() {

        $(".btAceitaColaborador").click(function () {
            var colaborador = $(this).attr('value');

            $("#ModalRetornoTransferencia").text("");
            $("#ModalRetornoTransferencia").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "transfereColaborador", tipo: "aceita", colaborador: colaborador },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    var msg = dados.msg;

                    if (dados.erro === "1") {

                        var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                        $("#ModalRetornoTransferencia").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");

                    } else {
                        var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                        $("#ModalRetornoTransferencia").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                            $("#linhaTrans" + colaborador).slideUp("fast");
                        });
                    }
                }
            });
        });
    }

    function recusaColaborador() {

        $(".btRecusaColaborador").click(function () {
            var colaborador = $(this).attr('value');

            $("#ModalRetornoTransferencia").text("");
            $("#ModalRetornoTransferencia").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "transfereColaborador", tipo: "recusa", colaborador: colaborador },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
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

                            $("#linhaTrans" + colaborador).slideUp("fast");
                        });
                    }
                }
            });
        });
    }

    function frota_pesquisa(txt, frotaAtual) {

        $("#listaFrotaPesquisa").slideUp("fast", function () {
            $("#listaFrotaPesquisa").removeClass("d-none");

            $("#ModalRetorno_frota").slideUp("fast");
            $("#ModalRetorno_frota").text("");
            $("#ModalRetorno_frota").removeClass();

            if (txt.length === 0) {

                var tempo = 1500;
                var classe = "bg-danger rounded font-weight-bold text-white p-2";
                var msg = "<i class='icon-attention-2'></i> Necessário preencher o campo de busca.";

                $("#ModalRetorno_frota").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

            } else {
                $.ajax({
                    type: 'post', //Definimos o método HTTP usado
                    data: { acao: "FrotaProcura", txt: txt, frotaAtual: frotaAtual },
                    dataType: 'json', //Definimos o tipo de retorno
                    url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
                    success: function (dados) {
                        if (dados.length === 0) {

                            var tempo = 1500;
                            var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                            var msg = "<i class='icon-attention-2'></i> Nenhum resultado encontrado";

                            $("#ModalRetorno_frota").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                        } else {
                            var linhas = eval(dados);

                            var lista = "";
                            lista += "<div class='card border'>";
                            lista += "<div class='card-header text-muted bg-light font-weight-bold'>Resultado da busca</div>";
                            lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                            lista += "<thead class='bg bg-light text-muted'>";
                            lista += "<tr>";
                            lista += "<th scope='col' class='text-center'>PLACA</th>";
                            lista += "<th scope='col' class='text-center'>VEÍCULO</th>";
                            lista += "<th scope='col' class='text-center'>COLABORADOR ATUAL</th>";
                            lista += "<th scope='col' class='text-center'>SELECIONE <i class='icon-target-2'></i></th>";
                            lista += "</tr>";
                            lista += "</thead>";
                            lista += "<tbody class='table table-striped table-hover'>";
                            for (var i = 0; i < linhas.length; i++) {

                                lista += "<tr id='linha" + linhas[i].id + "'>";
                                lista += "<td class='text-center'>" + linhas[i].placa + "</td>";
                                lista += "<td class='text-center'>" + linhas[i].marca + " " + linhas[i].modelo + "</td>";

                                if (linhas[i].nome === "DISPONÍVEL") {
                                    lista += "<td class='text-center text-success'>" + linhas[i].nome + "</td>";
                                    lista += "<td class='text-center'><button class='btSelecionaFrota btn btn-success btn-sm' value='" + linhas[i].placa + "'>Selecionar <i class='icon-target-2'></i></button></td>";
                                } else {
                                    lista += "<td class='text-center text-muted'>" + linhas[i].nome + "</td>";
                                    lista += "<td class='text-center'><button class='btSelecionaFrota btn btn-secondary btn-sm' value='" + linhas[i].placa + "'>Selecionar <i class='icon-target-2'></i></button></td>";
                                }

                                lista += "</tr>";
                            }
                            lista += "</tbody>";
                            lista += "</table></div>";
                            $("#listaFrotaPesquisa").slideDown("fast").html(lista);

                            SelecionaFrota();
                        }
                    }
                });
            }
        });
    }

    function SelecionaFrota() {

        $(".btSelecionaFrota").click(function () {

            $("#rowFrotaResult").slideUp("fast");
            var frota = $(this).attr('value');

            $("#frotaDados").text("");
            $("#frotaPlaca").text("");
            $("#frotaNome").text("");
            $("#frotaCoordenador").text("");
            $("#frotaAtribui").text("");

            $(".rowFrota").removeClass("d-none");

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "frotaSeleciona", frota: frota },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    var colaboradorAtual = $("#frotaAtualColaborador").text();
                    $("#listaFrotaPesquisa").slideUp("fast");

                    $("#rowFrotaResult").slideDown("fast");
                    $("#frotaDados").append(dados.veiculo);
                    $("#frotaPlaca").append(dados.placa);
                    $("#frotaNome").append(dados.nome);
                    $("#frotaCoordenador").append(dados.coordenador);
                }
            });
        });
    }

    function frotaCadastro(re, frotaAtual, frotaNova, veiculo, km) {

        $("#ModalRetorno_frota").text("");
        $("#ModalRetorno_frota").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cadastroFrota", re: re, frotaAtual: frotaAtual, frotaNova: frotaNova, veiculo: veiculo, km: km },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var msg = dados.msg;

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");

                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        $("#Modal_frota").modal("hide");
                        window.location.replace("USUARIO");
                    });
                }
            }
        });
    }

    function formResetaSenha() {

        $(".btEditaSenha").click(function () {
            var re = $(this).attr('value');


            var nome = $("#listaNome" + re).text().split(" [");
            $("#modalSenha_nome").text(nome[0]);
            $("#modalSenha_re").text(re);
            usuario_email(re);

            $("#Modal_senha").modal("show");
        });
    }

    function veiculo_lista() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "veiculoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linha = eval(dados);

                var linhas = "<option valur='0'>VEÍCULO</option>";
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=" + linha[i].id + ">" + linha[i].veiculo + "</option>";
                }
                $("#formFrotaModelo").html(linhas);
            }
        });
    }

    function usuario_email(re) {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "dados", re: re },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                $("#modalSenha_email").text("");
                $("#modalSenha_email").text(dados.email);

                if (dados.email === "") {
                    $("#modalSenha_email").append("<i class='icon-attention'></i> Sem e-mail cadastrado.");

                    $("#senhaEditaConfirma").attr("disabled", true);
                } else {
                    $("#senhaEditaConfirma").attr("disabled", false);
                }
            }
        });
    }

    function confirmaEditaSenha(re) {

        $("#ModalRetornoEditaSenha").text("");
        $("#ModalRetornoEditaSenha").removeClass();
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "resetaSenha", re: re },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'ResetaSenhaSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetornoEditaSenha").slideDown("fast").addClass(classe).append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ListaUSUARIO").slideUp("fast", function () {
                        $("#ModalRetornoEditaSenha").slideDown("fast").addClass(classe).append(dados.msg).delay(1500).slideUp("fast", function () {

                            $("#modalSenha_re").text("").fadeOut("fast");
                            $("#modalSenha_nome").text("").fadeOut("fast");

                            $("#Modal_senha").modal("hide");

                            location.reload();
                        });
                    });
                }
            }
        });
    }

    function usuario_dados(re) {
        var retorno = "";
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "dados", re: re },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                dados_select("Cargo", dados.cargo);
                dados_select("Coordenador", dados.coordenador);
                dados_select("CN", dados.cn);
                dados_select("Acesso", dados.sistema);
                dados_select("Ativo", dados.ativo);
                dados_select("Estado", dados.estado);
                dados_select("Gestao", dados.gestao);

                $("#editaRe").val(dados.re);
                $("#editaNome").val(dados.nome);
                $("#editaEmail").val(dados.email);
                $("#editaTelefone").val(dados.telefone);
                $("#editaSenha").val("");
            }
        });
    }

    function dados_select(tipo, select) {

        var acao = "detalhe_" + tipo;
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: acao },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "";
                var linha = eval(dados);

                if (tipo === "Coordenador") {
                    linhas += "<option value=\"0\">Selecione o " + tipo + "</option>";
                    for (var i = 0; i < linha.length; i++) {
                        linhas += "<option value=\"" + linha[i].re + "\">" + linha[i].nome + "</option>";
                    }
                } else if (tipo === "Ativo") {
                    linhas += "<option value=\"0\">" + tipo.toUpperCase() + "</option>";
                    for (var i = 0; i < linha.length; i++) {
                        linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                    }
                } else if (tipo === "Acesso") {
                    linhas += "<option value=\"0\">" + tipo.toUpperCase() + "</option>";
                    for (var i = 0; i < linha.length; i++) {
                        linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                    }
                } else if (tipo === "Gestao") {
                    linhas += "<option value=\"0\">" + tipo.toUpperCase() + "</option>";
                    for (var i = 0; i < linha.length; i++) {
                        linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                    }
                } else {
                    linhas += "<option value=\"0\">" + tipo.toUpperCase() + "</option>";
                    for (var i = 0; i < linha.length; i++) {
                        linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                    }
                }

                $("#edita" + tipo).slideDown("fast").html(linhas);

                var opt = "option[value=" + select + "]";
                $("#edita" + tipo).find(opt).attr("selected", "selected");
            }
        });
    }

    function cadastro_USUARIO_voltar() {

        $("#usr_formulario_novo").slideUp("fast", function () {

            window.location.replace("USUARIO");
        });
    }

    function form_cn() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cnLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">SELECIONE UM CN</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#usrCn").slideDown("fast").html(linhas);
                $("#usr_cn").slideDown("fast").html(linhas);
            }
        });
    }

    function form_coordenador(input) {
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "coordenadorLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">SELECIONE UM COORDENADOR</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].re + "\">" + linha[i].nome + "</option>";
                }
                $("#" + input).slideDown("fast").html(linhas);
            }
        });
    }

    function form_cargo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cargoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">SELECIONE UM CARGO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#usr_cargo").slideDown("fast").html(linhas);
            }
        });
    }
    function form_gestao() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "gestaoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">SELECIONE A GESTÃO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#usr_gestao").slideDown("fast").html(linhas);
            }
        });
    }

    function form_estado() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "estadoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">SELECIONE O Estado</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#usr_estado").slideDown("fast").html(linhas);
            }
        });
    }

    function UsuarioProcura(txt, cn, isMobile) {

        $("#retornoUsuario").text("");
        $("#retornoUsuario").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "USUARIOProcura", txt: txt, cn: cn },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var verifica = eval(dados);

                if (verifica.length === 0) {
                    $("#ListaUSUARIO").slideUp("fast").html("");

                    var tempo = 1500;
                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    var msg = "<i class='icon-attention'></i> Nenhuma correspondência!";

                    $("#retornoUsuario").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                } else {
                    var linhas = eval(dados);

                    if (isMobile) {

                        var lista = "";
                        for (var i = 0; i < linhas.length; i++) {
                            var status = "";
                            if (linhas[i].ativo === "NÃO") {
                                status = "<i class='icon-cancel-circled2 text-danger'></i> <span class='text-muted'><small>INATIVO</small></span>";
                            } else {
                                status = "<i class='icon-ok-circled2 text-success'></i> <span class='text-muted'><small>ATIVO</small></span>";
                            }
                            var coor = linhas[i].coordenador.split(" ");

                            lista += "<div class='col'>";
                            lista += "<div class='card bg-light mb-2' style='max-width: 18rem;'>";
                            lista += "<div class='card-header'><span class='text-muted font-weight-bold'><small>RE: " + linhas[i].re + "</small></span></div>";
                            lista += "<div class='card-body'>";
                            lista += "<ul class='list-group list'>";
                            lista += "<li id='' class='list-group-item text-left'><i class='icon-user-1'></i> <span class='text-muted'><small>" + linhas[i].nome + "</small></span></li>";
                            lista += "<li id='' class='list-group-item text-left'>" + status + "</li>";
                            lista += "<li class='list-group-item text-left'><i class=' icon-steering-wheel'></i><button class='btFrota btn btn-light btn-sm border text-muted' value=" + linhas[i].re + ">" + linhas[i].veiculo + "</button></li>";
                            lista += "<li class='list-group-item text-left'><i class=' icon-credit-card'></i><button class='btCartao btn btn-light btn-sm border text-muted' value=" + linhas[i].re + ">" + linhas[i].cartao + "</button></li>";
                            lista += "<li id='' class='list-group-item text-left'><i class='icon-location text-danger'></i> <span class='text-muted'><small>" + linhas[i].cn + "</small></span></li>";
                            lista += "<li class='list-group-item text-left'><i class='icon-group'></i><span class='text-muted'><small>" + coor[0] + " " + coor[1] + "</small></span></li>";
                            lista += "</ul>";
                            lista += "</div>";

                            lista += "<div class='card-footer text-muted'>";
                            lista += "<div class='row'>";
                            lista += "<div class='col'>";
                            lista += "<button value='" + linhas[i].re + "' class='btEditaUsuario btn btn-light btn-sm border text-muted'><i class='icon-edit text-info'></i> Editar</button>";
                            lista += "</div>";
                            lista += "<div class='col'>";
                            lista += "<button value='" + linhas[i].re + "' class='btEditaSenha btn btn-light btn-sm border text-muted'><i class='icon-key-outline text-danger'></i> Resetar Senha</button>";
                            lista += "</div>";
                            lista += "</div>";
                            lista += "</div>";

                            lista += "</div>";
                            lista += "</div>";
                        }
                    } else {
                        var lista = "";
                        lista += "<div class='card border-light p-1'>";
                        lista += "<div class='card-header fw-bold'>Resultado da busca</div>";
                        lista += "<table class='table table-striped table-sm w-auto'>";
                        lista += "<thead class='thead-dark'>";
                        lista += "<tr class='text-center'>";
                        lista += "<th class='text-left' scope='col'>COLABORADOR</th>";
                        lista += "<th scope='col'>ATIVO</th>";
                        lista += "<th scope='col'>VEÍCULO</th>";
                        lista += "<th scope='col'>CARTÃO</th>";
                        lista += "<th scope='col'>COORDENADOR</th>";
                        lista += "<th scope='col'>CN</th>";
                        lista += "<th scope='col'>RESETA SENHA</th>";
                        lista += "<th scope='col'>EDITAR</th>";
                        lista += "</tr>";
                        lista += "</thead>";
                        lista += "<tbody>";
                        for (var i = 0; i < linhas.length; i++) {
                            lista += "<tr class='text-center'>";
                            lista += "<td class='text-left' id='listaNome" + linhas[i].re + "'>" + linhas[i].nome + " [" + linhas[i].re + "]</td>";
                            lista += "<td>" + linhas[i].ativo + "</td>";
                            lista += "<td id='listaVeiculo" + linhas[i].re + "'><button class='btFrota btn btn-primary btn-sm d-block' value=" + linhas[i].re + ">" + linhas[i].veiculo + "</button></td>";
                            lista += "<td id='listaCartao" + linhas[i].re + "'><button class='btCartao btn btn-primary btn-sm d-block' value=" + linhas[i].re + ">" + linhas[i].cartao + "</button></td>";
                            coor = linhas[i].coordenador.split(" ");
                            lista += "<td>" + coor[0] + "</td>";
                            lista += "<td>" + linhas[i].cn + "</td>";
                            lista += "<td><button class='btEditaSenha btn btn-primary btn-sm d-block' value='" + linhas[i].re + "'>Resetar</button></td>";
                            lista += "<td><button class='btEditaUsuario btn btn-primary btn-sm d-block' value='" + linhas[i].re + "'>Editar</button></td>";
                            lista += "</tr>";
                        }
                        lista += "</tbody>";
                        lista += "</table>";
                    }
                    $("#ListaUSUARIO").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);

                    formEditaFrota();
                    formEditaCartao();
                    formEdita();
                    formResetaSenha();
                }
            }
        });
    }

    function cadastro() {

        var delay = 2000;

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: {
                acao: "cadastroUSUARIO",
                re: $("#usr_re").val(),
                nome: $("#usr_nome").val(),
                estado: $("#usr_estado").val(),
                email: $("#usr_email_cad").val(),
                telefone: $("#usr_telefone").val(),
                cargo: $("#usr_cargo").val(),
                gestao: $("#usr_gestao").val(),
                combustivel: $("#usr_combustivel").val(),
                cartao: $("#usr_cartao").val(),
                coordenador: $("#usr_coordenador").val(),
                cn: $("#usr_cn").val(),
                acesso: $("#usr_acesso").val(),
                ativo: $("#usr_ativo").val()
            },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            beforeSend: function () {
                $("#bt_cadastro_USUARIO").attr("disabled", true);

                var classe = "bg-info rounded font-weight-bold text-white pt-2 pb-2";
                var ico = "<i class='icon-clock'></i>";
                var msg = "Aguarde...";
                $("#ModalRetorno_cadastro").slideDown("fast").addClass(classe).append(ico + " " + msg);

            },
            success: function (dados) {

                setTimeout(function () {
                    var tempo = 1500;

                    if (dados.erro === "1") {

                        $("#ModalRetorno_cadastro").text("");
                        $("#ModalRetorno_cadastro").removeClass();

                        var msg = "<i class='icon-attention'></i> " + dados.msg;
                        var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";

                        if (dados.tempo > 0) {
                            tempo = dados.tempo;
                        }

                        $("#ModalRetorno_cadastro").empty().slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast", function () {

                            if (msg.indexOf('permissão') === 44) {
                                $("#usr_formulario_novo").modal("hide");
                                window.location.replace("USUARIO");
                            }
                            $("#bt_cadastro_USUARIO").attr("disabled", false);
                        });
                    } else {

                        $("#ModalRetorno_cadastro").text("");
                        $("#ModalRetorno_cadastro").removeClass();

                        var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                        var msg = "<i class='icon-ok-circle-1'></i> " + dados.msg;

                        $("#ModalRetorno_cadastro").empty().slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast", function () {

                            $("#usr_formulario_novo").modal("hide");
                            window.location.replace("USUARIO");

                            $("#bt_cadastro_USUARIO").attr("disabled", false);
                        });
                    }
                }, delay);
            }
        });
    }

    function edita() {

        var re = $("#editaRe").val();
        var nome = $("#editaNome").val();
        var estado = $("#editaEstado").val();
        var email = $("#editaEmail").val();
        var telefone = $("#editaTelefone").val();
        var cargo = $("#editaCargo").val();
        var coordenador = $("#editaCoordenador").val();
        var gestao = $("#editaGestao").val();
        var cn = $("#editaCN").val();
        var acesso = $("#editaAcesso").val();
        var ativo = $("#editaAtivo").val();
        var senha = $("#editaSenha").val();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "editaUSUARIO", re: re, nome: nome, estado: estado, email: email, telefone: telefone, cargo: cargo, coordenador: coordenador, gestao: gestao, cn: cn, acesso: acesso, ativo: ativo, senha: senha },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.erro === "1") {
                    var ico = "<i class='bi bi-exclamation-circle'></i> ";
                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    var retorno = ico + dados.msg;
                    $("#ModalRetorno_edita").empty().slideDown("fast").addClass(classe).append(retorno).delay(2000).slideUp("fast");
                } else {
                    var ico = "<i class='bi bi-check-circle'></i> ";
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";
                    var retorno = ico + dados.msg;
                    $("#ModalRetorno_edita").empty().slideDown("fast").addClass(classe).append(retorno).delay(1200).slideUp("fast", function () {

                        //                        $(".statusLn" + solicitacao).text("CONCLUÍDO");
                        $("#Modal_edita").modal("hide");
                        UsuarioProcura($("#usrTXT").val(), $("#usrCn").val(), isMobile);
                    });
                }
            }
        });
    }

    function cartaoAltera() {

        var re = $("#cartaoAtualRe").text();
        var cartaoAtual = $("#cartaoAtual").text();
        var cartaoNovo = $("#cartaoNovo").val();
        var cartaoMotivo = $("#cartaoMotivoTroca").val();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cartaoAltera", re: re, cartaoAtual: cartaoAtual, cartaoNovo: cartaoNovo, cartaoMotivo: cartaoMotivo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
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

        var re = $("#cartaoAtualRe").text();
        var cartaoAtual = $("#cartaoAtual").text();
        var cartaoNovo = $("#cartaoNovoselect").val();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cartaoAtribui", re: re, cartaoAtual: cartaoAtual, cartaoNovo: cartaoNovo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
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

    function cartaoDesbloqueio() {

        var re = $("#cartaoAtualRe").text();
        var cartaoAtual = $("#cartaoAtual").text();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cartaoDesbloqueio", re: re, cartaoAtual: cartaoAtual },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
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

    function cartaoRemove() {

        var re = $("#cartaoAtualRe").text();
        var cartaoAtual = $("#cartaoAtual").text();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cartaoRemove", re: re, cartaoAtual: cartaoAtual },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
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

    function frotaAlteraKm() {

        var re = $("#frotaAtualRe").text();
        var frota = $("#frotaAtual").text();
        var kmAtual = $("#frotaAtualKm").text();
        var kmNovo = $("#frotaNovoKm").val();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "frotaAlteraKm", re: re, frota: frota, kmAtual: kmAtual, kmNovo: kmNovo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(2000).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1200).slideUp("fast", function () {

                        $("#Modal_frota").modal("hide");
                    });
                }
            }
        });
    }

    function frotaAtribui() {

        var re = $("#frotaAtualRe").text();
        var atual = $("#frotaAtual").text();
        var novo = $("#frotaPlaca").text();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "frotaAtribui", re: re, atual: atual, novo: novo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.erro === "1") {
                    var tempo;
                    if (dados.tempo != 0) {
                        tempo = dados.tempo;
                    } else {
                        tempo = 2000;
                    }

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(2000).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1200).slideUp("fast", function () {

                        $("#Modal_frota").modal("hide");
                        window.location.replace("USUARIO");
                    });
                }
            }
        });
    }

    function frotaRemove() {

        var re = $("#frotaAtualRe").text();
        var frotaAtual = $("#frotaAtual").text();
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "frotaRemove", re: re, frotaAtual: frotaAtual },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(2000).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1200).slideUp("fast", function () {

                        $("#Modal_frota").modal("hide");
                    });
                }
            }
        });
    }
});