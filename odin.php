<!doctype html>
<html>

<head>
    
    <meta charset="iso-8859-1">
    <meta http-equiv="refresh" content="1800">
    <link rel="icon" href="css/ico.png">
    <title>Odin</title>
</head>

<body>
    <script>
        $(document).ready(function() {
            $('body iframe').css('margin', '0px');
            $('body iframe').css('padding', '0px');
            $('body iframe').css('width', '0px');
            $('body .main').next().css('margin', '0px');
            $('body .main').next().css('width', '0px');
            $('body .main').next().css('padding', '0px');
        });
    </script>
    <style>
        body {overflow: hidden; }
    </style>
    <div class="container" style="height:100vh; ">
        <iframe width="100%" style="position: absolute;" height="100%" src="https://app.powerbi.com/view?r=eyJrIjoiYjQxYWNiOTktZmQzZS00YzExLThkNmItYTVkZDM2MmU0MjNlIiwidCI6ImFlYWI5N2I1LTE2ZmQtNGM1NS05ZThiLTdmMmJiMWJlNGRmZCJ9" frameborder="0"></iframe>
    </div>
</body>

</html>