<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ubicacion extends CI_Controller {

	function __construct() {
		parent::__construct();
		//$this->load->helper(array('form'));
		//$this->load->library('form_validation');
		$this->load->model(array('ubicacion_model', 'rol_model'));
	}

	public function index()
	{

	}

	//Busca ubicaciones segun el parametro $valor y las manda a la vista para ser agregado al select
	public function ubicaciones_select()
	{
		$nombre = $this->input->post('valor');
		$todos = $this->input->post('todos');
		
		if ($todos == 1) {
			$ubicaciones = $this->ubicacion_model->get_todos();
		}else{
			$ubicaciones = $this->ubicacion_model->buscar($nombre);
		}

		if(!$ubicaciones){
			echo '<option value="">'.$this->lang->line('msj_error_resultado').'</option>';
		}else{
			foreach ($ubicaciones as $row) {
				echo '<option value="'.$row->id.'">'.$row->nombre.'</option>';
			}
		}
	}

	public function guardar()
	{
		$this->acceso_restringido();
		//reglas de validacion de formulario, en el server
		$config = array(
               array(
                     'field' => 'nombre',
                     'label' => 'Nombre',
                     'rules' => 'required'
                  )
        );

		$this->form_validation->set_rules($config); 

		if ($this->form_validation->run() == FALSE)
		{
		    $this->session->set_flashdata('mensaje', $this->lang->line('msj_error_guardar_usu'));
			$this->session->set_flashdata('tipo_mensaje', 'error');
			
			redirect('tabla', 'refresh');
		}
		else
		{
			$datos_recibidos = $this->input->post(NULL, TRUE);
			//Verificar nivel
			$datos_u = array('id' => $datos_recibidos['id_padre']);
			$ubicacion = $this->ubicacion_model->get_ubicacion($datos_u);
			$nivel = $ubicacion->nivel + 1;

			$datos = array(
				'nombre' => $datos_recibidos['nombre'],
				'id_padre' => $datos_recibidos['id_padre'],
				'descripcion' => $datos_recibidos['descripcion'],
				'nivel' => $nivel
			);

			$ubicacion = $this->ubicacion_model->save($datos);
			//Para abrir la pestaña
			$this->session->set_flashdata('seccion', 'ubicacion');
			if($ubicacion){
				
				$this->session->set_flashdata('mensaje', $this->lang->line('msj_exito')." ".$datos_recibidos['nombre']." ".$this->lang->line('msj_ext_guardar_usu'));
				$this->session->set_flashdata('tipo_mensaje', 'exito');
				
				redirect('tabla', 'refresh');
			}else{

				$this->session->set_flashdata('mensaje', $this->lang->line('msj_error_guardar_usu'));
				$this->session->set_flashdata('tipo_mensaje', 'error');
				
				redirect('tabla', 'refresh');
			}
		}
	}

	public function eliminar($id_ubicacion)
	{
		$this->acceso_restringido();
		$usuario = $this->ubicacion_model->delete($id_ubicacion);
		if(!$usuario){
			$this->session->set_flashdata('mensaje', $this->lang->line('msj_ext_eliminar_ubi'));
			$this->session->set_flashdata('tipo_mensaje', 'exito');
		}else{
			$this->session->set_flashdata('mensaje', $this->lang->line('msj_error_eliminar_ubi'));
			$this->session->set_flashdata('tipo_mensaje', 'error');
		}

		redirect('tabla', 'refresh');
	}

	public function acceso_restringido(){
		if (!$this->session->userdata('ingresado')) {
			redirect('panel', 'refresh');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */