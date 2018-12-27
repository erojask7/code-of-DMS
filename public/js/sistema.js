$(document).ready(function(){
	if ($.isFunction($.fn.datepicker)){	
		$.datepicker.regional['es'] = {
			      closeText: 'Cerrar',
			      prevText: 'Ant',
			      nextText: 'Sig',
			      currentText: 'Hoy',
			      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			      monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
			      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
			      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
			      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
			      weekHeader: 'Sm',
			      dateFormat: 'dd-mm-yy',
			      firstDay: 1,
			      isRTL: false,
			      showMonthAfterYear: false,
			      yearSuffix: ''
				};	
		$.datepicker.setDefaults( $.datepicker.regional[ "es" ] );	
		$(".date input").datepicker({
		      changeMonth: true,
		      changeYear: true		
		});
	}

	$('.number_sistema').validCampoFranz('0123456789');
});

//VALIDACIÓN
(function( $ ) {
	$.fn.validCampoFranz = function(cadena) {
    	$(this).on({
			keypress : function(e){
    			var key = e.which,
    				keye = e.keyCode,
    				tecla = String.fromCharCode(key).toLowerCase(),
    				letras = cadena;
			    if(letras.indexOf(tecla)==-1 && keye!=9&& (key==37 || keye!=37)&& (keye!=39 || key==39) && keye!=8&& keye!=13  && (keye!=46 || key==46) || key==161){
			    	e.preventDefault();
			    }
			}
		});
	};
})( jQuery );


//Abrir y Cerrar sub secciones
$("button#btnCerrar").click(function(){
	$(this).parent().parent().parent().parent().parent().css("display","none");
});

$('#btn_abrir_div_frm').click(function(e){
	$("#div_frm").css("display","block");
});
$("#div_modal button[name='btn2'], #div_modal button.close").click(function(e){//Confirmación de eliminación
	$(this).parents("div.modal").css("display","none");
});		    

//