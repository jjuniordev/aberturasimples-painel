<?php 
	
	if ($permissao <= 3) {
		echo '<h1 class="ui center aligned header"><i class="ui bullhorn icon"></i>Campanhas</h1>
		  <div class="ui inverted menu">
		    <a class="item" id="google" href="campanhas.php">
		      Google
		    </a>
		    <a class="item" id="facebook" href="facebook.php">
		      Facebook
		    </a>
		  </div>';
	} else {
		echo '<h1 class="ui center aligned header"><i class="ui bullhorn icon"></i>Campanhas</h1>
		  <div class="ui inverted menu">
		    <a class="active item" id="detalhes" href="campanhas.php">
		      Detalhes
		    </a>
		  </div>';
	}
	

?>



<script type="text/javascript">

	var documento = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);

	switch (documento) {
		case 'campanhas.php':
			$('#google').addClass('active');
			$('#facebook').removeClass('active');
			break;
		case 'facebook.php':
			$('#facebook').addClass('active');
			$('#google').removeClass('active');
			break;
		case 'credito_campanhas.php':
			$('#google').addClass('active');
			$('#facebook').removeClass('active');
			break;
	}
</script>

<script type="text/javascript">
  $(function buscaTabela(){
  $(".input-search").keyup(function(){
  //pega o css da tabela 
  var tabela = $(this).attr('alt');
  if( $(this).val() != ""){
      $("."+tabela+" tbody>tr").hide();
      $("."+tabela+" td:contains-ci('" + $(this).val() + "')").parent("tr").show();
  } else{
      $("."+tabela+" tbody>tr").show();
  }
  }); 
  });
  $.extend($.expr[":"], {
  "contains-ci": function(elem, i, match, array) {
      return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
  }
  });
</script>