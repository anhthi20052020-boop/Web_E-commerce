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


// brand
document.querySelectorAll('.brand-item').forEach(item => {
   item.addEventListener('mouseenter', function() {
       this.style.transition = 'all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1)';
   });
   
   item.addEventListener('mouseleave', function() {
       this.style.transition = 'all 0.3s ease-out';
   });
});

//flash sale
// Countdown Timer
function updateWeekendCountdown() {
   // Set end time to next Sunday 23:59:59
   const now = new Date();
   const nextSunday = new Date();
   nextSunday.setDate(now.getDate() + (7 - now.getDay()) % 7);
   nextSunday.setHours(23, 59, 59, 0);
   
   const distance = nextSunday - now;
   
   const days = Math.floor(distance / (1000 * 60 * 60 * 24));
   const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
   const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
   const seconds = Math.floor((distance % (1000 * 60)) / 1000);
   
   document.getElementById('countdown-days').textContent = days.toString().padStart(2, '0');
   document.getElementById('countdown-hours').textContent = hours.toString().padStart(2, '0');
   document.getElementById('countdown-minutes').textContent = minutes.toString().padStart(2, '0');
   document.getElementById('countdown-seconds').textContent = seconds.toString().padStart(2, '0');
}

// Initialize Swiper
const weekendSwiper = new Swiper('.products-slider', {
   loop: true,
   autoplay: {
       delay: 3000,
       disableOnInteraction: false,
   },
   pagination: {
       el: '.swiper-pagination',
       clickable: true,
   },
   slidesPerView: 1,
   spaceBetween: 20,
   breakpoints: {
       640: {
           slidesPerView: 2,
       },
       1024: {
           slidesPerView: 3,
       }
   }
});

setInterval(updateWeekendCountdown, 1000);
updateWeekendCountdown(); 

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

    localStorage.setItem('storeProduct', JSON.stringify(storeProduct));
    reloadProduct();
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

// Marquee card

const wrapper_mar = document.querySelector(".featured-products-list");
const colors = [
    "#c2bfbf",
    "#c2bfbf",
    "#c2bfbf",
    "#c2bfbf",
    "#c2bfbf",
    "#c2bfbf",
    "#c2bfbf"
];
const boxes = gsap.utils.toArray(".product-mar");

gsap.registerPlugin(Draggable, InertiaPlugin);

gsap.set(boxes, {
    backgroundColor: gsap.utils.wrap(colors)
});

const loop = horizontalLoop(boxes, { repeat: -1, draggable: true, gap: 40 }); // Added gap parameter

document
    .querySelector(".featured-products-list")
    .addEventListener("mouseenter", () => loop.pause());

document
    .querySelector(".featured-products-list")
    .addEventListener("mouseleave", () =>
        loop.reversed() ? loop.reverse() : loop.play()
    );

