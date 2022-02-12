<?php
$page = 'shop';
$catID = "";
$authorTitle = "";
$searchValue = "";
$profile_image = "";
$isEmptyCart = true;
$isLoggedIn = false;

session_start();

if (isset($_SESSION) && isset($_SESSION['LogIn']) == true) {
    $profile_image = $_SESSION["image"];
    $isEmptyCart = empty($_SESSION["shopping_cart"]);
    $isLoggedIn = true;
}

if (isset($_GET['catID'])) {
    $catID = $_GET['catID'];
}

if (isset($_GET['authorTitle'])) {
    $authorTitle = $_GET['authorTitle'];
}

if (isset($_POST['searchValue'])) {
    $searchValue = $_POST['searchValue'];
}



include('inc/config.php');
include('inc/function.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

$sql = $db->prepare("SELECT * FROM categories");
$sql->execute();
$categories = $sql->fetchAll(PDO::FETCH_ASSOC);
$categories = isset($categories) ? $categories : [];

$sqlQuery = "SELECT * FROM books";
$sqlParams = array();

if ($catID != "" && $authorTitle != "" && $searchValue != "") {
    $sqlQuery = $sqlQuery . " WHERE category_id = ? AND author = ? AND name LIKE ?";
    $sqlParams = array($catID, $authorTitle, '%' . $searchValue . '%');
} elseif ($catID != "" && $authorTitle != "") {
    $sqlQuery = $sqlQuery . " WHERE category_id = ? AND author = ?";
    $sqlParams = array($catID, $authorTitle);
} elseif ($catID != "" && $searchValue != "") {
    $sqlQuery = $sqlQuery . " WHERE category_id = ? AND name LIKE ?";
    $sqlParams = array($catID, '%' . $searchValue . '%');
} elseif ($authorTitle != "" && $searchValue != "") {
    $sqlQuery = $sqlQuery . " WHERE author = ? AND name LIKE ?";
    $sqlParams = array($authorTitle, '%' . $searchValue . '%');
} elseif ($catID != "") {
    $sqlQuery = $sqlQuery . " WHERE category_id = ?";
    $sqlParams = array($catID);
} elseif ($authorTitle != "") {
    $sqlQuery = $sqlQuery . " WHERE author = ?";
    $sqlParams = array($authorTitle);
} elseif ($searchValue != "") {
    $sqlQuery = $sqlQuery . " WHERE name LIKE ?";
    $sqlParams = array('%' . $searchValue . '%');
}

$sql = $db->prepare($sqlQuery);
$sql->execute($sqlParams);
$books = $sql->fetchAll(PDO::FETCH_ASSOC);
$books = isset($books) ? $books : [];

$db = null;

?>
<!DOCTYPE html>
<html lang="en">

<?php require_once './tpl/head.php' ?>

<body>
    <?php require_once './tpl/header.php' ?>

    <main class="main d-flex">
        <div class="container d-flex flex-column flex-md-row justify-content-center py-5">
            <div class="col-12 col-md-3 mb-4">
                <form class="d-flex" method="post">
                    <input type="text" name="searchValue" value="<?= $searchValue ?>" class="form-control" id="search" placeholder="Write Book Title">
                    <button type="submit" type="button" class="btn btn-primary ms-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"></path>
                        </svg>
                    </button>
                    <? if ($catID != "" || $authorTitle != "" || $searchValue != "") : ?>
                        <a type="button" class="btn btn-danger ms-2" href="shop.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"></path>
                            </svg>
                        </a>
                    <? endif ?>
                </form>
                <div class="d-flex flex-column">
                    <h3 class="mt-4 mb-3">Categories</h3>
                    <ul class="list-group">
                        <? foreach ($categories as $cat) : ?>
                            <a class="list-group-item <?= $cat['id'] == $catID ? "active" : "" ?>" aria-current=<?= $cat['id'] == $catID ? "true" : "false" ?> href="shop.php<?= $cat['id'] == $catID ? "" : "?catID=" . $cat['id'] ?>">
                                <?= $cat['title'] ?>
                            </a>
                        <? endforeach ?>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-md-9 ms-3">
                <div class="row">
                    <? foreach ($books as $book) : ?>
                        <div class="card mb-4 mx-3 p-0" style="max-width: 440px;width:86%">
                            <div class="row g-0 h-100">
                                <div class="col-md-4 d-flex align-items-center justify-content-center">
                                    <img src="<?= $book['cover'] ?>" class="img-fluid rounded-start" alt="cover">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <p class="card-text">Author: <?= $book['author'] ?></p>
                                        <h5 class="card-title"><?= $book['name'] ?></h5>
                                        <p class="card-text"><small><?= mb_strimwidth($book['description'], 0, 50, "...") ?></small></p>
                                        <p class="card-text price d-flex align-items-center link-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-currency-euro" viewBox="0 0 16 16">
                                                <path d="M4 9.42h1.063C5.4 12.323 7.317 14 10.34 14c.622 0 1.167-.068 1.659-.185v-1.3c-.484.119-1.045.17-1.659.17-2.1 0-3.455-1.198-3.775-3.264h4.017v-.928H6.497v-.936c0-.11 0-.219.008-.329h4.078v-.927H6.618c.388-1.898 1.719-2.985 3.723-2.985.614 0 1.175.05 1.659.177V2.194A6.617 6.617 0 0 0 10.341 2c-2.928 0-4.82 1.569-5.244 4.3H4v.928h1.01v1.265H4v.928z" />
                                            </svg>
                                            <?= $book['price'] ?>
                                        </p>
                                        <form class="d-flex position-relative" method="post" style="z-index: 2;">
                                            <input type=" text" class="d-none" name="id" value="<?= $book["id"] ?>" />
                                            <input type="text" class="d-none" name="cover" value="<?= $book["cover"] ?>" />
                                            <input type="text" class="d-none" name="name" value="<?= $book["name"] ?>" />
                                            <input type="text" class="d-none" name="author" value="<?= $book["author"] ?>" />
                                            <input type="number" class="d-none" name="price" value="<?= $book["price"] ?>" />
                                            <button type="submit" type="button" class="btn btn-outline-dark">
                                                Add to cart
                                            </button>
                                        </form>
                                        <a href="book.php?id=<?= $book["id"] ?>" class="stretched-link"></a>
                                    </div>
                                </div>
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