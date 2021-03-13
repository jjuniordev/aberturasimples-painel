<?php 

	include('menu.php'); 
	$id_user = $_SESSION['usuarioID'];
	$permissao  = verificarPermissao($id_user); 
	# Verificar permissão e negar acesso caso não tenha privilégios
  	if ($permissao >= 4) {
    	echo "Você não tem permissão para acessar esta página, <a href='index.php'>clique aqui</a> para voltar ao painel.";
    	exit();
  	}

	include('../controller/Lead.php');

	$leadsDuplicados = verificarLeadDuplicado();

	if ($leadsDuplicados) {
		ajustarBaseLeadDuplicado($leadsDuplicados);
	}

?>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> 
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style type="text/css">
  
  #cadLead {
    display: none;
  }
  #loader {
    display: none;
  }
  .loader {
    display: none;
  }

</style>


<!-- 
**********************************************************************
* Script JS que exibe as opções no autocomplete do input das uidades *
**********************************************************************
 -->
<?php 
  $busers = buscaUnidades(0);

  echo '<script>
  $( function() {
    var availableTags = [
      ';
      while ($unidades = mysql_fetch_array($busers)) {
        echo '"'.utf8_encode($unidades['nome_unidade']).'",';
      }
  echo '
      ""
    ];
    $( "#tags" ).autocomplete({
      source: availableTags
    });
  });
  </script>';
 ?>
  
 <div class="ui container">
 	<div class="loader">
	  <div class="ui active dimmer">
	    <div class="ui text loader">Enviando</div>
	  </div>
	</div>
 	<h1 class="ui center aligned header"><i class="address card icon"></i>Leads</h1>
	<?php include 'sub_menu.php'; ?> 
	<div class="ui two column grid">
		<div class="column">
			<h3 class="ui left aligned left floated header">
		      Pendentes
		      <div class="sub header">Tabela de leads com o status pendentes.</div>
		    </h3>
		</div>
	</div> 
	<br>
	<?php //include 'form_cadastrar_lead.php'; ?>
</div>

