<!DOCTYPE html>
<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <link rel="icon" href="../images/logo-pq.png">
  
  <title>Abertura Simples - Cadastro</title>

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
  <script src="css/semantic/dist/components/checkbox.js"></script>

  <style type="text/css">
    body {
      background-image: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%,rgba(0,0,0,0.4) 100%), url("../images/imagem-cadastro.jpg");
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
    }
    .container {
      max-width: 400px;
    }
    .conteudo_colado {
      float: right;
      margin: 25px 25px 0px 0px;
      position: relative;
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
</head>
<body>
    <div class="conteudo_colado">Já possui cadastro? &nbsp;&nbsp;&nbsp;&nbsp;<a href="login.php" class="ui small blue button">Faça login</a></div>
    <br>
    <div class="ui container">
      <form class="ui form">
        <h2 class="ui icon header">
          <img class="ui image" src="../images/logo-gr.png">
          <div class="content">
            Cadastre-se
            <div class="sub header">Preencha o formulário para aprovação de seu cadastro no Abertura Simples.</div>
          </div>
        </h2>
        <div class="fields">
          <div class="field">
            <label>Nome</label>
            <input type="text" id="nome_inscricao" placeholder="Nome" autofocus="">
          </div>
          <div class="field">
            <label>Sobrenome</label>
            <input type="text" id="sobrenome_inscricao" placeholder="Sobrenome">
          </div>
          <div class="field">
            <label>Email</label>
            <input type="text" id="email_inscricao" placeholder="Email">
          </div>
        </div>
        <div class="fields">
          <div class="field">
            <label>Telefone</label>
            <input type="text" id="telefone_inscricao" placeholder="Telefone">
          </div>
          <div class="field">
            <label>Estado</label>
            <input type="text" id="uf_inscricao" placeholder="Estado">
          </div>
          <div class="field">
            <label>Cidade</label>
            <input type="text" id="cidade_inscricao" placeholder="Cidade">
          </div>
        </div>
        <div class="fields">
          <div class="field">
            <label>Senha</label>
            <input type="password" id="senha" placeholder="*****">
          </div>
          <div class="field">
            <label>Confirmar Senha</label>
            <input type="password" id="confirmar_senha" placeholder="*****">
          </div>
        </div>
        <div class="fields">
          <div class="field">
            <label>Termos e condições</label>
            <textarea rows="6" cols="66" id="termo" style="resize: none" readonly="readonly"></textarea>
          </div>
        </div>
        <div class="field">
          <div class="ui checkbox">
            <input type="checkbox" tabindex="0" class="hidden" id="aceito_termos">
            <label>Eu li e aceito os termos de contrato</label>
          </div>
        </div>
        <button class="ui green disabled button" id="cadastrar_btn">Cadastrar</button>
      </form>     
    </div>
    <div class="footer">
        <a href="https://aberturasimples.com.br" target="_blank">Abertura Simples</a> - Todos os direitos Reservados © 2021
  </div>
</body>

</html>

<script>
  $('.ui.checkbox').checkbox();

  var texto = "1. Se for o contratante uma Pessoa Jurídica, o texto deve ser escrito da seguinte forma: CONTRATANTE: (Nome do Contratante), com sede em (xxx), na Rua (xxx), nº (xxx), bairro (xxx), Cep (xxx), no Estado (xxx), inscrita no CNPJ sob o n° (xxx), e no cadastro estadual sob o nº (xxx), neste ato representado pelo seu diretor (xxx), (Nacionalidade), (Estado Civil), (Profissão), Carteira de Identidade nº (xxx), CPF n° (xxx), residente e domiciliado na Rua (xxx), nº (xxx), bairro (xxx), Cep (xxx), Cidade (xxx), no Estado(xxx). \n\n2. Se for o contratado uma Pessoa Jurídica, o texto deve ser escrito da seguinte forma: CONTRATADO: (Nome do Contratado), com sede em (xxx), na Rua (xxx), nº (xxx), bairro (xxx), Cep (xxx), no Estado (xxx), inscrita no CNPJ sob o n° (xxx), e no cadastro estadual sob o nº (xxx), neste ato representado pelo seu diretor (xxx), (Nacionalidade), (Estado Civil), (Profissão), Carteira de Identidade nº (xxx), CPF n° (xxx), residente e domiciliado na Rua (xxx), nº (xxx), bairro (xxx), Cep (xxx), Cidade (xxx), no Estado(xxx). \n\n3. Podem ser estabelecidos diferentes tipos ou formas de pagamento, facultando aos contratantes o pagamento ser feito semanalmente, bimestralmente, anualmente, etc, dependendo das características do serviço, devendo a quantia ser paga assim que se realizar o serviço. \n\n4. É livre às partes estabelecer este prazo, que variará de acordo com os interesses ou as características específicas do serviço a ser realizado. \n\n5. Em relação ao prazo para realização do serviço, é livre entre as partes compactuar conforme lhes convém, podendo ser em anos, meses, semanas, etc.";

  $(document).ready(function(){
    $('#termo').bind("cut copy paste",function(e) {
          e.preventDefault();
      });
    $('#termo').html(texto);  
  });  

  $(document).on('change', '#aceito_termos', function() {
    if(this.checked) {
      $('#cadastrar_btn').removeClass('disabled');
    } else {
      $('#cadastrar_btn').addClass('disabled');
    }
});
</script>