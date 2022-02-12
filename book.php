<?php
$page = 'book';
$profile_image = "";
$isEmptyCart = true;
$isLoggedIn = false;
$bookID = null;
$isFullOpen = false;

session_start();

if (isset($_SESSION) && isset($_SESSION['LogIn']) == true) {
    $profile_image = $_SESSION["image"];
    $isEmptyCart = empty($_SESSION["shopping_cart"]);
    $isLoggedIn = true;
}

include('inc/config.php');
include('inc/function.php');

if (isset($_GET['id'])) {
    $bookID = $_GET['id'];
} else {
    redirect('shop.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['isFullOpen'])) {
        if ($_POST['isFullOpen'] == "true") {
            $isFullOpen = true;
        } else {
            $isFullOpen = false;
        }
        $_POST = [];
    }

    if (isset($_POST['id'])) {
        if (isset($_SESSION) && isset($_SESSION['LogIn']) == true) {
            $itemArray = array($_POST['id'] => array("name" => $_POST['name'], "id" => $_POST['id'], "quantity" => 1, "price" => $_POST["price"], "cover" => $_POST["cover"], "author" => $_POST["author"]));

            if (!empty($_SESSION["shopping_cart"])) {
                if (in_array($_POST["id"], array_keys($_SESSION["shopping_cart"]))) {
                    foreach ($_SESSION["shopping_cart"] as $k => $v) {
                        if ($_POST["id"] == $k) {
                            if (empty($_SESSION["shopping_cart"][$k]["quantity"])) {
                                $_SESSION["shopping_cart"][$k]["quantity"] = 0;
                            }
                            $_SESSION["shopping_cart"][$k]["quantity"] += 1;
                        }
                    }
                } else {
                    $_SESSION["shopping_cart"] = array_merge($_SESSION["shopping_cart"], $itemArray);
                }
            } else {
                $_SESSION["shopping_cart"] = $itemArray;
            }
            $isEmptyCart = empty($_SESSION["shopping_cart"]);
        } else {
            redirect("login.php");
        }
        $_POST = [];
    }
}

$db = connectDb();


$sql = $db->prepare("SELECT * FROM books WHERE id= ?");
$sql->execute([$bookID]);
$book = $sql->fetchAll(PDO::FETCH_ASSOC);
$book = isset($book[0]) ? $book[0] : null;

if (!isset($book)) {
    $db = null;
    redirect('shop.php');
}

$sql = $db->prepare("SELECT * FROM categories WHERE id= ?");
$sql->execute([$book['category_id']]);
$category = $sql->fetchAll(PDO::FETCH_ASSOC);
$category = isset($category[0]) ? $category[0] : null;

if (!isset($category)) {
    $db = null;
    redirect('shop.php');
}

$sql = $db->prepare("SELECT * FROM books WHERE category_id= ? AND id != ? LIMIT 3");
$sql->execute([$book['category_id'], $bookID]);
$recommendations = $sql->fetchAll(PDO::FETCH_ASSOC);
$recommendations = isset($recommendations) ? $recommendations : null;

$db = null;

?>
<!DOCTYPE html>
<html lang="en">

<?php require_once './tpl/head.php' ?>

<body>
    <?php require_once './tpl/header.php' ?>

    <main class="main d-flex">
        <div class="container d-flex flex-column flex-md-row justify-content-center py-5">
            <div class="col-12 col-md-5">
                <img src="<?= $book['cover'] ?>" class="img-fluid w-100" alt="cover">
            </div>
            <div class="col-12 col-md-7 ms-3 pt-4">
                <a href="shop.php">Go Back</a>
                <h4 class="card-title fw-bold"><?= $book['name'] ?></h4>
                <p class="d-flex mb-2">Author: <?= $book['author'] ?></p>
                <p class="d-flex mb-2">Category: <?= $category['title'] ?></p>
                <div class="d-flex mb-2">Price:
                    <p class="card-text price d-flex align-items-center link-success ms-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-currency-euro" viewBox="0 0 16 16">
                            <path d="M4 9.42h1.063C5.4 12.323 7.317 14 10.34 14c.622 0 1.167-.068 1.659-.185v-1.3c-.484.119-1.045.17-1.659.17-2.1 0-3.455-1.198-3.775-3.264h4.017v-.928H6.497v-.936c0-.11 0-.219.008-.329h4.078v-.927H6.618c.388-1.898 1.719-2.985 3.723-2.985.614 0 1.175.05 1.659.177V2.194A6.617 6.617 0 0 0 10.341 2c-2.928 0-4.82 1.569-5.244 4.3H4v.928h1.01v1.265H4v.928z" />
                        </svg>
                        <?= $book['price'] ?>
                    </p>
                </div>
                <form class="d-flex position-relative mb-4" method="post" style="z-index: 2;">
                    <input type=" text" class="d-none" name="id" value="<?= $book["id"] ?>" />
                    <input type="text" class="d-none" name="cover" value="<?= $book["cover"] ?>" />
                    <input type="text" class="d-none" name="name" value="<?= $book["name"] ?>" />
                    <input type="text" class="d-none" name="author" value="<?= $book["author"] ?>" />
                    <input type="number" class="d-none" name="price" value="<?= $book["price"] ?>" />
                    <button type="submit" type="button" class="btn btn-outline-dark">
                        Add to cart
                    </button>
                </form>
                <? if (!$isFullOpen) : ?>
                    <p class="card-text mb-2"><small><?= $book['description'] ?></small></p>
                    <form method="post">
                        <input type=" text" class="d-none" name="isFullOpen" value="true" />
                        <button class="read-more" type="submit">Read more</button>
                    </form>
                <? else : ?>
                    <p class="card-text mb-2"><small><?= $book['content'] ?></small></p>
                    <form method="post">
                        <input type=" text" class="d-none" name="isFullOpen" value="false" />
                        <button class="read-more" type="submit">Hide description</button>
                    </form>
                <? endif ?>
                <h4 class="mt-5 mb-4">You also may like:</h4>
                <div class="d-flex flex-wrap g-4">
                    <? foreach ($recommendations as $rec) : ?>
                        <div class="card book-rec me-3 my-3">
                            <div class="h-75 img-wrapper d-flex justify-content-center">
                                <img src="<?= $rec['cover'] ?>" class="h-100" alt="cover">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $rec['name'] ?></h5>
                                <p class="card-text"><small><?= $rec['author'] ?></small></p>
                                <a href="book.php?id=<?= $rec["id"] ?>" class="stretched-link"></a>
                            </div>
                        </div>
                    <? endforeach ?>
                </div>
            </div>
        </div>
    </main>

    <?php require_once './tpl/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>