<?php
$page = 'checkout';
$profile_image = "";
$isEmptyCart = true;
$isLoggedIn = false;

include('inc/config.php');
include('inc/function.php');

session_start();

checkLogin();

if (isset($_SESSION) && isset($_SESSION['LogIn']) == true) {
    $profile_image = $_SESSION["image"];
    $isEmptyCart = empty($_SESSION["shopping_cart"]);
    $isLoggedIn = true;
}

if ($isEmptyCart) {
    redirect('shop.php');
}

$totalPrice = 0;

foreach ($_SESSION["shopping_cart"] as $k => $v) {
    $totalPrice +=  $_SESSION["shopping_cart"][$k]["price"] * $_SESSION["shopping_cart"][$k]["quantity"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && isset($_POST["action"]) == "checkout") {
        $_SESSION["shopping_cart"] = [];
        redirect('success.php');
    } else {
        if (isset($_POST["changeQ"]) && isset($_POST["changeQ"]) == "true") {
            if ($_POST["quantity"] >= 1) {
                $totalPrice = 0;
                foreach ($_SESSION["shopping_cart"] as $k => $v) {
                    if ($_POST["id"] == $k) {
                        $_SESSION["shopping_cart"][$k]["quantity"] = $_POST["quantity"];
                    }
                    $totalPrice +=  $_SESSION["shopping_cart"][$k]["price"] * $_SESSION["shopping_cart"][$k]["quantity"];
                }
            } else {
                $totalPrice -=  $_SESSION["shopping_cart"][$_POST["id"]]["price"] * $_SESSION["shopping_cart"][$_POST["id"]]["quantity"];
                unset($_SESSION["shopping_cart"][$_POST["id"]]);
            }
        } elseif (isset($_POST["removeItem"]) && isset($_POST["removeItem"]) == "true") {
            $totalPrice -=  $_SESSION["shopping_cart"][$_POST["id"]]["price"] * $_SESSION["shopping_cart"][$_POST["id"]]["quantity"];
            unset($_SESSION["shopping_cart"][$_POST["id"]]);
        }
        $isEmptyCart = empty($_SESSION["shopping_cart"]);

        if ($isEmptyCart) {
            redirect('shop.php');
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<?php require_once './tpl/head.php' ?>

<body>
    <?php require_once './tpl/header.php' ?>

    <main class="main d-flex">
        <div class="container d-flex flex-column py-5">
            <div class="d-flex my-4">
                <h2 class="d-flex fw-bolder">
                    Total price:
                    <span class="d-flex align-items-center ms-3">
                        <svg style="transform: translateY(2px)" xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-currency-euro" viewBox="0 0 16 16">
                            <path d="M4 9.42h1.063C5.4 12.323 7.317 14 10.34 14c.622 0 1.167-.068 1.659-.185v-1.3c-.484.119-1.045.17-1.659.17-2.1 0-3.455-1.198-3.775-3.264h4.017v-.928H6.497v-.936c0-.11 0-.219.008-.329h4.078v-.927H6.618c.388-1.898 1.719-2.985 3.723-2.985.614 0 1.175.05 1.659.177V2.194A6.617 6.617 0 0 0 10.341 2c-2.928 0-4.82 1.569-5.244 4.3H4v.928h1.01v1.265H4v.928z" />
                        </svg>
                        <?= $totalPrice ?>
                    </span>
                </h2>
                <form class="ms-auto" method="post">
                    <input class="d-none" name="action" value="checkout" type="text">
                    <button type="submit" class="btn btn-outline-success">Checkout</button>
                </form>
            </div>
            <div class="table-responsive w-100">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Cover</th>
                            <th scope="col">Name</th>
                            <th scope="col">Author</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Price</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <? foreach ($_SESSION["shopping_cart"] as $k => $v) : ?>
                            <tr>
                                <th scope="row"><?= $_SESSION["shopping_cart"][$k]["id"] ?></th>
                                <td>
                                    <img class="img-fluid checkout__cover" src="<?= $_SESSION["shopping_cart"][$k]["cover"] ?>" />
                                </td>
                                <td>
                                    <a href="book.php?id=<?= $_SESSION["shopping_cart"][$k]["id"] ?>"><?= $_SESSION["shopping_cart"][$k]["name"] ?></a>
                                </td>
                                <td><?= $_SESSION["shopping_cart"][$k]["author"] ?></td>
                                <td>
                                    <form method="post" class="d-flex" style="width: 180px">
                                        <input class="d-none" name="changeQ" value="true" type="text">
                                        <input class="d-none" name="id" value="<?= $k ?>" type="number">
                                        <input class="form-control w-25 p-0 ps-2" name="quantity" value="<?= $_SESSION["shopping_cart"][$k]["quantity"] ?>" type="number">
                                        <button type="submit" class="btn btn-outline-dark ms-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"></path>
                                                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-currency-euro" viewBox="0 0 16 16">
                                            <path d="M4 9.42h1.063C5.4 12.323 7.317 14 10.34 14c.622 0 1.167-.068 1.659-.185v-1.3c-.484.119-1.045.17-1.659.17-2.1 0-3.455-1.198-3.775-3.264h4.017v-.928H6.497v-.936c0-.11 0-.219.008-.329h4.078v-.927H6.618c.388-1.898 1.719-2.985 3.723-2.985.614 0 1.175.05 1.659.177V2.194A6.617 6.617 0 0 0 10.341 2c-2.928 0-4.82 1.569-5.244 4.3H4v.928h1.01v1.265H4v.928z" />
                                        </svg>
                                        <span style="font-size: 18px"><?= $_SESSION["shopping_cart"][$k]["price"] ?></span>
                                    </div>
                                </td>
                                <td>
                                    <form method="post" class="d-flex justify-content-center">
                                        <input class="d-none" name="removeItem" value="true" type="text">
                                        <input class="d-none" name="id" value="<?= $k ?>" type="number">
                                        <button type="submit" class="btn btn-outline-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <? endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <?php require_once './tpl/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>