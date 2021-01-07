<?php 

  include 'seguranca.php';
  include 'funcoes.php';

  if (!isset($_POST['campo']) || $_POST['campo'] == "") {
    $campo = "";
  } else {
    $campo = $_POST['campo'];  
  }

  if (!isset($_POST['valor']) || $_POST['valor'] == "") {
    $valor = "";
  } else {
    $valor = $_POST['valor'];  
  }

  if (!isset($_POST['id']) || $_POST['id'] == "") {
    $id = "";
  } else {
    $id = $_POST['id'];  
  }

  if (!isset($_POST['valor_old']) || $_POST['valor_old'] == "") {
    $valor_old = "";
  } else {
    $valor_old = $_POST['valor_old'];  
  }

  mysql_query("UPDATE tb_leads SET " . $campo . " = '" . utf8_decode($valor) . "' WHERE id = " . $id);

  $usuario = $_SESSION['usuarioNome'];
  $mensagem = utf8_decode("Usuário: ".$usuario.", alterou o Lead ".$id." de: ".$valor_old." | para: ".$valor);
  salvaLog($mensagem);
 
  adicionarAtividade(1,"Alteração de Lead De: ".$valor_old." Para: ".$valor , $id, $_SESSION['usuarioID']);
 ?>

