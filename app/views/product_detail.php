<?php 
session_start();

// Require login
if (!isset($_SESSION['userID'])) {
    header("Location: /FluffyPetStore/public/login.php"); 
    exit();
}

// Only allow Customers
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'Customer') {
    header("Location: /FluffyPetStore/public/login.php"); 
    exit();
}

$title = "Product Details";
include(__DIR__ . "/partials/header.php");
include(__DIR__ . "/partials/navbar.php");

require_once(__DIR__ . "/../../app/controllers/ProductController.php");

$productController = new ProductController();

if (!isset($_GET['productID'], $_GET['specID'])) {
    die("Product not found");
}

$productID = (int) $_GET['productID'];
$specID    = (int) $_GET['specID'];

$product = $productController->getProduct($productID, $specID);
$specifications = $productController->getProductSpecifications($productID);

if (!$product) {
    die("Product not found");
}
?>

<div class="font-['Montserrat'] max-w-6xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-2 gap-10" x-data="productData()">

    <!-- Left: Product Image -->
    <div class="flex flex-col items-center justify-center border-black border-4">
        <img :src="productImage" 
             alt="<?php echo htmlspecialchars($product['productName']); ?>" 
             class="w-[200px] md:w-[300px] object-contain">
    </div>

    <!-- Right: Product Info -->
    <div class="flex flex-col gap-4">

        <!-- Product Name & Price -->
        <h2 class="text-[20px] md:text-[24px] font-bold" x-text="productName"></h2>
        <p class="text-[18px] md:text-[20px] font-semibold">
            LKR <span x-text="currentPrice.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
        </p>

        <!-- Spec Options -->
        <div>
            <p class="font-semibold text-sm md:text-base">Spec:</p>
            <div class="flex gap-2 mt-2">
                <template x-for="spec in specifications" :key="spec.specificationID">
                    <button type="button"
                            @click="selectSpec(spec.specificationID)"
                            :class="selectedSpec === spec.specificationID ? 'bg-black text-white' : 'bg-white text-black'"
                            class="px-4 py-2 border-2 border-black transition">
                        <span x-text="spec.specificationName"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- Quantity Selector -->
        <div class="flex items-center gap-4 mt-2">
            <p class="font-semibold text-sm md:text-base">Quantity:</p>
            <div class="flex items-center border-2 border-gray-400">
                <button type="button" @click="if(quantity>1) quantity--" class="px-3">-</button>
                <input type="text" x-model.number="quantity" class="w-12 text-center border-l border-r border-gray-400">
                <button type="button" @click="quantity++" class="px-3">+</button>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-4">
            <!-- Add to Cart -->
            <button type="button" @click="addToCart()" class="w-full py-2 bg-[#4FB5D0] text-white font-bold border-2 border-black hover:bg-black flex-1">
                Add to Cart
            </button>

            <!-- Add to Favorites -->
            <button type="button" @click="toggleFavorite()" class="flex items-center justify-center">
                <svg :class="isFavorite ? 'fill-black' : 'fill-none'" class="w-12 h-12 stroke-black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                    <path d="M442.9 144C415.6 144 389.9 157.1 373.9 179.2L339.5 226.8C335 233 327.8 236.7 320.1 236.7C312.4 236.7 305.2 233 300.7 226.8L266.3 179.2C250.3 157.1 224.6 144 197.3 144C150.3 144 112.2 182.1 112.2 229.1C112.2 279 144.2 327.5 180.3 371.4C221.4 421.4 271.7 465.4 306.2 491.7C309.4 494.1 314.1 495.9 320.2 495.9C326.3 495.9 331 494.1 334.2 491.7C368.7 465.4 419 421.3 460.1 371.4C496.3 327.5 528.2 279 528.2 229.1C528.2 182.1 490.1 144 443.1 144z"/>
                </svg>
            </button>
        </div>

        <!-- Product Deeds -->
        <div class="mt-4 border-t-2 border-black pt-4" x-data="{ open: 'details' }">
            <!-- Product Details -->
            <button @click="open === 'details' ? open = null : open = 'details'" 
                    class="flex justify-between w-full font-semibold py-2 items-center">
                Product Details
                <span>
                    <template x-if="open === 'details'">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4">
                            <path d="M96 320C96 302.3 110.3 288 128 288L512 288C529.7 288 544 302.3 544 320C544 337.7 529.7 352 512 352L128 352C110.3 352 96 337.7 96 320z"/>
                        </svg>
                    </template>
                    <template x-if="open !== 'details'">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4">
                            <path d="M352 128C352 110.3 337.7 96 320 96C302.3 96 288 110.3 288 128L288 288L128 288C110.3 288 96 302.3 96 320C96 337.7 110.3 352 128 352L288 352L288 512C288 529.7 302.3 544 320 544C337.7 544 352 529.7 352 512L352 352L512 352C529.7 352 544 337.7 544 320C544 302.3 529.7 288 512 288L352 288L352 128z"/>
                        </svg>
                    </template>
                </span>
            </button>
            <div x-show="open === 'details'" x-transition class="text-sm text-gray-700 mb-2">
                <?= !empty($product['productDetails']) ? nl2br(htmlspecialchars($product['productDetails'])) : "No details available." ?>
            </div>

            <!-- Benefits -->
            <button @click="open === 'benefits' ? open = null : open = 'benefits'" 
                    class="flex justify-between w-full font-semibold py-2 items-center">
                Benefits
                <span>
                    <template x-if="open === 'details'">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4">
                            <path d="M96 320C96 302.3 110.3 288 128 288L512 288C529.7 288 544 302.3 544 320C544 337.7 529.7 352 512 352L128 352C110.3 352 96 337.7 96 320z"/>
                        </svg>
                    </template>
                    <template x-if="open !== 'details'">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4">
                            <path d="M352 128C352 110.3 337.7 96 320 96C302.3 96 288 110.3 288 128L288 288L128 288C110.3 288 96 302.3 96 320C96 337.7 110.3 352 128 352L288 352L288 512C288 529.7 302.3 544 320 544C337.7 544 352 529.7 352 512L352 352L512 352C529.7 352 544 337.7 544 320C544 302.3 529.7 288 512 288L352 288L352 128z"/>
                        </svg>
                    </template>
                </span>
            </button>
            <div x-show="open === 'benefits'" x-transition class="text-sm text-gray-700 mb-2">
                <?= !empty($product['productBenefits']) ? nl2br(htmlspecialchars($product['productBenefits'])) : "No benefits listed." ?>
            </div>

            <!-- Nutritional Information -->
            <button @click="open === 'nutrition' ? open = null : open = 'nutrition'" 
                    class="flex justify-between w-full font-semibold py-2 items-center">
                Nutritional Information
                <span>
                    <template x-if="open === 'details'">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4">
                            <path d="M96 320C96 302.3 110.3 288 128 288L512 288C529.7 288 544 302.3 544 320C544 337.7 529.7 352 512 352L128 352C110.3 352 96 337.7 96 320z"/>
                        </svg>
                    </template>
                    <template x-if="open !== 'details'">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4">
                            <path d="M352 128C352 110.3 337.7 96 320 96C302.3 96 288 110.3 288 128L288 288L128 288C110.3 288 96 302.3 96 320C96 337.7 110.3 352 128 352L288 352L288 512C288 529.7 302.3 544 320 544C337.7 544 352 529.7 352 512L352 352L512 352C529.7 352 544 337.7 544 320C544 302.3 529.7 288 512 288L352 288L352 128z"/>
                        </svg>
                    </template>
                </span>
            </button>
            <div x-show="open === 'nutrition'" x-transition class="text-sm text-gray-700 mb-2">
                <?= !empty($product['productNutrition']) ? nl2br(htmlspecialchars($product['productNutrition'])) : "No nutritional info." ?>
            </div>
        </div>

    </div>
