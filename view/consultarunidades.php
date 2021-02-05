<?php 
	include('menu.php'); 
	$id_user = $_SESSION['usuarioID'];
	$permissao = verificarPermissao($id_user);	
	$query = mysql_query("SELECT 
                          a.id
                          ,a.nome_unidade
                          ,a.Cidade
                          ,a.Estado
                          ,a.google_id
                          ,a.esta_ativo
                          ,a.id_usuario
                          ,b.nome as nome_responsavel
                      FROM
                          tb_unidades a
                      INNER JOIN 
                        tb_usuarios b
                      ON
                        a.id_usuario = b.id
                      WHERE
                          a.esta_ativo = 1 AND a.id != 1
                      ORDER BY a.nome_unidade ASC;");
	if ($permissao > 3) {
    echo "Você não tem permissão para acessar esta página, <a href='index.php'>clique aqui</a> para voltar ao painel.";
      exit();
  }
?>
<style type="text/css">
	#loader {	
		display: none;
	}
</style>

<?php   
  # AQUI UM SCRIPT PARA BUSCAR OS DADOS DO BANCO DE DADOS E INSERIR EM ARRAY JS.
  echo "<script>";
  echo "var dados = [";
  while ($unidades = mysql_fetch_array($query)) {
    echo "['"
    .$unidades['id'] . "','" . utf8_encode($unidades['nome_unidade']) . "','" . utf8_encode($unidades['Cidade'])
    ."','" . utf8_encode($unidades['Estado']) . "','" . utf8_encode(trim($unidades['google_id'])) . "','".utf8_encode($unidades['nome_responsavel'])."'],";
  }
  echo "];";
  echo "</script>";
?>
<script type="text/javascript">

    // SCRIPT PARA MONTAR A TABELA PAGINADA

    var tamanhoPagina = 20; // Variável que indica a quantidade de linhas exibidas na tabela antes de paginar
    var pagina = 0; // Indica a página que irá iniciar

    function paginar() {
        $('table > tbody > tr').remove();
        var tbody = $('table > tbody');
        for (var i = pagina * tamanhoPagina; i < dados.length && i < (pagina + 1) *  tamanhoPagina; i++) {
            tbody.append(
                $('<tr>')
                  .append($('<td class="center aligned">').append('<div class="ui checkbox"><input name="Pacote" value="'+dados[i][0]+'" type="checkbox"><label></label></div>'))
                    .append($('<td name="nome_unidade" id="'+dados[i][0]+'" class="unidadeEditavel" title="'+ dados[i][1] +'">').append(dados[i][1]))
                    .append($('<td name="cidade" id="'+dados[i][0]+'" class="unidadeEditavel" title="'+ dados[i][2] +'">').append(dados[i][2]))
                    .append($('<td name="estado" id="'+dados[i][0]+'" class="unidadeEditavel" title="'+ dados[i][3] +'">').append(dados[i][3]))
                    .append($('<td name="google_id" id="'+dados[i][0]+'" class="unidadeEditavel" title="'+ dados[i][4] +'">').append(dados[i][4].substr(0,3)+"-"+dados[i][4].substr(3,3)+"-"+dados[i][4].substr(6,4)))
                    .append($('<td name="nome_responsavel" id="'+dados[i][5]+'" title="'+ dados[i][5] +'">').append(dados[i][5]))
            )
        }
        $('#numeracao').text('Página ' + (pagina + 1) + ' de ' + Math.ceil(dados.length / tamanhoPagina));

        duploCliqueUnid(); // Chama a função que edita com duplo clique, dentro do looping para inserir em todas as linhas
        
        // Verificar se alguma linha está checkada e exibir botões.
        $('[name=Pacote]').click(function(){
            if($('[name=Pacote]').is(':checked')) {
                $('#del-unidade').removeClass('disabled');
            } else {
                $('#del-unidade').addClass('disabled');
            }
        });
        
        //duploCliqueUnid(); // Chama a função que edita com duplo clique, dentro do looping para inserir em todas as linhas
        //exibeModal();
    }

    function ajustarBotoes() {
        $('#proximo').prop('disabled', dados.length <= tamanhoPagina || pagina >= Math.ceil(dados.length / tamanhoPagina) - 1);
        $('#anterior').prop('disabled', dados.length <= tamanhoPagina || pagina == 0);
    }

    $(function() {
        $('#proximo').click(function() {
            if (pagina < dados.length / tamanhoPagina - 1) {
                pagina++;
                paginar();
                ajustarBotoes();
            }
        });
        $('#anterior').click(function() {
            if (pagina > 0) {
                pagina--;
                paginar();
                ajustarBotoes();
            }
        });
        paginar();
        ajustarBotoes();
        duploCliqueUnid(); // Chama a função que edita com duplo clique, dentro do looping para inserir em todas as linhas

        // Verificar se alguma linha está checkada e exibir botões.
        $('[name=Pacote]').click(function(){
            if($('[name=Pacote]').is(':checked')) {
                $('#del-unidade').removeClass('disabled');
            } else {
                $('#del-unidade').addClass('disabled');
            }
        });
    });

