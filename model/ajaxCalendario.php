<?php 

  include 'seguranca.php';

  if (!isset($_POST['ini']) || $_POST['ini'] == "") {
    $ini = 0;
  } else {
    $ini = $_POST['ini'];  
  }

  if (!isset($_POST['fim']) || $_POST['fim'] == "") {
    $fim = 0;
  } else {
    $fim = $_POST['fim'];  
  }

  
$query = mysql_query("
            SELECT 
                *,
                date_format(data,'%d/%m/%Y - %Hh%i') as data_limpa
            FROM
                tb_leads
            WHERE 
                date_format(data,'%d/%m/%Y') >= '".$ini."'
            AND
                date_format(data,'%d/%m/%Y') <= '".$fim."'
            ORDER BY id
            ");


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


    //return $tabela;
    echo $tabela;


 ?>

