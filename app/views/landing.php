<?php 
$title = "Home";
include(__DIR__ . "/partials/header.php");
include(__DIR__ . "/partials/navbar.php");
?>

<!-- Hero Carousel -->
<div x-data="carousel()" class="w-full font-['Montserrat'] bg-[#4FB5D0] text-white overflow-hidden relative">

    <!-- Slides Container -->
    <div class="flex transition-transform duration-700 ease-in-out" :style="`transform: translateX(-${current * 100}%);`">
        <template x-for="(slide, index) in slides" :key="index">
            <div class="flex-shrink-0 w-full flex flex-col md:flex-row justify-between items-center md:items-start px-6 md:px-40 pt-12 md:pt-20 pb-14 md:pb-10 gap-4 md:gap-12">
                <div class="flex flex-col items-center justify-center md:items-start text-center md:text-left">
                    <h1 class="text-[20px] md:text-[40px] font-medium" x-text="slide.title1"></h1>
                    <h1 class="text-[30px] md:text-[60px] font-bold" x-text="slide.title2"></h1>
                    <a href="cat_products.php">
                        <button :class="slide.btnColor + ' text-white text-[20px] md:text-[24px] font-bold w-[130px] h-[45px] border-[3px] border-white mt-6'">
                            <span x-text="slide.btnText"></span>
                        </button>
                    </a>
                </div>
                <img :src="slide.img" alt="hero image" class="w-[242px] md:w-[350px] object-contain">
            </div>
        </template>
    </div>

    <!-- Navigation Buttons -->
    <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 text-white text-3xl font-bold z-10">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-10 h-10">
            <path fill="#ffffff" d="M169.4 297.4C156.9 309.9 156.9 330.2 169.4 342.7L361.4 534.7C373.9 547.2 394.2 547.2 406.7 534.7C419.2 522.2 419.2 501.9 406.7 489.4L237.3 320L406.6 150.6C419.1 138.1 419.1 117.8 406.6 105.3C394.1 92.8 373.8 92.8 361.3 105.3L169.3 297.3z"/>
        </svg>
    </button>
    <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white text-3xl font-bold z-10">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-10 h-10">
            <path fill="#ffffff" d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
        </svg>
    </button>

    <!-- Pagination Dots -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
        <template x-for="(slide, index) in slides" :key="index">
            <button @click="goTo(index)" 
                :class="{'bg-white': current === index, 'bg-gray-400': current !== index}" 
                class="w-3 h-3 rounded-full transition-colors border-2 border-black"></button>
        </template>
    </div>
</div>

<!-- Explore Section -->
<div class="flex flex-col items-center justify-center gap-4 font-['Montserrat'] text-black bg-white m-10">
    <h2 class="text-[24px] md:text-[30px] font-bold">Explore</h2>

    <div x-data="categoryCarousel()" class="w-full overflow-hidden relative px-6 md:px-40 mt-6">

        <!-- Cards Container -->
        <div class="flex transition-transform duration-500 ease-in-out gap-10" :style="slideStyle()">
            <template x-for="card in cards" :key="card.name">
                <div class="flex-shrink-0 w-full md:w-1/3 flex flex-col gap-2 items-center justify-center">
                    <div class="w-[350px] h-[250px] flex items-center justify-center">
                        <img :src="card.img" :alt="card.name" class="max-h-full max-w-full object-contain">
                    </div>
                    <p class="text-[16px] md:text-[20px] font-medium" x-text="card.name"></p>
                </div>
            </template>
        </div>


        <!-- Navigation Buttons -->
        <button @click="prev()" class="absolute left-0 top-1/2 -translate-y-1/2 text-black text-3xl font-bold z-10">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-10 h-10">
                <path fill="#000000" d="M169.4 297.4C156.9 309.9 156.9 330.2 169.4 342.7L361.4 534.7C373.9 547.2 394.2 547.2 406.7 534.7C419.2 522.2 419.2 501.9 406.7 489.4L237.3 320L406.6 150.6C419.1 138.1 419.1 117.8 406.6 105.3C394.1 92.8 373.8 92.8 361.3 105.3L169.3 297.3z"/>
            </svg>
        </button>
        <button @click="next()" class="absolute right-0 top-1/2 -translate-y-1/2 text-black text-3xl font-bold z-10">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-10 h-10">
                <path fill="#000000" d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
            </svg>
        </button>

        <!-- Pagination Dots -->
        <div class="flex gap-2 items-center justify-center mt-5">
            <template x-for="(page, index) in totalPages()" :key="index">
                <button @click="goTo(index)"
                        :class="{'bg-white': current === index, 'bg-gray-400': current !== index}"
                        class="w-3 h-3 rounded-full transition-colors border-2 border-black"></button>
            </template>
        </div>
    </div>
