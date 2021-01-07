<?php 

	include('menu.php'); 
	$id_user = $_SESSION['usuarioID'];
  	//protegePagina();
  	$permissao = verificarPermissao($id_user);
  	# Verificar permissão e negar acesso caso não tenha privilégios
  	if ($permissao >= 4) {
    	echo "Você não tem permissão para acessar esta página, <a href='index.php'>clique aqui</a> para voltar ao painel.";
    	exit();
  	}

 ?>
<div class="ui container">
	<h1 class="ui center aligned header"><i class="ui shopping cart icon"></i>Vendas do Mês</h1>
	<div class="ui divider"></div>
	<h3 class="ui left aligned left floated header">
      Vendedores
      <div class="sub header">Painel de vendedores e indicadores de performance.</div>
    </h3>
    <?php 
    	if ($permissao == 5) {
    		echo "Você não possui privilégios para acessar aqui";
    		exit();
    	}
     ?>
	<div style="height: 600px; width: 100%; overflow: hidden;">
	 	<iframe width="100%" height="635px" 
	 			src="https://datastudio.google.com/embed/reporting/1RSCbJCFyPqn5aJl1lRl3P8nIcvhfZ8Rf/page/BsyQ" 
	 			frameborder="0" style="border:0" allowfullscreen>
		</iframe>
	</div>

</div>