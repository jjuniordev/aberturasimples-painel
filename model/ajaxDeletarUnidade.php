<?php 
	include 'seguranca.php';
	$valores = $_POST['valores'];
	$ids  = implode(',', $valores);

	mysql_query("UPDATE tb_unidades SET esta_ativo = 0 WHERE id in (".$ids.",0)");
	$retorno = mysql_info();

	$usuario = $_SESSION['usuarioNome'];
	$mensagem = utf8_decode("Usuário: ".$usuario.", deletou as unidades ".$ids);
	salvaLog($mensagem);

	foreach ($valores as $unidade_id) {
		mysql_query("UPDATE tb_leads SET id_unidade = 1, id_status = 1 WHERE id_unidade = $unidade_id");
	}

	echo $retorno;
  

 ?>