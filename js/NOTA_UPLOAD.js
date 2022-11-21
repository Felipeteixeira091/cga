var formFiles, divReturn, progressBar;

formFiles = document.getElementById('formFiles');
divReturn = document.getElementById('return');
progressBar = document.getElementById('progressBar');

formFiles.addEventListener('submit', sendForm, false);

function sendForm(evt) {

    $(".spq").removeClass("d-none");

    var formData, ajax, pct;

    formData = new FormData(evt.target);
    formData.append('nome', $("#nota_arquivo").val());
    formData.append('nota', $("#nota_badge_id").text());

    ajax = new XMLHttpRequest();

    ajax.onreadystatechange = function () {

        $("#retornoUpload").text("");
        $("#retornoUpload").removeClass();

        if (ajax.readyState == 4) {
            formFiles.reset();

            var dados = JSON.parse(ajax.response);

            if (dados.erro === "1") {
                var msg = "<i class='icon-attention'></i> " + dados.msg;
                var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";

                $("#retornoUpload").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                });
            } else {
                var msg = "<i class='icon-ok-circle'></i> " + dados.msg;
                var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                $("#retornoUpload").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                    $("#nota_modal_upload").modal("hide");
                    obter_anexo($("#nota_badge_id").text());
                });

            }
            progressBar.style.display = 'none';
        } else {
            progressBar.style.display = 'block';
            divReturn.style.display = 'block';

            var msg = "<i class='icon-attention'></i> Enviando arquivo.";
            var classe = "bg bg-info rounded font-weight-bold text-white pt-2 pb-2";

            $("#retornoUpload").slideDown("fast").addClass(classe).append(msg);
        }
    }
    function obter_anexo(id) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "lista", tipo: "anexo", id: id },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'EXTVALIDA', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = eval(dados);

                var lista = "";
                lista += "<div class='card border'>";
                lista += "<div class='card-header text-muted bg-light font-weight-bold'>Resultado da busca</div>";
                lista += "<table class='table table-sm table-hover table-striped w-auto'>";
                lista += "<thead class='bg bg-light text-muted'>";
                lista += "<tr>";
                lista += "<th scope='col' class='text-center'>ARQUIVO</th>";
                lista += "<th scope='col' class='text-center'>DATA/HORA</th>";
                lista += "<th scope='col' class='text-center'>BAIXAR</i></th>";
                lista += "</tr>";
                lista += "</thead>";
                lista += "<tbody>";
                for (var i = 0; i < linhas.length; i++) {
                    lista += "<tr id='linha" + linhas[i].id + "'>";
                    lista += "<td class='text-center'>" + linhas[i].nome + "</td>";
                    lista += "<td class='text-center'>" + linhas[i].dh + "</td>";
                    lista += "<td class='text-center'><a download target='_blank' href='nota/" + linhas[i].arquivo + "'><button type='button' class='btn btn-sm btn-primary m-1'><i class='icon-attach-4'></i> Abrir</button></a></td>";
                    lista += "</tr>";
                }
                lista += "</tbody>";
                lista += "</table></div>";
                $("#nota_modal_arquivos").slideDown("fast").html(lista);
            }
        });
    }
    ajax.upload.addEventListener('progress', function (evt) {

        pct = Math.floor((evt.loaded * 100) / evt.total);
        progressBar.style.width = pct + '%';
        progressBar.getElementsByTagName('span')[0].textContent = pct + '%';

    }, false);

    ajax.open('POST', 'NOTAUPLOAD');
    ajax.send(formData);

}