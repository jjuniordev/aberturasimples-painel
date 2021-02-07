<?php 
	include '../model/seguranca.php';
    include '../model/funcoes.php';
	
	$ini = $_POST['ini'];
  	$fim = $_POST['fim'];
    $unidade_id = $_POST['unidade_id'];

  	$query = mysql_query("SELECT DISTINCT
                                a.identificador, COUNT(a.id) as quantidade
                            FROM
                                tb_conversoes a
                            INNER JOIN
                                tb_leads b
                            ON a.id_lead = b.id
                            WHERE str_to_date(a.data_conversao,'%Y-%m-%d') between str_to_date('$ini','%d/%m/%Y') AND str_to_date('$fim','%d/%m/%Y')
                            AND b.id_unidade = $unidade_id
                            GROUP BY a.identificador
                            ORDER BY 2 DESC
							    ;");

  	$tabela = '<div id="dvData"><table id="tabela_unidades" class="ui fixed single line celled selectable center aligned sortable table lista-clientes">';
    $tabela .= '<thead>';
    $tabela .= '<tr><th>Identificador</th>';
    $tabela .= '<th>Quantidade</th></tr>';
    $tabela .= '</thead>';
    while ($dados = mysql_fetch_array($query)) {
        $tabela .= '<tr>';
        $tabela .= '<td title="'.utf8_encode($dados['identificador']).'">'.utf8_encode($dados['identificador']).'</td>';
        $tabela .= '<td>'.$dados['quantidade'].'</td>';
        $tabela .= '</tr>';
    }

    $tabela .= '</table></div>';   
    $tabela .= '<div id="dimmer_campanha" class="ui active centered inline "></div>';
    $tabela .= '<script type="text/javascript" src="../view/js/funcoes.js"></script>
				<script type="text/javascript" src="../view/tablesort.js"></script>';
    
    echo $tabela; 

?>