# TRABAJO FINAL PAW 2020

## Integrantes:
* Nehuen Prados.-
* Nicolas Sciarrotta.-

### Idea principal:
La idea principal fue desarrollar un sistema auto-administrable de bebidas, con la posibilidad de poder armar un carrito de compras y finalizar dicha compra a través de la aplicación WhatsApp. 
Además de permitir la compra, al momento de cargar los productos, estos se actualizan automáticamente en las páginas de Facebook e Instragram de la empresa.
La carga en Facebook e Instagram, permite continuar la compra desde cualquier plataforma, siendo redirigido siempre a la página web principal.
Tanto los productos,las categorías, las ofertas y los medios de contacto pueden ser editados, cargados y borrados por el usuario administrador del sitio.

### Accesos:
Los accesos al sitio son:
- https://finalpaw.000webhostapp.com/ , este es el acceso para los clientes del sitio.
- https://finalpaw.000webhostapp.com/admin/login , es el sitio administrativo.
- https://facebook.com/Practica-PAW-111093277302797/shop/ tienda de Facebook (Esta obsoleta por falta de uso y restricciones de marcas).
- https://instagram.com/_practica_paw página de Instagram (No funciona, no habilitaron la opción de comprar por Instagram por falta de requisitos de la página).

### Tecnologías utilizadas
Para realizar el BackEnd, se utilizó PHP. Cabe destacar que el routeador funciona de forma automática sin necesidad de declarar las rutas previamente, solo basta con cumplir con las estructuras de los nombres de las functiones declaradas dentro del controlador.
Para el FrontEnd, se utilizó HTML, CSS, y JS. Para definir los colores, fuentes y estilos, se siguió el [manual](/Recursos/MARCA.pdf) de identidad coorporativa de la empresa. Las fuentes fueron agregadas al servidor, con el fin de que todos los clientes lo vean de la misma forma.

### MVC
Se implemento un engine MVC basico con las funcionalidades necesarias para hacer operativo el sitio.
		
#### Modelo
##### Logger
 * El logger es una herramienta que permite dejar un registro de los eventos que ocurren para un posterior analisis.
##### Dbal
 * La Dbal permite tener una capa de abstraccion con la base de datos previniendo la escritura de codigo vulnerable y haciendo mas facil de mantener el proyecto.
			
### Vista
#### Render
 * Se implemento un motor de templates que provee la funcion de interpolar datos en plantillas html, facilitando el renderizado de datos.
			
### Controlador
#### Router
 * El ruteador se encarga de recibir las URLs directamente desde Nginx y transformarlas en un path al metodo de una clase de forma automatica, esto hace que no sea necesario declarar las rutas previamente.
	
### Seguridad
 * Todos los datos son validados del lado del BackEnd, en la parte de FrontEnd se añadieron las validaciones pero estas no son para nada seguras.
 * Las imagenes no son almacenadas con el nombre que estan almacenadas en el equipo cliente.
		
#### Seguridad: NGINX
 * A nivel Nginx, se aplicaron reglas de seguridad para que los visitantes solo puedan acceder a los directorios assets, base y uplods donde se encuentran alojados archivos estaticos.

#### Seguridad: GIT
 * Se agregó un .gitignore al repositorio donde no se commitean los archivos de configuración, logs ni datos que pudieran comprometer la seguridad del sitio exponiendo claves publicamente.

#### Seguridad: PHP
* Se agrego un mecanismo de log que registra en un archivo de texto plano interno todos los eventos que ocurren, no solo errores tecnicos, sino  tambien posibles acciones maliciosas como un usuario accediendo a una pantalla del administrador sin estar logueado.
* Se implemento una DBAL para la conexion con la base de datos que gestiona las querys evitando que desde el backend se escriba codigo SQL de forma directa y minimizando los errores o fallas posibles.
* Cuando ocurre algun error al usuario se le muestra con mensaje amigable con un código de seguimiento para solicitar soporte, no un detalle de lo que ocurrió, esto sirve para detectar la falla dentro de los logs.

#### Seguridad: DB
* Se agregó un SALT y se aplico una funcion HASH a las claves de los usuarios.

#### Seguridad: FRONT
* Se aplicaron validaciones a los campos de entrada del usuario.

#### Seguridad: NETWORK
* Se implementó un servicio de CDN (Cloudflare) con el objetivo de proteger el sitio contra ataques DDOS (la red de Cloudflare se jacta de ser 16 veces más grande que el ataque ddos más grande registrado hasta la fecha).

#### Seguridad: DEV
* El acceso al servidor mediante SSH solo es posible desde dentro de una VPN.

### Análisis de requerimientos
 * El sistema es capaz de realizar las siguientes tareas:
	- Dar de alta, baja y modificar categorías.
	- Dar de alta, baja y modificar productos.
	- Dar de alta, baja y modificar ofertas.
	- Dar de alta, baja y modificar información de contacto.
	- Actualizar tienda de Facebook cuando se realiza cualquier acción sobre productos.
	- Actualizar tienda de Instagram cuando se realiza cualquier acción sobre productos.
	
* El sistema NO es capaz de realizar:
	- Modificación de contraseña de usuario.
	- Modificación de estilos de la página.
	- ABM de páginas del sitio.
	
### Intructivos Facebook
 * Haga click [aquí](/Recursos/InstructivoFacebook.pdf) para ver el instructivo de configuración de Facebook para el MashUp.
 * Al realizar esta configuración es posible conectar la página de ventas de Facebook con una página de Instagram, pero no tuvimos suerte con la aprobación por parte del servicio de Instagram. Se crearon dos cuentas para intentar la vinculación y en ambas se obtuvo la misma respuesta. [Error 1](/Recursos/IG1.PNG) , [Error 2](/Recursos/IG2.PNG)
 
### Adicional
 * PWA - Progressive Web App:
 	- Se añadió la configuración necesaria para que el sistema web pueda funcionar como una aplicación en dispositivos mobile, a continuación se puede ver los instructivos tanto para iOS como para Android. Una de las mayores ventajas de esta opción es que no ocupan espacio practicamente, ya que se ejecuta de forma nativa sobre el navegador (en el caso de iOS que implementa WebKit), en Android se corre una emulación sobre el navegador.
	- [iOS](/Recursos/PWA%20iOS.pdf)
	- [Android](/Recursos/PWA%20Android.pdf)
	
 * Microdata
 	- Se agregaron datos estructurados de manera tal que los mismos puedan ser consumidos por google, un ejemplo de esto se puede [ver aqui](https://search.google.com/structured-data/testing-tool?hl=es#url=https%3A%2F%2Fpaw.craving.com.ar%2Findex%2Fproducto%3Fid%3D47)
	
	
### Entregas anteriores 
 * [Primer entrega](/primerEntrega.md)
    
