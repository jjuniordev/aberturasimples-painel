<?php 

	//include 'menu.php';	

	function getResponsaveis() {

		$unidade_id = $_SESSION['usuarioUnidade'];

		$query = mysql_query("SELECT 
							    a.id
							    ,a.nome
							    ,a.sobrenome
							    ,b.nome_unidade
							FROM
							    tb_usuarios a
							INNER JOIN
								tb_unidades b
							ON a.id_unidade = b.id
							WHERE
							    active = 1
						    AND 
						    	a.id_unidade = $unidade_id
							ORDER BY a.nome ASC;");
		$res = '';
		$res .= '<div class="ui divider"></div>
		            <div class="header">
				      Adicionar Responsável
				    </div>
				    <div class="ui icon search input">
	 			      <i class="search icon"></i>
	 			      <input type="text" placeholder="Procurar nomes...">
	 			    </div>';
	 			    
		while ($resp = mysql_fetch_array($query)) {

			$res .= '<div id="'.$resp['id'].'" class="item responsavel_div disabled">
			    		<img class="ui avatar image" src="images/avatar/small/avatar.png">
			    		'.utf8_encode($resp['nome']).' '.utf8_encode(
			    			$resp['sobrenome']).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			    	</div>';
		}

		echo $res;
	}
?>


<?php getResponsaveis(); ?>

<script type="text/javascript">
	$('.ui.dropdown')
  		.dropdown()
	;

	// // Resgata o id do usuário selecionado
	$('.responsavel_div').click(function(){
		var id = $(this).attr('id');
		var pacote   = document.querySelectorAll('[name=Pacote]:checked');
		var values   = [];
		var nome     = $('#tags').val();
		  for (var i = 0; i < pacote.length; i++) {
		    // utilize o valor aqui, adicionei ao array para exemplo
		    values.push(pacote[i].value);
		  }
		// AJAX PARA ATRIBUIR OS LEADS SELECIONADOS PARA A UNIDADE ENVIADA
	    $.ajax({
	        url: '../model/ajaxSetResponsavel.php',
	        type: 'POST',
	        data: {
	          'valores': values,
	          'id': id
	        },
	        beforeSend: function(){	
					$('#opcoes').addClass('loading');	    
				  },
	        success: function(data) { //Se a requisição retornar com sucesso, 
				//ou seja, se o arquivo existe, entre outros
		        //$('#sucesso').html(data); //Parte do seu 
		        setTimeout(function(){
			    	$('#opcoes').removeClass('loading');
			    	//$(window).attr('location','leads_atribuidos.php');
			    	location.reload();
			    },1500);
		    }
	      });
	});

</script>