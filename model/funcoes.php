<?php

# Inclui o arquivo com o sistema de segurança
//include("seguranca.php");
//protegePagina(); // Chama a função que protege a página

function buscarClientId($id) {
    # Query que busca o client_id de um ID específico ou logado no sistema.
    $query = ("SELECT 
                trim(a.google_id)
            FROM
                tb_unidades a
            INNER JOIN
                tb_usuarios b 
            ON 
                a.id = b.id_unidade
            WHERE
                b.id = $id
            GROUP BY 
                a.google_id")
                    ;
    
    $exec   = mysql_query($query);

    if (mysql_num_rows($exec) == 0) {
        $res = 0;
    } else {
        $res    = mysql_result($exec,0);    
    }
    
    
    return $res;
}

function alterarClientId($customer_id) {
    
    $ini_file_array = parse_ini_file( 'adsapi_php.ini' );  # Pega as informações do .ini e joga em um Array. 
    $ini_file = 'adsapi_php.ini'; # Armazena o caminho do arquivo na variável.
    
    $obter  = file_get_contents($ini_file); # Armazen o conteúdo do arquivo.
    $novo   = str_replace($ini_file_array['clientCustomerId'], $customer_id, $obter); # Substitui o client_id antigo para o novo.
    $gravar = fopen($ini_file, "w"); # Inicia a gravação do arquivo.
    fwrite($gravar, $novo); # Escreve o novo cliente_id no arquivo .ini.
    fclose($gravar); # Fecha edição do arquivo.
    
    
}


function todosClientIds() {
    
    # buscar todos os client_ids cadastrados no banco de dados.
    //$query  = mysql_query("select distinct account_id from tb_google_account");
    $query  = mysql_query("select trim(google_id) from tb_unidades where google_id != ''");
    //$res    = mysql_fetch_array($query);
    
    return $query;
}

function carregaCsvFatoCampanhas($arquivocsv) {
    
    # Função que faz upload do conteúdo de um arquivo CSV para o banco de dados;
    $data_file = sys_get_temp_dir() . "/" . $arquivocsv;
    $row = 1;
    if (($handle = fopen($data_file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            $row++;
            for ($c=0; $c < $num; $c++) {
                # Pular linhas, a primeira linha começa em 2.
                if ($row > 3) {
                    $day            = utf8_encode($data[0]);
                    $campanhaID     = utf8_encode($data[1]);
                    $campanha       = utf8_decode($data[2]);
                    $avr_cpc        = utf8_encode($data[3]);
                    $avr_pos        = utf8_encode($data[4]);
                    $cliques        = utf8_encode($data[5]);
                    $conv           = utf8_encode($data[6]);
                    $custo          = utf8_encode($data[7]);
                    $impressoes     = utf8_encode($data[8]);
                } 
            }
            # Pular linhas, a primeira linha começa em 2.
            if ($row > 3) {
                $result = mysql_query("INSERT INTO tb_google_fato_ads ("
                    . "periodo,account_id,account_name,average_cpc,average_position,cliques,conversoes,custo,impressoes"
                    . ") VALUES ('$day',$campanhaID,'$campanha',$avr_cpc,'$avr_pos',$cliques,$conv,$custo,$impressoes)");

            } 
        } 
        //echo "Dados inseridos com sucesso <br>";
        fclose($handle);
    }
}

function cadastraUsuarios($nome,$sobrenome,$email,$login,$senha,$id_cliente) {
    date_default_timezone_set('America/Sao_Paulo');
    $date = date('d-m-Y');
    $q_acname = mysql_query("SELECT account_name FROM tb_google_account WHERE id = $id_cliente");
    $account_name = mysql_result($q_acname,0);
    $result = mysql_query("INSERT tb_usuarios SELECT '', '".$nome."', "
            . "'".$sobrenome."', '".$email."', '".$login."', "
            . "'". sha1($senha) ."', 1, 1, '".$date."','".$account_name."', ".$id_cliente);
    if($result) {
        echo 'Cadastrado!';
    } else {
        echo 'Erro';
    }
}

function buscaNomeConta() {
    
    $query      = mysql_query("SELECT id, account_name FROM tb_google_account");
    //$contas     = mysql_fetch_array($query);
    
    return $query;
}


function todosDadosFato($id) {
    $query = mysql_query("
    SELECT 
        a.*
    FROM
        tb_google_fato_ads a
    INNER JOIN
        tb_unidades b
    ON
        a.account_id = b.google_id
    INNER JOIN
        tb_usuarios c
    ON
        c.id_unidade = b.id
    WHERE
        c.id = $id;
        ");

    //echo '<pre>';
    //echo '<h2>Todos</h2>';
    echo '<table class="ui center aligned striped selectable blue celled small table">';
    echo '<thead>';
    echo '<th>Periodo</th>';
    echo '<th>Nome da Conta</th>';
    echo '<th>Cliques</th>';
    echo '<th>Impressões</th>';
    echo '<th>Conversões</th>';
    echo '<th>Custo</th>';
    echo '</thead>';

    while ($dfatos = mysql_fetch_array($query)) {
        echo '<tr>';
        echo '<td>' . $dfatos['periodo'] . '</td>';
        echo '<td>' . utf8_encode($dfatos['account_name']) . '</td>';
        echo '<td>' . $dfatos['cliques'] . '</td>';
        echo '<td>' . $dfatos['impressoes'] . '</td>';
        echo '<td>' . $dfatos['conversoes'] . '</td>';
        echo '<td>R$ ' . number_format($dfatos['custo'],0,",",".") . '</td>';
        echo '</tr>';
    }

    echo '</table>';
    //echo '</pre>';

}

function dadosParciaisFato($permissao,$id) {
    if ($permissao <= 3) {
            $query = mysql_query("SELECT 
                sum(a.cliques) as cliques,
                sum(a.impressoes) as impressoes,
                sum(a.conversoes) as conversoes,
                round(sum(a.custo),2) as custo
            FROM
                tb_google_fato_ads a
            INNER JOIN
                tb_unidades b
            ON
               -- a.account_id = b.google_id
               replace(a.account_name,'Abertura Simples - ','') = b.nome_unidade
            WHERE   
                a.account_name != ' -- '
            AND
                b.esta_ativo = 1
            AND
                str_to_date(a.periodo, '%Y-%m-%d') 
                  BETWEEN DATE_SUB(NOW(), INTERVAL 31 DAY) AND NOW()
           ;");
        
    } else {
        $query = mysql_query("
                SELECT 
                    a.account_name,
                    sum(a.cliques) as cliques,
                    sum(a.impressoes) as impressoes,
                    sum(a.conversoes) as conversoes,
                    round(sum(a.custo),2) as custo
                FROM
                    tb_google_fato_ads a
                INNER JOIN
                    tb_unidades b
                ON
                    replace(a.account_name,'Abertura Simples - ','') = b.nome_unidade
                INNER JOIN
                    tb_usuarios c
                ON
                    c.id_unidade = b.id
                WHERE
                    c.id = $id
                AND
                    str_to_date(a.periodo, '%Y-%m-%d') 
                      BETWEEN DATE_SUB(NOW(), INTERVAL 31 DAY) AND NOW()
                GROUP BY 
                    a.account_name;");
    }

    

    $dados = mysql_fetch_array($query);
    return $dados;

}

function dadosLeads($dini,$dfim) {
    removeDuplicados();
    if ($dini == 0 || $dfim == 0) {
        $query = mysql_query("
        SELECT 
            *,
            date_format(data,'%d/%m/%Y - %Hh%i') as data_limpa
        FROM
            tb_leads
        WHERE 
            date_format(data,'%d-%m') = date_format(now(),'%d-%m')
        ORDER BY id DESC
        ;");
    } else {
        $query = mysql_query("
            SELECT 
                *,
                date_format(data,'%d/%m/%Y - %Hh%i') as data_limpa
            FROM
                tb_leads
            WHERE 
                date_format(data,'%d/%m/%Y') >= '".$dini."'
            AND
                date_format(data,'%d/%m/%Y') <= '".$dfim."'
            ORDER BY id
            ");
    }

    
    
    $tabela = "<table class='ui striped fixed single line celled center aligned selectable compact very small table record_table'>";
    $tabela .= "<thead>";
    $tabela .= "<th style='visibility:hidden; display: none;'>id</th>";
    $tabela .= "<th>#</th>";
    $tabela .= "<th>Data</th>";
    $tabela .= "<th>Nome</th>";
    $tabela .= "<th>Email</th>";
    $tabela .= "<th>Telefone</th>";
    $tabela .= "<th>Cidade</th>";
    $tabela .= "<th>Estado</th>";
    $tabela .= "<th>Mensagem</th>";
    $tabela .= "<th>Origem</th>";
    $tabela .= "<th>Identificador</th>";
    $tabela .= "</thead>";

    while ($leads = mysql_fetch_array($query)) {
        $tabela .= "<tr>";
        $tabela .= "<td class='coluna-id' style='visibility:hidden; display: none;'>".$leads['id']."</td>";
        $tabela .= "<td id='selecao'><input class='btn-checkbox' type='checkbox'></td>";
        $tabela .= "<td title='".$leads['data_limpa']."'>". $leads['data_limpa'] ."</td>";
        $tabela .= "<td title='".utf8_encode($leads['nome'])."' name='nome' id='". $leads['id'] ."' class='leadEditavel'>". utf8_encode($leads['nome']) ."</td>";
        $tabela .= "<td title='".$leads['email']."' name='email' id='". $leads['id'] ."' class='leadEditavel'>". $leads['email'] ."</td>";
        $tabela .= "<td title='".$leads['telefone']."' name='telefone' id='". $leads['id'] ."' class='leadEditavel'>". $leads['telefone'] ."</td>";
        $tabela .= "<td title='".utf8_encode($leads['cidade'])."' name='cidade' id='". $leads['id'] ."' class='leadEditavel'>". utf8_encode($leads['cidade']) ."</td>";
        $tabela .= "<td name='estado' id='". $leads['id'] ."' class='leadEditavel'>". $leads['estado'] ."</td>";
        $tabela .= "<td title='".utf8_encode($leads['mensagem'])."' name='mensagem' id='". $leads['id'] ."' class='leadEditavel'>". utf8_encode($leads['mensagem']) ."</td>";
        $tabela .= "<td>". $leads['origem'] ."</td>";
        $tabela .= "<td title='".$leads['identificador']."'>". $leads['identificador'] ."</td>";
        $tabela .= "</tr>";
    }

    $tabela .= "</table>";

    return $tabela;

}

function verificarPermissao($usuario_id) {
    $query = mysql_query("SELECT id_level FROM tb_usuarios WHERE id = $usuario_id");
    $permissao = mysql_result($query, 0);

    return $permissao;
}

function buscaPermissao($permissao) {
    if ($permissao <= 2) {
        $query = mysql_query("SELECT * FROM tb_level WHERE id > 1");
        return $query;
    } elseif ($permissao > 2) {
        $query = mysql_query("SELECT * FROM tb_level WHERE id > $permissao");
        return $query;
    }
}

function buscaLevel($usuario_id) {
    $ver = verificarPermissao($usuario_id);
    $query = mysql_query("SELECT level FROM tb_level WHERE id = $ver");
    $level = mysql_result($query, 0);

    return $level;
}

function removeDuplicados() {
    mysql_query("SET SQL_SAFE_UPDATES = 0;");
    $query = mysql_query("DELETE a FROM tb_leads AS a, tb_leads AS b WHERE a.chave=b.chave AND a.id < b.id;");
}

function buscaUnidades($permissao) {
    if ($permissao <= 0) {
        $query = mysql_query("SELECT * FROM tb_unidades where esta_ativo = 1 ORDER BY nome_unidade");
        return $query;
    } elseif ($permissao <= 3) {
        $query = mysql_query("SELECT * FROM tb_unidades
                                WHERE id NOT IN 
                                (SELECT DISTINCT id_unidade 
                                    FROM tb_usuarios WHERE active = 1 ORDER BY nome_unidade
                                ) AND esta_ativo = 1");
        return $query;
    } elseif ($permissao > 3) {
        $query = 0;
        return $query;
    }
}

function buscaUnidades2($permissao) {
    if ($permissao <= 0) {
        $query = mysql_query("SELECT * FROM tb_unidades WHERE esta_ativo = 1 order by 2 asc");
        return $query;
    } elseif ($permissao <= 3) {
        $query = mysql_query("SELECT * FROM tb_unidades
                                WHERE id IN 
                                (SELECT DISTINCT id_unidade 
                                    FROM tb_usuarios
                                )
                                AND id != 1 AND esta_ativo = 1 order by nome_unidade");
        return $query;
    } elseif ($permissao > 3) {
        $query = 0;
        return $query;
    }
}

function topIdentificadores($permissao,$unidade_id) {
    if ($permissao >= 4) {
        $query = mysql_query("SELECT 
                                a.identificador, COUNT(a.identificador) as quantidade
                            FROM
                                tb_conversoes a
                            INNER JOIN
                                tb_leads b
                            ON
                                b.id_ultima_conversao = a.id
                            WHERE
                                b.id_unidade = $unidade_id
                            GROUP BY 
                                identificador
                            ORDER BY 2 desc
                            LIMIT
                                5");
    } else {
        $query = mysql_query("SELECT DISTINCT
                                a.identificador, COUNT(a.id) as quantidade
                            FROM
                                tb_conversoes a
                            INNER JOIN
                                tb_leads b
                            ON a.id_lead = b.id
                            GROUP BY a.identificador
                            ORDER BY 2 DESC
                            LIMIT 5;");
    }
    

    $tabela = '<table class="ui fixed single line celled selectable center aligned compact very small table">';
    $tabela .= '<thead>';
    $tabela .= '<th>Identificador</th>';
    $tabela .= '<th>Quantidade</th>';
    $tabela .= '</thead>';
    if (mysql_num_rows($query) > 0) {
            while ($dados = mysql_fetch_array($query)) {
            $tabela .= '<tr>';
            $tabela .= '<td title="'.$dados['identificador'].'">'.$dados['identificador'].'</td>';
            $tabela .= '<td>'.$dados['quantidade'].'</td>';
            $tabela .= '</tr>';
        }
    }
    else {
          $tabela .= '<tr>';
            $tabela .= '<td title=""></td>';
            $tabela .= '<td></td>';
            $tabela .= '</tr>';
        }

    $tabela .= '</table>';
    $tabela .= '<div class="ui center aligned container"><a href="identificadores.php" class="ui black mini button">Ver todos</a></div>';

    return $tabela;

}

function identificadores($id_unidade,$permissao) {

    if ($permissao <= 3) {
        $query = mysql_query("SELECT DISTINCT
                                a.identificador, COUNT(a.id) as quantidade
                            FROM
                                tb_conversoes a
                            INNER JOIN
                                tb_leads b
                            ON a.id_lead = b.id
                            GROUP BY a.identificador
                            ORDER BY 2 DESC;");
    } else {
        $query = mysql_query("SELECT DISTINCT
                                a.identificador, COUNT(a.id) as quantidade
                            FROM
                                tb_conversoes a
                            INNER JOIN
                                tb_leads b
                            ON a.id_lead = b.id
                            WHERE b.id_unidade = $id_unidade
                            GROUP BY a.identificador
                            ORDER BY 2 DESC;");
    }
    
    $tabela = '<div id="dvData"><table id="tabela_unidades" class="ui fixed single line celled selectable center aligned sortable table lista-clientes">';
    $tabela .= '<thead>';
    $tabela .= '<tr><th>Identificador</th>';
    $tabela .= '<th>Conversões</th></tr>';
    $tabela .= '</thead>';
    while ($dados = mysql_fetch_array($query)) {
        $tabela .= '<tr>';
        $tabela .= '<td title="'.utf8_encode($dados['identificador']).'">'.utf8_encode($dados['identificador']).'</td>';
        $tabela .= '<td>'.$dados['quantidade'].'</td>';
        $tabela .= '</tr>';
    }

    $tabela .= '</table></div>';
    $tabela .= '<div id="dimmer_campanha" class="ui active centered inline "></div>';

    return $tabela;
}

function ultimosLeads($permissao,$id) {
    if ($permissao >= 4) {
        $query = mysql_query("SELECT 
                                date_format(b.data_conversao,'%H:%i') as data, 
                                a.cidade,
                                a.nome
                            FROM
                                tb_leads a
                            INNER JOIN 
                                tb_conversoes b
                            ON
                                a.id_ultima_conversao = b.id
                            WHERE 
                                a.id_unidade = $id
                            ORDER BY a.id desc
                            LIMIT
                                5;");
    } else {
        $query = mysql_query("SELECT 
                                    date_format(b.data_conversao,'%H:%i') as data, 
                                    a.cidade,
                                    a.nome
                                FROM
                                    tb_leads a
                                INNER JOIN
                                    tb_conversoes b
                                ON
                                    a.id_ultima_conversao = b.id
                                ORDER BY a.id desc
                                LIMIT
                                    5;");
    }
    

    $tabela = '<table class="ui fixed single line celled selectable center aligned compact very small table">';
    $tabela .= '<thead>';    
    $tabela .= '<th>Cidade</th>';
    $tabela .= '<th>Horário</th>';
    $tabela .= '</thead>';
    
    while ($dados = mysql_fetch_array($query)) {
        $tabela .= '<tr>';
        $tabela .= '<td title="'.$dados['nome'].'">'.utf8_encode($dados['cidade']).'</td>';
        $tabela .= '<td>'.$dados['data'].'</td>';        
        $tabela .= '</tr>';
    }

    $tabela .= '</table>';
    $tabela .= '<div class="ui center aligned container"><a href="gerais.php" class="ui black mini button">Ver todos</a></div>';

    return $tabela;

}

function leadsPendentes() {

    $query = mysql_query("SELECT 
                            count(id) as total_pendentes
                        FROM
                            tb_leads
                        WHERE
                            id_status = 1
                        AND
                            esta_ativo = 1");

    $total_pendentes = mysql_result($query, 0);

    return $total_pendentes;
    
}

function leadsNovos($id) {

    $query = mysql_query("SELECT 
                            count(id) as total_pendentes
                        FROM
                            tb_leads
                        WHERE
                            id_status = 2
                        AND
                            esta_ativo = 1
                        AND
                            id_unidade = $id
                            ;");

    $total_novos = mysql_result($query, 0);

    return $total_novos;
    
}

function contarLeads($id) {

    $query = mysql_query("SELECT 
                            count(id) as total_leads
                        FROM
                            tb_leads
                        WHERE
                            id_unidade = $id
                            ;");

    $total_novos = mysql_result($query, 0);

    return $total_novos;
    
}

function avg() {
    return array_sum(func_get_args() ) / func_num_args();
}

function detalhesCampanha() {
    // $query = mysql_query("
    //     SELECT 
    //         replace(a.account_name,'Abertura Simples - ','') as Unidade, 
    //         sum(a.cliques) as cliques,
    //         sum(a.impressoes) as impressoes,
    //         round(sum(a.conversoes),0) as conversoes,
    //         round(avg(a.average_cpc/1000000),2) as cpc_medio,
    //         round(avg(a.average_position),1) as posicao_media,
    //         round(sum(a.custo/1000000),2) as custo,
    //         b.id
    //     FROM 
    //         tb_google_fato_ads a
    //     INNER JOIN
    //         tb_unidades b
    //     ON
    //         replace(a.account_name,'Abertura Simples - ','') = b.nome_unidade
    //     WHERE 
    //         a.account_name != ' -- '
    //     AND
    //         b.esta_ativo = 1
    //     AND
    //         str_to_date(a.periodo,'%Y-%m-%d') BETWEEN DATE_SUB(NOW(), INTERVAL 31 DAY) AND NOW()
    //     GROUP BY
    //         a.account_name,b.id
    //     ORDER BY custo DESC;");
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
            str_to_date(a.periodo,'%Y-%m-%d') BETWEEN DATE_SUB(NOW(), INTERVAL 31 DAY) AND NOW()
        GROUP BY
            a.account_name,b.id
        ORDER BY custo DESC;");

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
    $indice = 0;
    $cliques = 0;
    $impressoes = 0;
    $conversoes = 0;
    $posicao_media = 0;
    $cpc_medio = 0;
    $custo = 0;
    $custoporlead = 0;
    while ($fatos = mysql_fetch_array($query)) {
        $conversao = getConversoes($fatos['id']);
        if ($conversao == 0) {
            $cpl = 0;
        } else {
            $cpl = ($fatos['custo']/$conversao);
        }
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
        $cliques += $fatos['cliques'];
        $impressoes += $fatos['impressoes'];
        $conversoes += $conversao;
        $posicao_media += $fatos['posicao_media'];
        $cpc_medio += $fatos['cpc_medio'];
        $custo += $fatos['custo'];
        $custoporlead += $cpl;
    }
    # Minimizar erro de Not Division by 0.
    if ($posicao_media == 0) {
        $pos_med_footer = 0;
    } else {
        $pos_med_footer = $posicao_media/$indice;
    }

    if ($cpc_medio == 0) {
        $cpc_medio_footer = 0;
    } else {
        $cpc_medio_footer = $cpc_medio/$indice;
    }
    # --
    $detalhes .= '<tfoot>';
    $detalhes .= '<tr>';
    $detalhes .= '<th><b>Total</b></th>';
    $detalhes .= '<th><b>'.number_format($cliques,0,".",".").'</b></th>';
    $detalhes .= '<th><b>'.number_format($impressoes,0,".",".").'</b></th>';
    $detalhes .= '<th><b>'.number_format($conversoes,0,".",".").'</b></th>';
    $detalhes .= '<th><b>'.number_format($pos_med_footer,1,".",".").'</b></th>';
    $detalhes .= '<th><b>R$ '.round($cpc_medio_footer).'</b></th>';
    $detalhes .= '<th><b>R$ '.number_format($custoporlead,2,",",".").'</b></th>';
    $detalhes .= '<th><b>R$ '.number_format($custo,2,",",".").'</b></th>';
    $detalhes .= '</tr>';
    $detalhes .= '</table></div>';
    $detalhes .= '<div id="dimmer_campanha" class="ui active centered inline "></div>';

    return $detalhes;
}

function getUnidades() {
    $query = mysql_query("SELECT * FROM tb_unidades WHERE esta_ativo = 1 AND id != 1");

    $retorno = '<table class="ui center aligned celled fixed selectable table">';
    $retorno .= '<thead>';
    $retorno .= '<th>Unidade</th>';
    $retorno .= '<th>Cidade</th>';
    $retorno .= '<th>Estado</th>';
    $retorno .= '<th>Google ID</th>';
    $retorno .= '<th class="two wide column">#</th>';
    $retorno .= '</thead>';

    while ($unidade = mysql_fetch_array($query)) {
        $retorno .= '<tr>';
        $retorno .= '<td>'.utf8_encode($unidade['nome_unidade']).'</td>';
        $retorno .= '<td>'.utf8_encode($unidade['Cidade']).'</td>';
        $retorno .= '<td>'.utf8_encode($unidade['Estado']).'</td>';
        $retorno .= '<td class="unidadeEditavel" id="'.$unidade['id'].'" name="google_id">'.$unidade['google_id'].'</td>';
        $retorno .= '<td><a title="Deletar." name="'.$unidade['nome_unidade'].'" id="'.$unidade['id'].'" class="ui red tiny icon button del_unit"><i class="trash alternate icon"></i></a></td>';
        $retorno .= '</tr>';
    }
    $retorno .= '</table>';


    return $retorno;
}

function getNomeUnidade($usuario_id) {
    $query      = mysql_query("SELECT b.nome_unidade FROM tb_usuarios a INNER JOIN tb_unidades b ON a.id_unidade = b.id WHERE a.id = $usuario_id");
    $retorno    = mysql_result($query, 0);

    return $retorno;
}

function getIdUnidade($unidade_nome) {
    $query = mysql_query("SELECT id FROM tb_unidades WHERE nome_unidade = '".$unidade_nome."'");
    $unidade_id = mysql_result($query, 0);

    return $unidade_id;
}


function getConversoes($unidade_id) {
    if ($unidade_id == 1) {
        $query = mysql_query("SELECT 
                                count(*) as conversoes
                            FROM
                                tb_leads a
                            INNER JOIN
                                tb_conversoes b
                            ON 
                                a.id_ultima_conversao = b.id
                            WHERE id_status NOT IN (1,3)
                            AND str_to_date(b.data_conversao,'%Y-%m-%d') BETWEEN DATE_SUB(NOW(), INTERVAL 31 DAY) AND NOW()
                            AND
                                b.origem != 'Manual';");
        $resultado = mysql_result($query,0);

    } elseif ($unidade_id != 1) {
        $query = mysql_query("SELECT 
                                 COUNT(*) as conversoes
                            FROM
                                tb_leads a
                            INNER JOIN
                                tb_conversoes b
                            ON 
                                a.id_ultima_conversao = b.id
                            WHERE
                                a.id_unidade = $unidade_id
                            AND
                                str_to_date(b.data_conversao,'%Y-%m-%d') BETWEEN DATE_SUB(NOW(), INTERVAL 31 DAY) AND NOW()
                            AND
                                b.origem != 'Manual';");

        $resultado = mysql_result($query,0);
    }

    return $resultado;
}

function getConversoesData($unidade_id,$dini,$dfim) {
    if ($unidade_id == 1) {
        $query = mysql_query("SELECT 
                                count(*) as conversoes
                            FROM
                                tb_leads a
                            INNER JOIN
                                tb_conversoes b
                            ON 
                                a.id_ultima_conversao = b.id
                            WHERE id_status NOT IN (1,3)
                            AND str_to_date(b.data_conversao,'%Y-%m-%d') between str_to_date('$dini','%d/%m/%Y') AND str_to_date('$dfim','%d/%m/%Y')
                            AND
                                b.origem != 'Manual';");
        $resultado = mysql_result($query,0);

    } elseif ($unidade_id != 1) {
        $query = mysql_query("SELECT 
                                 COUNT(*) as conversoes
                            FROM
                                tb_leads a
                            INNER JOIN
                                tb_conversoes b
                            ON 
                                a.id_ultima_conversao = b.id
                            WHERE
                                a.id_unidade = $unidade_id
                            AND
                                str_to_date(b.data_conversao,'%Y-%m-%d') between str_to_date('$dini','%d/%m/%Y') AND str_to_date('$dfim','%d/%m/%Y')
                            AND
                                b.origem != 'Manual';");
        
        $resultado = mysql_result($query,0);
    }

    return $resultado;
}

function detalhesCampanhaMensal($unidade_nome,$unidade_id) {
    $conversoes = getConversoes($unidade_id);
    $query = mysql_query("SELECT 
                            replace(account_name,'Abertura Simples - ','') as Unidade, 
                            sum(cliques) as cliques,
                            sum(impressoes) as impressoes,
                            round(sum(conversoes),0) as conversoes,
                            round(avg(average_cpc),2) as cpc_medio,
                            round(avg(average_position),1) as posicao_media,
                            round(sum(custo),2) as custo
                        FROM 
                            tb_google_fato_ads
                        WHERE 
                            account_name != ' -- '
                         AND replace(account_name,'Abertura Simples - ','') = '$unidade_nome'
                         AND str_to_date(periodo,'%Y-%m-%d') BETWEEN DATE_SUB(NOW(), INTERVAL 31 DAY) AND NOW()
                        GROUP BY UNIDADE
                         ;");

        $detalhes = '<table id="tabela_campanhas" class="ui center aligned celled selectable sortable table lista-clientes">';
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

    while ($fatos = mysql_fetch_array($query)) {
        if ($fatos['conversoes'] == 0) {
            $cpl = 0;
        } else {
            $cpl = ($fatos['custo']/$conversoes);
        }
        $detalhes .= '<tr>';
        $detalhes .= '<td>'.utf8_encode($fatos['Unidade']).'</td>';
        $detalhes .= '<td>'.number_format($fatos['cliques'],0,".",".").'</td>';
        $detalhes .= '<td>'.number_format($fatos['impressoes'],0,".",".").'</td>';
        //$detalhes .= '<td>'.number_format($fatos['conversoes'],0,".",".").'</td>';
        $detalhes .= '<td>'.$conversoes.'</td>';
        $detalhes .= '<td>'.$fatos['posicao_media'].'</td>';
        $detalhes .= '<td>R$ '.number_format($fatos['cpc_medio'],2,",",".").'</td>';
        $detalhes .= '<td>R$ '.number_format($cpl,2,",",".").'</td>';
        $detalhes .= '<td>R$ '.number_format($fatos['custo'],2,",",".").'</td>';
        $detalhes .= '</tr>';

    }

    $detalhes .= '</table>';
    $detalhes .= '<div id="dimmer_campanha" class="ui active centered inline "></div>';

    return $detalhes;
}

function buscarEmail(){
    $query = mysql_query("SELECT to_email,id_lead FROM tb_email_queue");
    //$email = mysql_result($query, 0);

    return $query;
}

function dispararEmail($to_email,$id_lead) {
    $host = $_SERVER['HTTP_HOST'];
    $query = mysql_query("SELECT 
                            a.nome
                            ,a.email
                            ,a.telefone
                            ,a.private_hash
                            ,b.identificador
                        FROM
                            tb_leads a
                        INNER JOIN
                            tb_conversoes b
                        ON
                            a.id_ultima_conversao = b.id
                        WHERE
                            a.id = ".$id_lead);
    $dados_lead = mysql_fetch_array($query);
    require '../vendor/autoload.php'; // If you're using Composer (recommended)
    // Comment out the above line if not using Composer
    // require("./sendgrid-php.php"); 
    // If not using Composer, uncomment the above line

    $email = new \SendGrid\Mail\Mail(); 
    $email->setFrom("painel@aberturasimples.com.br", "Painel Abertura Simples");
    $email->setSubject("Novo Lead: ".utf8_encode($dados_lead['nome']));
    $email->addTo($to_email, "Associado");
    $corpo_email = '<html>
                    <head>
                    <title> Novo Lead: '.utf8_encode($dados_lead['nome']).'</title>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
                    </head>
                    <body bgcolor="#e3e3e3" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
                    <table width="100%" height="100%" bgcolor="#e3e3e3">
                    <tr>
                    <td width="100%" height="100%" bgcolor="#e3e3e3">
                    <table id="Table_01" width="600" border="0" cellpadding="0" cellspacing="0" align="center" style="font-family: Lato, sans-serif; background-color: #ffffff">
                        <tr>
                            <td width="600" height="71" bgcolor="#1b1c1d" align="center">
                                <a href="http://www.aberturasimples.com.br/" target="_blank"><img src="https://painel.aberturasimples.com.br/images/email/logo.png" alt="Abertura Simples" style="color: #ffffff; font-size: 24px"></a></td>
                        </tr>
                        <tr>
                            <td width="600" height="20" style="text-align: center; font-size: 22px; color: #1b1c1d; padding-top: 40px"><b>Parabéns!</b></td>
                        </tr>
                        <tr>
                            <td width="600" height="20" style="text-align: center; font-size: 14px; color: #757575">Você recebeu um novo Lead.</td>
                        </tr>
                        <tr>
                            <td width="600" height="40" style="font-size: 16px; color: #1b1c1d; padding-left: 40px; padding-top: 20px"><b>Detalhes do Lead<b/></td>
                        </tr>
                        <tr>
                            <td width="600" height="88" style="padding-left: 40px; font-size: 14px; color: #1b1c1d">
                                <p><b>Nome:</b> '.utf8_encode($dados_lead['nome']).'</p>
                                <p><b>Email: </b><a href="#" target="_blank">'.$dados_lead['email'].'</a></p>
                                <p><b>Telefone:</b> '.$dados_lead['telefone'].'</p>
                                <p><b>Identificador:</b> '.$dados_lead['identificador'].'</p>
                                <p><b>Perfil: </b><a href="https://'.$host.'/view/profile.php?private='.$dados_lead['private_hash'].'" target="_blank">https://'.$host.'/view/private/</a></p></td>
                        </tr>
                        <tr>
                            <td width="600" height="95" align="center">
                                <a href="http://'.$host.'/" target="_blank" style="padding-right: 40px;"><img src="https://painel.aberturasimples.com.br/images/email/acessar-painel.png" width="202" height="32" alt="Acessar Painel" style="color: #ffffff; background-color: #21ba45; border-radius: 3px; font-size: 14px;"></a>
                                <a href="https://'.$host.'/view/profile.php?private='.$dados_lead['private_hash'].'" target="_blank"><img src="https://painel.aberturasimples.com.br/images/email/informacoes-lead.png" width="202" height="32" alt="Informações do Lead" style="color: #ffffff; background-color: #db2828; border-radius: 3px; font-size: 14px;"></a></td>
                        </tr>
                        <tr>
                            <td width="600" height="78" style="text-align: center; color: #757575; font-size: 14px">
                                Enviado por <a href="http://www.aberturasimples.com.br/" target="_blank">Abertura Simples</a><br>
                                <small>© Abertura Simples 2019 - Todos os direitos Reservados.</small>
                            </td>
                        </tr>
                        <tr>
                            <td width="600" height="11" bgcolor="#1B1C1D"></td>
                        </tr>
                    </table>
                    <br><br><br><br>
                    </td>
                    </tr>
                    </table>
                    </body>
                    </html>';

    $email->addContent("text/html", $corpo_email);
    // $email->addContent(
    //     "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
    // );
    $sendgrid = new \SendGrid('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');
    try {
        $response = $sendgrid->send($email);
        //print $response->statusCode() . "\n";
        //print_r($response->headers());
        //print $response->body() . "\n";
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
    if ($response->statusCode() == 202) {
     // Successfully sent
     //echo 'Email enviado com sucesso';
     return true;
    } else {
     echo 'Ocorreu algum problema ao enviar o e-mail. Tente novamente ou entre em contato com o Administrador.';
     return false;
    }
}

function leadsPorUnidade($permissao) {
    $query = mysql_query("SELECT 
                                a.id
                                ,a.nome_unidade
                                ,count(a.id) as total
                                ,a.cidade
                                ,a.estado
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
                            -- AND c.origem != 'Manual'
                            GROUP BY
                                a.id,a.nome_unidade,a.cidade,a.estado
                            ORDER BY 3 DESC
                                ;");

    
    $retorno = "<div id='dvData' class='resultado'><table id='tabela_unidades' class='ui fixed single line celled selectable center aligned sortable compact table lista-clientes'>";
    $retorno .= "<thead>";
    $retorno .= "<th>Nome da Unidade</th>";
    $retorno .= "<th>Cidade da Unidade</th>";
    $retorno .= "<th class='three wide'>Estado da Unidade</th>";
    $retorno .= "<th class='two wide'>Total</th>";
    $retorno .= "<th class='one wide no-sort'></th>";
    $retorno .= "</thead>";
    $total = 0;
    while ($dados = mysql_fetch_array($query)) {
        $retorno .= "<tr>";
        $retorno .= "<td>".utf8_encode($dados['nome_unidade'])."</td>";
        $retorno .= "<td>".utf8_encode($dados['cidade'])."</td>";
        $retorno .= "<td>".$dados['estado']."</td>";
        $retorno .= "<td>".$dados['total']."</td>";
        $retorno .= "<td title='Clique aqui para visualizar a lista de Leads.'><button id='".$dados['id']."' class='ui tiny basic icon button ver_leads'><i class='eye black icon'></i></button></td>";
        $retorno .= "</tr>";
        $total += $dados['total'];
    }
    $retorno .= "<tfoot>";
    $retorno .= "<tr>";
    $retorno .= "<th colspan='5' class='right aligned'><br></th>";
    $retorno .= "</tr>";
    $retorno .= "</tfoot>";
    $retorno .= "</table></div>";    
    $retorno .= '<div id="dimmer_campanha" class="ui active centered inline "></div>';
    $retorno .= '<script src="../view/js/exibeModalLeads.js"></script>';
    return $retorno;    
}

function leadsEnviados($permissao) {
    $query = mysql_query("SELECT 
                                a.id
                                ,a.nome_unidade
                                ,count(a.id) as total
                                ,a.cidade
                                ,a.estado
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
                            AND c.origem != 'Manual'
                            GROUP BY
                                a.id,a.nome_unidade,a.cidade,a.estado
                            ORDER BY 3 DESC
                                ;");

    
    $retorno = "<div id='dvData' class='resultado'><table id='tabela_unidades' class='ui fixed single line celled selectable center aligned sortable compact table lista-clientes'>";
    $retorno .= "<thead>";
    $retorno .= "<th>Nome da Unidade</th>";
    $retorno .= "<th>Cidade da Unidade</th>";
    $retorno .= "<th class='three wide'>Estado da Unidade</th>";
    $retorno .= "<th class='two wide'>Total</th>";
    $retorno .= "<th class='one wide no-sort'></th>";
    $retorno .= "</thead>";
    $total = 0;
    while ($dados = mysql_fetch_array($query)) {
        $retorno .= "<tr>";
        $retorno .= "<td>".utf8_encode($dados['nome_unidade'])."</td>";
        $retorno .= "<td>".utf8_encode($dados['cidade'])."</td>";
        $retorno .= "<td>".$dados['estado']."</td>";
        $retorno .= "<td>".$dados['total']."</td>";
        $retorno .= "<td title='Clique aqui para visualizar a lista de Leads.'><button id='".$dados['id']."' class='ui tiny basic icon button ver_leads'><i class='eye black icon'></i></button></td>";
        $retorno .= "</tr>";
        $total += $dados['total'];
    }
    $retorno .= "<tfoot>";
    $retorno .= "<tr>";
    $retorno .= "<th colspan='5' class='right aligned'><br></th>";
    $retorno .= "</tr>";
    $retorno .= "</tfoot>";
    $retorno .= "</table></div>";    
    $retorno .= '<div id="dimmer_campanha" class="ui active centered inline "></div>';
    $retorno .= '<script src="../view/js/exibeModalLeads.js"></script>';
    return $retorno;    
}

function donoDoLead($lead_hash) {
    $query = mysql_query("SELECT 
                            id_unidade
                        FROM
                            tb_leads
                        WHERE
                            private_hash = '".$lead_hash."'");
    $dono = mysql_result($query,0);

    return $dono;
}

function atividadesLead($p_hash) {
        $query = mysql_query("SELECT 
                                a.id
                                ,a.tipo_atividade
                                ,a.comentario
                                ,a.data_atividade
                                ,c.data_conversao
                                ,c.identificador
                                ,c.mensagem
                                ,c.origem
                                ,c.midia
                                ,c.campaign
                                ,c.nicho_empresa
                                ,c.form_title
                                ,c.form_url
                                ,c.page_title
                                ,c.page_url
                                ,f.faturamento
                                ,g.tipo_empresa
                                ,d.nome as usuario
                                ,c.nome_conversao
                                ,c.telefone_conversao
                                ,c.cidade_conversao
                                ,c.estado_conversao
                                ,a.id_usuario
                            FROM
                                tb_atividades a
                            INNER JOIN tb_leads b ON a.private_hash_lead = b.private_hash
                            INNER JOIN tb_conversoes c ON a.id_conversao = c.id
                            INNER JOIN tb_usuarios d ON a.id_usuario = d.id
                            LEFT JOIN tb_chatbot_faturamento f ON c.id_faturamento_empresa = f.id
                            LEFT JOIN tb_chatbot_tipoempresa g ON c.id_tipo_empresa = g.id
                            WHERE 
                                b.private_hash = '".$p_hash."'
                            ORDER BY a.id DESC");

        function converterData($data) {
            $dInicio = new DateTime($data);
            $dFim  = new DateTime();
            $dDiff = $dInicio->diff($dFim);
            $dias = $dDiff->days;

            if($dias <= 0) {
                return 'Hoje às ' . $dInicio->format('H:i');
            } else if($dias == 1) {
                return 'Ontem às ' . $dInicio->format('H:i');
            }

            return $dInicio->format('d/m/Y') . ' às ' . $dInicio->format('H:i');
        }

        $lista_atividades = '<h4 class="ui center aligned header">Atividades do Lead</h4><br>';

        while ($atividade = mysql_fetch_array($query)) {
            switch ($atividade['tipo_atividade']) {
                case 1:
                    $tipo_atividade = "Conversão";
                    $icone = '<i class="trophy big yellow icon"></i>';
                    $action = '<div class="box-toggle">
                                <div class="tgl">
                                    <br>
                                    <p>
                                        <i class="calendar alternate grey icon"></i><b>Data da conversão: </b>'.converterData($atividade['data_conversao']).'<br>
                                        <i class="id card grey icon"></i><b>Nome: </b>'.utf8_encode($atividade['nome_conversao']).'<br>
                                        <i class="phone grey icon"></i><b>Telefone: </b>'.$atividade['telefone_conversao'].'<br>
                                        <i class="map marker grey icon"></i><b>Cidade: </b>'.utf8_encode($atividade['cidade_conversao']).'<br>
                                        <i class="map signs grey icon"></i><b>Estado: </b>'.$atividade['estado_conversao'].'<br>
                                        <i class="building signs grey icon"></i><b>Tipo de Empresa: </b>'.utf8_encode($atividade['tipo_empresa']).'<br>
                                        <i class="briefcase grey icon"></i><b>Atividade: </b>'.$atividade['nicho_empresa'].'<br>
                                        <i class="money grey icon"></i><b>Faturamento: </b>'.utf8_encode($atividade['faturamento']).'<br>
                                        <i class="barcode grey icon"></i><b>Identificador: </b>'.$atividade['identificador'].'<br>
                                        <i class="compass grey icon"></i><b>Origem: </b>'.$atividade['origem'].'<br>
                                        <i class="at grey icon"></i><b>Mídia: </b>'.$atividade['midia'].'<br>
                                        <i class="bullhorn grey icon"></i><b>Campanha: </b>'.$atividade['campaign'].'<br>
                                        <i class="clipboard grey icon"></i><b>Formulário: </b><a href="'.$atividade['form_url'].'" target="_blank">'.$atividade['form_title'].'</a><br>
                                        <i class="file alternate grey icon"></i><b>Página: </b><a href="'.$atividade['page_url'].'" target="_blank">'.$atividade['page_title'].'</a><br>
                                        <i class="comment alternate grey icon"></i><b> Mensagem: </b>'.utf8_encode($atividade['mensagem']).'<br>
                                    </p>
                                </div>
                            </div>';
                    break;
                case 2:
                    $tipo_atividade = "Alteração";
                    $icone = '<i class="sync alternate big grey icon"></i>';
                    $action = '<a class="reply">por '.utf8_encode($atividade['usuario']).'</a>';
                    break;
                case 3:
                    $tipo_atividade = "Comentário";
                    $icone = '<i class="comment alternate big green icon"></i>';
                    if ($atividade['id_usuario'] == $_SESSION['usuarioID']) {
                        $action = '<a class="reply">por '.utf8_encode($atividade['usuario']).'</a>'
                                .'<a class="save" id="'.$atividade['id'].'"><i class="trash alternate icon"></i></a>';
                    } else {
                        $action = '<a class="reply">por '.utf8_encode($atividade['usuario']).'</a>';
                    }
                    
                    break;       
                case 4:
                    $tipo_atividade = "Envio";
                    $icone = '<i class="paper plane big blue icon"></i>';
                    $action = '<a class="reply">por Sistema</a>';
                    
                    break;   
                case 5:
                    $tipo_atividade = "Responsável";
                    $icone = '<i class="user black big icon"></i>';
                    $action = '<a class="reply">por Sistema</a>';
                    break;        
                default:
                    $tipo_atividade = "Não Registrado";
                    break;
            }


            $nova_data = converterData($atividade['data_atividade']);

            $lista_atividades .= '
                                    <div class="comment">
                                    <a class="avatar">
                                      '.$icone.'
                                    </a>
                                    <div class="content">
                                      <a class="author">'.$tipo_atividade.'</a>
                                      <div class="metadata">
                                        <span class="date">'.$nova_data.'</span>
                                      </div>
                                      <div class="text">
                                        <p>'.utf8_encode($atividade['comentario']).'</p>
                                      </div>
                                      <div class="actions">
                                        '.$action.'                                        
                                      </div>
                                    </div>
                                    <div class="comments">

                                    </div>
                                  </div>';
        }
        $lista_atividades .= "</div>";

        $lista_atividades .= "<script>

                                jQuery.fn.toggleText = function(a,b) {

                                    return  this.html(this.html().replace(new RegExp('('+a+'|'+b+')'),function(x){return(x==a)?b:a;}));
                                }

                                $(document).ready(function(){

                                    $('.tgl').before('<a>Ver Mais</a>');
                                    $('.tgl').css('display', 'none');
                                    $('a', '.box-toggle').click(function() {
                                        $(this).next().slideToggle('slow')
                                            .siblings('.tgl:visible').slideToggle('fast');
                                            // aqui começa o funcionamento do plugin
                                            $(this).toggleText('Mais','Menos')
                                                .siblings('a').next('.tgl:visible').prev()
                                                .toggleText('Mais','Menos')
                                        });
                                });

                                $('.save').click(function(){
                                    var id = $(this).attr('id');
                                    $.ajax({
                                        url: '../model/ajaxDeletarComentario.php',
                                        type: 'POST',
                                        data: {
                                            'id': id,
                                            'hash': '".$p_hash."'
                                        },
                                        success: function(msg) { 
                                            $('#divcomments').fadeOut(500,function(){
                                                $('#divcomments').html(msg).fadeIn().delay(500);
                                            });
                                        }
                                    });
                                });

                            </script>";

        return $lista_atividades;
}

function adicionarAtividade($tipo_atividade,$comentario,$hash,$usuario_id) {
    date_default_timezone_set('America/Sao_Paulo');
    $date = date('d-m-Y H:i');

    if ($tipo_atividade == 2) {
        mysql_query("INSERT 
                    tb_atividades 
                SELECT 
                    null, 
                    ".$tipo_atividade." , 
                    '".utf8_decode($comentario)."' , 
                    (select private_hash from tb_leads where id = ".$hash.") , 
                    (select id_ultima_conversao from tb_leads where id = ".$hash.") , 
                    '".$date."' , 
                    ".$usuario_id)
    ;
    } else {
        mysql_query("INSERT 
                    tb_atividades 
                SELECT 
                    null, 
                    ".$tipo_atividade." , 
                    '".utf8_decode($comentario)."' , 
                    '".$hash."' , 
                    (select id_ultima_conversao from tb_leads where private_hash = '".$hash."') , 
                    '".$date."' ,
                    ".$usuario_id)
    ;
    }
    
}

function getNomeUsuario($id) {
    $query = mysql_query("SELECT nome,sobrenome FROM tb_usuarios WHERE id = $id");
    $nome_arr = mysql_fetch_array($query);

    return $nome_arr;
}

