
	// Pre-reqs: 
	// 		1. O botão tem que ter o name=modal;
	//		2. Tem que ter uma div com o ID=retorno;
	function exibeModal() {
		$('[name="maisinfo"]').click(function(){
			var id = $(this).attr('id');
			$.ajax({
				url: '../model/ajaxDadosModal.php',
				type: 'POST',
				data: {
					'id': id
				},
				cache: false,
				beforeSend: function(){	
					$('#div_modal').remove(); // Remove a div para não duplicar o Modal
					$('#retorno').html(''); // Renova a div que recebe o retorno do Ajax
				},
				success: function(result) {			
					$('#retorno').html(result); // Recebe o retorno do ajax e escreve na div
					$(".test").modal('show'); // Exibe o modal
					$('#salvarlead').hide();
					$('#editarlead').click(function(){
					  var nome = $('.camponome').html();
					  //var email = $('.campoemail').html();
					  var tel = $('.campotel').html();
					  var msg = $('.campomsg').html();
					  var ident = $('.campoident').html();
					  var estado = $('.campoestado').html();
					  var cidade = $('.campocidade').html();
					  //$('.campoemail').html('<div class="ui input"><input size="60%" id="novoemail" type="text" value="'+email+'"></div>');
					  $('.camponome').html('<div class="ui input" id="novonomediv"><input size="59%" id="novonome" type="text" value="'+nome+'" autofocus></div>');
					  //$('.campoident').html('<div class="ui input" id="novoidentdiv"><input size="53%" id="novoident" type="text" value="'+ident+'"></div>');
					  $('.campotel').html('<div class="ui input" id="novoteldiv"><input size="57%" id="novotel" type="text" value="'+tel+'"></div>');
					  $('.campomsg').html('<div class="ui form"><textarea id="novamsg" rows="4">'+msg+'</textarea></div>');
					  $('.campoestado').html('<div class="ui input" id="novoestadodiv"><input size="4" id="novoestado" type="text" value="'+estado+'"></div>');
					  $('.campocidade').html('<div class="ui input" id="novocidadediv"><input size="37" id="novocidade" type="text" value="'+cidade+'"></div>');
					  $('#editarlead').hide();
					  $('#salvarlead').show(); 

						  $('#salvarlead').click(function(){
						  //var novoemail = $('#novoemail').val();
						  var novotel = $('#novotel').val();
						  var novamsg = $('#novamsg').val();
						  //var novoident = $('#novoident').val();
						  var novoestado = $('#novoestado').val();
						  var novocidade = $('#novocidade').val();
						  var novonome = $('#novonome').val();
						  if (novonome == "" || novonome.match(/^(\s)+$/)) {
						  	// Função 'match(/^(\s)+$/)' verifica se tem somente espaço em branco digitado no input
						  	alert('Favor inserir um nome válido!');
						  	$('#novonomediv').addClass('error');
						  	exit();
						  }
						  if (novotel == "" || novotel.match(/^(\s)+$/)) {
						  	alert('Favor inserir um telefone válido!');
						  	$('#novoteldiv').addClass('error');
						  	exit();
						  }
						  // if (novoident == "" || novoident.match(/^(\s)+$/)) {
						  // 	alert('Favor inserir um identificador válido!');
						  // 	$('#novoidentdiv').addClass('error');
						  // 	exit();
						  // }
						  if (novocidade == "" || novocidade.match(/^(\s)+$/)) {
						  	alert('Favor inserir uma cidade válido!');
						  	$('#novocidadediv').addClass('error');
						  	exit();
						  }
						  if (novoestado == "" || novoestado.match(/^(\s)+$/)) {
						  	alert('Favor inserir um estado válido!');
						  	$('#novoestadodiv').addClass('error');
						  	exit();
						  } 

						  $('.camponome').html(novonome);
						  $('.campotel').html(novotel);
						  $('.campomsg').html(novamsg);
						  //$('.campoident').html(novoident);
						  $('.campoestado').html(novoestado);
						  $('.campocidade').html(novocidade);
						  //$('#editarlead').show();
						  //$('#salvarlead').hide();


						  if (nome != novonome) {
						  	$.ajax({
								url: '../model/ajaxAlterarModal.php',
								type: 'POST',
								data: {
									'id': id,
									'nome': novonome,
									'nome_old': nome
								}
							});
						  }

						  if (tel != novotel) {
						  	$.ajax({
								url: '../model/ajaxAlterarModal.php',
								type: 'POST',
								data: {
									'id': id,
									'tel': novotel,
									'tel_old': tel
								}
							});
						  }		

						  if (cidade != novocidade) {
						  	$.ajax({
								url: '../model/ajaxAlterarModal.php',
								type: 'POST',
								data: {
									'id': id,
									'cidade': novocidade,
									'cidade_old': cidade
								}
							});
						  }		  

						  if (estado != novoestado) {
						  	$.ajax({
								url: '../model/ajaxAlterarModal.php',
								type: 'POST',
								data: {
									'id': id,
									'estado': novoestado,
									'estado_old': estado
								}
							});
						  }		

						  if (msg != novamsg) {
						  	$.ajax({
								url: '../model/ajaxAlterarModal.php',
								type: 'POST',
								data: {
									'id': id,
									'msg': novamsg,
									'msg_old': msg
								}
							});
						  }	  
						  $('#salvarlead').addClass('loading');
						  window.setTimeout(function(){
								window.location.reload();
							}, 1000);
						});
					});				
				}
			});
		});
	}

	$(function(){
		$("#test").click(function(){
			//$(".test").modal('show');
		});
		$(".test").modal({
			closable: true
		});
	});	

	$('#salvarlead').hide();
	$('#editarlead').click(function(){
	  var valor = $('#campo').html();
	  $('#campo').html('<input id="novo" type="text" value="'+valor+'">');
	  $('#editarlead').hide();
	  $('#salvarlead').show();  
	});

	$('#salvarlead').click(function(){
	  var novovalor = $('#novo').val();
	  alert(novovalor + ' Cadastrado com sucesso!');
	  $('#campo').html(novovalor);
	  //$('#editarlead').show();
	  //$('#salvarlead').hide();
	  //$('#salvarlead').addClass('loader');
	});
