$(document).ready(function(){
	$("body#menu_1 #tabla_lista form[name='frm_eliminar1'] button").click(function(e){
		e.preventDefault();	//Si tiene dependencias, se muestra modal, sino, eliminar
		var form=$(this).parent();
		$.ajax({
	        type: "POST",
	        url: base_url+"/oficinas/verdependencias",
	        data: form.serialize(),
	        success: function(data){
	        	if(data.nrohijos>0){
	        		$("body#menu_1 #div_modal").css("display","block");
	        		$("body#menu_1 #div_modal").find("h4.modal-title").text("¿Está seguro?");
	        		$("body#menu_1 #div_modal").find("div.modal-body").html("<p>" +
	        			"Si continúa, también se darán de baja los <strong>"+data.nrohijos +" estantes</strong> registrados en ésta Oficina"+
	        			" y los <strong>archivadores</strong> y <strong>expedientes</strong> que contengan. Si luego desea darlos de alta, deberá hacerlo individualmente.</p>");
	        	}else form.submit();
	        	$("body#menu_1 #div_modal button[name='btn1']").click(function(e){//Confirmación de eliminación
	        		form.submit();
	        	});	        	
	        },
			error: function(errores, quepaso, otroobj){
	          console.log(errores);
	        }	        
	    });    	
	});

});