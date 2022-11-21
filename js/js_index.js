$(document).ready(function() {


    var tempo_ms = 30000;
    intervalo = setInterval(m, tempo_ms);

    function m() {
        // /    sol_pendente();
        //   sol_enviar();
    }

    $("input[name='opcAt']").each(function() {
        if ($(this).val() !== 0) {
            $(this).prop("checked", false);
            $(".opcAt").removeClass("active");
        } else {
            $(this).prop("checked", true);
            $(".opcAt").addClass("active");
        }
    });

    menu();


    logOut();

    function href() {

        $(".bt_link").click(function() {

            var link = $(this).attr('value');
            window.location.href = link;
        });
    }

    $("#btlinktr").click(function() {

        $("#linktr").modal("show");

    });

    $("#btFormSenha").click(function() {

        $("#modalAlteraSenha").modal("show");
    });

    $("#btAlteraSenha").click(function() {

        var senha1 = $("#senha1").val();
        var senha2 = $("#senha2").val();
        var tipo = $("input[name='opcAt']:checked").val();

        if (!tipo) {
            tipo = "tipo";
        } else {
            tipo = $("input[name='opcAt']:checked").val();
        }
        trocaSenha(senha1, senha2, tipo);
    });
    $("#btFormSenha").click(function() {

        $("#modalAlteraSenha").modal("show");
    });

    function trocaSenha(senha1, senha2, tipo) {

        $("#retornoTrocaSenha").text("");
        $("#retornoTrocaSenha").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "trocaSenha", senha1: senha1, senha2: senha2, tipo: tipo },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'dadosAltera', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {

                    var classe = "bg-danger rounded text-white font-weight-bold p-2 mt-2";
                    var msg = "<i class='icon-attention-2'></i> " + dados.msg;
                    $("#retornoTrocaSenha").empty().slideDown("fast").addClass(classe).append(msg).delay(2000).slideUp("fast");
                } else {
                    var classe = "bg-success rounded text-white font-weight-bold p-2 mt-2";
                    var msg = "<i class='icon-ok-circle'></i> " + dados.msg;
                    $("#retornoTrocaSenha").empty().slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        $("#senha1").val("");
                        $("#senha2").val("");

                        $("#modalAlteraSenha").modal("hide");

                    });
                }
            }
        });
    }

    function menu() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "index" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'menu_php', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var linhas = eval(dados);
                var lista = "";
                var geral = "<button id='btFormDados' class='btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>MEUS DADOS</button>";
                var scp = "";
                var psf = "";
                var sce = "";
                var sma = "";
                var cep = "";
                var gas = "";
                var gmg = "";
                var ext = "";
                var sbo = "";
                var vfb = "";
                var sga = "";
                var ccc = "";

                var pgeral = 0;
                var psce = 0;
                var pscp = 0;
                var ppsf = 0;
                var psma = 0;
                var pcep = 0;
                var pgas = 0;
                var pgmg = 0;
                var pext = 0;
                var psbo = 0;
                var pvfb = 0;
                var psga = 0;
                var pccc = 0;

                for (var i = 0; i < linhas.length; i++) {

                    var sub = linhas[i].sub;
                    var nome = linhas[i].nome;
                    var link = linhas[i].link;
                    if (sub === "GERAL") {
                        geral += "<button value=" + link + " class='bt_link btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>" + nome + "</button>";
                        pgeral++;
                    } else if (sub === "SCE") {
                        sce += "<button value=" + link + " class='bt_link btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>" + nome + "</button>";
                        psce++;
                    } else if (sub === "SMA") {
                        sma += "<button value=" + link + " class='bt_link btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>" + nome + "</button>";
                        psma++;
                    } else if (sub === "VFB") {
                        vfb += "<button value=" + link + " class='bt_link btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>" + nome + "</button>";
                        pvfb++;
                    } else if (sub === "SGA") {
                        sga += "<button value=" + link + " class='bt_link btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>" + nome + "</button>";
                        psga++;
                    } else if (sub === "CEP") {
                        cep += "<button value=" + link + " class='bt_link btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>" + nome + "</button>";
                        pcep++;
                    } else if (sub === "GAS") {
                        gas += "<button value=" + link + " class='bt_link btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>" + nome + "</button>";
                        pgas++;
                    } else if (sub === "GMG") {
                        gmg += "<button value=" + link + " class='bt_link btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>" + nome + "</button>";
                        pgmg++;
                    } else if (sub === "EXT") {
                        ext += "<button value=" + link + " class='bt_link btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>" + nome + "</button>";
                        pext++;
                    } else if (sub === "SBO") {
                        sbo += "<button value=" + link + " class='bt_link btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>" + nome + "</button>";
                        psbo++;
                    } else if (sub === "SCP") {
                        scp += "<button value=" + link + " class='bt_link btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>" + nome + "</button>";
                        pscp++;
                    } else if (sub === "PSF") {
                        psf += "<button value=" + link + " class='bt_link btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>" + nome + "</button>";
                        ppsf++;
                    }else if (sub === "CCC") {
                        ccc += "<button value=" + link + " class='bt_link btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>" + nome + "</button>";
                        pccc++;
                    }

                }
                geral += "<button id='btTutorial' class='btn btn-sm btn-light border ml-1 mt-1 text-muted customBt'>TUTORIAL</button>";
                if (pgeral > 0) {

                    $("#menuGERAL").removeClass("d-none");
                    $("#pagGERAL").slideDown("slow").html(geral);

                }
                if (pscp > 0) {

                    $("#menuSCP").removeClass("d-none");
                    $("#pagSCP").slideDown("slow").html(scp);

                }
                if (ppsf > 0) {

                    $("#menuPSF").removeClass("d-none");
                    $("#pagPSF").slideDown("slow").html(psf);

                }
                if (psce > 0) {

                    $("#menuSCE").removeClass("d-none");
                    $("#pagSCE").slideDown("slow").html(sce);

                }
                if (psma > 0) {

                    $("#menuSMA").removeClass("d-none");
                    $("#pagSMA").slideDown("slow").html(sma);

                }
                if (pcep > 0) {

                    $("#menuCEP").removeClass("d-none");
                    $("#pagCEP").slideDown("slow").html(cep);

                }
                if (pext > 0) {

                    $("#menuEXT").removeClass("d-none");
                    $("#pagEXT").slideDown("slow").html(ext);

                }
                if (pgas > 0) {

                    $("#menuGAS").removeClass("d-none");
                    $("#pagGAS").slideDown("slow").html(gas);

                }
                if (pgmg > 0) {

                    $("#menuGMG").removeClass("d-none");
                    $("#pagGMG").slideDown("slow").html(gmg);

                }
                if (psbo > 0) {

                    $("#menuSBO").removeClass("d-none");
                    $("#pagSBO").slideDown("slow").html(sbo);

                }
                if (pvfb > 0) {

                    $("#menuVFB").removeClass("d-none");
                    $("#pagVFB").slideDown("slow").html(vfb);
                }
                if (psga > 0) {

                    $("#menuSGA").removeClass("d-none");
                    $("#pagSGA").slideDown("slow").html(sga);
                }
                if (pccc > 0) {

                    $("#menuCCC").removeClass("d-none");
                    $("#pagCCC").slideDown("slow").html(ccc);
                }

                $("#btTutorial").click(function() {
                    $("#modalTutorial").modal("show");
                });

                href();

                $("#btFormDados").click(function() {
                    $("#modalDados").modal("show");

                    meus_dados();

                });
            }
        });
    }

    function meus_dados() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "meus_dados" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'indexSC', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var telefone = dados.telefone;
                telefone = "(" + telefone[0] + telefone[1] + ") " + telefone[2] + " " + telefone[3] + telefone[4] + telefone[5] + telefone[6] + "-" + telefone[7] + telefone[8] + telefone[9] + telefone[10];

                $("#dadosUF").text(dados.uf);
                $("#dadosCN").text(dados.cn);
                $("#dadosRE").text(dados.re);
                $("#dadosNOME").text(dados.nome);
                $("#dadosEMAIL").text(dados.email);
                $("#dadosCARTAO").text(dados.cartao);
                if (dados.placa === null) {
                    $("#dadosVEICULO").text("S/VEÍCULO");
                } else {
                    $("#dadosVEICULO").text(dados.placa + " - " + dados.vMarca + " " + dados.vModelo);
                }

                $("#dadosTELFONE").text(telefone);
                $("#dadosCARGO").text(dados.cargo);
                $("#dadosCOORDENADOR").text(dados.coordenador);
            }
        });
    }

    function logOut() {
        $("#btLogOut").click(function() {

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "logOut" },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'logOut', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {

                    var classe = "";
                    var msg = "";
                    if (dados.erro === "1") {
                        classe = "bg bg-danger rounded text-white font-weight-bold p-2";
                        msg = dados.msg;
                        $("#retornoIndex").empty().slideDown("fast").addClass(classe).append(dados.msg).delay(1900).slideUp("fast");
                    } else if (dados.erro === "0") {

                        classe = "bg bg-dark border rounded text-muted font-weight-bold p-2";
                        msg = "<i class='icon-lock-2 text-danger'></i> " + dados.msg;

                        $("#retornoIndex").empty().slideDown("fast").addClass(classe).append(msg).delay(1900).slideUp("fast", function() {

                            window.location.href = "telaLogin";

                        });
                    }
                }
            });
        });
    }
});