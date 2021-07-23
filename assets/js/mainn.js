const cart = document.getElementById('cart-toggle'),
	  product = document.getElementsByClassName('lprod'),
	  total = document.getElementById('carritoTotal'),
	  quantity = document.getElementById('num_productos'),
	  productList = document.querySelector('#cart-resume #car'),
	  emptyCartbtn = document.getElementById('vaciarCarrito'),
	  processPurchase = document.getElementById('comprar'),
	  listPurchase = document.querySelector('.lista-compra tbody'),
	  openDetail = document.querySelector('.cart-detail'),
	  end = document.querySelector('.endOfDelivery');

var idOfDelivery,totalOfDelivery;

				function loadEvents(){
					for (let index = 0, l=product.length; index < l; index++) {
						product[index].addEventListener('click', (e)=>{
						   let xhr = new XMLHttpRequest(),
						   request = '../carrito/guardar/carrito';
								xhr.open('POST',
								request,
								true);
								xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');  
								xhr.send('id='+ product[index].getAttribute('data-tsc'));
								xhr.onreadystatechange = function(){
									if(xhr.readyState == 4 && xhr.status==200){
										let element = JSON.parse(xhr.response);
										if(listPurchase !== null){
											addToResume(element['producto']);
											document.getElementById('totalCart').innerHTML = element["total"];
											finalizePurchase();
										}
										addToCart(element['producto']);
										quantity.innerHTML = element['cantidad'];
										total.innerHTML = element['total'];
								   }
								}
						});
						const back = document.getElementById('back');
						(back!==null)?back.addEventListener('click',(e)=>{location.href='/'}):null;
					 }  

						cart.addEventListener('click', (e)=>{
							if(openDetail.className === '.cart-detail open'){
								 openDetail.className = '.cart-detail';
							}else{
								 openDetail.className = '.cart-detail open';
								 };
							   
							 });

					emptyCartbtn.addEventListener('click', (e)=>{ 
						while(productList.firstChild){
							productList.removeChild(productList.firstChild);
						}
						if(listPurchase!==null){
							while(listPurchase.firstChild){
								listPurchase.removeChild(listPurchase.firstChild);
							}
						}
						emptyCart();
						quantity.innerHTML = 0;
						total.innerHTML = 40;
					});
 
				} 
				
document.addEventListener('DOMContentLoaded', function(){
	loadEvents();
	addAllItms();
	document.getElementById('envio').innerHTML = ' $40';
	if(listPurchase !== null){
		createResumen();
	}
	if(end!==null){
		finalizePurchase();
		end.addEventListener('click', (e)=>{
			setTimeout(function(){
				location.href='../index/comprafinalizada'
			},1500);
		});
	}
	
});


function addToCart(element){
	let rowTpl = document.createElement('li');
			rowTpl.innerHTML =  `
			<figure>
				<img src= "../uploads/${element.imagen}">
				<figcaption>
					<div>
						${element.nombre}<br>
						<span>${element.categoria}</span>
					</div>
					<div>$${element.precio}</div>
				</figcaption>
				<a href="#" class="borrar-producto" id="borrar-producto" data-id="${element.id}"><i class="far fa-trash-alt"></i></a>
			</figure>`;
		rowTpl.childNodes[1].childNodes[5]
			.addEventListener('click',function(evt){
				deleteElement(evt,element.id)
			});
		productList.appendChild(rowTpl);  
		
	
}

function emptyCart(){
	let xhr = new XMLHttpRequest(),
	request = '../carrito/limpiar/carrito';
		 xhr.open('GET',
		 request,
		 true);
		 xhr.send();
}

function addAllItms(){
	let xhr = new XMLHttpRequest();
		xhr.open('GET',
		'../carrito/leer/carrito',
		true);
		xhr.send();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status==200){
				let elements = JSON.parse(xhr.response);
					if(elements['carrito']){
						elements['carrito'].forEach(element => {
							addToCart(element);
						});
				}
				quantity.innerHTML = elements['cantidad'];
				total.innerHTML = elements['total'];
		
			}
		}
	   
 }

processPurchase.addEventListener('click',function(e){
	e.preventDefault();
	if(productList.childNodes.length === 0){
		var msg = document.getElementById('correcto');
		msg.classList.add('vacio');
		setTimeout(function() { msg.classList.remove('vacio'); }, 750);
	}else{
		location.href= '../index/formulario';
	}
});


function createResumen(){
	let xhr = new XMLHttpRequest();
		xhr.open('GET',
		'../carrito/resumen/carrito',
		true);
		xhr.send();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status==200){
				let elements = JSON.parse(xhr.response);
					elements["carrito"].forEach(element =>{
					addToResume(element);
				});
				document.getElementById('envioR').innerHTML = elements["envio"];
				document.getElementById('totalCart').innerHTML = elements["total"];
			}
		}
}

