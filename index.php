<?php
$page = '';
$profile_image = "";
$isEmptyCart = true;
$isLoggedIn = false;

session_start();

if (isset($_SESSION) && isset($_SESSION['LogIn']) == true) {
    $profile_image = $_SESSION["image"];
    $isEmptyCart = empty($_SESSION["shopping_cart"]);
    $isLoggedIn = true;
}

?>
<!DOCTYPE html>
<html lang="en">

<?php require_once './tpl/head.php' ?>

<body>
    <?php require_once './tpl/header.php' ?>

    <main class="main main-page position-relative d-flex align-items-center justify-content-center">
        <div class="main-cover h-100 position-absolute w-100"></div>
        <div class="container h-100 d-flex align-items-center flex-column justify-content-center" style="z-index: 2;">
            <h1 class="main-content text-center">Find Your Next Book</h1>
            <p class="main-content text-center">Where Everything You Need To Know Is Only An Arm's Length Away!</p>
            <a class="btn-shop d-flex align-items-center justify-content-center mt-3" href="shop.php">Shop Now</a>
        </div>
    </main>

    <?php require_once './tpl/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>