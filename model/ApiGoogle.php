<?php 
// ----- CARREGA A CLASSE DE CONEXÃO COM O BANCO DE DADOS
//  ----- //
require('seguranca.php');
require('Conexao.php');

class ApiGoogle {

	Private $usuario;

	public function getAccountId() {
		
		$usuario = $_SESSION['usuarioID'];

		$Acesso = new Acesso();

		$Acesso->Conexao();

		$query = "SELECT 
                    a.account_id
                FROM
                    tb_google_account a
                        INNER JOIN
                    tb_usuarios b 
                    ON 
                    a.id = b.account_id
                WHERE
                        b.id = " . $usuario ."
                GROUP BY 
                        a.account_id"
                        ;

		$Acesso->Query($query);

		$this->Result = $Acesso->result;
	}

	

    // ----- FUNÇÃO DE CONSULTA DE DADOS ----- //

    public function consultar($sql) {

        $Acesso = new Acesso();

        $Acesso->Conexao();

        $Acesso->Query($sql);

        $this->Linha = @mysqli_affected_rows($Acesso->result);

        $this->Result = $Acesso->result;
    }
}



 ?>