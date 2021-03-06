$(document).on("ready", inicio);

	/*Funcion para limpiar cadenas de caracteres especiales*/
var normalizar = (function() {
	var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç",
	to   = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc",
	mapping = {};

	for(var i = 0, j = from.length; i < j; i++ )
		mapping[ from.charAt( i ) ] = to.charAt( i );

	return function( str ) {
		var ret = [];
		for( var i = 0, j = str.length; i < j; i++ ) {
			var c = str.charAt( i );
			if( mapping.hasOwnProperty( str.charAt( i ) ) )
				ret.push( mapping[ c ] );
			else
				ret.push( c );
		}
		return ret.join( '' );
	}

})();

function inicio () {

	/** JSON **/	
	$.getJSON('http://localhost:8000/hola', function(data) {
		console.log('algo');
	}, function (data) {
		console.log('mierda');
	});

	/*
	* Selects anidados con chained jquery
	*/
	$("#slc_nombre_componente").chained("#slc_componente");
	
	/*
	* Input type number spinner
	*/
	$('.spinner').spinedit({
		value: $(this).val()
	});

	/*
	* Input type number slider
	*/
	$('.slider').slider();

	/*
	* Agregar conexion, computador -> monitor
	*/
	$('#btn_com_mon').on('click', function(){
		var accion = $("#slc_com_mon").data('url');
		var id = $("#slc_com_mon").val();
		var parametros = {"id_monitor":id}
		$.ajax({
	        type: "POST",
	        url: accion,
	        data: parametros,
	        dataType: "html",
	        async: false,
	        success: function (datos) {
	        	location.reload(true);
	        }
		});
	});

	/*
	* Agregar conexion, computador -> impresora
	*/
	$('#btn_com_imp').on('click', function(){
		var accion = $("#slc_com_imp").data('url');
		var id = $("#slc_com_imp").val();
		var parametros = {"id_impresora":id}
		$.ajax({
	        type: "POST",
	        url: accion,
	        data: parametros,
	        dataType: "html",
	        async: false,
	        success: function (datos) {
	        	location.reload(true);
	        }
		});
	});

	/*
	* Agregar conexion, computador -> dispositivo
	*/
	$('#btn_com_dis').on('click', function(){
		var accion = $("#slc_com_dis").data('url');
		var id = $("#slc_com_dis").val();
		var parametros = {"id_dispositivo":id}
		$.ajax({
	        type: "POST",
	        url: accion,
	        data: parametros,
	        dataType: "html",
	        async: false,
	        success: function (datos) {
	        	location.reload(true);
	        }
		});
	});

	/*
	* Agregar conexion, computador -> software
	*/
	$('#btn_com_soft').on('click', function(){
		var accion = $("#slc_com_soft").data('url');
		var id = $("#slc_com_soft").val();
		var parametros = {"id_software":id}
		$.ajax({
	        type: "POST",
	        url: accion,
	        data: parametros,
	        dataType: "html",
	        async: false,
	        success: function (datos) {
	        	location.reload(true);
	        }
		});
	});


	/*
	* Generador de correos
	*/

	$('#frmCorreo #crear-correo').on('click', function(){
		//recojo los valores para procesarlos
		var nombre1 = normalizar($('#frmCorreo #nombre1').val()).replace(" ", "").toLowerCase();
		var nombre2 = normalizar($('#frmCorreo #nombre2').val()).replace(" ", "").toLowerCase();
		var apellido1 = normalizar($('#frmCorreo #apellido1').val()).replace(" ", "").toLowerCase();
		var apellido2 = normalizar($('#frmCorreo #apellido2').val()).replace(" ", "").toLowerCase();

		var j = 0;
		var combinaciones = new Array();
		var correo = '@blancoynegromasivo.com.co';
		var accion = $(this).data('url');
		var validador = true;

		combinaciones[j++] = nombre1 +'.'+ apellido1;
		combinaciones[j++] = nombre1 +'.'+ apellido2;
		//si tiene segundo nombre
		if(nombre2.length > 0){
			combinaciones[j++] = nombre2 +'.'+ apellido1;
			combinaciones[j++] = nombre2 +'.'+ apellido2;
		}
		for(var i = 0; i<combinaciones.length; i++){
			if(validador){
				correoparcial = combinaciones[i]+correo;
				var parametros = {"correo":correoparcial}
				//envio la peticion ajax
				$.ajax({
			        type: "POST",
			        url: accion,
			        data: parametros,
			        dataType: "html",
			        async: false,
			        success: function(datos){
			        	if(datos == 0){
			        		validador = false;
			        	}
			        }
				});
			}
		}
		//muestro el mensaje para crear
		$('#correo-final').empty();
		$('#correo-final').append(correoparcial);
		$('#correo-final2').val(correoparcial);
		$('#informacion-correo').fadeIn(500);
	});

	/*
	* Textareas con un editor de texto html
	*/ 
	$('.editorhtml').wysihtml5({
		"font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
		"emphasis": true, //Italics, bold, etc. Default true
		"lists": false, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
		"html": false, //Button which allows you to edit the generated HTML. Default false
		"link": false, //Button to insert a link. Default true
		"image": false, //Button to insert an image. Default true,
		"color": false //Button to change color of font
	});


	/*
	* Toltip para botones de secciones
	*/
	$('#btn_agregar, #btn_imprimir').tooltip();

	$('.popover-tarea').popover({trigger:'hover'})

	/*
	* swicth, verificando estado y enviando para modificarlo (tareas)
	*/
	$('.terminada').on('switch-change', function (e, data) {
	    var value = data.value;
	    var id = $(this).data('id');
	    var accion = $(this).data('accion');
	    //fecha de inicio
	    var fecha = $(this).data('fecha');
	    if(value){
	    	var valor = 1;
	    }else{
	    	var valor = 0;
	    }
	    //pasar por ajax
	    var parametros = {"id":id, "valor":valor, "fecha":fecha}
		$.ajax({
	        type: "POST",
	        url: accion,
	        data: parametros,
	        dataType: "html",
	        async: false,
	        success: function (datos) {
	        	//console.log(datos);
	        },
	        error: function(datos){
	        	//console.log(datos);
	        }
		});
    });

    /*
	* swicth, verificando estado y enviando para modificarlo (tareas)
	*/
	$('.correo-creado').on('switch-change', function (e, data) {
	    var value = data.value;
	    var id = $(this).data('id');
	    var accion = $(this).data('accion');
	    if(value){
	    	var valor = 1;
	    }else{
	    	var valor = 0;
	    }
	    //pasar por ajax
	    var parametros = {"id":id, "valor":valor}
		$.ajax({
	        type: "POST",
	        url: accion,
	        data: parametros,
	        dataType: "html",
	        success: function(datos){
				console.log('Tarea = ' + datos);
			}
		});
    });

	/*
	* Calendario
	*/
	$('.fecha').datetimepicker({});

	/*
	* Validacion de formularios en la vista
	 */	
	$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();

	/*
	* Busca y reemplaza en select con los computadores
	 */
	$("#icomputador").on("keyup", function(){
		var valor = $("#icomputador").val();
		var accion = $("#icomputador").data('url');
		var todos = 0;

		if(valor == String.fromCharCode(42)){
			todos = 1;
		}
		var parametros = {"valor":valor, "todos":todos}
		$.ajax({
	        type: "POST",
	        url: accion,
	        data: parametros,
	        dataType: "html",
	        success: function(datos){
				$("#computador").children().remove();
				$("#computador").append(datos);
			}
		});
	});

	/*
	* Busca y reemplaza en select con las ubicaciones
	 */
	$("#iubicacion").on("keyup", function(){
		var valor = $("#iubicacion").val();
		var accion = $("#iubicacion").data('url');
		var todos = 0;

		if(valor == String.fromCharCode(42)){
			todos = 1;
		}
		var parametros = {"valor":valor, "todos":todos}
		$.ajax({
	        type: "POST",
	        url: accion,
	        data: parametros,
	        dataType: "html",
	        success: function(datos){
				$("#ubicacion").children().remove();
				$("#ubicacion").append(datos);
			}
		});
	});

	/*
	* Busca y reemplaza en select con las ubicaciones
	 */
	$("#idepartamento").on("keyup", function(){
		var valor = $("#idepartamento").val();
		var accion = $("#idepartamento").data('url');
		var todos = 0;

		if(valor == String.fromCharCode(42)){
			todos = 1;
		}

		var parametros = {"valor":valor, "todos":todos}
		$.ajax({
	        type: "POST",
	        url: accion,
	        data: parametros,
	        dataType: "html",
	        success: function(datos){
				$("#departamento").children().remove();
				$("#departamento").append(datos);
			}
		});
	});

	/*
	* Busca y reemplaza en select con las cargos
	 */
	$("#icargo").on("keyup", function(){
		var valor = $("#icargo").val();
		var accion = $("#icargo").data('url');
		var todos = 0;

		if(valor == String.fromCharCode(42)){
			todos = 1;
		}

		var parametros = {"valor":valor, "todos":todos}
		$.ajax({
	        type: "POST",
	        url: accion,
	        data: parametros,
	        dataType: "html",
	        success: function(datos){
				$("#cargo").children().remove();
				$("#cargo").append(datos);
				console.log('Listo');
			}
		});
	});

	/*
	* Busca y reemplaza en select con las ubicaciones
	 */
	$("#iusuario").on("keyup", function(){
		var valor = $("#iusuario").val();
		var accion = $("#iusuario").data('url');
		var todos = 0;

		if(valor == String.fromCharCode(42)){
			todos = 1;
		}
		var parametros = {"valor":valor, "todos":todos}
		$.ajax({
	        type: "POST",
	        url: accion,
	        data: parametros,
	        dataType: "html",
	        success: function(datos){
				$("#usuario").children().remove();
				$("#usuario").append(datos);
			}
		});
	});

	/*
	* Tablas paginadas
	 */
	oTable = $('.tabla').dataTable({
		"oLanguage": {
			"oPaginate": {
				"sNext": "",
				"sPrevious": ""
			},
			"sSearch": "Buscar",
			"sZeroRecords": "No se encontraron resultados",
			"sInfo": "Mostrando _TOTAL_ de (_START_ a _END_)",
			"sInfoEmpty": "Mostrando 0 de ",
			"sInfoFiltered": "_MAX_"
		},
		"iDisplayLength": 10
	});
	$.extend( $.fn.dataTableExt.oStdClasses, {
	    "sWrapper": "dataTables_wrapper form-inline"
	} );
}