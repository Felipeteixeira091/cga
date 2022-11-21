var formFiles, divReturn, progressBar;

formFiles = document.getElementById('formFiles');
divReturn = document.getElementById('return');
progressBar = document.getElementById('progressBar');

formFiles.addEventListener('submit', sendForm, false);

function sendForm(evt) {

    var formData, ajax, pct;

    formData = new FormData(evt.target);
    formData.append('bo_id', $("#Detalhe_id").text());

    ajax = new XMLHttpRequest();

    ajax.onreadystatechange = function () {

        $("#retornoUpload").text("");
        $("#retornoUpload").removeClass();

        if (ajax.readyState == 4) {
            formFiles.reset();

            var dados = JSON.parse(ajax.response);

            $("#spinnerUpload").addClass("d-none");
            if (dados.erro === "1") {
                var msg = "<i class='bi bi-exclamation-circle'></i> " + dados.msg;
                var classe = "bg bg-danger bg-gradient rounded font-weight-bold text-white pt-2 pb-2";

                $("#retornoUpload").slideDown("fast").addClass(classe).append(msg).delay(2200).slideUp("fast", function () {
                    //$("#DetalheBO").modal("hide");

                });
            } else {
                var msg = "<i class='bi bi-check-circle'></i> " + dados.msg;
                var classe = "bg bg-success bg-gradient rounded font-weight-bold text-white pt-2 pb-2";

                $("#retornoUpload").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {
                    $("#form_upload").modal("hide");
                });

            }
            progressBar.style.display = 'none';
        } else {
            progressBar.style.display = 'block';
            divReturn.style.display = 'block';

            var msg = "<i class='icon-attention'></i> Enviando arquivo.";
            var classe = "bg bg-primary bg-gradient rounded font-weight-bold text-white pt-2 pb-2";

            $("#spinnerUpload").removeClass("d-none");
            $("#retornoUpload").slideDown("fast").addClass(classe).append(msg);

            //            $("#load").append("Enviando arquivo!");
        }
    }

    ajax.upload.addEventListener('progress', function (evt) {

        pct = Math.floor((evt.loaded * 100) / evt.total);
        progressBar.style.width = pct + '%';
        progressBar.getElementsByTagName('span')[0].textContent = pct + '%';

    }, false);

    ajax.open('POST', 'SBOUPLOAD');
    ajax.send(formData);

}