<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> 
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

 <div class="ui container">
 	<h1 class="ui center aligned header"><i class="address card icon"></i>Leads</h1>
	
	<?php include 'sub_menu_associados.php'; ?> 

 </div>
 <style type="text/css">
	
	#cadLead {
		display: none;
	}

	#btnExport {
		color: #111;
	}

</style>
 <?php 

 	$total_novos = contarLeads($unidade_id);
 	if ($total_novos == 0) {
 		echo '<br>
 		<div class="ui container">
	 		<div class="ui compact icon message">
			  <i class="smile outline icon"></i>
			  <div class="content">
			    <div class="header">
			      BEM-VINDO!
			    </div>
			    <p>Você ainda não possui Leads no Painel. Caso prefira, você pode cadastrar um novo Lead <a id="btn-incluir-lead-ini" data-element="#cadLead" href="#">clicando aqui</a>. </p>
			  </div>
			</div>
 		</div>';
 	} else {
  

?>

<link href="https://cdn.rawgit.com/mdehoog/Semantic-UI-Calendar/76959c6f7d33a527b49be76789e984a0a407350b/dist/calendar.min.css" rel="stylesheet" type="text/css" />
<script src="bower_components/semantic-ui-calendar/dist/calendar.min.js"></script>
  
<script type="text/javascript">
  /**********************************************************
  ** Função para editar os dados da tabela com duplo clique *
  ***********************************************************/
	function duploClique() {
		$("td.leadeditavel").dblclick(function () {
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
	                    
                      // AQUI OS DADOS SÃO ENVIADOS POR AJAX PARA ALTERAÇÃO
                      $.ajax({
	                        url: '../model/ajaxAlterarLead.php',
	                        type: 'POST',
	                        data: {
	                            'campo': campo,
	                            'valor': novoConteudo,
                              'valor_old': conteudoOriginal,
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
    }
</script>

<?php 
  
  # AQUI UM SCRIPT PARA BUSCAR OS DADOS DO BANCO DE DADOS E INSERIR EM ARRAY JS.
  echo "<script>";
  echo "var dados = [";
  while ($leads = mysql_fetch_array($query)) {
  	if ($leads['responsavel'] == "Sistema") {
  		$responsavel = "-";
  	} else {
  		$responsavel = $leads['responsavel'] . " " . $leads['responsavel_n'];
  	}
    echo "['"
    .$leads['data_limpa'] . "','" . utf8_encode($leads['nome']) . "','" . $leads['email']
    ."','" . $leads['telefone'] . "','" . utf8_encode($leads['cidade']) . "','" . $leads['estado']
    ."','" . $leads['status2'] . "','" . $leads['data_limpa'] . "','" . mysql_real_escape_string(utf8_encode($leads['mensagem']))
    ."','" . $leads['id'] . "','" . utf8_encode($responsavel) . "','".utf8_encode($leads['identificador'])."','".$leads['origem']."','".$leads['midia']."','".utf8_encode($leads['campaign'])."','".utf8_encode($leads['nicho_empresa'])."','".utf8_encode($leads['faturamento'])."','".utf8_encode($leads['tipo_empresa'])."'],";
  }
  echo "];";

  echo "</script>";
  $filtro_colunas = @$_SESSION['colunas'];

	if ($filtro_colunas == "") {
		$col = ["telefone","cidade","data","responsavel"];
		$not_col = ["estado","identificador","mensagem","origem","midia","campaign","nicho_empresa","faturamento","tipo_empresa"];
		$tam_col = count($not_col);
		echo "<script>var colunas = [";
		for ($i=0; $i < $tam_col; $i++) { 
			echo "['".$not_col[$i]."'],";
		}
		echo "];
		var qspan = 0;
		</script>";
	} else {
		$tam_col = count($filtro_colunas);
		$not_col = $filtro_colunas;
		echo "<script>var colunas = [";
		for ($i=0; $i < $tam_col; $i++) { 			
			echo "['".$filtro_colunas[$i]."'],";
		}
		echo "];
		var qspan = 1;
		</script>";
	}
?>
<?php 
	$linhas 		= @implode(",", $_SESSION['linhas']);
	
	if ($linhas == "") {
		echo "<script>var tamanhoPagina = 10;</script>";
	} else {
		echo "<script>var tamanhoPagina = ".$linhas.";</script>";
	}	

?>
<script type="text/javascript">

	// SCRIPT PARA MONTAR A TABELA PAGINADA

	//var tamanhoPagina = 30; // Variável que indica a quantidade de linhas exibidas na tabela antes de paginar
	var pagina = 0; // Indica a página que irá iniciar

	function paginar() {
	    $('table > tbody > tr').remove();
	    var tbody = $('table > tbody');
	    for (var i = pagina * tamanhoPagina; i < dados.length && i < (pagina + 1) *  tamanhoPagina; i++) {
	        tbody.append(
	            $('<tr>')
	              .append($('<td class="center aligned">').append('<div class="ui checkbox"><input class="i_check" name="Pacote" value="'+dados[i][9]+'" type="checkbox"><label></label></div>'))
	                .append($('<td name="nome" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][1] +'">').append(dados[i][1]))
	                .append($('<td class="email" name="email" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][2] +'">').append("<a href='mailto:"+dados[i][2]+"' target='_blank'>"+dados[i][2]+"</a>"))
	                .append($('<td class="telefone" name="telefone" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][3] +'">').append("<a href='tel:"+dados[i][3]+"' target='_blank'>"+dados[i][3]+"</a>"))
	                .append($('<td class="cidade" name="cidade" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][5] +'">').append(dados[i][4]))
	                .append($('<td class="estado" name="estado" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][5] +'">').append(dados[i][5]))
	                .append($('<td class="data" name="data" id="'+dados[i][9]+'" title="'+ dados[i][7] +'">').append(dados[i][7]))
	                .append($('<td class="responsavel" name="responsavel" id="'+dados[i][9]+'" title="'+ dados[i][10] +'">').append(dados[i][10]))
	                .append($('<td class="identificador" name="identificador" id="'+dados[i][9]+'" title="'+ dados[i][11] +'">').append(dados[i][11]))
	                .append($('<td class="origem" name="origem" id="'+dados[i][9]+'" title="'+ dados[i][12] +'">').append(dados[i][12]))
	                .append($('<td class="midia" name="midia" id="'+dados[i][9]+'" title="'+ dados[i][13] +'">').append(dados[i][13]))
	                .append($('<td class="campaign" name="campaign" id="'+dados[i][9]+'" title="'+ dados[i][14] +'">').append(dados[i][14]))
	                .append($('<td class="nicho_empresa" name="nicho_empresa" id="'+dados[i][9]+'" title="'+ dados[i][15] +'">').append(dados[i][15]))
	                .append($('<td class="faturamento" name="faturamento" id="'+dados[i][9]+'" title="'+ dados[i][16] +'">').append(dados[i][16]))
	                .append($('<td class="tipo_empresa" name="tipo_empresa" id="'+dados[i][9]+'" title="'+ dados[i][17] +'">').append(dados[i][17]))
	                .append($('<td class="mensagem" name="mensagem" id="'+dados[i][9]+'" title="'+ dados[i][8] +'">').append(dados[i][8]))
	                .append($('<td>').append('<button class="ui icon basic mini button maisinfo" name="maisinfo" id="'+dados[i][9]+'"><i class="ellipsis vertical black icon"></i></button>'))
	        )
	    	for (var ind = 0; ind < colunas.length; ind++) {
	    		$('.'+colunas[ind]).hide();
	    		//$()
	    	}
	    }

	    $('#numeracao').text('Página ' + (pagina + 1) + ' de ' + Math.ceil(dados.length / tamanhoPagina));
	    
	    $('[name=Pacote]').click(function(){
			if($('[name=Pacote]').is(':checked')) {
	  			$('#follow').removeClass('disabled');
	  			$('#cliente').removeClass('disabled');
	  			$('#rejeitado').removeClass('disabled');
	  			$('#deletar').removeClass('disabled');
	  			$('.responsavel_div').removeClass('disabled');
	  		} else {
	  			$('#follow').addClass('disabled');
	  			$('#cliente').addClass('disabled');
	  			$('#rejeitado').addClass('disabled');
	  			$('#deletar').addClass('disabled');
	  			$('.responsavel_div').addClass('disabled');
	  		}
		});
		
		duploClique(); // Chama a função que edita com duplo clique, dentro do looping para inserir em todas as linhas
		exibeModal();
	}


	function ajustarBotoes() {
	    $('#proximo').prop('disabled', dados.length <= tamanhoPagina || pagina >= Math.ceil(dados.length / tamanhoPagina) - 1);
	    $('#anterior').prop('disabled', dados.length <= tamanhoPagina || pagina == 0);
	}

	$(function() {
	    $('#proximo').click(function() {
	        if (pagina < dados.length / tamanhoPagina - 1) {
	            pagina++;
	            paginar();
	            ajustarBotoes();
	        }
	    });
	    $('#anterior').click(function() {
	        if (pagina > 0) {
	            pagina--;
	            paginar();
	            ajustarBotoes();
	        }
	    });
	    paginar();
	    ajustarBotoes();

	    // Verificar se alguma linha está checkada e exibir botões.
	    $('[name=Pacote]').click(function(){
			if($('[name=Pacote]').is(':checked')) {
	  			$('#follow').removeClass('disabled');
	  			$('#cliente').removeClass('disabled');
	  			$('#rejeitado').removeClass('disabled');
	  			$('#deletar').removeClass('disabled');
	  			$('.responsavel_div').removeClass('disabled');
	  		} else {
	  			$('#follow').addClass('disabled');
	  			$('#cliente').addClass('disabled');
	  			$('#rejeitado').addClass('disabled');
	  			$('#deletar').addClass('disabled');
	  			$('.responsavel_div').addClass('disabled');
	  		}
		});
	});
</script>
<?php 
	// $filtro_colunas = @$_SESSION['colunas'];

	// if ($filtro_colunas != "") {
	// 	$tam_col = count($filtro_colunas);
	// 	for ($i=0; $i < $tam_col; $i++) { 
	// 		echo "<script>
	// 			$('.".$filtro_colunas[$i]."').hide();
	// 		</script>";
	// 	}
	// }
?>
<div class="ui container" id="container-principal">
	<div class="ui segment">
		<div class="ui grid">
			<div class="four wide column">
				<form method="POST" id="form-pesquisa" action="">
					<div class="ui right icon fluid small input">
						<i class="search icon"></i>
						<input type="text" class="" name="pesquisa" id="pesquisa" placeholder="Buscar..." autocomplete="off">
					</div>
				</form>
			</div>
			<div class="twelve wide right aligned column">	

				<a id="follow" class="ui labeled icon small yellow disabled button" title="Coloque aqui os Leads que você está atendendo ou que você precisa retornar">
				  <i class="phone icon"></i>
				  Atender
				</a>
				<a id="cliente" class="ui labeled icon small green disabled button" title="Parabéns! Coloque aqui quem virou seu cliente">
				  <i class="check circle icon"></i>
				  Ganhar
				</a>
				<a id="rejeitado" class="ui labeled icon small grey disabled button" title="Coloque aqui os leads que você não fechou negócio. Afinal, não se pode ganhar todas...">
				  <i class="user times icon"></i>
				  Perder
				</a>
				<a id="deletar" class="ui labeled icon small red disabled button" title="Coloque aqui os leads inúteis, juntando poeira">
				  <i class="trash alternate icon"></i>
				  Deletar
				</a>
				<a id="btn-incluir-lead" data-element="#cadLead" class="ui labeled icon small black button" title="Cadastre seus leads de outras fontes como Telefone ou Whatsapp">
				  <i class="plus icon"></i>
				  Cadastrar
				</a>
				<div id="opcoes" class="ui labeled icon top right pointing dropdown link item basic small button">
		          <i class="caret down icon"></i>
		          <span title="Clique para visualizar mais opções " class="text">Opções</span>
		          <div class="menu">  
		          	<div class="header">
				      Ações
				    </div>
				    <!-- <div class="ui divider"></div> -->
		            <div class="item">
		              <i class="download icon"></i>
		              <a id="btnExport" class="export" title="Gere um arquivo do Excel com os dados que visualiza na tela">Exportar (.csv)</a>
		            </div> 
	 			    <?php 
	 			    	if ($permissao < 5) {
	 			    		include 'getResponsaveis.php'; 
	 			    	}	 			    	
 			    	?>
		          </div>
		        </div>
		       <a class="ui icon basic small button" id="botao_filtro" title="Clique aqui para visualizar as opções de filtro">
				  <i class="filter icon"></i>
				</a>
			</div>
			<?php 
				if ($permissao < 5) {
					include 'filtro.php';
				} else {
					echo "<script>$('#botao_filtro').hide();</script>";
				}
			?>
			
		</div>	
		<span id="gambi2"><br></span>
			<div class="ui container">
			  <form method="POST" action="" id="form-cadastro">
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
			        <input id="btncadlead" type="button" value="Cadastrar" class="ui positive small button" onclick="">
			        <input type="hidden" name="ok" id="ok" />
			        <button id="btn-incluir-lead2" data-element="#cadLead" class="ui black small button">Cancelar</button>
			        <?php echo '<input type="hidden" name="unidade" id="unidade" value="'.$unidade_id.'" />'; ?>
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
		<br>
		<div id="dvData">
			<table class="ui fixed single line selectable compact celled center aligned sortable table lista-clientes" id="tabela_padrao">
			    <thead>
			        <tr>
		              <th class="one wide no-sort"><div class="ui checkbox"><input type="checkbox" id="checkTodos" name="checkTodos"><label></label></div></th>
		              <th>Nome</th>
		              <th class="email">Email</th>
		              <th class="telefone no-sort">Telefone</th>
		              <th class="cidade">Cidade</th>
		              <th class="estado">Estado</th>
		              <th class="data">Data</th>
		              <th class="responsavel">Responsável</th>
		              <th class="identificador">Identificador</th>
		              <th class="origem">Origem</th>
		              <th class="campaign">Campanha</th>
		              <th class="midia">Midia</th>
		              <th class="nicho_empresa">Nicho da Empresa</th>
		              <th class="faturamento">Faturamento</th>
		              <th class="tipo_empresa">Tipo de Empresa</th>
		              <th class="mensagem">Mensagem</th>
		              <th class="one wide no-sort"></th>
		          </tr>
			    </thead>
			    <tbody>
			        <tr>
			            <td colspan="2" align="center">Nenhum dado ainda...</td>
			        </tr>
			    </tbody>
			    <tfoot class="full-width">
			    <tr>
			      <th></th>
			      <th id="colspan" colspan="7">
			        <div class="ui center floated buttons">
						<button id="anterior" class="ui black button" disabled>&lsaquo; Anterior</button>
					    <button class="ui basic disabled button"><span id="numeracao"></span></button>
						<button id="proximo" class="ui black button" disabled>Próximo &rsaquo;</button>
					</div>
			      </th>
			    </tr>
			  </tfoot>
			</table>
			<script type="text/javascript">
				if (qspan != 0) {
					var cspan = $("#colspan").prop("colspan")-(colunas.length-9);

					$("#colspan").attr("colspan",cspan);	
				}
				
			</script>
			<div class="resultado">
			
			</div>
		</div>
	</div>
	<br><br>
</div>

<script src="../controller/crm_control_status.js"></script>

<!-- Exibir o modal dentro da div retorno -->
<div id="retorno"></div>
<script type="text/javascript" src="js/exibeModal.js"></script>
<script type="text/javascript" src="js/funcoes.js"></script>
<script type='text/javascript' src='tablesort.js'></script>

<script type="text/javascript">

	var checkTodos = $("#checkTodos");
	checkTodos.click(function () {
	  if ( $(this).is(':checked') ){
	    $('.i_check:checkbox').prop("checked", true);
	    $('#follow').removeClass('disabled');
		$('#cliente').removeClass('disabled');
		$('#rejeitado').removeClass('disabled');
		$('#deletar').removeClass('disabled');
		$('.responsavel_div').removeClass('disabled');
	  }else{
	    $('.i_check:checkbox').prop("checked", false);
	    $('#follow').addClass('disabled');
		$('#cliente').addClass('disabled');
		$('#rejeitado').addClass('disabled');
		$('#deletar').addClass('disabled');
		$('.responsavel_div').addClass('disabled');
	  }
	});

  $('.ui.dropdown')
  .dropdown({
    action: 'hide'
  })
;
</script>

<script type="text/javascript">
	$('#btncadlead').click(function(){
		var nome 	= $('[name=nome]').val();
		var email 	= $('[name=email]').val();
		var telefone = $('[name=telefone]').val();
		var estado 	= $('[name=estado]').val();
		var cidade 	= $('[name=cidade]').val();
		var ident 	= $('[name=identificador]').val();
		var fatur   = $('[name=faturamento]').val();
	    var nicho   = $('[name=nicho_empresa]').val();
	    var tipo    = $('[name=tipo_empresa]').val();
		var msg 	= $('[name=mensagem]').val();
		var unidade = $('#unidade').val();
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
			url: '../model/ajaxCadastrarLeadAssoc.php',
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
            	'unidade': unidade,
          	},
      	beforeSend: function(){	
			$('#loader').css({
				display:"block"
			});
			$.ajax({
				url: '../controller/validaCadastroLead.php',
				type: 'POST',
				data: {
					'email_cad': email
				},
				success: function(data) {
					alert(data);	
					if (data == "Não foi possível cadastrar este Lead.\nDetalhes do erro: E-mail duplicado não atribuido a esta unidade.") {
						alert("opa");
					}				
				}
			});
		  },
		  success: function(data) { //Se a requisição retornar com sucesso, 
    		//ou seja, se o arquivo existe, entre outros
            //$('#sucesso').html(data); //Parte do seu 
            setTimeout(function(){
		    	$('#loader').css({display:"none"});	
		    	location.reload();
		    	// $('#form-cadastro').css({display:"none"});
		    	// $('#tabela_padrao').fadeOut();
		    	// $('#tabela_padrao').fadeIn("slow");
		    	//$('#tabela_padrao').load(location.href+' #tabela_padrao');
		    	//paginar();
		    	// $('#tabela_padrao').data('#tabela_padrao');
		    },1000); 
            //HTML que voce define onde sera carregado o conteudo
            //PHP, por default eu uso uma div, mas da pra fazer em outros lugares
        }
		});
	});
</script>

<?php } ?>