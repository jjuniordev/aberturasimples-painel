
  // FUNÇÃO PARA BUSCAR OS DADOS QUE ESTÃO CHECKADOS (ID)
	function setFollow() {
	  var pacote   = document.querySelectorAll('[name=Pacote]:checked');
	  var values   = [];
    var nome     = $('#tags').val();
	  for (var i = 0; i < pacote.length; i++) {
	    // utilize o valor aqui, adicionei ao array para exemplo
	    values.push(pacote[i].value);
	  }
    // AJAX PARA ATRIBUIR OS LEADS SELECIONADOS PARA A UNIDADE ENVIADA
    $.ajax({
        url: '../model/ajaxSetFollow.php',
        type: 'POST',
        data: {
          'valores': values,
        },
        beforeSend: function(){	
				$('#follow').addClass('loading');	    
			  },
        success: function(data) { //Se a requisição retornar com sucesso, 
			//ou seja, se o arquivo existe, entre outros
	        //$('#sucesso').html(data); //Parte do seu 
	        setTimeout(function(){
		    	$('#follow').removeClass('loading');
		    	//$(window).attr('location','leads_atribuidos.php');
		    	location.reload();
		    },1500);
	    }
      });
	}

  function setCliente() {
		var pacote   = document.querySelectorAll('[name=Pacote]:checked');
		var values   = [];
		var nome     = $('#tags').val();
	  for (var i = 0; i < pacote.length; i++) {
	    // utilize o valor aqui, adicionei ao array para exemplo
	    values.push(pacote[i].value);
	  }
    // AJAX PARA ATRIBUIR OS LEADS SELECIONADOS PARA A UNIDADE ENVIADA
    $.ajax({
        url: '../model/ajaxSetCliente.php',
        type: 'POST',
        data: {
          'valores': values,
        },
        beforeSend: function(){	
				$('#cliente').addClass('loading');	    
			  },
        success: function(data) { //Se a requisição retornar com sucesso, 
			//ou seja, se o arquivo existe, entre outros
	        //$('#sucesso').html(data); //Parte do seu 
	        setTimeout(function(){
		    	$('#cliente').removeClass('loading');
		    	//$(window).attr('location','leads_atribuidos.php');
		    	location.reload();
		    },1500);
	    }
      });
    //location.reload();
	}

	function setRejeitado() {
		var pacote   = document.querySelectorAll('[name=Pacote]:checked');
		var values   = [];
		var nome     = $('#tags').val();
	  for (var i = 0; i < pacote.length; i++) {
	    // utilize o valor aqui, adicionei ao array para exemplo
	    values.push(pacote[i].value);
	  }
    // AJAX PARA ATRIBUIR OS LEADS SELECIONADOS PARA A UNIDADE ENVIADA
    $.ajax({
        url: '../model/ajaxSetRejeitado.php',
        type: 'POST',
        data: {
          'valores': values,
        },
        beforeSend: function(){	
				$('#rejeitado').addClass('loading');	    
			  },
        success: function(data) { //Se a requisição retornar com sucesso, 
			//ou seja, se o arquivo existe, entre outros
	        //$('#sucesso').html(data); //Parte do seu 
	        setTimeout(function(){
		    	$('#rejeitado').removeClass('loading');
		    	//$(window).attr('location','leads_atribuidos.php');
		    	location.reload();
		    },1500);
	    }
      });
    //location.reload();
	}

	function setDeletado() {
		var pacote   = document.querySelectorAll('[name=Pacote]:checked');
		var values   = [];
		var nome     = $('#tags').val();
	  for (var i = 0; i < pacote.length; i++) {
	    // utilize o valor aqui, adicionei ao array para exemplo
	    values.push(pacote[i].value);
	  }
    // AJAX PARA ATRIBUIR OS LEADS SELECIONADOS PARA A UNIDADE ENVIADA
    $.ajax({
        url: '../model/ajaxSetDeletado.php',
        type: 'POST',
        data: {
          'valores': values,
        },
        beforeSend: function(){	
				$('#deletar').addClass('loading');	    
			  },
        success: function(data) { //Se a requisição retornar com sucesso, 
			//ou seja, se o arquivo existe, entre outros
	        //$('#sucesso').html(data); //Parte do seu 
	        setTimeout(function(){
		    	$('#deletar').removeClass('loading');
		    	//$(window).attr('location','leads_atribuidos.php');
		    	location.reload();
		    },1500);
	    }
      });
    //location.reload();
	}
   
	var follow = document.getElementById('follow');
	var cliente = document.getElementById('cliente');
	var rejeitado = document.getElementById('rejeitado');
	var deletar = document.getElementById('deletar');
	follow.addEventListener('click', setFollow, false);
	cliente.addEventListener('click', setCliente, false);
	rejeitado.addEventListener('click', setRejeitado, false);
	deletar.addEventListener('click', setDeletado, false);

