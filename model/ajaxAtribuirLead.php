<?php 

  include 'seguranca.php';
  include 'funcoes.php';
  date_default_timezone_set('America/Sao_Paulo');
  $date = date('d-m-Y');

  if (!isset($_POST['nome']) || $_POST['nome'] == "") {
    $nome = "";
  } else {
    $nome = utf8_decode($_POST['nome']);  
    //$nome = $_POST['nome'];  
  }
  $valores = $_POST['valores'];
  $tam_array = count($valores);
  $ids  = implode(',', $valores);

  $q_id_unidade = mysql_query("SELECT id FROM tb_unidades WHERE nome_unidade = '".$nome."' AND esta_ativo = 1");
  $id_unidade     = mysql_result($q_id_unidade,0);

  if ($id_unidade == 1) {
    $id_status = 1;
  } else {
    $id_status = 2;
  }

  mysql_query("UPDATE tb_leads SET id_unidade = ".$id_unidade." , id_status = ".$id_status." WHERE id in (".$ids.",0)");

  for ($i=0; $i <= $tam_array; $i++) { 
    
    $q_email_usr = mysql_query("select distinct email b from tb_usuarios a inner join tb_unidades b on a.id_unidade = b.id where b.id = ".$id_unidade." AND a.id_level != 5 AND a.active = 1");
    $email_usr = mysql_result($q_email_usr, 0);

    $q_email_lead = mysql_query("SELECT email FROM tb_leads WHERE id = ".$valores[$i]);
    $email_lead = mysql_result($q_email_lead, 0);

    mysql_query("INSERT tb_email_queue SELECT null,'".$email_usr."','".$date."',".$valores[$i]);  
    $private = md5($email_lead);
    adicionarAtividade(4,$_SESSION['usuarioNome']." enviou o lead para a unidade ".$nome,$private,$_SESSION['usuarioID']);
  }
  
  if ($id_unidade == 2) {
    
    $retorno = 1;

  } else {
    $retorno = 0;
  }
  
  $usuario = $_SESSION['usuarioNome'];
  $mensagem = utf8_decode("Usuário: ".$usuario.", atribuiu os Leads ".$ids." para a Unidade: ".$nome);
  salvaLog($mensagem);
  echo $retorno;

 ?>

