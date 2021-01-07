
var ini = $('#calendar-ini').val();
var fim = $('#calendar-fim').val();

$(".ver_leads").click(function(){
    var id = $(this).attr("id");
    $.ajax({
		url: '../model/ajaxDadosModalLeadsEnviadosFiltrado.php',
		type: 'POST',
		data: {
			'id': id,
			'ini': ini,
			'fim': fim
		},
		cache: false,
		beforeSend: function(){	
			$('#div_modal').remove(); // Remove a div para não duplicar o Modal
			$('#retorno').html(''); // Renova a div que recebe o retorno do Ajax
		},
		success: function(result) {			
			$('#retorno').html(result);
			$('.ui.longer.modal').modal('show');
		}
	})
});

