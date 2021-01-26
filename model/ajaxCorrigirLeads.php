<?php

    header('Cache-Control: no-cache, must-revalidate'); 
    header('Content-Type: application/json; charset=utf-8');

    include 'seguranca.php';
    include 'funcoes.php';

    $sql = "SELECT
                id_ultima_conversao,
                COUNT(id_ultima_conversao) AS qtd
            FROM tb_leads
            WHERE id_ultima_conversao > 0
            GROUP BY id_ultima_conversao
            HAVING qtd > 1
            ORDER BY 2 DESC";

    $query = mysql_query($sql);

    if (mysql_num_rows($query) == 0) {
        echo json_encode(array("success" => true, "data" => ''));
        return;
    }
    
    while ($result = mysql_fetch_array($query)) {
        $arrUltimaConversao[] = $result;
    }

    foreach ($arrUltimaConversao as $ultimaConversao) {
        $id_ultima_conversao[] = $ultimaConversao['id_ultima_conversao'];
    }

    echo json_encode($id_ultima_conversao);

    // $sql = "SELECT id FROM tb_leads WHERE ";

    // echo json_encode(array("success" => true, "data" => $id_ultima_conversao));



?>