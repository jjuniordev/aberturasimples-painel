<?php 

	include('menu.php'); 
	$id_user = $_SESSION['usuarioID'];
  $unidade_id = $_SESSION['usuarioUnidade'];
  //protegePagina();
  require_once('../controller/ControleLead.php');
  Processo('incluir'); // ----- PASSA O PROCESSO AO CONTROLE ----- //

  $account_id = buscarClientId($id_user); # Busca o Google Account ID
  $permissao  = verificarPermissao($id_user); 

 ?>

<style type="text/css">
  #cartao-red {
    background-color: #DA542E;
    color: #fff;
  }
  #cartao-lblue {
    background-color: #27A9E3;
    color: #fff;
  }
  #cartao-green {
    background-color: #28B779;
    color: #fff;
  }
  #cartao-blue {
    background-color: #2255A4;
    color: #fff;
  }

  #valor-php {
    font-size: 36pt;
  }

  #valor-php2 {
    font-size: 26pt;
  }

  #cadLead, #atribuir-leads  {
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

<?php 

  if ($permissao >= 4) {
    $dfatos = dadosParciaisFato($permissao,$unidade_id); 
    include 'cards_associado.php';
  } else {
    $dadosCards = dadosParciaisFatoAdm();
    include 'cards_adm.php';
  }

?>

<br>
<div class="ui divider"></div>
<div class="ui grid">
  <div class="left aligned column">
    <h3 class="ui header">
      Gráficos
      <div class="sub header">Visão detalhada sobre os leads com gráficos diversos.</div>
    </h3>

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