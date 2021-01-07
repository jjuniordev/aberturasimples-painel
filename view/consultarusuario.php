<?php include('menu.php'); // ----- CARREGA O MENU ----- // ?> 

<?php
    $id_user = $_SESSION['usuarioID'];
    $permissao = verificarPermissao($id_user);
    //$unidade = getNomeUnidade($id_user);
    $unidade = $_SESSION['usuarioUnidade'];

    if ($permissao > 4) {
        echo "Você não possui privilégios para acessar esta página!";
        echo "<br/><a href='index.php'>< Página Inicial</a>";
        exit();
      }

    if ($permissao == 4) {
                $query = mysql_query("SELECT 
                                    a.*, b.nome_unidade, c.level
                                FROM
                                    tb_usuarios a
                                INNER JOIN
                                    tb_unidades b ON a.id_unidade = b.id
                                INNER JOIN
                                    tb_level c
                                ON
                                    c.id = a.id_level
                                WHERE
                                    a.id_level >= $permissao
                                AND
                                    a.id_unidade = $unidade
                                AND
                                    a.active = 1
                                ORDER BY a.nome ASC"); // ----- REALIZA UMA CONSULTA E CARREGA PARA AS VARIAVEIS GLOBAIS ----- //
            } else {
                $query = mysql_query("SELECT 
                                    a.*, b.nome_unidade, c.level
                                FROM
                                    tb_usuarios a
                                INNER JOIN
                                    tb_unidades b ON a.id_unidade = b.id
                                INNER JOIN
                                    tb_level c
                                ON
                                    c.id = a.id_level
                                WHERE
                                    a.id_level >= $permissao
                                AND
                                    a.active = 1
                                ORDER BY a.nome ASC"); // ----- REALIZA UMA CONSULTA E CARREGA PARA AS VARIAVEIS GLOBAIS ----- //
            }
?>

<?php   
  # AQUI UM SCRIPT PARA BUSCAR OS DADOS DO BANCO DE DADOS E INSERIR EM ARRAY JS.
  echo "<script>";
  echo "var dados = [";
  while ($usuarios = mysql_fetch_array($query)) {
    echo "['"
    .$usuarios['id'] . "','" . utf8_encode($usuarios['nome']) . "','" . utf8_encode($usuarios['sobrenome']) . "','" . $usuarios['email']
    ."','" . $usuarios['login'] . "','" . utf8_encode($usuarios['nome_unidade']) . "','" . utf8_encode($usuarios['level']) . "'],";
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
                    .append($('<td name="nome" id="'+dados[i][0]+'" class="tabelaEditavel" title="'+ dados[i][1] +'">').append(dados[i][1]))
                    .append($('<td name="sobrenome" id="'+dados[i][0]+'" class="tabelaEditavel" title="'+ dados[i][2] +'">').append(dados[i][2]))
                    .append($('<td name="email" id="'+dados[i][0]+'" class="tabelaEditavel" title="'+ dados[i][3] +'">').append(dados[i][3]))
                    .append($('<td name="login" id="'+dados[i][0]+'" class="tabelaEditavel" title="'+ dados[i][4] +'">').append(dados[i][4]))
                    .append($('<td name="nome_unidade" id="'+dados[i][0]+'" class="" title="'+ dados[i][5] +'">').append(dados[i][5]))
                    .append($('<td name="nome_unidade" id="'+dados[i][0]+'" class="" title="'+ dados[i][6] +'">').append(dados[i][6]))
            )
        }
        $('#numeracao').text('Página ' + (pagina + 1) + ' de ' + Math.ceil(dados.length / tamanhoPagina));
        
        // Verificar se alguma linha está checkada e exibir botões.
        $('[name=Pacote]').click(function(){
            if($('[name=Pacote]').is(':checked')) {
                $('#del-user').removeClass('disabled');
            } else {
                $('#del-user').addClass('disabled');
            }
        });
        
        duploCliqueEdit(); // Chama a função que edita com duplo clique, dentro do looping para inserir em todas as linhas
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

        // Verificar se alguma linha está checkada e exibir botões.
        $('[name=Pacote]').click(function(){
            if($('[name=Pacote]').is(':checked')) {
                $('#del-user').removeClass('disabled');
            } else {
                $('#del-user').addClass('disabled');
            }
        });
        //duploCliqueEdit(); // Chama a função que edita com duplo clique, dentro do looping para inserir em todas as linhas
    });

