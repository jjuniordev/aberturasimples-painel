<?php 
	include '../model/seguranca.php';
	include '../model/funcoes.php';

	$id = $_SESSION['usuarioID'];
	$unidade_id = $_SESSION['usuarioUnidade'];


	if (isset($_POST['ini']) && isset($_POST['fim'])) {
	  $ini = $_POST['ini'];
	  $fim = $_POST['fim'];

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
            a.account_id = b.google_id
	    INNER JOIN
            tb_usuarios c
	    ON
            c.id_unidade = b.id
	    WHERE
            c.id = $id
		AND
            str_to_date(a.periodo, '%Y-%m-%d') 
            BETWEEN str_to_date('$ini','%d/%m/%Y') AND str_to_date('$fim','%d/%m/%Y')
	    GROUP BY 
            a.account_name;
	        ");

	    $dados = mysql_fetch_array($query);
	    $conversoes = getConversoes($unidade_id);

	    if ($conversoes == 0) {
                $cpl = 0;
              } else {
                $cpl = $dados['custo']/$conversoes;
              }


	  $retorno = '<div class="ui four cards">
					  <div id="cartao-blue" class="fluid card">
					    <div class="content">
					      <div id="cartao-blue" class="header">
					        <p>Clicks
					        <i class="right floated hand point right inverted large icon"></i></p>
					        <p id="valor-php" class="ui center aligned">
					          '.number_format($dados["cliques"],0,",",".").'
					        </p>
					      </div>
					    </div>
					  </div>
					  <div id="cartao-yellow" class="fluid card">
					    <div class="content">
					      <div id="cartao-yellow" class="header">
					        <p>Custo
					        <i class="right floated credit card outline inverted large icon"></i></p>
					        <p id="valor-php2" class="ui center aligned">R$ '.number_format($dados["custo"],2,",",".").'</p>
					      </div>
					    </div>
					  </div>
					  <div id="cartao-red" class="fluid card">
					    <div class="content">
					      <div id="cartao-red" class="header">
					        <p>Conversões
					        <i class="right floated sync alternate large inverted icon"></i></p>
					        <p id="valor-php" class="ui center aligned">'.$conversoes.'</p>
					      </div>
					    </div>
					  </div>
					  <div id="cartao-green" class="fluid card">
					    <div class="content">
					      <div id="cartao-green" class="header">
					        <p>Custo por Conversão
					        <i class="right floated credit card inverted large icon"></i></p>
					        <p id="valor-php2" class="ui center aligned">R$ '. number_format($cpl,2,",",".").'</p>
					      </div>
					    </div>
					  </div>
					</div>';

		echo $retorno;
	}


	

 ?>