<?php 

	include '../model/seguranca.php';

	$nome = $_POST['nome'];
	$cidade = $_POST['cidade'];
	$estado = $_POST['estado'];
	// $google_id = $_POST['google_id'];

	mysql_query("INSERT tb_unidades SELECT null,'".utf8_decode($nome)."','".utf8_decode($cidade)."','".utf8_decode($estado)."','',1,0");

?>