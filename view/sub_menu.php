
<div class="ui inverted menu" id="submenu">
	<a id="geral" class="item" href="gerais.php">
	    Gerais
	  </a>
	<a id="pendentes" class="item" href="pendentes.php">
	    Pendentes
	  </a>
	  <a id="enviados" class="item" href="leads_atribuidos.php">
	    Enviados
	  </a>
	  <a id="deletados" class="item" href="leads_inativos.php">
	    Deletados
	  </a>
	</div>

<script type="text/javascript">
	
	var documento = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);

	switch (documento) {
		case 'leads_inativos.php':
			$('#deletados').addClass('active');
			$('#geral').removeClass('active');
			$('#enviados').removeClass('active');
			$('#pendentes').removeClass('active');
			var status = "= 3";
			break;
		case 'gerais.php':
			$('#geral').addClass('active');
			$('#deletados').removeClass('active');
			$('#enviados').removeClass('active');
			$('#pendentes').removeClass('active');
			var status = "in (1,2,3)";
			break;
		case 'leads_atribuidos.php':
			$('#enviados').addClass('active');
			$('#geral').removeClass('active');
			$('#deletados').removeClass('active');
			$('#pendentes').removeClass('active');
			var status = "= 2";
			break;
		case 'pendentes.php':
			$('#enviados').removeClass('active');
			$('#geral').removeClass('active');
			$('#deletados').removeClass('active');
			$('#pendentes').addClass('active');
			var status = "= 1";
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
			$.post('busca_geral.php', dados, function(retorna){
				//Mostra dentro da ul os resultado obtidos 
				$(".resultado").html(retorna);
				$("#tabela_padrao").hide();
				$('#reativar').addClass('disabled');
				$('#btn-atribuir-leads').addClass('disabled');
				$('#trocar-combo').addClass('disabled');
				$('#del-lead').addClass('disabled');
				$('#enviar-combo').addClass('disabled');
				$('input:checkbox').prop('checked', false);
			});
		}else{
			$(".resultado").html('');
			$("#tabela_padrao").show();
			$('#reativar').addClass('disabled');
			$('#btn-atribuir-leads').addClass('disabled');
			$('#trocar-combo').addClass('disabled');
			$('#del-lead').addClass('disabled');
			$('#enviar-combo').addClass('disabled');
			$('input:checkbox').prop('checked', false);
		}		
	});
});
</script>

