<?php 

	include("../model/ApiGoogle.php");

	function getApiData($period) {

		switch ($period) {
			case 'all':
				global $linha;
				global $rs;

				$apig = new ApiGoogle();

				$conta = $apig->getAccountId();
				$apig->consultar("SELECT 
							        a.*
							    FROM
							        tb_google_fato_ads a
							    INNER JOIN
							        tb_google_account b
							    ON
							        a.account_name = b.account_name
							    INNER JOIN
							        tb_usuarios c
							    ON
							        c.account_id = b.id
							    WHERE
							        c.id = $conta
							    ORDER BY 
							        a.periodo desc");

				$rs = $apig->Result;
				break;

			case 'partial':
				# code...
				break;
			
			default:
				# code...
				break;
		}
	}


 ?>