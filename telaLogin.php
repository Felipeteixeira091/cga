<?php
include "versao.php";
?>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo $sistema ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

    <link rel="icon" href="css/ico.png">
    <link rel="stylesheet" href="css/login.css<?php echo $versao; ?>">
    <link rel="stylesheet" href="css/all.css<?php echo $versao; ?>">
    <script src="js/js_login.js<?php echo $versao; ?>" type="text/javascript"></script>
    <link rel="stylesheet" href="css/spin.css<?php echo $versao; ?>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <script>
        function somenteNumeros(e) {
            var charCode = e.charCode ? e.charCode : e.keyCode;
            // charCode 8 = backspace   
            // charCode 9 = tab
            if (charCode != 8 && charCode != 9) {
                // charCode 48 equivale a 0   
                // charCode 57 equivale a 9
                if (charCode < 48 || charCode > 57) {
                    return false;
                }
            }
        }
    </script>
</head>
<style>
    body {
        background-color: MidnightBlue;
    }

    #btLogin {
        background-color: MidnightBlue;
        color: white;
    }

    #h1_titulo {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    .material-icons.md-18 {
        vertical-align: middle;
    }

    @media (max-width:570px) {
        #logoIco {

            width: 100%;
        }
        
    }
</style>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <div class="container theme-showcase" role="main">
        <center>
            <figure class="figure mt-5" id="logoIco">
                <img src="./resources/css/logo-neg.png" class="figure-img img-fluid rounded w-75" alt="...">
                <figcaption class="figure-caption text-end">Tecnologia LTDA.</figcaption>
            </figure>

            <div class="container">
                <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                    <div class="card my-5 " id="formLogin">
                        <div id="login_corpo" class="card-body">
                            <div class="row mt-1 ml-1 mr-1">
                                <div class="col">
                                    <label class="float-start">
                                        <span class="badge badge-pill border mt-1 mb-1 text-body"><i class="bi bi-person-circle"></i> (EX: 29819)</span>
                                    </label>
                                    <input onkeypress="return somenteNumeros(event)" maxlength="6" type="text" id="re" class="form-control  p-3 shadow-sm" placeholder="RE (SOMENTE NÚMEROS)" autofocus>
                                </div>
                            </div>
                            <div class="row mt-2 ml-1 mr-1">
                                <div class="col">
                                    <label class="float-start">
                                        <span class="badge badge-pill border mt-1 mb-1 text-body"><i class="bi bi-key-fill"></i> SENHA</span>
                                    </label>
                                    <input type="password" id="senha" class="form-control p-3 shadow-sm" placeholder="***">
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="d-grid gap-2">
                                    <button id="btLogin" class="btn btn-block"><i class="bi bi-box-arrow-in-right shadow"></i> Entrar</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div id="retornoLogin"></div>
                            <div id="spin" class="bg-primary bg-gradient rounded p-2 text-white" style="display: none;">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Aguarde...
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </center>
    </div>
    <footer class="fixed-bottom text-center mb-1">
        <small class="text-muted">ICOMON © Todos os direitos reservados. Desenvolvido por Felipe Teixeira</small>
    </footer>
</body>

</html>