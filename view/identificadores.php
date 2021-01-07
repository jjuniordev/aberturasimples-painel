<?php 
	include('menu.php'); 
	$id_user = $_SESSION['usuarioID'];
	$unidade_id = $_SESSION['usuarioUnidade'];
	$permissao = verificarPermissao($id_user);	
?>
<!-- GAMBIARRA DO FILTRO DE DATA INICIAL E FINAL -->
 <style type="text/css">
    #select-periodo {
    float: right;
  }
 </style>
 
<link href="https://cdn.rawgit.com/mdehoog/Semantic-UI-Calendar/76959c6f7d33a527b49be76789e984a0a407350b/dist/calendar.min.css" rel="stylesheet" type="text/css" />
<script src="bower_components/semantic-ui-calendar/dist/calendar.min.js"></script>
<div class="ui container">
	<h1 class="ui center aligned header"><i class="chart area icon"></i></i>Relatórios</h1>
	<!-- <div class="ui divider"></div> -->
	<?php 
		if ($permissao <= 3) {
			include 'sub_menu_relatorios.php';
		} 
	?>
	<div class="ui grid">
		<div class="seven wide column">
			<h3 class="ui header">
	            Identificadores
	            <div class="sub header">Soma de identificadores por conversão.</div>
	        </h3>
		</div>
	</div>
        
<div class="ui segment">
	  <div class="ui grid">
  	  <div class="five wide column">
  	    <div class="ui right icon small fluid input">
  	      <i class="search icon"></i>
  	      <input type="text" id="pesquisa" class="input-search" alt="lista-clientes" placeholder="Buscar..." autocomplete="off">
  	    </div>
  	  </div>
	  <div class="eleven wide right floated right aligned column">
	     <div id="select-periodo" class="ui small form">
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
	        <button id="filtro-data2" class="ui small black button">Filtrar</button>&nbsp;&nbsp;&nbsp;
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

<?php 
	echo "<div id='manipular'>";
	$tabela = identificadores($unidade_id,$permissao); 
	echo $tabela;
  echo "</div>";
?>
</div>
<br><br>
</div>

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
        url: '../model/ajaxFiltroIdentificadores.php',
        type: 'POST',
        data: {
          'ini': ini,
          'fim': fim,
        },
          success: function(retorno) {
            $('#tabela_unidades').remove();
            $('#dimmer_campanha').addClass('loader');
            window.setTimeout(function() {
              $('#dimmer').removeClass('loader');
              $('#manipular').html(retorno);
            }, 1500);
          }
      });
      }            
    })
    });
</script>

<script type="text/javascript" src="js/funcoes.js"></script>
<script type="text/javascript" src="tablesort.js"></script>
<script>  
  $('.ui.dropdown')
  .dropdown({
    action: 'hide'
  })
;
</script>