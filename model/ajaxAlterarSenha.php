<?php 
  	
  	include 'seguranca.php';

	$id_user = $_POST['id_user'];
	$novasenha = $_POST['novasenha'];

	mysql_query("UPDATE tb_usuarios SET password = sha1('".$novasenha."') WHERE id = ".$id_user);

	$mensagem = utf8_decode("Usuário: ".$id_user." alterou sua senha");
  	salvaLog($mensagem);
 ?>