<?php
session_start();
$title = "Create Employee";

include(__DIR__ . "/../partials/header.php");
require_once __DIR__ . "/../../controllers/AuthController.php";

$auth = new AuthController();
$name = $email = $password = $role = '';
$error = '';

// Ensure only logged-in employees can access
if (!isset($_SESSION['userID']) || $_SESSION['userRole'] !== 'Employee') {
    header("Location: /FluffyPetStore/public/login.php");
    exit;
}

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'Employee'; // ensure that the role is as Employee

    $result = $auth->registerEmployee($name, $email, $password, $role);
    if(isset($result['error'])) {
        $error = $result['error'];
    } else {
        // Redirect to employee dashboard after successful creation
        header("Location: /FluffyPetStore/app/views/employee/dashboard.php");
        exit;
    }
}
?>

<div class="flex items-center justify-center min-h-screen bg-white font-[Montserrat]">
    <div id="container" class="w-[840px] max-w-full border-0 md:border-[3px] md:border-black flex flex-col md:flex-row">

        <div class="flex flex-col items-center justify-center w-full md:w-1/2 p-6 gap-2 md:gap-4">
            <a href="index.php"><img src="/FluffyPetStore/public/assets/images/fluffy-blue.png" alt="Fluffy Logo" class="h-21"></a>
            <h3 class="font-medium text-[20px] mb-4">Create Employee Account</h3>

            <?php if($error): ?>
                <p class="text-[14px] text-red-500 font-regular"><?php echo $error; ?></p>
            <?php endif; ?>

            <form action="" method="post" class="flex flex-col gap-4 w-full max-w-[330px]">
                <input type="text" name="name" placeholder="Name" value="<?php echo htmlspecialchars($name); ?>" class="w-full h-[40px] border-[1.5px] border-black px-3" required>
                <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" class="w-full h-[40px] border-[1.5px] border-black px-3" required>
                <input type="password" name="password" placeholder="Password" value="<?php echo htmlspecialchars($password); ?>" class="w-full h-[40px] border-[1.5px] border-black px-3" required>

                <button type="submit" name="submit" value="register" class="w-full h-[40px] bg-[#4FB5D0] font-bold text-white border-[1.5px] border-black">
                    Create
                </button>
            </form>
        </div>

        <div class="hidden md:flex items-center justify-center w-1/2 bg-white">
            <img src="/FluffyPetStore/public/assets/images/umbrella.png" alt="Bumpy Pizza" class="h-60">
        </div>
    </div>
</div>
