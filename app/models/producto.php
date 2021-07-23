<?php
	namespace App\Models;

	use \Dbal;

	class Producto {

		private $db;

		public function validate() {
			$errors = [];
			
			if(empty(trim($this->db->categoria))){
				array_push($errors, "Ingrese una categoria");
			}
			
			if(empty(trim($this->db->nombre))){
				array_push($errors, "Ingrese un nombre del producto");
			}
			
			if(empty(trim($this->db->precio))){
				array_push($errors, "Ingrese un precio del producto");
			}
			
			if(empty(trim($this->db->imagen))){
				array_push($errors,"Ingrese una foto del producto");
			}

			return $errors;
		}

		public function __construct() {
			$this->db = new Dbal('productos');
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

		public function getAll($categoria = null) {
			$this->db->flush();
			if (!is_null($categoria)) {
				$this->db->where('productos.categoria', '=', $categoria);				
			}			
			
			$this->db->leftJoin('categorias');
			$this->db->on('productos.categoria', '=', 'categorias.id');
			$this->db->field([ 'productos.id' 					=> 'id' 			]);
			$this->db->field([ 'categorias.nombre'				=> 'categoria'		]);
			$this->db->field([ 'productos.precio' 				=> 'precio' 		]);
			$this->db->field([ 'productos.nombre' 				=> 'nombre' 		]);
			$this->db->field([ 'productos.calificacion' 		=> 'calificacion'	]);
			$this->db->field([ 'productos.validohasta' 			=> 'validohasta'	]);
			$this->db->field([ 'productos.fecha' 				=> 'fecha' 			]);
			$this->db->field([ 'productos.imagen' 				=> 'imagen'			]);
			
			$this->db->select();
			
			return $this->db->result;
		}		
	}