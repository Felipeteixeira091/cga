var formFiles, divReturn, progressBar;

formFiles = document.getElementById('formFiles');
divReturn = document.getElementById('return');
progressBar = document.getElementById('progressBar');

formFiles.addEventListener('submit', sendForm, false);

function sendForm(evt) {

    $(".spq").removeClass("d-none");

    var id_SCE = $("#sce_badge_id").text();

    var formData, ajax, pct;

    formData = new FormData(evt.target);
    formData.append('tipo', $("#sce_upload_tipo").val());
    formData.append('sce', $("#sce_badge_id").text());

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

                    $("#sce_modal_upload").modal("hide");

                    sce_dados_anexo(id_SCE);

                    if (dados.contador === 2) {
                        window.location.reload();
                    }
                    //obter_anexo($("#nota_badge_id").text());
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
    function sce_dados_anexo(sce) {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "anexo", sce: sce },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'SCECOLABORADOR', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                var linhas = "";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {

                    linhas += "<a href='sce/" + linha[i].arquivo + "' target='_blank' class='btn btn-sm btn-dark ml-2'><i class='icon-photo-1'></i> " + linha[i].tipo + "</a>";
                }
                $("#sce_form_anexo").slideDown("fast").html(linhas);
            }
        });

    }


    ajax.upload.addEventListener('progress', function (evt) {

        pct = Math.floor((evt.loaded * 100) / evt.total);
        progressBar.style.width = pct + '%';
        progressBar.getElementsByTagName('span')[0].textContent = pct + '%';

    }, false);

    ajax.open('POST', 'SCEUPLOAD');
    ajax.send(formData);

}