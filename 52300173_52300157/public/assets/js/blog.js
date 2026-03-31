const menuOpenButton =  document.querySelector("#menu-open-button");
const menuCloseButton =  document.querySelector("#menu-close-button");

const carouselItems = document.querySelectorAll('.carousel-item');
        const indicators = document.querySelectorAll('.carousel-indicators button');
        let currentIndex = 0;

        function showSlide(index) {
            carouselItems.forEach(item => item.classList.remove('active'));
            indicators.forEach(btn => btn.classList.remove('active'));
            carouselItems[index].classList.add('active');
            indicators[index].classList.add('active');
        }

        indicators.forEach((btn, index) => {
            btn.addEventListener('click', () => {
                currentIndex = index;
                showSlide(currentIndex);
            });
        });

        setInterval(() => {
            currentIndex = (currentIndex + 1) % carouselItems.length;
            showSlide(currentIndex);
        }, 5000);

menuOpenButton.addEventListener("click", () =>{
   document.body.classList.toggle("show-mobile-menu");
});

menuCloseButton.addEventListener("click", () => menuOpenButton.click());


var wrapper = document.getElementById('id01');


function openLoginForm() {
   document.getElementById('overlei').style.display = 'block';
   document.getElementById('id01').style.display = 'block';
}

function closeLoginForm() {
   document.getElementById('overlei').style.display = 'none';
   document.getElementById('id01').style.display = 'none';
}

function openRegisterForm() {
   document.getElementById('overlei2').style.display = 'block';
   document.getElementById('id02').style.display = 'block';
}

function closeRegisterForm() {
   document.getElementById('overlei2').style.display = 'none';
   document.getElementById('id02').style.display = 'none';
}

function openSetPassForm() {
   document.getElementById('overlei3').style.display = 'block';
   document.getElementById('id03').style.display = 'block';
}

function closeSetPassForm() {
   document.getElementById('overlei3').style.display = 'none';
   document.getElementById('id03').style.display = 'none';
}

$(document).ready(function(){
   $('#myCarousel').carousel({
       interval: 3000, 
       wrap: true      
   });
});

var btn = $('#btop-button');

$(window).scroll(function() {
   if ($(window).scrollTop() > 300) {
      btn.addClass('show');
   } else {
      btn.removeClass('show');
   }
});

document.getElementById('btop-button').addEventListener('click', function(e) {
   e.preventDefault();
   window.scrollTo({
      top: 0,
      behavior: 'smooth' 
   });
});

