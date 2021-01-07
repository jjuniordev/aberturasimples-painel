<?php

// ----- CLASSE QUE IRÁ REALIZAR A CONEXÃO COM O BANCO DE DADOS ----- //

class Acesso {
    
    // ----- FUNÇÃOQUE VAI ABRIR A CONEXÃO COM O BANCO ----- //
    
    public function Conexao() {

        $this->cnx = mysqli_connect("localhost", "admin", "@Admin123", "integrations");

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }

    // ----- REALIZA A QUERY NO BANCO ----- //
    
    public function Query($sql) {
        $this->result = mysqli_query($this->cnx,$sql, MYSQLI_STORE_RESULT);
    }

    // ----- FECHA A CONEXÃO COM O BANCO DE DADOS ----- //
    
    public function __destruct() {
        mysqli_close($this->cnx);
    }

}
?> 

