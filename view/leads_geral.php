<?php 

	include('menu.php'); 
	$id_user = $_SESSION['usuarioID'];
	$account_id = buscarClientId($id_user);
	$dfatos     = dadosParciaisFato($id_user);
	$query = mysql_query("SELECT 
						    a.*, 
						    DATE_FORMAT(data, '%d/%m/%Y - %Hh%i') AS data_limpa,
						    c.account_name,
						    b.unidade
						FROM
						    tb_leads a
						INNER JOIN
							tb_usuarios b
						ON
							a.id_associado = b.id
						INNER JOIN 
							tb_google_account c
						ON 
							b.account_id = c.id
						ORDER BY 1 DESC;");
 ?>
 <?php 
	echo "<script>";
	echo "var dados = [";
	while ($leads = mysql_fetch_array($query)) {
		switch ($leads['esta_ativo']) {
			case 0:
				$ativo 	= "Inativo";
				$classe = "disabled";
				break;
			case 1:
				$ativo = "Ativo";
				$classe = "";
				break;			
			default:
				$ativo = "Ativo";
				$classe = "";
				break;
		}

		echo "['"
		.$leads['data_limpa'] . "','" . utf8_encode($leads['nome']) . "','" . $leads['email']
		."','"
		.$leads['unidade'] . "','" . utf8_encode($leads['cidade']) . "','" . $leads['estado']
		."','"
		.$leads['origem'] . "','" . $leads['identificador'] . "','" . mysql_real_escape_string(utf8_encode($leads['mensagem']))
		."','"
		.$leads['id'] . "','".$ativo."','".$classe."'],";
	}
	// echo "['','','','','','','','']];";
	echo "];";
	echo "</script>";
?>
 <script type="text/javascript">

var tamanhoPagina = 16;
var pagina = 0;

function paginar() {
    $('table > tbody > tr').remove();
    var tbody = $('table > tbody');
    for (var i = pagina * tamanhoPagina; i < dados.length && i < (pagina + 1) *  tamanhoPagina; i++) {
        tbody.append(
            $('<tr class="' + dados[i][11] + '">')
            	// .append($('<td class="center aligned">').append('<div class="ui checkbox"><input name="Pacote" value="'+dados[i][9]+'" type="checkbox"><label></label></div>'))
                .append($('<td title="'+ dados[i][0] +'">').append(dados[i][0]))
                .append($('<td name="nome" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][2] +'">').append(dados[i][1]))
                // .append($('<td name="email" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][2] +'">').append(dados[i][2]))
                // .append($('<td name="telefone" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][3] +'">').append(dados[i][3]))
                .append($('<td name="cidade" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][4] +'">').append(dados[i][4]))
                .append($('<td name="estado" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][5] +'">').append(dados[i][5]))
                .append($('<td name="origem" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][6] +'">').append(dados[i][6]))
                .append($('<td name="identificador" id="'+dados[i][9]+'" title="'+ dados[i][7] +'">').append(dados[i][7]))
                .append($('<td name="mensagem" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][8] +'">').append(dados[i][8]))
                .append($('<td name="unidade" id="'+dados[i][9]+'" class="leadeditavel" title="'+ dados[i][3] +'">').append(dados[i][3]))
                .append($('<td name="esta_ativo" id="'+dados[i][9]+'">').append(dados[i][10]))
        )
    }
    $('#numeracao').text('Página ' + (pagina + 1) + ' de ' + Math.ceil(dados.length / tamanhoPagina));

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

});

</script>

 <div class="ui container">
	<h1 class="ui left aligned header"><i class="ui list alternate icon"></i>Leads</h1>
	<div class="ui divider"></div>
	<div class="ui inverted menu">
	  <a class="item active">
	    Geral
	  </a>
	  <a class="item" href="leads_atribuidos.php">
	    Atribuídos
	  </a>
	  <a class="item" href="leads_inativos.php">
	    Inativos
	  </a>

	  <div class="right menu">
	  	<div class="item">
		  	<div class="ui transparent icon input">
			  <input type="text" class="input-search" alt="lista-clientes" placeholder="Pesquisar..."/>
			  <i class="search link inverted icon"></i>
			</div>
		</div>
	  </div>
	</div>
	<div class="ui right aligned container">
		
	</div>
	<table class="ui fixed single line selectable compact celled center aligned table lista-clientes">
	    <thead>
	        <tr>
	            <!-- <th class="one wide"></th> -->
	            <th>Data</th>
	            <th>Nome</th>
	            <!-- <th>Email</th>
	            <th>Telefone</th> -->
	            <th>Cidade</th>
	            <th>Estado</th>
	            <th class="two wide">Origem</th>
	            <th>Identificador</th>
	            <th class="one wide">Mensagem</th>
	            <th>Unidade</th>
	            <th>Status</th>
	        </tr>
	    </thead>
	    <tbody>
	        <tr>
	            <td colspan="2" align="center">Nenhum dado ainda...</td>
	        </tr>
	    </tbody>
	    <tfoot class="full-width">
	    <tr>
	      <th colspan="9">
	        <div class="ui center floated buttons">
				<button id="anterior" class="ui black button" disabled>&lsaquo; Anterior</button>
			    <button class="ui basic disabled button"><span id="numeracao"></span></button>
				<button id="proximo" class="ui black button" disabled>Próximo &rsaquo;</button>
			</div>
	      </th>
	    </tr>
	  </tfoot>
	</table>
</div>

<script type="text/javascript">
    $(function buscaTabela(){
    $(".input-search").keyup(function(){
        //pega o css da tabela 
        var tabela = $(this).attr('alt');
        if( $(this).val() != ""){
            $("."+tabela+" tbody>tr").hide();
            $("."+tabela+" td:contains-ci('" + $(this).val() + "')").parent("tr").show();
        } else{
            $("."+tabela+" tbody>tr").show();
        }
    }); 
});
$.extend($.expr[":"], {
    "contains-ci": function(elem, i, match, array) {
        return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
    }
});
</script>