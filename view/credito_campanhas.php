<?php 

  include('menu.php'); 
  $id_user = $_SESSION['usuarioID'];
  $unidade_id = $_SESSION['usuarioUnidade'];
  //protegePagina();
  $permissao  = verificarPermissao($id_user); 

  if ($permissao >= 4) {
    echo "Você não tem permissão para acessar esta página, <a href='index.php'>clique aqui</a> para voltar ao painel.";
    exit();
  }

  function getCustoCampanha($google_id) {
    $query = mysql_query("select sum(custo) from tb_google_fato_ads where account_id = '".$google_id."'");

    $custo = mysql_result($query, 0);

    if ($custo == '' || is_null($custo)) {
        $custo = 0;
      }  

    return $custo;
  }

  function saldoNaTela() {
    $query = mysql_query("SELECT 
                              a.nome_unidade
                              ,trim(a.google_id) as Google_ID
                              ,b.credito
                              ,b.valor_final
                              ,b.status
                              ,a.id
                          FROM
                              tb_unidades a
                          LEFT JOIN
                            tb_campanhas b
                          ON
                            trim(a.google_id) = trim(b.google_id)
                          WHERE
                              esta_ativo = 1
                          AND
                              a.google_id != ''
                          ORDER BY a.nome_unidade ASC;");

     $campanhas = "<div id='result'></div><table class='ui center aligned very compact celled selectable sortable table lista-clientes'>";
     $campanhas .= "<thead>";
     $campanhas .= "<th>Nome unidade</th>";
     $campanhas .= "<th>Google ID</th>";
     $campanhas .= "<th>Saldo</th>";
     $campanhas .= "<th>Credito</th>";
     $campanhas .= "</thead>";

     while ($unidades = mysql_fetch_array($query)) {
       $custo = getCustoCampanha($unidades['Google_ID']);
       $saldo = $unidades['valor_final'] - $custo;
       if ($saldo <= 0) {
         $saldo = 0;
       }
       $campanhas .= "<tr>";
       $campanhas .= "<td>".utf8_encode($unidades['nome_unidade'])."</td>";
       $campanhas .= "<td>".substr($unidades['Google_ID'],0,3)."-".substr($unidades['Google_ID'],3,3)."-".substr($unidades['Google_ID'],6,4)."</td>";
       $campanhas .= "<td>R$ ".number_format($saldo,2,',','.')."</td>";
       $campanhas .= "<td class='four wide'><div class='ui action left icon small input'><i class='dollar icon'></i><input class='poe_credito' placeholder='0,00' size='10' type='text' id='".$unidades['Google_ID']."'><button class='ui small button' id='".$unidades['id']."'>Creditar</button></div></td>";
       $campanhas .= "</tr>";
       $campanhas .= "<script>
                          $('#".$unidades['id']."').click(function() {
                            var credito2 = $('#".$unidades['Google_ID']."').val();
                            var credito3 = credito2.replace('.', '');
                            var credito = credito3.replace(',', '.');
                            $.ajax({
                              url: '../model/ajaxCreditarCampanha.php',
                              type: 'POST',
                              data: {
                                'credito': credito,
                                'google_id': '".$unidades['Google_ID']."' ,
                              },
                                success: function(retorno) {
                                  window.setTimeout(function() {
                                    location.reload();
                                  }, 500);
                                  //$('#result').html(retorno);
                                }
                            });
                          });
                      </script>";
     }

     $campanhas .= "</table>";
     echo $campanhas;
  }

  //saldoNaTela();
  

?>
 <div class="ui container">
  <?php include 'sub_menu_campanhas.php'; ?>
  <div class="ui grid">
    <div class="eleven wide column">
      <h3 class="ui header">
          Saldo das Campanhas
          <div class="sub header">Lista com campanhas ativas para consulta de saldo e input de credito.</div>
      </h3>
    </div>
  </div>
  <div class="ui segment">
    <div class="ui grid">
    <div class="seven wide column">
      <div class="ui right icon small fluid input">
        <i class="search icon"></i>
        <input type="text" id="pesquisa" class="input-search" alt="lista-clientes" placeholder="Buscar...">
      </div>
    </div>
      <div class="two wide right floated column">
        <div class="ui labeled icon top middle pointing dropdown basic right floated small button">
          <i class="cog icon"></i>
          <span class="text">Opções</span>
          <div class="menu">
          </div>
        </div>
      </div>
  </div>
    <?php saldoNaTela(); ?>
  </div>  
  <br><br>
</div>

<script src="tablesort.js"></script>

<script language="javascript">   
  function moeda(a, e, r, t) {
      let n = ""
        , h = j = 0
        , u = tamanho2 = 0
        , l = ajd2 = ""
        , o = window.Event ? t.which : t.keyCode;
      if (13 == o || 8 == o)
          return !0;
      if (n = String.fromCharCode(o),
      -1 == "0123456789-".indexOf(n))
          return !1;
      for (u = a.value.length,
      h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
          ;
      for (l = ""; h < u; h++)
          -1 != "0123456789-".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
      if (l += n,
      0 == (u = l.length) && (a.value = ""),
      1 == u && (a.value = "0" + r + "0" + l),
      2 == u && (a.value = "0" + r + l),
      u > 2) {
          for (ajd2 = "",
          j = 0,
          h = u - 3; h >= 0; h--)
              3 == j && (ajd2 += e,
              j = 0),
              ajd2 += l.charAt(h),
              j++;
          for (a.value = "",
          tamanho2 = ajd2.length,
          h = tamanho2 - 1; h >= 0; h--)
              a.value += ajd2.charAt(h);
          a.value += r + l.substr(u - 2, u)
      }
      return !1
  }

  $('.poe_credito').keypress(function(){
    return(moeda(this,'.',',',event));
  });
</script> 

<script>  
  $('.ui.dropdown')
  .dropdown({
    action: 'hide'
  })
;
</script>

<script type="text/javascript">
  $(function buscaTabela(){
  $(".input-search").keyup(function(){
  //pega o css da tabela 
  var tabela = $(this).attr('alt');
  if( $(this).val() != ""){
      $("."+tabela+" tbody>tr").hide();
      $("."+tabela+" td:contains-ci('" + $(this).val() + "')").parent("tr").show();
  } else{
      $("."+tabela+" tbody>tr").show();
  }
  }); 
  });
  $.extend($.expr[":"], {
  "contains-ci": function(elem, i, match, array) {
      return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
  }
  });
</script>