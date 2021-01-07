<?php 
  include '../model/funcoes.php';
  include '../model/seguranca.php';
?>

<?php 
	$email = $_POST['email_cad'];
	$unidade_id = $_SESSION['usuarioUnidade'];
	
	$query = mysql_query("SELECT email FROM tb_leads WHERE email = '".$email."'");

	$row = mysql_num_rows($query);

	if ($row == 1) {
		$res = mysql_result($query, 0);
		$hash = md5($res);
		$dono = donoDoLead($hash);
		if ($dono == $_SESSION['usuarioUnidade']) {
			$retorno = "Cadastro realizado com Sucesso!
			\n Atenção: Este Lead já existe em seu Painel.\n Você pode encontrar o Lead através do campo 'Buscar email' no Menu.";
		} else {
			$retorno = "Não foi possível cadastrar este Lead.\nDetalhes do erro: E-mail duplicado não atribuido a esta unidade.";
		}		
	} else {
		$retorno = "Cadastro realizado com Sucesso!";
	}

	echo $retorno;
?>