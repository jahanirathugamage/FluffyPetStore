<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_data'])) {
    $_SESSION['cart'] = json_decode($_POST['cart_data'], true);
}

// Require login
if (!isset($_SESSION['userID'])) {
    header("Location: /FluffyPetStore/public/login.php"); 
    exit();
}

// Only allow Customers to view this
if (!isset($_SESSION['userRole']) || $_SESSION['userRole'] !== 'Customer') {
    header("Location: /FluffyPetStore/public/login.php"); 
    exit();
}

// the cart sync 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['sync_cart'])) {
    $data = json_decode(file_get_contents("php://input"), true);
    if ($data && isset($data['cart'])) {
        $_SESSION['cart'] = $data['cart'];
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid cart data']);
    }
    exit;
}

$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['sync_cart'])) {
    $email = trim($_POST['email']);
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city'] ?? '');
    $phone = trim($_POST['phone']);
    $cardNumber = preg_replace('/\s+/', '', $_POST['cardNumber']);
    $expDate = trim($_POST['expDate']);
    $cvv = trim($_POST['cvv']);
    $cardName = trim($_POST['cardName']);

    // Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address.";
    }

    // Required text fields
    if ($fname === '') $errors['fname'] = "First name is required.";
    if ($lname === '') $errors['lname'] = "Last name is required.";
    if ($address === '') $errors['address'] = "Address is required.";
    if ($city === '') $errors['city'] = "Please select a city.";

    // Phone (optional but if filled must be 10 digits)
    if ($phone !== '' && !preg_match('/^[0-9]{10}$/', $phone)) {
        $errors['phone'] = "Phone number must be 10 digits.";
    }

    // Card validation
    if (!preg_match('/^[0-9]{16}$/', $cardNumber)) {
        $errors['cardNumber'] = "Card number must be 16 digits.";
    }

    if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expDate)) {
        $errors['expDate'] = "Expiration date must be in MM/YY format.";
    }

    if (!preg_match('/^[0-9]{3}$/', $cvv)) {
        $errors['cvv'] = "Security code must be 3 digits.";
    }

    if ($cardName === '') {
        $errors['cardName'] = "Name on card is required.";
    }

    // Success
    if (empty($errors)) {
        $success = true;
        $_SESSION['cart'] = []; // Clear cart
        echo "<script>
                alert('Order Successful! Thank you for shopping with us!');
                window.location.href = '/FluffyPetStore/public/index.php';
              </script>";
        exit();
    }
}

$title = "Checkout";
include(__DIR__ . "/partials/header.php");
?>

<body class="bg-gray-50">

