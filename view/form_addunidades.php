<style type="text/css">
	#loader {
		display: none;
		/*opacity: 0;*/
	}
</style>
<div class="ui segment">
  <form action="" id="form" name="form" method="post">
        <h3 class="ui icon center aligned header">
          <div class="content">
            Cadastro de Unidades
            <div class="sub header">Adicione novas unidades ao sistema.</div>
          </div>
        </h3>
        <br>
        <div class="ui center aligned grid">
        <div class="ui form">
        	<div id="nomediv" class="field">
	                <label>Nome da Unidade</label>
	                <input type="text" id="nome" name="nome" placeholder="Nome" required autofocus>
            </div>
            <div class="fields">
            	<div id="cidadediv" class="field">
	                <label>Cidade</label>
	                <input type="text" id="cidade" name="cidade" class="form-control" placeholder="Cidade" required>
	            </div>
	            <div id="estadodiv" class="field">
	                <label>Estado</label>
	                <input type="text" id="estado" name="estado" class="form-control" placeholder="Estado" required>
	            </div>	
            </div>
            <!-- <div class="field">
                <label>GoogleID</label>
                <input type="text" id="google_id" name="google_id" class="form-control" placeholder="ID da conta Google" required>
            </div>  -->  
        </div>
    </div>
        <br><br>
        
        <div class="ui center aligned container">
            <input type="button" name="button" id="button" value="Cadastrar" class="ui green button"/>
            <!-- <input type="hidden" name="ok" id="ok"/> -->
        </div>
        <div id="loader" class="">
		  <div class="ui active inverted dimmer">
		    <div class="ui text loader">Loading</div>
		  </div>
    </form>
</div>
<div id="sucesso"></div>

</div>

<script type="text/javascript">
	$('#nomediv').keyup(function(){
		var nome = $('#nome').val();
		$.ajax({
			type: "POST",
			data: {
				nome: nome
			},
			url: "../controller/ajaxVerificaNomeUnidade.php",
			success: function(resultado) {
				if (resultado != 0) {
					$('#nomediv').addClass('error');
					$('#button').addClass('disabled');
				} else {
					$('#nomediv').removeClass('error');
					$('#button').removeClass('disabled');
				}
			}
		});
	});
	$('#button').click(function() {
		var nome 	= $('#nome').val();
		var cidade 	= $('#cidade').val();
		var estado 	= $('#estado').val();
		// var google_id = $('#google_id').val();
		if (nome == '') {
			//alert("Favor preencher campo Nome");
			$('#nomediv').addClass('error');
			exit();
		} else if (cidade == '') {
			//alert("Favor preencher campo Cidade");
			$('#cidadediv').addClass('error');
			exit();
		}else if (estado == '') {
			//alert("Favor preencher campo Estado");
			$('#estadodiv').addClass('error');
			exit();
		}
		$.ajax({
			type: "POST",
			data: {
				nome: nome,
				cidade: cidade,
				estado: estado,
			},
			url: "../model/ajaxCadastrarUnidade.php",
			beforeSend: function(){	
				$('#loader').css({display:"block"});		    
			  },
			success: function(data) { //Se a requisição retornar com sucesso, 
        		//ou seja, se o arquivo existe, entre outros
                $('#sucesso').html(data); //Parte do seu 
                setTimeout(function(){
			    	$('#loader').css({display:"none"});	
			    	$(window).attr('location','consultarunidades.php');
			    },1000); 			    
                //HTML que voce define onde sera carregado o conteudo
                //PHP, por default eu uso uma div, mas da pra fazer em outros lugares
            }
		});

	});
</script>