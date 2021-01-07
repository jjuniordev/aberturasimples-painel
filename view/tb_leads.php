<?php 
  
  # QUERY PARA BUSCAR OS DADOS DA TABELA DE LEADS INICIAL.
	// $query = mysql_query("SELECT 
 //                          *,
 //                          date_format(data,'%d/%m/%Y - %Hh%i') as data_limpa 
 //                        FROM 
 //                          tb_leads 
 //                        WHERE 
 //                          id_associado = 0 
 //                        AND 
 //                          esta_ativo = 1 
 //                        ORDER BY 1 DESC");

  $query = mysql_query("SELECT 
                            a.*, 
                            DATE_FORMAT(data, '%d/%m/%Y - %Hh%i') AS data_limpa,
                            c.account_name,
                            b.unidade
                        FROM
                            tb_leads a
                        INNER JOIN
                          tb_usuarios b
                        ON
                          a.id_associado = b.id
                        INNER JOIN 
                          tb_google_account c
                        ON 
                          b.account_id = c.id
                        ORDER BY 1 DESC;");

?>
<style type="text/css">
	
	#enviar-combo, #cadLead, #del-lead {
		display: none;
	}

</style>

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
<script type="text/javascript">
	
  // FUNÇÃO QUE EXIBE/OCULTA O FORMULÁRIO DE INCLUIR NOVO LEAD MANUAL.
  $(function(){
        $("#btn-incluir-lead").click(function(e){
            e.preventDefault();
            el = $(this).data('element');
            $(el).toggle();
        });
    });
</script>
<?php 
  
  # AQUI UM SCRIPT PARA BUSCAR OS DADOS DO BANCO DE DADOS E INSERIR EM ARRAY JS.
echo "<script>";
  echo "var dados = [";
  while ($leads = mysql_fetch_array($query)) {
    switch ($leads['esta_ativo']) {
      case 0:
        $ativo  = '<a class="ui tiny label">Inativo</a>';
        $classe = "disabled";
        break;
      case 1:
        $ativo = '';
        $classe = "";
        break;      
      default:
        $ativo = '';
        $classe = "";
        break;
    }

    if ($leads['id_associado'] != 0) {
      $ativo = '<a class="ui tiny green label">Atribuído</a>';
    }

    echo "['"
    .$leads['data_limpa'] . "','" . utf8_encode($leads['nome']) . "','" . $leads['email']
    ."','"
    .$leads['unidade'] . "','" . utf8_encode($leads['cidade']) . "','" . $leads['estado']
    ."','"
    .$leads['origem'] . "','" . $leads['identificador'] . "','" . mysql_real_escape_string(utf8_encode($leads['mensagem']))
    ."','"
    .$leads['id'] . "','".$ativo."','".$classe."'],";
  }
  // echo "['','','','','','','','']];";
  echo "];";
  echo "</script>";
?>

<script type="text/javascript">

// SCRIPT PARA MONTAR A TABELA PAGINADA

var tamanhoPagina = 16; // Variável que indica a quantidade de linhas exibidas na tabela antes de paginar
var pagina = 0; // Indica a página que irá iniciar

