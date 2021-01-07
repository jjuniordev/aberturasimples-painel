<?php 
	//include 'menu.php';
?>
<?php
	function getResponsaveis_f() {

		$unidade_id = $_SESSION['usuarioUnidade'];

		$query = mysql_query("SELECT 
							    a.id
							    ,a.nome
							    ,a.sobrenome
							    ,b.nome_unidade
							FROM
							    tb_usuarios a
							INNER JOIN
								tb_unidades b
							ON a.id_unidade = b.id
							WHERE
							    active = 1
						    AND 
						    	a.id_unidade = $unidade_id
							ORDER BY a.nome ASC;");
		$res = '';
		while ($resp = mysql_fetch_array($query)) {
			$res .= '<div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="'.$resp['id'].'" name="responsavel" tabindex="0" class="hidden">
					        <label>'.utf8_encode($resp['nome']).' '.utf8_encode($resp['sobrenome']).'</label>
					      </div>
					    </div>';
		}

		echo $res;
	}
?>
<style type="text/css">
	#filtros, #gambi {
		display: none;
	}
</style>
<!-- <a class="ui icon basic small button" id="botao_filtro" title="Clique aqui para visualizar as opções de filtro">
				  <i class="filter icon"></i>
				</a> -->
<div class="ui container">
	<!-- <a class="ui icon basic small button" id="botao_filtro">
	  <i class="filter icon"></i>
	</a> -->
	<div class="ui segment" id="filtros">		
		<!-- <div class="ui divider"></div> -->
		<div class="ui three column internally celled grid">
			<div class="column">
				<h2 class="ui sub header">Selecionar responsável(is)</h2>
				<p>
					<div id="fields_r" class="grouped fields">
					    <?php getResponsaveis_f(); ?>
					    <div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="0" name="responsavel" tabindex="0" class="hidden">
					        <label>Sem Responsável</label>
					      </div>
					    </div>
				  	</div>
			  	</p>
			</div>
			<div class="column">
				<h2 class="ui sub header">Adicionar / remover colunas</h2>
				<p>
					<div id="fields_c" class="grouped fields">
						<div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="telefone" name="coluna" tabindex="0" class="hidden">
					        <label>Telefone</label>
					      </div>
					    </div>
						<div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="cidade" name="coluna" tabindex="0" class="hidden">
					        <label>Cidade</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="estado" name="coluna" tabindex="0" class="hidden">
					        <label>Estado</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="data" name="coluna" tabindex="0" class="hidden">
					        <label>Data</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="responsavel" name="coluna" tabindex="0" class="hidden">
					        <label>Responsável</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="identificador" name="coluna" tabindex="0" class="hidden">
					        <label>Identificador</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="origem" name="coluna" tabindex="0" class="hidden">
					        <label>Origem</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="campaign" name="coluna" tabindex="0" class="hidden">
					        <label>Campanha</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="midia" name="coluna" tabindex="0" class="hidden">
					        <label>Midia</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="nicho_empresa" name="coluna" tabindex="0" class="hidden">
					        <label>Nicho da Empresa</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="faturamento" name="coluna" tabindex="0" class="hidden">
					        <label>Faturamento</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="tipo_empresa" name="coluna" tabindex="0" class="hidden">
					        <label>Tipo de Empresa</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui checkbox">
					        <input type="checkbox" id="mensagem" name="coluna" tabindex="0" class="hidden">
					        <label>Mensagem</label>
					      </div>
					    </div>
				  	</div>	
				</p>				
			</div>	
			<div class="column">
				<h2 class="ui sub header">Quantidade de linhas</h2>
				<p>
					<div id="fields" class="grouped fields">
					    <div class="field">
					      <div class="ui radio checkbox">
					        <input type="radio" id="10" checked="" name="linha" tabindex="0" class="hidden">
					        <label>10</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui radio checkbox">
					        <input type="radio" id="25" name="linha" tabindex="0" class="hidden">
					        <label>25</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui radio checkbox">
					        <input type="radio" id="50" name="linha" tabindex="0" class="hidden">
					        <label>50</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui radio checkbox">
					        <input type="radio" id="100" name="linha" tabindex="0" class="hidden">
					        <label>100</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui radio checkbox">
					        <input type="radio" id="200" name="linha" tabindex="0" class="hidden">
					        <label>200</label>
					      </div>
					    </div>
				  	</div>
			  	</p>
			  	<button class="ui right floated small compact blue button" id="aplicar_filtro" title="Aplicar os filtros selecionados">Aplicar</button>	
			  	<button class="ui right floated small compact button" id="limpar_filtro" title="Resetar todos os filtros">Limpar</button>
			</div>
		</div>	
	</div>
	<span id="gambi"><p></p></span>
