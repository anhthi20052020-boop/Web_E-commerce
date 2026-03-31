const menuOpenButton =  document.querySelector("#menu-open-button");
const menuCloseButton =  document.querySelector("#menu-close-button");

const btns = document.querySelectorAll(".nav-btn");

document.getElementById('menu-open-button').addEventListener('click', function() {
   document.body.classList.add('show-mobile-menu');
});

document.getElementById('menu-close-button').addEventListener('click', function() {
   document.body.classList.remove('show-mobile-menu');
});

function closeMobileMenu() {
   document.body.classList.remove("show-mobile-menu");
   resetMobileTabs();
   document.querySelector('.mobile-tab[data-tab="home-tab"]').classList.add('active');
   document.querySelector('.mobile-tab[data-tab="catolog-tab"]').classList.remove('active');
   document.getElementById('home-tab').classList.add('active');
   document.getElementById('catolog-tab').classList.remove('active');
   
   if (window.innerWidth > 900) {
       document.querySelectorAll('.tab-content').forEach(c => {
           c.style.display = 'none';
       });
   }
}


const mobileTabs = document.querySelectorAll('.mobile-tab');

mobileTabs.forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.mobile-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        tab.classList.add('active');
        
        const tabId = tab.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
    });
});

function resetMobileTabs() {
   document.querySelector('.mobile-tab[data-tab="home-tab"]').classList.add('active');
   document.querySelector('.mobile-tab[data-tab="catolog-tab"]').classList.remove('active');
   document.getElementById('home-tab').classList.add('active');
   document.getElementById('catolog-tab').classList.remove('active');
}


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
     


document.addEventListener('click', function(event) {
    const dropdowns = document.querySelectorAll('.user-dropdown');
    dropdowns.forEach(dropdown => {
        if (!dropdown.contains(event.target)) {
            const content = dropdown.querySelector('.user-dropdown-content');
            if (content) content.style.display = 'none';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.user-dropbtn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const content = this.nextElementSibling;
            if (content.style.display === 'block') {
                content.style.display = 'none';
            } else {
                content.style.display = 'block';
            }
        });
    });
});



window.addEventListener('resize', function() {
   if (window.innerWidth > 900) { 
       closeMobileMenu();
       document.querySelector('.navbar-bottom').style.display = 'block';
       document.querySelectorAll('.tab-content').forEach(c => {
           c.style.display = 'none';
       });
   } else {
       document.querySelectorAll('.tab-content').forEach(c => {
           c.style.display = '';
       });
   }
});


function handleResponsive() {

   const navbar = document.querySelector('.navbar');
   const navMenu = document.querySelector('.nav-menu');

   if (window.innerWidth > 900) {
      document.querySelector('.navbar-bottom').style.display = 'block';
      document.querySelector('#menu-open-button').style.display = 'none';
      closeMobileMenu();
   } else {
      document.querySelector('.navbar-bottom').style.display = 'none';
      document.querySelector('#menu-open-button').style.display = 'block';
   }
}

document.addEventListener('DOMContentLoaded', handleResponsive);
window.addEventListener('resize', handleResponsive);


const mainImage = document.getElementById('mainImage');
let canZoom = true;

function checkZoomPermission() {
    if (window.innerWidth < 768) {
        mainImage.classList.remove('zoom-enabled');
        mainImage.classList.add('zoom-disabled');
        canZoom = false;
        mainImage.style.transform = 'scale(1)';
    } else {
        mainImage.classList.add('zoom-enabled');
        mainImage.classList.remove('zoom-disabled');
        canZoom = true;
    }
}

if (mainImage) {
    mainImage.addEventListener('mousemove', function(e) {
        if (!canZoom || !this.dataset.zoomImage) return;
        
        const {left, top, width, height} = this.getBoundingClientRect();
        const x = (e.clientX - left) / width;
        const y = (e.clientY - top) / height;
        
        this.style.transformOrigin = `${x * 100}% ${y * 100}%`;
        this.style.transform = 'scale(1.5)';
    });
    
    mainImage.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });
    
    checkZoomPermission();
    window.addEventListener('resize', checkZoomPermission);
}

