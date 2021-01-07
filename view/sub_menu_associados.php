
 <style type="text/css">
	
	#cadLead {
		display: none;
	}
	#loader {
 		display: none;
 	}
 	#gambi2 {
 		display: none;
 	}

</style>

<div class="ui inverted menu" id="submenu">
	
	<a id="recebidos" class="item" href="recebidos.php">
	    Novos
	  </a>	  
	  <a id="atendimento" class="item" href="followup.php" >
	    Atendidos
	  </a>
	  <a id="clientes" class="item" href="clientes.php">
	    Clientes
	  </a>
	  <a id="rejeitados" class="item" href="rejeitados.php">
	    Perdidos
	  </a>	  
	  <a id="deletados" class="item" href="deletados_ext.php">
	    Deletados
	  </a>
	  <a id="geral" class="item" href="gerais_ext.php">
	    Todos
	  </a>

  <div class="right menu">
  </div>
</div>
<div class="ui icon info message">
	<i class="close icon"></i>
	  <i class="lightbulb icon"></i>
	  <div class="content">
	    <div class="header">
	      Dica! 
	    </div>
	    <p>Cadastre seus leads gerados por Telefone e Whatsapp através do botão &nbsp;&nbsp; <a id="btn-incluir-lead2" data-element="#cadLead" class="ui compact labeled icon mini black button">
		  <i class="plus icon"></i>
		  Cadastrar
		</a></p>
	  </div>
	</div>

	<div class="ui two column grid">
	<br>
	
		<div class="five wide column">
			<h3 id="titulo" class="ui left aligned left floated header">
		      
		    </h3>	
		    <br>	    
		</div>
	</div> 
	

<script type="text/javascript">
	
	var documento = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);

	switch (documento) {
		case 'clientes.php':
			$('#clientes').addClass('active');
			$('#geral').removeClass('active');
			$('#atendimento').removeClass('active');
			$('#recebidos').removeClass('active');
			$('#rejeitados').removeClass('active');
			$('#deletados').removeClass('active');
			$('#titulo').html('Clientes<div class="sub header">Tabela de leads convertidos em clientes.</div>');
			var status = "= 5";
			break;
		case 'gerais_ext.php':
			$('#geral').addClass('active');
			$('#clientes').removeClass('active');
			$('#atendimento').removeClass('active');
			$('#recebidos').removeClass('active');
			$('#rejeitados').removeClass('active');
			$('#deletados').removeClass('active');
			$('#titulo').html('Gerais<div class="sub header">Tabela de leads gerais do sistema.</div>');
			var status = "in (2,4,5,6,7)";
			break;
		case 'followup.php':
			$('#atendimento').addClass('active');
			$('#geral').removeClass('active');
			$('#clientes').removeClass('active');
			$('#recebidos').removeClass('active');
			$('#rejeitados').removeClass('active');
			$('#deletados').removeClass('active');
			$('#titulo').html('Follow Up<div class="sub header">Tabela de leads que estão em negociação.</div>');
			var status = "= 4";
			break;
		case 'recebidos.php':
			$('#atendimento').removeClass('active');
			$('#geral').removeClass('active');
			$('#clientes').removeClass('active');
			$('#recebidos').addClass('active');
			$('#rejeitados').removeClass('active');
			$('#deletados').removeClass('active');
			$('#titulo').html('Novos<div class="sub header">Tabela contendo os leads recebidos.</div>');
			var status = "= 2";
			break;
		case 'rejeitados.php':
			$('#atendimento').removeClass('active');
			$('#geral').removeClass('active');
			$('#clientes').removeClass('active');
			$('#recebidos').removeClass('active');
			$('#rejeitados').addClass('active');
			$('#deletados').removeClass('active');
			$('#titulo').html('Rejeitados<div class="sub header">Tabela de leads que foram rejeitados.</div>');
			var status = "= 6";
			break;
		case 'deletados_ext.php':
			$('#atendimento').removeClass('active');
			$('#geral').removeClass('active');
			$('#clientes').removeClass('active');
			$('#recebidos').removeClass('active');
			$('#rejeitados').removeClass('active');
			$('#deletados').addClass('active');
			$('#titulo').html('Deletados<div class="sub header">Tabela de leads deletados.</div>');
			var status = "= 7";
			break;
	}
</script>

<script>
	$(function(){
	//Pesquisar os cursos sem refresh na página
	$("#pesquisa").keyup(function(){
		
		var pesquisa = $(this).val();
		
		//Verificar se há algo digitado
		if(pesquisa != '' && pesquisa.length > 2){
			var dados = {
				palavra : pesquisa,
				status : status
			}		
			$.post('busca_geral_associados.php', dados, function(retorna){
				//Mostra dentro da ul os resultado obtidos 
				$(".resultado").html(retorna);
				$("#tabela_padrao").hide();
				$('#reativar').addClass('disabled');
				$('#btn-atribuir-leads').addClass('disabled');
				$('#trocar-combo').addClass('disabled');
				$('#del-lead').addClass('disabled');
				$('#enviar-combo').addClass('disabled');
				$('.i_check').prop('checked', false);
			});
		}else{
			$(".resultado").html('');
			$("#tabela_padrao").show();
			$('#reativar').addClass('disabled');
			$('#btn-atribuir-leads').addClass('disabled');
			$('#trocar-combo').addClass('disabled');
			$('#del-lead').addClass('disabled');
			$('#enviar-combo').addClass('disabled');
			$('.i_check').prop('checked', false);
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
            $('#gambi2').toggle('slow');
        });
        $("#btn-incluir-lead-ini").click(function(e){
            e.preventDefault();
            el = $(this).data('element');
            $(el).toggle('slow');
            $('[name=nome]').focus();
            $('#gambi2').toggle('slow');
        });
        $("#btn-incluir-lead2").click(function(e){
            e.preventDefault();
            el = $(this).data('element');
            $(el).toggle('slow');
            $('[name=nome]').focus();
            $('#gambi2').toggle('slow');
        });
    });
</script>

<script type="text/javascript">
	$('.message .close')
  .on('click', function() {
    $(this)
      .closest('.message')
      .transition('fade')
    ;
  })
;
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
					$('#btncadlead').addClass('disabled');
					$('#divemail').addClass('error');
					alert('Não é possível cadastrar este email.\nDescrição: Este email já está cadastrado em outra unidade!');

				} else if (data == 2) {
					alert('ATENÇÃO:\nEste email já está cadastrado em sua base de dados.');
					$('#btncadlead').removeClass('disabled');
				} else {
					$('#btncadlead').removeClass('disabled');
					$('#divemail').removeClass('error');
				}
			}
		});
	});
</script>