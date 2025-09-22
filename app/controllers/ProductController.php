<?php
require_once(__DIR__ . "/../models/product.php");

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    // get all the products
    public function getProducts() {
        return $this->productModel->getAllProducts();
    }

    // get all the categories
    public function getCategories() {
        return $this->productModel->getCategories();
    }

    // get all the animal types
    public function getAnimalTypes() {
        return $this->productModel->getAnimalTypes();
    }

    // get all the products in a paginated way
    public function getProductsPaginated($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        return $this->productModel->getProductsWithLimit($perPage, $offset);
    }

    // validate the product information before sending it to the model
    public function validateAndCreate($postData, $fileData) {
        $errors = [];
        $old = $postData;

        // Validation
        if (empty($postData['productName'])) {
            $errors['productName'] = "*Product name is required";
        }
        if (empty($postData['specificationName'])) {
            $errors['specificationName'] = "*Specification is required";
        }
        if (empty($postData['productPrice']) || !is_numeric($postData['productPrice'])) {
            $errors['productPrice'] = "*Valid price is required";
        }
        if (empty($postData['categoryID'])) {
            $errors['categoryID'] = "*Category is required";
        }
        if (empty($postData['animalID'])) {
            $errors['animalID'] = "*Animal type is required";
        }
        if (empty($postData['productStock']) || !is_numeric($postData['productStock'])) {
            $errors['productStock'] = "*Stock must be a number";
        }
        if (empty(trim($postData['productDetails'] ?? ''))) {
            $errors['productDetails'] = "*Product details are required";
        }
        if (empty(trim($postData['productBenefits'] ?? ''))) {
            $errors['productBenefits'] = "*Product benefits are required";
        }
        if (empty(trim($postData['productNutrition'] ?? ''))) {
            $errors['productNutrition'] = "*Product nutrition information is required";
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors'  => $errors,
                'old'     => $old
            ];
        }

        $success = $this->productModel->create(
            array_merge($postData, ['imageFile' => $fileData['imageFile']])
        );

        return [
            'success' => $success,
            'errors'  => $success ? [] : ['general' => 'Failed to create product.'],
            'old'     => $old
        ];
    }

    // get product info using it's ID
    public function getProduct($productID, $specID) {
        return $this->productModel->getProductByID($productID, $specID);
    }

    // validate the product update information before sending it to the model
    public function validateAndUpdate($postData, $fileData) {
        $errors = [];

        // validation
        if (empty($postData['name'])) $errors['name'] = "*Product name is required";
        if (empty($postData['specification'])) $errors['specification'] = "*Specification is required";
        if (!is_numeric($postData['price'])) $errors['price'] = "*Valid price is required";
        if (!is_numeric($postData['stock'])) $errors['stock'] = "*Stock must be a number";
        if (empty($postData['category'])) $errors['category'] = "*Category required";
        if (empty($postData['type'])) $errors['type'] = "*Animal type required";
        if (empty(trim($postData['details'] ?? ''))) $errors['details'] = "*Details required";
        if (empty(trim($postData['benefits'] ?? ''))) $errors['benefits'] = "*Benefits required";
        if (empty(trim($postData['nutrition'] ?? ''))) $errors['nutrition'] = "*Nutrition required";

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $imageName = $postData['currentImage'];
        if (isset($fileData['imageFile']) && $fileData['imageFile']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($fileData['imageFile']['name'], PATHINFO_EXTENSION);
            $imageName = time() . '.' . $ext;
            $targetDir = __DIR__ . "/../../public/assets/images/" . $postData['type'] . "/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            move_uploaded_file($fileData['imageFile']['tmp_name'], $targetDir . $imageName);
        }

        $success = $this->productModel->update(array_merge($postData, ['imageName' => $imageName]));

        return [
            'success' => $success,
            'errors' => $success ? [] : ['general' => 'Failed to update product.']
        ];
    }

    // delete a single product through the inline thing
    public function deleteSingle($productID, $specificationID) {
        $specCount = $this->productModel->countSpecificationsByProductID($productID);

        if ($specCount <= 1) {
            $this->productModel->deleteProductByID($productID);
            return "Product deleted successfully!";
        } else {
            $this->productModel->deleteSpecificationByID($specificationID);
            return "Product specification deleted successfully!";
        }
    }

    public function getProductSpecifications($productID) {
        return $this->productModel->getSpecificationsByProductID($productID);
    }
}

// triggers the system to go back to the updated dashboard.php after the deletion of an item.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ProductController();
    $action = $_POST['action'] ?? '';

    if ($action === 'singleDelete') {
        $msg = $controller->deleteSingle((int)$_POST['productID'], (int)$_POST['specificationID']);
        echo "<script>alert('$msg');window.location.href='../views/employee/dashboard.php';</script>";
    }
}
