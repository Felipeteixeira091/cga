$(document).ready(function() {

    function difference(date1) {

        var date2 = new Date();

        const date1utc = Date.UTC(date1.getFullYear(), date1.getMonth(), date1.getDate());
        const date2utc = Date.UTC(date2.getFullYear(), date2.getMonth(), date2.getDate());
        day = 1000 * 60 * 60 * 24;
        return (date2utc - date1utc) / day
    }

    $(".btnotaVolta").click(function() {
        $("#notaOpc").slideDown("fast", function() {

            $("#notaFormulario").slideUp("fast");
            $("#notaListaPendente").slideUp("fast");
        });
    });
    $("#vfbListaPendente").slideDown("fast", function() {

        ListaVfb();
        vfb_qtd();
    });

    $(".btnotaVer").click(function() {

        $("#notaVer").modal("show");
    });

    $(".btStatus").click(function() {
        var status = $(this).attr('value');
        $("#idStatus").text(status);
        $("#formularios").slideUp("fast", function() {

            $("#form2").slideDown("#fast");

            //    $("#btAprovado").attr("disabled", true);
            //    $("#btCorrecao").attr("disabled", true);
            //    $("#btReprovado").attr("disabled", true);

            var statusTXT = "";

            if (status === "3") {
                statusTXT = "APROVADO";
            } else if (status === "4") {
                statusTXT = "REPROVADO";
            } else {
                statusTXT = "NECESSÁRIO CORREÇÃO";
            }
            $("#status").text(statusTXT);

        });
    });
    $("#btConcluir").click(function() {

        var id = $("#vfbId").text();
        var status = $("#idStatus").text();
        var obs = $("#obs2").val();
        conclui(id, status, obs);
    });

    $("#btCheckList").click(function() {

        var id = $("#vfbId").text();
        var seg = $("input[name='seg']:checked").val();
        var perg1 = $("input[name='perg1']:checked").val();
        var perg2 = $("input[name='perg2']:checked").val();
        var perg3 = $("input[name='perg3']:checked").val();
        var perg4 = $("input[name='perg4']:checked").val();
        var perg5 = $("input[name='perg5']:checked").val();
        var perg6 = $("input[name='perg6']:checked").val();
        var perg7 = $("input[name='perg7']:checked").val();

        enviarChecklist(id, seg, perg1, perg2, perg3, perg4, perg5, perg6, perg7);

    });

    $("#btInserirObs").click(function() {
        var vfb = $("#vfbId").text();
        var obs = $("#novaObs").val();
        InserirObs(vfb, obs);
    });

    function enviarChecklist(id, seg, perg1, perg2, perg3, perg4, perg5, perg6, perg7, perg8, perg9) {

        $("#retornoChecklist").text("");
        $("#retornoChecklist").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: {
                acao: "vfbCheckList",
                id: id,
                seg: seg,
                perg1: perg1,
                perg2: perg2,
                perg3: perg3,
                perg4: perg4,
                perg5: perg5,
                perg6: perg6,
                perg7: perg7,
                perg8: perg8,
                perg9: perg9
            },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'VFBPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoChecklist").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                } else {
                    var msg = "<i class='icon-ok-circle'></i> " + dados.msg;
                    var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoChecklist").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        // window.location.reload();
                    });
                }
            }
        });
    }

    function conclui(id, status, obs) {

        $("#retornoUpload").text("");
        $("#retornoUpload").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: {
                acao: "conclui",
                id: id,
                status: status,
                obs: obs
            },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'VFBPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoUpload").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                } else {
                    var msg = "<i class='icon-ok-circle'></i> " + dados.msg;
                    var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoUpload").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        window.location.reload();
                    });
                }
            }
        });
    }

    function InserirObs(vfb, obs) {

        $("#retornoObs").text("");
        $("#retornoObs").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "vfbObs", vfb: vfb, obs: obs },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'VFBPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoObs").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                } else {
                    var msg = "<i class='icon-ok-circle'></i> " + dados.msg;
                    var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoObs").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {
                        $("#novaObs").val("");
                        VfbAnexo(vfb);

                    });
                }
            }
        });
    }

    function ConcluiSolicitacao(vfb) {

        $("#retornoUpload").text("");
        $("#retornoUpload").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "vfbConcluir", vfb: vfb },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'VFBPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                if (dados.erro === "1") {

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoUpload").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                } else {
                    var msg = "<i class='icon-ok-circle'></i> " + dados.msg;
                    var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoUpload").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        window.location.reload();
                    });
                }
            }
        });
    }

    function vfb_qtd() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "qtdVfb" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'VFBPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                $("#notaQtd").text("");
                $("#notaQtd").append(dados.qtd);
            }

        });
    }

    function ListaVfb() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaNota" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'VFBPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {

                var qtd = $("#notaQtd").text();
                var notas = dados.notas;

                var linhas = eval(notas);

                var lista = "";
                lista += "<div class='card border'>";
                lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                lista += "<thead class='bg bg-light text-muted'>";
                lista += "<tr>";
                lista += "<th scope='col' class='text-center'>SLA</th>";
                lista += "<th scope='col' class='text-center'>SOLICITADO</th>";
                lista += "<th scope='col' class='text-center'>SITE</th>";
                lista += "<th scope='col' class='text-center'>OS</th>";
                lista += "<th scope='col' class='text-center'>STATUS</th>";
                lista += "<th scope='col' class='text-center'><i class='icon-popup'></i> VER</th>";
                lista += "</tr>";
                lista += "</thead>";
                lista += "<tbody>";
                for (var i = 0; i < linhas.length; i++) {

                    var date1 = new Date(linhas[i].data);

                    var dias = difference(date1);

                    lista += "<tr>";
                    lista += "<td class='text-center'>" + dias + "</td>";
                    lista += "<td class='text-center'>" + linhas[i].data + " " + linhas[i].hora + "</td>";
                    lista += "<td class='text-center'>" + linhas[i].site + "</td>";
                    lista += "<td class='text-center'>" + linhas[i].os + "</td>";
                    lista += "<td class='text-center'>" + linhas[i].status + "</td>";
                    lista += "<td class='text-center'><button class='btnotaVer btn btn-light btn-sm border' value='" + linhas[i].id + "'><i class='icon-popup'></i> Ver</button></td>";
                    lista += "</tr>";
                }
                lista += "</tbody>";
                lista += "</table></div>";

                $("#notaPendente").slideUp("slow").html("").delay(100).slideDown("fast").html(lista);

                VerNota();
            }
        });
    }

    function VerNota() {
        $(".btnotaVer").click(function() {

            $("#vfbId").text("");

            var vfb = $(this).attr('value');
            $("#vfbId").append(vfb);
            $("#notaVer").modal("show");
            VfbAnexo(vfb);
        });
    }

    function VfbAnexo(vfb) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "vfbAnexo", vfb: vfb },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'VFBPENDENTE', //Definindo o arquivo onde serão buscados os dados
            success: function(dados) {
                var anexo = dados.anexo;
                var detalhe = dados.detalhe;
                var checklist = dados.checklist;

                $("#vfbReDetalhe").text(detalhe.re);
                $("#vfbNomeDetalhe").text(detalhe.nome);
                $("#vfbDataDetalhe").text(detalhe.data);
                $("#vfbHoraDetalhe").text(detalhe.hora);
                $("#vfbSiteDetalhe").text(detalhe.site);
                $("#vfbOsDetalhe").text(detalhe.os);
                $("#vfbValorDetalhe").text(detalhe.valor);
                $("#vfbMOTipoDetalhe").text(detalhe.mo);
                $("#vfbMODetalhe").text(detalhe.meNome + " - " + detalhe.meRe);
                $("#vfbOrientacao").text(detalhe.solicitacao);

                $("input[name=seg][value='" + checklist.seg + "']").prop("checked", true);
                $("input[name=perg1][value='" + checklist.perg1 + "']").prop("checked", true);
                $("input[name=perg2][value='" + checklist.perg2 + "']").prop("checked", true);
                $("input[name=perg3][value='" + checklist.perg3 + "']").prop("checked", true);
                $("input[name=perg4][value='" + checklist.perg4 + "']").prop("checked", true);
                $("input[name=perg5][value='" + checklist.perg5 + "']").prop("checked", true);
                $("input[name=perg6][value='" + checklist.perg6 + "']").prop("checked", true);
                $("input[name=perg7][value='" + checklist.perg7 + "']").prop("checked", true);
                $("input[name=perg8][value='" + checklist.perg8 + "']").prop("checked", true);

                var opt = "option[value=" + checklist.perg9 + "]";
                $("#perg9").find(opt).attr("selected", "selected");

                var notas = anexo;

                var linhas = eval(notas);

                var lista = "";
                lista += "<div class='card border'>";
                lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                lista += "<thead class='bg bg-light text-muted'>";
                lista += "<tr>";
                lista += "<th scope='col' class='text-center'>DATA/HORA</th>";
                lista += "<th scope='col' class='text-center'>TIPO</th>";
                lista += "<th scope='col' class='text-center'>DESCRIÇÃO</th>";
                lista += "<th scope='col' class='text-center'>APAGAR</th>";
                lista += "</tr>";
                lista += "</thead>";
                lista += "<tbody>";
                for (var i = 0; i < linhas.length; i++) {

                    var bt = "";
                    var btDell = "";
                    if (linhas[i].codigo === "") {
                        bt = "<button type='button' disabled class='btn btn-sm btn-light border text-muted'><i class='icon-comment'></i> TXT</button>";
                        btDell = "<button disabled class='deletaImg btn btn-light btn-sm border' value='" + linhas[i].id + "'><i class='icon-trash-1 text-danger'></i></button>";
                    } else {

                        let anyString = linhas[i].codigo;
                        let cod = anyString.substring(anyString.length - 4)

                        cod = cod.replace('.', '');

                        bt = "<a href='vfb_anexo/" + linhas[i].codigo + "' target='_blank'><button type='button' class='btn btn-sm btn-light border text-muted'><i class='icon-attach-4'></i> " + cod + "</button></a>";
                        btDell = "<button class='deletaImg btn btn-light btn-sm border' value='" + linhas[i].id + "'><i class='icon-trash-1 text-danger'></i></button>";
                    }

                    lista += "<tr id='linha" + linhas[i].id + "'>";
                    lista += "<td class='text-center'>" + linhas[i].data + " " + linhas[i].hora + "</td>";
                    lista += "<td class='text-center'>" + bt + "</td>";
                    lista += "<td class='text-center'>" + linhas[i].descricao + "</td>";
                    lista += "<td class='text-center'>" + btDell + "</td>";
                    lista += "</tr>";
                }
                lista += "</tbody>";
                lista += "</table></div>";
                $("#anexo").slideDown("slow").html(lista);

                deletaImg();
            }
        });
    }

    function deletaImg() {

        $(".deletaImg").click(function() {
            var img = $(this).attr('value');

            $("#retornoUpload").text("");
            $("#retornoUpload").removeClass();

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "deletaImg", img: img },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'VFBPENDENTE', //Definindo o arquivo onde serão buscados os dados
                success: function(dados) {

                    var msg = "<i class='icon-ok-circle-1'></i> " + dados.msg;
                    var classe = "bg-info rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoUpload").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function() {

                        $("#linha" + img).fadeOut("fast");
                        // ItemLista();
                    });
                }
            });
        });
    }

});