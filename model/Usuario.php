<?php

// ----- CARREGA A CLASSE DE CONEXÃO COM O BANCO DE DADOS
//  ----- //
//require('seguranca.php');
require('Conexao.php');

class Usuario {

    // ----- ATRIBUTOS NA NOSSA CLASSE ----- //
    
    Private $nome;
    Private $email;
    Private $senha;

    // ----- FUNÇÃO DE INCLUSÃO DE DADOS ----- //
    
    public function incluir($nome, $sobrenome, $email, $login, $password, $id_level, $active, $unidade, $account_name, $account_id) { 
        
        date_default_timezone_set('America/Sao_Paulo');        
        $data_atual = date('Y-m-d H:i');

        $insert = 'insert into tb_usuarios 
            (nome, sobrenome, email, login, password, id_level, active, data_cadastro, id_unidade, account_name, account_id)
        values
            ("' . utf8_decode($nome) . '","' . utf8_decode($sobrenome) . '","' . $email . '","' . $login . '","' . sha1($password) . '",' . $id_level . ','.$active.', "' . $data_atual . '",' . $unidade . ',"' . $account_name . '","'.$account_id.'")';



        $Acesso = new Acesso();

        $Acesso->Conexao();

        $Acesso->Query($insert);
    }
    
    // ----- FUNÇÃO DE CONSULTA DE DADOS ----- //

    public function consultar($sql) {

        $Acesso = new Acesso();

        $Acesso->Conexao();

        $Acesso->Query($sql);

        $this->Linha = @mysqli_affected_rows($Acesso->result);

        $this->Result = $Acesso->result;
    }

    // ----- FUNÇÃO DE EXCLUSÃO DE DADOS ----- //
    
    public function excluir($id) {

        $delete = 'delete from tb_usuarios where id="' . $id . '"';

        $Acesso = new Acesso();

        $Acesso->Conexao();

        $Acesso->Query($delete);
    }

    // ----- FUNÇÃO DE EDIÇÃO DE DADOS ----- //
    
    public function alterar($nome, $email, $senha, $id) {

        $update = 'update tb_usuarios set nome="' . $nome . '", email="' . $email . '" , senha="' . $senha . '" where id="' . $id . '"';

        $Acesso = new Acesso();

        $Acesso->Conexao();

        $Acesso->Query($update);

        $this->Linha = mysqli_num_rows($Acesso->result);

        $this->Result = $Acesso->result;
    }

}
