var formFiles, divReturn, progressBar;

formFiles = document.getElementById('formFiles');
divReturn = document.getElementById('return');
progressBar = document.getElementById('progressBar');

formFiles.addEventListener('submit', sendForm, false);

function sendForm(evt) {

    $(".spq").removeClass("d-none");

    var formData, ajax, pct;

    formData = new FormData(evt.target);
    formData.append('sma', $("#NumeroSolicitacao").text());
    formData.append('id', $("#itemId").text());
    formData.append('pa', $("#itemPA").val());
    formData.append('tipo', $("#tipoUpload").text());
    formData.append('obs', $("#obsUpload").val());

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

                    $("#form_upload").modal("hide");
                    window.location.reload();
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

    ajax.upload.addEventListener('progress', function (evt) {

        pct = Math.floor((evt.loaded * 100) / evt.total);
        progressBar.style.width = pct + '%';
        progressBar.getElementsByTagName('span')[0].textContent = pct + '%';

    }, false);

    ajax.open('POST', 'SMAUPLOAD');
    ajax.send(formData);

}