<div class="min-h-screen flex flex-col font-[Montserrat]">

    <!-- Header -->
    <div class="bg-[#4FB5D0] py-4 px-6 flex justify-center">
        <img src="/FluffyPetStore/public/assets/images/fluffy-logo.png" alt="logo" class="w-28">
    </div>

    <!-- Content -->
    <div class="flex flex-col lg:flex-row lg:justify-center lg:gap-12 px-4 py-8 max-w-6xl mx-auto w-full">

        <!-- Left Section (Form) -->
        <form method="POST" class="w-full lg:w-2/3 bg-white p-6 border-2 border-black shadow-sm">

            <!-- Contact -->
            <h2 class="text-lg font-semibold mb-4">Contact</h2>
            <?php if(isset($errors['email'])): ?><p class="text-red-500 text-sm mb-1"><?= $errors['email'] ?></p><?php endif; ?>
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" class="w-full border border-black px-3 py-2 mb-4">

            <label class="flex items-center space-x-2 mb-6">
                <input type="checkbox" class="accent-[#4FB5D0]" checked>
                <span class="text-sm">Sign up for news and special offers</span>
            </label>

            <!-- Shipping -->
            <h2 class="text-lg font-semibold mb-4">Shipping Address</h2>
            <div class="space-y-3 mb-6">
                <select name="country" class="w-full border px-3 py-2 border-black" required>
                    <option value="Sri Lanka">Sri Lanka</option>
                </select>

                <div class="flex space-x-3">
                    <div class="w-1/2">
                        <?php if(isset($errors['fname'])): ?><p class="text-red-500 text-sm mb-1"><?= $errors['fname'] ?></p><?php endif; ?>
                        <input type="text" name="fname" placeholder="First name" value="<?= htmlspecialchars($_POST['fname'] ?? '') ?>" class="w-full border px-3 py-2 border-black">
                    </div>
                    <div class="w-1/2">
                        <?php if(isset($errors['lname'])): ?><p class="text-red-500 text-sm mb-1"><?= $errors['lname'] ?></p><?php endif; ?>
                        <input type="text" name="lname" placeholder="Last name" value="<?= htmlspecialchars($_POST['lname'] ?? '') ?>" class="w-full border px-3 py-2 border-black">
                    </div>
                </div>

                <?php if(isset($errors['address'])): ?><p class="text-red-500 text-sm mb-1"><?= $errors['address'] ?></p><?php endif; ?>
                <input type="text" name="address" placeholder="Address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>" class="w-full border px-3 py-2 border-black">

                <input type="text" name="apartment" placeholder="Apartment, suite, etc. (optional)" value="<?= htmlspecialchars($_POST['apartment'] ?? '') ?>" class="w-full border px-3 py-2 border-black">

                <?php if(isset($errors['city'])): ?><p class="text-red-500 text-sm mb-1"><?= $errors['city'] ?></p><?php endif; ?>
                <select name="city" class="w-full border px-3 py-2 border-black">
                    <option disabled <?= empty($_POST['city']) ? 'selected' : '' ?>>City</option>
                    <option <?= (($_POST['city'] ?? '') === 'Colombo') ? 'selected' : '' ?>>Colombo</option>
                    <option <?= (($_POST['city'] ?? '') === 'Kandy') ? 'selected' : '' ?>>Kandy</option>
                    <option <?= (($_POST['city'] ?? '') === 'Galle') ? 'selected' : '' ?>>Galle</option>
                </select>

                <?php if(isset($errors['phone'])): ?><p class="text-red-500 text-sm mb-1"><?= $errors['phone'] ?></p><?php endif; ?>
                <input type="text" name="phone" placeholder="Phone (optional)" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" class="w-full border px-3 py-2 border-black">
            </div>

            <!-- Delivery -->
            <div class="border p-3 border-black mb-6 flex justify-between items-center">
                <div>
                    <p class="font-medium">Standard Delivery</p>
                    <p class="text-sm text-gray-500">Standard Delivery within 48hrs</p>
                </div>
                <p class="font-medium">LKR 300.00</p>
            </div>

            <!-- Payment -->
            <h2 class="text-lg font-semibold mb-4">Payment</h2>
            <p class="text-sm text-gray-500 mb-4">All transactions are secure and encrypted</p>

            <?php if(isset($errors['cardNumber'])): ?><p class="text-red-500 text-sm mb-1"><?= $errors['cardNumber'] ?></p><?php endif; ?>
            <input type="text" name="cardNumber" placeholder="Card number" value="<?= htmlspecialchars($_POST['cardNumber'] ?? '') ?>" class="w-full border px-3 py-2 border-black mb-3">

            <div class="flex space-x-3">
                <div class="w-1/2">
                    <?php if(isset($errors['expDate'])): ?><p class="text-red-500 text-sm mb-1"><?= $errors['expDate'] ?></p><?php endif; ?>
                    <input type="text" name="expDate" placeholder="MM/YY" value="<?= htmlspecialchars($_POST['expDate'] ?? '') ?>" class="w-full border px-3 py-2 border-black">
                </div>
                <div class="w-1/2">
                    <?php if(isset($errors['cvv'])): ?><p class="text-red-500 text-sm mb-1"><?= $errors['cvv'] ?></p><?php endif; ?>
                    <input type="text" name="cvv" placeholder="CVV" value="<?= htmlspecialchars($_POST['cvv'] ?? '') ?>" class="w-full border px-3 py-2 border-black">
                </div>
            </div>

            <?php if(isset($errors['cardName'])): ?><p class="text-red-500 text-sm mb-1"><?= $errors['cardName'] ?></p><?php endif; ?>
            <input type="text" name="cardName" placeholder="Name on card" value="<?= htmlspecialchars($_POST['cardName'] ?? '') ?>" class="w-full border px-3 py-2 border-black mt-3 mb-6">

            <!-- Pay Button -->
            <button type="submit" class="w-full bg-black text-white py-3 font-medium">Pay now</button>

            <a href="/FluffyPetStore/public/index.php" class="flex items-center justify-center mt-6">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                    <path fill="#69a985" d="M169.4 297.4C156.9 309.9 156.9 330.2 169.4 342.7L361.4 534.7C373.9 547.2 394.2 547.2 406.7 534.7C419.2 522.2 419.2 501.9 406.7 489.4L237.3 320L406.6 150.6C419.1 138.1 419.1 117.8 406.6 105.3C394.1 92.8 373.8 92.8 361.3 105.3L169.3 297.3z"/>
                </svg>
                <p class="text-[#69A985] text-sm hover:underline">Return to shopping</p>
            </a>
        </form>

        <!-- Right Section (Order Summary) -->
        <div class="w-full lg:w-1/3 mt-8 lg:mt-0">
            <div class="bg-white p-6 border-2 border-black shadow-sm">
                <h2 class="text-lg font-semibold mb-4">Order Summary</h2>

                <div class="space-y-4">
                    <?php 
                    $cart = $_SESSION['cart'] ?? [];
                    $subtotal = 0;
                    foreach($cart as $item):
                        $lineTotal = $item['price'] * $item['quantity'];
                        $subtotal += $lineTotal;
                    ?>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3 relative">
                                <div class="relative">
                                    <img src="<?= htmlspecialchars($item['image']) ?>" class="h-12">
                                    <span class="absolute -top-2 -right-2 bg-black text-white text-xs rounded-full px-2 py-0.5">
                                        <?= $item['quantity'] ?>
                                    </span>
                                </div>
                                <div class="flex flex-col">
                                    <p class="text-sm"><?= htmlspecialchars($item['name']) ?></p>
                                    <p class="text-[12px]"><?= htmlspecialchars($item['spec']) ?></p>
                                </div>
                            </div>
                            <p class="text-sm font-medium">LKR <?= number_format($lineTotal,2) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Totals -->
                <div class="mt-6 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <div class="flex gap-1">
                            <p>Subtotal</p>
                            <p>·</p>
                            <p><?= count($cart) ?> items</p>
                        </div>
                        <p>LKR <?= number_format($subtotal,2) ?></p>
                    </div>
                    <div class="flex justify-between">
                        <p>Shipping</p>
                        <p>LKR 300.00</p>
                    </div>
                    <div class="flex justify-between font-semibold text-base">
                        <p>Total</p>
                        <p>LKR <?= number_format($subtotal + 300,2) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        // On checkout page load, sync localStorage cart into PHP session
        document.addEventListener("DOMContentLoaded", function () {
            let cart = JSON.parse(localStorage.getItem("cart")) || [];
            if (cart.length > 0) {
                fetch(window.location.href + "?sync_cart=1", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ cart: cart })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        localStorage.removeItem("cart"); 
                        location.reload(); 
                    }
                });
            }
        });

    </script>

</body>
</html>
