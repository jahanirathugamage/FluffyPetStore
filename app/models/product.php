<?php
require_once __DIR__ . '/Database.php';

class Product {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection(); 
    }

    // getting all the products from the db
    public function getAllProducts() {
        $sql = "
            SELECT
                p.productID,
                p.productName,
                p.productImage,
                a.animalName,
                c.categoryName,
                s.specificationID,
                s.specificationName,
                s.productPrice,
                s.productStock
            FROM product p
            INNER JOIN animal a ON p.productAnimalID = a.animalID
            INNER JOIN category c ON p.productCategoryID = c.categoryID
            INNER JOIN specification s ON p.productID = s.productID
            ORDER BY p.productID ASC
        ";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    // the product info through its id
    public function getProductByID($productID, $specID) {
        $sql = "
            SELECT
                p.productID,
                p.productName,
                p.productDetails,
                p.productBenefits,
                p.productNutrition,
                p.productImage,
                p.productAnimalID,
                p.productCategoryID,
                a.animalName,
                c.categoryName,
                s.specificationID,
                s.specificationName,
                s.productPrice,
                s.productStock
            FROM product p
            INNER JOIN animal a ON p.productAnimalID = a.animalID
            INNER JOIN category c ON p.productCategoryID = c.categoryID
            INNER JOIN specification s ON p.productID = s.productID
            WHERE p.productID = :productID AND s.specificationID = :specID
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':productID' => $productID, ':specID' => $specID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // for the product card display
    public function getProductsWithLimit($limit, $offset){
        $sql = "
            SELECT
                p.productID,
                p.productName,
                p.productImage,
                a.animalName,
                c.categoryName,
                s.specificationID,
                s.specificationName,
                s.productPrice,
                s.productStock
            FROM product p
            INNER JOIN animal a ON p.productAnimalID = a.animalID
            INNER JOIN category c ON p.productCategoryID = c.categoryID
            INNER JOIN specification s ON p.productID = s.productID
            ORDER BY p.productID ASC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategories() {
        $stmt = $this->conn->prepare("SELECT categoryID, categoryName FROM Category");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    public function getAnimalTypes() {
        $stmt = $this->conn->prepare("SELECT animalID, animalName FROM Animal");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    // create a product and insert it to the db
    public function create($data) {
        if (!isset($data['imageFile']) || $data['imageFile']['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $stmtAnimal = $this->conn->prepare("SELECT animalName FROM Animal WHERE animalID = :id");
        $stmtAnimal->execute([':id' => $data['animalID']]);
        $animalRow = $stmtAnimal->fetch(PDO::FETCH_ASSOC);
        $animalName = $animalRow ? $animalRow['animalName'] : 'unknown';

        $imageFile = $data['imageFile'];
        $imageName = time() . '_' . basename($imageFile['name']);
        $targetDir = __DIR__ . "/../../public/assets/images/" . $animalName . "/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (!move_uploaded_file($imageFile['tmp_name'], $targetDir . $imageName)) {
            return false;
        }

        try {
            $this->conn->beginTransaction();

            $sqlProduct = "INSERT INTO product 
                (productName, productDetails, productBenefits, productNutrition, productImage, productAnimalID, productCategoryID)
                VALUES (:productName, :details, :benefits, :nutrition, :productImage, :animalID, :categoryID)";
            $stmtProduct = $this->conn->prepare($sqlProduct);
            $stmtProduct->execute([
                ':productName'  => $data['productName'],
                ':details'      => $data['productDetails'],
                ':benefits'     => $data['productBenefits'],
                ':nutrition'    => $data['productNutrition'],
                ':productImage' => $imageName,
                ':animalID'     => $data['animalID'],
                ':categoryID'   => $data['categoryID']
            ]);

            $productID = $this->conn->lastInsertId();

            $sqlSpec = "INSERT INTO specification 
                (specificationName, productPrice, productStock, productID)
                VALUES (:specName, :price, :stock, :productID)";
            $stmtSpec = $this->conn->prepare($sqlSpec);
            $stmtSpec->execute([
                ':specName'  => $data['specificationName'],
                ':price'     => $data['productPrice'],
                ':stock'     => $data['productStock'],
                ':productID' => $productID
            ]);

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error creating product: " . $e->getMessage());
            return false;
        }
    }

    // update the info regarding a product in the db
    public function update($data) {
        try {
            $this->conn->beginTransaction();

            $stmtAnimal = $this->conn->prepare("SELECT animalID FROM Animal WHERE animalName = :name LIMIT 1");
            $stmtAnimal->execute([':name' => $data['type']]);
            $animalRow = $stmtAnimal->fetch(PDO::FETCH_ASSOC);
            $animalID = $animalRow ? $animalRow['animalID'] : null;

            $stmtCategory = $this->conn->prepare("SELECT categoryID FROM Category WHERE categoryName = :name LIMIT 1");
            $stmtCategory->execute([':name' => $data['category']]);
            $categoryRow = $stmtCategory->fetch(PDO::FETCH_ASSOC);
            $categoryID = $categoryRow ? $categoryRow['categoryID'] : null;

            if (!$animalID || !$categoryID) {
                throw new Exception("Invalid animal type or category");
            }

            $sqlProduct = "
                UPDATE product
                SET productName = :name,
                    productDetails = :details,
                    productBenefits = :benefits,
                    productNutrition = :nutrition,
                    productImage = :image,
                    productAnimalID = :animalID,
                    productCategoryID = :categoryID
                WHERE productID = :productID
            ";
            $stmtProduct = $this->conn->prepare($sqlProduct);
            $stmtProduct->execute([
                ':name' => $data['name'],
                ':details' => $data['details'],
                ':benefits' => $data['benefits'],
                ':nutrition' => $data['nutrition'],
                ':image' => $data['imageName'],
                ':animalID' => $animalID,
                ':categoryID' => $categoryID,
                ':productID' => $data['productID']
            ]);

            $sqlSpec = "
                UPDATE specification
                SET specificationName = :specName,
                    productPrice = :price,
                    productStock = :stock
                WHERE specificationID = :specID
            ";
            $stmtSpec = $this->conn->prepare($sqlSpec);
            $stmtSpec->execute([
                ':specName' => $data['specification'],
                ':price' => $data['price'],
                ':stock' => $data['stock'],
                ':specID' => $data['specID']
            ]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error updating product: " . $e->getMessage());
            return false;
        }
    }
    
    // count the specifications of the product so that all the product's specifications can be found
    public function countSpecificationsByProductID($productID) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM specification WHERE productID = :id");
        $stmt->execute([':id' => $productID]);
        return (int)$stmt->fetchColumn();
    }

    // delete a product in the db using it's ID
    public function deleteProductByID($productID) {
        $stmt = $this->conn->prepare("DELETE FROM product WHERE productID = :id");
        return $stmt->execute([':id' => $productID]);
    }

    // delete a specification based on an iD
    public function deleteSpecificationByID($specificationID) {
        $stmt = $this->conn->prepare("DELETE FROM specification WHERE specificationID = :id");
        return $stmt->execute([':id' => $specificationID]);
    }

    // get all the product's related specifications
    public function getSpecificationsByProductID($productID) {
        $sql = "
            SELECT
                s.specificationID,
                s.specificationName,
                s.productPrice,
                s.productStock
            FROM specification s
            WHERE s.productID = :productID
            ORDER BY s.specificationID ASC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':productID' => $productID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
