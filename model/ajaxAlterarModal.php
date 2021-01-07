<?php 
  include 'seguranca.php';
  include 'funcoes.php';
?>

<?php 
  $id         = $_POST['id'];
  $nome       = isset($_POST['nome']) ? utf8_decode($_POST['nome']) : "empty"; 
  $nome_old   = isset($_POST['nome_old']) ? utf8_decode($_POST['nome_old']) : "empty"; 
  $tel        = isset($_POST['tel']) ? utf8_decode($_POST['tel']) : "empty"; 
  $tel_old    = isset($_POST['tel_old']) ? utf8_decode($_POST['tel_old']) : "empty"; 
  $mensagem   = isset($_POST['msg']) ? utf8_decode($_POST['msg']) : "empty"; 
  $mensagem_old = isset($_POST['msg_old']) ? utf8_decode($_POST['msg_old']) : "empty"; 
  $estado     = isset($_POST['estado']) ? utf8_decode($_POST['estado']) : "empty";
  $estado_old = isset($_POST['estado_old']) ? utf8_decode($_POST['estado_old']) : "empty";
  $cidade     = isset($_POST['cidade']) ? utf8_decode($_POST['cidade']) : "empty"; 
  $cidade_old = isset($_POST['cidade_old']) ? utf8_decode($_POST['cidade_old']) : "empty"; 
  $unidade    = isset($_POST['id_unidade']) ? $_POST['id_unidade'] : 0; 
  $unidade_old = isset($_POST['id_unidade_old']) ? utf8_decode($_POST['id_unidade_old']) : "empty";
  $unidade_n = isset($_POST['unidade_nome']) ? utf8_decode($_POST['unidade_nome']) : "empty";


  if ($nome != "empty") {
     mysql_query("UPDATE tb_leads SET nome = '".$nome."' WHERE id = ".$id);
     
     $alteracao = "Nome alterado <b>De:</b> <i>".$nome_old."</i> <b>, Para: </b><i>".$nome."</i>";

     adicionarAtividade(2,utf8_encode($alteracao),$id,$_SESSION['usuarioID']);

   } 

   if ($tel != "empty") {
     mysql_query("UPDATE tb_leads SET telefone = '".$tel."' WHERE id = ".$id);
     
     $alteracao = "Telefone alterado <b>De:</b> <i>".$tel_old."</i> <b>, Para: </b><i>".$tel."</i>";

     adicionarAtividade(2,utf8_encode($alteracao),$id,$_SESSION['usuarioID']);

   } 

   if ($mensagem != "empty") {
     mysql_query("UPDATE tb_conversoes SET mensagem = '".$mensagem."' WHERE id = (SELECT id_ultima_conversao FROM tb_leads WHERE id = ".$id.")");
     
     $alteracao = "Mensagem alterada <b>De:</b> <i>".$mensagem_old."</i> <b>, Para: </b><i>".$mensagem."</i>";

     adicionarAtividade(2,utf8_encode($alteracao),$id,$_SESSION['usuarioID']);

   } 

   if ($estado != "empty") {
     mysql_query("UPDATE tb_leads SET estado = '".$estado."' WHERE id = ".$id);
     
     $alteracao = "Estado alterado <b>De:</b> <i>".$estado_old."</i> <b>, Para: </b><i>".$estado."</i>";

     adicionarAtividade(2,utf8_encode($alteracao),$id,$_SESSION['usuarioID']);

   } 

   if ($cidade != "empty") {
     mysql_query("UPDATE tb_leads SET cidade = '".$cidade."' WHERE id = ".$id);
     
     $alteracao = "Cidade alterada <b>De:</b> <i>".$cidade_old."</i> <b>, Para: </b><i>".$cidade."</i>";

     adicionarAtividade(2,utf8_encode($alteracao),$id,$_SESSION['usuarioID']);

   } 

   if ($unidade != 0) {
     mysql_query("UPDATE tb_leads SET id_unidade = '".$unidade."' WHERE id = ".$id);
     
     $alteracao = "Unidade alterada <b>De:</b> <i>".$unidade_old."</i> <b>, Para: </b><i>".$unidade_n."</i>";

     adicionarAtividade(2,utf8_encode($alteracao),$id,$_SESSION['usuarioID']);

   } 

  $mensagem = utf8_decode("Lead: ".$id." , foi alterado pelo usuário: ".$_SESSION['usuarioNome']);
  salvaLog($mensagem);

  //adicionarAtividade(2,"Nova alteração de Lead!",$id,$_SESSION['usuarioID']);
?>