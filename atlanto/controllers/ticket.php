<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ticket extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array(
			'form',
			'email',
			'download'
		));
		$this->load->model(array(
			'ticket_model',
			'estadoticket_model',
			'prioridad_model',
			'usuario_model',
			'historial_model',
			'computador_model',
		));
		$this->load->library(array('horas'));
	}

	
	/***** PANEL ADMINISTRADOR *****/
	public function index($admin = FALSE)
	{
		$this->acceso_restringido();
		if ($this->session->userdata('roles')->id != 1) {
			redirect('panel/escritorio', 'refresh');
		}
		$this->breadcrumbs->push('Tickets', '/ticket');
		$this->breadcrumbs->unshift($this->lang->line('bre_inicio'), '/panel/escritorio');
		$breadcrumbs = $this->breadcrumbs->show();

		if ($admin) {
			$tickets = $this->ticket_model->get_tickets(FALSE, $this->session->userdata('id'));
		}else{
			$tickets = $this->ticket_model->get_tickets();			
		}

		$data = array(
			'titulo' => 'Tickets',
			'content' => 'tickets/admin/index_view',
			'breadcrumbs' => $breadcrumbs,
			'tickets' => $tickets
		);
		
		$this->load->view('template', $data);
	}

	public function estado($estado)
	{
		$this->acceso_restringido();
		if ($this->session->userdata('roles')->id != 1) {
			redirect('panel/escritorio', 'refresh');
		}
		$this->breadcrumbs->push('Tickets', '/ticket');
		$this->breadcrumbs->unshift($this->lang->line('bre_inicio'), '/panel/escritorio');
		$breadcrumbs = $this->breadcrumbs->show();

		$tickets = $this->ticket_model->get_tickets_estado($estado);

		$data = array(
			'titulo' => 'Tickets',
			'content' => 'tickets/admin/index_view',
			'breadcrumbs' => $breadcrumbs,
			'tickets' => $tickets
		);
		
		$this->load->view('template', $data);
	}
	
	public function ver_ticket($id)
	{
		$this->acceso_restringido();
		if ($this->session->userdata('roles')->id != 1) {
			redirect('panel/escritorio', 'refresh');
		}
		$this->breadcrumbs->push('Tickets', '/ticket');
		$this->breadcrumbs->push('Ticket #'.$id, '/ticket/ver_ticket/'.$id);
		$this->breadcrumbs->unshift($this->lang->line('bre_inicio'), '/panel/escritorio');
		$breadcrumbs = $this->breadcrumbs->show();

		$ticket = $this->ticket_model->get_tickets($id);
		$prioridad = $this->prioridad_model->get_todos();
		$mensajes = $this->ticket_model->get_mensajes($id);
		$archivos = $this->ticket_model->get_archivos(array('id_ticket' => $id));
		$admin = $this->usuario_model->get_administradores();
		$estados = $this->estadoticket_model->get_todos();
		
		$data = array(
			'titulo' => 'Ticket #'.$id,
			'content' => 'tickets/admin/ver_view',
			'breadcrumbs' => $breadcrumbs,
			'accion' => site_url('ticket/responder_admin'),
			'accion_asignar' => site_url('ticket/asignar/'.$id),
			'accion_prioridad' => site_url('ticket/cambiar_prioridad/'.$id),
			'accion_estado' => site_url('ticket/cambiar_estado/'.$id),
			'accion_compu' => site_url('ticket/relacionar_computador/'.$id),
			'ticket' => $ticket,
			'archivos' => $archivos,
			'mensajes' => $mensajes,
			'prioridad' => $prioridad,
			'admin' => $admin,
			'estados' => $estados
		);
		
		$this->load->view('template', $data);
	}

	public function relacionar_computador($id)
	{
		$datos_recibidos = $this->input->post(NULL, TRUE);
		if ($this->session->userdata('roles')->id != 1) {
			redirect('panel/escritorio', 'refresh');
		}
		$datos = array(
			'computador_relacionado' => $datos_recibidos['computador']
		);

		$this->ticket_model->update($id, $datos);
		//traer computador de usuario de ticket

		$ticket = $this->ticket_model->get_ticket_usuario($id);
		$compu = $this->computador_model->get_computador(array('id_usuario' => $ticket->id_usuario));

		//Historial**
		$log = $this->historial_model->save(array(
			'fecha' => date("Y-m-d H:i:s"),
			'id_usuario' => $this->session->userdata('id'),
			'id_componente' => $compu->id,
			'componente' => 'computador',
			'descripcion' => 'Ticket #'.$id.' Relacionado',
			'ant_valor' => '',
			'new_valor' => ''
		));
		
		$this->session->set_flashdata('mensaje', 'El computador del usuario fue relacionado al ticket.');
		$this->session->set_flashdata('tipo_mensaje', 'exito');
		
		redirect('ticket/ver_ticket/'.$id, 'refresh');
	}

	public function asignar($id)
	{
		$this->acceso_restringido();
		if ($this->session->userdata('roles')->id != 1) {
			redirect('panel/escritorio', 'refresh');
		}
		$datos_recibidos = $this->input->post(NULL, TRUE);

		$datos = array(
			'id_usuario_asignado' => $datos_recibidos['usuario']
		);
		$this->ticket_model->update($id, $datos);
		
		$usuario = $this->usuario_model->get_usuario(array('id' => $datos_recibidos['usuario']));

		$html = "<p>El Ticket #".$id." es asignado a su nombre</p>";

		if(enviar('SCI - Se te ha asignado el Ticket #'.$id, 'Ticket #'.$id, $html, $usuario->email, array('correo' => 'informatica@blancoynegromasivo.com.co','nombre' => 'Informatica'))){
			$this->session->set_flashdata('mensaje', 'El ticket fue asignado.');
			$this->session->set_flashdata('tipo_mensaje', 'exito');
			
			redirect('ticket/ver_ticket/'.$id, 'refresh');
		}
	}

	public function cambiar_prioridad($id)
	{
		$this->acceso_restringido();
		if ($this->session->userdata('roles')->id != 1) {
			redirect('panel/escritorio', 'refresh');
		}
		$datos_recibidos = $this->input->post(NULL, TRUE);

		$datos = array(
			'id_prioridad' => $datos_recibidos['prioridad']
		);
		$this->ticket_model->update($id, $datos);
		//enviar correo

		$this->session->set_flashdata('mensaje', 'Prioridad del ticket ha sido cambiada');
		$this->session->set_flashdata('tipo_mensaje', 'exito');
		
		redirect('ticket/ver_ticket/'.$id, 'refresh');
	}

	public function cambiar_estado($id)
	{
		$this->acceso_restringido();
		if ($this->session->userdata('roles')->id != 1) {
			redirect('panel/escritorio', 'refresh');
		}
		$datos_recibidos = $this->input->post(NULL, TRUE);

		$datos = array(
			'id_estado' => $datos_recibidos['estado']
		);
		//Si es solucionado calcular tiempo en el que se demoro en solucionar el ticket
		if ($datos_recibidos['estado'] == 2) {
			//traer ticket
			$ticket = $this->ticket_model->get_ticket_usuario($id);

			//Calcula la duracion que tuvo la tarea
    		$horas = new Horas();
    		$fecha_actual = date("Y-m-d H:i:s");
    		$tiempo = $horas->calcular($ticket->fecha_creado, $fecha_actual);
    		$datos['duracion'] = $tiempo['minutos'];
    		$datos['fecha_solucion'] = $fecha_actual;
		}
		$this->ticket_model->update($id, $datos);
		//enviar correo
		$usuario = $this->ticket_model->get_ticket_usuario($id);
		$estado = $this->estadoticket_model->get_estado(array('id' => $datos_recibidos['estado']));

		$html = "<p>Se ha cambiado de estado el Ticket #".$id." a ".$estado->nombre." </p>";

		if(enviar('SCI - Cambio de estado del Ticket #'.$id, 'Ticket #'.$id, $html, $usuario->correo, array('correo' => 'informatica@blancoynegromasivo.com.co','nombre' => 'Informatica'))){
			$this->session->set_flashdata('mensaje', 'Estado del ticket ha sido cambiado');
			$this->session->set_flashdata('tipo_mensaje', 'exito');
			
			redirect('ticket/ver_ticket/'.$id, 'refresh');
		}	
			
	}

	public function responder_admin()
	{
		$this->acceso_restringido();
		if ($this->session->userdata('roles')->id != 1) {
			redirect('panel/escritorio', 'refresh');
		}
		$datos_recibidos = $this->input->post(NULL, TRUE);
		$config = array(
			array(
				'field' => 'mensaje',
				'label' => 'Mensaje',
				'rules' => 'required'
			)
		);
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('mensaje', $this->lang->line('msj_error_guardar'));
			$this->session->set_flashdata('tipo_mensaje', 'error');
			
			redirect('ticket/ver_ticket/'.$datos_recibidos['id_ticket'], 'refresh');
		}else{

			$datos = array(
				'id_ticket' => $datos_recibidos['id_ticket'],
				'mensaje' => $datos_recibidos['mensaje'],
				'fecha' => date('Y-m-d H:i:s'),
				'id_usuario' => $this->session->userdata('id')
			);

			$ticket = $this->ticket_model->get_tickets($datos_recibidos['id_ticket']);
			if ($ticket->id_estado == 3) {
				$this->ticket_model->update($datos_recibidos['id_ticket'], array('id_estado' => 1));
			}
			
			$mensaje = $this->ticket_model->save_mensaje($datos);

			if($mensaje){
				$ticket = $this->ticket_model->get_ticket_usuario($datos_recibidos['id_ticket']);
				
				$html = "<p><i>".$datos_recibidos['mensaje']." </i></p>";
				$html .= "<small>Estado del Ticket: ".$ticket->estado."</small>";

				if(enviar('SCI - Respuesta en Ticket #'.$datos_recibidos['id_ticket'], 'Ticket #'.$datos_recibidos['id_ticket'], $html, $ticket->correo, array('correo' => 'informatica@blancoynegromasivo.com.co','nombre' => 'Informatica'))){
					$this->session->set_flashdata('mensaje', 'Su respuesta ha sido enviada.');
					$this->session->set_flashdata('tipo_mensaje', 'exito');
					redirect('ticket/ver_ticket/'.$datos_recibidos['id_ticket'], 'refresh');
				}
			}
		}
	}
	
	/***** PANEL USUARIO *****/
	public function mis_tickets()
	{
		$this->acceso_restringido();
		
		$this->breadcrumbs->push('Mis Tickets', '/ticket');
		$this->breadcrumbs->unshift($this->lang->line('bre_inicio'), '/panel/escritorio');
		$breadcrumbs = $this->breadcrumbs->show();
		
		$tickets = $this->ticket_model->get_mis_tickets($this->session->userdata('id'));
		
		$data = array(
			'titulo' => 'Tickets',
			'content' => 'tickets/user/mis_tickets_view',
			'breadcrumbs' => $breadcrumbs,
			'tickets' => $tickets
		);
		
		$this->load->view('template', $data);
	}

	public function ver($id)
	{
		$this->acceso_restringido();
		
		$this->breadcrumbs->push('Mis Ticket', '/ticket/mis_tickets');
		$this->breadcrumbs->push('Ticket #'.$id, '/ticket/ver/'.$id);
		$this->breadcrumbs->unshift($this->lang->line('bre_inicio'), '/panel/escritorio');
		$breadcrumbs = $this->breadcrumbs->show();

		$ticket = $this->ticket_model->get_mis_tickets($this->session->userdata('id'), $id);
		$mensajes = $this->ticket_model->get_mensajes($id);
		$archivos = $this->ticket_model->get_archivos(array('id_ticket' => $id));
		
		$data = array(
			'titulo' => 'Ticket #'.$id,
			'content' => 'tickets/user/ver_view',
			'breadcrumbs' => $breadcrumbs,
			'accion' => site_url('ticket/responder'),
			'accion_c' => site_url('ticket/calificar'),
			'ticket' => $ticket,
			'archivos' => $archivos,
			'mensajes' => $mensajes
		);
		
		$this->load->view('template', $data);
	}

	public function estado_ticket($estado)
	{
		$this->acceso_restringido();
		$this->breadcrumbs->push('Tickets', '/ticket');
		$this->breadcrumbs->unshift($this->lang->line('bre_inicio'), '/panel/escritorio');
		$breadcrumbs = $this->breadcrumbs->show();

		$tickets = $this->ticket_model->get_tickets_estado($estado, $this->session->userdata('id'));

		$data = array(
			'titulo' => 'Tickets',
			'content' => 'tickets/user/mis_tickets_view',
			'breadcrumbs' => $breadcrumbs,
			'tickets' => $tickets
		);
		
		$this->load->view('template', $data);
	}

	public function responder()
	{
		$this->acceso_restringido();
		$datos_recibidos = $this->input->post(NULL, TRUE);
		$config = array(
			array(
				'field' => 'mensaje',
				'label' => 'Mensaje',
				'rules' => 'required'
			)
		);
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('mensaje', $this->lang->line('msj_error_guardar'));
			$this->session->set_flashdata('tipo_mensaje', 'error');
			
			redirect('ticket/ver/'.$datos_recibidos['id_ticket'], 'refresh');
		}else{
			$datos = array(
				'id_ticket' => $datos_recibidos['id_ticket'],
				'mensaje' => $datos_recibidos['mensaje'],
				'fecha' => date('Y-m-d H:i:s'),
				'id_usuario' => $this->session->userdata('id')
			);
			
			$mensaje = $this->ticket_model->save_mensaje($datos);

			if($mensaje){
				$admins = $this->usuario_model->get_administradores();
				$usuarios = array('informatica@blancoynegromasivo.com.co');
				foreach ($admins as $row) {
					array_push($usuarios, $row->email);
				}

				$html = "<p><i>".$datos_recibidos['mensaje']."</i></p>";

				if(enviar('SCI - Ha sido respondido el Ticket #'.$datos_recibidos['id_ticket'], 'Ticket #'.$datos_recibidos['id_ticket'], $html, $usuarios, array('correo' => 'informatica@blancoynegromasivo.com.co','nombre' => 'Informatica'))){
					$this->session->set_flashdata('mensaje', 'Su respuesta ha sido enviada.');
					$this->session->set_flashdata('tipo_mensaje', 'exito');
					redirect('ticket/ver/'.$datos_recibidos['id_ticket'], 'refresh');
				}
				
			}
		}
	}

	public function calificar()
	{
		$this->acceso_restringido();
		$datos_recibidos = $this->input->post(NULL, TRUE);
		$config = array(
			array(
				'field' => 'solucion',
				'label' => 'Solucion',
				'rules' => 'required'
			)
		);
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('mensaje', $this->lang->line('msj_error_guardar'));
			$this->session->set_flashdata('tipo_mensaje', 'error');
			
			redirect('ticket/ver/'.$datos_recibidos['id_ticket'], 'refresh');
		}else{
			$datos = array(
				'id_ticket' => $datos_recibidos['id_ticket'],
				'observacion' => $datos_recibidos['observacion'],
				'solucion' => $datos_recibidos['solucion'],
				'calificacion' => $datos_recibidos['calificacion'],
				'fecha' => date('Y-m-d H:i:s')
			);
			
			$calificacion = $this->ticket_model->save_calificacion($datos);

			if($calificacion){
				$admins = $this->usuario_model->get_administradores();
				$usuarios = array('informatica@blancoynegromasivo.com.co');
				foreach ($admins as $row) {
					array_push($usuarios, $row->email);
				}

				$solucion = ($datos_recibidos['solucion'] == 1) ? 'Si' : 'No';

				$html = '<ul>';
				$html .= '<li>Solucionó el problema: '.$solucion.'</li>';
				$html .= '<li>Calificación: '.$datos_recibidos['calificacion'].'</li>';
				$html .= '<li><i>Observación: '.$datos_recibidos['observacion'].'</i></li>';
				$html .= '<ul>';

				if(enviar('SCI - Calificación en Ticket #'.$datos_recibidos['id_ticket'], 'Ticket #'.$datos_recibidos['id_ticket'], $html, $usuarios, array('correo' => 'informatica@blancoynegromasivo.com.co','nombre' => 'Informatica'))){
				
					$ticket = $this->ticket_model->update($datos_recibidos['id_ticket'], array('calificado' => 1));
					$this->session->set_flashdata('mensaje', 'Gracias por su calificación, estaremos trabajando para mejorar.');
					$this->session->set_flashdata('tipo_mensaje', 'exito');
					redirect('ticket/ver/'.$datos_recibidos['id_ticket'], 'refresh');
				}
			}
		}
	}
	
	public function nuevo()
	{
		$this->acceso_restringido();
		
		$this->breadcrumbs->push('Nuevo Ticket', '/ticket/nuevo');
		$this->breadcrumbs->unshift($this->lang->line('bre_inicio'), '/panel/escritorio');
		$breadcrumbs = $this->breadcrumbs->show();
		
		$data = array(
			'titulo' => 'Nuevo Ticket',
			'content' => 'tickets/user/save_view',
			'breadcrumbs' => $breadcrumbs,
			'accion' => site_url('ticket/guardar')
		);
		
		$this->load->view('template', $data);
	}
	
	public function guardar()
	{
		$this->acceso_restringido();
		$config = array(
			array(
				'field' => 'asunto',
				'label' => 'Asunto',
				'rules' => 'required'
			),
			array(
				'field' => 'mensaje',
				'label' => 'Mensaje',
				'rules' => 'required'
			)
		);
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('mensaje', $this->lang->line('msj_error_guardar'));
			$this->session->set_flashdata('tipo_mensaje', 'error');
			
			redirect('ticket/nuevo', 'refresh');
		} else {
			$datos_recibidos = $this->input->post(NULL, TRUE);
			
			$datos = array(
				'asunto' => $datos_recibidos['asunto'],
				'mensaje' => $datos_recibidos['mensaje'],
				'id_origen' => 1,
				'id_usuario' => $this->session->userdata('id'),
				'id_estado' => 3,
				'id_prioridad' => 2,
				'ip' => get_ip_cliente(),
				'fecha_creado' => date('Y-m-d H:i:s')
			);
			
			$ticket = $this->ticket_model->save($datos);
			if ($ticket) {
				//Configuracion para subir archivo
				$config['upload_path']   = "./file/";
				$config['allowed_types'] = "jpg|png|doc|docx|xls|xlsx|txt|pdf|msg";
				$config['max_size']      = '4000';
				
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('archivo')) {
					$data['error'] = $this->upload->display_errors();
					$this->session->set_flashdata('mensaje', 'Error al intentar adjuntar el archivo. ' . $this->upload->display_errors() . ' El ticket fue creado, por favor no crear otro.');
					$this->session->set_flashdata('tipo_mensaje', 'error');
				} else {
					$archivo       = $this->upload->data();
					$datos_archivo = array(
						'nombre' => $archivo['file_name'],
						'url' => 'file/' . $archivo['file_name'],
						'mime' => $archivo['file_type'],
						'extension' => $archivo['file_ext'],
						'peso' => $archivo['file_size'],
						'id_ticket' => $ticket
					);
					$archivo       = $this->ticket_model->save_archivo($datos_archivo);
				}

				$admins = $this->usuario_model->get_administradores();
				$usuarios = array('informatica@blancoynegromasivo.com.co');
				foreach ($admins as $row) {
					array_push($usuarios, $row->email);
				}
				
				$html = "<p><strong>".$datos_recibidos['asunto']."</strong></p>";
				$html .= '<p><i>'.$datos_recibidos['mensaje'].'</i></p>';

				if(enviar('SCI - Nuevo Ticket #'.$ticket, 'Ticket #'.$ticket, $html, $usuarios, array('correo' => 'informatica@blancoynegromasivo.com.co','nombre' => 'Informatica'))){
					
					$link = anchor('ticket/ver/' . $ticket, 'Ticket #' . $ticket);
					$this->session->set_flashdata('mensaje', "Su " . $link . " " . 'Ha sido enviado con Éxito. Estaremos atentos para darle una pronta solución a su problema. Gracias.');
					$this->session->set_flashdata('tipo_mensaje', 'exito');
					redirect('ticket/mis_tickets', 'refresh');
				}
			} else {
				$this->session->set_flashdata('mensaje', $this->lang->line('msj_error_guardar'));
				$this->session->set_flashdata('tipo_mensaje', 'error');
				
				redirect('ticket/mis_tickets', 'refresh');
			}
			
		}
	}
	
	public function acceso_restringido()
	{
		if (!$this->session->userdata('ingresado')) {
			redirect('panel', 'refresh');
		}
	}
}