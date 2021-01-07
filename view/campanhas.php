<?php 

	include('menu.php'); 
	$id_user = $_SESSION['usuarioID'];
  $unidade_id = $_SESSION['usuarioUnidade'];
  //protegePagina();
  $permissao  = verificarPermissao($id_user); 

  //$account_id = buscarClientId($id_user); # Busca o Google Account ID
  $dfatos     = dadosParciaisFato($permissao,$id_user); # Retorna os valores da campanha do usuário logado

  if ($permissao >= 4) {
    $nome_unidade = getNomeUnidade($id_user);
    $campanha = detalhesCampanhaMensal($nome_unidade,$unidade_id);
    // echo $campanha;
  } else {
    $nome_unidade = getNomeUnidade($id_user);
    $campanha = detalhesCampanha($nome_unidade);
    // echo $campanha;  

  }
 ?>

<style type="text/css">
  #cartao-red {
    background-color: #DB2828;
    color: #fff;
  }
  #cartao-blue {
    background-color: #2185D0;
    color: #fff;
  }
  #cartao-green {
    background-color: #21BA45;
    color: #fff;
  }
  #cartao-yellow {
    background-color: #FBBD08;
    color: #fff;
  }

  #valor-php {
    font-size: 36pt;
  }

  #valor-php2 {
    font-size: 26pt;
  }

  #cadLead, #atribuir-leads,  {
    display: none;
  }

  #select-periodo {
    float: right;
  }
  
</style>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> 
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://cdn.rawgit.com/mdehoog/Semantic-UI-Calendar/76959c6f7d33a527b49be76789e984a0a407350b/dist/calendar.min.css" rel="stylesheet" type="text/css" />
<script src="bower_components/semantic-ui-calendar/dist/calendar.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


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
    $(document).on('click','#filtro-data', function(){
      var ini = $('#calendar-ini').val();
      var fim = $('#calendar-fim').val();
      var dini = toDate(ini); // Convertendo em data
      var dfim = toDate(fim); // Convertendo em data
      var ddatual = toDate(datual); // Convertendo em data
      if (dini > dfim || dini > ddatual) {
        var aviso = 'Favor selecionar um periodo válido!';
        alert(aviso);
      } else {
        $.ajax({
        url: '../model/ajaxFiltroAdwords.php',
        type: 'POST',
        data: {
          'ini': ini,
          'fim': fim,
        },
          success: function(retorno) {
            $('#manipular').html(retorno);
          }
      });
      }            
    })
    });
</script>
<!--  ********************************************************
      ** Script do segundo calendário de todas as campanhas **
      ******************************************************** -->


<?php 
  echo "<script>

          function toDate(dateStr) {
            var parts = dateStr.split('/');
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
              var uni = '".utf8_encode($nome_unidade)."';
              var dini = toDate(ini); // Convertendo em data
              var dfim = toDate(fim); // Convertendo em data
              var ddatual = toDate(datual); // Convertendo em data
              if (dini > dfim || dini > ddatual) {
                var aviso = 'Favor selecionar um periodo válido!';
                alert(aviso);
              } else {
                $.ajax({
                url: '../model/ajaxFiltroTodasCampanhas.php',
                type: 'POST',
                data: {
                  'ini': ini,
                  'fim': fim,
                  'uni': uni,
                },
                  success: function(retorno) {
                    $('#tabela_campanhas').remove();
                    $('#dimmer_campanha').addClass('loader');
                    window.setTimeout(function() {
                      $('#dimmer').removeClass('loader');
                      $('#manipular2').html(retorno);
                    }, 1500);
                  }
              });
              }            
            })
            });
        </script>";

?>

<div class="ui container">
<?php include 'sub_menu_campanhas.php'; ?>
<div class="ui grid">
  <div class="seven wide column">
    <h3 class="ui left aligned left floated header">
      Resumo
      <div class="sub header">Informações gerais dos últimos 30 dias sobre a sua campanha no Google Ads.</div>
    </h3>
  </div>
</div>
<br>
<div id="manipular">
  <div class="ui four cards">
    <div id="cartao-blue" class="fluid card">
      <div class="content">
        <div id="cartao-blue" class="header">
          <p>Clicks
          <i class="right floated hand point right inverted large icon"></i></p>
          <p id="valor-php" class="ui center aligned">
            <?php echo number_format($dfatos['cliques'],0,".","."); ?>
          </p>
        </div>
      </div>
    </div>
    <div id="cartao-yellow" class="fluid card">
      <div class="content">
        <div id="cartao-yellow" class="header">
          <p>Custo
          <i class="right floated credit card outline inverted large icon"></i></p>
          <p id="valor-php2" class="ui center aligned">
            <?php echo "R$ " . number_format($dfatos['custo'],2,",","."); ?>
          </p>
        </div>
      </div>
    </div>
    <div id="cartao-red" class="fluid card">
      <div class="content">
        <div id="cartao-red" class="header">
          <p>Conversões
          <i class="right floated sync alternate large inverted icon"></i></p>
          <p id="valor-php" class="ui center aligned">
            <?php //echo number_format($dfatos['conversoes'],0,".","."); ?>
            <?php 
              $conversoes = getConversoes($unidade_id); 
              echo $conversoes;
            ?>
          </p>
        </div>
      </div>
    </div>
    <div id="cartao-green" class="fluid card">
      <div class="content">
        <div id="cartao-green" class="header">
          <p>Custo por Conversão
          <i class="right floated credit card inverted large icon"></i></p>
          <p id="valor-php2" class="ui center aligned">
            <?php 
              if ($conversoes == 0) {
                $cpl = 0;
              } else {
                $cpl = $dfatos['custo']/$conversoes;
              }
              echo "R$ " . number_format($cpl,2,",","."); ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<br>
<div class="ui divider"></div>
    <h3 class="ui left aligned left floated header">
      Detalhes
      <div class="sub header">Informações específicas de acordo com cada unidade.</div>
    </h3>
  <br><br>
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
                <div class="item">
                  <i class="money icon"></i>
                  <a href="credito_campanhas.php" class="">Saldo</a>
                </div>   
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <div class="" id="manipular2">
    
  <?php 
    # CHAMANDO A FUNÇÃO PARA EXIBIR TODAS AS CAMPANHAS DETALHADAS
    echo $campanha;  
  ?>
  
  </div> 
</div>
<br><br><br>

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

<div id="retorno"></div>
<script type="text/javascript" src="tablesort.js"></script>
<script type="text/javascript" src="js/funcoes.js"></script>
<script>  
  $('.ui.dropdown')
  .dropdown({
    action: 'hide'
  })
;
</script>
<?php include 'rodape.php'; ?>