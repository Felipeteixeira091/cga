var formFiles, divReturn, progressBar;

formFiles = document.getElementById('formFiles');
divReturn = document.getElementById('return');
progressBar = document.getElementById('progressBar');

formFiles.addEventListener('submit', sendForm, false);

function sendForm(evt) {

    $("#retornoUpload").empty();
    $("#retornoUpload").text("");
    $("#retornoUpload").removeClass();

    var formData, ajax, pct;

    formData = new FormData(evt.target);

    ajax = new XMLHttpRequest();

    ajax.onreadystatechange = function() {

        if (ajax.readyState == 4) {
            formFiles.reset();

            var dados = JSON.parse(ajax.response);

            if (dados.erro === "1") {

                var msg = "<i class='icon-attention'></i> " + dados.msg;
                var classe = "bg-danger rounded font-weight-bold text-white pt-2 pb-2";

                $("#retornoUpload").slideDown("fast").addClass(classe).append(msg).delay(1500).slideUp("fast");

            } else {

               // $("#load").slideUp("fast");

                var classe = "bg bg-success rounded font-weight-bold text-white p-2";
                var msg = "<i class='icon-ok-circle'></i> " + dados.msg;

                $("#retornoUpload").slideDown("fast").addClass(classe).append(msg).delay(1600).slideUp("fast", function() {
                    $("#upload").modal("hide");
                    $("#md5Arquivo").val(dados.arq);
                    //window.location.reload();

                });
            }

            progressBar.style.display = 'none';
        } else {
            progressBar.style.display = 'block';
            divReturn.style.display = 'block';
            $("#upload").modal("hide");
        }
    }

    ajax.upload.addEventListener('progress', function(evt) {


        pct = Math.floor((evt.loaded * 100) / evt.total);
        progressBar.style.width = pct + '%';
        progressBar.getElementsByTagName('span')[0].textContent = pct + '%';

    }, false);

    ajax.open('POST', 'CCCUPLOAD');
    ajax.send(formData);

}