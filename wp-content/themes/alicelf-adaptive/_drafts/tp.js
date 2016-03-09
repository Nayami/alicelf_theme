jQuery(document).ready(function ($){
	$.ajax({
		url : ajaxurl,
		type : "POST",
		data : {
			action : "ajx20161009111025"
		},
		//dataType : "html",
		beforeSend : function(){},
		success : function(data) {},
		error     : function(jqXHR, textStatus, errorThrown) {
			alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
		}
	});
});