function paginar() {
    $('table > tbody > tr').remove();
    var tbody = $('table > tbody');
    for (var i = pagina * tamanhoPagina; i < dados.length && i < (pagina + 1) *  tamanhoPagina; i++) {
        tbody.append(
            $('<tr class="' + dados[i][11] + '">')
              .append($('<td class="center aligned">').append('<div class="ui checkbox"><input name="Pacote" value="'+dados[i][9]+'" type="checkbox"><label></label></div>'))
                .append($('<td title="'+ dados[i][0] +'">').append(dados[i][0]))
                .append($('<td name="nome" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][2] +'">').append(dados[i][1]))
                // .append($('<td name="email" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][2] +'">').append(dados[i][2]))
                // .append($('<td name="telefone" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][3] +'">').append(dados[i][3]))
                .append($('<td name="cidade" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][4] +'">').append(dados[i][4]))
                .append($('<td name="estado" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][5] +'">').append(dados[i][5]))
                .append($('<td name="origem" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][6] +'">').append(dados[i][6]))
                .append($('<td name="identificador" id="'+dados[i][9]+'" title="'+ dados[i][7] +'">').append(dados[i][7]))
                .append($('<td name="mensagem" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][8] +'">').append(dados[i][8]))
                .append($('<td name="unidade" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][3] +'">').append(dados[i][3]))
                .append($('<td name="esta_ativo" id="'+dados[i][9]+'">').append(dados[i][10]))
        )
    }
    $('#numeracao').text('Página ' + (pagina + 1) + ' de ' + Math.ceil(dados.length / tamanhoPagina));
    
    $('[name=Pacote]').click(function(){
		if($('[name=Pacote]').is(':checked')) {
  			$('#enviar-combo').css('display','block');
        $('#cadLead').css('display','none');
        $('#del-lead').css('display','inline');
  		} else {
  			$('#enviar-combo').css('display','none');
        $('#del-lead').css('display','none');
  		}
	});
	
	duploClique(); // Chama a função que edita com duplo clique, dentro do looping para inserir em todas as linhas
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
  			$('#enviar-combo').css('display','inline');
        $('#cadLead').css('display','none');
        $('#del-lead').css('display','inline');
  		} else {
  			$('#enviar-combo').css('display','none');
        $('#del-lead').css('display','none');
  		}
	});

});

</script>
<br>
<div class="ui container">
<div class="ui right aligned grid">
  <div class="left floated left aligned six wide column">
		<button id="btn-incluir-lead" title="Cadastrar" data-element="#cadLead" class="ui labeled icon secondary button">
		  <i class="plus icon"></i>
		  Add Lead
		</button>
    <button class="ui icon red button" id="del-lead" title="Deletar"><i class="trash alternate icon"></i></button>
  </div>
  <div class="right floated right aligned six wide column">
      <div id="enviar-combo">
      	<div class="ui left icon action input">      	
			  <input id="tags" type="text" placeholder="buscar unidades...">
			  <i class="users icon"></i>
			  <button id="btn-atribuir-leads" class="ui secondary button">Enviar</button>
		  </div>
		</div>
  </div>
</div>
<br>
<form method="POST" action="">
  <div id="cadLead" class="ui segment">
    <h4 class="ui header">
      Cadastrar Lead
    </h4>
    <div class="ui form">
      <div class="fields">
        <div class="field">
          <label>Nome</label>
          <input type="text" name="nome" placeholder="Ex.: João da Silva">
        </div>
        <div class="field">
          <label>Email</label>
          <input type="text" name="email" placeholder="email@email.com">
        </div>
        <div class="field">
          <label>Telefone</label>
          <input type="text" name="telefone" placeholder="(xx) 0000-0000">
        </div>
        <div class="field">
          <label>Estado</label>
         <!--  <input type="text" placeholder="Ex: São Paulo"> -->
        <!-- Estado -->
        <select id="estados" name="estado">
          <option value=""></option>
        </select>
        </div>
        <div class="field">
          <label>Cidade</label>
          <!-- <input type="text" placeholder="UF"> -->
          <select id="cidades" name="cidade"></select>
        </div>
        <div class="field">
          <label>Identificador</label>
          <input type="text" name="identificador" placeholder="Ex.: chat-site">
        </div>
      </div>
      <!-- <div class="fields"> -->
        <div class="field">
          <label>Mensagem</label>
          <textarea rows="2" name="mensagem" placeholder="Escreva uma mensagem..."></textarea>
        </div>
      <!-- </div> -->
      <input type="submit" value="Cadastrar" class="ui positive button" onclick="Processo('incluir')">
      <input type="hidden" name="ok" id="ok" />
    </div>
  </div>
</form>
	<table class="ui fixed single line selectable compact celled center aligned definition table">
	    <thead>
	        <tr>
              <th class="one wide"></th>
              <th>Data</th>
              <th>Nome</th>
              <!-- <th>Email</th>
              <th>Telefone</th> -->
              <th>Cidade</th>
              <th>Estado</th>
              <th class="two wide">Origem</th>
              <th>Identificador</th>
              <th class="one wide">Mensagem</th>
              <th>Unidade</th>
              <th>Status</th>
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
	      <th colspan="9">
	        <div class="ui center floated buttons">
				<button id="anterior" class="ui button" disabled>&lsaquo; Anterior</button>
			    <button class="ui basic disabled button"><span id="numeracao"></span></button>
				<button id="proximo" class="ui button" disabled>Próximo &rsaquo;</button>
			</div>
	      </th>
	    </tr>
	  </tfoot>
	</table>
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
        }
      });
    location.reload();
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

<script type="text/javascript">
  $('#meuCalendarioIni').calendar({
    type: 'date',
    monthFirst: false,
    text: {
      days: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
      months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
      monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
      today: 'Today',
      now: 'Now',
      am: 'AM',
      pm: 'PM'
    },
    formatter: {
      date: function (date, settings) {
      if (!date) return '';
      var day = date.getDate() + '';
      if (day.length < 2) {
        day = '0' + day;
      }
      var month = date.getMonth() + 1 + '';
      if (month.length < 2) {
        month = '0' + month;
      }
      var year = date.getFullYear();
      return day + '/' + month + '/' + year;
    }
  }
  });
  $('#meuCalendarioFim').calendar({
    type: 'date',
    monthFirst: false,
    text: {
      days: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
      months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
      monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
      today: 'Today',
      now: 'Now',
      am: 'AM',
      pm: 'PM'
    },
    formatter: {
      date: function (date, settings) {
      if (!date) return '';
      var day = date.getDate() + '';
      if (day.length < 2) {
        day = '0' + day;
      }
      var month = date.getMonth() + 1 + '';
      if (month.length < 2) {
        month = '0' + month;
      }
      var year = date.getFullYear();
      return day + '/' + month + '/' + year;
    }
  }
  });
</script>