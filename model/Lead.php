<?php

// ----- CARREGA A CLASSE DE CONEXÃO COM O BANCO DE DADOS
//  ----- //
//require('seguranca.php');
require('Conexao.php');

class Lead {

    // ----- ATRIBUTOS NA NOSSA CLASSE ----- //
    
    Private $nome;
    Private $email;
    //Private $senha;

    // ----- FUNÇÃO DE INCLUSÃO DE DADOS ----- //
    
    public function incluir($nome, $email, $telefone, $estado, $cidade, $identificador, $mensagem) { 
        
        date_default_timezone_set('America/Sao_Paulo');        
        $data_atual = date('Y-m-d H:i');
        $chave = $nome.$email.$cidade.$estado;
        $private = md5($email);

        $insert = 'insert into tb_leads 
            (data, nome, email, telefone, cidade, estado, mensagem, origem, identificador, id_unidade, id_status, esta_ativo, chave, private_hash)
        values
            ("' . $data_atual . '","' . $nome . '","' . $email . '","' . $telefone . '","' . $cidade . '","' . $estado . '","'. $mensagem .'","Manual", "' . $identificador . '", 1, 1, 1,"'.$chave.'","teste")';

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
