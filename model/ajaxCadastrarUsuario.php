<?php 
    include 'seguranca.php';

    date_default_timezone_set('America/Sao_Paulo');
    $date = date('d-m-Y H:i');

	$nome 		= $_POST['nome'];
	$sobrenome	= $_POST['sobrenome'];
	$email 		= $_POST['email'];
	$login 		= $_POST['login'];
	$senha 		= sha1($_POST['senha']);
	$level 		= $_POST['level'];
	$unidade 	= $_POST['unidade'];

	$sql = mysql_query("INSERT tb_usuarios SELECT null,'".utf8_decode($nome)."','".utf8_decode($sobrenome)."','".$email."','".$login."','".$senha."',".$level.",1,'".$date."',".$unidade.",'',0");

	$retorno = mysql_info();

	echo $retorno;

?>