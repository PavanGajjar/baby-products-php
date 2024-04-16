<?php
include './helper/session_check.php';
if (isset($_SESSION['username'])) {
    $welcome_message = "Welcome back, " . $_SESSION['username'] . "!";
} else {
    $welcome_message = "Welcome to our baby products store!";
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include './layout/head.html'; ?>
<body>
    <?php include './layout/navbar.html'; ?>
    <div class="container mt-5">
        <h1>Welcome to Our Baby Products Store</h1>
        <p class="welcome-message"><?php echo $welcome_message; ?></p>
        <a href="products.php" class="products-button">View Products</a>
    </div>
</body>

</html>