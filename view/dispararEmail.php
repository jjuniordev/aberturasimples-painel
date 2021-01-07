<?php 

	include('menu.php'); 
	require("../class/RDStationAPI.class.php");
	$id_user = $_SESSION['usuarioID'];
	//protegePagina();
	$gatilho_api = $_GET['gatilho_api'];
	$valores = $_GET['ids'];
 ?>
<style type="text/css">
	.sidenav {
		display: none;
	}
	#menu-superior {
		display: none;
	}
</style>



<div class="loader">
  <div class="ui active dimmer">
    <div class="ui text loader">Disparando Email</div>
  </div>
  <p></p>  
</div>

<?php 
	date_default_timezone_set('America/Sao_Paulo');
	$date = date('d-m-Y H:i');

	$query = buscarEmail();
	//$retorno = dispararEmail($email);
	if (mysql_num_rows($query) == 0) {
		echo "0 resultados encontrados";
	} elseif (mysql_num_rows($query) == 1) {
		$email = mysql_result($query, 0,0);
		$id_lead = mysql_result($query, 0,1);
		$retorno = dispararEmail($email,$id_lead);
	} else {
		$emails = array();
		$i = 1;
		while ($dados = mysql_fetch_array($query)) {
			$retorno = dispararEmail($dados['to_email'],$dados['id_lead']);
		}
	}

	if ($retorno) {
		$query = buscarEmail();
		if (mysql_num_rows($query) == 0) {
			//echo "0 resultados encontrados";
		} elseif (mysql_num_rows($query) == 1) {
			$email = mysql_result($query, 0,0);
			$id_lead = mysql_result($query, 0,1);
			mysql_query("INSERT tb_email_success SELECT null,'".$email."','".$date."',".$id_lead);
		} else {
			while ($dados = mysql_fetch_array($query)) {
				mysql_query("INSERT tb_email_success SELECT null,'".$dados['to_email']."','".$date."',".$dados['id_lead']);
			}
		}
		
		mysql_query("TRUNCATE TABLE tb_email_queue");
	}


	if ($gatilho_api == 1) {
		$rdAPI = new RDStationAPI("5c3cf23601aa4c3115678f51e0a9ecde", "98f1ff03c12f683045be8f8cae10801b");

	    $query = mysql_query("SELECT email,nome FROM tb_leads WHERE id in (".$valores.")");

	    while ($dados = mysql_fetch_array($query)) {
	      # SEND NEW LEAD TO RD STATION
	      $return1 = $rdAPI->sendNewLead($dados['email'], array(
	        "name" => utf8_encode($dados['nome']),
	        "identificador" => "fluxo-sao-paulo"
	      ));
	    }
	}


?>

 <script>
 	setTimeout(function(){
    	//$(window).attr('location','pendentes.php');
    	history.back(1);
    },500);
 </script>