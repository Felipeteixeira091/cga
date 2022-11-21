$(document).ready(function () {

    almoxLista();

    function almox_exibe() {

        $(".bt_almox").click(function () {
            var almox = $(this).attr('value');

            $.ajax({
                type: 'post', //Definimos o método HTTP usado
                data: { acao: "residuoLista", almox: almox },
                dataType: 'json', //Definimos o tipo de retorno
                url: 'SGADESCARTE', //Definindo o arquivo onde serão buscados os dados
                success: function (dados) {

                    var linhas = eval(dados);

                    var lista = "";

                    var almox = linhas[0].almox;
                    var almoxId = linhas[0].id;
                    $("#tituloAlmox").text(almox);
                    $("#idAlmox").text(almoxId);

                    for (var i = 0; i < linhas.length; i++) {

                        var saldo = linhas[i].qtd - linhas[i].descarte;
                        var percent = (saldo * 100) / linhas[i].limite;
                        var cor = "";
                        var barra = "";

                        if (saldo === 0) {
                            cor = "text-success";
                        } else if (percent >= 1 && percent <= 60) {
                            barra = "bg-Warning";
                            cor = "text-dark"
                            cor2 = "text-warning";
                        } else if (percent >= 61 && percent <= 100) {
                            barra = "bg-danger";
                            cor = "text-white";
                            cor2 = "text-danger";
                        }
                        lista += "<div class='row border mt-1 rounded pb-1'>";
                        lista += "<div class='col'>";
                        lista += "<span id='itemTipo" + linhas[i].idItem + "' class='text-muted'>" + linhas[i].tipo + "</span><div class='mt-2 progress'><div id='itemQuantidade" + linhas[i].idItem + "' class='progress-bar " + barra + "' role='progressbar' style='width: " + percent + "%;' aria-valuenow='" + saldo + "' aria-valuemin='0' aria-valuemax='" + linhas[i].limite + "'><span class='" + cor + "'>" + saldo + "</span></div></div>";
                        lista += "</div>";
                        lista += "<div class='col-2'>";
                        lista += "<span class='" + cor2 + "'>" + saldo + " (" + percent + "%)</span>";
                        lista += "</div>";
                        lista += "<div class='col-2'>";
                        lista += "<button value='" + linhas[i].idItem + "' class='bt_recicla btn btn-light btn-sm border text-muted m-2'><i class='icon-loop-1 text-muted'></i> Reciclar</button>";
                        lista += "</div>";
                        lista += "</div>";

                        if (saldo <= 0) {
                            $(".bt_recicla").attr("disabled", true);
                        } else {
                            $(".bt_recicla").attr("disabled", false);
                        }

                    }

                    lista += "";

                    $("#residuos").slideDown("slow").html(lista);

                    recicla();
                }
            });

            $("#modalAlmox").modal("show");

        });
    }

    function recicla() {

        $(".bt_recicla").click(function () {

            var itemId = $(this).attr('value');
            var itemNome = $("#itemTipo" + itemId).text();
            var almox = $("#idAlmox").text();
            var itemQtd = $("#itemQuantidade" + itemId).text();

            $("#itemRecicla").text(itemNome);
            $("#itemQuantidade").text(itemQtd);

            $("#ModalReciclagem").modal("show");
        });


    }
    function almoxLista() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "almoxLista" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SGADESCARTE', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = eval(dados);

                var lista = "";
                lista += "<div id='elementoAtivo' class='row'>";
                for (var i = 0; i < linhas.length; i++) {

                    lista += "<button value='" + linhas[i].id + "' class='bt_almox btn btn-light btn-sm border text-muted m-2'><i class='icon-shop-1 text-info'></i> " + linhas[i].nome + "</button>";
                }

                lista += "</div>";
                $("#almoxLista").slideDown("slow").html(lista);

                almox_exibe();
            }
        });
    }
});