<?php
session_start();
$title = "Update Products";

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

// Get productID and specID from URL
$productID = $_GET['productID'] ?? null;
$specID = $_GET['specID'] ?? null;

if (!$productID || !$specID) {
    header("Location: dashboard.php");
    exit();
}

// Fetch product details
$product = $productController->getProduct($productID, $specID);

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = array_merge($_POST, [
        'productID'   => $productID,
        'specID'      => $specID,
        'currentImage'=> $product['productImage']
    ]);
    
    $result = $productController->validateAndUpdate($postData, $_FILES);

    if ($result['success']) {
        $successMessage = "Product updated successfully!";
    } else {
        $errors = $result['errors'];
    }
}
?>

<div class="flex items-center justify-center min-h-screen bg-white font-['Montserrat']">
    <div id="container" class="w-[840px] max-w-full border-[3px] border-black m-5">
        <form action="" method="post" enctype="multipart/form-data" class="flex flex-col gap-[10px]">
            <!-- Header -->
            <div class="flex items-center justify-center mt-[20px] mx-[20px]">
                <h3 class="font-bold text-[28px]">Update Product</h3>
            </div>

            <!-- product Image, Name, Specification & price -->
            <div class="flex md:flex-row flex-col justify-between m-5 font-medium sm:gap-[20px] gap-[20px] text-[12px] md:text-[16px]">
                <div class="w-[300px] h-auto max-w-full border-2 border-black flex flex-col items-center justify-center m-3 p-4 mx-auto">
                    <label for="imageUpload" id="image-label" class="block font-bold text-left w-full">Upload Product Image</label>
                    <?php if(isset($errors['imageFile'])): ?>
                        <p class="text-black text-sm mt-1">*<?= htmlspecialchars($errors['imageFile']) ?></p>
                    <?php endif; ?>
                    <input type="file" id="imageUpload" name="imageFile" accept="image/*" class="mt-2 w-full file:bg-white hover:file:bg-[#4FB5D0]">
                    <p class="text-sm mt-2">Current Image:</p>
                    <img src="/FluffyPetStore/public/assets/images/<?= htmlspecialchars($product['animalName']) ?>/<?= htmlspecialchars($product['productImage']) ?>" class="w-24 h-24 object-contain mt-1">
                </div>

                <div class="flex flex-col gap-[20px]">
                    <div class="bg-[#4FB5D0] w-full sm:w-[350px] h-auto max-w-full border-2 border-black flex flex-col px-3 py-2 gap-1">
                        <h4 class="font-bold text-[12px] md:text-[16px] text-left">Product Name</h4>
                        <?php if(isset($errors['name'])): ?>
                            <p class="text-black text-sm">*<?= htmlspecialchars($errors['name']) ?></p>
                        <?php endif; ?>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['productName']) ?>" class="w-full h-[40px] border-2 border-black px-3" required>
                    </div>

                    <div class="bg-[#4FB5D0] w-full sm:w-[350px] h-auto max-w-full border-2 border-black flex flex-col px-3 py-2 gap-1">
                        <h4 class="font-bold text-[12px] md:text-[16px] text-left">Specification</h4>
                        <?php if(isset($errors['specification'])): ?>
                            <p class="text-black text-sm">*<?= htmlspecialchars($errors['specification']) ?></p>
                        <?php endif; ?>
                        <input type="text" id="specification" name="specification" value="<?= htmlspecialchars($product['specificationName']) ?>" class="w-full h-[40px] border-2 border-black px-3" required>
                    </div>

                    <div class="bg-[#4FB5D0] w-full sm:w-[350px] h-auto max-w-full border-2 border-black flex flex-col px-3 py-2 gap-1">
                        <h4 class="font-bold text-[12px] md:text-[16px] text-left">Price</h4>
                        <?php if(isset($errors['price'])): ?>
                            <p class="text-black text-sm">*<?= htmlspecialchars($errors['price']) ?></p>
                        <?php endif; ?>
                        <div class="flex items-center gap-2">
                            <p class="text-left">LKR</p>
                            <input type="number" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars($product['productPrice']) ?>" class="flex-1 h-[40px] border-2 border-black px-3" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- product category, type and stock -->
            <div class="flex m-5 items-left md:items-center md:justify-center gap-[20px] flex-wrap">
                <div class="bg-[#4FB5D0] w-full md:w-[250px] h-auto max-w-full border-2 border-black flex flex-col text-left p-3 gap-1">
                    <h4 class="font-bold text-[12px] md:text-[16px] mb-1">Category</h4>
                    <?php if(isset($errors['category'])): ?>
                        <p class="text-black text-sm">*<?= htmlspecialchars($errors['category']) ?></p>
                    <?php endif; ?>
                    <select name="category" class="border-2 border-black w-full p-1" required>
                        <?php foreach($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['categoryName']) ?>" <?= $product['categoryName'] == $category['categoryName'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['categoryName']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="bg-[#4FB5D0] w-full md:w-[250px] h-auto max-w-full border-2 border-black flex flex-col text-left p-3 gap-1">
                    <h4 class="font-bold text-[12px] md:text-[16px] mb-1">Type</h4>
                    <?php if(isset($errors['type'])): ?>
                        <p class="text-black text-sm">*<?= htmlspecialchars($errors['type']) ?></p>
                    <?php endif; ?>
                    <select name="type" class="border-2 border-black w-full p-1" required>
                        <?php foreach($animalTypes as $type): ?>
                            <option value="<?= htmlspecialchars($type['animalName']) ?>" <?= $product['animalName'] == $type['animalName'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['animalName']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="bg-[#4FB5D0] w-full md:w-[250px] h-auto max-w-full border-2 border-black flex flex-col px-3 py-2 gap-1">
                    <h4 class="font-bold text-[12px] md:text-[16px] text-left">Stock</h4>
                    <?php if(isset($errors['stock'])): ?>
                        <p class="text-black text-sm">*<?= htmlspecialchars($errors['stock']) ?></p>
                    <?php endif; ?>
                    <input type="number" id="stock" name="stock" step="1" min="0" value="<?= htmlspecialchars($product['productStock']) ?>" class="w-full h-[40px] border-2 border-black px-3" required>
                </div>
            </div>

            <!-- product Information -->
            <div class="flex flex-col m-5 items-center justify-center gap-[20px]">
                <?php foreach(['details'=>'Details','benefits'=>'Benefits','nutrition'=>'Nutrition'] as $field=>$label): ?>
                    <div class="bg-[#4FB5D0] w-full lg:w-[800px] h-auto max-w-full border-2 border-black flex flex-col px-3 py-2 gap-1">
                        <h4 class="font-bold text-[12px] md:text-[16px] text-left"><?= $label ?></h4>
                        <?php if(isset($errors[$field])): ?>
                            <p class="text-black text-sm">*<?= htmlspecialchars($errors[$field]) ?></p>
                        <?php endif; ?>
                        <textarea id="<?= $field ?>" name="<?= $field ?>" rows="5" class="w-full h-[100px] border-2 border-black px-3 py-2" required><?= htmlspecialchars($product['product'.ucfirst($field)]) ?></textarea>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Save / Cancel Buttons at Bottom -->
            <div class="flex justify-between gap-[20px] mt-[20px] mb-[30px] px-[20px]">
                <button id="cancelBtn" type="button" class="flex-1 h-[45px] border-[2px] border-black bg-white text-black font-medium font-[Montserrat] text-[16px] hover:bg-gray-300 hover:text-white" onclick="window.location.href='dashboard.php'">
                    Cancel
                </button>
                <button id="saveBtn" type="submit" class="flex-1 h-[45px] border-[2px] border-black bg-black text-white font-medium font-[Montserrat] text-[16px] hover:bg-[#6FAE8D] hover:text-black">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<?php if($successMessage): ?>
<script>
    alert("<?= addslashes($successMessage) ?>");
    window.location.href = "dashboard.php";
</script>
<?php endif; ?>
</body>