</div>

<!-- Top Picks Section -->
<?php
require_once(__DIR__ . "/../controllers/ProductController.php");

$productController = new ProductController();
$products = $productController->getProducts();

// Pick 6 random products
shuffle($products);
$randomProducts = array_slice($products, 0, 6);
?>

<div x-data="topPicksCarousel()" class="flex flex-col items-center justify-center gap-4 font-['Montserrat'] text-black bg-[#69A985] m-10">
    <h2 class="text-[24px] md:text-[30px] font-bold mt-10">Top Picks</h2>

    <div class="w-full overflow-hidden relative px-6 md:px-40 mt-6">
        <!-- Carousel Container -->
        <div class="flex transition-transform duration-500 ease-in-out gap-6 md:gap-20" :style="slideStyle()">
            <template x-for="product in visibleProducts()" :key="product.productID">
                <div class="flex-shrink-0 w-[200px] md:w-[250px] h-auto border-[2px] md:border-[3px] border-black flex flex-col items-center justify-center gap-[6px] p-2 bg-white">
                    <img :src="`/FluffyPetStore/public/assets/images/${product.animalName}/${product.productImage}`"
                        :alt="product.productName"
                        class="w-[120px] md:w-[180px] h-[160px] md:h-[220px] object-contain mt-[10px]">

                    <p class="font-regular text-[#5A5A5A] text-[12px] md:text-[14px]" x-text="product.categoryName.toUpperCase()"></p>

                    <a href="" class="h-[48px] md:h-[56px] flex items-center justify-center text-center">
                        <p class="font-medium text-black text-[16px] md:text-[20px] text-center hover:underline" x-text="product.productName"></p>
                    </a>

                    <p class="font-regular text-black text-[16px] md:text-[20px] text-center" x-text="`LKR ${Number(product.productPrice).toFixed(2)}`"></p>

                    <div class="flex gap-[10px] md:gap-[20px] mt-[5px] mb-[10px] md:mb-[20px]">
                        <button class="w-[100px] md:w-[120px] h-[30px] md:h-[35px] border-[2px] border-black bg-white text-black text-[14px] md:text-[16px] flex items-center justify-center hover:text-white hover:bg-black">
                            Add to Cart
                        </button>
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-6 h-6 md:w-8 md:h-8">
                                <path d="M378.9 80c-27.3 0-53 13.1-69 35.2l-34.4 47.6c-4.5 6.2-11.7 9.9-19.4 9.9s-14.9-3.7-19.4-9.9l-34.4-47.6c-16-22.1-41.7-35.2-69-35.2-47 0-85.1 38.1-85.1 85.1 0 49.9 32 98.4 68.1 142.3 41.1 50 91.4 94 125.9 120.3 3.2 2.4 7.9 4.2 14 4.2s10.8-1.8 14-4.2c34.5-26.3 84.8-70.4 125.9-120.3 36.2-43.9 68.1-92.4 68.1-142.3 0-47-38.1-85.1-85.1-85.1zM271 87.1c25-34.6 65.2-55.1 107.9-55.1 73.5 0 133.1 59.6 133.1 133.1 0 68.6-42.9 128.9-79.1 172.8-44.1 53.6-97.3 100.1-133.8 127.9-12.3 9.4-27.5 14.1-43.1 14.1s-30.8-4.7-43.1-14.1C176.4 438 123.2 391.5 79.1 338 42.9 294.1 0 233.7 0 165.1 0 91.6 59.6 32 133.1 32 175.8 32 216 52.5 241 87.1l15 20.7 15-20.7z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Navigation Buttons -->
        <button @click="prev()" class="absolute left-0 top-1/2 -translate-y-1/2 text-black text-3xl font-bold z-10">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-10 h-10">
                <path fill="#000000" d="M169.4 297.4C156.9 309.9 156.9 330.2 169.4 342.7L361.4 534.7C373.9 547.2 394.2 547.2 406.7 534.7C419.2 522.2 419.2 501.9 406.7 489.4L237.3 320L406.6 150.6C419.1 138.1 419.1 117.8 406.6 105.3C394.1 92.8 373.8 92.8 361.3 105.3L169.3 297.3z"/>
            </svg>
        </button>
        <button @click="next()" class="absolute right-0 top-1/2 -translate-y-1/2 text-black text-3xl font-bold z-10">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-10 h-10">
                <path fill="#000000" d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
            </svg>
        </button>

        <!-- Pagination Dots -->
        <div class="flex gap-2 items-center justify-center mt-10 mb-10">
            <template x-for="(page, index) in totalPages()" :key="index">
                <button @click="goTo(index)"
                        :class="{'bg-white': current === index, 'bg-gray-400': current !== index}"
                        class="w-3 h-3 rounded-full transition-colors border-2 border-black"></button>
            </template>
        </div>
    </div>
