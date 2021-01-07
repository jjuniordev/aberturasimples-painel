<?php 

  include 'seguranca.php';
  include 'funcoes.php';

  $valores = $_POST['valores'];
  $tam_array = count($valores);
  $ids  = implode(',', $valores);
  $usuario_id = $_POST['id'];
  $nomes = getNomeUsuario($usuario_id);

  mysql_query("UPDATE tb_leads SET id_usuario = $usuario_id WHERE id in (".$ids.",0)");
  
  for ($i=0; $i <= $tam_array; $i++) { 

  	$q_email_lead = mysql_query("SELECT email FROM tb_leads WHERE id = ".$valores[$i]);
    $email_lead = mysql_result($q_email_lead, 0);
    $private = md5($email_lead);

  	adicionarAtividade(5,$_SESSION['usuarioNome']." delegou este Lead para o usuário ".utf8_encode($nomes['nome']) . " " . utf8_encode($nomes['sobrenome']),$private,$_SESSION['usuarioID']);
  }

  $usuario = $_SESSION['usuarioNome'];
  $mensagem = utf8_decode("Usuário: ".$usuario.", delegou os Leads ".$ids." para o Usuário: ".$nome);
  salvaLog($mensagem);


 ?>

