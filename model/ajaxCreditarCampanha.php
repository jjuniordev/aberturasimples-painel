<?php 
  include '../model/funcoes.php';
  include '../model/seguranca.php';
?>

<?php 
	
	//Recuperar o valor da palavra
	$google_id 	= $_POST['google_id'];
	$credito 	= $_POST['credito'];

	function getCustoCampanha($google_id) {
	    $query = mysql_query("select sum(custo) from tb_google_fato_ads where account_id = '".$google_id."'");

	    $custo = mysql_result($query, 0);

	    if ($custo == '' || is_null($custo)) {
	        $custo = 0;
	      }  

	    return $custo;
	  }

  	function temCampanhaAtiva($google_id) {
  		$query = mysql_query("SELECT id FROM tb_campanhas WHERE google_id = '".$google_id."' AND status = 1");

  		$result = mysql_num_rows($query);

  		return $result;
  	}

  	function getCredito($google_id) {
  		$query = mysql_query("SELECT credito FROM tb_campanhas WHERE google_id = '".$google_id."'");
  		$retorno = mysql_result($query, 0);

  		return $retorno;
  	}

  	function getValorFinal($google_id) {
  		$query = mysql_query("SELECT valor_final FROM tb_campanhas WHERE google_id = '".$google_id."'");
  		$retorno = mysql_result($query, 0);

  		return $retorno;
  	}

	$custo = getCustoCampanha($google_id);
	$valor_final = $custo + $credito;


	function insereSaldo($google_id,$credito,$valor_final) {
		
		$tem_campanha = temCampanhaAtiva($google_id);

		if ($tem_campanha == 0) {
			$query = mysql_query("INSERT tb_campanhas SELECT null,'".$google_id."',".$credito.",".$valor_final.",1");

			$erro = mysql_error();
			

		} else {
			$credito_atual = getCredito($google_id);
			$valor_final_atual = getValorFinal($google_id);

			$cre 	= $credito 		+ $credito_atual;
			$vf 	= $credito 	+ $valor_final_atual;

			$query = mysql_query("UPDATE tb_campanhas SET credito = ".$cre." , valor_final = ".$vf." WHERE google_id = '".$google_id."'");

			$erro = mysql_error();

		}
		return $erro;

	}

	$result = insereSaldo($google_id,$credito,$valor_final);

	echo $result;

?>