</div>

<!-- Fluffy premium section -->
<div class="flex flex-col items-center justify-center gap-4 font-['Montserrat'] m-8 md:m-20 w-[90%] md:w-auto">
    <img src="/FluffyPetStore/public/assets/images/cookie.png" alt="cookie">
    <div class="flex flex-col gap-2 text-center">
        <h2 class="font-bold text-[24px] md:text-[30px]">Join Fluffy Premium</h2>
        <h3 class="font-bold text-[18px] md:text-[24px]">More Treats, Perks and Joy!</h3>
    </div>
    <div class="flex flex-col gap-4 text-center max-w-[90%] md:max-w-[600px] mx-auto">
        <p class="font-regular text-[16px] md:text-[20px]">
            Spoil your pet with exclusive perks! Starting from just 
            <span class="font-bold">LKR 499/month</span>, you'll unlock special benefits.
        </p>
        <p class="font-regular text-[16px] md:text-[20px]">
            And for a limited time, enjoy a 
            <span class="font-bold">discounted membership rates</span> as part of our launch promotion.
        </p>
    </div>
    <a href="/FluffyPetStore/app/views/membership.php">
        <button class="text-white text-[16px] md:text-[20px] bg-[#4FB5D0] font-bold w-[150px] h-[45px] border-[3px] border-black hover:bg-black mt-4">
            JOIN NOW
        </button>
    </a>
</div>

<!-- About us section -->
<div class="flex items-center justify-center">
    <div class="w-[90%] md:w-[700px] flex flex-col items-center justify-center gap-4 font-['Montserrat'] m-8 md:m-20 border-4 border-black">
        <div class="flex flex-col items-center justify-center mx-4 md:mx-10 gap-10">
            <h2 class="font-bold text-[24px] md:text-[30px] mt-10">About</h2>
            <img src="/FluffyPetStore/public/assets/images/bag.png" alt="cookie">
            <div class="flex flex-col gap-4 text-center max-w-[90%] md:max-w-[600px] mx-auto mb-10">
                <p class="font-regular text-[16px] md:text-[20px]">
                    At <span class="font-bold">Fluffy</span> we believe pets make life brighter! So their goodies should be just as joyful! From 
                    <span class="font-bold">eco-friendly</span> toys to tasty treats, we handpick the best for wagging tails, happy purrs, and even the occasional rabbit and hamster high-five.
                </p>
                <p class="font-regular text-[16px] md:text-[20px]">
                    Also we would like to credit the artist <a href="https://www.instagram.com/bumpy2025/" class="text-black font-bold hover:underline">@bumpy2025</a> for the cute artwork!
                </p>
            </div>
        </div>
    </div>
</div>


<!-- Personalized Goodies Section -->
<div class="flex flex-col items-center justify-center gap-4 font-['Montserrat'] m-8 md:m-20 w-[90%] md:w-auto">
    <img src="/FluffyPetStore/public/assets/images/box.png" alt="box">
    <h2 class="font-bold text-[24px] md:text-[30px] text-center">Personalized Goodies</h2>
    <div class="flex flex-col gap-4 text-center max-w-[90%] md:max-w-[600px] mx-auto">
        <p class="font-regular text-[16px] md:text-[20px]">
            Spoil your furry friend with our adorable gift boxes, packed with tasty treats, fun toys, and eco-friendly essentials. Perfect for birthdays, "gotcha" days, or just!
        </p>
        <p class="font-regular text-[16px] md:text-[20px]">
            Each box is a bundle of tail wags, happy purrs and planet-friendly love.
        </p>
    </div>
    <a href="/FluffyPetStore/app/views/seasonal_products.php">
        <button class="text-white text-[16px] md:text-[20px] bg-[#69A985] font-bold w-[150px] h-[45px] border-[3px] border-black hover:bg-black mt-4">
            GET IT
        </button>
    </a>
