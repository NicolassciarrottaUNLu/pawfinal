<?php
	namespace App\Models;

	use \Dbal;

	class Contacto {

		private $db;

		public function validate() {
			$errors = [];

			if ( empty(trim($this->db->contacto)) ) {
				array_push($errors, "Ingrese un nombre de contacto");
			}
			
			if ( empty(trim($this->db->informacion)) ) {
				array_push($errors, "Ingrese una informacion de contacto");
			}

			return $errors;
		}

		public function __construct() {
			$this->db = new Dbal('contacto');
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

	}