<?php 

	include('menu.php'); 
	$id_user = $_SESSION['usuarioID'];
	$permissao  = verificarPermissao($id_user); 
	# Verificar permissão e negar acesso caso não tenha privilégios
  	if ($permissao >= 4) {
    	echo "Você não tem permissão para acessar esta página, <a href='index.php'>clique aqui</a> para voltar ao painel.";
    	exit();
  	}

?>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> 
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
 	<h1 class="ui center aligned header"><i class="wrench icon"></i>Manutenção</h1> 
   <div class="ui divider"></div>
	<br>
  <button class="ui basic button" id="btn-corrigir-leads">
    <i class="wrench icon"></i>
    Corrigir leads
  </button>
  <br><br>
  <div class="ui indicating progress" data-value="1" data-total="10" id="example4">
    <div class="bar">
      <div class="progress"></div>
    </div>
    <div class="label">Leads corrigidos</div>
  </div>
</div>

<script>
$('#btn-corrigir-leads').click(() => {
  $('#example4').progress('reset');
  $('#example4').progress('set label', 'Leads corrigidos');
  $('#btn-corrigir-leads').addClass('loading');
  $.ajax({
    url: '../model/ajaxCorrigirLeads.php',
    type: 'POST',
    data: {},
    dataType: 'json',
    beforeSend: (data) => {
      $('#example4').progress('increment');
    },
    success: (data) => {
      if (!data['success']) {
        $('#example4').progress('set error');
        $('#example4').progress('set label', 'Erro ao normalizar leads, refaça a operação');
        $('#btn-corrigir-leads').removeClass('loading');
      }
      $('#example4').progress('complete');
      $('#btn-corrigir-leads').removeClass('loading');
    }
  });
  $('#example4')
    .progress('increment')
  ;
});

</script>