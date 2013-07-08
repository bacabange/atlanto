<?php 
//Titulos
$lang['titulo'] = 'SCI | Inicio';
$lang['titulo_usuarios'] = 'SCI | Usuarios';
$lang['titulo_nuevo_usuario'] = 'SCI | Nuevo Usuario';
$lang['titulo_tablas'] = 'SCI | Tablas';
$lang['titulo_404'] = 'SCI | Error 404';
$lang['titulo_eliminar_usu'] = 'Eliminar usuario';

//Pagina index
$lang['index_titulo_menu'] = 'Sistema de Control de Informática';
$lang['index_titulo_ticket'] = 'Crear Ticket Rápido';
$lang['index_info_ticket'] = 'Esta opción usela cuando necesita enviar un ticket de prioridad alta o urgente';


//Menu Principal
$lang['men_escritorio'] = 'Escritorio';
$lang['men_inventario'] = 'Inventario';
$lang['men_tickets'] = 'Tickets';
$lang['men_tareas'] = 'Tareas';
$lang['men_financiero'] = 'Financiero';
$lang['men_administracion'] = 'Administración';
$lang['men_reportes'] = 'Reportes';
$lang['men_config'] = 'Configuración';
$lang['men_cuenta'] = 'Cuenta';
	//Submenu Inventario
		$lang['men_sub_compu'] = 'Computadores';
		$lang['men_sub_monitores'] = 'Monitores';
		$lang['men_sub_red'] = 'Equipos de red';
		$lang['men_sub_impresoras'] = 'Impresoras';
		$lang['men_sub_telefonos'] = 'Teléfonos';
		$lang['men_sub_dispositivos'] = 'Dispositivos';
		$lang['men_sub_software'] = 'Software';
	//Submenu Tickets
		$lang['men_sub_abiertos'] = 'Abiertos';
		$lang['men_sub_respondidos'] = 'Respondidos';
		$lang['men_sub_cerrados'] = 'Cerrados';	
		$lang['men_sub_mis_tickets'] = 'Mis Tickets';	
		$lang['men_sub_crear'] = 'Crear Ticket';
	//Submenu Tareas
		$lang['men_sub_nueva'] = 'Nueva Tarea';
		$lang['men_sub_mis'] = 'Mis Tareas';
	//Submenu Financiero
		$lang['men_sub_proveedores'] = 'Proveedores';
		$lang['men_sub_contratos'] = 'Contratos';
	//Submenu Administracion
		$lang['men_sub_usuarios'] = 'Usuarios';
		$lang['men_sub_perfiles'] = 'Perfiles';
		$lang['men_sub_tablas'] = 'Tablas';
		$lang['men_sub_resp'] = 'Respuestas Automaticas';
	//Submenu Configuracion
		$lang['men_sub_general'] = 'General';
		$lang['men_sub_noti'] = 'Notificaciones';
		$lang['men_sub_rol'] = 'Roles';
	//Submenu Cuenta
		$lang['men_sub_configuracion'] = 'Configuracion';
		$lang['men_sub_salir'] = 'Salir';

//Menu Tablas
	$lang['men_tab_cargos'] = 'Cargos';
	$lang['men_tab_dep'] = 'Departamentos';
	$lang['men_tab_ubicacion'] = 'Ubicaciones';

