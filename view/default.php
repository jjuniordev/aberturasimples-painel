<?php include '../model/seguranca.php'; 
    $url_atual = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    protegePagina($url_atual); ?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
        <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=0.25" />
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="Junior Nascimento">
        <link rel="icon" href="../images/logo-pq.png">

        <!-- <title>Painel <?php //echo $qtd_title; ?></title> -->

        <!-- Bootstrap core CSS -->
        <!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <!-- <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet"> -->

        <!-- Custom styles for this template -->
        <!-- <link href="css/justified-nav.css" rel="stylesheet">
        <link href="css/signin.css" rel="stylesheet"> -->

        <link rel="stylesheet" type="text/css" href="css/semantic/dist/semantic.min.css">
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-92309321-1"></script>
        <script>
         window.dataLayer = window.dataLayer || [];
         function gtag(){dataLayer.push(arguments);}
         gtag('js', new Date());

         gtag('config', 'UA-92309321-1');
        </script>
        <script
          src="https://code.jquery.com/jquery-3.1.1.min.js"
          integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
          crossorigin="anonymous"></script>
        <script src="css/semantic/dist/semantic.min.js"></script>       
        <script src="../bower_components/chart.js/dist/Chart.js"></script> 
        <script type="text/javascript">
            function atualizar() {
              location.reload(true)
            }
            window.setInterval("atualizar()",600000);
        </script>
        

    <!-- 
    ***********************************
    * SCRIPT JSON QUE BUSCA CIDADE/UF * 
    *********************************** -->        
    <script type="text/javascript">         
        $(document).ready(function () {        
            $.getJSON('estados_cidades.json', function (data) {
                var items = [];
                var options = '<option value="">Selecionar</option>';    
                $.each(data, function (key, val) {
                    options += '<option value="' + val.sigla + '">' + val.sigla + '</option>';
                });                 
                $("#estados").html(options);                
                
                $("#estados").change(function () {              
                
                    var options_cidades = '';
                    var str = "";                   
                    
                    $("#estados option:selected").each(function () {
                        str += $(this).text();
                    });
                    
                    $.each(data, function (key, val) {
                        if(val.sigla == str) {                           
                            $.each(val.cidades, function (key_city, val_city) {
                                options_cidades += '<option value="' + val_city + '">' + val_city + '</option>';
                            });                         
                        }
                    });
                    $("#cidades").html(options_cidades);                    
                }).change();                    
            });        
        });
        
    </script>  

    <!-- 
    *************************************
    * SCRIPT SELECIONAR LINHA DE TABELA * 
    ************************************* -->  

<script type="text/javascript">
    $(function () {
    $("td.leadEditavel").dblclick(function () {
        var conteudoOriginal = $(this).text();
        var campo = $(this).attr('name');
        var id = $(this).attr('id');
        
        $(this).addClass("warning");
        $(this).html("<input type='text' value='" + conteudoOriginal + "' />");
        $(this).children().first().focus();
        $(this).children().first().select();

        $(this).children().first().keypress(function (e) {
            if (e.which == 13) {
                var novoConteudo = $(this).val(); 
                var confirma = confirm('De: ' + conteudoOriginal + ' \nPara: ' + novoConteudo + '\n\nConfirmar esta ateração?');                
                if (confirma == true) {
                    $(this).parent().text(novoConteudo);
                    $(this).parent().removeClass("warning");                    
                    $.ajax({
                        url: '../model/ajaxAlterarLead.php',
                        type: 'POST',
                        data: {
                            'campo': campo,
                            'valor': novoConteudo,
                            'id': id,
                        }
                    });
                } 
            }
        });
        
        $(this).children().first().blur(function(){
        $(this).parent().text(conteudoOriginal);
        $(this).parent().removeClass("warning");
    });
    });
});
</script>

    <!--
    *********************************
    * ESTILO DA TABELA SELECIONAVEL *
    ********************************* -->
    <style type="text/css">
        .record_table {
            width: 100%;
            /* border-collapse: collapse; */
        }
        .record_table tr:hover {
            background: #eee;
        }
        .record_table td {
            /*border: 1px solid #eee;*/
        }
        .highlight_row {
            background: #000;
        }
    </style>
    </head>
    <body>


    </body>

</html>
