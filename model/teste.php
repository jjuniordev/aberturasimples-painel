<?php 

  include 'funcoes.php';

  // if (!isset($_POST['nome']) || $_POST['nome'] == "") {
  //   $nome = "";
  // } else {
  //   $nome = $_POST['nome'];  
  // }

  $q_id_associado = mysql_query("SELECT id FROM tb_usuarios WHERE unidade = 'Sao Paulo'");
  $id_usuario     = mysql_result($q_id_associado,0);
  
  $q_pegaids = mysql_query("SELECT selected_itens FROM tb_system_aux_id_lead");
  
  
  $rows = mysql_num_rows($q_pegaids);

  for ($i=0; $i < $rows; $i++) { 
    $result = mysql_result($q_pegaids, $i);
    mysql_query("UPDATE tb_leads SET id_associado = ".$id_usuario." WHERE id = ".$result);
    echo $result . "<br>";
  }

 ?>

