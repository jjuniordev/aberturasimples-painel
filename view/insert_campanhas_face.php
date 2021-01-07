<?php 
  include '../model/funcoes.php';
  include '../model/seguranca.php';
?>

<?php
 
 mysql_query("TRUNCATE TABLE tb_facebook_fato_ads");
 if(isset($_POST["Import"])){
		
	$filename = $_FILES["file"]["tmp_name"];		


	 if($_FILES["file"]["size"] > 0)
	 {
	  	$file = fopen($filename, "r");
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
         {
         	$custo = str_replace(",", ".", $getData[9]);
         	$cpc = $getData[6];
         	//$pos = str_replace(",", ".", $getData[8]);
         	//$pos_f = floatval($pos);
         	$sql = "INSERT tb_facebook_fato_ads SELECT null,'".$getData[2]."','".$getData[0]."','".utf8_decode($getData[1])."','".$cpc."','".$getData[3]."','".$getData[5]."','".$custo."','".$getData[4]."'";
           $result = mysql_query($sql);
               
			if(!isset($result))
			{
				echo "<script type=\"text/javascript\">
						alert(\"Invalid File:Please Upload CSV File.\");
						window.location = \"upload_campanhas_face.php\"
					  </script>";		
			}
			else {
				  echo "<script type=\"text/javascript\">
					alert(\"CSV File has been successfully Imported.\");
					window.location = \"upload_campanhas_face.php\"
				</script>";
			}
         }
		
         fclose($file);	
         mysql_query("DELETE FROM tb_facebook_fato_ads WHERE periodo = 'All' AND id != 0");
         //print_r($getData);
	 }
}	 
 
 
?>