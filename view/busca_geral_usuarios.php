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
					a.*
                    ,b.nome_unidade
                    ,c.level
				FROM
					tb_usuarios a 
				INNER JOIN
					tb_unidades b
				ON
					a.id_unidade = b.id
				INNER JOIN
					tb_level c
				ON
					a.id_level = c.id
				WHERE
					a.nome LIKE '%".$cursos."%' AND a.active = 1
				OR
					a.sobrenome like '%".$cursos."%' AND a.active = 1
				OR
					a.email like '%".$cursos."%' AND a.active = 1
				OR 
					a.login like '%".$cursos."%' AND a.active = 1
				OR
					b.nome_unidade like '%".$cursos."%' AND a.active = 1
				OR
					c.level like '%".$cursos."%' AND a.active = 1
				;";
	$resultado_cursos = mysql_query($cursos);
	
	$pesquisa = "";
	$pesquisa .= "<table class='ui fixed single line selectable compact celled center aligned sortable table'>";
	$pesquisa .= "<thead>";
	$pesquisa .= '<th class="one wide no-sort"><div class="ui checkbox"><input type="checkbox" id="checkTodos2" name="checkTodos"><label></label></div></th>';
	$pesquisa .= "<th>Nome</th>";
	$pesquisa .= "<th>Sobrenome</th>";
	$pesquisa .= "<th>Email</th>";
	$pesquisa .= "<th>Login</th>";
	$pesquisa .= "<th>Unidade</th>";
	$pesquisa .= "<th>Permissão</th>";
	$pesquisa .= "</thead>";

	if(mysql_num_rows($resultado_cursos) <= 0){
		echo "Nenhum dado encontrado...";
	}else{
		while($rows = mysql_fetch_assoc($resultado_cursos)){
			//echo "<li>".utf8_encode($rows['nome'])."</li>";
			$pesquisa .= "<tr>";
			$pesquisa .= "<td><div class='ui checkbox'><input class='input_busca' value='".$rows['id']."' name='Pacote' type='checkbox'><label></label></div></td> ";
			$pesquisa .= "<td name='nome' id='".$rows['id']."' class='tabelaEditavel'>".utf8_encode($rows['nome'])."</td>";
			$pesquisa .= "<td name='sobrenome' id='".$rows['id']."' class='tabelaEditavel'>".utf8_encode($rows['sobrenome'])."</td>";
			$pesquisa .= "<td>".utf8_encode($rows['email'])."</td>";
			$pesquisa .= "<td name='login' id='".$rows['id']."' class='tabelaEditavel'>".$rows['login']."</td>";
			$pesquisa .= "<td name='nome_unidade' id='".$rows['id']."'>".utf8_encode($rows['nome_unidade'])."</td>";
			$pesquisa .= "<td name='level' id='".$rows['id']."'>".utf8_encode($rows['level'])."</td>";
			$pesquisa .= "</tr>";
		}
	}

	$pesquisa .= "</table>";

	$pesquisa .= "<script>
				// Verificar se alguma linha está checkada e exibir botões.
		        $('[name=Pacote]').click(function(){
		            if($('[name=Pacote]').is(':checked')) {
		                $('#del-user').removeClass('disabled');
		            } else {
		                $('#del-user').addClass('disabled');
		            }
		        });
		        duploCliqueEdit();
		        var checkTodos = $('#checkTodos2');
				checkTodos.click(function () {
				  if ( $(this).is(':checked') ){
				    $('.input_busca:checkbox').prop('checked', true);
				    $('#del-user').removeClass('disabled');
				  }else{
				    $('.input_busca:checkbox').prop('checked', false);
				    $('#del-user').addClass('disabled');
				  }
				});
        </script>
        <script type='text/javascript' src='tablesort.js'></script>";

	echo $pesquisa;
?>