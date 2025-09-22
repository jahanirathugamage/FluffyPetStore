<?php include __DIR__ . "/header.php"; ?>

<body class="font-['Montserrat']">

<!-- Navbar -->
<nav class="flex items-center justify-between bg-[#4FB5D0] px-[58px] py-3">
    <!-- Left: Menu Icon and Logo -->
    <div class="flex items-center gap-6">
        <button id="menu-btn" class="w-8 h-8">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                <path d="M96 160C96 142.3 110.3 128 128 128L512 128C529.7 128 544 142.3 544 160C544 177.7 529.7 192 512 192L128 192C110.3 192 96 177.7 96 160zM96 320C96 302.3 110.3 288 128 288L512 288C529.7 288 544 302.3 544 320C544 337.7 529.7 352 512 352L128 352C110.3 352 96 337.7 96 320zM544 480C544 497.7 529.7 512 512 512L128 512C110.3 512 96 497.7 96 480C96 462.3 110.3 448 128 448L512 448C529.7 448 544 462.3 544 480z"/>
            </svg>
        </button>
        <a href="index.php">
            <img src="/FluffyPetStore/public/assets/images/fluffy-logo.png" alt="Fluffy Logo" class="h-10">
        </a>
    </div>

    <!-- Sidebar Menu -->
    <div id="mobile-menu" class="fixed top-0 left-0 w-72 h-full bg-[#4FB5D0] transform -translate-x-full transition-transform duration-200 z-50 shadow-lg">
        <div class="p-4">
            <div class="flex items-center justify-between mb-6">
                <a href="index.php">
                    <img src="/FluffyPetStore/public/assets/images/fluffy-logo.png" alt="Fluffy Logo" class="h-10">
                </a>
                <button id="close-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                        <path d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z"/>
                    </svg>
                </button>
            </div>

            <?php 
                $mainMenu = [
                    "/FluffyPetStore/public/index.php" => "Home",
                    "/FluffyPetStore/app/views/cat_products.php" => "Cats",
                    "/FluffyPetStore/app/views/dog_products.php" => "Dogs",
                    "/FluffyPetStore/app/views/rabbit_products.php" => "Rabbits",
                    "/FluffyPetStore/app/views/hamster_products.php" => "Hamsters",
                    "/FluffyPetStore/app/views/seasonal_products.php" => "Seasonal Boxes"
                ];
            ?>
            <ul id="main-menu" class="space-y-4">
                <?php foreach ($mainMenu as $link => $label): ?>
                    <li class="flex items-center justify-between">
                        <a href="<?= htmlspecialchars($link) ?>" class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold">
                            <?= htmlspecialchars($label) ?>
                        </a>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                            <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                        </svg>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!-- Bottom Profile Button (Mobile) -->
        <div class="mt-8 md:hidden absolute bottom-0 left-0 w-full h-[119px] bg-white flex items-center justify-center">
            <a href="/FluffyPetStore/app/views/customer/profile.php" 
            class="w-[90px] h-[35px] border-[2px] border-black bg-white text-black font-[Montserrat] text-[16px] flex items-center justify-center hover:font-bold">
                Profile
            </a>
        </div>
    </div>

    <!-- Right: Icons and Profile -->
    <div class="flex items-center gap-6">
        <!-- Search -->
        <div class="w-8 h-8">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="search w-8 h-8 fill-white cursor-pointer">
                <path d="M480 272C480 317.9 465.1 360.3 440 394.7L566.6 521.4C579.1 533.9 579.1 554.2 566.6 566.7C554.1 579.2 533.8 579.2 521.3 566.7L394.7 440C360.3 465.1 317.9 480 272 480C157.1 480 64 386.9 64 272C64 157.1 157.1 64 272 64C386.9 64 480 157.1 480 272zM272 416C351.5 416 416 351.5 416 272C416 192.5 351.5 128 272 128C192.5 128 128 192.5 128 272C128 351.5 192.5 416 272 416z"/>
            </svg>
        </div>

        <!-- Cart -->
        <button id="cart-btn" class="w-8 h-8 relative">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                <path d="M24 48C10.7 48 0 58.7 0 72C0 85.3 10.7 96 24 96L69.3 96C73.2 96 76.5 98.8 77.2 102.6L129.3 388.9C135.5 423.1 165.3 448 200.1 448L456 448C469.3 448 480 437.3 480 424C480 410.7 469.3 400 456 400L200.1 400C188.5 400 178.6 391.7 176.5 380.3L171.4 352L475 352C505.8 352 532.2 330.1 537.9 299.8L568.9 133.9C572.6 114.2 557.5 96 537.4 96L124.7 96L124.3 94C119.5 67.4 96.3 48 69.2 48L24 48zM208 576C234.5 576 256 554.5 256 528C256 501.5 234.5 480 208 480C181.5 480 160 501.5 160 528C160 554.5 181.5 576 208 576zM432 576C458.5 576 480 554.5 480 528C480 501.5 458.5 480 432 480C405.5 480 384 501.5 384 528C384 554.5 405.5 576 432 576z"/>
            </svg>
            <span id="cart-count" class="absolute top-0 right-0 w-4 h-4 bg-red-600 rounded-full text-[10px] text-white flex items-center justify-center">0</span>
        </button>

        <!-- Profile -->
        <a href="/FluffyPetStore/app/views/customer/profile.php" class="hidden md:flex w-[90px] h-[32px] border-[2px] border-black bg-white text-black font-[Montserrat] text-[16px] items-center justify-center hover:font-bold">
            Profile
        </a>
    </div>
