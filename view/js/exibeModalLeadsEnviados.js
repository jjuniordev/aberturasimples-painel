
$(".ver_leads").click(function(){
    $('#loader_dados').show();
    var id = $(this).attr("id");
    $.ajax({
		url: '../model/ajaxDadosModalLeadsEnviados.php',
		type: 'POST',
		data: {
			'id': id
		},
		cache: false,
		beforeSend: function(){	
			$('#div_modal').remove(); // Remove a div para não duplicar o Modal
			$('#retorno').html(''); // Renova a div que recebe o retorno do Ajax			
		},
		success: function(result) {			
			$('#loader_dados').hide();
			$('#retorno').html(result);
			$('.ui.longer.modal').modal('show');
		}
	})
});