</div>
<?php 

	$linhas 		= @implode(",", $_SESSION['linhas']);
	$colunas 		= @implode(",", $_SESSION['colunas']);
	$responsaveis 	= @implode(",", $_SESSION['responsaveis']);
		

	if (@$linhas != "") {
		echo "<script>
				
				$('#fields input').each(function(){
						if ($(this).attr('id') == ".@$linhas.") {
							$(this).prop('checked',true);
						}
					});
				
			</script>";
	}

	if (@$_SESSION['colunas'] != "") {
		$tamanho = count($_SESSION['colunas']);
		echo "<script>$('#fields_c input').prop('checked',true);</script>";
		for ($i = 0; $i <= $tamanho; $i++) { 
			echo "<script>
				$('#fields_c input').each(function(){
						//
						if ($(this).attr('id') == '".@$_SESSION['colunas'][$i]."') {
							$(this).prop('checked',false);
						} 
					});
					$('#botao_filtro').addClass('black');
					$('#botao_filtro').removeClass('basic');
			</script>";
		}
	} else {
		$col = ["telefone","cidade","data","responsavel"];
		$tamanho = count($col);
		for ($i=0; $i <= $tamanho; $i++) {
			echo "<script>
				$('#fields_c input').each(function(){
					if ($(this).attr('id') == '".@$col[$i]."') {
						$(this).prop('checked',true);
					}
				});

			</script>";
		}
	}

	if ($responsaveis != "") {
		$tamanho_r = count($_SESSION['responsaveis']);
		for ($i=0; $i <= $tamanho_r; $i++) { 
			echo "<script>
				
				$('#fields_r input').each(function(){
						if ($(this).attr('id') == '".@$_SESSION['responsaveis'][$i]."') {
							$(this).prop('checked',true);
						}
					});
				$('#botao_filtro').addClass('black');
				$('#botao_filtro').removeClass('basic');
				
			</script>";
		}
	}


?>
<div id="retorno"></div>
<script type="text/javascript">
	$('.ui.dropdown')
	  .dropdown()
	;

	$('.ui.checkbox')
	  .checkbox()
	;
</script>

<script>
	$('#botao_filtro').click(function(){
		$('#filtros').toggle('slow');
		$('#gambi').toggle();
	});

	$('#aplicar_filtro').click(function(){
		var responsaveis 	= new Array();
		var colunas 		= new Array();
		var linhas 			= new Array();
		$('#filtros').addClass('loading');
		$("input[name='responsavel']:checked").each(function()
		{
		   // valores inteiros usa-se parseInt
		   responsaveis.push(parseInt($(this).attr('id')));
		   // string
		   //responsaveis.push( $(this).attr('id'));		   
		});
		$("input[name='coluna']").each(function()
		{
		  //colunas.push(($(this).attr('id')));	
		  // alert(colunas);   
		  var isChecked = $(this).is(':checked');
		  if (isChecked == false) {
		  	colunas.push(($(this).attr('id')));
		  }
		  //alert(colunas);
		  
		});
		$("input[name='linha']:checked").each(function()
		{
		   linhas.push(($(this).attr('id')));	

		});
		//alert('IDs: ' + responsaveis + '\n\nColunas: ' + colunas + ',\n\nLinhas: '+ linhas);

		$.ajax({
			url: 'ajaxSetFiltro.php',
			type: 'POST',
			data: {
				'linhas': linhas,
				'responsaveis': responsaveis,
				'colunas': colunas
			},
			 success: function(data) { //Se a requisição retornar com sucesso, 
	    		//ou seja, se o arquivo existe, entre outros
	            setTimeout(function(){
			    	location.reload();
			    },1000); 
	        }

		});
	});	

	$('#limpar_filtro').click(function(){
		$('#filtros').addClass('loading');
		$.ajax({
			url: 'ajaxDelFiltro.php',
			type: 'POST',
			data: {
				
			},
			 success: function(data) { //Se a requisição retornar com sucesso, 
	    		//ou seja, se o arquivo existe, entre outros
	            setTimeout(function(){
			    	location.reload();
			    },1000); 
	        }

		});
	});
</script>