</nav>

<!-- Cart Overlay -->
<div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

<!-- Cart Sidebar -->
<div id="cart-menu" class="fixed top-0 right-0 w-80 md:w-96 h-full bg-white transform translate-x-full transition-transform duration-300 z-50 shadow-lg font-[Montserrat] flex flex-col">
    <div class="p-6 flex flex-col h-full">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold">Your Cart</h2>
            <button id="close-cart">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6 fill-black">
                    <path d="M183.1 137.4C170.6 124.9 150.3 124.9 137.8 137.4C125.3 149.9 125.3 170.2 137.8 182.7L275.2 320L137.9 457.4C125.4 469.9 125.4 490.2 137.9 502.7C150.4 515.2 170.7 515.2 183.2 502.7L320.5 365.3L457.9 502.6C470.4 515.1 490.7 515.1 503.2 502.6C515.7 490.1 515.7 469.8 503.2 457.3L365.8 320L503.1 182.6C515.6 170.1 515.6 149.8 503.1 137.3C490.6 124.8 470.3 124.8 457.8 137.3L320.5 274.7L183.1 137.4z"/>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto space-y-6 pr-2" id="cart-items-container">
            <!--  The cart items are inserted here -->
        </div>

        <div class="mt-6 flex flex-col gap-4">
            <div class="flex justify-between">
                <span class="font-bold">Estimated total</span>
                <span id="cart-total" class="font-medium">LKR 0.00</span>
            </div>
            <span class="font-regular text-[10px] text-center">Taxes, Discounts and Shipping calculated at checkout</span>
            <form id="checkout-form" action="/FluffyPetStore/app/views/checkout.php" method="POST">
                <input type="hidden" name="cart_data" id="cart-data">
                <button type="submit" 
                    onclick="prepareCheckout()" 
                    class="w-full bg-[#69A985] text-white font-bold py-3 border-2 border-black hover:bg-black transition">
                    CHECKOUT
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Favorites Sidebar -->
<div id="favorites-menu" class="fixed top-0 right-0 w-80 md:w-96 h-full bg-white transform translate-x-full transition-transform duration-300 z-50 shadow-lg font-[Montserrat] flex flex-col border-l border-black">
    <div class="p-6 flex flex-col h-full">
        <h2 class="text-xl font-bold mb-4 text-center">Favorites</h2>
        <div class="flex-1 overflow-y-auto space-y-6 pr-2" id="favorites-container">
            <!-- JS dynamically inserts favorite items here -->
        </div>
    </div>
</div>

<script>
const menuBtn = document.getElementById('menu-btn');
const mobileMenu = document.getElementById('mobile-menu');
const closeBtn = document.getElementById('close-btn');

const cartBtn = document.getElementById('cart-btn');
const cartMenu = document.getElementById('cart-menu');
const closeCart = document.getElementById('close-cart');
const cartOverlay = document.getElementById('cart-overlay');

menuBtn.addEventListener('click', () => mobileMenu.classList.remove('-translate-x-full'));
closeBtn.addEventListener('click', () => mobileMenu.classList.add('-translate-x-full'));

cartBtn.addEventListener('click', () => {
    cartMenu.classList.remove('translate-x-full');
    cartOverlay.classList.remove('hidden');
});
closeCart.addEventListener('click', () => {
    cartMenu.classList.add('translate-x-full');
    cartOverlay.classList.add('hidden');
});
cartOverlay.addEventListener('click', () => {
    cartMenu.classList.add('translate-x-full');
    cartOverlay.classList.add('hidden');
});

