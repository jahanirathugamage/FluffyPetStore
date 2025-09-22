<?php
session_start(); 

$title = "Dashboard";

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../login.php");
    exit();
}

// Restrict customers from accessing employee dashboard
if ($_SESSION['userRole'] !== 'Employee') {
    header("Location: ../login.php");
    exit();
}

include(__DIR__ . "/../partials/header.php"); 
require_once __DIR__ . "/../../controllers/ProductController.php";

$productController = new ProductController();

// Pagination setup
$perPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Fetch products with pagination
$products = $productController->getProductsPaginated($page, $perPage);

// Get total products for page count
$totalProducts = count($productController->getProducts());
$totalPages = ceil($totalProducts / $perPage);
?>

<body>
    <!-- Navbar -->
    <nav class="flex items-center justify-between bg-[#4FB5D0] px-[58px] py-3">
        <!-- Left: Menu Icon and Logo -->
        <div class="flex items-center gap-6">
            <!-- Menu Icon -->
            <button id="menu-btn" class="w-8 h-8" alt="Menu">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-8 h-8 fill-white">
                    <path d="M96 160C96 142.3 110.3 128 128 128L512 128C529.7 128 544 142.3 544 160C544 177.7 529.7 192 512 192L128 192C110.3 192 96 177.7 96 160zM96 320C96 302.3 110.3 288 128 288L512 288C529.7 288 544 302.3 544 320C544 337.7 529.7 352 512 352L128 352C110.3 352 96 337.7 96 320zM544 480C544 497.7 529.7 512 512 512L128 512C110.3 512 96 497.7 96 480C96 462.3 110.3 448 128 448L512 448C529.7 448 544 462.3 544 480z"/>
                </svg>
            </button>

            <!-- Fluffy Logo -->
            <a href="index.php">
                <img src="/FluffyPetStore/public/assets/images/fluffy-logo.png" alt="Fluffy Logo" class="h-10">
            </a>
        </div>

        <!-- Sidebar Menu -->
        <div id="mobile-menu" class="fixed top-0 left-0 w-72 h-full bg-[#4FB5D0] transform -translate-x-full transition-transform duration-200 z-50 shadow-lg">
            <div class="p-4">

                <!-- Sidebar Header: Logo + Close Icon -->
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

                <!-- Main Menu -->
                <ul id="main-menu" class="space-y-4">
                    <li class="flex items-center justify-between">
                        <a href="/FluffyPetStore/app/views/employee/dashboard.php" 
                        class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold">
                            Dashboard
                        </a>
                        <!-- Arrow SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                            <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                        </svg>
                    </li>

                    <li class="flex items-center justify-between">
                        <a href="/FluffyPetStore/app/views/employee/create_employee.php" 
                        class="block text-white font-[Montserrat] text-[16px] font-medium hover:font-bold">
                            Account Creation
                        </a>
                        <!-- Arrow SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 fill-white">
                            <path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/>
                        </svg>
                    </li>
                </ul>

            </div>

            <!-- Bottom Profile Button (Mobile) -->
            <div class="mt-8 md:hidden absolute bottom-0 left-0 w-full h-[119px] bg-white flex items-center justify-center">
                <a href="/FluffyPetStore/public/logout.php" 
                class="w-[90px] h-[35px] border-[2px] border-black bg-white text-black font-[Montserrat] text-[16px] flex items-center justify-center hover:font-bold">
                    Logout
                </a>
            </div>

        </div>

        <!-- Right: Icons and Profile -->
        <div class="flex items-center gap-6">
            <!-- Search Icon -->
            <div class="w-8 h-8">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="search w-8 h-8 fill-white cursor-pointer">
                    <path d="M480 272C480 317.9 465.1 360.3 440 394.7L566.6 521.4C579.1 533.9 579.1 554.2 566.6 566.7C554.1 579.2 533.8 579.2 521.3 566.7L394.7 440C360.3 465.1 317.9 480 272 480C157.1 480 64 386.9 64 272C64 157.1 157.1 64 272 64C386.9 64 480 157.1 480 272zM272 416C351.5 416 416 351.5 416 272C416 192.5 351.5 128 272 128C192.5 128 128 192.5 128 272C128 351.5 192.5 416 272 416z"/>
                </svg>
            </div>

            <!-- Profile Button (desktop only) -->
            <a href="/FluffyPetStore/public/logout.php" class="hidden md:flex w-[90px] h-[32px] border-[2px] border-black bg-white text-black font-[Montserrat] text-[16px] items-center justify-center hover:font-bold">
                Logout
            </a>

        </div>
    </nav>

    <div class="flex w-full items-center justify-center m-[20px] sm:m-40px font-[Montserrat]">
        <h2 class="font-bold text-[24px] md:text-[30px]">Employee Dashboard</h2>
    </div>

    <div class="flex flex-col items-center justify-left m-[20px] sm:m-[40px] gap-[10px] overflow-auto font-[Montserrat] ">
        <!-- Product Table Header -->
        <div class="w-full md:w-[1100px] flex justify-between items-center md:gap-[60px] mb-2">
            <h3 class="font-bold text-[20px] md:text-[26px]">Products</h3>
            <a href="add_product.php">
                <button class="flex w-[130px] md:w-[150px] h-[35px] md:h-[40px] border-[2px] border-black bg-white text-black font-medium font-[Montserrat] text-[14px] md:text-[16px] items-center justify-center gap-[7px] hover:bg-[#6FAE8D]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-4 h-4 md:w-6 md:h-6">
                        <path d="M352 128C352 110.3 337.7 96 320 96C302.3 96 288 110.3 288 128L288 288L128 288C110.3 288 96 302.3 96 320C96 337.7 110.3 352 128 352L288 352L288 512C288 529.7 302.3 544 320 544C337.7 544 352 529.7 352 512L352 352L512 352C529.7 352 544 337.7 544 320C544 302.3 529.7 288 512 288L352 288L352 128z"/>
                    </svg>
                    Add Product
                </button>
            </a>
        </div>

        <!-- Product Table - Desktop -->
        <div class="overflow-auto hidden md:block w-full md:w-[1100px]">
            <table class="w-full table-auto border-2 border-gray-300">
                <thead class="border-b-2 border-gray-300">
                    <tr>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">No.</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Product</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Product Name</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Price</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Stock</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Specification</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Category</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Type</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Action</th>
                        <th class="p-3 text-sm font-medium tracking-wide text-left">Delete</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="p-3 text-center text-sm whitespace-nowrap"><?= $product['productID'] ?></td>
                            <td class="p-3 whitespace-nowrap">
                                <img src="/FluffyPetStore/public/assets/images/<?= htmlspecialchars($product['animalName']) ?>/<?= htmlspecialchars($product['productImage']) ?>" 
                                    alt="<?= htmlspecialchars($product['productName']) ?>" 
                                    class="w-12 h-12 object-contain">
                            </td>
                            <td class="p-3 text-sm whitespace-nowrap"><?= htmlspecialchars($product['productName']) ?></td>
                            <td class="p-3 text-sm whitespace-nowrap">LKR <?= number_format($product['productPrice'], 2) ?></td>
                            <td class="p-3 text-sm whitespace-nowrap"><?= $product['productStock'] ?></td>
                            <td class="p-3 text-sm whitespace-nowrap"><?= htmlspecialchars($product['specificationName']) ?></td>
                            <td class="p-3 text-sm whitespace-nowrap"><?= htmlspecialchars($product['categoryName']) ?></td>
                            <td class="p-3 text-sm whitespace-nowrap"><?= htmlspecialchars($product['animalName']) ?></td>
                            <td class="p-3 text-center whitespace-nowrap">
                                <a href="update_product.php?productID=<?= $product['productID'] ?>&specID=<?= $product['specificationID'] ?>">
                                    <button type="button" class="action-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6 mx-auto">
                                            <path d="M320 208C289.1 208 264 182.9 264 152C264 121.1 289.1 96 320 96C350.9 96 376 121.1 376 152C376 182.9 350.9 208 320 208zM320 432C350.9 432 376 457.1 376 488C376 518.9 350.9 544 320 544C289.1 544 264 518.9 264 488C264 457.1 289.1 432 320 432zM376 320C376 350.9 350.9 376 320 376C289.1 376 264 350.9 264 320C264 289.1 289.1 264 320 264C350.9 264 376 289.1 376 320z"/>
                                        </svg>
                                    </button>
                                </a>
                            </td>
                            <td class="p-3 text-center whitespace-nowrap">
                                <form method="POST" action="../../controllers/ProductController.php" 
                                    onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    <input type="hidden" name="action" value="singleDelete">
                                    <input type="hidden" name="productID" value="<?= $product['productID'] ?>">
                                    <input type="hidden" name="specificationID" value="<?= $product['specificationID'] ?>">
                                    <button type="submit" class="row-delete-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6">
                                            <path d="M232.7 69.9L224 96L128 96C110.3 96 96 110.3 96 128C96 145.7 110.3 160 128 160L512 160C529.7 160 544 145.7 544 128C544 110.3 529.7 96 512 96L416 96L407.3 69.9C402.9 56.8 390.7 48 376.9 48L263.1 48C249.3 48 237.1 56.8 232.7 69.9zM512 208L128 208L149.1 531.1C150.7 556.4 171.7 576 197 576L443 576C468.3 576 489.3 556.4 490.9 531.1L512 208z"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Product Table - Mobile -->
        <div class="grid grid-cols-1 gap-4 md:hidden w-full">
            <?php foreach ($products as $product): ?>
                <div class="flex flex-col border-b-2 border-gray-300 pb-4">
                    <div class="flex items-start">
                        <div class="p-1 border-2 border-black">
                            <img src="/FluffyPetStore/public/assets/images/<?= htmlspecialchars($product['animalName']) ?>/<?= htmlspecialchars($product['productImage']) ?>" 
                                alt="<?= htmlspecialchars($product['productName']) ?>" 
                                class="w-24 h-24 object-contain">
                        </div>
                        <div class="flex-1 grid grid-cols-[auto_1fr] gap-x-2 gap-y-1 ml-2">
                            <p class="font-medium text-left">No.</p><p><?= $product['productID'] ?></p>
                            <p class="font-medium text-left">Name</p><p><?= htmlspecialchars($product['productName']) ?></p>
                            <p class="font-medium text-left">Price</p><p>LKR <?= number_format($product['productPrice'], 2) ?></p>
                            <p class="font-medium text-left">Stock</p><p><?= $product['productStock'] ?></p>
                            <p class="font-medium text-left">Set</p><p><?= htmlspecialchars($product['categoryName']) ?></p>
                            <p class="font-medium text-left">Type</p><p><?= htmlspecialchars($product['animalName']) ?></p>
                        </div>
                        <div class="flex flex-col gap-[20px]">
                            <a href="update_product.php?productID=<?= $product['productID'] ?>&specID=<?= $product['specificationID'] ?>">
                                <button type="button" class="action-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6">
                                        <path d="M320 208C289.1 208 264 182.9 264 152C264 121.1 289.1 96 320 96C350.9 96 376 121.1 376 152C376 182.9 350.9 208 320 208zM320 432C350.9 432 376 457.1 376 488C376 518.9 350.9 544 320 544C289.1 544 264 518.9 264 488C264 457.1 289.1 432 320 432zM376 320C376 350.9 350.9 376 320 376C289.1 376 264 350.9 264 320C264 289.1 289.1 264 320 264C350.9 264 376 289.1 376 320z"/>
                                    </svg>
                                </button>
                            </a>
                            <form method="POST" action="../../controllers/ProductController.php" 
                                onsubmit="return confirm('Are you sure you want to delete this item?');">
                                <input type="hidden" name="action" value="singleDelete">
                                <input type="hidden" name="productID" value="<?= $product['productID'] ?>">
                                <input type="hidden" name="specificationID" value="<?= $product['specificationID'] ?>">
                                <button type="submit" class="delete-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6 mx-auto">
                                        <path d="M232.7 69.9L224 96L128 96C110.3 96 96 110.3 96 128C96 145.7 110.3 160 128 160L512 160C529.7 160 544 145.7 544 128C544 110.3 529.7 96 512 96L416 96L407.3 69.9C402.9 56.8 390.7 48 376.9 48L263.1 48C249.3 48 237.1 56.8 232.7 69.9zM512 208L128 208L149.1 531.1C150.7 556.4 171.7 576 197 576L443 576C468.3 576 489.3 556.4 490.9 531.1L512 208z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination Desktop -->
        <div class="hidden md:flex justify-center gap-2 mt-4">
            <?php if($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="px-3 py-1 border">Prev</a>
            <?php endif; ?>
            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="px-3 py-1 border <?= $i == $page ? 'bg-gray-300' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="px-3 py-1 border">Next</a>
            <?php endif; ?>
        </div>

        <!-- Pagination Mobile -->
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
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeBtn = document.getElementById('close-btn');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('-translate-x-full');
        });

        closeBtn.addEventListener('click', () => {
            mobileMenu.classList.add('-translate-x-full');
        });
    </script>

<?php include(__DIR__ . "/../partials/footer.php"); ?>
