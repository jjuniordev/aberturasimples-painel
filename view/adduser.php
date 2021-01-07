<?php 
	include('menu.php'); 
	$id_user = $_SESSION['usuarioID'];
	$unidade_id = $_SESSION['usuarioUnidade'];
	
?>

<div class="ui container">
	<h1 class="ui center aligned header"><i class="cogs icon"></i>Gerenciar</h1>
	<!-- <div class="ui divider"></div> -->
<?php
	//require_once('../controller/ControleUsuario.php'); // ----- CARREGA O CONTROLE ----- //
	//Processo('incluir'); // ----- PASSA O PROCESSO AO CONTROLE ----- //
	$permissao = verificarPermissao($id_user);
?>

<script src="js/Validacaoform.js"></script>

<?php include 'sub_menu_gerenciar.php'; ?>

<?php 
	if ($permissao <= 3) {
		include 'form_adduser_interno.php';
	} elseif ($permissao == 4) {
		include 'form_adduser_externo.php';
	} else {
		echo "Você não possui privilégios para acessar aqui.";
	}
?>

</div>