function addToResume(element){
	let row = document.createElement('tr');
	row.innerHTML = `
			  <tr>
				<td><img src= "../uploads/${element.imagen}"></td>
				<td>${element.nombre}</td>
				<td>${element.categoria}</td>
				<td>$${element.precio}</td>
				<td><a href="#" class="borrar-producto" data-id="${element.id}"><i class="far fa-trash-alt"></i></a></td>
			 </tr>
			`;
			row.childNodes[9].firstChild.addEventListener('click',function(evt){
				deleteElement(evt,element.id);
			});
		
		listPurchase.appendChild(row);
}

function deleteElement(evt,id){
	let xhr = new XMLHttpRequest();
		xhr.open('POST',
		 '../carrito/borrar/carrito',
		 true);
		 xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');  
		 xhr.send('id='+id);
		 xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 && xhr.status==200){
					let element = JSON.parse(xhr.response);   
					switch(evt.target.parentElement.parentElement.tagName){
						case 'TR':
							evt.target.parentElement.parentElement.remove();
							for(let index = 0, l= productList.childNodes.length; index<l; index++){
								if(productList.childNodes[index].lastChild.childNodes[5].getAttribute('data-id')===evt.target.getAttribute('data-id')){
									productList.childNodes[index].remove();
									index=l;
								}
							}
							document.getElementById('envioR').innerHTML = element["envio"];
							document.getElementById('totalCart').innerHTML = element["total"];
							break;
						case 'LI':
							evt.target.parentElement.parentElement.remove();
							if(listPurchase!==null){
								for (let index = 1, l=listPurchase.childNodes.length; index < l; index++) {
									if(listPurchase.childNodes[index].childNodes[9].firstChild.getAttribute('data-id')===evt.target.getAttribute('data-id')) {
										listPurchase.childNodes[index].remove();
										index=l;
									};
								}
								document.getElementById('envioR').innerHTML = element["envio"];
								document.getElementById('totalCart').innerHTML = element["total"];    
							}
							break;
						}
						finalizePurchase();
						quantity.innerHTML = element['cantidad'];
						total.innerHTML = element['total'];
				};
				
			};
	}

	function finalizePurchase(){
		let xhr = new XMLHttpRequest(),
			id=null;
			xhr.open('GET',
			'../carrito/resumen/carrito',
			true);
			xhr.send();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 && xhr.status==200){
					let response = JSON.parse(xhr.response),
						cartEnd = response["carrito"],
						client = JSON.parse(response['client']);
						cartEnd.sort(function(a,b){return a.categoria.localeCompare(b.categoria);});
						let currentCategoria = cartEnd[0]['categoria'],
							pedido = '',
							curr = new Date(),
							start = new Date(),
							pad = '───────────────────────',
							pad2 = '                       ';
							id = [ (curr.getFullYear() - 2000), 
								('00' + (curr.getMonth() + 1)).slice(-2), 
								('00' + (curr.getDate() )).slice(-2),
							];
							start.setHours(0,0,0,0);
						id.push( Math.floor((curr.getTime() - start.getTime()) / 1000) );
						pedido = '```┌─────────────────────┐```\n';
						pedido += '```│ PEDIDO: ' + id.join('') + '  │```\n';
						pedido += '```├ ' + (client.name+ ' ' + pad + '   ').slice(0, 23 - 4) + '─┤\n```';
						pedido += '```├ ' + (client.direction + ' ' + pad + '   ').slice(0, 23 - 4) + '─┤\n```';
						pedido += '```├ ' + (client.phoneNumber + ' ' + pad + '   ').slice(0, 23 - 4) + '─┤\n```';
						pedido += '```├─────────────────────┤```\n';
						pedido += '```├ ' + (currentCategoria + ' ' + pad + '   ').slice(0, 23 - 4) + '─┤\n```';
							cartEnd.forEach(product=>{
								if (currentCategoria != product['categoria']) {
									currentCategoria = product['categoria'];
									pedido += '```├ ' + (currentCategoria + ' ' + pad + '   ').slice(0, 23 - 4) + '─┤\n```';
								}
								pedido += '```│ ' + ('  ' + '1').slice(-3) + ' ' + (product.nombre + pad2).slice(0, 23 - 8) + ' │```\n';
							 });
							 pedido += '```├─────────────────────┤```\n';
							 pedido += '```│ TOTAL: ' + ((pad + ' $' + response["total"]).slice(-23 + 11)) + ' │\n```';
							 pedido += '```└─────────────────────┘```\n';
							 pedido += 'Comentarios: '+ client.comment +'';
							 idOfDelivery = id.join('');
							 totalOfDelivery = response["total"];
						end.href = 'https://api.whatsapp.com/send?phone=5492346541804&text='+encodeURIComponent(pedido);
				}
			}
	}