// cart and favorites logic
function renderCart() {
    const cart = JSON.parse(localStorage.getItem('cart') || "[]");
    const container = document.getElementById('cart-items-container');
    const totalEl = document.getElementById('cart-total');
    let total = 0;

    container.innerHTML = '';
    cart.forEach(item => {
        total += item.price * item.quantity;

        container.innerHTML += `
            <div class="flex items-center justify-between border-b pb-4">
                <div class="flex items-center gap-3">
                    <img src="${item.image}" class="w-16 h-16 object-contain">
                    <div>
                        <p class="font-semibold text-sm">${item.name}</p>
                        <p class="text-[8px] md:text-[12px]">${item.spec}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <button onclick="updateQuantity('${item.id}', '${item.spec}', -1)" class="w-6 h-6 border flex items-center justify-center">-</button>
                            <span id="qty-${item.id}-${item.spec}">${item.quantity}</span>
                            <button onclick="updateQuantity('${item.id}', '${item.spec}', 1)" class="w-6 h-6 border flex items-center justify-center">+</button>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <button onclick="removeFromCart('${item.id}', '${item.spec}')" class="text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-5 h-5 fill-black">
                            <path d="M135.2 17.7C140.8 7.6 151.3 0 163.2 0H284.8c11.9 0 22.4 7.6 28 17.7L328 32H432c8.8 0 16 7.2 16 16s-7.2 16-16 16H416l-25.6 364.3c-1.3 18.2-16.2 32.7-34.5 32.7H92.1c-18.3 0-33.2-14.5-34.5-32.7L32 64H16c-8.8 0-16-7.2-16-16s7.2-16 16-16H120l15.2-14.3zM171.2 192c-8.8 0-16 7.2-16 16v192c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm105.6 0c-8.8 0-16 7.2-16 16v192c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/>
                        </svg>
                    </button>
                    <p class="text-sm font-medium">LKR ${(item.price*item.quantity).toLocaleString(undefined, {minimumFractionDigits:2})}</p>
                </div>
            </div>
        `;
    });

    document.getElementById('cart-count').textContent = cart.length;
    totalEl.textContent = "LKR " + total.toLocaleString(undefined,{minimumFractionDigits:2});
}

function removeFromCart(id, spec){
    let cart = JSON.parse(localStorage.getItem('cart') || "[]");
    cart = cart.filter(item => !(item.id == id && item.spec == spec));
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
}

function updateQuantity(id, spec, delta){
    let cart = JSON.parse(localStorage.getItem('cart') || "[]");
    const item = cart.find(i => i.id == id && i.spec == spec);
    if(item){
        item.quantity += delta;
        if(item.quantity < 1) item.quantity = 1;
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
    }
}

function renderFavorites() {
    const favs = JSON.parse(localStorage.getItem('favorites') || "[]");
    const container = document.getElementById('favorites-container');
    container.innerHTML = '';

    favs.forEach(item => {
        container.innerHTML += `
            <div class="flex items-center justify-between border-black pb-4">
                <div class="flex items-center gap-3">
                    <img src="${item.image}" class="w-16 h-16 object-contain">
                    <div class="flex flex-col gap-2">
                        <p class="font-semibold text-sm text-black">${item.name}</p>
                        <p class="text-[8px] md:text-[12px] text-black">${item.spec}</p>
                        <button onclick="addToCartFromFav('${item.id}', '${item.spec}')" class="w-20 h-8 bg-white text-black text-[12px] border-2 border-black hover:font-bold transition">ADD</button>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-6">
                    <button onclick="removeFromFavorites('${item.id}', '${item.spec}')" class="text-red-600 font-bold">Remove</button>
                    <p class="text-black text-sm font-medium">LKR ${item.price.toLocaleString(undefined,{minimumFractionDigits:2})}</p>
                </div>
            </div>
        `;
    });
}

function removeFromFavorites(id, spec){
    let favs = JSON.parse(localStorage.getItem('favorites') || "[]");
    favs = favs.filter(item => !(item.id == id && item.spec == spec));
    localStorage.setItem('favorites', JSON.stringify(favs));
    renderFavorites();
}

function addToCartFromFav(id, spec){
    let cart = JSON.parse(localStorage.getItem('cart') || "[]");
    let favs = JSON.parse(localStorage.getItem('favorites') || "[]");
    const item = favs.find(i => i.id == id && i.spec == spec);
    if(item){
        const existing = cart.find(i => i.id == item.id && i.spec == item.spec);
        if(existing){
            existing.quantity += 1;
        } else {
            cart.push({...item, quantity: 1});
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
    }
}

function prepareCheckout() {
    const cart = localStorage.getItem('cart') || "[]";
    document.getElementById('cart-data').value = cart;
}


// initialisations 
renderCart();
renderFavorites();
</script>
