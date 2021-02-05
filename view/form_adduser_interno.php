<div class="ui segment">
  <form action="" id="form" name="form" method="post">
        <h3 class="ui icon center aligned header">
          <div class="content">
            Cadastro de usuários
            <div class="sub header">Adicione novos usuários ao sistema.</div>
          </div>
        </h3>
        <br>
        <div class="ui center aligned grid">
        <div class="ui equal width form">
        	<div class="fields">
	            <div class="field">
	                <label>Nome</label>
	                <input type="text" id="nome" name="nome" placeholder="Nome" required autofocus>
	            </div>
	            <div class="field">
	                <label>Sobrenome</label>
	                <input type="text" id="sobrenome" name="sobrenome" class="form-control" placeholder="Sobrenome" required>
	            </div>        
	            <div class="field">
	                <label>E-mail</label>
	                <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
	            </div>
        	</div>
        	<div class="fields">
	            <div class="field" id="logindiv">
	                <label>Login</label>
	                <input type="text" id="login" name="login" class="form-control" placeholder="Login" required>
	            </div>
	            <div class="field">
	                <label>Senha</label>
	                <input type="password" id="senha" name="senha" class="form-control" placeholder="Senha" required>
	            </div>
	            <div class="field">
	                <label>Permissão</label>
	                <?php 
	                	$permissoes = buscaPermissao($permissao); 
	                	echo '<select id="level" name="id_level" class="form-control" required>';
	                	echo '<option selected hidden>Selecione...</option>';
		                while ($p = mysql_fetch_array($permissoes)) {
		                    echo '<option value="'.$p['id'].'" class="form-control">'.utf8_encode($p['level']).'</option>';
		                }
	                	echo "</select>";

	                ?>
	            </div>
	            <style type="text/css">
	            	#f_unidade, #f_unidade2, #loader  {
	            		display: none;
	            	}
	            </style>
	            
	            <?php 
	            	if ($permissao <= 3) {
	            		echo '<div id="f_unidade" class="field">
	                			<label>Unidade</label>';
            			$unidades = buscaUnidades($permissao);
	                	echo "<select name='unidade' id='seletor' class='form-control' required>";
	                	while ($un = mysql_fetch_array($unidades)) {
	                		echo '<option name="'.$un['nome_unidade'].'" id="uni" value="'.$un['id'].'">'.utf8_encode($un['nome_unidade']).'</option>';
	                	}
	                	echo "</select>";
	                	echo "</div>";
	                	echo '<div id="f_unidade2" class="field">
	                			<label>Unidade</label>';
            			$unidades = buscaUnidades2($permissao);
	                	echo "<select name='unidade2' id='seletor2' class='form-control' required>";
	                	while ($un = mysql_fetch_array($unidades)) {
	                		echo '<option name="'.$un['nome_unidade'].'" id="uni2" value="'.$un['id'].'">'.utf8_encode($un['nome_unidade']).'</option>';
	                	}
	                	echo "</select>";
	                	echo "</div>";
	            	}
	             ?>
            </div>
        </div>
        </div>
        <br>
        <div class="ui center aligned container">
            <!-- <input type="button" name="button" id="button" value="Cadastrar" class="ui green button" onclick="validar(document.form);"/> -->
            <input type="submit" name="button" id="button" value="Cadastrar" class="ui green button"/>
            <!-- <input type="hidden" name="ok" id="ok" /> -->
        </div>
    </form>
</div>
<script type="text/javascript">
	$(document).ready(function(){	            		
		$('#level').change(function() {	  
		var per = $(this).val();          			
		if (per == 4) {
			$('#f_unidade').css('display','block');
			$('#f_unidade2').css('display','none');
		} else if (per == 5) {
			$('#f_unidade').css('display','none');
			$('#f_unidade2').css('display','block');
		} else {
			$('#f_unidade2').css('display','none');
			$('#f_unidade').css('display','none');
		}
		});
	});
</script>
<script type="text/javascript">
	$('#logindiv').keyup(function(){
		var login = $('#login').val();
		$.ajax({
			type: "POST",
			data: {
				login: login
			},
			url: "../controller/ajaxVerificaNomeUsuario.php",
			success: function(resultado) {
				if (resultado != 0) {
					$('#logindiv').addClass('error');
					$('#button').addClass('disabled');
				} else {
					$('#logindiv').removeClass('error');
					$('#button').removeClass('disabled');
				}
			}

		});
	});
	$('#button').click(function(){
		var nome 	= $('#nome').val();
		var sn 		= $('#sobrenome').val();
		var email 	= $('#email').val();
		var login 	= $('#login').val();
		var senha 	= $('#senha').val();
		var level 	= $('#level').val();
		if ($('#f_unidade').is(':visible')) {
			var uni	= $('#seletor').val();	
		} else if ($('#f_unidade2').is(':visible')) {
			var uni = $('#seletor2').val();
		} else {
			var uni = 1;
		}
		$.ajax({
			url: '../model/ajaxCadastrarUsuario.php',
			type: 'POST',
			data: {
				'nome': nome,
				'sobrenome': sn,
				'email': email,
				'login': login,
				'senha': senha,
				'level': level,
				'unidade': uni
			},
				beforeSend: function() {
					$('#loader').show();
				},
				success: function(retorno) {
					// setTimeout(function(){
					// 	location.reload();
					// },1000);
				}
		});
	});
</script>

  <div class="ui active dimmer" id="loader">
    <div class="ui text loader">Cadastrando</div>
  </div>
  <p></p>
