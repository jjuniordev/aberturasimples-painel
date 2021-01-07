<?php 
	include '../model/seguranca.php';
    include '../model/funcoes.php';
	
	$ini = $_POST['ini'];
  	$fim = $_POST['fim'];
    $id_user = $_SESSION['usuarioID'];
    $permissao  = verificarPermissao($id_user); 
    $nome_unidade = getNomeUnidade($id_user);

    if ($permissao >= 4) {
        $unidade_nome = $_POST['uni'];
        $query = mysql_query("SELECT 
                                replace(a.nome_campanha,'Leads ','') as Campanha, 
					            sum(a.cliques) as cliques,
					            sum(a.impressoes) as impressoes,
					            round(sum(a.conversoes),0) as conversoes,
					            round(avg(a.cpc),2) as cpc_medio,
					            round(sum(a.custo),2) as custo
                            FROM 
                                tb_facebook_fato_ads a
                        WHERE 
                            nome_campanha != ' -- '
                         AND
                            str_to_date(periodo,'%Y-%m-%d') 
                            BETWEEN str_to_date('".$ini."','%d/%m/%Y')
                            AND str_to_date('".$fim."','%d/%m/%Y')
                        GROUP BY a.nome_campanha
                         ;");
    } else {
        $query = mysql_query("
                            SELECT 
                                replace(a.nome_campanha,'Leads ','') as Campanha, 
					            sum(a.cliques) as cliques,
					            sum(a.impressoes) as impressoes,
					            round(sum(a.conversoes),0) as conversoes,
					            round(avg(a.cpc),2) as cpc_medio,
					            round(sum(a.custo),2) as custo
                            FROM 
                                tb_facebook_fato_ads a
                        WHERE 
                            nome_campanha != ' -- '
                         AND
                            str_to_date(periodo,'%Y-%m-%d') 
                            BETWEEN str_to_date('".$ini."','%d/%m/%Y')
                            AND str_to_date('".$fim."','%d/%m/%Y')
                        GROUP BY a.nome_campanha
                        ORDER BY cliques DESC
                         ;");
    }


  	

  	$detalhes = '<div id="dvData"><table id="tabela_campanhas" class="ui center aligned celled selectable sortable table lista-clientes">';
    $detalhes .= '<thead>';
    $detalhes .= '<th class="four wide">Unidade</th>';
    $detalhes .= '<th>Cliques</th>';
    $detalhes .= '<th>Impressões</th>';
    $detalhes .= '<th>Conversões</th>';
    $detalhes .= '<th>CPC Médio</th>';
    $detalhes .= '<th>Custo</th>';
    $detalhes .= '</thead>';
    # Iniciando as variáveis para utilizar no Laço
    $tot_cliques = 0;
    $tot_impressoes = 0;
    $tot_conversoes = 0;
    $avg_cpc = 0;
    $tot_custo = 0;
    $i = 0;

    while ($fatos = mysql_fetch_array($query)) {

        $detalhes .= '<tr>';
        $detalhes .= '<td>'.utf8_encode($fatos['Campanha']).'</td>';
        $detalhes .= '<td>'.number_format($fatos['cliques'],0,".",".").'</td>';
        $detalhes .= '<td>'.number_format($fatos['impressoes'],0,".",".").'</td>';
        $detalhes .= '<td>'.number_format($fatos['conversoes'],0,".",".").'</td>';
        $detalhes .= '<td>R$ '.number_format($fatos['cpc_medio'],2,",",".").'</td>';
        // $detalhes .= '<td>R$ '.number_format($cpl,2,",",".").'</td>';
        $detalhes .= '<td>R$ '.number_format($fatos['custo'],2,",",".").'</td>';
        $detalhes .= '</tr>';
        $tot_cliques += $fatos['cliques'];
        $tot_impressoes += $fatos['impressoes'];
        $tot_conversoes += $fatos['conversoes'];
        $avg_cpc += $fatos['cpc_medio'];
        $i ++;
        $tot_custo += $fatos['custo'];
    }

    if ($avg_cpc != 0) {
        $average_cpc = $avg_cpc/$i;
    } else {
        $average_cpc = 0;
    }

    $detalhes .= '<tfoot>';
    $detalhes .= '<tr>';
    $detalhes .= '<th><b>Total</b></th>';
    $detalhes .= '<th><b>'.number_format($tot_cliques,0,".",".").'</b></th>';
    $detalhes .= '<th><b>'.number_format($tot_impressoes,0,".",".").'</b></th>';
    $detalhes .= '<th><b>'.number_format($tot_conversoes,0,".",".").'</b></th>';
    $detalhes .= '<th><b>R$ '.number_format($average_cpc,2,".",".").'</b></th>';
    $detalhes .= '<th><b>R$ '.number_format($tot_custo,2,",",".").'</b></th>';
    $detalhes .= '</tr>';
    $detalhes .= '</table></div>';
    $detalhes .= '<div id="dimmer_campanha" class="ui active centered inline "></div>';

    echo $detalhes;

 ?>