function changeMainImage(thumbnail) {
    if (mainImage) {
        mainImage.src = thumbnail.src.replace('80x80', '500x500');
        mainImage.dataset.zoomImage = thumbnail.src.replace('80x80', '1000x1000');
        mainImage.style.transform = 'scale(1)';
    }
}
        
        mainImage.addEventListener('mousemove', function(e) {
            if (!canZoom || !this.dataset.zoomImage) return;
            
            const {left, top, width, height} = this.getBoundingClientRect();
            const x = (e.clientX - left) / width;
            const y = (e.clientY - top) / height;
            
            this.style.transformOrigin = `${x * 100}% ${y * 100}%`;
            this.style.transform = 'scale(1.5)';
        });
        
        mainImage.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
        
        window.onload = function() {
            const firstThumbnail = document.querySelector('.thumbnail');
            if (firstThumbnail) {
                changeMainImage(firstThumbnail);
            }
            checkZoomPermission();
            window.addEventListener('resize', checkZoomPermission);
        };

document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.slider-container', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        grabCursor: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            }
        }
    });

   });


document.querySelector('.overlay').addEventListener('click', () => {
    document.body.classList.remove('active');
});
// CART
let items = [
   
];

var productBox = document.querySelector('.productbox');
var cartbox = document.querySelector('.cartbox');

var quantity = document.querySelector('.quantity');
var cartcontainer = document.querySelector('.cartcontainer ul');
var total = document.querySelector('.subtotal span');


cartbox.addEventListener('click',()=>{
   document.body.classList.toggle('active')
});
const closebtn = document.querySelector('.close-cart-btn');
if (closebtn) {
    closebtn.addEventListener('click', () => {
        document.body.classList.remove('active');
    });
}


let storeProduct = JSON.parse(localStorage.getItem('storeProduct')) || [];

let currentProduct = {
    id:  $product['id'] ?? 0 ,
    name: addslashes($product['name'] ?? '') ,
    image:  $images[0] ?? '',
    price:  $product['price'] ?? 0 
};


function addToCart(productId, isRelated = false) {
    if (!isLoggedIn()) {
        openLoginForm();
        return;
    }

    let product;
    if (isRelated) {
        product = {
            id: productId,
            name: document.querySelector(`.product-card[data-id="${productId}"] .product-title`).textContent.trim(),
            image: document.querySelector(`.product-card[data-id="${productId}"] .product-image img`).src,
            price: parseFloat(document.querySelector(`.product-card[data-id="${productId}"] .current-price`).textContent.replace(/\./g, '').replace('đ', ''))
        };
    } else {
        const productName = document.querySelector('.product-title').textContent.trim();
        const productPrice = parseFloat(document.querySelector('.current-price').textContent.replace(/\./g, '').replace('đ', ''));
        const productImage = document.querySelector('.main-image').src;
        const quantity = parseInt(document.getElementById('quantity').value) || 1;
        
        product = {
            id: productId,
            name: productName,
            image: productImage,
            price: productPrice,
            quantity: quantity
        };
    }

    let storeProduct = JSON.parse(localStorage.getItem('storeProduct')) || [];
    let existingIndex = storeProduct.findIndex(p => p.id == product.id);

    if (existingIndex >= 0) {
        storeProduct[existingIndex].quantity += product.quantity;
    } else {
        storeProduct.push(product);
    }

    localStorage.setItem('storeProduct', JSON.stringify(storeProduct));
    reloadProduct();

    if (!isRelated) {
        showCartNotification(product.name, product.quantity);
        document.body.classList.add('active');
    }
}

function buyNow(productId) {
    if (!isLoggedIn()) {
        openLoginForm();
        return;
    }

    addToCart(productId);
    proceedToCheckout();
}

