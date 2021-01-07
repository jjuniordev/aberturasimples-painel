<div class="ui container">
  <form method="POST" action="">
    <div id="cadLead" class="ui center aligned segment">
      <h4 class="ui header">
        Cadastrar Lead
      </h4>
      <div class="ui equal width form">
        <div class="fields">
          <div id="divnome" class="field">
            <label>Nome</label>
            <input type="text" id="nome" name="nome" placeholder="Ex.: João da Silva">
          </div>
          <div id="divemail" class="field">
            <label>Email</label>
            <input type="text" name="email" id="email_cad" placeholder="email@email.com">
          </div>
          <div id="divfone" class="field">
            <label>Telefone</label>
            <input type="text" name="telefone" placeholder="(xx) 0000-0000">
          </div>
        </div>
        <div class="fields">
          <div id="divestado" class="field">
            <label>Estado</label>
          <select id="estados" name="estado">
            <option value=""></option>
          </select>
          </div>
          <div id="divcidade" class="field">
            <label>Cidade</label>
            <select id="cidades" name="cidade"></select>
          </div>
          <div id="divident" class="field">
            <label>Identificador</label>
            <select name="identificador">
              <option value="">Selecionar...</option>
              <option value="whatsapp">Whatsapp</option>
              <option value="telefone">Telefone</option>
              <option value="presencial">Presencial</option>
              <option value="outros">Outros</option>
            </select>
          </div>
          <div id="divfaturamento" class="field">
            <label>Faturamento</label>
            <select name="faturamento">
              <option value="">Selecionar...</option>
              <option value="1">0 a 7 mil</option>
              <option value="2">7 a 50 mil</option>
              <option value="3">50 a 100 mil</option>
              <option value="4">+ 100 mil</option>
              <option value="5">Não sei</option>
            </select>
          </div>
          <div id="divtipo" class="field">
            <label>Tipo de Empresa</label>
            <select name="tipo_empresa">
              <option value="">Selecionar...</option>
              <option value="1">Comércio</option>
              <option value="2">Serviço</option>
              <option value="3">Indústria</option>
            </select>
          </div>
          <div id="divnicho" class="field">
            <label>Nicho da Empresa</label>
            <input type="text" name="nicho_empresa" placeholder="Ex.: Loja de Sapatos">
          </div>
        </div>
        <div id="divmsg" class="field">
          <label>Mensagem</label>
          <textarea rows="2" name="mensagem" placeholder="Escreva uma mensagem..."></textarea>
        </div>
        <input id="cadlead" type="button" value="Cadastrar" class="ui positive small button" onclick="">
        <input type="hidden" name="ok" id="ok" />
        <button id="btn-incluir-lead2" data-element="#cadLead" class="ui black small button">Cancelar</button>
        <div id="loader" class="">
          <div class="ui active inverted dimmer">
            <div class="ui text loader">Loading</div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <p></p>
</div>


  <script type="text/javascript">
    $('#cadlead').click(function(){
      
      var nome      = $('[name=nome]').val();
      var email     = $('[name=email]').val();
      var telefone  = $('[name=telefone]').val();
      var estado    = $('[name=estado]').val();
      var cidade    = $('[name=cidade]').val();
      var ident     = $('[name=identificador]').val();
      var fatur     = $('[name=faturamento]').val();
      var nicho     = $('[name=nicho_empresa]').val();
      var tipo     = $('[name=tipo_empresa]').val();
      var msg       = $('[name=mensagem]').val();
      if (nome == '') {
        $('#divnome').addClass('error');
        exit();       
      } else if (email == '') {
        $('#divemail').addClass('error');
        exit();
      } else if (estado == '' || estado == 'Selecionar') {
        $('#divestado').addClass('error');
        exit();
      } else if (ident == '') {
        $('#divident').addClass('error');
        exit();
      } 

      $.ajax({
        url: '../model/ajaxCadastrarLead.php',
            type: 'POST',
            data: {
                'nome': nome,
                'email': email,
                'telefone': telefone,
                'estado': estado,
                'cidade': cidade,
                'identificador': ident,
                'faturamento': fatur,
                'nicho': nicho,
                'tipo_empresa': tipo,
                'msg': msg,
              },
              beforeSend: function(){ 
        $('#loader').css({display:"block"});        
        },
        success: function(data) { //Se a requisição retornar com sucesso, 
            //ou seja, se o arquivo existe, entre outros
                //$('#sucesso').html(data); //Parte do seu 
                setTimeout(function(){
            $('#loader').css({display:"none"}); 
            $(window).attr('location','pendentes.php');
          },1000);          
                //HTML que voce define onde sera carregado o conteudo
                //PHP, por default eu uso uma div, mas da pra fazer em outros lugares
            }
      });
    });
  </script>
<script type="text/javascript">
  
  // FUNÇÃO QUE EXIBE/OCULTA O FORMULÁRIO DE INCLUIR NOVO LEAD MANUAL.
  $(function(){
        $("#btn-incluir-lead").click(function(e){
            e.preventDefault();
            el = $(this).data('element');
            $(el).toggle('slow');
            $('[name=nome]').focus();
        });
        $("#btn-incluir-lead2").click(function(e){
            e.preventDefault();
            el = $(this).data('element');
            $(el).toggle('slow');
            $('[name=nome]').focus();
        });
    });
</script>

<script>
  $('#email_cad').keyup(function(){
    var email_cad = $('#email_cad').val();
    $.ajax({
      url: '../controller/validaCadastroLead.php',
      type: 'POST',
      data: {
        'email_cad': email_cad
      },
      success: function(data) {
        if (data == 1) {
          $('#cadlead').addClass('disabled');
          $('#divemail').addClass('error');
          alert('Não é possível cadastrar este email.\nDescrição: Este email já está cadastrado em outra unidade!');

        } else if (data == 2) {
          alert('ATENÇÃO:\nEste email já está cadastrado em sua base de dados.');
          $('#cadlead').removeClass('disabled');
        } else {
          $('#cadlead').removeClass('disabled');
          $('#divemail').removeClass('error');
        }
      }
    });
  });
</script>