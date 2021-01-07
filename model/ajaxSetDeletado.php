<?php 

  include 'seguranca.php';
  include 'funcoes.php';

  $valores = $_POST['valores'];
  $tam_array = count($valores);
  $ids  = implode(',', $valores);
  $usuario_id = $_SESSION['usuarioID'];

  mysql_query("UPDATE tb_leads SET id_status = 7 WHERE id in (".$ids.",0)");

  for ($i=0; $i <= $tam_array; $i++) { 
  	$q_email_lead = mysql_query("SELECT email FROM tb_leads WHERE id = ".$valores[$i]);
    $email_lead = mysql_result($q_email_lead, 0);
    $private = md5($email_lead);

  	adicionarAtividade(2,$_SESSION['usuarioNome']." atualizou o status deste lead para <b>Deletado</b> ",$valores[$i],$_SESSION['usuarioID']);
  }	
  
  $usuario = $_SESSION['usuarioNome'];
  $mensagem = utf8_decode("Usuário: ".$usuario.", atualizou os Leads ".$ids." para Deletados");
  salvaLog($mensagem);

 ?>

