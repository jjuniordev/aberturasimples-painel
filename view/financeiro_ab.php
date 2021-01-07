<?php  
	
	include 'menu.php';
	$id_user = $_SESSION['usuarioID'];
	$permissao  = verificarPermissao($id_user); 
	if ($permissao > 3) {
		echo "Você não tem permissão para acessar este conteúdo.";
		exit();
	} 

	function getAssinaturas() {
		$query = mysql_query("SELECT date_format(data_evento,'%d/%m/%Y') as data_f,id_assinatura FROM tb_iugu WHERE status = 1 and id_empresa = 1 ORDER BY data_evento DESC;");

		echo "<table class='ui fixed single line selectable compact celled center aligned sortable table'>
				<thead>
					<th>Data</th>
					<th>Assinatura</th>
					<th>Link</th>
				</thead>";

		while ($dados = mysql_fetch_array($query)) {
			echo "<tr>";
			echo "<td>".$dados['data_f']."</td>";
			echo "<td>".$dados['id_assinatura']."</td>";
			echo "<td><a href='https://app.iugu.com/subscriptions/".$dados['id_assinatura']."' target='_blank'>Visualizar no Iugu</a></td>";
			echo "</tr>";
		}

		echo "</table>";
		}

?>

<div class="ui container">
	<h1 class="ui center aligned header"><i class="money bill alternate icon"></i></i>Financeiro</h1>
<div class="ui inverted menu" id="submenu">
	
	<a class="active item" href="financeiro_ab.php">
	    Abertura Simples
  	</a>	
  	<a class="item" href="financeiro.php">
	    Arena Factus
  	</a>	  
</div>
<div class="ui grid">
  <div class="seven wide column">
    <h3 class="ui left aligned left floated header">
      Assinaturas Suspensas
      <div class="sub header">Lista de assinaturas suspensas pelo sistema de controle financeiro Iugu.</div>
    </h3>
  </div>
</div>

<?php getAssinaturas(); ?>

</div>