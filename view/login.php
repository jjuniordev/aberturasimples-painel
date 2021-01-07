

<?php //include('menu.php'); ?>

<?php 
    include('../model/seguranca.php'); 
    if (isset($_GET['error'])) {
      $error = $_GET['error'];
    } else {
      $error = 0;
    }

    if (!isset($_GET['url'])) {
      
    } else {
      $url = $_GET['url'];  
    }
    
?>

<!DOCTYPE html>
<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <link rel="icon" href="../images/logo-pq.png">
  
  <title>Painel - Login</title>

  <!-- Site Properties -->
  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/reset.css">
  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/site.css">

  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/container.css">
  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/grid.css">
  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/header.css">
  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/image.css">
  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/menu.css">

  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/divider.css">
  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/segment.css">
  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/form.css">
  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/input.css">
  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/button.css">
  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/list.css">
  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/message.css">
  <link rel="stylesheet" type="text/css" href="css/semantic/dist/components/icon.css">
  <link rel="stylesheet" type="text/css" href="css/semantic/dist/semantic.min.css">

  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="css/semantic/dist/components/form.js"></script>
  <script src="css/semantic/dist/components/transition.js"></script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-92309321-1"></script>
<script>
 window.dataLayer = window.dataLayer || [];
 function gtag(){dataLayer.push(arguments);}
 gtag('js', new Date());
 gtag('config', 'UA-92309321-1');
</script>
  <style type="text/css">
    body {
      /*background: url("../images/imagem-cadastro.jpg") no-repeat center center;*/
      background-image: linear-gradient(to bottom, rgba(0,0,0,0.6) 0%,rgba(0,0,0,0.6) 100%), url("../images/imagem-cadastro.jpg");
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
    }
    body > .grid {
      height: 100%;
    }
    .image {
      margin-top: -100px;
    }
    .column {
      max-width: 450px;
    }
    .footer {
      position:absolute;
      bottom:0;
      margin-bottom: 10px;
      width:100%;
      text-align: center;
      color: #FFF;
    }
  </style>
  <script>
  $(document)
    .ready(function() {
      $('.ui.form')
        .form({
          fields: {
            email: {
              identifier  : 'email',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your e-mail'
                },
                {
                  type   : 'email',
                  prompt : 'Please enter a valid e-mail'
                }
              ]
            },
            password: {
              identifier  : 'password',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your password'
                },
                {
                  type   : 'length[6]',
                  prompt : 'Your password must be at least 6 characters'
                }
              ]
            }
          }
        })
      ;
    })
  ;
  </script>
</head>
<body>

<div class="ui middle aligned center aligned grid">
  <div class="column">
    <h2 class="ui green image header">
      <img src="../images/logo-gr.png" class="image">
      <div class="content">
        Painel Associados - Login
      </div>
    </h2>
    <?php
        if (isset($url)) {
           echo '<form class="ui large form" method="POST" action="../controller/valida.php?url='.$url.'">';
         } else {
          echo '<form class="ui large form" method="POST" action="../controller/valida.php">';
         }
        
      ?>
      <div class="ui stacked segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="usuario" placeholder="Usuário" autofocus>
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="senha" placeholder="Senha">
          </div>
        </div>
        <div class="ui fluid large green submit button">ENTRAR</div>
        <?php 
            if ($error!='true') {
                echo "<div class='ui negative message'>Usuário ou senha inválidos!</div>";
            }
         ?>
      </div>

      <div class="ui error message"></div>

    </form>

    <div class="ui message">
      <!-- Não possui login? <a href="inscricao.php">Inscreva-se</a> -->
      Não possui login? <a href="#">Inscreva-se</a>
    </div>
  </div>

</div>
<div class="footer">
    <a href="https://aberturasimples.com.br" target="_blank">Abertura Simples</a> - Todos os direitos Reservados © 2019
  </div>
</body>

</html>
