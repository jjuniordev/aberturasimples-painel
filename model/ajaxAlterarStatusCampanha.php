<?php
    include 'seguranca.php';

    $ids = implode(',', $_POST['ids']);
    $ativar = $_POST['ativar'];

    if ($ativar == 'true') {
        $status = 1;
    } else {
        $status = 0;
    }

    mysql_query("UPDATE tb_unidades SET google_id = $status WHERE id IN ($ids , 0)");
    $retorno = mysql_info();

    echo $retorno;

?>