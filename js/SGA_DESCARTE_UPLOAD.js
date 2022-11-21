var formFiles, divReturn, progressBar;

formFiles = document.getElementById('formFiles');
divReturn = document.getElementById('return');
progressBar = document.getElementById('progressBar');

formFiles.addEventListener('submit', sendForm, false);

function sendForm(evt) {

    var formData, ajax, pct;

    formData = new FormData(evt.target);
    formData.append('id', $("#idDescarte").text());
    formData.append('observacao', $("#observacao").val());

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
                    //$("#DetalheBO").modal("hide");

                });
            } else {
                var msg = "<i class='icon-ok-circle'></i> " + dados.msg;
                var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                $("#retornoUpload").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {
                    $("#ModalReciclagemUpload").modal("hide");

                    var arquivo = "<span class='text-info text-nowrap font-weight-bold'>Concluído</span>";

                    $("#arquivo_" + $("#idDescarte").text()).html(arquivo);

                    $("#detalheBtnUp").addClass("d-none");
                    $("#detalheBtnDown").removeClass("d-none");
                    $("#detalheBtnDown").val(dados.nota);
                    $("#detalheNota").text("PDF");

                    $("#rowObs").removeClass("d-none");

                    $("#detalheObs").text($("#observacao").val());
                    $("#observacao").val("");

                });

            }
            progressBar.style.display = 'none';
        } else {
            progressBar.style.display = 'block';
            divReturn.style.display = 'block';

            var msg = "<i class='icon-attention'></i> Enviando arquivo.";
            var classe = "bg bg-info rounded font-weight-bold text-white pt-2 pb-2";

            $("#retornoUpload").slideDown("fast").addClass(classe).append(msg);

            //            $("#load").append("Enviando arquivo!");
        }
    }

    ajax.upload.addEventListener('progress', function (evt) {

        pct = Math.floor((evt.loaded * 100) / evt.total);
        progressBar.style.width = pct + '%';
        progressBar.getElementsByTagName('span')[0].textContent = pct + '%';

    }, false);

    ajax.open('POST', 'SGADESCARTEUPLOAD');
    ajax.send(formData);

}