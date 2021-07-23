<?php 

require __DIR__ . '/ticket/autoload.php'; 
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Producto{

    public function __construct($nombre, $precio, $cantidad){
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->cantidad = $cantidad;
    }
}
 
$productos = array(
        new Producto("Papas fritas", 10, 1),
        new Producto("Pringles", 22, 2),
        new Producto("Galletas saladas", 10, 1.5),
    );
 
$nombre_impresora = "bro"; 
 
 
$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector); 
$printer->setJustification(Printer::JUSTIFY_CENTER);
try{
    $logo = EscposImage::load("logo.png", false);
    $printer->bitImage($logo);
}catch(Exception $e){/*No hacemos nada si hay error*/}
 
$printer->text("Yo voy en el encabezado" . "\n");
$printer->text("Otra linea" . "\n");
$printer->text(date("Y-m-d H:i:s") . "\n");
$total = 0;
foreach ($productos as $producto) {
    $total += $producto->cantidad * $producto->precio;
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer->text($producto->cantidad . "x" . $producto->nombre . "\n");
    $printer->setJustification(Printer::JUSTIFY_RIGHT);
    $printer->text(' $' . $producto->precio . "\n");
}
$printer->text("--------\n");
$printer->text("TOTAL: $". $total ."\n");
$printer->text("Muchas gracias por su compra\nColón Café Bar");
$printer->feed(3);
$printer->cut();
$printer->pulse();
$printer->close();