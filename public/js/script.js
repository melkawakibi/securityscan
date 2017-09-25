$(document).ready(function(){

	var active = $('.active');

	active.click(function(e){

		var p = $(e.target).parent().find('p');
		var id = p.text();

		if(active.val() == 'Active'){
			$('#dialogtext').text("Weet u zeker dat u deze account (" + id + ") wilt deactiveren?");
			$( "#dialog-confirm" ).dialog({
		      resizable: false,
		      height: "auto",
		      width: 400,
		      modal: true,
		      buttons: {
		        "Deactiveer account": function() {
		        $.get('http://localhost:8000/active/' + id, function(){
		        	window.location.href = 'http://localhost:8000/customers';
				})
				.fail(function(jqXHR){
					alert('error, probeer het opnieuw' + jqXHR.responseText);
				})
		          $( this ).dialog( "close" );
		        },
		        Annuleer: function() {
		          $( this ).dialog( "close" );
		         }
		      }
			});
	
		}else{
			$('#dialogtext').text("Weet u zeker dat u deze account (" + id + ") wilt activeren?");
			$( "#dialog-confirm" ).dialog({
		      resizable: false,
		      height: "auto",
		      width: 400,
		      modal: true,
		      buttons: {
		        "Activeer account": function() {
		        $.get('http://localhost:8000/active/' + id, function(){
		        	window.location.href = 'http://localhost:8000/customers';
				})
				.fail(function(jqXHR){
					alert('error, probeer opnieuw' + jqXHR.responseText);
					$('#error').html(jqXHR.responseText);
				})
		          $( this ).dialog( "close" );
		        },
		        Annuleer: function() {
		          $( this ).dialog( "close" );
		         }
		      }
			});
		}
	});

	var verzend = $('.verzend');

	verzend.click(function(e){

		var p = $(e.target).parent().find('p');
		var reportId = p.text();

		$.get('http://localhost:8000/send/' + reportId, function(){
		        	window.location.href = 'http://localhost:8000/reports';
				})
		.fail(function(jqXHR){
			alert('error, probeer opnieuw' + jqXHR.responseText);
			$('#error').html(jqXHR.responseText);
		})		
	});
});