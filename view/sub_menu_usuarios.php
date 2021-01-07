<div class="ui inverted menu">
  <a id="gerenciar" class="item" href="consultarusuario.php">
    Gerenciar
  </a>
  <a id="adicionar" class="item active" href="adduser.php"> 
    Adicionar
  </a>

</div>

<script type="text/javascript">
	var documento = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);

	switch (documento) {
				case 'consultarusuario.php':
					$('#gerenciar').addClass('active');
					$('#adicionar').removeClass('active');
					break;
				case 'adduser.php':
					$('#adicionar').addClass('active');
					$('#gerenciar').removeClass('active');
					break;
			}
</script>

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

