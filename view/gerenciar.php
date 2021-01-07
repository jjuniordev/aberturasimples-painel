<?php 
	include('menu.php'); 
	$id_user = $_SESSION['usuarioID'];
	$unidade_id = $_SESSION['usuarioUnidade'];
	$permissao = verificarPermissao($id_user);	
	$query = mysql_query("SELECT 
							    a.nome,
							    a.email,
							    a.login,
							    b.level,
							    c.nome_unidade
							FROM
							    tb_usuarios a
							INNER JOIN
								tb_level b
							ON a.id_level = b.id
							INNER JOIN
								tb_unidades c
							ON a.id_unidade = c.id
							WHERE
							    a.id = $id_user");
	$usuarios = mysql_fetch_array($query);
?>
<?php 
	function getUsuarios() {
		$consulta = mysql_query("SELECT * FROM tb_usuarios WHERE active = 1 and id_level >= 4 order by nome ASC");
		$exibe = "";
		while ($resposta = mysql_fetch_array($consulta)) {
			$exibe .= '<tr>';
			$exibe .= '<td>'.utf8_encode($resposta['nome'])." ".utf8_encode($resposta['sobrenome']).'</td>';
			$exibe .= '<td>'.utf8_encode($resposta['email']).'</td>';
			$exibe .= '<td>'.utf8_encode($resposta['login']).'</td>';	
			$exibe .= '<td>'.utf8_encode($resposta['data_cadastro']).'</td>';
			$exibe .= '<td><i class="sync icon"></i></td>';
			$exibe .= '</tr>';
		}

		return $exibe;
	}

?>

<div class="ui container">
	<h1 class="ui center aligned header"><i class="cogs icon"></i>Gerenciar</h1>
	<div class="ui divider"></div>
		<h3 class="ui icon left aligned header">
          <div class="content">
            Dados de cadastro
            <div class="sub header">Visualize e gerencie os dados do seu cadastro.</div>
          </div>
        </h3>
        <br>
    <div class="">
		<table class="ui center aligned fixed celled table">
			<thead>
				<th>Nome</th>
				<th>Email</th>
				<th>Login</th>
				<th>Senha</th>
				<th>Permissão</th>
				<th>Unidade</th>
			</thead>
		  <tr>
		    <!-- <th class="right aligned">Nome:</th> -->
		    <td><?php echo utf8_encode($usuarios['nome']); ?></td>
		    <td><?php echo $usuarios['email']; ?></td>
		    <td><?php echo $usuarios['login']; ?></td>
		    <td>
		    	<?php echo "<a id='link' href='#'><i class='pencil alternate icon'></i>Alterar</a>" ?>
		    	<div id="input" class="ui fluid input">
		    		<input id="senha" type="text" placeholder="Nova senha...">
		    		<?php echo '<input type="hidden" value="'.$id_user.'" id="id_user">'; ?>
		    		<a><i id="cancelinput" class='times black icon'></i></a>
		    	</div>
		    </td>
		    <td><?php echo utf8_encode($usuarios['level']); ?></td>
		    <td><?php echo utf8_encode($usuarios['nome_unidade']); ?></td>
		  </tr>
		</table>
    </div>
    <div id="msgbox" class="ui success message">
	  <i class="close icon"></i>
	  <div class="header">
	    Senha alterada com sucesso!
	  </div>
	  <p></p>
	</div>
	<br>
<!-- <div class="ui input focus"><input type="text" placeholder="Search..."></div> -->
<script type="text/javascript">
	$('#input').hide();
	$('#msgbox').hide();
	$('#link').click(function(){
		$('#link').hide();
		$('#input').show();
	});
	$('#cancelinput').click(function(){
		$('#link').show();
		$('#input').hide();
	});
	$('#input').keyup(function(e){
		if (e.keyCode == 13){
			var novasenha = $('#senha').val();
			var id_user = $('#id_user').val();
			var verifica = confirm('Deseja mesmo alterar sua senha?');
			if (verifica == true) {
					$.ajax({
					url: '../model/ajaxAlterarSenha.php',
					type: 'POST',
					data: {
						'novasenha': novasenha,
						'id_user': id_user,
					},
					success: function(){
						//location.reload();
						$('#msgbox').show();
						$('#link').show();
						$('#input').hide();
					}
				});
			} else {
				alert('cancelado!');
			}
			
		}
	})
</script>

	