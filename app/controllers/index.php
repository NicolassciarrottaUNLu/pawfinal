<?php
	namespace App\Controllers;

	require_once('app/models/categoria.php');
	require_once('app/models/contacto.php');
	require_once('app/models/producto.php');
	require_once('app/models/oferta.php');
	require_once('app/controllers/carrito.php');

	use \Render;
	use \App\Models\Categoria;
	use \App\Models\Contacto;
	use \App\Models\Producto;
	use \App\Models\Oferta;
	use \App\Controllers\Carrito;

	class Index {

		public function __construct() {

		}

		// ------------------------------------------------------------------------

    public function getIndex() {
			 $producto = new Producto();
			 $productos = $producto->getAll();

			$categoria = new Categoria();
			$categorias = $categoria->getAll();

			$oferta = new Oferta();
			$ofertas = $oferta->getAll();

			$contacto = new Contacto();
			$contactos = $contacto->getAll();

			 foreach($categorias as $idx => $cat) {
			 	$categorias[$idx] = [	 
			 		'id'			=> $cat['id'],
			 		'link'   		=> 'productos/'.Render::slugify($cat['nombre']),
			 		'nombre' 		=> $cat['nombre'],
			 		'imagen' 		=> 'uploads/'.Render::slugify($cat['imagen']),
			 		'cantidad' 		=> $categoria->countProductos($cat['id'])
			 	];
			 }
		
			$data = [
					'titulo'     => 'Tu escabio',
				 	'categorias' => $categorias,
				 	'productos'  => $productos,
				 	'ofertas'	 => $ofertas,
				 	'contactos'  => $contactos
			];


			return Render::interpolate(Render::view('index'), $data);
    }

		// ------------------------------------------------------------------------

	public function getProducto($session, $params){		
		if ( empty($data) ) {
			$data = [];
		}
		if ( empty($data['mensajes']) ) {
			$data['mensajes'] = [];
		}
		
		
		$contacto = new Contacto();
		$contactos = $contacto->getAll();
		$categoria = new Categoria();
		$cat = $categoria->get($params['id']);

		$producto = new Producto();
		$productos = $producto->getAll();
		$listado = $producto->getAll($params['id']);

		$data = [
			'titulo' => 'Tu escabio',
			'nombre_categoria' 	=> $cat['nombre'],
			'productos' 		=> $productos,
			'listado' 			=> $listado,
			'contactos' 		=> $contactos
		];

		return Render::interpolate(Render::view('index/producto'),$data);
	}

	public function getResumen($session, $params){
		$data =[];
		$producto = new Producto();
		$productos = $producto->getAll();
		$contacto = new Contacto();
		$contactos = $contacto->getAll();

		$data['contactos']=$contactos;
		$data['productos']=$productos;
		
		if(empty($params['name'])){
			$data['nameErr'] = '*Nombre requerido';
			return Render::interpolate(Render::view('index/cliente'),$data);
		}
		if(empty($params['direction'])){
			$data['directionErr'] = '*Direccion requerida';
			return Render::interpolate(Render::view('index/cliente'),$data);
		}
		if(empty($params['phoneNumber'])){
			$data['phoneNumberErr']='*Teléfono requerido';
			return Render::interpolate(Render::view('index/cliente'),$data);
		}

		$_SESSION['client'] = json_encode([
			'name'			=> $params['name'],
			'direction'		=> $params['direction'],
			'comment'		=> empty($params['comment'])?'No hay comentarios':$params['comment'],
			'phoneNumber'	=> $params['phoneNumber']
		]);

		$data['name'] = $params['name'];
		$data['direction'] = $params['direction'];
		$data['comment'] = empty($params['comment'])?'No hay comentarios':$params['comment'];
		$data['phoneNumber'] = $params['phoneNumber'];
		$data['titulo'] = 'Tu escabio Resumen';
		return Render::interpolate(Render::view('index/resumen'),$data);
	}

	public function getFormulario($params){
		$data =[];
		$producto = new Producto();
		$productos = $producto->getAll();
		$contacto = new Contacto();
		$contactos = $contacto->getAll();

		$data['contactos']=$contactos;
		$data['productos']=$productos;
		$data['titulo'] = 'Tu escabio Formulario';
		$data['nameErr']='';
		$data['directionErr']='';
		$data['phoneNumberErr']='';
		return Render::interpolate(Render::view('index/cliente'),$data);
	}
	
	public function getIndividual($session, $params){
		if ( empty($data) ) {

			$data = [
				'titulo' => 'Tu escabio Colon'
			];
		}

		if ( empty($data['mensajes']) ) {
			$data['mensajes'] = [];
		}

		$contacto = new Contacto();
		$contactos = $contacto->getAll();

		$producto = new Producto();
		$productos = $producto->getAll();
		$listado = $producto->get($params['id']);

		$categoria = new Categoria();
		$cat = $categoria->get($listado['categoria']);

		$data = [
			'nombre_categoria' => $cat['nombre'],
			'productos' => $productos,
			'listado' => [ $listado ],
			'contactos' => $contactos
		];

		return Render::interpolate(Render::view('index/producto'),$data);
	}

	public function getComprafinalizada(){
		$contacto = new Contacto();
		$cart = new Carrito();
		$cart->getLimpiarCarrito();
		$data=[
			'titulo' 	=> 'Tu escabio',
			'contactos'	=> $contacto->getAll()
		];
		return Render::interpolate(Render::view('index/saludo'),$data);
	}

	public function getDataset() {
		$producto = new Producto();
		$productos = $producto->getAll();
		$data = [
			'site_base_url' => 'https://paw.craving.com.ar/',
			'productos' => $productos
		];
		return Render::interpolate(Render::view('dataset'), $data);
	}


	public function getQuienesSomos(){
		$data = [];
		return Render::interpolate(Render::view('index/quienes_somos'),$data);
	}

	public function getFrecuentes(){
		$data=[];
		return Render::interpolate(Render::view('index/frecuentes'),$data);
	}

	



	}