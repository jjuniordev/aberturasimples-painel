function UpdateRecord(id)
  {
      jQuery.ajax({
       type: "POST",
       url: "../../model/updateLeadAssociado.php",
       data: 'id='+id,
       cache: false,
       success: function(response)
       {
         alert("Record successfully updated");
       }
     });
 }
