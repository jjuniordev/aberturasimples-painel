<div class="ui grid">
  <div class="seven wide column">
    <h3 class="ui left aligned left floated header">
      Resumo
      <!-- <div class="sub header">Informações gerais sobre a sua campanha no Google AdWords.</div> -->
      <div class="sub header">Informações gerais sobre os leads da sua unidade.</div>
    </h3>
  </div>
  <div class="seven wide right floated column">
  </div>
</div>
<br>
<div id="manipular">
  <div class="ui four cards">
    <div id="cartao-lblue" class="fluid card">
      <div class="content">
        <div id="cartao-lblue" class="header">
          <p>Atendidos
          <i class="right floated phone volume inverted large icon"></i></p>
          <p id="valor-php" class="ui center aligned">
            <?php echo $dfatos[0]->valor ?>
          </p>
        </div>
      </div>
    </div>
    <div id="cartao-green" class="fluid card">
      <div class="content">
        <div id="cartao-green" class="header">
          <p>Clientes
          <i class="right floated handshake inverted large icon"></i></p>
          <p id="valor-php" class="ui center aligned">
          <?php echo $dfatos[1]->valor ?>
          </p>
        </div>
      </div>
    </div>
    <div id="cartao-red" class="fluid card">
      <div class="content">
        <div id="cartao-red" class="header">
          <p>Negativas
          <i class="right floated thumbs down large inverted icon"></i></p>
          <p id="valor-php" class="ui center aligned">
          <?php echo $dfatos[2]->valor ?>
          </p>
        </div>
      </div>
    </div>
    <div id="cartao-blue" class="fluid card">
      <div class="content">
        <div id="cartao-blue" class="header">
          <p>Conversão
          <i class="right floated trophy inverted large icon"></i></p>
          <p id="valor-php" class="ui center aligned">
            <?php 
              $total = $dfatos[1]->valor + $dfatos[2]->valor;
              if ($total == 0) {
                $porcentagem = 0;
              } else {
                $porcentagem = ($dfatos[1]->valor/$total)*100;
              }
              echo round($porcentagem) . "%";
            ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>