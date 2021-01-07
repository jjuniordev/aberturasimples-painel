<?php 
  include 'seguranca.php';

  $id     = $_POST['id'];
  $host = $_SERVER['HTTP_HOST'];

  $query = mysql_query("SELECT 
                          a.id
                          ,b.data_conversao
                          ,date_format(b.data_conversao,'%d/%m/%y') as data_limpa
                          ,a.nome
                          ,a.email
                          ,a.telefone
                          ,a.cidade
                          ,a.estado
                          ,b.identificador
                          ,b.origem
                          ,b.mensagem
                          ,c.status
                          ,d.nome_unidade
                          ,a.private_hash
                          ,e.nome as responsavel
                          ,e.sobrenome as responsavel_n
                          FROM tb_leads a
                          INNER JOIN tb_conversoes b ON a.id_ultima_conversao = b.id
                          INNER JOIN tb_lead_status c ON a.id_status = c.id
                          INNER JOIN tb_unidades d ON a.id_unidade = d.id
                          INNER JOIN tb_usuarios e ON a.id_usuario = e.id
                          -- WHERE d.id != 1
                          WHERE a.id =".$id);

  while ($dados = mysql_fetch_array($query)) {
    $nome   = $dados['nome'];
    $email  = $dados['email'];
    $tel    = $dados['telefone'];
    $cidade = ($dados['cidade'] == "") ? "-" : $dados['cidade'];
    $estado = ($dados['estado'] == "") ? "" : $dados['estado'];
    $msg    = $dados['mensagem'];
    $origem = $dados['origem'];
    $ident  = $dados['identificador'];
    $data   = $dados['data_conversao'];
    $unidade = $dados['nome_unidade'];
    $private_hash = $dados['private_hash'];
    if ($dados['responsavel'] == "Sistema") {
      $responsavel = "-";
    } else {
      $responsavel = $dados['responsavel']." ".$dados['responsavel_n'];
    }
  }

  # Verifica se é RD e busca a url pública
    if ($origem != "" || $origem != " ") {
      $query = mysql_query("SELECT 
                                distinct a.leads_public_url
                            FROM
                                tb_rdstation a
                            INNER JOIN
                                tb_leads b 
                                ON a.leads_email = b.email
                            WHERE
                                b.id = ".$id);
      $aux_url    = @mysql_result($query, 0);
      $public_url = (!isset($aux_url) ? "" : $aux_url);
      $texto_link = "https://rdstation.com.br/leads/public/";
    } else {
      $public_url = "";
      $texto_link = "";
    }

  $hash   = md5($email);
  $rate   = "?r=pg";
  $tam  = "&s=512";
  $url  = "https://www.gravatar.com/avatar/" . $hash . $rate . $tam . "&d=mp";

  $retorno = '<div id="div_modal" class="ui modal test">
                <i class="close icon"></i>
                <div class="header">
                  '.utf8_encode($nome).'
                </div>
                <div class="image content">
                  <div class="ui medium image">
                    <img src="'.$url.'">
                  </div>
                  <div class="description">
                    <div class="ui header">Informações adicionais do lead</div>
                    <p><i class="calendar alternate grey icon"></i><b>Data:</b> '.date_format(date_create($data), 'd/m/Y - H:i').'</p>
                    <p><i class="id card grey icon"></i><b>Nome: </b><span class="camponome">'.utf8_encode($nome).'</span></p>
                    <p><i class="envelope grey icon"></i><b>Email: </b><span class="campoemail">'.$email.'</span></p>
                    <p><i class="phone grey icon"></i><b>Telefone: </b><span class="campotel">'.$tel.'</span></p>
                    <p><i class="map marker alternate grey icon"></i><b>Cidade: </b><span class="campocidade">'.utf8_encode($cidade).'</span> | <i class="map grey signs icon"></i><b>Estado: </b><span class="campoestado">'.utf8_encode($estado).'</span></p>
                    <p><i class="barcode grey icon"></i><b>Identificador: </b><span class="campoident">'.$ident.'</span></p>
                    <p><i class="home grey icon"></i><b>Unidade: </b><span class="campounidade">'.utf8_encode($unidade).'</span></p>
                    <p><i class="user grey icon"></i><b>Dono do Lead: </b><span class="campouresp">'.utf8_encode($responsavel).'</span></p>
                    <p><i class="compass grey icon"></i><b>Origem: </b><span class="campoorigem">'.$origem.'</span></p>                    
                    <p><i class="linkify grey icon"></i><b>URL pública: <a href="'.$public_url.'" target="_blank">'.$texto_link.'</a></b></p>
                    <p><i class="lock grey icon"></i><b>URL privada: <a href="profile.php?private='.$private_hash.'" target="_blank">http://'.$host.'/view/private/</a></b></p>
                    <p><i class="comment alternate grey icon"></i><b>Mensagem: </b><span class="campomsg">'.utf8_encode($msg).'</span></p>
                  </div>
                </div>
                <div class="actions">
                  <a href="profile.php?private='.$private_hash.'" class="ui labeled icon left floated button">
                    <i class="id card icon"></i> 
                    Perfil do Lead
                  </a>                  
                  <div id="salvarlead" class="ui labeled icon green button">
                  <i class="save icon"></i>
                    Salvar                    
                  </div>
                  <div id="editarlead" class="ui labeled icon blue button">
                  <i class="pencil icon"></i>
                    Editar                    
                  </div>
                  <div class="ui deny black button">
                    Fechar
                  </div>
                </div>
              </div>
              ';

  echo $retorno;

?>