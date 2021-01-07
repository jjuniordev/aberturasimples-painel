<?php 
  include '../model/funcoes.php';
  include '../model/seguranca.php';
?>

<?php
 
 mysql_query("TRUNCATE TABLE tb_google_fato_ads");
 if(isset($_POST["Import"])){
		
	$filename=$_FILES["file"]["tmp_name"];		


	 if($_FILES["file"]["size"] > 0)
	 {
	  	$file = fopen($filename, "r");
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
         {
         	$custo = str_replace(",", ".", $getData[5]);
         	$cpc = str_replace(",", ".", $getData[7]);
         	$pos = str_replace(",", ".", $getData[8]);
         	$pos_f = floatval($pos);
         	$sql = "INSERT tb_google_fato_ads SELECT null,'".$getData[1]."','".$getData[2]."','".utf8_decode($getData[0])."','".$cpc."','".number_format(round($pos_f,1),1,".",".")."','".$getData[4]."','0','".$custo."','".$getData[6]."'";
               $result = mysql_query($sql);
               
			if(!isset($result))
			{
				echo "<script type=\"text/javascript\">
						alert(\"Invalid File:Please Upload CSV File.\");
						window.location = \"upload_campanhas.php\"
					  </script>";		
			}
			else {
				  echo "<script type=\"text/javascript\">
					alert(\"CSV File has been successfully Imported.\");
					window.location = \"upload_campanhas.php\"
				</script>";
			}
         }
		
         fclose($file);	
         print_r($getData);
	 }
}	 
 
 
?>