</script>

<div class="ui container">
	<h1 class="ui center aligned header"><i class="cogs icon"></i>Gerenciar</h1>
	<?php include 'sub_menu_gerenciar.php'; ?>
	<div class="ui two column grid">
            <div class="column">
                <h3 class="ui left aligned left floated header">
                  Lista de Unidades
                  <div class="sub header">Gerencie as unidades ativas no sistema.</div>
                </h3>
            </div>
            <div class="right aligned column">       
                <!-- <button class="ui labeled icon red tiny disabled button" id="del-unidade" title="Deletar">
                    <i class="trash alternate icon"></i>
                    Deletar
                </button> -->
            </div>
        </div> 

<div class="ui segment">
      <div class="ui grid">
      <div class="nine wide column">
        <form method="POST" id="form-pesquisa" action="">
            <div class="ui right icon fluid small input">
              <i class="search icon"></i>
              <input type="text" class="" name="pesquisa" id="pesquisa" placeholder="Buscar..." autocomplete="off">
            </div>
        </form>
      </div>
      <div class="six wide right floated right aligned column">
         <button class="ui labeled icon red disabled small button" id="del-unidade" title="Deletar"><i class="trash alternate icon"></i>Deletar</button>
         <a href="addunidades.php" class="ui labeled icon black small button" title="Adicionar nova unidade"><i class="plus icon"></i>Adicionar</a>
         <div class="ui labeled icon top middle pointing dropdown basic small button">
          <i class="caret down icon"></i>
          <span class="text">Opções</span>
          <div class="menu">
            <div class="item">
              <i class="download icon"></i>
              <a id="btnExport" class="export">Exportar (.csv)</a>
            </div>    
          </div>
        </div>
      </div>
    </div>

<div id="loader" class="">
  <div class="ui active inverted dimmer">
    <div class="ui text loader">Loading</div>
  </div>
</div>
<div id="sucesso"></div>
<script type="text/javascript">
	$('.del_unit').click(function(){
		var id = $(this).attr('id');
		var nome = $(this).attr('name');
		var confirma = confirm('Deseja deletar a Unidade ' + nome + '?');
		if (confirma) {
			$.ajax({
				type: 'POST',
				data: {
					id: id
				},
				url: '../model/ajaxDeletarUnidade.php',
				beforeSend: function(){	
					$('#loader').css({display:"block"});		    
			  	},
				success: function(data) { //Se a requisição retornar com sucesso, 
	        		//ou seja, se o arquivo existe, entre outros
	                $('#sucesso').html(data); //Parte do seu 
	                setTimeout(function(){
				    	$('#loader').css({display:"none"});	
				    	$(window).attr('location','consultarunidades.php');
				    },500); 			    
	                //HTML que voce define onde sera carregado o conteudo
	                //PHP, por default eu uso uma div, mas da pra fazer em outros lugares
	            }
			});
		} else {

		}
	});

</script>

