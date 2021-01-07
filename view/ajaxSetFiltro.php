<?php 

	include '../model/seguranca.php';

	$linhas = $_POST['linhas'];
	$colunas = $_POST['colunas'];
	$responsaveis = $_POST['responsaveis'];

	$_SESSION['linhas'] = $linhas;
	$_SESSION['colunas'] = $colunas;
	$_SESSION['responsaveis'] = $responsaveis;

?>