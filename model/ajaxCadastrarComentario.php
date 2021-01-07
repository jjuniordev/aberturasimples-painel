<?php 
  include 'funcoes.php';
  include 'seguranca.php';
?>

<?php 
	date_default_timezone_set('America/Sao_Paulo');
    $date = date('d-m-Y H:i');
  $comentario     = utf8_decode($_POST['comentario']);
  $hash           = $_POST['hash'];
  $usuario_id     = $_SESSION['usuarioID'];



  mysql_query("INSERT tb_atividades SELECT null, 3 , '".$comentario."' , '".$hash."' , (select id_ultima_conversao from tb_leads where private_hash = '".$hash."') , '".$date."' , ".$usuario_id);

  $la = atividadesLead($hash);
  echo $la;


  //echo $lista_atividades;

?>