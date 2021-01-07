<?php 

	include 'seguranca.php';
	$valores = $_POST['valores'];
	$ids  = implode(',', $valores);

	mysql_query("UPDATE tb_usuarios SET active = 0 WHERE id in (".$ids.",0)");

  $usuario = $_SESSION['usuarioNome'];
  $mensagem = utf8_decode("Usuário: ".$usuario.", deletou os usuários ".$ids);
  salvaLog($mensagem);
?>