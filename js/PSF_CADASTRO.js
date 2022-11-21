$(document).ready(function () {


    // solicitacaoVerifica();

    $("#btCadastro").click(function () {
        var tipo = $("#tipo").val();
        var veiculo = $("#veiculo").val();
        var data = $("#psfData").val();
        var hora = $("#psfHora").val();
        var obs = $("#obs").val();


        form_cadastro(tipo, veiculo, data, hora, obs);
    });
    $("#btNova").click(function () {

        form_tipo();
        form_veiculo();
        form_verifica();

        $("#notaFormulario").modal("show");

    });
    $("#formCancela").click(function () {
        var solicitacao = $("#NumeroSolicitacao").text();
        CancelaSolicitacao(solicitacao);
    });
    $("#formConclui").click(function () {
        var solicitacao = $("#NumeroSolicitacao").text();
        ConcluiSolicitacao(solicitacao);
    });
    function form_verifica() {


        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "verifica" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PSFCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var ativa = dados.ativa;
                var dados = dados.dados;

                if (ativa === "sim") {
                    //
                    $("#id").text(dados.id);
                    $("#tipo").val(dados.tipo);
                    $("#veiculo").val(dados.veiculo);
                    $("#psfData").val(dados.data);
                    $("#psfHora").val(dados.hora);

                    var bt = "<i class='icon-edit text-info'></i> Editar";
                    $("#btCadastro").html(bt);

                    $("#obs").val("");
                    $("#obs").attr("disabled", true);
                    alert("Já existe uma solicitação em aberto!");
                } else {

                    $("#obs").val("");
                    $("#obs").attr("disabled", false);
                    var bt = "<i class='icon-ok-circled-1 text-success'></i> Cadastrar";
                    $("#btCadastro").html(bt);
                }
            }
        });
    }
    function form_tipo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaTipo" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PSFCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">TIPO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].id + "\">" + linha[i].nome + "</option>";
                }
                $("#tipo").slideDown("fast").html(linhas);
            }
        });
    }

    function form_veiculo() {

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "listaVeiculo" },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PSFCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {
                var linhas = "<option value=\"0\">VEÍCULO</option>";
                var linha = eval(dados);
                for (var i = 0; i < linha.length; i++) {
                    linhas += "<option value=\"" + linha[i].placa + "\">" + linha[i].placa + " " + linha[i].nome + "</option>";
                }
                $("#veiculo").slideDown("fast").html(linhas);
            }

        });
    }

    function form_cadastro(tipo, veiculo, data, hora, obs) {

        $("#retornoCadastro").text("");
        $("#retornoCadastro").removeClass();

        $.ajax({
            type: 'post', //Definimos o método HTTP usado
            data: { acao: "cadastro", tipo: tipo, veiculo: veiculo, data: data, hora: hora, obs: obs },
            dataType: 'json', //Definimos o tipo de retorno
            url: 'PSFCADASTRO', //Definindo o arquivo onde serão buscados os dados
            success: function (dados) {

                if (dados.erro === "1") {

                    var msg = "<i class='icon-attention'></i> " + dados.msg;
                    var classe = "bg bg-danger rounded font-weight-bold text-white pt-2 pb-2";
                    $("#retornoCadastro").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast");

                } else {
                    var msg = "<i class='icon-ok-circle'></i> " + dados.msg;
                    var classe = "bg bg-success rounded font-weight-bold text-white pt-2 pb-2";

                    $("#retornoCadastro").slideDown("fast").addClass(classe).append(msg).delay(1200).slideUp("fast", function () {

                        window.location.reload();
                    });
                }
            }
        });
    }
});