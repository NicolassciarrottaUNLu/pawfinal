<?php
	namespace App\Models;
	
	use \Dbal;
	use \Config;

	class Usuario {
		
		private $db;
		
		private $id = "";
		private $name = "";
		private $username = "";
		private $password = "";
		
		private static function securePassword($user, $rawpass) {			
			return hash('sha256', $user . Config::$crypt_salt . $rawpass);
		}
		
		private function validate() {
			$errors = [];
			
			if ( empty(trim($this->username)) ) {
				array_push($errors, "Ingrese un usuario");
			}

			if ( empty(trim($this->password)) ) {
				array_push($errors,"Ingrese una clave");
			}

			return $errors;
		}
			
		public function __construct() {
			$this->db = new Dbal('usuarios');
		}
		
		public function getId() {
			return $this->db->id;
		}
		
		public function getNombre() {
			return $this->db->nombre;
		}
		
		public function login($user, $rawpass) {
			$this->db->flush();
			$this->db->where('user', '=', $user);
			$this->db->where('pass', '=', Usuario::securePassword($user, $rawpass));
			$this->db->first();
			
			return !empty($this->db->id);
		}

	}