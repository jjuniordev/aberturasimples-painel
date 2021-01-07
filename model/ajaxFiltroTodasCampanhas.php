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
                                replace(a.account_name,'Abertura Simples - ','') as Unidade, 
                                sum(a.cliques) as cliques,
                                sum(a.impressoes) as impressoes,
                                round(sum(a.conversoes),0) as conversoes,
                                round(avg(a.average_cpc),2) as cpc_medio,
                                round(avg(a.average_position),1) as posicao_media,
                                round(sum(a.custo),2) as custo,
                                b.id
                            FROM 
                                tb_google_fato_ads a
                            INNER JOIN
                                tb_unidades b
                            ON
                                replace(a.account_name,'Abertura Simples - ','') = b.nome_unidade
                        WHERE 
                            account_name != ' -- '
                         AND replace(account_name,'Abertura Simples - ','') = '".$nome_unidade."'
                         AND
                            str_to_date(periodo,'%Y-%m-%d') 
                            BETWEEN str_to_date('".$ini."','%d/%m/%Y')
                            AND str_to_date('".$fim."','%d/%m/%Y')
                        GROUP BY a.account_name,b.id
                         ;");
    } else {
        $query = mysql_query("
                                SELECT 
                                    replace(a.account_name,'Abertura Simples - ','') as Unidade, 
                                    sum(a.cliques) as cliques,
                                    sum(a.impressoes) as impressoes,
                                    round(sum(a.conversoes),0) as conversoes,
                                    round(avg(a.average_cpc),2) as cpc_medio,
                                    round(avg(a.average_position),1) as posicao_media,
                                    round(sum(a.custo),2) as custo,
                                    b.id
                                FROM 
                                    tb_google_fato_ads a
                                INNER JOIN
                                    tb_unidades b
                                ON
                                    replace(a.account_name,'Abertura Simples - ','') = b.nome_unidade
                                WHERE 
                                    a.account_name != ' -- '
                                AND
                                    b.esta_ativo = 1
                                AND
                                    str_to_date(periodo,'%Y-%m-%d') 
                                    BETWEEN str_to_date('".$ini."','%d/%m/%Y') AND str_to_date('".$fim."','%d/%m/%Y')
                                GROUP BY
                                    a.account_name,b.id
                                ORDER BY custo DESC;");
    }


  	

  	$detalhes = '<div id="dvData"><table id="tabela_campanhas" class="ui center aligned celled selectable sortable table lista-clientes">';
    $detalhes .= '<thead>';
    $detalhes .= '<th class="four wide">Unidade</th>';
    $detalhes .= '<th>Cliques</th>';
    $detalhes .= '<th>Impressões</th>';
    $detalhes .= '<th>Conversões</th>';
    $detalhes .= '<th>Posição</th>';
    $detalhes .= '<th>CPC Médio</th>';
    $detalhes .= '<th>Custo por Lead</th>';
    $detalhes .= '<th>Custo</th>';
    $detalhes .= '</thead>';
    # Iniciando as variáveis para utilizar no Laço
    $indice         = 0;
    $cliques        = 0;
    $impressoes     = 0;
    $conversoes     = 0;
    $posicao_media  = 0;
    $cpc_medio      = 0;
    $custo          = 0;
    $custoporlead   = 0;

    while ($fatos = mysql_fetch_array($query)) {
        $conversao = getConversoesData($fatos['id'],$ini,$fim);
        if ($conversao == 0) {
            $cpl = 0;
        } else {
            $cpl = ($fatos['custo']/$conversao);
        }

        // if ($fatos['conversoes'] == 0) {
        //     $cpl = 0;
        // } else {
        //     $cpl = ($fatos['custo']/$fatos['conversoes']);
        // }
        $detalhes .= '<tr>';
        $detalhes .= '<td>'.utf8_encode($fatos['Unidade']).'</td>';
        $detalhes .= '<td>'.number_format($fatos['cliques'],0,".",".").'</td>';
        $detalhes .= '<td>'.number_format($fatos['impressoes'],0,".",".").'</td>';
        $detalhes .= '<td>'.number_format($conversao,0,".",".").'</td>';
        $detalhes .= '<td>'.$fatos['posicao_media'].'</td>';
        $detalhes .= '<td>R$ '.number_format($fatos['cpc_medio'],2,",",".").'</td>';
        $detalhes .= '<td>R$ '.number_format($cpl,2,",",".").'</td>';
        $detalhes .= '<td>R$ '.number_format($fatos['custo'],2,",",".").'</td>';
        $detalhes .= '</tr>';
        $indice++;
        $cliques        += $fatos['cliques'];
        $impressoes     += $fatos['impressoes'];
        $conversoes     += $conversao;
        $posicao_media  += $fatos['posicao_media'];
        $cpc_medio      += $fatos['cpc_medio'];
        $custo          += $fatos['custo'];
        $custoporlead   += $cpl;
    }
    if ($posicao_media == 0) {
        $pos_med = 0;
    } else {
        $pos_med = $posicao_media/$indice;
    }

    if ($cpc_medio == 0) {
        $cpc_med = 0;
    } else {
        $cpc_med = $cpc_medio/$indice;
    }

    $detalhes .= '<tfoot>';
    $detalhes .= '<tr>';
    $detalhes .= '<th><b>Total</b></th>';
    $detalhes .= '<th><b>'.number_format($cliques,0,".",".").'</b></th>';
    $detalhes .= '<th><b>'.number_format($impressoes,0,".",".").'</b></th>';
    $detalhes .= '<th><b>'.number_format($conversoes,0,".",".").'</b></th>';
    $detalhes .= '<th><b>'.number_format($pos_med,1,".",".").'</b></th>';
    $detalhes .= '<th><b>R$ '.round($cpc_med).'</b></th>';
    $detalhes .= '<th><b>R$ '.number_format($custoporlead,2,",",".").'</b></th>';
    $detalhes .= '<th><b>R$ '.number_format($custo,2,",",".").'</b></th>';
    $detalhes .= '</tr>';
    $detalhes .= '</table></div>';
    $detalhes .= '<div id="dimmer_campanha" class="ui active centered inline "></div>';

    echo $detalhes;

 ?>