</script>

<div class="ui container">
    <h1 class="ui center aligned header"><i class="cogs icon"></i>Gerenciar</h1>

    <?php include 'sub_menu_gerenciar.php'; ?>

    <div class="">
        <div class="ui two column grid">
            <div class="column">
                <h3 class="ui left aligned left floated header">
                  Lista de Usuários
                  <div class="sub header">Gerencie os usuários ativos no sistema.</div>
                </h3>
            </div>
            <div class="right aligned column">       
                
            </div>
        </div> 
    <div class="ui segment">
      <div class="ui grid">
      <div class="seven wide column">
        <form method="POST" id="form-pesquisa" action="">
            <div class="ui right icon fluid small input">
              <i class="search icon"></i>
              <input type="text" class="" name="pesquisa" id="pesquisa" placeholder="Buscar..." autocomplete="off">
            </div>
        </form>
      </div>
      <div class="eight wide right floated right aligned column">
         <button class="ui labeled icon red disabled small button" id="del-user" title="Deletar"><i class="trash alternate icon"></i>Deletar</button>
         <a href="adduser.php" class="ui labeled icon black small button" title="Adicionar novo usuário"><i class="plus icon"></i>Adicionar</a>
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
    <br>
    <div id="dvData">
        <table class="ui fixed single line selectable compact celled center aligned sortable table lista-clientes" id="tabela_padrao">
            <thead>
                <tr>
                    <th class="one wide no-sort"><div class="ui checkbox"><input type="checkbox" id="checkTodos" name="checkTodos"><label></label></div></th>
                    <th class="three wide">Nome</th>
                    <th class="three wide">Sobrenome</th>
                    <th class="four wide">Email</th>
                    <th class="two wide">Login</th>
                    <th class="two wide">Unidade</th>
                    <th class="two wide">Permissão</th>
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
              <th colspan="6">
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
    <br>
    <br>
    </div>
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
      msg = confirm('Deletar os Usuários ' + values + '?');
      if (msg == true) {
        $.ajax({
          url: '../model/ajaxDeletarUsuario.php',
          type: 'POST',
          data: {
            'valores': values,
          },
            beforeSend: function(){ 
                    $('#del-user').addClass('disabled loading');            
                  },
            success: function(data) { //Se a requisição retornar com sucesso, 
                //ou seja, se o arquivo existe, entre outros
                //$('#sucesso').html(data); //Parte do seu 
                setTimeout(function(){
                    $('#del-user').removeClass('disabled loading');
                    $(window).attr('location','pendentes.php');
                },1500);
            }
        });
      location.reload();
    } 
   } 
   
    var del = document.getElementById('del-user');
    del.addEventListener('click', delValues, false);
</script>

<script type="text/javascript">
    function duploCliqueEdit() {
    $("td.tabelaEditavel").dblclick(function () {
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
                        url: '../model/ajaxAlterarUsuario.php',
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
            $.post('busca_geral_usuarios.php', dados, function(retorna){
                //Mostra dentro da ul os resultado obtidos 
                $(".resultado").html(retorna);
                $("#tabela_padrao").hide();
                $('#del-user').addClass('disabled');  
                $('input:checkbox').prop('checked', false);
            });
        }else{
            $(".resultado").html('');
            $('#del-user').addClass('disabled');  
            $("#tabela_padrao").show();
            $('input:checkbox').prop('checked', false);
        }       
    });
});
</script>

<script type="text/javascript" src="js/funcoes.js"></script>
<script type="text/javascript" src="tablesort.js"></script>

<script type="text/javascript">

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
    $('#del-user').removeClass('disabled');
  }else{
    $('input:checkbox').prop("checked", false);
    $('#del-user').addClass('disabled');
  }
});
</script>