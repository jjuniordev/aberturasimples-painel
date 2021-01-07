<?php 

  include 'seguranca.php';

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

  mysql_query("UPDATE tb_unidades SET " . $campo . " = '" . utf8_decode($valor) . "' WHERE id = " . $id);
 
  $mensagem = utf8_decode("Unidade: ".$id." , foi alterada pelo usuário: ".$_SESSION['usuarioNome']);
  salvaLog($mensagem);
  
 ?>

