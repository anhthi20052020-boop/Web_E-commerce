const menuOpenButton =  document.querySelector("#menu-open-button");
const menuCloseButton =  document.querySelector("#menu-close-button");

const btns = document.querySelectorAll(".nav-btn");
const sildes = document.querySelectorAll(".video-slider");
const herodetails = document.querySelectorAll(".hero-details");

var sliderNav = function(manual) {
    btns.forEach((btn) => {
        btn.classList.remove("active");
    });

    sildes.forEach((slide) => {
        slide.classList.remove("active");
    });

    herodetails.forEach((herodetails) => {
        herodetails.classList.remove("active");
    });


    btns[manual].classList.add("active");
    sildes[manual].classList.add("active");
    herodetails[manual].classList.add("active");

}

btns.forEach((btn, i) => {
    btn.addEventListener("click", () => {
        sliderNav(i) ;
    });
});



menuOpenButton.addEventListener("click", () =>{
   document.body.classList.toggle("show-mobile-menu");
});

menuCloseButton.addEventListener("click", () => menuOpenButton.click());

const swiper = new Swiper('.slider-wrapper', {
   loop: true,
   grabCursor: true,
   spaceBetween: 25,
 
   pagination: {
     el: '.swiper-pagination',
     clickable: true,
     dynamicBullets: true,
   },
 
   navigation: {
     nextEl: '.swiper-button-next',
     prevEl: '.swiper-button-prev',
   },

   breakpoints: {
      0: {
         slidesPerView: 1
      },
      768: {
         slidesPerView: 2
      },
      1024: {
         slidesPerView: 3
      }
   }
 
 });

 var wrapper = document.getElementById('id01');


 

// Back to top
var btn = $('#btop-button');

$(window).scroll(function() {
   if ($(window).scrollTop() ) {
      btn.addClass('show');
   } else {
      btn.removeClass('show');
   }
});

document.getElementById('btop-button').addEventListener('click', function(e) {
   e.preventDefault();
   window.scrollTo(0,0);
});