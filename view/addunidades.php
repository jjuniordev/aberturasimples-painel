<?php 
	include('menu.php'); 
	$id_user = $_SESSION['usuarioID'];
	$permissao = verificarPermissao($id_user);
	
?>

<div class="ui container">
	<h1 class="ui center aligned header"><i class="cogs icon"></i>Gerenciar</h1>

<?php include 'sub_menu_gerenciar.php'; ?>

<?php 
	if ($permissao >= 4) {
		echo "Você não possui privilégios para acessar aqui.";
		//include 'form_adduser_interno.php';
	} else {
		include 'form_addunidades.php';
	}
?>

</div>

