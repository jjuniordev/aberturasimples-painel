<?php 
// Inclui o arquivo com o sistema de segurança
require_once("../model/seguranca.php");
if (isset($_GET['url'])) {
  $url = $_GET['url'];
}
// Verifica se um formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Salva duas variáveis com o que foi digitado no formulário
  // Detalhe: faz uma verificação com isset() pra saber se o campo foi preenchido
  $usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
  $senha = (isset($_POST['senha'])) ? $_POST['senha'] : '';
  // Utiliza uma função criada no seguranca.php pra validar os dados digitados
  if (validaUsuario($usuario, $senha) == true) {
    // O usuário e a senha digitados foram validados, manda pra página interna
    $mensagem = utf8_decode("Login efetuado com sucesso! [Usuário: " . $usuario . " ]");
    salvaLog($mensagem);
    if (isset($url)) {
      header("Location: ".$url);  
    } else {
      header("Location: ../view/index.php");
    }
    
  } else {
    // O usuário e/ou a senha são inválidos, manda de volta pro form de login
    // Para alterar o endereço da página de login, verifique o arquivo seguranca.php
    $mensagem = utf8_decode("Falha na tentativa de login! [Usuário: " . $usuario . " | Senha: ". $senha ."]");
    salvaLog($mensagem);
    expulsaVisitante('erro');
  }
}

 ?>