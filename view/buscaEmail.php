<div class="ui category search">
  <div class="ui icon mini input">
    <input class="prompt" type="text" placeholder="Buscar email...">
    <i class="search icon"></i>
  </div>
  <!-- <div class="results"></div> -->
</div>

<?php 
	$host = $_SERVER['HTTP_HOST'];
	$query = mysql_query("SELECT 
						    #a.nome,
						    a.email
						    #,b.nome_unidade
						    ,a.telefone
						    #,c.identificador
                            ,a.private_hash
						FROM
						    tb_leads a
						-- INNER JOIN
						-- 	tb_unidades b
						-- ON	
						-- 	a.id_unidade = b.id
                        -- INNER JOIN
						-- 	tb_conversoes c
						-- ON
						-- 	a.id_ultima_conversao = c.id
                            ;");

	$script_content = '<script>';
	$script_content .= 'var content = [';
	while ($email_buscado = mysql_fetch_array($query)) {
		$description = //"<i class='address card icon'></i>".utf8_encode($email_buscado['nome'])
						//."<br><i class='user icon'></i>".utf8_encode($email_buscado['nome_unidade'])
						"<i class='phone icon'></i>".$email_buscado['telefone'];
					//	."<br><i class='barcode icon'></i>".$email_buscado['identificador'];
		$url = "https://".$host."/view/profile.php?private=".$email_buscado['private_hash'];
		$script_content .= '
			{ 
				title: "'.$email_buscado['email'].'",
				description: "'.$description.'",
				url : "'.$url.'"
			},';
	}
	$script_content .= ']; </script>';

	echo $script_content;
?>

<script type="text/javascript">

$('.ui.search')
  .search({
    source : content,
    searchFields   : [
      'title'
    ],
    fullTextSearch: false,
    urlTarget: "blank",
    error : {
		  source      : 'Cannot search. No source used, and Semantic API module was not included',
		  noResults   : 'Email não cadastrado no sistema',
		  logging     : 'Error in debug logging, exiting.',
		  noTemplate  : 'A valid template name was not specified.',
		  serverError : 'There was an issue with querying the server.',
		  maxResults  : 'Results must be an array to use maxResults setting',
		  method      : 'The method you called is not defined.'
		}
  })
;

</script>
