<?php 
  include 'funcoes.php';
  include 'seguranca.php';
?>

<?php 

  $id     = $_POST['id'];
  $hash   = $_POST['hash'];

  mysql_query("DELETE FROM tb_atividades WHERE id = $id");

  $la = atividadesLead($hash);
  echo $la;

?>