<?php 
	include 'seguranca.php';
	$valores = $_POST['valores'];
	$ids  = implode(',', $valores);

	mysql_query("UPDATE tb_unidades SET esta_ativo = 0 WHERE id in (".$ids.",0)");

  $usuario = $_SESSION['usuarioNome'];
  $mensagem = utf8_decode("Usuário: ".$usuario.", deletou as unidades ".$ids);
  salvaLog($mensagem);

 ?>