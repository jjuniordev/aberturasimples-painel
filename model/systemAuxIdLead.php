<?php 

  include 'funcoes.php';


  if ($_POST['tipo'] == 'inc') {
  	  if (!isset($_POST['id_lead']) || $_POST['id_lead'] == "") {
	    $id_lead = "";
	  } else {
	    $id_lead = $_POST['id_lead'];  
	  }
  
	$query = mysql_query("INSERT tb_system_aux_id_lead SELECT null,$id_lead");

  } elseif ($_POST['tipo'] == 'del') {
  		if (!isset($_POST['id_lead']) || $_POST['id_lead'] == "") {
	    $id_lead = "";
	  } else {
	    $id_lead = $_POST['id_lead'];  
	  }

	  $query = mysql_query("DELETE FROM tb_system_aux_id_lead WHERE selected_itens = $id_lead");

  } elseif ($_POST['tipo'] == 'tru') {
  		mysql_query("TRUNCATE TABLE tb_system_aux_id_lead");
  }

 ?>

