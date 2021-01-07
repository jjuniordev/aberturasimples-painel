<?php 
  include '../model/funcoes.php';
  include '../model/seguranca.php';
?>

<?php 
	
	//Recuperar o valor da palavra
	$cursos = utf8_decode($_POST['palavra']);
	
	//Pesquisar no banco de dados nome do curso referente a palavra digitada pelo usuário
	//$cursos = "SELECT * FROM cursos WHERE nome LIKE '%$cursos%'";
	$cursos = "SELECT 
					*
				FROM
					tb_unidades
				WHERE
					nome_unidade LIKE '%".$cursos."%' AND esta_ativo = 1
				OR
					Cidade like '%".$cursos."%' AND esta_ativo = 1
				OR
					Estado like '%".$cursos."%' AND esta_ativo = 1
				OR 
					google_id like '%".$cursos."%' AND esta_ativo = 1
				;";
	$resultado_cursos = mysql_query($cursos);
	
	$pesquisa = "";
	$pesquisa .= "<table class='ui fixed single line selectable compact celled center aligned sortable table'>";
	$pesquisa .= "<thead>";
	$pesquisa .= '<th class="one wide no-sort"><div class="ui checkbox"><input type="checkbox" id="checkTodos2" name="checkTodos"><label></label></div></th>';
	$pesquisa .= "<th>Nome</th>";
	$pesquisa .= "<th>Cidade</th>";
	$pesquisa .= "<th>Estado</th>";
	$pesquisa .= "<th>Google ID</th>";
	$pesquisa .= "</thead>";

	if(mysql_num_rows($resultado_cursos) <= 0){
		echo "Nenhum dado encontrado...";
	}else{
		while($rows = mysql_fetch_assoc($resultado_cursos)){
			//echo "<li>".utf8_encode($rows['nome'])."</li>";
			$pesquisa .= "<tr>";
			$pesquisa .= "<td><div class='ui checkbox'><input class='input_busca' value='".$rows['id']."' name='Pacote' type='checkbox'><label></label></div></td> ";
			$pesquisa .= "<td name='nome_unidade' id='".$rows['id']."' class='unidadeEditavel'>".utf8_encode($rows['nome_unidade'])."</td>";
			$pesquisa .= "<td>".utf8_encode($rows['Cidade'])."</td>";
			$pesquisa .= "<td>".utf8_encode($rows['Estado'])."</td>";
			$pesquisa .= "<td name='google_id' id='".$rows['id']."' class='unidadeEditavel'>".$rows['google_id']."</td>";
			$pesquisa .= "</tr>";
		}
	}

	$pesquisa .= "</table>";

	$pesquisa .= "<script>
				// Verificar se alguma linha está checkada e exibir botões.
			        $('[name=Pacote]').click(function(){
			            if($('[name=Pacote]').is(':checked')) {
			                $('#del-unidade').removeClass('disabled');
			            } else {
			                $('#del-unidade').addClass('disabled');
			            }
			        });
			        duploCliqueUnid();

	    var checkTodos = $('#checkTodos2');
		checkTodos.click(function () {
		  if ( $(this).is(':checked') ){
		    $('.input_busca:checkbox').prop('checked', true);
		    $('#del-unidade').removeClass('disabled');
		  }else{
		    $('.input_busca:checkbox').prop('checked', false);
		    $('#del-unidade').addClass('disabled');
		  }
		});
        </script>
        <script type='text/javascript' src='tablesort.js'></script>";

	echo $pesquisa;
?>