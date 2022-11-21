$(document).ready(function() {

    $("#site_formulario1").slideDown("fast");
    form_cn();
    form_coordenador("editaCoordenador");
    form_cargo();
    form_estado();

    $("#btFiltra").click(function() {

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
            UsuarioProcura(txt, cn);
        }
    });

    $("#btFormNovo").click(function() {
        $("#site_formulario1").slideUp("fast", function() {

            $("#usr_formulario_novo").slideDown("fast");
            $("#usr_formulario_novo").modal("show");
        });
        form_cn();
        form_coordenador("usr_coordenador");
    });
    $("#btFormTrans").click(function() {

        $("#ModalRetornoTransferencia").text("");
        $("#ModalRetornoTransferencia").removeClass();

        $("#Modal_transferencia").modal("show");
        form_transferecnia();
    });
    $("#bt_cadastro_USUARIO").click(function() {

        $("#ModalRetorno_cadastro").text("");
        $("#ModalRetorno_cadastro").removeClass();

        cadastro();
    });

    $("#bt_edita_USUARIO").click(function() {

        $("#ModalRetorno_edita").text("");
        $("#ModalRetorno_edita").removeClass();
        edita();
    });

    $("#btCartaoAltera").click(function() {

        $("#ModalRetorno_cartao").text("");
        $("#ModalRetorno_cartao").removeClass();

        cartaoAltera();
    });
    $("#btCartaoAtribui").click(function() {

        $("#ModalRetorno_cartao").text("");
        $("#ModalRetorno_cartao").removeClass();

        cartaoAtribui();
    });
    $("#btCartaoRemove").click(function() {

        $("#ModalRetorno_cartao").text("");
        $("#ModalRetorno_cartao").removeClass();

        cartaoRemove();
    });
    $("#btFrotaAlteraKm").click(function() {

        $("#ModalRetorno_frota").text("");
        $("#ModalRetorno_frota").removeClass();

        frotaAlteraKm();
    });

    $("#btFrotaRemove").click(function() {

        $("#ModalRetorno_frota").text("");
        $("#ModalRetorno_frota").removeClass();

        frotaRemove();
    });
    $("#btFrotaAtribui").click(function() {

        $("#ModalRetorno_frota").text("");
        $("#ModalRetorno_frota").removeClass();

        frotaAtribui();
    });
    $("#bt_cadastro_USUARIO_voltar").click(function() {

        cadastro_USUARIO_voltar();
    });

    $("#senhaEditaConfirma").click(function() {

        var re = $("#modalSenha_re").text();

        confirmaEditaSenha(re);
    });

    $("#btfrotaPesquisa").click(function() {

        var txt = $("#frotaTXT").val();
        var frotaAtual = $("#frotaAtual").text();

        $("#rowFrotaResult").slideUp("fast");
        $("#rowFrotaNovo").slideUp("fast");

        frota_pesquisa(txt, frotaAtual);

    });
    $("#btfrotaNova").click(function() {

        $("#rowFrotaResult").slideUp("fast", function() {
            $("#listaFrotaPesquisa").slideUp("fast");
            $("#frotaDados").text("");
            $("#frotaPlaca").text("");
            $("#rowFrotaNovo").slideDown("fast");

            veiculo_lista();
        });
    });
    $("#btFechaFrota").click(function() {

        $("#rowFrotaResult").slideUp("fast", function() {
            $("#listaFrotaPesquisa").slideUp("fast");
            $("#frotaDados").text("");
            $("#frotaPlaca").text("");
        });
    });
    $("#btfrotaFormCadastra").click(function() {

        var re = $("#frotaAtualRe").text();
        var frotaNova = $("#formFrotaNovoPlaca").val();
        var frotaAtual = $("#frotaAtual").text();
        var veiculo = $("#formFrotaModelo").val();
        var km = $("#formFrotaKm").val();

        frotaCadastro(re, frotaAtual, frotaNova, veiculo, km);

    });

    function formEdita() {

        $(".btEditaUsuario").click(function() {
            var re = $(this).attr('value');

            usuario_dados(re);

            $("#Modal_edita").modal("show");

        });
    }

    function formEditaCartao() {

        $(".btCartao").click(function() {
            var re = $(this).attr('value');

            $("#btfrotaFormNovo").slideDown("fast");

            $("#ListaUSUARIO").slideUp("fast");

            cartao_dados(re);

            $("#Modal_cartao").modal("show");

        });
    }

    function formEditaFrota() {

        $(".btFrota").click(function() {
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
            success: function(dados) {
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
            success: function(dados) {
                var cartao = dados.cartao;

                $("#cartaoAtual").append(cartao.cartao);
                $("#cartaoAtualColaborador").append(cartao.nome);
                $("#cartaoAtualRe").append(cartao.re);

                cartao_lista();

                if (dados.permissao != 0) {
                    $(".cartaoFerramenta").removeClass("d-none");
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
            success: function(dados) {

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
            success: function(dados) {
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
                    lista += "<tbody>";
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

        $(".btAceitaColaborador").click(function() {
            var colaborador = $(this).attr('value');

            $("#ModalRetornoTransferencia").text("");
            $("#ModalRetornoTransferencia").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "transfereColaborador", tipo: "aceita", colaborador: colaborador },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {

                    var msg = dados.msg;

                    if (dados.erro === "1") {

                        var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                        $("#ModalRetornoTransferencia").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");

                    } else {
                        var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                        $("#ModalRetornoTransferencia").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                            $("#linhaTrans" + colaborador).slideUp("fast");
                        });
                    }
                }
            });
        });
    }

    function recusaColaborador() {

        $(".btRecusaColaborador").click(function() {
            var colaborador = $(this).attr('value');

            $("#ModalRetornoTransferencia").text("");
            $("#ModalRetornoTransferencia").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "transfereColaborador", tipo: "recusa", colaborador: colaborador },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {

                    var msg = "";

                    if (dados.erro === "1") {

                        msg = "<i class='icon-attention-2'></i> " + dados.msg;

                        var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                        $("#ModalRetornoTransferencia").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");

                    } else {
                        msg = "<i class='icon-ok-circled2'></i> " + dados.msg;
                        var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                        $("#ModalRetornoTransferencia").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                            $("#linhaTrans" + colaborador).slideUp("fast");
                        });
                    }
                }
            });
        });
    }

    function frota_pesquisa(txt, frotaAtual) {

        $("#listaFrotaPesquisa").slideUp("fast", function() {
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
                    success: function(dados) {
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
                            lista += "<tbody>";
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

        $(".btSelecionaFrota").click(function() {

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
                success: function(dados) {

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
            success: function(dados) {

                var msg = dados.msg;

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");

                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        $("#Modal_frota").modal("hide");
                        window.location.replace("USUARIO");
                    });
                }
            }
        });
    }

    function formResetaSenha() {

        $(".btEditaSenha").click(function() {
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
            success: function(dados) {

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
            success: function(dados) {

                $("#modalSenha_email").text("");
                $("#modalSenha_email").text(dados.email);
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
            success: function(dados) {

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetornoEditaSenha").slideDown("fast").addClass(classe).append(dados.msg).delay(1500).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ListaUSUARIO").slideUp("fast", function() {
                        $("#ModalRetornoEditaSenha").slideDown("fast").addClass(classe).append(dados.msg).delay(1500).slideUp("fast", function() {

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
            success: function(dados) {

                dados_select("Cargo", dados.cargo);
                dados_select("Coordenador", dados.coordenador);
                dados_select("CN", dados.cn);
                dados_select("Acesso", dados.sistema);
                dados_select("Ativo", dados.ativo);
                dados_select("Estado", dados.estado);

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
            success: function(dados) {
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

        $("#usr_formulario_novo").slideUp("fast", function() {

            window.location.replace("PLANTAO");
        });
    }

    function form_cn() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cnLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
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
            success: function(dados) {
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
            success: function(dados) {
                var linhas = "<option value=\"0\">SELECIONE UM CARGO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#usr_cargo").slideDown("fast").html(linhas);
            }
        });
    }

    function form_estado() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "estadoLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = "<option value=\"0\">SELECIONE O Estado</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#usr_estado").slideDown("fast").html(linhas);
            }
        });
    }

    function UsuarioProcura(txt, cn) {

        $("#retornoUsuario").text("");
        $("#retornoUsuario").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "USUARIOProcura", txt: txt, cn: cn },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var verifica = eval(dados);

                if (verifica.length === 0) {
                    $("#ListaUSUARIO").slideUp("fast").html("");

                    var tempo = 1500;
                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    var msg = "<i class='icon-attention'></i> Nenhuma correspondência!";

                    $("#retornoUsuario").slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast");

                } else {
                    var linhas = eval(dados);

                    var lista = "";
                    lista += "<table class='table table-striped w-auto text-center'>";
                    lista += "<thead class='thead-dark'>";
                    lista += "<tr>";
                    lista += "<th scope='col'>COLABORADOR</th>";
                    lista += "<th scope='col'>ATIVO</th>";
                    lista += "<th scope='col'>VEÍCULO</th>";
                    lista += "<th scope='col'>CARTÃO</th>";
                    lista += "<th scope='col'>COORDENADOR</th>";
                    lista += "<th scope='col'>CN</th>";
                    lista += "<th scope='col'><i class='icon-key-outline'></i> RESETA SENHA</th>";
                    lista += "<th scope='col'>EDITAR</th>";
                    lista += "</tr>";
                    lista += "</thead>";
                    lista += "<tbody>";
                    for (var i = 0; i < linhas.length; i++) {
                        lista += "<tr>";
                        lista += "<td id='listaNome" + linhas[i].re + "'>" + linhas[i].nome + " [" + linhas[i].re + "]</td>";
                        lista += "<td>" + linhas[i].ativo + "</td>";
                        lista += "<td id='listaVeiculo" + linhas[i].re + "'><button class='btFrota btn btn-sm btn-outline-secondary' value=" + linhas[i].re + "><i class=' icon-steering-wheel'></i> " + linhas[i].veiculo + "</button></td>";
                        lista += "<td id='listaCartao" + linhas[i].re + "'><button class='btCartao btn btn-sm btn-outline-secondary' value=" + linhas[i].re + "><i class='icon-credit-card'></i> " + linhas[i].cartao + "</button></td>";

                        coor = linhas[i].coordenador.split(" ");

                        lista += "<td>" + coor[0] + "</td>";
                        lista += "<td>" + linhas[i].cn + "</td>";
                        lista += "<td><button class='btEditaSenha btn btn-outline-danger btn-sm' value='" + linhas[i].re + "'><i class='icon-arrows-cw-1'></i> Resetar</button></td>";
                        lista += "<td><button class='btEditaUsuario btn btn-outline-info btn-sm' value='" + linhas[i].re + "'><i class='icon-popup'></i> Editar</button></td>";

                        lista += "</tr>";
                    }
                    lista += "</tbody>";
                    lista += "</table>";

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
        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: {
                acao: "cadastroUSUARIO",
                re: $("#usr_re").val(),
                nome: $("#usr_nome").val(),
                estado: $("#usr_estado").val(),
                email: $("#usr_email").val(),
                telefone: $("#usr_telefone").val(),
                cargo: $("#usr_cargo").val(),
                cartao: $("#usr_cartao").val(),
                coordenador: $("#usr_coordenador").val(),
                cn: $("#usr_cn").val(),
                acesso: $("#usr_acesso").val(),
                ativo: $("#usr_ativo").val()
            },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var tempo = 1500;

                if (dados.erro === "1") {

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";

                    if (dados.tempo > 0) {
                        tempo = dados.tempo;
                    }

                    $("#ModalRetorno_cadastro").empty().slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast", function() {

                        if (msg.indexOf('permissão') === 44) {
                            $("#usr_formulario_novo").modal("hide");
                            window.location.replace("USUARIO");
                        }
                    });
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    var msg = "<i class='icon-ok-circle-1'></i> " + dados.msg;

                    $("#ModalRetorno_cadastro").empty().slideDown("fast").addClass(classe).append(msg).delay(tempo).slideUp("fast", function() {

                        $("#usr_formulario_novo").modal("hide");
                        window.location.replace("USUARIO");
                    });
                }
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
        var cn = $("#editaCN").val();
        var acesso = $("#editaAcesso").val();
        var ativo = $("#editaAtivo").val();
        var senha = $("#editaSenha").val();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "editaUSUARIO", re: re, nome: nome, estado: estado, email: email, telefone: telefone, cargo: cargo, coordenador: coordenador, cn: cn, acesso: acesso, ativo: ativo, senha: senha },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'USUARIOSC', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetorno_edita").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(2000).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_edita").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1200).slideUp("fast", function() {

                        //                        $(".statusLn" + solicitacao).text("CONCLUÍDO");
                        $("#Modal_edita").modal("hide");
                        UsuarioProcura($("#usrTXT").val(), $("#usrCn").val());
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
            success: function(dados) {

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetorno_cartao").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(2000).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_cartao").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1200).slideUp("fast", function() {

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
            success: function(dados) {

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetorno_cartao").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(2000).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_cartao").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1200).slideUp("fast", function() {

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
            success: function(dados) {

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetorno_cartao").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(2000).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_cartao").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1200).slideUp("fast", function() {

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
            success: function(dados) {

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(2000).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1200).slideUp("fast", function() {

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
            success: function(dados) {

                if (dados.erro === "1") {
                    var tempo;
                    if (dados.tempo != 0) {
                        tempo = dados.tempo;
                    } else {
                        tempo = 2000;
                    }

                    var classe = "bg-danger rounded text-white p-2 mt-2";
                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(dados.tempo).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1200).slideUp("fast", function() {

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
            success: function(dados) {
                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(2000).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";

                    $("#ModalRetorno_frota").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1200).slideUp("fast", function() {

                        $("#Modal_frota").modal("hide");
                    });
                }
            }
        });
    }
});