<?php 

 	$total_pendentes = leadsPendentes();
 	if ($total_pendentes == 0) {
 		echo '<br>
 		<div class="ui container">
	 		<div class="ui compact icon message">
			  <i class="smile outline icon"></i>
			  <div class="content">
			    <div class="header">
			      PARABÉNS!
			    </div>
			    <p>Você não possui Leads Pendentes. 
			    <button id="btn-incluir-lead" data-element="#cadLead" class="ui labeled icon small black right floated button" title="Cadastre seus leads de outras fontes como Telefone ou Whatsapp"><i class="plus icon"></i>Cadastrar</button></p>
			  </div>
			</div>
			';
			include 'form_cadastrar_lead.php';
 		echo '</div>';
 	} else {
  
  #QUERY PARA BUSCAR OS DADOS DA TABELA DE LEADS PENDENTES.
  $query = mysql_query(
        "SELECT 
			a.id
			,b.data_conversao
			,date_format(b.data_conversao,'%d/%m/%y') as data_limpa
			,a.nome
			,a.email
			,a.telefone
			,a.cidade
			,a.estado
            ,c.status
			,b.identificador
			,b.origem
            ,b.midia
            ,b.campaign
            ,b.nicho_empresa
            ,d.faturamento
            ,e.tipo_empresa
			,b.mensagem
			FROM tb_leads a
			INNER JOIN tb_conversoes b ON a.id_ultima_conversao = b.id
            INNER JOIN tb_lead_status c ON a.id_status = c.id
            LEFT JOIN tb_chatbot_faturamento d ON b.id_faturamento_empresa = d.id
            LEFT JOIN tb_chatbot_tipoempresa e ON b.id_tipo_empresa = e.id
			WHERE a.esta_ativo = 1
			AND a.id_status = 1 
			ORDER BY a.id DESC
            ;
        ");

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
    //alert('Oi, mundo');
</script>

<?php 
  
  # AQUI UM SCRIPT PARA BUSCAR OS DADOS DO BANCO DE DADOS E INSERIR EM ARRAY JS.
  echo "<script>";
  echo "var dados = [";
  while ($leads = mysql_fetch_array($query)) {
    echo "['"
    .$leads['data_limpa'] . "','" . utf8_encode($leads['nome']) . "','" . $leads['email']
    ."','" . $leads['telefone'] . "','" . utf8_encode($leads['cidade']) . "','" . $leads['estado']
    ."','" . $leads['status'] . "','" . $leads['data_limpa'] . "','" . mysql_real_escape_string(utf8_encode($leads['mensagem']))
    ."','" . $leads['id'] . "','".utf8_encode($leads['identificador'])."','".$leads['origem']."','".$leads['midia']."','".$leads['campaign']."','".utf8_encode($leads['nicho_empresa'])."','".utf8_encode($leads['faturamento'])."','".utf8_encode($leads['tipo_empresa'])."'],";
  }
  echo "];";

  echo "</script>";

  $filtro_colunas = @$_SESSION['colunas'];

	if ($filtro_colunas == "") {
		$col = ["telefone","cidade","identificador"];
		$not_col = [
			"estado",
			"data",
			"mensagem",
			"origem",
			"midia",
			"campaign",
			"nicho_empresa",
			"faturamento",
			"tipo_empresa"
		];
		$tam_col = count($not_col);
		echo "<script>colunas = [";
		for ($i=0; $i < $tam_col; $i++) { 
			echo "['".$not_col[$i]."'],";
		}
		echo "];
		var qspan = 0;
		</script>";
	} else {
		$tam_col = count($filtro_colunas);
		$not_col = $filtro_colunas;
		echo "<script>colunas = [";
		for ($i=0; $i < $tam_col; $i++) { 			
			echo "['".$filtro_colunas[$i]."'],";
		}
		echo "];
		var qspan = 1;
		</script>";
	}

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
	                .append($('<td  name="nome" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][1] +'">').append(dados[i][1]))
	                .append($('<td class="email" name="email" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][2] +'">').append("<a href='mailto:"+dados[i][2]+"' target='_blank'>"+dados[i][2]+"</a>"))
	                .append($('<td class="telefone" name="telefone" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][3] +'">').append("<a href='tel:"+dados[i][3]+"' target='_blank'>"+dados[i][3]+"</a>"))
	                .append($('<td class="cidade" name="cidade" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][5] +'">').append(dados[i][4]))
	                .append($('<td class="estado" name="estado" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][5] +'">').append(dados[i][5]))
	                .append($('<td class="data" name="data" id="'+dados[i][9]+'" title="'+ dados[i][7] +'">').append(dados[i][7]))
	                .append($('<td class="identificador" name="identificador" id="'+dados[i][9]+'" title="'+ dados[i][10] +'">').append(dados[i][10]))
	                .append($('<td class="origem" name="origem" id="'+dados[i][9]+'" title="'+ dados[i][11] +'">').append(dados[i][11]))
	                .append($('<td class="midia" name="midia" id="'+dados[i][9]+'" title="'+ dados[i][12] +'">').append(dados[i][12]))
	                .append($('<td class="campaign" name="campaign" id="'+dados[i][9]+'" title="'+ dados[i][13] +'">').append(dados[i][13]))
	                .append($('<td class="nicho_empresa" name="nicho_empresa" id="'+dados[i][9]+'" title="'+ dados[i][14] +'">').append(dados[i][14]))
	                .append($('<td class="faturamento" name="faturamento" id="'+dados[i][9]+'" title="'+ dados[i][15] +'">').append(dados[i][15]))
	                .append($('<td class="tipo_empresa" name="tipo_empresa" id="'+dados[i][9]+'" title="'+ dados[i][16] +'">').append(dados[i][16]))
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
	  			$('#enviar-combo').removeClass('disabled');
	  			$('#btn-atribuir-leads').removeClass('disabled');
	  			$('#del-lead').removeClass('disabled');
	        	$('#cadLead').css('display','none');
	  		} else {
	  			$('#enviar-combo').addClass('disabled');
	  			$('#btn-atribuir-leads').addClass('disabled');
	  			$('#del-lead').addClass('disabled');
	  		}
		});
		
		//duploClique(); // Chama a função que edita com duplo clique, dentro do looping para inserir em todas as linhas
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
	  			$('#enviar-combo').removeClass('disabled');
	  			$('#btn-atribuir-leads').removeClass('disabled');
	  			$('#del-lead').removeClass('disabled');
	        	$('#cadLead').css('display','none');
	  		} else {
	  			$('#enviar-combo').addClass('disabled');
	  			$('#btn-atribuir-leads').addClass('disabled');
	  			$('#del-lead').addClass('disabled');
	  		}
		});
	});

</script>

<div class="ui container">
	<div class="ui segment">
      <div class="ui grid">
      <div class="five wide column">
        <form method="POST" id="form-pesquisa" action="">
            <div class="ui right icon fluid small input">
              <i class="search icon"></i>
              <input type="text" class="" name="pesquisa" id="pesquisa" placeholder="Buscar..." autocomplete="off">
            </div>
        </form>
      </div>
      <div class="eleven wide right aligned column">
      	<div id="enviar-combo" class="ui left icon action small disabled input">      	
		  <input id="tags" type="text" placeholder="buscar unidades...">
		  <i class="users icon"></i>
		  <button id="btn-atribuir-leads" class="ui green small disabled button">Enviar</button>&nbsp;&nbsp;
	  	</div>
	  	<button class="ui labeled icon red disabled small button" id="del-lead" title="Deletar"><i class="trash alternate icon"></i>Deletar</button>
	  	<button id="btn-incluir-lead" data-element="#cadLead" class="ui labeled icon small black button" title="Cadastre seus leads de outras fontes como Telefone ou Whatsapp"><i class="plus icon"></i>Cadastrar</button>
	  	<div class="ui labeled icon top middle pointing dropdown basic small button">
          <i class="caret down icon"></i>
          <span class="text">Opções</span>
          <div class="menu">
            <div class="item">
              <i class="download icon"></i>
              <a id="btnExport" class="export">Exportar (.csv)</a>
            </div>  
          </div>
        </div>
        <a class="ui icon basic small button" id="botao_filtro" title="Clique aqui para visualizar as opções de filtro">
		  <i class="filter icon"></i>
		</a>
      </div>
      <?php include 'filtro_adm.php'; ?>
      <?php include 'form_cadastrar_lead.php'; ?>
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
		              <th class="identificador">Identificador</th>
		              <th class="origem">Origem</th>
		              <th class="midia">Midia</th>
		              <th class="campaign">Campanha</th>
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
		      <th id="colspan" colspan="6">
		        <div class="ui center floated buttons">
					<button id="anterior" class="ui black button" disabled>&lsaquo; Anterior</button>
				    <button class="ui basic disabled button"><span id="numeracao"></span></button>
					<button id="proximo" class="ui black button" disabled>Próximo &rsaquo;</button>				
				</div>
				<!-- <button class="ui right floated button">add 10 linhas</button> -->
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
    </div>
		<div class="resultado">
		
		</div>
	</div>
	<br><br>
</div>

<script type="text/javascript">

  // FUNÇÃO PARA BUSCAR OS DADOS QUE ESTÃO CHECKADOS (ID)
	function getValues() {
	  var pacote   = document.querySelectorAll('[name=Pacote]:checked');
	  var values   = [];
      var nome     = $('#tags').val();
	  for (var i = 0; i < pacote.length; i++) {
	    // utilize o valor aqui, adicionei ao array para exemplo
	    values.push(pacote[i].value);
	  }
    // AJAX PARA ATRIBUIR OS LEADS SELECIONADOS PARA A UNIDADE ENVIADA
    $.ajax({
        url: '../model/ajaxAtribuirLead.php',
        type: 'POST',
        data: {
          'nome': nome,
          'valores': values,
        },
        beforeSend: function(){	
				//$('#enviar-combo').addClass('disabled loading');	
				//$('.loader').show();	    
				$('.loader').css({display:"block"});		
			  },
        success: function(data) { //Se a requisição retornar com sucesso, 
			//ou seja, se o arquivo existe, entre outros
	        //$('#sucesso').html(data); //Parte do seu 
	        //alert(nome);
	        if (nome == 'São Paulo') {
	        	setTimeout(function(){
			    	$('#enviar-combo').removeClass('disabled loading');
			    	//$('.loader').hide();
			    	$(window).attr('location','dispararEmail.php?gatilho_api=1&ids='+values);
			    },100);
	        } else {
	        	setTimeout(function(){
			    	$('#enviar-combo').removeClass('disabled loading');
			    	//$('.loader').hide();
			    	$(window).attr('location','dispararEmail.php?gatilho_api=0&ids=0');
			    },100);
	        }
	    }
      });
	}

  function delValues() {
    var pacote   = document.querySelectorAll('[name=Pacote]:checked');
    var values   = [];
    var nome     = $('#tags').val();
    for (var i = 0; i < pacote.length; i++) {
      // utilize o valor aqui, adicionei ao array para exemplo
      values.push(pacote[i].value);
    }
      msg = confirm('Deletar os Leads ' + values + '?');
      if (msg == true) {
        $.ajax({
          url: '../model/ajaxDeletarLead.php',
          type: 'POST',
          data: {
            'valores': values,
          },
	        beforeSend: function(){	
					$('#del-lead').addClass('disabled loading');		    
				  },
	        success: function(data) { //Se a requisição retornar com sucesso, 
				//ou seja, se o arquivo existe, entre outros
		        //$('#sucesso').html(data); //Parte do seu 
		        setTimeout(function(){
			    	$('#del-lead').removeClass('disabled loading');
			    	$(window).attr('location','pendentes.php');
			    },1500);
		    }
        });
      location.reload();
    } 
   } 
   
	var btn = document.getElementById('btn-atribuir-leads');
  	var del = document.getElementById('del-lead');
	btn.addEventListener('click', getValues, false);
  	del.addEventListener('click', delValues, false);
  //}

</script>

<!-- Exibir o modal dentro da div retorno -->
<div id="retorno"></div>

<script type="text/javascript" src="js/exibeModal.js"></script>
<script type="text/javascript" src="js/funcoes.js"></script>
<script type="text/javascript" src="tablesort.js"></script>

<?php } ?>

<script type="text/javascript">
	$('.message .close')
  .on('click', function() {
    $(this)
      .closest('.message')
      .transition('fade')
    ;
  })
;

  $('.ui.dropdown')
  .dropdown({
    action: 'hide'
  })
;

$("#checkTodos__").change(function () {
$("input:checkbox").prop('checked', $(this).prop("checked"));
});

$("#checkTodos__").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

var checkTodos = $("#checkTodos");
checkTodos.click(function () {
  if ( $(this).is(':checked') ){
    $('input:checkbox').prop("checked", true);
    $('#del-lead').removeClass('disabled');
    $('#enviar-combo').removeClass('disabled');
	$('#btn-atribuir-leads').removeClass('disabled');
  }else{
    $('input:checkbox').prop("checked", false);
    $('#del-lead').addClass('disabled');
    $('#enviar-combo').addClass('disabled');
	$('#btn-atribuir-leads').addClass('disabled');
  }
});
</script>