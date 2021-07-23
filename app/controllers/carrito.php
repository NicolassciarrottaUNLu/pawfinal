<?php

namespace App\Controllers;

require_once('app/models/producto.php');
require_once('app/models/categoria.php');
require_once('app/models/pedido.php');

use \App\Models\Producto;
use \App\Models\Categoria;
use \App\Models\Pedido;


class Carrito{


    public function __construct() {

    }


    public function postGuardarCarrito(){

        $id = $_POST['id'];

        $producto = new Producto();
        $prod = $producto->get($id);

        $categoria = new Categoria();
        $cat = $categoria->get($prod['categoria']);
        $prod['categoria'] = $cat['nombre'];

        
            if(!isset($_SESSION["carrito"])){
                $_SESSION["carrito"][0] = $prod;
            }else{
                
                $_SESSION["carrito"][count($_SESSION["carrito"])] = $prod;
            }

        $toReturn = [
            'producto' => $prod,
            'cantidad' => $this->getCantidadCarrito(),
            'total'    => $this->getTotalCarrito()
        ];
        return json_encode($toReturn);
		
    }
    
    public function getCantidadCarrito(){
        return !empty($_SESSION["carrito"])?count($_SESSION["carrito"]):0;
    }

    public function getLimpiarCarrito(){
        $_SESSION["carrito"]=[];
    }

    public function getLeerCarrito(){
        $toReturn = [
            'carrito'   => ($this->getCantidadCarrito()>0)?$_SESSION['carrito']:0,
            'cantidad'  => $this->getCantidadCarrito(),
            'total'     => $this->getTotalCarrito()
        ];
        return json_encode($toReturn);
    }

    public function getTotalCarrito(){
        $total = 40;
        if(isset($_SESSION["carrito"])){
            foreach ($_SESSION["carrito"] as $itm) {
                $total += $itm['precio'];
            }
        }
        return $total;
    }

    public function postBorrarCarrito(){
        $id = $_POST['id'];
        $find = false;
        
        foreach ($_SESSION["carrito"] as $itm => $value) {
            if (($value['id'] == $id) && (!$find)){
                $find = true;
                unset($_SESSION["carrito"][$itm]);
                $_SESSION["carrito"]=array_values($_SESSION["carrito"]);
            }
        }

        
        $toReturn=[
            'cantidad'  => $this->getCantidadCarrito(),
            'envio'    => 40,
            'total'     => $this->getTotalCarrito(),
            'find'      => ($find)?'true':'false'
        ];
        return json_encode($toReturn);
    }

    public function getResumenCarrito(){
        $toReturn =[
            'carrito' => $_SESSION["carrito"],
            'envio'   => 40,
            'total'   => $this->getTotalCarrito(),
            'client'  => $_SESSION['client']
        ];
        return json_encode($toReturn);
    }
}