<script type="text/javascript">
    function duploCliqueUnid() {
    $("td.unidadeEditavel").dblclick(function () {
        var conteudoOriginal = $(this).text();
        var campo = $(this).attr('name');
        var id = $(this).attr('id');
        
        $(this).addClass("warning");
        $(this).html("<input type='text' value='" + conteudoOriginal + "' />");
        $(this).children().first().focus();
        $(this).children().first().select();

        $(this).children().first().keypress(function (e) {
            if (e.which == 13) {
                var novoConteudo = $(this).val(); 
                var confirma = confirm('De: ' + conteudoOriginal + ' \nPara: ' + novoConteudo + '\n\nConfirmar esta ateração?');                
                if (confirma == true) {
                    $(this).parent().text(novoConteudo);
                    $(this).parent().removeClass("warning");                    
                    $.ajax({
                        url: '../model/ajaxAlterarUnidade.php',
                        type: 'POST',
                        data: {
                            'campo': campo,
                            'valor': novoConteudo,
                            'id': id,
                        }
                    });
                } 
            }
        });
        
        $(this).children().first().blur(function(){
        $(this).parent().text(conteudoOriginal);
        $(this).parent().removeClass("warning");
    });
    });
};
</script>
    <br>
    <div id="dvData">
        <table class="ui fixed single line selectable compact celled center aligned sortable table lista-clientes" id="tabela_padrao">
            <thead>
                <tr>
                    <th class="one wide no-sort"><div class="ui checkbox"><input type="checkbox" id="checkTodos" name="checkTodos"><label></label></div></th>
                    <th class="">Nome</th>
                    <th class="">Cidade</th>
                    <th class="two wide">Estado</th>
                    <th class="">Google id</th>
                    <th class="">Responsável</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" align="center">Nenhum dado ainda...</td>
                </tr>
            </tbody>
            <tfoot class="full-width">
            <tr>
              <th></th>
              <th colspan="5">
                <div class="ui center floated buttons">
                    <button id="anterior" class="ui black button" disabled>&lsaquo; Anterior</button>
                    <button class="ui basic disabled button"><span id="numeracao"></span></button>
                    <button id="proximo" class="ui black button" disabled>Próximo &rsaquo;</button>             
                </div>
                <!-- <button class="ui right floated button">add 10 linhas</button> -->
              </th>
            </tr>
          </tfoot>
        </table>
    </div>    
    <div class="resultado">
        
    </div>
</div>
<br><br>
</div>

<script>
    function delValues() {
    var pacote   = document.querySelectorAll('[name=Pacote]:checked');
    var values   = [];
    var nome     = $('#tags').val();
    for (var i = 0; i < pacote.length; i++) {
      // utilize o valor aqui, adicionei ao array para exemplo
      values.push(pacote[i].value);
    }
      msg = confirm('Deletar as Unidades ' + values + '?');
      if (msg == true) {
        $.ajax({
          url: '../model/ajaxDeletarUnidade.php',
          type: 'POST',
          data: {
            'valores': values,
          },
            beforeSend: function(){ 
                    $('#del-unidade').addClass('disabled loading');            
                  },
            success: function(data) { //Se a requisição retornar com sucesso, 
                //ou seja, se o arquivo existe, entre outros
                //$('#sucesso').html(data); //Parte do seu 
                setTimeout(function(){
                    $('#del-unidade').removeClass('disabled loading');
                    $(window).attr('location','pendentes.php');
                },2000);
            },
            error: function (request, status, error) {
                msg = confirm('Não foi possível completar a sua ação, tente novamente.');
                if (msg) {
                  location.reload();
                }
            }
        });
      // location.reload();
    } 
   } 
   
    var del = document.getElementById('del-unidade');
    del.addEventListener('click', delValues, false);
</script>

<script type="text/javascript" src="js/funcoes.js"></script>
<script type="text/javascript" src="tablesort.js"></script>

<script>
    $(function(){
    //Pesquisar os cursos sem refresh na página
    $("#pesquisa").keyup(function(){
        
        var pesquisa = $(this).val();
        
        //Verificar se há algo digitado
        if(pesquisa != ''){
            var dados = {
                palavra : pesquisa
            }       
            $.post('busca_geral_unidades.php', dados, function(retorna){
                //Mostra dentro da ul os resultado obtidos 
                $(".resultado").html(retorna);
                $("#tabela_padrao").hide();
                $('#del-unidade').addClass('disabled');
                $('input:checkbox').prop('checked', false);
            });
        }else{
            $(".resultado").html('');
            $('#del-unidade').addClass('disabled');
            $('input:checkbox').prop('checked', false);
            $("#tabela_padrao").show();
        }       
    });
});

  $('.ui.dropdown')
  .dropdown({
    action: 'hide'
  })
;

$("#checkTodos__").change(function () {
$("input:checkbox").prop('checked', $(this).prop("checked"));
});

$("#checkTodos__").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

var checkTodos = $("#checkTodos");
checkTodos.click(function () {
  if ( $(this).is(':checked') ){
    $('input:checkbox').prop("checked", true);
    $('#del-unidade').removeClass('disabled');
  }else{
    $('input:checkbox').prop("checked", false);
    $('#del-unidade').addClass('disabled');
  }
});
</script>