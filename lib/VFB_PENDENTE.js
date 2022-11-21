$(document).ready(function() {

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


    $("#btConcluir").click(function() {
        var vfb = $("#vfbId").text();
        ConcluiSolicitacao(vfb);
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
                lista += "<th scope='col' class='text-center'>ID</th>";
                lista += "<th scope='col' class='text-center'>SOLICITADO</th>";
                lista += "<th scope='col' class='text-center'>SITE</th>";
                lista += "<th scope='col' class='text-center'>OS</th>";
                lista += "<th scope='col' class='text-center'>STATUS</th>";
                lista += "<th scope='col' class='text-center'><i class='icon-popup'></i> VER</th>";
                lista += "</tr>";
                lista += "</thead>";
                lista += "<tbody>";
                for (var i = 0; i < linhas.length; i++) {
                    lista += "<tr>";
                    lista += "<td class='text-center'>" + linhas[i].id + "</td>";
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

                $("#anexo").slideDown("slow").html(anexo);

            }
        });
    }

});