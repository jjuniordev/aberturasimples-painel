<?php

require_once("../model/Lead.php");  // ----- CARREGA A CLASSE USUARIO  ----- //

function Processo($Processo) {

    switch ($Processo) { // ----- A PARTIR DESTE PONTO TESTA O PROCESSO PASSADO PELA CAMADA DE VISÃO ----- //

        case 'incluir': // ----- PROCESSO DE INCLUIR PASSADO NA VISÃO INCLUIR USUARIO ----- //

            global $linha; // ----- VARIAVEL GLOBAL LINHA ----- //
            global $rs; // ----- VARIALVEL GLOGAL RS, É NOSSO CONJUNTO DE DADOS OU RESULTADO ----- //           


            $lead = new Lead(); // ----- CRIA O OBJETO DE USUARIO ----- //

            $lead->consultar("select * from tb_leads"); // ----- REALIZA UMA CONSULTA E CARREGA PARA AS VARIAVEIS GLOBAIS ----- //
            $linha = $lead->Linha;
            $rs = $lead->Result;

            if (isset($_POST['ok']) == 'true') {
                //$ac = explode("|", $_POST['account_name']);
                $lead->incluir(utf8_decode($_POST['nome']), $_POST['email'], $_POST['telefone'],$_POST['estado'], utf8_decode($_POST['cidade']), $_POST['identificador'], utf8_decode($_POST['mensagem']));
                echo '<script>alert("Cadastrado com sucesso !");</script>'; // ===== ALERTA JAVA SCRIPT NA TELA DO USUARIO ===== //
                echo '<script>window.location="../view/index.php";</script>'; // ===== REDIRECIONA O USUARIO DEPOIS DE FEITA A OPERAÇÃO DESEJADA ===== //
            }

            break;

        case 'consultar': // ----- PROCESSO DE INCLUIR PASSADO NA VISÃO CONSULTAR USUARIO ----- //

            global $linha; // ----- VARIAVEL GLOBAL LINHA ----- //
            global $rs; // ----- VARIALVEL GLOGAL RS, É NOSSO CONJUNTO DE DADOS OU RESULTADO ----- //

            $usuario = new Usuario(); // ----- CRIA O OBJETO DE USUARIO ----- //
            
            $usuario->consultar("select * from tb_usuarios"); // ----- REALIZA UMA CONSULTA E CARREGA PARA AS VARIAVEIS GLOBAIS ----- //
            $linha = $usuario->Linha;
            $rs = $usuario->Result;

            if ($_GET['ok'] == "excluir") {
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
