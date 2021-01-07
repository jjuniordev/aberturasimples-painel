<?php 
	include '../model/seguranca.php';
    include '../model/funcoes.php';
	
	$ini = $_POST['ini'];
  	$fim = $_POST['fim'];

  	$query = mysql_query("SELECT 
							    a.nome_unidade
							    ,count(a.id) as total
							    ,a.cidade
							    ,a.estado
							    ,a.id
							FROM
							    tb_unidades a
							INNER JOIN
								tb_leads b
							ON
								b.id_unidade = a.id
							INNER JOIN 
								tb_conversoes c
							ON
								b.id_ultima_conversao = c.id
							WHERE 
								b.id_status NOT IN (1)
							-- AND
							--	c.origem != 'Manual'
							AND str_to_date(c.data_conversao,'%Y-%m-%d') 
								BETWEEN str_to_date('".$ini."','%d/%m/%Y') and str_to_date('".$fim."','%d/%m/%Y')
							GROUP BY
								a.nome_unidade,a.cidade,a.estado,a.id
							ORDER BY 2 DESC
							    ;");

  	$retorno = "<div id='dvData' class='resultado'><table id='tabela_unidades' class='ui fixed single line celled selectable center aligned sortable compact table lista-clientes'>";
    $retorno .= "<thead>";
    $retorno .= "<th>Nome da Unidade</th>";
    $retorno .= "<th>Cidade da Unidade</th>";
    $retorno .= "<th class='three wide'>Estado da Unidade</th>";
    $retorno .= "<th class='two wide'>Total</th>";
    $retorno .= "<th class='one wide no-sort'></th>";
    $retorno .= "</thead>";
    while ($dados = mysql_fetch_array($query)) {
        $retorno .= "<tr>";
        $retorno .= "<td>".utf8_encode($dados['nome_unidade'])."</td>";
        $retorno .= "<td>".utf8_encode($dados['cidade'])."</td>";
        $retorno .= "<td>".$dados['estado']."</td>";
        $retorno .= "<td>".$dados['total']."</td>";
        $retorno .= "<td title='Clique aqui para visualizar a lista de Leads.'><button id='".$dados['id']."' class='ui tiny basic icon button ver_leads'><i class='eye black icon'></i></button></td>";
        $retorno .= "</tr>";
    }
    $retorno .= "</table></div>";    
    $retorno .= '<div id="dimmer_campanha" class="ui active centered inline "></div>';
    $retorno .= '<script type="text/javascript" src="../view/js/funcoes.js"></script>
					<script type="text/javascript" src="../view/tablesort.js"></script>';
	$retorno .= '<script src="../view/js/exibeModalLeadsFiltrado.js"></script>';
    
    echo $retorno; 

?>