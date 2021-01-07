<?php 
  /*
    Dados do Grafico de Donut:
      - A Variavel 'top4' busca as 4 cidades com mais Leads recebidos 
      - Enquanto a variavel 'outros' retorna o total de Leads na base
      - No laço de repetição é subtraído o total - top4, ficando o valor exato de outros */
  $top4 = mysql_query("SELECT 
                          cidade, 
                          COUNT(id) as total
                      FROM
                          tb_leads
                      WHERE
                        cidade != ''
                      GROUP BY 
                        cidade
                      ORDER BY 
                        total DESC
                      LIMIT 4;");
  $busca_outros = mysql_query("SELECT count(id) FROM tb_leads WHERE cidade != ''");
  $outros = mysql_result($busca_outros, 0);
  $cid = array();
  $i = 0;
  $total = 0;

  while ($cidades = mysql_fetch_array($top4)) {
    $cid[$i] = utf8_encode($cidades['cidade']);
    $vcid[$i] = $cidades['total'];
    $total = $total + $cidades['total'];
    $i++;
  }
  $outros = $outros - $total;
  /*************************************************************************************/

  /* 
    Dados do Gráfico de Pizza
      - Simples query separando o que é contador(Sede) e Empreendedor(não Sede) */
  $contXemp = mysql_query("SELECT
                              count(a.id) as Empreendedores,
                                (SELECT count(a.id) FROM tb_leads a inner join tb_unidades b on a.id_unidade = b.id WHERE b.nome_unidade = 'Associados') as Contadores
                              FROM
                              tb_leads a
                              INNER JOIN
                              tb_unidades b
                              ON
                                a.id_unidade = b.id
                              WHERE
                                b.nome_unidade != 'Associados'
                              ;");
  $empreendedores = mysql_result($contXemp, 0,0);
  $contadores     = mysql_result($contXemp, 0,1);
/*************************************************************************************/

  $q_rec = mysql_query("SELECT
                          date_format(str_to_date(b.data_conversao,'%Y-%m-%d'),'%m') as mes_p,
                          date_format(str_to_date(b.data_conversao,'%Y-%m-%d'),'%M') as mes,
                          count(a.id) as total
                        FROM
                          tb_leads a 
                        INNER JOIN
                          tb_conversoes b
                        ON
                          a.id_ultima_conversao = b.id
                        WHERE date_format(str_to_date(b.data_conversao,'%Y-%m-%d'),'%Y') = date_format(now(),'%Y')
                        GROUP BY mes, mes_p
                        ORDER BY mes_p ASC;");

  $q_env = mysql_query("SELECT
                          date_format(str_to_date(b.data_conversao,'%Y-%m-%d'),'%m') as mes_p,
                          date_format(str_to_date(b.data_conversao,'%Y-%m-%d'),'%M') as mes,
                          count(a.id) as total
                        FROM
                          tb_leads a
                        INNER JOIN
                          tb_conversoes b
                        ON 
                          a.id_ultima_conversao = b.id
                        WHERE date_format(str_to_date(b.data_conversao,'%Y-%m-%d'),'%Y') = date_format(now(),'%Y')
                        AND a.id_status not in (1,3)
                        GROUP BY mes, mes_p
                        ORDER BY mes_p asc
                        ;");

  $q_del = mysql_query("SELECT
                        date_format(str_to_date(b.data_conversao,'%Y-%m-%d'),'%m') as mes_p,
                        date_format(str_to_date(b.data_conversao,'%Y-%m-%d'),'%M') as mes,
                        count(a.id) as total
                      FROM
                        tb_leads a
                      INNER JOIN
                        tb_conversoes b
                      ON
                        a.id_ultima_conversao = b.id
                      WHERE date_format(str_to_date(b.data_conversao,'%Y-%m-%d'),'%Y') = date_format(now(),'%Y')
                      AND a.id_status = 3
                      GROUP BY mes, mes_p
                      ORDER BY mes_p asc
                        ;");

        $rjan = 0; $rfev = 0; $rmar = 0; $rabr = 0; $rmai = 0; $rjun = 0; $rjul = 0; $rago = 0; $rset = 0; $rout = 0; $rnov = 0; $rdez = 0;
        $ejan = 0; $efev = 0; $emar = 0; $eabr = 0; $emai = 0; $ejun = 0; $ejul = 0; $eago = 0; $eset = 0; $eout = 0; $enov = 0; $edez = 0;
        $djan = 0; $dfev = 0; $dmar = 0; $dabr = 0; $dmai = 0; $djun = 0; $djul = 0; $dago = 0; $dset = 0; $dout = 0; $dnov = 0; $ddez = 0;

  while ($recebidos = mysql_fetch_array($q_rec)) {
    switch (utf8_encode($recebidos['mes'])) {
      case 'January':
        $rjan = $recebidos['total'];
        break;
      case 'February':
        $rfev = $recebidos['total'];
        break;
      case 'March':
        $rmar = $recebidos['total'];
        break;
      case 'April':
        $rabr = $recebidos['total'];
        break;
      case 'May':
        $rmai = $recebidos['total'];
        break;
      case 'June':
        $rjun = $recebidos['total'];
        break;
      case 'July':
        $rjul = $recebidos['total'];
        break;
      case 'August':
        $rago = $recebidos['total'];
        break;
      case 'September':
        $rset = $recebidos['total'];
        break;
      case 'October':
        $rout = $recebidos['total'];
        break;
      case 'November':
        $rnov = $recebidos['total'];
        break;
      case 'December':
        $rdez = $recebidos['total'];
        break;
      default:
        # code...
        break;
    }
  }

  while ($enviados = mysql_fetch_array($q_env)) {
    switch (utf8_encode($enviados['mes'])) {
      case 'January':
        $ejan = $enviados['total'];
        break;
      case 'February':
        $efev = $enviados['total'];
        break;
      case 'March':
        $emar = $enviados['total'];
        break;
      case 'April':
        $eabr = $enviados['total'];
        break;
      case 'May':
        $emai = $enviados['total'];
        break;
      case 'June':
        $ejun = $enviados['total'];
        break;
      case 'July':
        $ejul = $enviados['total'];
        break;
      case 'August':
        $eago = $enviados['total'];
        break;
      case 'September':
        $eset = $enviados['total'];
        break;
      case 'October':
        $eout = $enviados['total'];
        break;
      case 'November':
        $enov = $enviados['total'];
        break;
      case 'December':
        $edez = $enviados['total'];
        break;
      default:
        # code...
        break;
    }
  }

  while ($deletados = mysql_fetch_array($q_del)) {
    switch (utf8_encode($deletados['mes'])) {
      case 'January':
        $djan = $deletados['total'];
        break;
      case 'February':
        $dfev = $deletados['total'];
        break;
      case 'March':
        $dmar = $deletados['total'];
        break;
      case 'April':
        $dabr = $deletados['total'];
        break;
      case 'May':
        $dmai = $deletados['total'];
        break;
      case 'June':
        $djun = $deletados['total'];
        break;
      case 'July':
        $djul = $deletados['total'];
        break;
      case 'August':
        $dago = $deletados['total'];
        break;
      case 'September':
        $dset = $deletados['total'];
        break;
      case 'October':
        $dout = $deletados['total'];
        break;
      case 'November':
        $dnov = $deletados['total'];
        break;
      case 'December':
        $ddez = $deletados['total'];
        break;
      default:
        # code...
        break;
    }
  }

?>