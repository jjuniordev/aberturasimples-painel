<?php 
  # **************************************************************
  # * QUERIES UTILIZADAS NOS DADOS DOS GRÁFICOS DE PIZZA E DONUT *
  # **************************************************************
  $clientes = mysql_query("SELECT 
                          COUNT(id) as clientes
                      FROM
                          tb_leads
                      WHERE
                          id_status = 5 
                      AND 
                          id_unidade = $unidade_id;");
  $total_clientes = mysql_result($clientes,0);

    $rejeitados = mysql_query("SELECT 
                          COUNT(id) as clientes
                      FROM
                          tb_leads
                      WHERE
                          id_status = 6
                      AND 
                          id_unidade = $unidade_id;");
  $total_rejeitados = mysql_result($rejeitados,0);

    $followup = mysql_query("SELECT 
                          COUNT(id) as clientes
                      FROM
                          tb_leads
                      WHERE
                          id_status = 4 
                      AND 
                          id_unidade = $unidade_id;");
  $total_followup = mysql_result($followup,0);

  # *****************************************************
  # * QUERIES UTILIZADAS NOS DADOS DO GRÁFICO DE BARRAS *
  # *****************************************************
  $dataf=date('Y-m-d');
  $datai=date('Y-m-d ', strtotime('-12 months'));
  // $leadsPorMes = mysql_query("SELECT 
  //                                 date_format(str_to_date(a.data,'%Y-%m-%d'),'%y/%m') as numeracao,
  //                                 concat(left(date_format(str_to_date(a.data,'%Y-%m-%d'),'%M'),3),date_format(str_to_date(data,'%Y-%m-%d'),'%y')) as label,
  //                                 date_format(str_to_date(a.data,'%Y-%m-%d'),'%M') as data_format,
  //                                 count(a.id) as total
  //                             FROM
  //                                 tb_leads a
  //                             WHERE
  //                                 id_unidade = ".$unidade_id."
  //                             AND str_to_date(data,'%Y-%m-%d') BETWEEN str_to_date('2017-05-07','%Y-%m-%d') AND str_to_date('2018-05-07','%Y-%m-%d')
  //                             AND date_format(str_to_date(data,'%Y-%m-%d'),'%Y') = date_format(now(),'%Y')
  //                             GROUP BY 
  //                               data_format,numeracao, label
  //                             ORDER BY 
  //                               numeracao DESC
  //                             ;");
  $leadsPorMes = mysql_query("SELECT
                                date_format(str_to_date(b.data_conversao,'%Y-%m-%d'),'%m') as mes_p,
                                date_format(str_to_date(b.data_conversao,'%Y-%m-%d'),'%M') as mes,
                                count(a.id) as total
                                FROM
                                tb_leads a
                                INNER JOIN
                                tb_conversoes b
                                  ON a.id_ultima_conversao = b.id
                                WHERE date_format(str_to_date(b.data_conversao,'%Y-%m-%d'),'%Y') = date_format(now(),'%Y')
                              AND id_unidade = ".$unidade_id."
                              AND str_to_date(b.data_conversao,'%Y-%m-%d') BETWEEN str_to_date('".$datai."','%Y-%m-%d') AND str_to_date('".$dataf."','%Y-%m-%d')
                              GROUP BY mes, mes_p
                              ORDER BY mes_p ASC
                              ;");
        $djan = 0;
        $dfev = 0;
        $dmar = 0;
        $dabr = 0;
        $dmai = 0;
        $djun = 0;
        $djul = 0;
        $dago = 0;
        $dset = 0;
        $dout = 0;
        $dnov = 0;
        $ddez = 0;

  while ($total_leads = mysql_fetch_array($leadsPorMes)) {
    switch (utf8_encode($total_leads['mes'])) {
      default:

        break;
      case 'January':
        $djan = $total_leads['total'];
        break;
      case 'February':
        $dfev = $total_leads['total'];
        break;
      case 'March':
        $dmar = $total_leads['total'];
        break;
      case 'April':
        $dabr = $total_leads['total'];
        break;
      case 'May':
        $dmai = $total_leads['total'];
        break;
      case 'June':
        $djun = $total_leads['total'];
        break;
      case 'July':
        $djul = $total_leads['total'];
        break;
      case 'August':
        $dago = $total_leads['total'];
        break;
      case 'September':
        $dset = $total_leads['total'];
        break;
      case 'October':
        $dout = $total_leads['total'];
        break;
      case 'November':
        $dnov = $total_leads['total'];
        break;
      case 'December':
        $ddez = $total_leads['total'];
        break;      
      
    }
  }


?>
<!-- CANVAS CONTENDO A DIV DO GRÁFICO -->
<div class="ui two column grid">
  <div class="column">
  <div class="ui segment">
    <h4 class="ui center aligned header">Total x Status</h4>
    <canvas id="doughnut-chart" width="600" height="250"></canvas>
  </div>  
</div>
<div class="column">
  <div class="ui segment">
    <h4 class="ui center aligned header">Taxa de Conversão de Vendas</h4>
    <canvas id="pie-chart" width="600" height="250"></canvas>
  </div>
</div>
</div>
<br><br>
<div style="height: 142px; width: 100%; overflow: hidden;">
  <embed width="100%" height="280px" src="https://datastudio.google.com/embed/reporting/1lO52q39SyTBNmp6sAsccLhDVUKdeRq4q/page/9zkQ" frameborder="0" style="border:0;"></embed>
</div>
<!-- CANVAS CONTENDO A DIV DO GRÁFICO -->
<br>
<div class="ui segment">
  <h4 class="ui center aligned header">Leads por Mês (<?php echo date('Y'); ?>)</h4>
  <canvas id="myChart" width="900" height="220"></canvas>  
</div>
<br>
<div class="ui two column grid">
  <div class="column">
    <div class="ui segment">
      <h4 class="ui center aligned header">Top Identificadores</h4>
      <?php   
      $top_identificadores = topIdentificadores($permissao,$unidade_id);
      echo $top_identificadores;
     ?>  
    </div>
  </div>
  <div class="column">
    <div class="ui segment">
      <h4 class="ui center aligned header">Últimos Leads</h4>
      <?php 
        $ultimosLeads = ultimosLeads($permissao,$unidade_id);
        echo $ultimosLeads;
       ?>
    </div>
  </div>
</div>

<!--******************************
    **SCRIPT DO GRÁFICO DE DONUT**
    ******************************-->
<?php  
  echo '<script>
    new Chart(document.getElementById("doughnut-chart"), {
        type: "doughnut",
        data: {
          labels: ["Follow Up","Clientes", "Rejeitados"],
          datasets: [
            {
              label: "",
              backgroundColor: ["#FBBD08", "#2185D0","#e0e1e2"],
              data: ['.$total_followup.','.$total_clientes.','.$total_rejeitados.'],
              borderWidth: 0
            }
          ]
        },
        options: {
          title: {
            display: false,
            text: "Perfil do Lead"
          }
        }
    });
  </script>';

?>


<!--*******************************
    **SCRIPT DO GRÁFICO DE BARRAS**
    *******************************-->
<?php 

echo "<script>
  var ctx = document.getElementById('myChart');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
      datasets: [{
          label: 'Recebidos',
          data: [".$djan.",".$dfev.",".$dmar.",".$dabr.",".$dmai.",".$djun.",".$djul.",".$dago.",".$dset.",".$dout.",".$dnov.",".$ddez."],
          backgroundColor: [
            '#DB2828',
            '#DB2828',
            '#DB2828',
            '#DB2828',
            '#DB2828',
            '#DB2828',
            '#DB2828',
            '#DB2828',
            '#DB2828',
            '#DB2828',
            '#DB2828',
            '#DB2828'
          ],
          borderColor: [
            'rgba(255,99,132,1)',
            'rgba(255,99,132,1)',
            'rgba(255,99,132,1)',
            'rgba(255,99,132,1)',
            'rgba(255,99,132,1)',
            'rgba(255,99,132,1)',
            'rgba(255,99,132,1)',
            'rgba(255,99,132,1)',
            'rgba(255,99,132,1)',
            'rgba(255,99,132,1)',
            'rgba(255,99,132,1)',
            'rgba(255,99,132,1)',
          ],
          borderWidth: 0
        },
      ]
    },
    options: {
      scales: {
        yAxes: [{
          stacked: true,
          ticks: {
            beginAtZero: true
          }
        }],
        xAxes: [{
          stacked: true,
          ticks: {
            beginAtZero: true
          }
        }]
      }
    }
  });
</script>";


?>


<!--******************************
    **SCRIPT DO GRÁFICO DE PIZZA**
    ******************************-->
<?php 
  echo '<script>
    new Chart(document.getElementById("pie-chart"), {
      type: "doughnut",
      data: {
        labels: ["Clientes", "Rejeitados"],
        datasets: [{
          label: "",
          backgroundColor: ["#2185D0", "#e0e1e2"],
          data: ['.$total_clientes.','.$total_rejeitados.'],
          borderWidth: 0
        }]
      },
      options: {
        title: {
          display: false,
          text: "Leads por Cidade"
        }
      }
    });
  </script>';

?>
