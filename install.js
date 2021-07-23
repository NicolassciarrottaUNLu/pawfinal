'use strict';

window.addEventListener('beforeinstallprompt', function (evt) {
	document.querySelectorAll('.pwaInstall')
		.forEach(function(pwa) {
			pwa.style.display = 'flex';
			pwa.addEventListener('click', function () {
				document.querySelectorAll('.pwaInstall')
					.forEach(function(pwa) {
						pwa.style.display = 'none';
					});
					
				evt.prompt();		
				evt.userChoice
						.then((choice) => {
							console.log('User ' + choice.outcome + ' the PWA.');
						});				
			});				
		});
});

window.addEventListener('appinstalled', function (evt) {
	console.log('Aplicación instalada.', evt);
});

if ('serviceWorker' in navigator) {
	window.addEventListener('load', async () => {
		navigator.serviceWorker.register('/sw.js')
			.then(console.log)
			.catch(console.error);
	});
}