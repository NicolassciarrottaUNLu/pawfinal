# Trabajo-Final

## Presentación de la idea:

El proyecto en cuestion se trata de la plataforma de venta online "Tu Escabio", un emprendimiento local de venta de bebidas con contenido alcoholico. 

Se contempla el desarrollo de una plataforma web de e-commerce autoadministrable donde los vendedores puedan publicar los catalogos de productos que comercializan. Cada catalogo representa a una agrupacion de bebidas con caracteristicas comunes, por ejemplo "Cervezas", "Energizantes", "Licores", entre otras.

Se incluye una herramienta de publicacion de ofertas, destinada a maximizar la visibilidad de los productos en promocion y aumentar el ratio de conversion de estas bebidas estrategicas. 

Acorde a las especificaciones, el sitio estara optimizado para contener 3 ofertas, un estimado de 6 catalogos y cada catalogo estara optimizado para contener un promedio de 24 articulos, sin ser estas cantidades una limitacion taxativa.

Se proporcionara al visitante informacion sobre el emprendimiento asi como respuestas claras a las principales consultas, de manera que se minimice la cantidad de consultas recurrentes, pudiendo el visitante evacuar estas dudas de forma autonoma.

El sitio implementara tecnologia Open Graph, que maximiza la compatibilidad con las redes sociales y facilita su integracion con las mismas.

Los productos utilizaran como pasarela de venta a Whatsapp Business, pudiendo los visitantes armar un carrito de compras online y luego finalizar el proceso mediante Whatsapp de una forma simple e intuitiva.

Se realizara un mashup con Facebook Store e IG Shopping.
Se realizara un buscador predictivo de productos.

Se realizara una vinculacion Google Maps a fines de mostrar la ubicacion fisica del deposito en un mapa.
Todo el administrador de la plataforma de venta estara protegido por Usuario + Clave, acorde a lo solicitado, no existiran roles de usuarios ya que todos los vendedores tendran acceso completo a la administracion del sitio.

Se llevara un registro de auditoria interno donde quedara registrado que usuario modifica que articulo en que momento.
Toda la visual del sitio estara adecuada al manual de marca que posee la empresa, siguiendo los lineamientos correspondientes tanto en el frontend como en el backend.

No se utilizaran librerias de terceros para el desarrollo.
Se selecciona el stack MYSQL + PHP / APACHE / HTML5 + CSS3 + JS como las tecnologias optimas para llevar adelante el proyecto.

El desarrollo del proyecto requiere de 2 Programadores Junior (uno para backend y otro para frontend) y de un analista programador de sistemas de informacion para la planificacion de la arquitectura y supervision del desarrollo, adicionalmente se requiere un Tester Junior y un Data Entry para el testeo de la herramienta y la carga de datos.
Las siguientes estimaciones presupuestarias se hacen en base a una dedicacion Part Time de todos los recursos humanos.

### Presupuesto de recursos humanos:

- Analisis de requerimientos y diseño: **16Hs** * **$1200/Hs** 
- Programacion de backend: **24Hs** * **$800/Hs** 
- Programacion de frontend desktop: **24Hs** * **$600/Hs**  
- Programacion de frontend mobile: **12Hs** * **$700/Hs**
- Testing: **6Hs** * **$500/Hs** 
- Carga de contenido: **8Hs** * **$250/Hs**

### Presupuesto de infraestructura:

- Contratacion y configuracion de Hosting: **$2500/Anuales** + **2Hs** * **$400**
- Contratacion y configuracion de SSL: **$1500/Anuales** + **2Hs** * **$400**
- Configuracion de cuentas de correo + clientes de correo: **2Hs** * **$400**
- Contratacion y delegacion de dominio .com.ar: **$270** + **2Hs** * **$400**

### Presupuesto por supervision

- Supervision: +35%

### Carga impositiva:

- IVA: +21%
- IIBB: +3%

### Presupuesto total:

Tiempo de desarrollo estimado: **25 Dias Habiles**

```
--------------------------
RRHH:            $  66.200
Infraestructura: $   7.470
--------------------------
Subtotal:        $  73.670
--------------------------
Supervision:     $  25.785
--------------------------
Total Neto:      $  99.455
--------------------------
Impuestos:       $  23.870
--------------------------
Total:           $ 123.325
--------------------------
```

## SiteMap:
- Home
  * Categoría 1
      * Producto 1
      * Producto 2
      * Producto 3
  * Categoría 2
      * Producto 4
      * Producto 5
      * Producto 6
  * Categoría 3
      * Producto 7
      * Producto 8
      * Producto 9

- Quienes somos
- Preguntas frecuentes
- Login
      
## WireFrames:
Los WireFrames estan divididos en:
[Dispositivos de escritorio](/proyecto/wireframes/pc) 
[Dispositivos móviles](/proyecto/wireframes/mobile) 

## BackEnd:
El proyecto se realizará en PHP utilizando el patrón MVC, utilizando MySQL como motor de  base de datos.

### Diagrama de clases:
![](/proyecto/diagrama/diagrama.png) 

### Diagrama de db:
![](/proyecto/db/db.png) 
