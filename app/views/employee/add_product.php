<?php
session_start();
$title = "Add Products";

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../login.php");
    exit();
}

// Restrict customers from accessing employee pages
if ($_SESSION['userRole'] !== 'Employee') {
    header("Location: ../login.php");
    exit();
}

include(__DIR__ . "/../partials/header.php");
require_once(__DIR__ . "/../../controllers/ProductController.php");


$productController = new ProductController();
$categories = $productController->getCategories();
$animalTypes = $productController->getAnimalTypes();

// Initialize error messages, old values, and success message
$errors = [];
$old = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $productController->validateAndCreate($_POST, $_FILES);

    if ($result['success']) {
        $successMessage = "Product added successfully!";
        $old = []; // Clear form fields
    } else {
        $errors = $result['errors'];
        $old = $result['old'];
    }
}

?>

    <div class="flex items-center justify-center min-h-screen bg-white font-['Montserrat']">
        <div id="container" class="w-[840px] max-w-full border-[3px] border-black m-5">
            <form action="" method="post" enctype="multipart/form-data" class="flex flex-col gap-[10px]">
                <!-- Header -->
                <div class="flex items-center justify-center mt-[20px]">
                    <h3 class="font-bold text-[28px]">Add Product</h3>
                </div>

                <div class="flex flex-col md:flex-row justify-between">
                    <!-- Product Image -->
                    <div class="w-[300px] h-auto max-w-full border-2 border-black flex flex-col items-center justify-center m-3 p-4 mx-auto">
                        <label for="imageUpload" class="block font-bold text-left w-full">Upload Product Image</label>
                        <p class="text-[14px] text-black font-regular"><?= $errors['imageFile'] ?? '' ?></p>
                        <input type="file" id="imageUpload" name="imageFile" accept="image/*" class="mt-2 w-full file:bg-white hover:file:bg-[#4FB5D0]">
                    </div>

                    <!-- Name, Specification, Price -->
                    <div class="flex md:flex-row flex-col justify-between m-5 font-medium sm:gap-[20px] gap-[20px] text-[12px] md:text-[16px]">
                        <div class="flex flex-col gap-[20px]">
                            <div class="bg-[#4FB5D0] w-full sm:w-[350px] h-auto max-w-full border-2 border-black flex flex-col px-3 py-2 gap-1">
                                <h4 class="font-bold text-[12px] md:text-[16px] text-left">Product Name</h4>
                                <p class="text-[14px] text-black font-regular"><?= $errors['productName'] ?? '' ?></p>
                                <input type="text" id="productName" name="productName" value="<?= htmlspecialchars($old['productName'] ?? '') ?>" class="w-full h-[40px] border-2 border-black px-3">
                            </div>
                            <div class="bg-[#4FB5D0] w-full sm:w-[350px] h-auto max-w-full border-2 border-black flex flex-col px-3 py-2 gap-1">
                                <h4 class="font-bold text-[12px] md:text-[16px] text-left">Specification</h4>
                                <p class="text-[14px] text-black font-regular"><?= $errors['specificationName'] ?? '' ?></p>
                                <input type="text" id="specificationName" name="specificationName" value="<?= htmlspecialchars($old['specificationName'] ?? '') ?>" class="w-full h-[40px] border-2 border-black px-3">
                            </div>
                            <div class="bg-[#4FB5D0] w-full sm:w-[350px] h-auto max-w-full border-2 border-black flex flex-col px-3 py-2 gap-1">
                                <h4 class="font-bold text-[12px] md:text-[16px] text-left">Price</h4>
                                <p class="text-[14px] text-black font-regular"><?= $errors['productPrice'] ?? '' ?></p>
                                <div class="flex items-center gap-2">
                                    <p class="text-left">LKR</p>
                                    <input type="text" id="productPrice" name="productPrice" value="<?= htmlspecialchars($old['productPrice'] ?? '') ?>" class="flex-1 h-[40px] border-2 border-black px-3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category, Type, Stock -->
                <div class="flex m-5 items-left md:items-center md:justify-center gap-[20px] flex-wrap">
                    <div class="bg-[#4FB5D0] w-full md:w-[250px] h-auto max-w-full border-2 border-black flex flex-col text-left p-3">
                        <h4 class="font-bold text-[12px] md:text-[16px] mb-1">Category</h4>
                        <p class="text-[14px] text-black font-regular"><?= $errors['categoryID'] ?? '' ?></p>
                        <select name="categoryID" class="border-2 border-black w-full p-1">
                            <option selected disabled>Select option</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['categoryID'] ?>" <?= (isset($old['categoryID']) && $old['categoryID'] == $cat['categoryID']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['categoryName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="bg-[#4FB5D0] w-full md:w-[250px] h-auto max-w-full border-2 border-black flex flex-col text-left p-3">
                        <h4 class="font-bold text-[12px] md:text-[16px] mb-1">Type</h4>
                        <p class="text-[14px] text-black font-regular"><?= $errors['animalID'] ?? '' ?></p>
                        <select name="animalID" class="border-2 border-black w-full p-1">
                            <option selected disabled>Select option</option>
                            <?php foreach($animalTypes as $t): ?>
                                <option value="<?= $t['animalID'] ?>" <?= (isset($old['animalID']) && $old['animalID'] == $t['animalID']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($t['animalName']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="bg-[#4FB5D0] w-full md:w-[250px] h-auto max-w-full border-2 border-black flex flex-col px-3 py-2 gap-1">
                        <h4 class="font-bold text-[12px] md:text-[16px] text-left">Stock</h4>
                        <p class="text-[14px] text-black font-regular"><?= $errors['productStock'] ?? '' ?></p>
                        <input type="number" id="productStock" name="productStock" min="1" value="<?= htmlspecialchars($old['productStock'] ?? 1) ?>" class="w-full h-[40px] border-2 border-black px-3">
                    </div>
                </div>

                <!-- Details, Benefits, Nutrition -->
                <div class="flex flex-col m-5 items-center justify-center gap-[20px]">
                    <?php foreach (['details' => 'productDetails', 'benefits' => 'productBenefits', 'nutrition' => 'productNutrition'] as $label => $field): ?>
                        <div class="bg-[#4FB5D0] w-full lg:w-[800px] h-auto max-w-full border-2 border-black flex flex-col px-3 py-2 gap-1">
                            <h4 class="font-bold text-[12px] md:text-[16px] text-left"><?= ucfirst($label) ?></h4>
                            <p class="text-[14px] text-black font-regular"><?= $errors[$field] ?? '' ?></p>
                            <textarea id="<?= $field ?>" name="<?= $field ?>" rows="5" class="w-full h-[100px] border-2 border-black px-3 py-2 text-[12px]"><?= htmlspecialchars($old[$field] ?? '') ?></textarea>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="flex justify-between gap-[20px] mt-[20px] mb-[30px] px-[20px]">
                    <button type="button" class="flex-1 h-[45px] border-[2px] border-black bg-white text-black font-medium font-[Montserrat] text-[16px] hover:bg-gray-300 hover:text-white" 
                        onclick="window.location.href='dashboard.php'">Cancel
                    </button>
                    <button type="submit" class="flex-1 h-[45px] border-[2px] border-black bg-black text-white font-medium font-[Montserrat] text-[16px] hover:bg-[#6FAE8D] hover:text-black">Save</button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($successMessage): ?>
    <script>
        alert("<?= addslashes($successMessage) ?>");
        window.location.href = "dashboard.php";
    </script>
    <?php endif; ?>
</body>
