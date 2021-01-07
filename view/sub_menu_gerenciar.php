<?php 
	if ($permissao >= 4) {
		echo '<div class="ui inverted menu">
				  <a id="usuarios" class="item" href="consultarusuario.php">
				    Usuários
				  </a>
				</div>';
	} else {
		echo '<div class="ui inverted menu">
				  <a id="usuarios" class="item" href="consultarusuario.php">
				    Usuários
				  </a>
				  <a id="unidades" class="item active" href="consultarunidades.php"> 
				    Unidades
				  </a>
				</div>';
	}

 ?>

<script type="text/javascript">
	var documento = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);

	switch (documento) {
				case 'consultarusuario.php':
					$('#usuarios').addClass('active');
					$('#unidades').removeClass('active');
					$('#grupos').removeClass('active');
					break;
				case 'consultarunidades.php':
					$('#unidades').addClass('active');
					$('#usuarios').removeClass('active');
					$('#grupos').removeClass('active');
					break;
				case 'adduser.php':
					$('#usuarios').addClass('active');
					$('#unidades').removeClass('active');
					$('#grupos').removeClass('active');
					break;
				case 'addunidades.php':
					$('#unidades').addClass('active');
					$('#usuarios').removeClass('active');
					$('#grupos').removeClass('active');
					break;
				case 'teste.php':
					$('#grupos').addClass('active');
					$('#usuarios').removeClass('active');
					$('#unidades').removeClass('active');
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