</div>


<!-- contact us section -->
<div class="flex items-center justify-center">
    <div class="w-[400px] md:w-[700px] flex flex-col items-center justify-center gap-4 bg-[#4FB5D0] font-['Montserrat'] m-20 border-4 border-black">
        <div class="flex flex-col items-center justify-center mx-10 gap-10">
            <h2 class="font-bold text-[24px] md:text-[30px] mt-10">Contact Us</h2>
            <form action="" method="post" class="flex flex-col gap-6 w-full max-w-[330px] items-center justify-center">
                <input type="text" placeholder="Name" class="w-[250px] md:w-[500px] h-[40px] border-[2px] border-black px-3" required>
                <input type="email" placeholder="Email" class="w-[250px] md:w-[500px] h-[40px] border-[2px] border-black px-3" required>
                <input type="number" placeholder="Mobile Number" class="w-[250px] md:w-[500px] h-[40px] border-[2px] border-black px-3" required>
                <textarea class="w-[250px] md:w-[500px] border-[2px] border-black px-3" rows="5" placeholder="Message" required></textarea>
                <button type="submit" class="w-[180px] h-[45px] bg-white font-regular text-[16px] md:text-[20px] text-black border-[2px] border-black hover:font-bold mb-10">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function carousel() {
        return {
            current: 0,
            slides: [
                { title1: 'make your pet', title2: 'HAPPY HERE', img: '/FluffyPetStore/public/assets/images/airplane.png', btnText: 'START', btnColor: 'bg-[#69A985]' },
                { title1: 'make your pet', title2: 'SMILE HERE', img: '/FluffyPetStore/public/assets/images/pencil.png', btnText: 'START', btnColor: 'bg-[#69A985]' }
                
            ],
            next() {
                this.current = (this.current + 1) % this.slides.length;
            },
            prev() {
                this.current = (this.current - 1 + this.slides.length) % this.slides.length;
            },
            goTo(index) {
                this.current = index;
            },
            init() {
                setInterval(() => this.next(), 5000);
            }
        }
    }

    function categoryCarousel() {
        return {
            current: 0,
            cards: [
                {name: 'Sustainable', img: '/FluffyPetStore/public/assets/images/trees.png'},
                {name: 'Food', img: '/FluffyPetStore/public/assets/images/cart.png'},
                {name: 'Accessories', img: '/FluffyPetStore/public/assets/images/clothes.png'},
                {name: 'Grooming', img: '/FluffyPetStore/public/assets/images/fan.png'},
                {name: 'Toys', img: '/FluffyPetStore/public/assets/images/tuk.png'}
            ],
            get perPage() {
                if (window.innerWidth < 768) return 1; // mobile
                if (window.innerWidth < 1024) return 2; // tablet
                return 3; // desktop
            },
            totalPages() {
                return Math.ceil(this.cards.length / this.perPage);
            },
            next() {
                this.current = (this.current + 1) % this.totalPages();
            },
            prev() {
                this.current = (this.current - 1 + this.totalPages()) % this.totalPages();
            },
            goTo(index) {
                this.current = index;
            },
            slideStyle() {
                const translatePercent = (100 / this.perPage) * this.current;
                return `transform: translateX(-${translatePercent}%);`;
            },
            init() {
                window.addEventListener('resize', () => {
                    if (this.current >= this.totalPages()) this.current = this.totalPages() - 1;
                });
            }
        }
    }

    function topPicksCarousel() {
        return {
            current: 0,
            products: <?= json_encode($randomProducts) ?>,
            get perPage() {
                if (window.innerWidth < 768) return 2;
                return 3;
            },
            totalPages() {
                return Math.ceil(this.products.length / this.perPage);
            },
            visibleProducts() {
                const start = this.current * this.perPage;
                return this.products.slice(start, start + this.perPage);
            },
            slideStyle() {
                const translatePercent = (100 / this.perPage) * this.current;
                return `transform: translateX(-${translatePercent}%);`;
            },
            next() { this.current = (this.current + 1) % this.totalPages(); },
            prev() { this.current = (this.current - 1 + this.totalPages()) % this.totalPages(); },
            goTo(index) { this.current = index; },
            init() {
                window.addEventListener('resize', () => {
                    if (this.current >= this.totalPages()) this.current = this.totalPages() - 1;
                });
            }
        }
    }
</script>

<?php include(__DIR__ . "/partials/footer.php"); ?>