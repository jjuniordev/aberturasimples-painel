<?php 
  include '../model/funcoes.php';
  include '../model/seguranca.php';
?>

<?php 
	
	//Recuperar o valor da palavra
	$cursos = utf8_decode($_POST['palavra']);
	$status = $_POST['status'];
	$unidade_id = $_SESSION['usuarioUnidade'];
	$responsaveis 	= @implode(",", $_SESSION['responsaveis']);
	
	//Pesquisar no banco de dados nome do curso referente a palavra digitada pelo usuário
	//$cursos = "SELECT * FROM cursos WHERE nome LIKE '%$cursos%'";

	if ($responsaveis != "") {
		$cursos = "SELECT 
			    a.id
					,b.data_conversao
					,date_format(b.data_conversao,'%d/%m/%y - %H:%i') as data_limpa
					,a.nome
					,a.email
					,a.telefone
					,a.cidade
					,a.estado
					,b.identificador
					,b.origem
					,b.mensagem
					,c.status2
					,d.nome_unidade
					,a.esta_ativo
		            ,e.nome as responsavel
		            ,e.sobrenome as responsavel_n
		            ,a.private_hash
				FROM tb_leads a
					INNER JOIN tb_conversoes b ON a.id_ultima_conversao = b.id
					INNER JOIN tb_lead_status c ON a.id_status = c.id
					INNER JOIN tb_unidades d ON a.id_unidade = d.id
		            INNER JOIN tb_usuarios e ON a.id_usuario = e.id
			WHERE
			    	a.nome LIKE '%".$cursos."%' 
			    	AND a.id_status ".$status." 
			    	AND a.id_unidade = ".$unidade_id." 
			    	AND e.id in (".$responsaveis.")
				OR
			    	a.email LIKE '%".$cursos."%' 
			    	AND a.id_status ".$status." 
			    	AND a.id_unidade = ".$unidade_id." 
			    	AND e.id in (".$responsaveis.")
			    OR
			    	a.telefone LIKE '%".$cursos."%' 
			    	AND a.id_status ".$status." 
			    	AND a.id_unidade = ".$unidade_id." 
			    	AND e.id in (".$responsaveis.")
			    OR
					a.cidade LIKE '%".$cursos."%' 
					AND a.id_status ".$status." 
					AND a.id_unidade = ".$unidade_id." 
					AND e.id in (".$responsaveis.")
			    OR
			    	a.estado LIKE '%".$cursos."%' 
			    	AND a.id_status ".$status." 
			    	AND a.id_unidade = ".$unidade_id." 
			    	AND e.id in (".$responsaveis.")
			    OR
					b.identificador LIKE '%".$cursos."%' 
					AND a.id_status ".$status." 
					AND a.id_unidade = ".$unidade_id." 
					AND e.id in (".$responsaveis.")
			    ;";
	} else {
		$cursos = "SELECT 
			    a.id
					,b.data_conversao
					,date_format(b.data_conversao,'%d/%m/%y - %H:%i') as data_limpa
					,a.nome
					,a.email
					,a.telefone
					,a.cidade
					,a.estado
					,b.identificador
					,b.origem
					,b.mensagem
					,c.status2
					,d.nome_unidade
					,a.esta_ativo
		            ,e.nome as responsavel
		            ,e.sobrenome as responsavel_n
		            ,a.private_hash
				FROM tb_leads a
					INNER JOIN tb_conversoes b ON a.id_ultima_conversao = b.id
					INNER JOIN tb_lead_status c ON a.id_status = c.id
					INNER JOIN tb_unidades d ON a.id_unidade = d.id
		            INNER JOIN tb_usuarios e ON a.id_usuario = e.id
			WHERE
				    a.nome LIKE '%".$cursos."%' 
				    AND a.id_status ".$status." 
				    AND a.id_unidade = ".$unidade_id."
				OR
			    	a.email LIKE '%".$cursos."%' 
			    	AND a.id_status ".$status." 
			    	AND a.id_unidade = ".$unidade_id."
			    OR
			    	a.telefone LIKE '%".$cursos."%' 
			    	AND a.id_status ".$status." 
			    	AND a.id_unidade = ".$unidade_id."
			    OR
					a.cidade LIKE '%".$cursos."%' 
					AND a.id_status ".$status." 
					AND a.id_unidade = ".$unidade_id."
			    OR
			    	a.estado LIKE '%".$cursos."%' 
			    	AND a.id_status ".$status." 
			    	AND a.id_unidade = ".$unidade_id."
			    OR
					b.identificador LIKE '%".$cursos."%' 
					AND a.id_status ".$status."	
					AND a.id_unidade = ".$unidade_id."
			    ;";
	}
	

	$resultado_cursos = mysql_query($cursos);
	
	$pesquisa = "";
	$pesquisa .= "<table id='tabela_pesquisa' class='ui fixed single line selectable compact celled center aligned sortable table'>";
	$pesquisa .= "<thead>";
	$pesquisa .= '<th class="one wide no-sort"><div class="ui checkbox"><input type="checkbox" id="checkTodos2" name="checkTodos"><label></label></div></th>';
	$pesquisa .= "<th class='nome'>Nome</th>";
	$pesquisa .= "<th class='email'>Email</th>";
	$pesquisa .= "<th class='telefone no-sort'>Telefone</th>";
	$pesquisa .= "<th class='cidade'>Cidade</th>";
	$pesquisa .= "<th class='estado'>Estado</th>";
	$pesquisa .= "<th class='data'>Data</th>";
	$pesquisa .= "<th class='responsavel'>Responsável</th>";
	$pesquisa .= "<th class='identificador'>Identificador</th>";
	$pesquisa .= "<th class='mensagem'>Mensagem</th>";
	$pesquisa .= "<th class='one wide no-sort'></th>";
	$pesquisa .= "</thead>";

	if(mysql_num_rows($resultado_cursos) <= 0){
		echo "Nenhum dado encontrado...";
	}else{
		while($rows = mysql_fetch_assoc($resultado_cursos)){
			//echo "<li>".utf8_encode($rows['nome'])."</li>";
			$pesquisa .= "<tr>";
			$pesquisa .= "<td><div class='ui checkbox'><input class='input_busca' value='".$rows['id']."' name='Pacote' type='checkbox'><label></label></div></td> ";
			$pesquisa .= "<td class='nome' title='".utf8_encode($rows['nome'])."'>".utf8_encode($rows['nome'])."</td>";
			$pesquisa .= "<td class='email' title='".$rows['email']."'><a href='mailto:".$rows['email']."' target='_blank'>".$rows['email']."</a></td>";
			$pesquisa .= "<td class='telefone'><a href='tel:".$rows['telefone']."' target='_blank'>".$rows['telefone']."</a></td>";
			$pesquisa .= "<td class='cidade'>".utf8_encode($rows['cidade'])."</td>";
			$pesquisa .= "<td class='estado'>".utf8_encode($rows['estado'])."</td>";
			$pesquisa .= "<td class='data'>".utf8_encode($rows['data_limpa'])."</td>";
			$pesquisa .= "<td class='responsavel'>".utf8_encode($rows['responsavel'])."</td>";
			$pesquisa .= "<td class='identificador' name='identificador'>".utf8_encode($rows['identificador'])."</td>";
			$pesquisa .= "<td class='mensagem'>".utf8_encode($rows['mensagem'])."</td>";
			$pesquisa .= "<td><a class='ui basic icon mini button' href='profile.php?private=".$rows['private_hash']."'><i class='ellipsis vertical icon'></i></a></td>";
			$pesquisa .= "</tr>";
		}
	}

	$pesquisa .= "</table>";

	$pesquisa .= "<script>
	$('[name=Pacote]').click(function(){
		if($('[name=Pacote]').is(':checked')) {
  			$('#follow').removeClass('disabled');
			$('#cliente').removeClass('disabled');
			$('#rejeitado').removeClass('disabled');
			$('#deletar').removeClass('disabled');
  		} else {
  			$('#reativar').addClass('disabled');
  			$('#follow').addClass('disabled');
			$('#cliente').addClass('disabled');
			$('#rejeitado').addClass('disabled');
			$('#deletar').addClass('disabled');
  		}
	});


	var checkTodos = $('#checkTodos2');
	checkTodos.click(function () {
	  if ( $(this).is(':checked') ){
	    $('.input_busca:checkbox').prop('checked', true);
	    $('#follow').removeClass('disabled');
		$('#cliente').removeClass('disabled');
		$('#rejeitado').removeClass('disabled');
		$('#deletar').removeClass('disabled');
	  }else{
	    $('.input_busca:checkbox').prop('checked', false);
	    $('#follow').addClass('disabled');
		$('#cliente').addClass('disabled');
		$('#rejeitado').addClass('disabled');
		$('#deletar').addClass('disabled');
	  }
	});

</script>
<script type='text/javascript' src='tablesort.js'></script>";

 $filtro_colunas = @$_SESSION['colunas'];

	if ($filtro_colunas == "") {
		$col = ["telefone","cidade","data","responsavel"];
		$not_col = ["estado","identificador","mensagem"];
		$tam_col = count($not_col);
		echo "<script>var colunas = [";
		for ($i=0; $i < $tam_col; $i++) { 
			echo "['".$not_col[$i]."'],";
		}
		echo "];
		var qspan = 0;
		</script>";
	} else {
		$tam_col = count($filtro_colunas);
		$not_col = $filtro_colunas;
		echo "<script>var colunas = [";
		for ($i=0; $i < $tam_col; $i++) { 			
			echo "['".$filtro_colunas[$i]."'],";
		}
		echo "];
		var qspan = 1;
		</script>";
	}

	$pesquisa .= "<script>for (var ind = 0; ind < colunas.length; ind++) {
		    		$('.'+colunas[ind]).hide();
		    		//$()
		    	};</script>";	

	echo $pesquisa;
?>
