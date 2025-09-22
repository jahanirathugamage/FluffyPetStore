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

$title = "Hamster Products";
include(__DIR__ . "/partials/header.php");
include(__DIR__ . "/partials/navbar.php");

require_once(__DIR__ . "/../controllers/ProductController.php");

$productController = new ProductController();
$products = $productController->getProducts();

// filter only hamster products
$hamsterProducts = array_filter($products, function($product) {
    return strtolower($product['animalName']) === 'hamster';
});

// keep only the first instance of each product (by productID)
$uniqueHamsterProducts = [];
$productIDs = [];
foreach ($hamsterProducts as $product) {
    if (!in_array($product['productID'], $productIDs)) {
        $uniqueHamsterProducts[] = $product;
        $productIDs[] = $product['productID'];
    }
}
$hamsterProducts = $uniqueHamsterProducts;

// Pagination
$perPage = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalProducts = count($hamsterProducts);
$totalPages = ceil($totalProducts / $perPage);
$offset = ($page - 1) * $perPage;
$hamsterProductsPaginated = array_slice($hamsterProducts, $offset, $perPage);
?>

<div class="flex flex-col items-center justify-left m-[20px] sm:m-[40px] gap-[10px] font-[Montserrat]">
    <!-- Product Header -->
    <div class="w-full md:w-[870px] flex justify-between items-center md:gap-[60px] mb-2">
        <h3 class="font-bold text-[24px] md:text-[30px]"><?= htmlspecialchars($hamsterProducts[0]['animalName'] ?? 'Hamster') ?> Products</h3>
        <button class="flex w-[130px] md:w-[150px] h-[35px] md:h-[40px] border-[2px] border-black bg-[#6FAE8D] text-black font-medium font-[Montserrat] text-[14px] md:text-[16px] items-center justify-center gap-[7px]">
            <img src="../../public/assets/images/filter.svg" alt="Filter" class="h-5 md:h-6">
            Show filter
        </button>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-[20px] md:gap-[60px] w-full max-w-[870px]">
        <?php if (!empty($hamsterProductsPaginated)): ?>
            <?php foreach ($hamsterProductsPaginated as $product): ?>
                <div class="w-full md:w-[250px] h-auto border-[2px] md:border-[3px] border-black flex flex-col items-center justify-center gap-[6px] p-2">
                    <img src="../../public/assets/images/<?= htmlspecialchars($product['animalName']) ?>/<?= htmlspecialchars($product['productImage']) ?>" alt="<?= htmlspecialchars($product['productName']) ?>" class="w-[120px] md:w-[180px] h-[160px] md:h-[220px] object-contain mt-[10px]">
                    <p class="font-regular text-[#5A5A5A] text-[12px] md:text-[14px]">
                        <?= strtoupper(htmlspecialchars($product['categoryName'])) ?>
                    </p>
                    <a href="/FluffyPetStore/app/views/product_detail.php?productID=<?= $product['productID'] ?>&specID=<?= $product['specificationID'] ?>" class="h-[48px] md:h-[56px] flex items-center justify-center text-center">
                        <p class="font-medium text-black text-[16px] md:text-[20px] text-center hover:underline">
                            <?= htmlspecialchars($product['productName']) ?>
                        </p>
                    </a>
                    <p class="font-regular text-black text-[16px] md:text-[20px] text-center">
                        LKR <?= number_format($product['productPrice'], 2) ?>
                    </p>
                    <div class="flex gap-[10px] md:gap-[20px] mt-[5px] mb-[10px] md:mb-[20px]">
                        <button 
                            class="add-to-cart-btn w-[100px] md:w-[120px] h-[30px] md:h-[35px] border-[2px] border-black bg-white text-black text-[14px] md:text-[16px] flex items-center justify-center hover:text-white hover:bg-black"
                            data-id="<?= $product['productID'] ?>"
                            data-name="<?= htmlspecialchars($product['productName']) ?>"
                            data-spec="<?= htmlspecialchars($product['specificationName']) ?>"
                            data-price="<?= $product['productPrice'] ?>"
                            data-image="../../public/assets/images/<?= htmlspecialchars($product['animalName']) ?>/<?= htmlspecialchars($product['productImage']) ?>"
                        >
                            Add to Cart
                        </button>
                        <button 
                            class="add-to-fav-btn"
                            data-id="<?= $product['productID'] ?>"
                            data-name="<?= htmlspecialchars($product['productName']) ?>"
                            data-spec="<?= htmlspecialchars($product['specificationName']) ?>"
                            data-price="<?= $product['productPrice'] ?>"
                            data-image="../../public/assets/images/<?= htmlspecialchars($product['animalName']) ?>/<?= htmlspecialchars($product['productImage']) ?>"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-6 h-6 md:w-8 md:h-8">
                                <path d="M378.9 80c-27.3 0-53 13.1-69 35.2l-34.4 47.6c-4.5 6.2-11.7 9.9-19.4 9.9s-14.9-3.7-19.4-9.9l-34.4-47.6c-16-22.1-41.7-35.2-69-35.2-47 0-85.1 38.1-85.1 85.1 0 49.9 32 98.4 68.1 142.3 41.1 50 91.4 94 125.9 120.3 3.2 2.4 7.9 4.2 14 4.2s10.8-1.8 14-4.2c34.5-26.3 84.8-70.4 125.9-120.3 36.2-43.9 68.1-92.4 68.1-142.3 0-47-38.1-85.1-85.1-85.1z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="flex flex-col items-center justify-center font-['Montserrat'] font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-12 h-12">
                    <path d="M320 576C178.6 576 64 461.4 64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576zM320 384C302.3 384 288 398.3 288 416C288 433.7 302.3 448 320 448C337.7 448 352 433.7 352 416C352 398.3 337.7 384 320 384zM320 192C301.8 192 287.3 207.5 288.6 225.7L296 329.7C296.9 342.3 307.4 352 319.9 352C332.5 352 342.9 342.3 343.8 329.7L351.2 225.7C352.5 207.5 338.1 192 319.8 192z"/>
                </svg>
                <h3>No products found.</h3>
            </div>
        <?php endif; ?>
    </div>

    <!-- Desktop Pagination -->
    <div class="hidden md:flex justify-center gap-2 mt-4">
        <?php if($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="px-3 py-1 border">Prev</a>
        <?php endif; ?>
        <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="px-3 py-1 border <?= $i == $page ? 'bg-gray-300 font-bold' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>" class="px-3 py-1 border">Next</a>
        <?php endif; ?>
    </div>

    <!-- Mobile Pagination -->
    <div class="flex md:hidden justify-center mt-4 overflow-x-auto">
        <div class="flex gap-2 min-w-max">
            <?php if($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="px-3 py-1 border">Prev</a>
            <?php endif; ?>

            <?php
            $visiblePages = 5;
            $start = max(1, $page - 2);
            $end = min($totalPages, $start + $visiblePages - 1);
            if ($end - $start + 1 < $visiblePages) {
                $start = max(1, $end - $visiblePages + 1);
            }
            ?>
            <?php for($i = $start; $i <= $end; $i++): ?>
                <a href="?page=<?= $i ?>" class="px-3 py-1 border <?= $i == $page ? 'bg-gray-300 font-bold' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="px-3 py-1 border">Next</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Add to Cart
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const product = {
                id: btn.dataset.id,
                name: btn.dataset.name,
                spec: btn.dataset.spec,
                price: parseFloat(btn.dataset.price),
                image: btn.dataset.image,
                quantity: 1
            };
            let cart = JSON.parse(localStorage.getItem('cart') || "[]");
            const existing = cart.find(item => item.id == product.id && item.spec == product.spec);
            if(existing){
                existing.quantity += 1;
            } else {
                cart.push(product);
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            alert(`${product.name} added to cart!`);
        });
    });

    // Add to Favorites
    document.querySelectorAll('.add-to-fav-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const product = {
                id: btn.dataset.id,
                name: btn.dataset.name,
                spec: btn.dataset.spec,
                price: parseFloat(btn.dataset.price),
                image: btn.dataset.image
            };
            let favs = JSON.parse(localStorage.getItem('favorites') || "[]");
            const exists = favs.find(item => item.id == product.id && item.spec == product.spec);
            if(!exists){
                favs.push(product);
                localStorage.setItem('favorites', JSON.stringify(favs));
                alert(`${product.name} added to favorites!`);
            } else {
                alert(`${product.name} is already in favorites.`);
            }
        });
    });
</script>

<?php include(__DIR__ . "/partials/footer.php"); ?>
