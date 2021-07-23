<?php

    namespace App\Models;

    use \Dbal;

    class Oferta {

        private $db;

        public function __construct() {
            $this->db = new Dbal('ofertas');
        }

        public function import($params){
            $this->db->flush();
            $this->db->import($params);
        }


        public function validate(){
            $errors = [];
            if(empty(trim($this->db->producto))){
                array_push($errors,"Ingrese producto");
            }
            if(empty(trim($this->db->imagen))){
                array_push($errors, "Ingrese foto de la oferta");
            }
            if(empty(trim($this->db->descripcion))){
                array_push($errors, "Ingrese una descripción de la oferta");
            }
            return $errors;
        }

	public function save(){
		if (empty($this->db->id)) {
			$this->db->id = 0;
		}
		
		if ( count($this->validate()) > 0 ) {		
			throw new \Exception('Errores de validacion en los datos a insertar.');
		}
		
		$this->db->id = $this->db->save();       
	}

	public function delete($id) {
		$this->db->flush();
		$this->db->where('id', '=', $id);
		$this->db->delete();
	}

	public function get($id) {
		$this->db->flush();
		$this->db->find($id);        
		return $this->db->props;
	}

	public function getAll() {
		$this->db->flush();
		$this->db->leftJoin('productos');
		$this->db->on('productos.id', '=', 'ofertas.producto');
		$this->db->field([ 'ofertas.id' => 'id' ]);
		$this->db->field([ 'ofertas.producto' => 'producto' ]);
		$this->db->field([ 'ofertas.imagen' => 'imagen' ]);
		$this->db->field([ 'ofertas.descripcion' => 'descripcion' ]);
		$this->db->field([ 'productos.nombre' => 'producto' ]);
		
		$this->db->select();
		
		return $this->db->result;
	}
}