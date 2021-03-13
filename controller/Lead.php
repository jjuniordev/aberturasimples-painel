<?php

require_once("../model/seguranca.php");

function verificarLeadDuplicado() {
    $sqlBuscarEmails = mysql_query("SELECT
                            email
                        FROM
                            tb_leads
                        GROUP BY
                            email
                        HAVING COUNT(email) > 1");

    if (mysql_num_rows($sqlBuscarEmails) == 0) {
        return false;
    }
    
    while ($retorno = mysql_fetch_array($sqlBuscarEmails)) {
        $emailsDuplicados[] = $retorno['email'];
    }

    return $emailsDuplicados;
}

function ajustarBaseLeadDuplicado($leadsDuplicados) {
    
    foreach ($leadsDuplicados as $email) {
        $sqlBuscarIdsPorEmail = mysql_query("SELECT id FROM tb_leads WHERE email = '$email' ORDER BY id ASC");

        while ($retorno = mysql_fetch_array($sqlBuscarIdsPorEmail)) {
            $ids[] = $retorno['id'];
        }
        array_shift($ids);
        $idsDeletar = $ids;
    }

    $idsDeletar = implode(",", $idsDeletar);

    mysql_query("DELETE FROM tb_leads WHERE id IN ($idsDeletar)");

    return true;
}



?>