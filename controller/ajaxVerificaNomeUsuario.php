<?php 

	include '../model/seguranca.php';

	$login = $_POST['login'];

	$busca = mysql_query("SELECT 
						    count(*)
						FROM
						    tb_usuarios
						WHERE
						    login = '".utf8_decode($login)."'
					    AND
							active = 1");

	$resultado = mysql_result($busca,0);
	echo $resultado;

?>