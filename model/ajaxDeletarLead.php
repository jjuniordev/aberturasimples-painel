<?php 

  include 'seguranca.php';

  if ($_POST['ativar'] != 1) {
  	$valores = $_POST['valores'];
	$ids  = implode(',', $valores);

	mysql_query("UPDATE tb_leads SET esta_ativo = 0, id_status = 3 WHERE id in (".$ids.",0)");

	$usuario = $_SESSION['usuarioNome'];
	$mensagem = utf8_decode("Usuário: ".$usuario.", deletou os Leads ".$ids);
	salvaLog($mensagem);
  } else {
  	$valores = $_POST['valores'];
	$ids  = implode(',', $valores);

	mysql_query("UPDATE tb_leads SET esta_ativo = 1, id_status = 1 WHERE id in (".$ids.",0)");

	$usuario = $_SESSION['usuarioNome'];
	$mensagem = utf8_decode("Usuário: ".$usuario.", ativou os Leads ".$ids);
	salvaLog($mensagem);
  }

  

 ?>

