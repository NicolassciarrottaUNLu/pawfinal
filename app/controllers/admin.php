<?php
	namespace App\Controllers;

	require_once('app/models/usuario.php');
	require_once('app/models/auth.php');
	require_once('app/models/categoria.php');
	require_once('app/models/contacto.php');
	require_once('app/models/producto.php');
	require_once('app/models/oferta.php');
	require_once('app/models/pedido.php');

	use \Render;
	use \Router;
	use \Logger;
	use \App\Models\Auth;
	use \App\Models\Categoria;
	use \App\Models\Contacto;
	use \App\Models\Producto;
	use \App\Models\Oferta;
	use \App\Models\Pedido;

	class Admin {

		public function __construct() {

		}

  	  public function getIndex($session, $params, $data) {
		$this->authenticate($session, "Anonimo intentando acceder al administrador", "getIndex");
			$categoria = new Categoria();
			$categorias = $categoria->getAll();			
			
			foreach($categorias as $idx => $cat) {				
				$categorias[$idx] = [
					'link'   		=> 'productos/'.Render::slugify($cat['nombre']),
					'nombre' 		=> $cat['nombre'],
					'imagen' 		=> 'uploads/'.Render::slugify($cat['imagen']),
					'cantidad' 		=> $categoria->countProductos($cat['id'])
				];
			}
			if ( empty($data) ) {
				$data = [];
			}
			$data =[
				'usuario' 		=> [ $session['usuario'] ],
				'categorias' 	=>	$categorias,
				'titulo' 		=> 'Tu escabio administración',
			];
			if (empty($data['mensajes']) ) {
				$data['mensajes'] = [];
			}
			
			return Render::interpolate(Render::view('admin/index'), $data);
    }

		// ------------------------------ LOGIN ------------------------------------------

    	public function getLogin($session) {
			$data = [ 'mensajes' => [] ];
			Auth::setCurrentUser($session, null);
			return Render::interpolate(Render::view('admin/login'), $data);
    	}

    	public function postLogin(&$session, $params, $files) {
			$data = [ 'mensajes' => [] ];
			$usuario = new \App\Models\Usuario();

			if ( $usuario->login($params['user'], $params['pass']) ) {
				Auth::setCurrentUser($session, $usuario);
				return Router::redirect('/admin/index');
			} else {
				Logger::error("Login fallido ({$params['user']})");
				array_push($data['mensajes'], [
					'style' => 'error',
					'mensaje' => 'Usuario/Clave incorrectos.'
				]);
			}
			return Render::interpolate(Render::view('admin/login'), $data);
		}
	
		public function getLogout($session) {
			Auth::destroy();
			return Router::redirect('/admin/index');
		}

		// ---------------------------- CATEGORIA --------------------------------------------

		public function getIndexCategoria(&$session, $params, $data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getIndexCategoria");
			if ( empty($data) ) {
				$data = [];
			}
			$categorias = new Categoria();
			if ( empty($data['mensajes']) ) {
				$data['mensajes'] = [];
			}
			$data['usuario']		=[ $session['usuario'] ];
			$data['categorias']		=$categorias->getAll();
			$data['titulo']			='Tu escabio administración categorías';
			return Render::interpolate(Render::view('admin/categoria'), $data);
    	}

    	public function getNuevoCategoria(&$session, $params, $data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getNuevoCategoria");
				if ( empty($data) ) {
					$data = [];
				}
				if ( empty($data['mensajes']) ) {
					$data['mensajes'] = [];
				}
				$data['usuario']		=[ $session['usuario'] ];
				$data['titulo']			='Tu escabio administración categorías';
				return Render::interpolate(Render::view('admin/categoria-form'), $data);
    		}

    	public function getBorrarCategoria(&$session, $params, $data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getBorrarCategoria");
				if ( empty($data) ) {
					$data = [];
				}
				if ( empty($data['mensajes']) ) {
				$data['mensajes'] = [];
				}
				$data['usuario']		=[ $session['usuario'] ];
				$data['titulo']			='Tu escabio administración categorías';
				if (empty($params['id'])) {
					array_push($data['mensajes'], [ 'style' => 'error', 'mensaje' => 'ID Invalido.' ]);
					return $this->getIndexCategoria($session, $params, $data);
				}
				$categoria = new Categoria();
			
				try {
					$oldData = $categoria->get($params['id']);
					$categoria->delete($params['id']);	
					$data = $this->unlinkFiles($oldData,$session,$params,$data,$oldData['nombre'], "getIndexCategoria");
				
				} catch(\Exception $e) {
					$data['mensajes'] =[];
					array_push($data['mensajes'], [ 'style' => 'error', 'mensaje' => 'No se pudo eliminar la categoria, posiblemente tenga productos vinculados.' ]);
					return $this->getIndexCategoria($session, $params, $data);			
				}
				return $this->getIndexCategoria($session, $params, $data);	
    	}

    	public function getEditarCategoria(&$session, $params, $data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getEditarCategoria");
				if ( empty($data) ) {
					$data = [];
				}
				if ( empty($data['mensajes']) ) {
					$data['mensajes'] = [];
				}			
				$data['usuario']		=[ $session['usuario'] ];
				$data['titulo']			='Tu escabio administración categorías';
				if ( empty($params['id']) ) {
					array_push($data['mensajes'], [ 'style' => 'error', 'mensaje' => 'ID Invalido.' ]);
					return $this->getIndexCategoria($session, $params, $data);
				}
				$categoria = new Categoria();
				$props = $categoria->get($params['id']);
				foreach ($props as $key => $value) {
					$data[$key] = $value;
				}
				return Render::interpolate(Render::view('admin/categoria-form'), $data);
    	}

    	public function postGuardarCategoria(&$session, $params, $files) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "postGuardarCategoria");
				$data = [
					'usuario' 	=> [ $session['usuario'] ],
					'mensajes' => [],
					'titulo' 	=> 'Tu escabio administración categorías'
				];
				$categoria = new Categoria();
				$backup = [];
				if (!empty($params['id'])) {
					$backup = $categoria->get($params['id']);	
				}
				if ( empty($files['imagen']['name']) ) {
					$params['imagen'] = '';
					if (isset($backup['imagen'])) {
						$params['imagen'] = $backup['imagen'];
					}
					} else {
						$ext = pathinfo($files['imagen']['name'], PATHINFO_EXTENSION);				
						$params['imagen'] = base_convert(round(microtime(true) * 1000), 10, 32).".".$ext;
					}			
			
				$categoria->import($params);
				$errores = $categoria->validate();
				if ( count($errores) > 0 ) {
					foreach ($errores as $error) {
						$error = [ 'style' => 'error', 'mensaje' => $error ];
						array_push($data['mensajes'], $error);
					}
				if(!empty($params)){
					$data['nombre'] = $params['nombre'];
					$data['imagen'] = $params['imagen'];
				}
				return Render::interpolate(Render::view('admin/categoria-form'), $data);
				}
			
				if ( !empty($files['imagen']['name']) ) {
					move_uploaded_file($files['imagen']['tmp_name'], 'uploads/'.$params['imagen']);				
						if (!empty($backup['imagen'])) {
							unlink('uploads/'.$backup['imagen']);
						}
					$categoria->import($params);
				}
			
			$categoria->save();
			array_push($data['mensajes'], [ 'style' => 'success', 'mensaje' => 'Categoria guardada correctamente.' ]);
			
			if ( empty($params['id']) ) {return $this->getNuevoCategoria($session, $params, $data);}			
			return $this->getEditarCategoria($session, $params, $data);
		}

		// -------------------------- PRODUCTO ----------------------------------------------

	    public function getIndexProducto(&$session, $params, $data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getIndexProducto");
				if ( empty($data) ) {
					$data = [];
				}
				if ( empty($data['mensajes']) ) {
					$data['mensajes'] = [];
				}
				$productos = new Producto();
				$data['usuario'] 		= [ $session['usuario'] ];
				$data['titulo']			= 'Tu escabio administración productos';
				$data['productos'] 		= $productos->getAll();
	
				return Render::interpolate(Render::view('admin/producto'), $data);
    	}

    	public function getNuevoProducto(&$session, $params, $data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getNuevoProducto");
				if ( empty($data) ) {
					$data = [];
				}
				if ( empty($data['mensajes']) ) {
					$data['mensajes'] = [];
				}
				$categorias = new Categoria();
				$data['usuario'] 		= [ $session['usuario'] ];
				$data['titulo']			= 'Tu escabio administración productos';
				$data['categorias']		= $categorias->getAll();
				return Render::interpolate(Render::view('admin/producto-form'), $data);
    	}

    	public function getBorrarProducto(&$session, $params, $data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getBorrarProducto");
				if ( empty($data) ) {
					$data = [];
				}
				if ( empty($data['mensajes']) ) {
					$data['mensajes'] = [];
				}
				$data['usuario'] 		= [ $session['usuario'] ];
				$data['titulo']			= 'Tu escabio administración productos';
				if (empty($params['id'])) {
					array_push($data['mensajes'], [ 'style' => 'error', 'mensaje' => 'ID Invalido.' ]);
					return $this->getIndexProducto($session, $params, $data);
				}
				$producto = new Producto();
				$oldData = $producto->get($params['id']);
				$data = $this->unlinkFiles($oldData,$session,$params,$data,$oldData['nombre'], "getIndexProducto");
				$producto->delete($params['id']);
			return $this->getIndexProducto($session, $params, $data);
    	}

    	public function getEditarProducto(&$session, $params, $data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getEditarProducto");
				if ( empty($data) ) {
					$data = [];
				}		
				if ( empty($data['mensajes']) ) {
					$data['mensajes'] = [];
				}
				$categorias = new Categoria();
				$data['usuario'] 		= [ $session['usuario'] ];
				$data['titulo']			= 'Tu escabio administración productos';
				$data['categorias']		= $categorias->getAll();
				if ( empty($params['id']) ) {
					array_push($data['mensajes'], [ 'style' => 'error', 'mensaje' => 'ID Invalido.' ]);
					return Render::interpolate(Render::view('admin/producto-form'), $data);
				}
				$producto = new Producto();
				$props = $producto->get($params['id']);
				foreach ($props as $key => $value) {
					$data[$key] = $value;
				}
			
				foreach ($data['categorias'] as $idx => $categoria) {
					if ($categoria['id'] == $data['categoria']) {
						$data['categorias'][$idx]['selected'] = 'selected';
					}
				}

			return Render::interpolate(Render::view('admin/producto-form'), $data);
    	}

    	public function postGuardarProducto(&$session, $params, $files) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getGuardarProducto");
			$categoria = new Categoria();
			$categorias = $categoria->getAll();
			$data = [
				'usuario' => [ $session['usuario'] ],
				'mensajes' => [],
				'categorias' => $categorias,
				'titulo' => 'Tu escabio administración productos'
			];
			$producto = new Producto();
			$backup = [];
			if (!empty($params['id'])) {
				$backup = $producto->get($params['id']);	
			}
			if ( empty($files['imagen']['name']) ) {
				$params['imagen'] = '';
				if (isset($backup['imagen'])) {
					$params['imagen'] = $backup['imagen'];
				}
			} else {
				$ext = pathinfo($files['imagen']['name'], PATHINFO_EXTENSION);				
				$params['imagen'] = base_convert(round(microtime(true) * 1000), 10, 32).".".$ext;
			}
			$producto->import($params);
			$errores = $producto->validate(); 
			if ( count($errores) > 0 ) {
				foreach ($errores as $error) {
					$error = [ 'style' => 'error', 'mensaje' => $error ];
					array_push($data['mensajes'], $error);
				}
				if(!empty($params)){
						$data['nombre'] 		= $params['nombre'];
						$data['precio'] 		= $params['precio'];
						$data['validohasta'] 	= $params['validohasta'];
						$data['calificacion'] 	= $params['calificacion'];
						$data['fecha']		 	= $params['fecha'];
						$data['imagen'] 		= $params['imagen'];
					
				}
				return Render::interpolate(Render::view('admin/producto-form'), $data);
			}
			
			if ( !empty($files['imagen']['name']) ) {
				move_uploaded_file($files['imagen']['tmp_name'], 'uploads/'.$params['imagen']);				
				if (!empty($backup['imagen'])) {
					unlink('uploads/'.$backup['imagen']);
				}
				$producto->import($params);
			}
			
			$producto->save();
			
			array_push($data['mensajes'], [ 'style' => 'success', 'mensaje' => 'Producto guardado correctamente.' ]);

			if ( empty($params['id']) ) {
				return $this->getNuevoProducto($session, $params, $data);
			}

			return $this->getEditarProducto($session, $params, $data);
    	}

		// ------------------------------OFERTA------------------------------------------

	    public function getIndexOferta(&$session,$params,$data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getIndexOferta");
			if ( empty($data) ) {
				$data = [];
			}
			$oferta = new Oferta();
			if ( empty($data['mensajes']) ) {
				$data['mensajes'] = [];
			}
			$data['usuario'] 	= [ $session['usuario'] ];
			$data['titulo']		='Tu escabio administración ofertas';
			$data['ofertas'] 	=$oferta->getAll();
			return Render::interpolate(Render::view('admin/oferta'), $data);
		}
	

    	public function getNuevoOferta(&$session,$params,$data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getNuevoOferta");
				if (empty($data) ) {
					$data = [];
				}
				$producto = new Producto();
				if ( empty($data['mensajes']) ) {
					$data['mensajes'] = [];
				}

				$data['usuario'] 	= [ $session['usuario'] ];
				$data['titulo']		='Tu escabio administración ofertas';
				$data['productos']	=$producto->getAll();
			return Render::interpolate(Render::view('admin/oferta-form'), $data);
		}

    	public function getBorrarOferta(&$session, $params, $data) {
				$this->authenticate($session, "Anonimo intentando acceder al administrador", "getBorrarOferta");
				if ( empty($data) ) {
					$data = [];
				}
				if ( empty($data['mensajes']) ) {
					$data['mensajes'] = [];
				}
				$data['usuario'] 	= [ $session['usuario'] ];
				$data['titulo']		='Tu escabio administración ofertas';

				if (empty($params['id'])) {
					array_push($data['mensajes'], [ 'style' => 'error', 'mensaje' => 'ID Invalido.' ]);
					return Render::interpolate(Render::view('admin/oferta'), $data);
				}
				$oferta = new Oferta();
				$oldData = $oferta->get($params['id']);
				$data = $this->unlinkFiles($oldData,$session,$params,$data,"Oferta", "getIndexOferta");
				$oferta->delete($params['id']);
				return $this->getIndexOferta($session, $params, $data);
  		}	
	

		public function postGuardarOferta(&$session, $params, $files){
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "postGuardarOferta");
			$producto = new Producto();
			$productos = $producto->getAll();
			$data = [
				'usuario' => [ $session['usuario'] ],
				'mensajes' => [],
				'productos' => $productos,
				'titulo' => 'Tu escabio administración ofertas'
			];
			$oferta = new Oferta();
			$backup = [];
			if (!empty($params['id'])) {
				$backup = $oferta->get($params['id']);	
			}

			if ( empty($files['imagen']['name']) ) {
				$params['imagen'] = '';
					if (isset($backup['imagen'])) {
						$params['imagen'] = $backup['imagen'];
					}
			} else {
				$ext = pathinfo($files['imagen']['name'], PATHINFO_EXTENSION);				
				$params['imagen'] = base_convert(round(microtime(true) * 1000), 10, 32).".".$ext;
			}
			$oferta->import($params);
			$errores = $oferta->validate();
			if ( count($errores) > 0 ) {
				foreach ($errores as $error) {
					$error = [ 'style' => 'error', 'mensaje' => $error ];
					array_push($data['mensajes'], $error);
				}
				if(!empty($params['descripcion'])){
					$data['descripcion'] = $params['descripcion'];
				}
				return Render::interpolate(Render::view('admin/oferta-form'), $data);
			}
		
			if (!empty($files['imagen']['name']) ) {
				move_uploaded_file($files['imagen']['tmp_name'], 'uploads/'.$params['imagen']);				
					if (!empty($backup['imagen'])) {
						unlink('uploads/'.$backup['imagen']);
					}
				$oferta->import($params);			
			}	
		
			$oferta->save();
		
			array_push($data['mensajes'], [ 'style' => 'success', 'mensaje' => 'Oferta guardada correctamente.' ]);
		
			if ( empty($params['id']) ) {
				return $this->getNuevoOferta($session, $params, $data);
			}

			return $this->getEditarOferta($session, $params, $data);
		}

		public function getEditarOferta(&$session, $params, $data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getEditarOferta");
			if ( empty($data) ) {
				$data = [];
			}
			if ( empty($params['id']) ) {
				array_push($data['mensajes'], [ 'style' => 'error', 'mensaje' => 'ID Invalido.' ]);
				return Render::interpolate(Render::view('admin/oferta-form'), $data);
			}
			$oferta = new Oferta();
			$props = $oferta->get($params['id']);
			foreach ($props as $key => $value) {
				$data[$key] = $value;
			}
			$productos = new Producto();
			
			if ( empty($data['mensajes']) ) {
				$data['mensajes'] = [];
			}

			$data['usuario'] 	= [ $session['usuario'] ];
			$data['titulo']		='Tu escabio administración ofertas';
			$data['productos'] 	=$productos->getAll();

		
			foreach ($data['productos'] as $idx => $producto) {
				if ($producto['id'] == $data['producto']) {
					$data['productos'][$idx]['selected'] = 'selected';
				}
			}

			return Render::interpolate(Render::view('admin/oferta-form'), $data);
		}


		// --------------------------CONTACTO----------------------------------------------

	    public function getIndexContacto(&$session, $params, $data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getIndexContacto");
			if ( empty($data) ) {
				$data = [];
			}
			$contactos = new Contacto();
	
			if ( empty($data['mensajes']) ) {
				$data['mensajes'] = [];
			}
			$data['usuario'] 	= [ $session['usuario'] ];
			$data['titulo']		='Tu escabio administración ofertas';
			$data['contactos']	=$contactos->getAll();
			
			return Render::interpolate(Render::view('admin/contacto'), $data);
    	}

	    public function getNuevoContacto(&$session, $params, $data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getNuevoContacto");
			if ( empty($data) ) {
				$data = [];
			}
	
			if ( empty($data['mensajes']) ) {
				$data['mensajes'] = [];
			}

			$data['usuario'] 	= [ $session['usuario'] ];
			$data['titulo']		='Tu escabio administración ofertas';
			return Render::interpolate(Render::view('admin/contacto-form'), $data);
    	}

    	public function getBorrarContacto(&$session, $params, $data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getBorrarContacto");
				if ( empty($data) ) {
					$data = [];
				}
				if ( empty($data['mensajes']) ) {
				$data['mensajes'] = [];
				}
				$data['usuario'] 	= [ $session['usuario'] ];
				$data['titulo']		='Tu escabio administración contacto';
				if (empty($params['id'])) {
					array_push($data['mensajes'], [ 'style' => 'error', 'mensaje' => 'ID Invalido.' ]);
					return Render::interpolate(Render::view('admin/contacto'), $data);
				}
				$contacto = new Contacto();
				$oldData = $contacto->get($params['id']);
				$data = $this->unlinkFiles($oldData,$session,$params,$data,$oldData['contacto'], "getIndexProducto");
				$contacto->delete($params['id']);
				return $this->getIndexContacto($session, $params, $data);
    	}

    	public function getEditarContacto(&$session, $params, $data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getEditarContacto");
				if ( empty($data) ) {
					$data = [];
				}
				if ( empty($data['mensajes']) ) {
					$data['mensajes'] = [];
				}
				if ( empty($params['id']) ) {
					array_push($data['mensajes'], [ 'style' => 'error', 'mensaje' => 'ID Invalido.' ]);
					return Render::interpolate(Render::view('admin/contacto-form'), $data);
				}
				$data['usuario'] 	= [ $session['usuario'] ];
				$data['titulo']		='Tu escabio administración contacto';
				$contacto = new Contacto();
				$props = $contacto->get($params['id']);
				foreach ($props as $key => $value) {
					$data[$key] = $value;
				}
			return Render::interpolate(Render::view('admin/contacto-form'), $data);
    	}

    	public function postGuardarContacto(&$session, $params, $files) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "postGuardarContacto");
				$data = [
					'usuario' => [ $session['usuario'] ],
					'mensajes' => [],
					'titulo' => 'Tu escabio administración contactos'
				];
				$contacto = new Contacto();
				$backup = [];
				if (!empty($params['id'])) {
					$backup = $contacto->get($params['id']);	
				}
			
				if ( empty($files['imagen']['name']) ) {
					$params['imagen'] = '';
					if (isset($backup['imagen'])) {
						$params['imagen'] = $backup['imagen'];
					}
				} else {
					$ext = pathinfo($files['imagen']['name'], PATHINFO_EXTENSION);				
					$params['imagen'] = base_convert(round(microtime(true) * 1000), 10, 32).".".$ext;
				}
			
				$contacto->import($params);

				$errores = $contacto->validate();
				if ( count($errores) > 0 ) {
					foreach ($errores as $error) {
						$error = [ 'style' => 'error', 'mensaje' => $error ];
						array_push($data['mensajes'], $error);
					}
					if(!empty($params)){
						$data['contacto'] = $params['contacto'];
						$data['informacion'] = $params['informacion'];
					}
					return Render::interpolate(Render::view('admin/contacto-form'), $data);
				}
			
				if (!empty($files['imagen']['name']) ) {
					move_uploaded_file($files['imagen']['tmp_name'], 'uploads/'.$params['imagen']);				
						if (!empty($backup['imagen'])) {
							unlink('uploads/'.$backup['imagen']);
						}
					$contacto->import($params);
				}
				$contacto->save();
				array_push($data['mensajes'], [ 'style' => 'success', 'mensaje' => 'Contacto guardado correctamente.' ]);
				$params=[];
				if ( empty($params['id']) ) {
					return $this->getNuevoContacto($session, $params, $data);
				}

				return $this->getEditarContacto($session, $params, $data);
		}

		/*--------------------- PEDIDOS -----------------------*/
		
		public function getIndexPedido(&$session, $params, $data) {
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getIndexPedidos");
			if ( empty($data) ) {
				$data = [];
			}
			$pedidos = new Pedido();
	
			if ( empty($data['mensajes']) ) {
				$data['mensajes'] = [];
			}
			$data['usuario'] 	= [ $session['usuario'] ];
			$data['titulo']		='Tu escabio administración pedidos';
			$data['pedidos']	=$pedidos->getAll();
			
			return Render::interpolate(Render::view('admin/pedido'), $data);
		}
		
		public function getVerPedido($session, $params, $data){
			$this->authenticate($session, "Anonimo intentando acceder al administrador", "getPedido");
			if(empty($data)){
				$data =[];
			}
			$pedido = new Pedido();
				if(empty($data['mensajes'])){
					$data['mensajes'] =[];
				}
			$pe	= $pedido->get($params['id']);
			$product = new Producto();
			$productos =[];
			$idProducts = explode('/',$pe['descripcion']);	
				for ($i=0; $i < (count($idProducts)-1) ; $i++) { 
					array_push($productos, $product->get($idProducts[$i]) );
				}
			$data['usuario'] 	= [ $session['usuario'] ];
			$data['titulo']		= 'Tu escabio administración pedidos';
			$data['productos']  = $productos;
			$data['numero']	= $pe['numero'];
			$data['direccion'] = $pe['direccion'];
			$data['tel'] = $pe['tel'];
			$data['total']		= $pe['total'];
			

		
			return Render::interpolate(Render::view('admin/pedidoIndividual'), $data);
		}

		public function postModificarEstado(){
			$id = $_POST['id'];
			$valor = $_POST['valor'];
			$pedido = new Pedido();
			$pedidoActual = $pedido->get($id);
			$pedidoActual['estado'] = $valor;
			$pedido->import($pedidoActual);
			$pedido->save();
			
		}

		public function getBorrarPedidos(){
			$pedido = new Pedido();
			$pedido->deleteAll();
		}

		// -------------------------- achuras -------------------------------------

		private function authenticate($session,$messageLog,$position){
			if (!Auth::isAdminLogged($session) ) {
				Logger::error($messageLog."($position)");
				return Router::redirect('/admin/login');
			}
		}

		private function unlinkFiles($oldData, $session,$params,$data,$nombre,$procedimiento){
			
			if (empty($oldData['id'])) {
				array_push($data['mensajes'], [ 'style' => 'error', 'mensaje' => 'ID Invalido.' ]);
				return $this->$procedimiento($session, $params, $data);
			}
			
			if (!empty($oldData['imagen'])) {
				unlink('uploads/'.$oldData['imagen']);
			}

			array_push($data['mensajes'], ['style' => 'success', 'mensaje' => $nombre. " eliminado con éxito."]);

			return $data;
		}

		
	}