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
          <p>Total Associados
          <i class="right floated users inverted large icon"></i></p>
          <p id="valor-php" class="ui center aligned">
            <?php echo $dadosCards->TotalAssociados; ?>
          </p>
        </div>
      </div>
    </div>
    <div id="cartao-green" class="fluid card">
      <div class="content">
        <div id="cartao-green" class="header">
          <p>Total Cidades
          <i class="right floated map inverted large icon"></i></p>
          <p id="valor-php" class="ui center aligned">
          <?php echo $dadosCards->TotalCidades; ?>
          </p>
        </div>
      </div>
    </div>
    <div id="cartao-red" class="fluid card">
      <div class="content">
        <div id="cartao-red" class="header">
          <p>Total Campanhas
          <i class="right floated google large inverted icon"></i></p>
          <p id="valor-php" class="ui center aligned">
          <?php echo $dadosCards->TotalCampanhas; ?>
          </p>
        </div>
      </div>
    </div>
    <div id="cartao-blue" class="fluid card">
      <div class="content">
        <div id="cartao-blue" class="header">
          <p>Taxa Conversão Geral
          <i class="right floated trophy inverted large icon"></i></p>
          <p id="valor-php" class="ui center aligned">
            <?php 
              echo round($dadosCards->TaxaConversao) . "%";
            ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>