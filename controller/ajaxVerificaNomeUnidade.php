<?php 

	include '../model/seguranca.php';

	$nome = $_POST['nome'];

	$busca = mysql_query("SELECT 
						    count(*)
						FROM
						    tb_unidades
						WHERE
						    nome_unidade = '".utf8_decode($nome)."'
					    AND
							esta_ativo = 1");

	$resultado = mysql_result($busca,0);
	echo $resultado;

?>