<?php

require_once("../model/Usuario.php");  // ----- CARREGA A CLASSE USUARIO  ----- //
//include('../view/menu.php'); 
$id_user = $_SESSION['usuarioID'];

function getUnidade() {
    $query = mysql_query("SELECT id_unidade FROM tb_usuarios WHERE id = ".$_SESSION['usuarioID']);
    $unidade_id = mysql_result($query, 0);
    return $unidade_id;
}

function Processo($Processo) {

    switch ($Processo) { // ----- A PARTIR DESTE PONTO TESTA O PROCESSO PASSADO PELA CAMADA DE VISÃO ----- //

        case 'incluir': // ----- PROCESSO DE INCLUIR PASSADO NA VISÃO INCLUIR USUARIO ----- //

            global $linha; // ----- VARIAVEL GLOBAL LINHA ----- //
            global $rs; // ----- VARIALVEL GLOGAL RS, É NOSSO CONJUNTO DE DADOS OU RESULTADO ----- //           


            $usuario = new Usuario(); // ----- CRIA O OBJETO DE USUARIO ----- //

            $usuario->consultar("select * from tb_usuarios"); // ----- REALIZA UMA CONSULTA E CARREGA PARA AS VARIAVEIS GLOBAIS ----- //
            $linha = $usuario->Linha;
            $rs = $usuario->Result;

            if (isset($_POST['ok']) == 'true') {
                if (isset($_POST['unidade'])) {
                    $unidade = $_POST['unidade'];
                } elseif (isset($_POST['unidade2'])) {
                    $unidade = $_POST['unidade2'];
                } else {
                    $unidade = getUnidade();
                }
                //$ac = explode("|", $_POST['account_name']);
                $usuario->incluir($_POST['nome'], $_POST['sobrenome'], $_POST['email'],$_POST['login'], $_POST['senha'], $_POST['id_level'], 1, $unidade, '',0);
                echo '<script>alert("Cadastrado com sucesso !");</script>'; // ===== ALERTA JAVA SCRIPT NA TELA DO USUARIO ===== //
                echo '<script>window.location="../view/consultarusuario.php";</script>'; // ===== REDIRECIONA O USUARIO DEPOIS DE FEITA A OPERAÇÃO DESEJADA ===== //
                
            }

            break;

        case 'consultar': // ----- PROCESSO DE INCLUIR PASSADO NA VISÃO CONSULTAR USUARIO ----- //

            global $linha; // ----- VARIAVEL GLOBAL LINHA ----- //
            global $rs; // ----- VARIALVEL GLOGAL RS, É NOSSO CONJUNTO DE DADOS OU RESULTADO ----- //
            $id_user = $_SESSION['usuarioID'];
            $permissao = verificarPermissao($id_user);
            $unidade = getUnidade();

            $usuario = new Usuario(); // ----- CRIA O OBJETO DE USUARIO ----- //
            if ($permissao == 4) {
                $usuario->consultar("SELECT 
                                    a.*, b.nome_unidade, c.level
                                FROM
                                    tb_usuarios a
                                INNER JOIN
                                    tb_unidades b ON a.id_unidade = b.id
                                INNER JOIN
                                    tb_level c
                                ON
                                    c.id = a.id_level
                                WHERE
                                    a.id_level >= $permissao
                                AND
                                    a.id_unidade = $unidade
                                ORDER BY b.nome_unidade ASC"); // ----- REALIZA UMA CONSULTA E CARREGA PARA AS VARIAVEIS GLOBAIS ----- //
            } else {
                $usuario->consultar("SELECT 
                                    a.*, b.nome_unidade, c.level
                                FROM
                                    tb_usuarios a
                                INNER JOIN
                                    tb_unidades b ON a.id_unidade = b.id
                                INNER JOIN
                                    tb_level c
                                ON
                                    c.id = a.id_level
                                WHERE
                                    a.id_level >= $permissao
                                ORDER BY b.nome_unidade ASC"); // ----- REALIZA UMA CONSULTA E CARREGA PARA AS VARIAVEIS GLOBAIS ----- //
            }
            
            $linha = $usuario->Linha;
            $rs = $usuario->Result;

            if (isset($_GET['ok']) == "excluir") {
                $usuario->excluir($_GET['id']);
                echo '<script>alert("Excluido com sucesso !");</script>'; // ===== ALERTA JAVA SCRIPT NA TELA DO USUARIO ===== //
                echo '<script>window.location="consultarusuario.php";</script>'; // ===== REDIRECIONA O USUARIO DEPOIS DE FEITA A OPERAÇÃO DESEJADA ===== //
            }

            break;


        case 'editar':

            global $linha; // ----- VARIAVEL GLOBAL LINHA ----- //
            global $rs; // ----- VARIALVEL GLOGAL RS, É NOSSO CONJUNTO DE DADOS OU RESULTADO ----- //

            $usuario = new Usuario(); // ----- CRIA O OBJETO DE USUARIO ----- //

            $usuario->consultar("select * from tb_usuarios where id=" . $_GET['id']); // ----- REALIZA UMA CONSULTA E CARREGA PARA AS VARIAVEIS GLOBAIS NESTE CASO UMA CONSULTA ESPECIFICA PARA O ID DE USUARIO VEJA O WHERE----- //
            $linha = $usuario->Linha;
            $rs = $usuario->Result;

            if ($_POST['ok'] == "true") {
                $usuario->alterar($_POST['nome'], $_POST['email'], $_POST['senha'], $_GET['id']);
                echo '<script>alert("Alterado com sucesso !");</script>'; // ===== ALERTA JAVA SCRIPT NA TELA DO USUARIO ===== //
                echo '<script>window.location="consultarusuario.php";</script>'; // ===== REDIRECIONA O USUARIO DEPOIS DE FEITA A OPERAÇÃO DESEJADA ===== //
            }

            break;
    }
}
