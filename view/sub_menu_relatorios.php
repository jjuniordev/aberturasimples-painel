	<div class="ui inverted menu" id="submenu">
		<a id="leads_unidades" class="item" href="leads_unidades.php">
    		Leads por Unidades
  		</a>
  		<a id="leads_enviados" class="item" href="leads_enviados.php">
    		Leads Enviados
  		</a>
		<a id="identificadores" class="item" href="identificadores.php">
	    	Identificadores
	  	</a>
	</div>
<script type="text/javascript">

	var documento = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);

	switch (documento) {
		case 'leads_unidades.php':
			$('#leads_unidades').addClass('active');
			$('#identificadores').removeClass('active');
			$('#leads_enviados').removeClass('active');
			break;
		case 'identificadores.php':
			$('#identificadores').addClass('active');
			$('#leads_unidades').removeClass('active');
			$('#leads_enviados').removeClass('active');
			break;
		case 'leads_enviados.php':
			$('#identificadores').removeClass('active');
			$('#leads_unidades').removeClass('active');
			$('#leads_enviados').addClass('active');
			break;
		case 'leads_atribuidos.php':
			$('#enviados').addClass('active');
			$('#geral').removeClass('active');
			$('#deletados').removeClass('active');
			$('#pendentes').removeClass('active');
			$('#leads_enviados').removeClass('active');
			break;
		case 'pendentes.php':
			$('#enviados').removeClass('active');
			$('#geral').removeClass('active');
			$('#deletados').removeClass('active');
			$('#pendentes').addClass('active');
			$('#leads_enviados').removeClass('active');
	}
</script>

<script type="text/javascript">
	$(function buscaTabela(){
	$(".input-search").keyup(function(){
	//pega o css da tabela 
	var tabela = $(this).attr('alt');
	if( $(this).val() != ""){
	    $("."+tabela+" tbody>tr").hide();
	    $("."+tabela+" td:contains-ci('" + $(this).val() + "')").parent("tr").show();
	} else{
	    $("."+tabela+" tbody>tr").show();
	}
	}); 
	});
	$.extend($.expr[":"], {
	"contains-ci": function(elem, i, match, array) {
	    return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
	});
</script>