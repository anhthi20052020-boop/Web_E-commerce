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

function addtocart(id){

    if (typeof id !== 'number' || !items[id]) return;

    let item = items[id];

    let existingIndex = storeProduct.findIndex(p => p && p.id === item.id); 
    
    if (existingIndex >= 0) {
        storeProduct[existingIndex].quantity += 1;
    } else {
        let newItem = {...item, quantity: 1};
        storeProduct.push(newItem);
    }

    localStorage.setItem('storeProduct', JSON.stringify(cart));
    
    if (document.body.dataset.userLoggedIn === 'true') {
        fetch('update_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                userId: document.body.dataset.userId,
                cartData: cart
            })
        });
    }

    localStorage.setItem('storeProduct', JSON.stringify(storeProduct));
    reloadProduct();
}



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

document.querySelectorAll('.buy-button, .add-to-cart').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault(); 
        
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
    const localStorageLoggedIn = !!localStorage.getItem('user_logged_in');
    return phpLoggedIn || localStorageLoggedIn;
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
                    <i class="fas fa-times" onclick="changeCount(${key}, 0)"></i>
                </li>
            `;
        });


        document.querySelector('.cartcontainer ul').innerHTML = cartHTML;
        document.querySelector('.subtotal span').textContent = subtotal.toLocaleString('vi-VN') + 'đ';
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
