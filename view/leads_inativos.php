<?php 

	include('menu.php'); 
	$id_user = $_SESSION['usuarioID'];
	$account_id = buscarClientId($id_user);
	$permissao  = verificarPermissao($id_user); 
	# Verificar permissão e negar acesso caso não tenha privilégios
  	if ($permissao >= 4) {
    	echo "Você não tem permissão para acessar esta página, <a href='index.php'>clique aqui</a> para voltar ao painel.";
    	exit();
  	}
	$query = mysql_query("SELECT 
							a.id
							,b.data_conversao
							,date_format(b.data_conversao,'%d/%m/%y') as data_limpa
							,a.nome
							,a.email
							,a.telefone
							,a.cidade
							,a.estado
							,b.identificador
							,b.origem
                            ,b.midia
							,b.campaign
							,b.nicho_empresa
							,f.faturamento
						    ,e.tipo_empresa
							,b.mensagem
							,c.status
							,d.nome_unidade
							,a.esta_ativo
							FROM tb_leads a
							INNER JOIN tb_conversoes b ON a.id_ultima_conversao = b.id
							INNER JOIN tb_lead_status c ON a.id_status = c.id
							INNER JOIN tb_unidades d ON a.id_unidade = d.id
                            LEFT JOIN tb_chatbot_faturamento f ON b.id_faturamento_empresa = f.id
							LEFT JOIN tb_chatbot_tipoempresa e ON b.id_tipo_empresa = e.id
							WHERE a.esta_ativo = 0
							ORDER BY a.id DESC
						");
 ?>
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
 <?php 
	echo "<script>";
	echo "var dados = [";
	while ($leads = mysql_fetch_array($query)) {
		switch ($leads['esta_ativo']) {
			case 0:
				$ativo 	= "Inativo";
				$classe = "disabled";
				break;
			case 1:
				$ativo = "Ativo";
				$classe = "";
				break;			
			default:
				$ativo = "Ativo";
				$classe = "";
				break;
		}

		echo "['"
	    .$leads['data_limpa'] . "','" . utf8_encode($leads['nome']) . "','" . $leads['email']
	    ."','" . $leads['telefone'] . "','" . utf8_encode($leads['cidade']) . "','" . $leads['estado']
	    ."','" . $leads['status'] . "','" . $leads['data_limpa'] . "','" . mysql_real_escape_string(utf8_encode($leads['mensagem']))
	    ."','" . $leads['id'] . "','".utf8_encode($leads['identificador'])."','".$leads['origem']."','".$leads['midia']."','".utf8_encode($leads['campaign'])."','".utf8_encode($leads['nicho_empresa'])."','".utf8_encode($leads['faturamento'])."','".utf8_encode($leads['tipo_empresa'])."'],";
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

  $linhas     = @implode(",", $_SESSION['linhas']);
  
  if ($linhas == "") {
    echo "<script>var tamanhoPagina = 10;</script>";
  } else {
    echo "<script>var tamanhoPagina = ".$linhas.";</script>";
  } 
?>
 <script type="text/javascript">

//var tamanhoPagina = 30;
var pagina = 0;

function paginar() {
    $('table > tbody > tr').remove();
    var tbody = $('table > tbody');
    for (var i = pagina * tamanhoPagina; i < dados.length && i < (pagina + 1) *  tamanhoPagina; i++) {
        tbody.append(
            $('<tr class="">')
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

    exibeModal();
    // Verificar se alguma linha está checkada e exibir botões.
    $('[name=Pacote]').click(function(){
		if($('[name=Pacote]').is(':checked')) {
  			// $('#reativar').css('display','inline');
  			$('#reativar').removeClass('disabled');
  		} else {
  			// $('#reativar').css('display','none');
  			$('#reativar').addClass('disabled');
  		}
	});

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
  			// $('#reativar').css('display','inline');
  			$('#reativar').removeClass('disabled');
  		} else {
  			// $('#reativar').css('display','none');
  			$('#reativar').addClass('disabled');
  		}
	});

});


</script>

 <div class="ui container">
	<h1 class="ui center aligned header"><i class="address card icon"></i>Leads</h1>
	<?php include 'sub_menu.php'; ?> 
	<div class="ui two column grid">
		<div class="column">
			<h3 class="ui left aligned left floated header">
		      Deletados
		      <div class="sub header">Tabela de leads deletados.</div>
		    </h3>
		</div>
	</div>
	<div class="ui segment">
      <div class="ui grid">
      <div class="eight wide column">
        <form method="POST" id="form-pesquisa" action="">
            <div class="ui right icon fluid small input">
              <i class="search icon"></i>
              <input type="text" class="" name="pesquisa" id="pesquisa" placeholder="Buscar..." autocomplete="off">
            </div>
        </form>
      </div>
      <div class="eight wide right floated right aligned column">
         <button id="reativar" class="ui green small labeled icon disabled button">
				<i class="reply icon"></i>Mover para Pendentes
			</button>
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

	// *****************************************************
	// FUNÇÃO PARA BUSCAR OS DADOS QUE ESTÃO CHECKADOS (ID)
	//******************************************************
  function delValues() {
    var pacote   = document.querySelectorAll('[name=Pacote]:checked');
    var values   = [];
    var nome     = $('#tags').val();
    for (var i = 0; i < pacote.length; i++) {
      // utilize o valor aqui, adicionei ao array para exemplo
      values.push(pacote[i].value);
    }
      msg = confirm('Ativar os Leads ' + values + '?');
      if (msg == true) {
        $.ajax({
          url: '../model/ajaxDeletarLead.php',
          type: 'POST',
          data: {
            'valores': values,
            'ativar': 1,
          },
	        success: function(data) { //Se a requisição retornar com sucesso, 
				//ou seja, se o arquivo existe, entre outros
		        //$('#sucesso').html(data); //Parte do seu 
		        setTimeout(function(){
			    	//$('#del-lead').removeClass('disabled loading');
			    	$(window).attr('location','leads_inativos.php');
			    },1500);
		    }
        });
      //location.reload();
    } 
   }
   
	var reativar = document.getElementById('reativar');
  	// var del = document.getElementById('del-lead');
	reativar.addEventListener('click', delValues, false);
  	// del.addEventListener('click', delValues, false);

</script>

<!-- Exibir o modal dentro da div retorno -->
<div id="retorno"></div>
<script type="text/javascript" src="js/exibeModal.js"></script>
<script type="text/javascript" src="js/funcoes.js"></script>
<script type="text/javascript" src="tablesort.js"></script>

<script type="text/javascript">

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
    $('#reativar').removeClass('disabled');
  }else{
    $('input:checkbox').prop("checked", false);
    $('#reativar').addClass('disabled');
  }
});
</script>