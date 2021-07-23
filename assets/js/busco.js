 document.querySelector('.hero input[type= search]')
     .addEventListener('input', function(){
         const options = document.querySelectorAll('.hero ul li');
             let inn = this.value.toLowerCase().replaceAll(' ','');
             new RegExp(inn ,'gi');
             for (let index = 0, l=options.length; index < l; index++) {
                 let element = options[index].textContent.toLowerCase(),
                     e = element.replace(/\s+/gi, '');
                         if(!e.match(inn)){
                             options[index].style.display='none';
                         }else{
                             options[index].style.display='';
                         }
                
             }

 });
