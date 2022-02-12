<?php
$page = 'success';
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

    <main class="main success-page position-relative d-flex align-items-center justify-content-center">
        <div class="main-cover h-100 position-absolute w-100"></div>
        <div class="container h-100 d-flex align-items-center flex-column justify-content-center" style="z-index: 2;">
            <svg version="1.1" width="45px" height="45px" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                <circle style="fill:#25AE88;" cx="25" cy="25" r="25" />
                <polyline style="fill:none;stroke:#FFFFFF;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" points="
	38,15 22,33 12,25 " />
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
            </svg>
            <h1 class="main-content text-center mt-3">Checkout Successful</h1>
            <p class="main-content text-center">Thank you for using our store!</p>
            <a class="btn-shop d-flex align-items-center justify-content-center mt-3" href="shop.php">Go Back</a>
        </div>
    </main>

    <?php require_once './tpl/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>