const menuOpenButton =  document.querySelector("#menu-open-button");
const menuCloseButton =  document.querySelector("#menu-close-button");

const btns = document.querySelectorAll(".nav-btn");




document.getElementById('menu-open-button').addEventListener('click', function() {
   document.body.classList.add('show-mobile-menu');
});

document.getElementById('menu-close-button').addEventListener('click', function() {
   document.body.classList.remove('show-mobile-menu');
});








 
 