</div>

<script>
function productData() {
    return {
        productID: <?php echo $productID; ?>,
        productName: "<?php echo addslashes($product['productName']); ?>",
        productImage: "/FluffyPetStore/public/assets/images/<?php echo htmlspecialchars($product['animalName']); ?>/<?php echo htmlspecialchars($product['productImage']); ?>",
        specifications: <?php echo json_encode($specifications); ?>,
        selectedSpec: <?php echo $specID; ?>,
        quantity: 1,
        isFavorite: false,

        get currentPrice() {
            let spec = this.specifications.find(s => s.specificationID == this.selectedSpec);
            return spec ? spec.productPrice : 0;
        },

        selectSpec(specID) {
            this.selectedSpec = specID;
        },

        addToCart() {
            if(!this.selectedSpec){
                return alert("Please select a product specification.");
            }

            if(this.quantity < 1){
                return alert("Quantity must be at least 1.");
            }

            let spec = this.specifications.find(s => s.specificationID == this.selectedSpec);
            if(!spec) return alert("Invalid specification selected.");

            let cart = JSON.parse(localStorage.getItem('cart') || "[]");
            let existing = cart.find(item => item.id == this.productID && item.spec == spec.specificationName);

            let product = {
                id: this.productID,
                name: this.productName,
                spec: spec.specificationName,
                price: spec.productPrice,
                image: this.productImage,
                quantity: this.quantity
            };

            if(existing){
                existing.quantity += this.quantity;
            } else {
                cart.push(product);
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            alert(`${this.productName} (${spec.specificationName}) added to cart!`);
        },

        toggleFavorite() {
            if(!this.selectedSpec){
                return alert("Select a specification before adding to favorites.");
            }

            let spec = this.specifications.find(s => s.specificationID == this.selectedSpec);
            let favs = JSON.parse(localStorage.getItem('favorites') || "[]");

            if(this.isFavorite){
                favs = favs.filter(f => f.id != this.productID || f.spec != spec.specificationName);
            } else {
                favs.push({
                    id: this.productID,
                    spec: spec.specificationName,
                    name: this.productName,
                    price: spec.productPrice,
                    image: this.productImage
                });
            }

            localStorage.setItem('favorites', JSON.stringify(favs));
            this.isFavorite = !this.isFavorite;
        },

        init() {
            let spec = this.specifications.find(s => s.specificationID == this.selectedSpec);
            let favs = JSON.parse(localStorage.getItem('favorites') || "[]");
            this.isFavorite = favs.some(f => f.id == this.productID && f.spec == spec.specificationName);
        }
    }
}
</script>

<?php include(__DIR__ . "/partials/footer.php"); ?>
