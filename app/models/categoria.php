<?php
	namespace App\Models;

	use \Dbal;

	class Categoria {

		private $db;

		public function validate() {
			$errors = [];

			if ( empty(trim($this->db->nombre)) ) {
				array_push($errors, "Ingrese un nombre de la categoria");
			}
			if ( empty(trim($this->db->imagen)) ){
				array_push($errors, "Ingrese imagen de categoria");
			}

			return $errors;
		}

		public function __construct() {
			$this->db = new Dbal('categorias');
		}

		public function import($params) {
			$this->db->flush();
			$this->db->import($params);
		}

		public function save() {
			if (empty($this->db->id)) {
				$this->db->id = 0;
			}
			
			if ( count($this->validate()) > 0 ) {
				throw new \Exception('Errores de validacion en los datos a insertar.');
			}
			
			$this->db->id = $this->db->save();
		}

		public function delete($id) {
			$productos = $this->countProductos($id);
			if ($productos > 0) {
				throw new \Exception('Existen productos que impiden eliminar la categoria.');				
			}
			
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
			$this->db->select();

			return $this->db->result;
		}

		public function countProductos($id) {
			$this->db->flush();
			$this->db->rightJoin('productos');
			$this->db->on('productos.categoria', '=', 'categorias.id');
			$this->db->where('categorias.id', '=', $id);

			return $this->db->count();
		}

	}