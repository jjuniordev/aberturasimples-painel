
<?php include '../model/dados_grafico_interno.php'; ?>

<!-- CANVAS CONTENDO A DIV DO GRÁFICO -->
<div class="ui two column grid">
  <div class="column">
  <div class="ui segment">
    <h4 class="ui center aligned header">Leads por Cidade</h4>
    <canvas id="doughnut-chart" width="600" height="250"></canvas>
  </div>  
</div>
<div class="column">
  <div class="ui segment">
    <h4 class="ui center aligned header">Perfil do Lead</h4>
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
  <h4 class="ui center aligned header">Fluxo de Leads (<?php echo date('Y') ?>)</h4>
  <canvas id="myChart" width="900" height="220"></canvas>  
</div>
<br>
<div class="ui two column grid">
  <div class="column">
    <div class="ui segment">
      <h4 class="ui center aligned header">Principais Identificadores</h4>
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
          labels: ["'.$cid[0].'", "'.$cid[1].'", "'.$cid[2].'", "'.$cid[3].'", "Outros"],
          datasets: [
            {
              label: "Population (millions)",
              backgroundColor: ["#2185D0", "#DB2828","#FBBD08","#21BA45","#e0e1e2"],
              data: ['.$vcid[0].','.$vcid[1].','.$vcid[2].','.$vcid[3].','.$outros.'],
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
      labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
      datasets: [{
          label: 'Recebidos',
          data: [".$rjan.", ".$rfev.", ".$rmar.", ".$rabr.", ".$rmai.", ".$rjun.", ".$rjul.", ".$rago.", ".$rset.", ".$rout.", ".$rnov.", ".$rdez."],
          backgroundColor: [
            '#2185D0',
            '#2185D0',
            '#2185D0',
            '#2185D0',
            '#2185D0',
            '#2185D0',
            '#2185D0',
            '#2185D0',
            '#2185D0',
            '#2185D0',
            '#2185D0',
            '#2185D0'
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
        {
          label: 'Enviados',
          data: [".$ejan.", ".$efev.", ".$emar.", ".$eabr.", ".$emai.", ".$ejun.", ".$ejul.", ".$eago.", ".$eset.", ".$eout.", ".$enov.", ".$edez."],
          backgroundColor: [
            '#21BA45',
            '#21BA45',
            '#21BA45',
            '#21BA45',
            '#21BA45',
            '#21BA45',
            '#21BA45',
            '#21BA45',
            '#21BA45',
            '#21BA45',
            '#21BA45',
            '#21BA45'
          ],
          borderColor: [
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 0
        },
        {
          label: 'Deletados',
          data: [".$djan.", ".$dfev.", ".$dmar.", ".$dabr.", ".$dmai.", ".$djun.", ".$djul.", ".$dago.", ".$dset.", ".$dout.", ".$dnov.", ".$ddez."],
          backgroundColor: [
            '#e0e1e2',
            '#e0e1e2',
            '#e0e1e2',
            '#e0e1e2',
            '#e0e1e2',
            '#e0e1e2',
            '#e0e1e2',
            '#e0e1e2',
            '#e0e1e2',
            '#e0e1e2',
            '#e0e1e2',
            '#e0e1e2'
          ],
          borderColor: [
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 0
        }
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
      labels: ["Empreendedores", "Contadores"],
      datasets: [{
        label: "Population (millions)",
        backgroundColor: ["#2185D0", "#e0e1e2"],
        data: ['.$empreendedores.','.$contadores.'],
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

