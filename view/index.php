<?php 

	include('menu.php'); 
	$id_user = $_SESSION['usuarioID'];
  $unidade_id = $_SESSION['usuarioUnidade'];
  //protegePagina();
  require_once('../controller/ControleLead.php');
  Processo('incluir'); // ----- PASSA O PROCESSO AO CONTROLE ----- //

  $account_id = buscarClientId($id_user); # Busca o Google Account ID
  $permissao  = verificarPermissao($id_user); 
  $dfatos     = dadosParciaisFato($permissao,$id_user); # Retorna os valores da campanha do usuário logado

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


 <!-- 
 *********************************************** 
 * Requisição Ajax p/ botões da página inicial *
 ***********************************************
 -->
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click','#filtro-data', function(){
      var ini = $('#calendar-ini').val();
      var fim = $('#calendar-fim').val();
      //alert(ini + " - " + fim);
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
    })
    });
</script>
<div class="ui container">
  <!-- <div id="manipular"></div> -->
<h1 class="ui center aligned header"><i class="ui tachometer alternate icon"></i>Painel</h1>
<div class="ui divider"></div>
<div class="ui grid">
  <div class="seven wide column">
    <h3 class="ui left aligned left floated header">
      Campanha Google
      <!-- <div class="sub header">Informações gerais sobre a sua campanha no Google AdWords.</div> -->
      <div class="sub header">Informações gerais sobre a sua campanha no Google AdWords.</div>
    </h3>
  </div>
  <div class="seven wide right floated column">
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
<div class="ui grid">
  <div class="left aligned column">
    <h3 class="ui header">
      Visão Geral do Lead
      <div class="sub header">Relatório de informações dos leads gerados no portal.</div>
    </h3>

<?php //include 'tb_leads.php'; # Arquivo separado com a tabela de Leads. ?>

</div>
</div> 
<br>

<?php 

  if ($permissao >= 4) {
    include 'graficos_externo.php'; 
  } else {
    include 'graficos_interno.php'; 
  }
  
?>

<br><br><br>

<?php include 'rodape.php'; ?>