function horizontalLoop(items, config) {
    items = gsap.utils.toArray(items);
    config = config || {};
    let onChange = config.onChange,
        lastIndex = 0,
        tl = gsap.timeline({
            repeat: config.repeat,
            onUpdate:
                onChange &&
                function () {
                    let i = tl.closestIndex();
                    if (lastIndex !== i) {
                        lastIndex = i;
                        onChange(items[i], i);
                    }
                },
            paused: config.paused,
            defaults: { ease: "none" },
            onReverseComplete: () => tl.totalTime(tl.rawTime() + tl.duration() * 100)
        }),
        length = items.length,
        startX = items[0].offsetLeft,
        times = [],
        widths = [],
        spaceBefore = [],
        xPercents = [],
        curIndex = 0,
        indexIsDirty = false,
        center = config.center,
        pixelsPerSecond = (config.speed || 1) * 100,
        snap = config.snap === false ? (v) => v : gsap.utils.snap(config.snap || 1),
        gap = config.gap || 40, 
        timeOffset = 0,
        container =
            center === true
                ? items[0].parentNode
                : gsap.utils.toArray(center)[0] || items[0].parentNode,
        totalWidth,
        getTotalWidth = () => {
            let lastItem = items[length - 1],
                width = lastItem.offsetLeft +
                        (xPercents[length - 1] / 100) * widths[length - 1] -
                        startX +
                        spaceBefore[0] +
                        lastItem.offsetWidth * gsap.getProperty(lastItem, "scaleX") +
                        (parseFloat(config.paddingRight) || 0) +
                        gap; 
            return width;
        },
        populateWidths = () => {
            let b1 = container.getBoundingClientRect(),
                b2;
            items.forEach((el, i) => {
                widths[i] = el.offsetWidth;
                xPercents[i] = snap(
                    (parseFloat(gsap.getProperty(el, "x", "px")) / widths[i]) * 100 +
                        gsap.getProperty(el, "xPercent")
                );
                b2 = el.getBoundingClientRect();
                spaceBefore[i] = b2.left - (i ? b1.right : b1.left);
                b1 = b2;
            });
            gsap.set(items, {
                xPercent: (i) => xPercents[i]
            });
            totalWidth = getTotalWidth();
        },
        timeWrap,
        populateOffsets = () => {
            timeOffset = center
                ? (tl.duration() * (container.offsetWidth / 2)) / totalWidth
                : 0;
            center &&
                times.forEach((t, i) => {
                    times[i] = timeWrap(
                        tl.labels["label" + i] +
                            (tl.duration() * (widths[i] + gap)) / 2 / totalWidth -
                            timeOffset
                    );
                });
        },
        getClosest = (values, value, wrap) => {
            let i = values.length,
                closest = 1e10,
                index = 0,
                d;
            while (i--) {
                d = Math.abs(values[i] - value);
                if (d > wrap / 2) {
                    d = wrap - d;
                }
                if (d < closest) {
                    closest = d;
                    index = i;
                }
            }
            return index;
        },
        populateTimeline = () => {
            let i, item, curX, distanceToStart, distanceToLoop;
            tl.clear();
            for (i = 0; i < length; i++) {
                item = items[i];
                curX = (xPercents[i] / 100) * widths[i];
                distanceToStart = item.offsetLeft + curX - startX + spaceBefore[0];
                distanceToLoop =
                    distanceToStart + widths[i] * gsap.getProperty(item, "scaleX") + gap; // Include gap
                tl.to(
                    item,
                    {
                        xPercent: snap(((curX - distanceToLoop) / widths[i]) * 100),
                        duration: distanceToLoop / pixelsPerSecond
                    },
                    0
                )
                    .fromTo(
                        item,
                        {
                            xPercent: snap(
                                ((curX - distanceToLoop + totalWidth) / widths[i]) * 100
                            )
                        },
                        {
                            xPercent: xPercents[i],
                            duration:
                                (curX - distanceToLoop + totalWidth - curX) / pixelsPerSecond,
                            immediateRender: false
                        },
                        distanceToLoop / pixelsPerSecond
                    )
                    .add("label" + i, distanceToStart / pixelsPerSecond);
                times[i] = distanceToStart / pixelsPerSecond;
            }
            timeWrap = gsap.utils.wrap(0, tl.duration());
        },
        refresh = (deep) => {
            let progress = tl.progress();
            tl.progress(0, true);
            populateWidths();
            deep && populateTimeline();
            populateOffsets();
            deep && tl.draggable
                ? tl.time(times[curIndex], true)
                : tl.progress(progress, true);
        },
        proxy;
    gsap.set(items, { x: 0 });
    populateWidths();
    populateTimeline();
    populateOffsets();
    window.addEventListener("resize", () => refresh(true));
    function toIndex(index, vars) {
        vars = vars || {};
        Math.abs(index - curIndex) > length / 2 &&
            (index += index > curIndex ? -length : length);
        let newIndex = gsap.utils.wrap(0, length, index),
            time = times[newIndex];
        if (time > tl.time() !== index > curIndex && index !== curIndex) {
            time += tl.duration() * (index > curIndex ? 1 : -1);
        }
        if (time < 0 || time > tl.duration()) {
            vars.modifiers = { time: timeWrap };
        }
        curIndex = newIndex;
        vars.overwrite = true;
        gsap.killTweensOf(proxy);
        return vars.duration === 0
            ? tl.time(timeWrap(time))
            : tl.tweenTo(time, vars);
    }
    tl.toIndex = (index, vars) => toIndex(index, vars);
    tl.closestIndex = (setCurrent) => {
        let index = getClosest(times, tl.time(), tl.duration());
        if (setCurrent) {
            curIndex = index;
            indexIsDirty = false;
        }
        return index;
    };
    tl.current = () => (indexIsDirty ? tl.closestIndex(true) : curIndex);
    tl.next = (vars) => toIndex(tl.current() + 1, vars);
    tl.previous = (vars) => toIndex(tl.current() - 1, vars);
    tl.times = times;
    tl.progress(1, true).progress(0, true);
    if (config.reversed) {
        tl.vars.onReverseComplete();
        tl.reverse();
    }
    if (config.draggable && typeof Draggable === "function") {
        proxy = document.createElement("div");
        let wrap = gsap.utils.wrap(0, 1),
            ratio,
            startProgress,
            draggable,
            dragSnap,
            lastSnap,
            initChangeX,
            align = () =>
                tl.progress(
                    wrap(startProgress + (draggable.startX - draggable.x) * ratio)
                ),
            syncIndex = () => {
                if (draggable.startX - draggable.x > 0) {
                    tl.reversed(false);
                    gsap.to(tl, {
                        ease: "power1.in",
                        duration: 0.25,
                        timeScale: 1
                    });
                } else {
                    tl.reversed(true);
                    gsap.to(tl, {
                        ease: "power1.in",
                        duration: 0.25,
                        timeScale: -1
                    });
                }
                tl.closestIndex(true);
            };
        typeof InertiaPlugin === "undefined" &&
            console.warn(
                "InertiaPlugin required for momentum-based scrolling and snapping. https://greensock.com/club"
            );
        draggable = Draggable.create(proxy, {
            trigger: items[0].parentNode,
            type: "x",
            onPressInit() {
                let x = this.x;
                gsap.killTweensOf(tl);
                startProgress = tl.progress();
                refresh();
                ratio = 1 / totalWidth;
                initChangeX = startProgress / -ratio - x;
                gsap.set(proxy, { x: startProgress / -ratio });
            },
            onDrag: align,
            onThrowUpdate: align,
            overshootTolerance: 0,
            inertia: true,
            snap(value) {
                if (Math.abs(startProgress / -ratio - this.x) < 10) {
                    return lastSnap + initChangeX;
                }
                let time = -(value * ratio) * tl.duration(),
                    wrappedTime = timeWrap(time),
                    snapTime = times[getClosest(times, wrappedTime, tl.duration())],
                    dif = snapTime - wrappedTime;
                Math.abs(dif) > tl.duration() / 2 &&
                    (dif += dif < 0 ? tl.duration() : -tl.duration());
                lastSnap = (time + dif) / tl.duration() / -ratio;
                return lastSnap;
            },
            onRelease() {
                syncIndex();
                draggable.isThrowing && (indexIsDirty = true);
            },
            onThrowComplete: syncIndex
        })[0];
        tl.draggable = draggable;
    }
    tl.closestIndex(true);
    lastIndex = curIndex;
    onChange && onChange(items[curIndex], curIndex);
    return tl;
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
    const localStorageLoggedIn = localStorage.getItem('user_logged_in') === 'true';
    return phpLoggedIn && localStorageLoggedIn;
}


const categoryLinks = document.querySelectorAll('.tab-content .nav-link span');
categoryLinks.forEach(el => {
  el.parentElement.href = 'filter.php?category=' + encodeURIComponent(el.innerText.trim());
});


document.querySelectorAll('.brand-item.card img').forEach(img => {
  img.addEventListener('click', () => {
    const brand = img.alt;
    window.location.href = 'filter.php?brand=' + encodeURIComponent(brand);
  });
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