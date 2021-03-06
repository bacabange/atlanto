<?php 

class Estadocomponente_model extends CI_Model {
	private $tabla = 'estado_componente';

	function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    //Guarda datos
    function save($datos) {
        $guarda = $this->db->insert($this->db->dbprefix($this->tabla), $datos);
        if ($guarda) {
            return $this->db->insert_id();
        }else{
            return $guarda;
        }
    }

    //Traer datos
    function get_todos() {
        $query = $this->db->get($this->db->dbprefix($this->tabla));
        if ($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }        
    }

    //Trae estado segun parametros de $data
    function get_estado($data) {
        $this->db->where($data);
        $query = $this->db->get($this->db->dbprefix($this->tabla));
        if ($query->num_rows() > 0){
            return $query->row();
        }else{
            return FALSE;
        }
    }

    //Elimina un estado
    function delete($id_estado) {
        $this->db->where('id', $id_estado);
        $this->db->delete($this->db->dbprefix($this->tabla));
    }

    //Actualiza los datos del usuario
    function update($id_estado, $datos) {
        $this->db->where('id', $id_estado);
        return $this->db->update($this->db->dbprefix($this->tabla), $datos);
    }
}


