<?php 
  include 'seguranca.php';

  $id     = $_POST['id'];
  $ini    = $_POST['ini'];
  $fim    = $_POST['fim'];

  $query = mysql_query("SELECT 
                          a.*
                          ,d.data_atividade
                          ,date_format(c.data_conversao,'%d-%m-%Y %H:%i') as data_conversao
                          ,c.identificador
                        FROM
                          tb_leads a
                        INNER JOIN 
                          tb_conversoes c
                        ON
                          a.id_ultima_conversao = c.id
                        LEFT JOIN
                          tb_atividades d
                        ON
                          a.id_ultima_conversao = d.id_conversao AND d.tipo_atividade = 4
                        WHERE
                          a.id_unidade = $id
                        AND
                          a.id_status NOT IN (1)
                        -- AND
                        --  c.origem != 'Manual'    
                        AND str_to_date(c.data_conversao,'%Y-%m-%d') 
                          BETWEEN str_to_date('".$ini."','%d/%m/%Y') and str_to_date('".$fim."','%d/%m/%Y')                    
                        GROUP BY a.id,d.data_atividade
                        ORDER BY nome ASC;");

  $query2 = mysql_query("SELECT nome_unidade FROM tb_unidades WHERE id = $id");
?>

<?php 
  $total_linhas = mysql_num_rows($query);
  $retorno = '<div id="div_modal" class="ui longer large modal">
  <i class="close icon"></i>
  <div class="header">
    Unidade '.utf8_encode(mysql_result($query2, 0)).'
  </div>
  <div class="scrolling content">
    <div id="dvData2">
      <table class="ui fixed single line celled selectable center aligned sortable very compact table">
        <thead>
          <th>Nome</th>
          <th>Email</th>
          <th>Telefone</th>
          <th>Cidade</th>
          <th class="two wide">Estado</th>
          <th>Identificador</th>
          <th>Data Conversão</th>
          <th>Data Envio</th>
        </thead>
        ';
        while ($dados = mysql_fetch_array($query)) {
          if ($dados['data_atividade'] == '') {
            $data_ativ = "-";
          } else {
            $data_ativ = $dados['data_atividade'];
          }
          $retorno .= "<tr>";
          $retorno .= "<td title='".utf8_encode($dados['nome'])."'><a href='profile.php?private=".$dados['private_hash']."' target='_blank'>".utf8_encode($dados['nome'])."</a></td>";
          $retorno .= "<td title='".$dados['email']."'>".$dados['email']."</td>";
          $retorno .= "<td title='".$dados['telefone']."'>".$dados['telefone']."</td>";
          $retorno .= "<td title='".utf8_encode($dados['cidade'])."'>".utf8_encode($dados['cidade'])."</td>";
          $retorno .= "<td>".$dados['estado']."</td>";
          $retorno .= "<td title='".$dados['identificador']."'>".$dados['identificador']."</td>";
          $retorno .= "<td title='".$dados['data_conversao']."'>".$dados['data_conversao']."</td>";
          $retorno .= "<td title='".$data_ativ."'>".$data_ativ."</td>";
          $retorno .= "</tr>";
        }
  $retorno .= '<tfoot>
                <tr>
                  <th colspan="8"><b>Total: </b>'.$total_linhas.' leads</th>
                </tr>
              </tfoot>
            </table>
      </div>
    </div>
    <div class="actions">
      <a id="btnExport2" class="ui blue button export2">Download (.csv)</a>     
    </div>
  </div>';
 $retorno .= '<script type="text/javascript" src="js/funcoes.js"></script>';
 $retorno .= '<script type="text/javascript" src="tablesort.js"></script>';

echo $retorno;

?>
