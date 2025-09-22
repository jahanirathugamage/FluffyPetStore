<?php
session_start();
$title = "Profile";

require_once __DIR__ . "/../../models/User.php";
require_once __DIR__ . "/../../controllers/AuthController.php";

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../login.php");
    exit();
}

// Redirects employees to their dashboard instead of allowing them here
if ($_SESSION['userRole'] === 'Employee') {
    header("Location: ../employee/dashboard.php");
    exit();
}

$auth = new AuthController();
$userID = $_SESSION['userID'];
$user = $auth->getUserById($userID); 

include(__DIR__ . "/../partials/header.php");
include(__DIR__ . "/../partials/navbar.php");
?>

<div class="flex flex-col items-center justify-center font-['Montserrat'] m-20">
    <h2 class="font-bold text-[24px] md:text-[30px]">Profile</h2>
    <div class="flex flex-col items-left m-6 border-2 border-black p-10">
        <div class="flex gap-4 ">
            <p class="font-medium text-sm text-gray-500">Name</p>
            <p><?= htmlspecialchars($user['userName']) ?></p>
            <button>
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                    <path d="M100.4 417.2C104.5 402.6 112.2 389.3 123 378.5L304.2 197.3L338.1 163.4C354.7 180 389.4 214.7 442.1 267.4L476 301.3L442.1 335.2L260.9 516.4C250.2 527.1 236.8 534.9 222.2 539L94.4 574.6C86.1 576.9 77.1 574.6 71 568.4C64.9 562.2 62.6 553.3 64.9 545L100.4 417.2zM156 413.5C151.6 418.2 148.4 423.9 146.7 430.1L122.6 517L209.5 492.9C215.9 491.1 221.7 487.8 226.5 483.2L155.9 413.5zM510 267.4C493.4 250.8 458.7 216.1 406 163.4L372 129.5C398.5 103 413.4 88.1 416.9 84.6C430.4 71 448.8 63.4 468 63.4C487.2 63.4 505.6 71 519.1 84.6L554.8 120.3C568.4 133.9 576 152.3 576 171.4C576 190.5 568.4 209 554.8 222.5C551.3 226 536.4 240.9 509.9 267.4z"/>
                </svg>
            </button>
        </div>
        <div class="flex gap-4 ">
            <p class="font-medium text-sm text-gray-500">Email</p>
            <p><?= htmlspecialchars($user['userEmail']) ?></p>
        </div>
        <a href="/FluffyPetStore/public/logout.php">
            <button class="w-full h-[40px] bg-[#4FB5D0] font-bold text-white border-2 border-black mt-10">
                Logout
            </button>
        </a>
    </div>
</div>

<?php include(__DIR__ . "/../partials/footer.php"); ?>
