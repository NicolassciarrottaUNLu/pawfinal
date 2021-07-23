window.addEventListener('load', function(){
	document.querySelectorAll('.contenedor_slider')
		.forEach(function(container) {			
			var slider = container.querySelector(".slider"),
					imagenes = slider.querySelectorAll(".slide"),
					largo_imagenes = imagenes.length,
					btn_derecha = container.querySelector("#derecha"),
					btn_izquierda = container.querySelector("#izquierda"),
					click_izquierdo=1,
					click_derecho=1;
					btn_derecha.classList.add('hidden');

			container.querySelector("#derecha")
				.addEventListener('click', function(){
						if(click_derecho-1 != 0){
								slider.style.transform = "translate("+ (click_derecho*100)+"%)";
								slider.style.transition = '1s';
								click_derecho++;
								click_izquierdo--;
						}
						if(click_derecho == 1){
							btn_derecha.classList.add('hidden');
						}else{
							btn_derecha.classList.remove('hidden');
							btn_izquierda.classList.remove('hidden');
						}
						
						if(click_derecho == click_izquierdo){
							btn_izquierda.classList.remove('hidden');
						}
				});

			container.querySelector("#izquierda")
				.addEventListener('click',function (){
						if(click_izquierdo != largo_imagenes){
								slider.style.transform= "translate(-"+(click_izquierdo*100)+"%)";
								slider.style.transition = '1s';
								click_derecho--;
								click_izquierdo++;
						}
						btn_derecha.classList.remove('hidden');

						 if(click_izquierdo == largo_imagenes){
							btn_izquierda.classList.add('hidden');
						 }else{
							 btn_izquierda.classList.remove('hidden');
							 btn_derecha.classList.remove('hidden');
						 }
						
				});
		});	
});

window.onload = function(){
     var contenedor = document.getElementById('contenedor-carga');
     contenedor.style.transition = '1s';
     contenedor.style.opacity ='0';
     contenedor.style.visibility = 'hidden';
}

document.querySelectorAll('.productos .cart-toggle')
	.forEach(function(btn) {
		btn.addEventListener('click', function(evt) {
			if (btn.previousElementSibling.value <= 0) {
				evt.preventDefault();
				evt.stopPropagation();
				return;
			}
			
			var msg = document.getElementById('correcto');
			msg.classList.add('correcto');
			setTimeout(function() { msg.classList.remove('correcto'); }, 750);
		});
	});

document.querySelectorAll('.hero ul .cart-toggle')
	.forEach(function(btn) {
		btn.addEventListener('click', function(evt) {
			if (btn.previousElementSibling.value <= 0) {
				evt.preventDefault();
				evt.stopPropagation();
				return;
			}
			
			var msg = document.getElementById('correcto');
			msg.classList.add('correcto');
			setTimeout(function() { msg.classList.remove('correcto'); }, 750);
		});
	});