<?php

    header('Cache-Control: no-cache, must-revalidate'); 
    header('Content-Type: application/json; charset=utf-8');

    include 'seguranca.php';
    include 'funcoes.php';

    $sql = "SELECT 
                id, 
                count(id) AS somaId
            FROM 
                tb_leads 
            WHERE id_ultima_conversao = 0 
            GROUP BY id";

    $query = mysql_query($sql);

    if (mysql_num_rows($query) == 0) 
    {
        echo json_encode(
            array(
                "success" => true, 
                "data" => ''
            )
        );
        return;
    }

    $arrLeadSemConversao = '';
    
    while ($result = mysql_fetch_array($query)) {
        $arrLeadSemConversao .= $result['id'];
        $arrLeadSemConversao .= ',';
    }

    $arrLeadSemConversao .= '0';

    mysql_query("DELETE FROM tb_leads WHERE id IN ($arrLeadSemConversao)");

    $return = array(
        'success' => true
    );


    echo json_encode($return);

    

?>