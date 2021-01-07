<?php 
	include 'menu.php';

	$query = mysql_query("SELECT 
			replace(a.nome_campanha,'Leads ','') as Campanha, 
            sum(a.cliques) as cliques,
            sum(a.impressoes) as impressoes,
            round(sum(a.conversoes),0) as conversoes,
            round(avg(a.cpc),2) as cpc_medio,
            #round(avg(a.average_position),1) as posicao_media,
            round(sum(a.custo),2) as custo
        FROM 
            tb_facebook_fato_ads a
        WHERE 
            a.nome_campanha != ' -- '
        AND
            str_to_date(a.periodo,'%Y-%m-%d') BETWEEN DATE_SUB(NOW(), INTERVAL 31 DAY) AND NOW()
        GROUP BY
            a.nome_campanha
        ORDER BY custo DESC;");
?>
	<!-- Gambiarras de estilo -->
	<style type="text/css">
		#select-periodo {
	    float: right;
	  }
	</style>
	<!-- Bibliotecas de código para o calendário -->
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> 
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link href="https://cdn.rawgit.com/mdehoog/Semantic-UI-Calendar/76959c6f7d33a527b49be76789e984a0a407350b/dist/calendar.min.css" rel="stylesheet" type="text/css" />
	<script src="bower_components/semantic-ui-calendar/dist/calendar.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<div class="ui container">
		<?php include 'sub_menu_campanhas.php'; ?>
		<div class="ui grid">
		  <div class="nine wide column">
		    <h3 class="ui left aligned left floated header">
		      Facebook
		      <div class="sub header">Informações gerais dos últimos 30 dias sobre a sua campanha no Facebook Ads.</div>
		    </h3>
		  </div>
		</div>
	<div class="ui segment">
	    <div class="ui grid">
	      <div class="five wide column">
	          <div class="ui right icon fluid small input">
	            <i class="search icon"></i>
	            <input type="text" id="pesquisa" class="input-search" alt="lista-clientes" placeholder="Buscar..." autocomplete="off">
	          </div>
	      </div>
	      <div class="nine wide right floated column">
	        <!-- CALENDÀRIO PARA SELEÇÃO DE PERIODO -->
	        <div id="select-periodo" class="ui tiny form">
	          <div class="inline fields">
	            <div class="field">
	              <div class="ui calendar" id="meuCalendarioIni2">
	              <div class="ui input left icon">
	                <i class="calendar icon"></i>
	                <input type="text" id="calendar-ini2" placeholder="Data inicial" autocomplete="off">
	              </div>
	            </div>
	           </div>
	            <div class="field">
	              <div class="ui calendar" id="meuCalendarioFim2">
	              <div class="ui input left icon">
	                <i class="calendar icon"></i>
	                <input type="text" id="calendar-fim2" placeholder="Data Final" autocomplete="off">
	              </div>
	            </div>
	            </div>
	            <button id="filtro-data2" class="ui tiny black button">Filtrar</button>&nbsp;&nbsp;&nbsp;
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
	          </div>
	        </div>
	      </div>
	    </div>
	  <div class="" id="manipular2">
	  	<div id="dvData">
	  		<table class="ui center aligned celled selectable sortable table lista-clientes">
				<thead>
					<th>Campanha</th>
					<th>Cliques</th>
					<th>Impressões</th>
					<th>Conversões</th>
					<th>CPC</th>
					<th>Custo</th>
				</thead>
				<?php 
          $tot_cliques = 0;
          $tot_impressoes = 0;
          $tot_conversoes = 0;
          $avg_cpc = 0;
          $tot_custo = 0;
          $i = 0;
					while ($face = mysql_fetch_array($query)) {
						echo "<tr>";
						echo "<td>".utf8_encode($face['Campanha'])."</td>";
						echo "<td>".$face['cliques']."</td>";
						echo "<td>".$face['impressoes']."</td>";
						echo "<td>".$face['conversoes']."</td>";
						echo "<td>R$ ".$face['cpc_medio']."</td>";
						echo "<td>R$ ".$face['custo']."</td>";
						echo "</tr>";
            $tot_cliques += $face['cliques'];
            $tot_impressoes += $face['impressoes'];
            $tot_conversoes += $face['conversoes'];
            $avg_cpc += $face['cpc_medio'];
            $i ++;
            $tot_custo += $face['custo'];
					}
				?>
        <tfoot>
          <tr>
            <th><b>Total</b></th>
            <th><b><?php echo $tot_cliques; ?></b></th>
            <th><b><?php echo $tot_impressoes; ?></b></th>
            <th><b><?php echo $tot_conversoes; ?></b></th>
            <th><b>R$ <?php echo @number_format($avg_cpc / $i,2,".","."); ?></b></th>
            <th><b>R$ <?php echo $tot_custo; ?></b></th>
          </tr>
        </tfoot>
			</table>
      
	  	</div>
	  </div> 
	</div>	
</div>
<script type="text/javascript">

  function toDate(dateStr) {
    var parts = dateStr.split("/");
    return new Date(parts[2], parts[1] - 1, parts[0]);
  }
  // *********************************************************************
  // Script para pegar a data atual em JS
  // *********************************************************************
  var d = new Date();
  var month = d.getMonth()+1;
  var day = d.getDate();
  var datual = 
      ((''+day).length<2 ? '0' : '') + day + '/' +
      ((''+month).length<2 ? '0' : '') + month + '/' + d.getFullYear();
  // *********************************************************************

  $(document).ready(function(){
    $(document).on('click','#filtro-data2', function(){
      var ini = $('#calendar-ini2').val();
      var fim = $('#calendar-fim2').val();
      var dini = toDate(ini); // Convertendo em data
      var dfim = toDate(fim); // Convertendo em data
      var ddatual = toDate(datual); // Convertendo em data
      if (dini > dfim || dini > ddatual) {
        var aviso = 'Favor selecionar um periodo válido!';
        alert(aviso);
      } else {
        $.ajax({
        url: '../model/ajaxFiltroFacebookAds.php',
        type: 'POST',
        data: {
          'ini': ini,
          'fim': fim,
        },
          success: function(retorno) {
            $('#manipular2').html(retorno);
          }
      });
      }            
    })
    });
</script>
<script type="text/javascript">
  $('#meuCalendarioIni2').calendar({
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
  $('#meuCalendarioFim2').calendar({
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

<script type="text/javascript" src="tablesort.js"></script>
<script type="text/javascript" src="js/funcoes.js"></script>
<script>  
  $('.ui.dropdown')
  .dropdown({
    action: 'hide'
  })
;
</script>