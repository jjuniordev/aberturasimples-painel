<!DOCTYPE html>
<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <link rel="icon" href="../images/logo-pq.png">
  
  <title>Painel - Cadastro</title>

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

  <style type="text/css">
    body {
      background-color: #DADADA;
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
  </style>
</head>
<body>

<div class="ui middle aligned center aligned grid">
  <div class="column">
    <h2 class="ui green image header">
      <img src="../images/logo-gr.png" class="image">
      <div class="content">
        Cadastre-se
      </div>
    </h2>
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
        <div class="ui fluid large green submit button">CADASTRAR</div>
      </div>
      <div class="ui error message"></div>

    </form>

    <div class="ui message">
      Não possui login? <a href="#">Inscreva-se</a>
    </div>
  </div>
</div>

</body>

</html>