//Formularios
	//Labels
		$lang['lbl_usuario'] = 'Usuario';
		$lang['lbl_password'] = 'Contraseña';
		$lang['lbl_comf_password'] = 'Comfirmar Contraseña';
		$lang['lbl_asunto'] = 'Asunto';
		$lang['lbl_mensaje'] = 'Mensaje';
		$lang['lbl_mostrar'] = 'Mostrar';
		$lang['lbl_imprimir'] = 'Imprimir';
		$lang['lbl_nombre'] = 'Nombre';
		$lang['lbl_apellido'] = 'Apellido';
		$lang['lbl_telefono'] = 'Teléfono';
		$lang['lbl_mail'] = 'Email';
		$lang['lbl_ubicacion'] = 'Ubicación';
		$lang['lbl_cargo'] = 'Cargo';
		$lang['lbl_departamento'] = 'Departamento';
		$lang['lbl_activado'] = 'Activado';
		$lang['lbl_rol'] = 'Rol de Usuario';
		$lang['lbl_notas'] = 'Nota';
		$lang['lbl_descripcion'] = 'Descripcion';
		$lang['lbl_debajo'] = 'Debajo de';
		
	//Botones
		$lang['btn_cerrar'] = 'Cerrar';
		$lang['btn_entrar'] = 'Entrar';
		$lang['btn_guardar'] = 'Guardar';
		$lang['btn_cancelar'] = 'Cancelar';
		$lang['btn_eliminar'] = 'Eliminar';
		$lang['btn_enviar'] = 'Enviar';
		$lang['btn_iniciar_sesion'] = 'Iniciar Sesión';
		$lang['btn_ticket_rapido'] = 'Ticket Rápido';

	//links
		$lang['lnk_agregar'] = 'Agregar Nuevo';
		$lang['lnk_new_plantilla'] = 'Nueva Plantilla';

	//Placeholder
		$lang['plc_buscar'] = 'Buscar...';
		$lang['plc_nota_interna'] = 'Las notas internas solo son visualizadas por los administradores.';
		$lang['plc_descripcion'] = 'Pequeña descripcion';

	//Selects
		$lang['slc_imp_pdf'] = 'Imprimir PDF';
		$lang['slc_imp_excel'] = 'Imprimir Excel';

	//Tabs
		$lang['tab_usu_usuario'] = 'Usuario';
		$lang['tab_car_cargos'] = 'Cargos';

		

//Mensajes
	//Error
	$lang['msj_error_sesion'] = 'Eror, sus credenciales no son correctas. Intentelo de nuevo';
	$lang['msj_error_resultado'] = 'Sin resultado';
	$lang['msj_error_guardar_usu'] = 'Ocurrio un error al tratar de guardar';
	$lang['msj_error_modificar_usu'] = 'Ocurrio un error al tratar modificar';
	$lang['msj_error_eliminar_usu'] = 'Ocurrio un error al tratar eliminar';
	$lang['msj_error_eliminar_car'] = 'Ocurrio un error al tratar eliminar';
	//Exito
	$lang['msj_exito'] = '<strong>Éxito</strong>';
	$lang['msj_ext_guardar_usu'] = 'fue creado en la base de datos';
	$lang['msj_ext_modificar_usu'] = 'fue modificado en la base de datos';
	$lang['msj_ext_eliminar_usu'] = 'Usuario eliminado.';
	$lang['msj_ext_eliminar_car'] = 'Cargo eliminado.';
	$lang['msj_ext_eliminar_dep'] = 'Departamento eliminado.';
	$lang['msj_ext_eliminar_ubi'] = 'Ubicación eliminada.';
	//Preguntas
	$lang['msj_eliminar'] = '¿Desea eliminar el usuario de ';
	$lang['msj_eliminar_car'] = '¿Desea eliminar el cargo ';
	$lang['msj_eliminar_dep'] = '¿Desea eliminar el departamento ';
	$lang['msj_eliminar_ubi'] = '¿Desea eliminar la ubicación ';
	

//Log
	//tipos
	$lang['log_tipo_error'] = 'Error';
	$lang['log_tipo_precaucion'] = 'Precaucion';
	$lang['log_tipo_info'] = 'Información';

	//Descripciones
	$lang['log_error_login'] = 'Fallo en el inicio de sesion.';

//Tablas
	$lang['tab_empty'] = '&nbsp;';
	$lang['tab_nombre'] = 'Nombre';
	$lang['tab_nombre'] = 'Nombre';
	$lang['tab_descripcion'] = 'Descripcion';
	$lang['tab_departamento'] = 'Departamento';
	$lang['tab_lugar'] = 'Lugar/Ubicación';
	$lang['tab_mail'] = 'Email';
	$lang['tab_acciones'] = 'Acciones';
	