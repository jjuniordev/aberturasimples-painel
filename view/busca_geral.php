<?php 
  include '../model/funcoes.php';
  include '../model/seguranca.php';
?>

<?php 
	
	//Recuperar o valor da palavra
	$cursos = utf8_decode($_POST['palavra']);
	$status = $_POST['status'];
	
	//Pesquisar no banco de dados nome do curso referente a palavra digitada pelo usuário

	$cursos = "SELECT 
			    a.id
			    ,a.nome
			    ,a.email
			    ,a.telefone
			    ,a.cidade
			    ,a.estado
			    ,a.private_hash
                ,b.identificador
                ,b.origem
                ,b.midia
                ,b.campaign
                ,b.nicho_empresa
                ,f.faturamento
                ,t.tipo_empresa
			FROM
			    tb_leads a
			INNER JOIN
				tb_conversoes b
			ON
				a.id_ultima_conversao = b.id
			LEFT JOIN
				tb_chatbot_faturamento f 
			ON 
				b.id_faturamento_empresa = f.id
			LEFT JOIN
				tb_chatbot_tipoempresa t
			ON
				b.id_tipo_empresa = t.id
			WHERE
			    nome LIKE '%".$cursos."%' AND id_status ".$status."
				OR
			    email LIKE '%".$cursos."%' AND id_status ".$status."
			    OR
			    telefone LIKE '%".$cursos."%' AND id_status ".$status."
			    OR
				cidade LIKE '%".$cursos."%' AND id_status ".$status."
			    OR
			    estado LIKE '%".$cursos."%'	AND id_status ".$status."
			    OR
				b.identificador LIKE '%".$cursos."%' AND id_status ".$status."
                OR
                b.origem LIKE '%".$cursos."%' AND id_status ".$status."
                OR
                b.midia LIKE '%".$cursos."%' AND id_status ".$status."
                OR
                b.campaign LIKE '%".$cursos."%' AND id_status ".$status."
                OR
                b.nicho_empresa LIKE '%".$cursos."%' AND id_status ".$status."
                OR
				f.faturamento LIKE '%".$cursos."%' AND id_status ".$status."
                OR
                t.tipo_empresa LIKE '%".$cursos."%' AND id_status ".$status."
			ORDER BY b.data_conversao DESC;";

	$resultado_cursos = mysql_query($cursos);
	
	$pesquisa = "";
	$pesquisa .= "<table id='tabela_pesquisa' class='ui fixed single line selectable compact celled center aligned sortable table'>";
	$pesquisa .= "<thead>";
	$pesquisa .= '<th class="one wide no-sort"><div class="ui checkbox"><input type="checkbox" id="checkTodos2" name="checkTodos"><label></label></div></th>';
	$pesquisa .= "<th class='three wide'>Nome</th>";
	$pesquisa .= "<th class='three wide'>Email</th>";
	$pesquisa .= "<th>Telefone</th>";
	$pesquisa .= "<th>Cidade</th>";
	$pesquisa .= "<th class='two wide'>Identificador</th>";
	$pesquisa .= "<th class='one wide no-sort'></th>";
	$pesquisa .= "</thead>";

	if(mysql_num_rows($resultado_cursos) <= 0){
		echo "Nenhum dado encontrado...";
	}else{
		while($rows = mysql_fetch_assoc($resultado_cursos)){
			//echo "<li>".utf8_encode($rows['nome'])."</li>";
			$pesquisa .= "<tr>";
			$pesquisa .= "<td><div class='ui checkbox'><input class='input_busca' value='".$rows['id']."' name='Pacote' type='checkbox'><label></label></div></td> ";
			$pesquisa .= "<td title='".utf8_encode($rows['nome'])."'>".utf8_encode($rows['nome'])."</td>";
			$pesquisa .= "<td title='".$rows['email']."'>".$rows['email']."</td>";
			$pesquisa .= "<td>".$rows['telefone']."</td>";
			$pesquisa .= "<td>".utf8_encode($rows['cidade'])."</td>";
			$pesquisa .= "<td>".utf8_encode($rows['identificador'])."</td>";
			$pesquisa .= "<td><a class='ui basic icon mini button' href='profile.php?private=".$rows['private_hash']."'><i class='ellipsis vertical icon'></i></a></td>";
			$pesquisa .= "</tr>";
		}
	}

	$pesquisa .= "</table>";

	$pesquisa .= "<script>
	$('[name=Pacote]').click(function(){
		if($('[name=Pacote]').is(':checked')) {
  			$('#reativar').removeClass('disabled');
  			$('#btn-atribuir-leads').removeClass('disabled');
  			$('#trocar-combo').removeClass('disabled');
  			$('#del-lead').removeClass('disabled');
  			$('#enviar-combo').removeClass('disabled');
  		} else {
  			$('#reativar').addClass('disabled');
  			$('#btn-atribuir-leads').addClass('disabled');
  			$('#trocar-combo').addClass('disabled');
  			$('#del-lead').addClass('disabled');
  			$('#enviar-combo').addClass('disabled');
  		}
	});

	$('#checkTodos__').change(function () {
	$('input:checkbox').prop('checked', $(this).prop('checked'));
	});

	$('#checkTodos__').click(function(){
	    $('input:checkbox').not(this).prop('checked', this.checked);
	});

	var checkTodos = $('#checkTodos2');
	checkTodos.click(function () {
	  if ( $(this).is(':checked') ){
	    $('.input_busca:checkbox').prop('checked', true);
	    $('#reativar').removeClass('disabled');
		$('#btn-atribuir-leads').removeClass('disabled');
		$('#trocar-combo').removeClass('disabled');
		$('#del-lead').removeClass('disabled');
		$('#enviar-combo').removeClass('disabled');
	  }else{
	    $('.input_busca:checkbox').prop('checked', false);
	    $('#reativar').addClass('disabled');
		$('#btn-atribuir-leads').addClass('disabled');
		$('#trocar-combo').addClass('disabled');
		$('#del-lead').addClass('disabled');
		$('#enviar-combo').addClass('disabled');
	  }
	});
	var consulta = `".trim(str_replace("\n", "", $cursos))."`;
	
</script>
<script type='text/javascript' src='tablesort.js'></script>";

	echo $pesquisa;
?>
