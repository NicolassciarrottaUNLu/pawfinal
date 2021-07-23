<?php
	namespace App\Models;

	use \Dbal;

	class Pedido {

		private $db;

        public function __construct() {
			$this->db = new Dbal('pedidos');
        }
        
        public function import($params) {
			$this->db->flush();
			$this->db->import($params);
			
        }
        
        public function save() {
			if (empty($this->db->id)) {
				$this->db->id = 0;
			}
			$this->db->id = $this->db->save();
		}

		public function delete($id) {
			$this->db->flush();
			$this->db->where('id', '=', $id);
			$this->db->delete();
		}

		public function deleteAll(){
			$this->db->flush();
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