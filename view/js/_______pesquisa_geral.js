$(function(){
	//Pesquisar os cursos sem refresh na página
	$("#pesquisa").keyup(function(){
		
		var pesquisa = $(this).val();
		
		//Verificar se há algo digitado
		if(pesquisa != ''){
			var dados = {
				palavra : pesquisa
			}
			$.ajax({
				url: '../view/busca_geral.php',
				type: 'POST',
				data: {
					'palavra': dados
				},
				success: function(retorna){
					$(".resultado").html(retorna);
				}
			});} else {
				$(".resultado").html('');
			}	
		// 	$.ajax('../view/busca_geral.php', dados, function(retorna){
		// 		//Mostra dentro da ul os resultado obtidos 
		// 		$(".resultado").html(retorna);
		// 		//$(".resultado").html('retorna');
		// 	});
		// }else{
		// 	$(".resultado").html('');
		// }		
	});
});