function showCartNotification(productName, quantity) {
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.innerHTML = `
        <span>Đã thêm ${quantity} ${productName} vào giỏ hàng</span>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}


function changeCount(key,quantity){
    if(quantity == 0) {
        storeProduct.splice(key, 1); 
    }
    else{
        storeProduct[key].quantity = quantity;
    }
    localStorage.setItem('storeProduct', JSON.stringify(storeProduct));
    reloadProduct();
}


document.querySelectorAll('.btn-buy, .btn-cart').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault(); 
        if (this.classList.contains('btn-buy')) {
            proceedToCheckout();
        }
        if (!isLoggedIn()) {
            alert('Vui lòng đăng nhập để mua hàng');
            openLoginForm();
            return;
        }

        const productCard = this.closest('.product-sale, .product-card');
        const productName = productCard.querySelector('h3, .product-title').textContent.trim();

        const priceText = productCard.querySelector('.new-price, .current-price').textContent;
        const productPrice = parseFloat(priceText.replace(/\./g, '').replace('đ', '')); 

        const productImage = productCard.querySelector('img').src;

        const productId = productCard.getAttribute('data-id') || (productName + '-' + productPrice);

        let existingIndex = storeProduct.findIndex(item => item && item.id === productId);
        
        if (existingIndex >= 0) {
            storeProduct[existingIndex].quantity += 1;
        } else {
            storeProduct.push({
                id: productId, 
                name: productName,
                image: productImage,
                star: 5,
                price: productPrice,
                quantity: 1
            });
        }

        localStorage.setItem('storeProduct', JSON.stringify(storeProduct));
        reloadProduct(); 
        document.body.classList.add('active'); 
    });
});

function isLoggedIn() {
    const phpLoggedIn = document.body.getAttribute('data-user-logged-in') === 'true';
    const localStorageLoggedIn = localStorage.getItem('user_logged_in') === 'true';
    return phpLoggedIn && localStorageLoggedIn;
}

const categoryLinks = document.querySelectorAll('.tab-content .nav-link span');
categoryLinks.forEach(el => {
  el.parentElement.href = 'filter.php?category=' + encodeURIComponent(el.innerText.trim());
});



const searchForm = document.querySelector('.search-box form');
searchForm?.addEventListener('submit', (e) => {
  e.preventDefault();
  const value = searchForm.querySelector('input[name=search]').value;
  window.location.href = 'filter.php?search=' + encodeURIComponent(value);
});

const mobileSearchForm = document.querySelector('.mobile-search-box form');
mobileSearchForm?.addEventListener('submit', (e) => {
  e.preventDefault();
  const value = mobileSearchForm.querySelector('input[name=search]').value;
  window.location.href = 'filter.php?search=' + encodeURIComponent(value);
});



function reloadProduct() {
    storeProduct = JSON.parse(localStorage.getItem('storeProduct')) || [];

    let totalQuantity = storeProduct.reduce((total, item) => total + (item.quantity || 0), 0);
    document.querySelector('.quantity').textContent = totalQuantity;
    
    if (document.querySelector('.cartcontainer ul')) {
        let cartHTML = '';
        let count = 0;
        let subtotal = 0;
        cartHTML += ''                                        
        storeProduct.forEach((item, key) => {
            if (!item) return;
            count += item.quantity;
            subtotal += item.price * item.quantity;
            
            cartHTML += `
                <li class="cart-item">
                    <div class="cart-item-img">
                        <img src="${item.image}" alt="${item.name}">
                    </div>
                    <div class="cart-item-info">
                        <h4>${item.name}</h4>
                        <div class="cart-item-price">
                        
                            ${item.price.toLocaleString('vi-VN')}đ x ${item.quantity}
                        </div>
                        <div class="changequantity">
                            <button onclick="changeCount(${key}, ${item.quantity - 1})">-</button>
                            <span>${item.quantity}</span>
                            <button onclick="changeCount(${key}, ${item.quantity + 1})">+</button>
                        </div>
                    </div>
                    
                </li>
                
            `;

            
        });



        document.querySelector('.cartcontainer ul').innerHTML = cartHTML;
        document.querySelector('.subtotal span').textContent = 'Total: ' + subtotal.toLocaleString('vi-VN') + 'đ';
    }

    if (isLoggedIn()) {
        const userId = localStorage.getItem('user_id') || document.body.getAttribute('data-user-id');
        if (userId) {
            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    userId: userId,
                    cartData: storeProduct
                })
            });
        }
    }
     if (totalQuantity > 0) {
    document.querySelector('.quantity').style.display = 'block';
} else {
    document.querySelector('.quantity').style.display = 'none';
}
}

document.addEventListener('DOMContentLoaded', function() {
    reloadProduct();
    handleResponsive();

});





function proceedToCheckout() {
    const cartItems = JSON.parse(localStorage.getItem('storeProduct') || '[]');
    
    if (cartItems.length === 0) {
        alert('Your cart is empty!');
        return;
    }
    
    const isLoggedIn = localStorage.getItem('user_logged_in') === 'true';
    
    if (!isLoggedIn) {
        alert('Please login to proceed to checkout');
        openLoginForm();
        return;
    }
    
    document.cookie = `storeProduct=${JSON.stringify(cartItems)}; path=/`;
    window.location.href = 'checkout.php';
}



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