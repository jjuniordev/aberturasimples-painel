<?php include 'menu.php'; ?>

<?php 
	$id_user 	= $_SESSION['usuarioID'];
	$unidade_id = $_SESSION['usuarioUnidade'];
	$permissao 	= verificarPermissao($id_user);	
	$hash 		= $_GET['private'];
	$dono_lead 	= donoDoLead($hash);
	$la 		= atividadesLead($hash);
	
	$query 	= mysql_query("SELECT 
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
                            ,b.midia
                            ,b.campaign
                            ,b.form_url
                            ,b.form_title
                            ,b.page_url
                            ,b.page_title
                            ,b.nicho_empresa
							,b.mensagem
                            ,f.faturamento
                            ,g.tipo_empresa
							,c.status
							,d.nome_unidade
							,a.private_hash
							,b.id_lead
							,a.id_status
							,e.nome as responsavel
							,e.sobrenome as responsavel_s
							FROM tb_leads a
							INNER JOIN tb_conversoes b ON a.id_ultima_conversao = b.id
							INNER JOIN tb_lead_status c ON a.id_status = c.id
							INNER JOIN tb_unidades d ON a.id_unidade = d.id
							INNER JOIN tb_usuarios e ON a.id_usuario = e.id
                            LEFT JOIN tb_chatbot_faturamento f ON b.id_faturamento_empresa = f.id
                            LEFT JOIN tb_chatbot_tipoempresa g ON b.id_tipo_empresa = g.id
							WHERE a.private_hash = '". $hash . "'");
	$linhas = mysql_num_rows($query);
	// while ($dados=mysql_fetch_array($query)) {
	// 		for ($i=0; $i < $linhas; $i++) { 
	// 			$array_dados;
	// 		}
	// }
	
	$dados 		= mysql_fetch_array($query);
	$hash_img   = md5($dados['email']);
	$rate   	= "?r=pg";
	$tam  		= "&s=512";
	$url  		= "https://www.gravatar.com/avatar/" . $hash_img . $rate . $tam . "&d=mp";
	//$public_url = 0;

	switch ($dados['status']) {
		case 'Pendente':
			$tag_status = '<span class="ui yellow mini label">Pendente</span>';
			break;
		case 'Enviado':
			$tag_status = '<span class="ui green mini label">Enviado</span>';
			break;
		case 'Deletado':
			$tag_status = '<span class="ui red mini label">Deletado</span>';
			break;		
		default:
			$tag_status = '<span class="ui yellow mini label">Pendente</span>';
			break;
	}

	# Verifica se é RD e busca a url pública
    if ($dados['origem'] != "JivoChat" || $dados['origem'] != "Manual") {
      $bquery = mysql_query("SELECT 
                                distinct a.leads_public_url
                            FROM
                                tb_rdstation a
                            INNER JOIN
                                tb_leads b 
                                ON a.leads_email = b.email
                            WHERE
                                b.id = ".$dados['id']);
      $aux_url 	  = @mysql_result($bquery, 0);
      $public_url = (!isset($aux_url)) ? "-" : $aux_url;
      $texto_link = "https://".$host."/public/";
    } else {
      $public_url = "";
      $texto_link = "";
    }

    # Função que lista as unidades dentro do Select dono do lead
	function listarUnidadesInput($valor_antigo) {
			$query = mysql_query("SELECT id, nome_unidade FROM tb_unidades WHERE esta_ativo = 1 ORDER BY nome_unidade ASC");

			$input = "<div class='ui small input form'><select id='unidades' class='ui dropdown'>";
			while ($unidades = mysql_fetch_array($query)) {
				
				if ($valor_antigo == utf8_encode($unidades['nome_unidade'])) {
					$default = "selected";
				} else {
					$default = "";
				}

				$input .= "<option name='".$unidades['id']."' ".$default.">" .utf8_encode($unidades['nome_unidade']). "</option>";
			}
			$input .= "</select></div>";

			return $input;
		}
?>

<div class="ui container">
	<h1 class="ui center aligned header"><i class="id badge icon"></i>Perfil do Lead</h1>
	<div class="ui divider"></div>
	
	<div class="ui two column grid">
		<div class="column">
			<div class="ui segment" id="segment">
				<div class="ui items">
				  <div class="item">
				    <a class="ui small image">
				      <?php echo '<img src="'.$url.'">'; ?>
				    </a>
				    <div class="content">
				    <table border="0" width="100%" class="header">
				    	<tr>
				    		<td width="100%">
				    			<br><?php echo utf8_encode($dados['nome']); ?>
				    		</td>
				    		<td>
				    			<span>
						    		<?php 
						        		if ($permissao <= 3) {
						        			echo '<button class="ui labeled icon right floated blue tiny button" id="editar_info">
													  <i class="pencil icon"></i>
													  Editar
													</button>';
						        		} elseif ($dono_lead == $unidade_id) {
						        			echo '<button class="ui labeled icon right floated blue tiny button" id="editar_info">
													  <i class="pencil icon"></i>
													  Editar
													</button>';
						        		}
						        	?>
									<div class="ui tiny buttons" id="salvar_cancelar">
									  <button class="ui green button" id="salvar_info">Salvar</button>
									  <!-- <div class="or" data-text="ou"></div> -->
									  <button class="ui button" id="cancelar_info">Cancelar</button>
									</div>
						    	</span>
				    		</td>
				    	</tr>
				    </table>	
				    <div class="description">
						
				        <p><i class="calendar alternate grey icon"></i><b>Data: </b><?php echo date_format(date_create($dados['data_conversao']), 'd/m/Y - H:i'); ?> </p>
				        <p><i class="home grey icon"></i><b>Unidade: </b><span id="dono_do_lead"><?php echo utf8_encode($dados['nome_unidade']); ?></span></p>
				        <p><i class="user grey icon"></i><b>Dono do Lead: </b>
				        	<?php 
				        		if ($dados['responsavel'] == "Sistema") {
				        			$resp = "-";
				        		} else {
				        			$resp = utf8_encode($dados['responsavel']) . " " . utf8_encode($dados['responsavel_s']);
				        		}
				        		echo $resp;
			        		?>
			        	</p>
				        <p><i class="tag grey icon"></i><b>Status: </b><?php echo $tag_status; ?></p>
				        
				      </div>
				    </div>
				  </div>
				</div>
				<h4 class="ui center aligned header">Dados Pessoais</h4>
				<div class="ui container">
					<div class="ui medium aligned divided list">
					  <div class="item">
					  	<div class="right floated content">
					  		<!-- Conteúdo à direita -->
					    </div>
					    <i class="id card grey middle aligned icon"></i>
					    <div class="content">
					      <b>Nome: </b><span class="nome"><?php echo utf8_encode($dados['nome']); ?></span>
					    </div>
					    <br>
					  </div>
					  <div class="item" id="esconder_input">
					  	<br>
					  	<div class="right floated content">
					  		<!-- Conteúdo à direita -->
					    </div>
					    <i class="envelope grey middle aligned icon"></i>
					    <div class="content">
					      <b>ID: </b><span class="id_lead"><?php echo $dados['id']; ?></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br>
					  	<div class="right floated content">
					  		<!-- Conteúdo à direita -->
					    </div>
					    <i class="envelope grey middle aligned icon"></i>
					    <div class="content">
					      <b>Email: </b><span class="email"><?php echo $dados['email']; ?></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br>
					  	<div class="right floated content">
					  		<!-- Conteúdo à direita -->
					    </div>
					    <i class="phone grey middle aligned icon"></i>
					    <div class="content">
					      <b>Telefone: </b><span class="telefone"><?php echo $dados['telefone']; ?></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br>
					  	<div class="right floated content">
					  		<!-- Conteúdo à direita -->
					    </div>
					    <i class="map marker grey middle aligned icon"></i>
					    <div class="content">
					      <b>Cidade: </b><span class="cidade"><?php echo utf8_encode($dados['cidade']); ?></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br>
					  	<div class="right floated content">
					  		<!-- Conteúdo à direita -->
					    </div>
					    <i class="map signs grey middle aligned icon"></i>
					    <div class="content">
					      <b>Estado: </b><span class="estado"><?php echo utf8_encode($dados['estado']); ?></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br><br>
					  	<h4 class="ui center aligned header">Detalhes</h4>					  	
					  	<br>
					  	<!-- <div class="right floated content">
					  		Conteúdo à direita
					    </div> -->
					    <i class="building grey middle aligned icon"></i>
					    <div class="content">
					      <b>Tipo de Empresa: </b><span class="origem"><?php $tipo_empresa = (empty($dados['tipo_empresa'])) ? "-" : $dados['tipo_empresa']; echo utf8_encode($tipo_empresa); ?></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br>
					    <i class="briefcase grey middle aligned icon"></i>
					    <div class="content">
					      <b>Atividade: </b><span class="ident"><?php $atividade = (empty($dados['nicho_empresa'])) ? "-" : $dados['nicho_empresa']; echo $atividade; ?></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br>
					    <i class="money grey middle aligned icon"></i>
					    <div class="content">
					      <b>Estimativa de Faturamento: </b><span class="ident"><?php $faturamento = (empty($dados['faturamento'])) ? "-" : $dados['faturamento']; echo utf8_encode($faturamento); ?></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br>
					  	<div class="right floated content">
					  		<!-- Conteúdo à direita -->
					    </div>
					    <i class="comment alternate grey middle aligned icon"></i>
					    <div class="content">
					      <b>Mensagem: </b><span class="mensagem"><?php $mensagem = (empty($dados['mensagem'])) ? "-" : $dados['mensagem']; echo utf8_encode($mensagem); ?></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br><br>
					  	<h4 class="ui center aligned header">Rastreamento</h4>
					  	<br>
					    <i class="barcode grey middle aligned icon"></i>
					    <div class="content">
					      <b>Identificador: </b><span class="ident"><?php $identificador = (empty($dados['identificador'])) ? "-" : $dados['identificador']; echo utf8_encode($identificador); ?></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br>
					    <i class="compass grey middle aligned icon"></i>
					    <div class="content">
					      <b>Origem: </b><span class="ident"><?php $origem = (empty($dados['origem'])) ? "-" : $dados['origem']; echo utf8_encode($origem); ?></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br>
					    <i class="at grey middle aligned icon"></i>
					    <div class="content">
					      <b>Mídia: </b><span class="ident"><?php $midia = (empty($dados['midia'])) ? "-" : $dados['midia']; echo utf8_encode($midia); ?></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br>
					    <i class="bullhorn grey middle aligned icon"></i>
					    <div class="content">
					      <b>Campanha: </b><span class="ident"><?php $campaign = (empty($dados['campaign'])) ? "-" : $dados['campaign']; echo utf8_encode($campaign); ?></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br>
					    <i class="clipboard grey middle aligned icon"></i>
					    <div class="content">
					      <b>Formulário: </b><span class="ident"><a href="<?php echo $dados['form_url']; ?>" target="_blank"><?php $form_title = (empty($dados['form_title'])) ? "-" : $dados['form_title']; echo utf8_encode($form_title); ?></a></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br>
					    <i class="file alternate grey middle aligned icon"></i>
					    <div class="content">
					      <b>Página: </b><span class="ident"><a href="<?php echo $dados['page_url']; ?>" target="_blank"><?php $page_title = (empty($dados['page_title'])) ? "-" : $dados['page_title']; echo $page_title; ?></a></span>
					    </div>
					    <br>
					  </div>
					  <div class="item">
					  	<br>
					    <i class="link grey icon"></i>
					    <div class="content">
					      <?php echo '<b>URL pública: <a href="'.$public_url.'" target="_blank">'.$texto_link.'</a></b>'; ?>
					    </div>
					    <br>
					  </div>



					  <div class="item">
					  	<br>
					    <i class="lock grey icon"></i>
					    <div class="content">
					      <?php echo '<b>URL privada: <a href="profile.php?private='.$dados['private_hash'].'" target="_blank">https://'.$host.'/private/</a></b>'; ?>
					    </div>
					    <br>
					  </div>
					</div>
				</div>
			</div>
		</div>
		<div class="column">
		<?php 

			if ($permissao <= 3 ) {
				echo '<div class="ui threaded comments container"> 
					<div class="ui segment"> 
					<h4 class="ui center aligned header">Adicionar Comentários</h4>
					  <form class="ui form">
					    <div class="field">
					      <textarea rows="3" placeholder="Escreva um comentário..." id="comentario"></textarea>
					    </div>
					    <div class="ui green labeled submit icon small right floated button" id="botaocomentar">
					      <i class="icon edit"></i> Comentar
					    </div>
					  </form>
					  <br><br>
					  </div>
					  
					<div class="ui segment" id="divcomments">';
					echo $la;
					echo "<script>
							$('#botaocomentar').click(function(){
								var comentario = $('#comentario').val();
								if (comentario == '' || comentario.match(/^(\s)+$/)) {
									alert('Digite um comentário válido!');
									exit();
								}
								$.ajax({
									url: '../model/ajaxCadastrarComentario.php',
									type: 'POST',
									data: {
										'comentario': comentario,
										'hash': '".$hash."'
									},
									success: function(msg) { 
										$('textarea').val('');
								        $('#divcomments').fadeOut(500,function(){
								        	$('#divcomments').html(msg).fadeIn().delay(500);
							        	});
								    }
								});
							});
							
						</script>
						</div>
						<br><br><br>
					</div>";
			} elseif ($dono_lead == $unidade_id) {
				echo '<div class="ui threaded comments container">  
					<div class="ui segment"> 
					<h4 class="ui center aligned header">Adicionar Comentários</h4>
					  <form class="ui form">
					    <div class="field">
					      <textarea rows="3" placeholder="Escreva um comentário..." id="comentario"></textarea>
					    </div>
					    <div class="ui green labeled submit icon small right floated button" id="botaocomentar">
					      <i class="icon edit"></i> Comentar
					    </div>
					  </form>
					  <br><br>
					  </div>
					<div class="ui segment" id="divcomments">';
					echo $la;
					echo "<script>
							$('#botaocomentar').click(function(){
								var comentario = $('#comentario').val();
								if (comentario == '' || comentario.match(/^(\s)+$/)) {
									alert('Digite um comentário válido!');
									exit();
								}
								$.ajax({
									url: '../model/ajaxCadastrarComentario.php',
									type: 'POST',
									data: {
										'comentario': comentario,
										'hash': '".$hash."'
									},
									success: function(msg) { 
										$('textarea').val('');
								        $('#divcomments').fadeOut(500,function(){
								        	$('#divcomments').html(msg).fadeIn().delay(500);
							        	});
								    }
								});
							});
						</script>
						</div>
						<br><br><br>
					</div>";
			} else {
				echo '<div class="ui threaded comments container">  					  
					<div class="ui segment" id="divcomments">';
					echo $la;
					echo "<script>
							$('#botaocomentar').click(function(){
								var comentario = $('#comentario').val();
								if (comentario == '' || comentario.match(/^(\s)+$/)) {
									alert('Digite um comentário válido!');
									exit();
								}
								$.ajax({
									url: '../model/ajaxCadastrarComentario.php',
									type: 'POST',
									data: {
										'comentario': comentario,
										'hash': '".$hash."'
									},
									success: function(msg) { 
										$('textarea').val('');
								        $('#divcomments').fadeOut(500,function(){
								        	$('#divcomments').html(msg).fadeIn().delay(500);
							        	});
								    }
								});
							});

						</script>
						</div>
						<br><br><br>
					</div>";
			}

		?>
		</div>
	</div>
	<br>
	

<script type="text/javascript">
	$('#esconder_input').hide();
	$('#salvar_cancelar').hide();
	$('#editar_info').click(function(){
		$('#salvar_cancelar').show();
		$('#editar_info').hide();
		var antigo_nome = $('.nome').html();
		var antigo_tel = $('.telefone').html();
		var antigo_cidade = $('.cidade').html();
		var antigo_estado = $('.estado').html();
		var antigo_mensagem = $('.mensagem').html();
		var antigo_dono = $('#dono_do_lead').html();
		// var antigo_ident = $('.ident').html();
		$('.nome').html("<div class='ui input' id='novonomediv'><input id='novo_nome' size='35' type='text' value='"+antigo_nome+"' autofocus></div>");
		$('.telefone').html("<div class='ui input' id='novoteldiv'><input id='novo_tel' size='34' type='text' value='"+antigo_tel+"'></div>");
		$('.cidade').html("<div class='ui input' id='novocidadediv'><input id='novo_cidade' size='34' type='text' value='"+antigo_cidade+"'></div>");
		$('.estado').html("<div class='ui input' id='novoestadodiv'><input id='novo_estado' size='34' type='text' value='"+antigo_estado+"'></div>");
		$('.mensagem').html("<div class='ui form'><textarea id='novo_msg' style='width:450px' rows='4'>"+antigo_mensagem+"</textarea></div>");
		// $('.ident').html("<div class='ui input'><input size='69' type='text' value='"+antigo_ident+"'></div>");
		//alert(antigo_dono);
		// $('#dono_do_lead').html("<div class='ui input' id='novodonodiv'><input id='novo_dono' size='25' type='text' value='"+antigo_dono+"'></div>");
		$('#dono_do_lead').html("<?php $verunid = listarUnidadesInput(utf8_encode($dados['nome_unidade'])); echo $verunid; ?>");
		$('#cancelar_info').click(function(){
			window.location.reload();
			exit();
		});
		$('#salvar_info').click(function(){
			var id = $('.id_lead').html();
			var novo_nome = $('#novo_nome').val();
			var novo_tel = $('#novo_tel').val();
			var novo_cidade = $('#novo_cidade').val();
			var novo_estado = $('#novo_estado').val();
			var novo_msg = $('#novo_msg').val();
			var novo_unidade = $('#unidades option:selected').attr('name');
			var novo_unidade_nome = $('#unidades option:selected').val();

			if (novo_nome == "" || novo_nome.match(/^(\s)+$/)) {
			  	// Função 'match(/^(\s)+$/)' verifica se tem somente espaço em branco digitado no input
			  	alert('Favor inserir um nome válido!');
			  	$('#novonomediv').addClass('error');
			  	exit();
			  }
			  if (novo_tel == "" || novo_tel.match(/^(\s)+$/)) {
			  	alert('Favor inserir um telefone válido!');
			  	$('#novoteldiv').addClass('error');
			  	exit();
			  }
			  if (novo_cidade == "" || novo_cidade.match(/^(\s)+$/)) {
			  	alert('Favor inserir uma cidade válido!');
			  	$('#novocidadediv').addClass('error');
			  	exit();
			  }
			  if (novo_estado == "" || novo_estado.match(/^(\s)+$/)) {
			  	alert('Favor inserir um estado válido!');
			  	$('#novoestadodiv').addClass('error');
			  	exit();
			  }
			  if (antigo_nome != novo_nome) {
			  	$.ajax({
					url: '../model/ajaxAlterarProfile.php',
					type: 'POST',
					data: {
						'id': id,
						'nome': novo_nome,
						'nome_old': antigo_nome
					}
				});
			  } 
			  if (antigo_tel != novo_tel) {
			  	$.ajax({
					url: '../model/ajaxAlterarProfile.php',
					type: 'POST',
					data: {
						'id': id,
						'tel': novo_tel,
						'tel_old': antigo_tel
					}
				});
			  }
			  if (antigo_cidade != novo_cidade) {
			  	$.ajax({
					url: '../model/ajaxAlterarProfile.php',
					type: 'POST',
					data: {
						'id': id,
						'cidade': novo_cidade,
						'cidade_old': antigo_cidade
					}
				});
			  }
			  if (antigo_estado != novo_estado) {
			  	$.ajax({
					url: '../model/ajaxAlterarProfile.php',
					type: 'POST',
					data: {
						'id': id,
						'estado': novo_estado,
						'estado_old': antigo_estado
					}
				});
			  }
			  if (antigo_mensagem != novo_msg) {
			  	$.ajax({
					url: '../model/ajaxAlterarProfile.php',
					type: 'POST',
					data: {
						'id': id,
						'msg': novo_msg,
						'msg_old': antigo_mensagem
					}
				});
			  }
			  if (antigo_dono != novo_unidade_nome) {
			  	$.ajax({
					url: '../model/ajaxAlterarProfile.php',
					type: 'POST',
					data: {
						'id': id,
						'id_unidade': novo_unidade,
						'id_unidade_old': antigo_dono,
						'unidade_nome': novo_unidade_nome
					}
				});
			  }
			$('#segment').addClass('loading');
			window.setTimeout(function(){
				window.location.reload();
			}, 1500);
		});
	});
</script>

