<?php
$page = 'cms/books';
$profile_image = "";
$isEmptyCart = true;
$isLoggedIn = false;

include('../inc/config.php');
include('../inc/function.php');

session_start();

checkLogin();

if (isset($_SESSION) && isset($_SESSION['LogIn']) == true) {
    $profile_image = $_SESSION["image"];
    $isEmptyCart = empty($_SESSION["shopping_cart"]);
    $isLoggedIn = true;
}

if (isset($_SESSION) && isset($_SESSION['role']) == true && $_SESSION['role'] != "admin") {
    redirect('../profile.php');
}

// $name = $surname = $email = "";
// $image = $age = null;
// $name_err = $surname_err = $image_err = $age_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = connectDb();

    // Check input errors before inserting in database
    if ($_POST['action'] == 'change') {
        $sql = $db->prepare("UPDATE books SET name = ?, author = ?, description = ?, content = ?, cover = ?, price = ?, category_id = ? WHERE id = ?");

        if (!$sql->execute([$_POST['name'], $_POST['author'], $_POST['description'], $_POST['content'], $_POST['cover'], $_POST['price'], $_POST['category_id'], $_POST['id']])) {
            echo "Oops! Something went wrong. Please try again later.";
        }
    } elseif ($_POST['action'] == 'delete') {
        $sql = $db->prepare("DELETE FROM books WHERE id = ?");

        if (!$sql->execute([$_POST['id']])) {
            echo "Oops! Something went wrong. Please try again later.";
        }
    } elseif ($_POST['action'] == 'create') {
        $sql = $db->prepare("INSERT INTO books (name, author, description, content, cover, price, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if (!$sql->execute([$_POST['name'], $_POST['author'], $_POST['description'], $_POST['content'], $_POST['cover'], $_POST['price'], $_POST['category_id']])) {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    $db = null;
}

// $isAdmin = false;

$db = connectDb();

$sql = $db->prepare("SELECT * FROM books");
$sql->execute();
$books = $sql->fetchAll(PDO::FETCH_ASSOC);
$books = isset($books) ? $books : null;

$sql = $db->prepare("SELECT * FROM categories");
$sql->execute();
$categories = $sql->fetchAll(PDO::FETCH_ASSOC);
$categories = isset($categories) ? $categories : null;

$db = null;

?>
<!DOCTYPE html>
<html lang="en">

<?php require_once '../tpl/head.php' ?>

<body>
    <?php require_once '../tpl/header.php' ?>

    <main class="main d-flex">
        <div class="container d-flex flex-column py-5">
            <?php require_once '../tpl/tabs.php' ?>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Author</th>
                            <th scope="col">Description</th>
                            <th scope="col">Content</th>
                            <th scope="col">Cover</th>
                            <th scope="col">Price</th>
                            <th scope="col">CatID</th>
                            <th scope="col">
                                <button type="button" id="createEl" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <svg xmlns=" http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                        <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"></path>
                                    </svg>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <? foreach ($books as $book) : ?>
                            <tr>
                                <th scope="row"><?= $book["id"] ?></th>
                                <td id="name<?= $book['id'] ?>"><?= $book["name"] ?></td>
                                <td id="author<?= $book['id'] ?>"><?= $book["author"] ?></td>
                                <td id="description<?= $book['id'] ?>"><?= $book["description"] ?></td>
                                <td id="content<?= $book['id'] ?>"><?= $book["content"] ?></td>
                                <td id="cover<?= $book['id'] ?>" data-cover-link="<?= $book["cover"] ?>">
                                    <img src="<?= $book["cover"] ?>" class="img-fluid" />
                                </td>
                                <td id="price<?= $book['id'] ?>"><?= $book["price"] ?></td>
                                <td id="category_id<?= $book['id'] ?>"><?= $book["category_id"] ?></td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" data-id="<?= $book['id'] ?>" class="btn btn-outline-success book-modal" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <svg xmlns=" http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"></path>
                                            </svg>
                                        </button>
                                        <form method="post" class="d-flex justify-content-center ms-2">
                                            <input class="d-none" name="action" value="delete" type="text">
                                            <input class="d-none" name="id" value="<?= $book["id"] ?>" type="number">
                                            <button type="submit" class="btn btn-outline-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <? endforeach ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form class="modal-content" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Book Settings</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <input class="d-none" type="text" name="action" id="bookAction" value="change" />
                        <input class="d-none" type="number" name="id" id="bookID" />
                        <div class="modal-body">
                            <label for="bookName" class="form-label">Name</label>
                            <input class="form-control" type="text" id="bookName" name="name" />
                            <label for="bookAuthor" class="form-label">Author</label>
                            <input class="form-control" type="text" id="bookAuthor" name="author" />
                            <label for="bookDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="bookDescription" rows="3" name="description"></textarea>
                            <label for="bookContent" class="form-label">Content</label>
                            <textarea class="form-control" id="bookContent" rows="3" name="content"></textarea>
                            <label for="bookCover" class="form-label">Cover</label>
                            <input class="form-control" type="text" id="bookCover" name="cover" />
                            <label for="bookPrice" class="form-label">Price</label>
                            <input class="form-control" type="number" id="bookPrice" name="price" />
                            <label for="bookCatID" class="form-label">Category</label>
                            <select class="form-select" id="bookCatID" name="category_id">
                                <? foreach ($categories as $category) : ?>
                                    <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                                <? endforeach ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <?php require_once '../tpl/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        window.addEventListener('load', function() {
            const createEl = document.getElementById("createEl");
            createEl.addEventListener('click', function(e) {
                document.getElementById("bookAction").value = "create";
            });

            const btns = document.querySelectorAll('.book-modal');
            btns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const id = e.currentTarget.getAttribute('data-id');
                    const name = document.getElementById("name" + id).innerHTML;
                    const author = document.getElementById("author" + id).innerHTML;
                    const description = document.getElementById("description" + id).innerHTML;
                    const content = document.getElementById("content" + id).innerHTML;
                    const cover = document.getElementById("cover" + id).getAttribute('data-cover-link');
                    const price = document.getElementById("price" + id).innerHTML;
                    const category_id = document.getElementById("category_id" + id).innerHTML;

                    document.getElementById("bookID").value = id;
                    document.getElementById("bookName").value = name;
                    document.getElementById("bookAuthor").value = author;
                    document.getElementById("bookDescription").value = description;
                    document.getElementById("bookContent").value = content;
                    document.getElementById("bookCover").value = cover;
                    document.getElementById("bookPrice").value = price;
                    document.getElementById("bookCatID").value = category_id;
                });
            });
        }, false);
    </script>
</body>

</html>