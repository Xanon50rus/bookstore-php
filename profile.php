<?php
$page = 'profile';
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

$name = $surname = $email = "";
$image = $age = null;
$name_err = $surname_err = $image_err = $age_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = connectDb();

    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter a name.";
    } else {
        $name = trim($_POST["name"]);
    }

    if (empty(trim($_POST["surname"]))) {
        $surname_err = "Please enter a surname.";
    } else {
        $surname = trim($_POST["surname"]);
    }

    if (isset($_POST["age"]) && preg_match('#[^0-9]#', trim($_POST["age"]))) {
        $age_err = "Only numbers allowed.";
    } elseif (isset($_POST["age"]) && trim($_POST["age"]) < 5) {
        $age_err = "Invalid age.";
    } else {
        $age = trim($_POST["age"]);
    }

    if (isset($_POST["image"]) && !filter_var(trim($_POST["image"]), FILTER_VALIDATE_URL)) {
        $image_err = "Invalid url format.";
    } elseif (isset($_POST["image"])) {
        $image = filter_var(trim($_POST["image"]), FILTER_SANITIZE_URL);
    }

    // Check input errors before inserting in database
    if (empty($name_err) && empty($surname_err) && empty($age_err) && empty($image_err)) {

        $sql = $db->prepare("UPDATE users SET ime = ?, priimek = ?, starost = ?, slika = ? WHERE id = ?");

        if ($sql->execute([$name, $surname, $age, $image, $_SESSION["id"]])) {
            $_SESSION["image"] = $image;
            $profile_image = $image;
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    $db = null;
}

$isAdmin = false;

$db = connectDb();

$sql = $db->prepare("SELECT ime, priimek, email, starost, slika, role FROM users where id= ?");
$sql->execute([$_SESSION["id"]]);
$data = $sql->fetchAll(PDO::FETCH_ASSOC);
$data = (isset($data) && isset($data[0])) ? $data[0] : null;

$_SESSION["LogIn"] = true;
$_SESSION["email"] = $data['email'];
$_SESSION["image"] = $data['slika'];
$_SESSION["role"] = $data['role'];

$name = $data['ime'];
$surname = $data['priimek'];
$email = $data['email'];
$image = $data['slika'];
$age = $data['starost'];

$db = null;

?>
<!DOCTYPE html>
<html lang="en">

<?php require_once './tpl/head.php' ?>

<body>
    <?php require_once './tpl/header.php' ?>

    <main class="main d-flex">
        <div class="container d-flex justify-content-center py-5">
            <div class="col-12 profile">
                <form class="form shadow p-3 rounded mx-auto" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="profile_image d-flex align-items-center justify-content-center mb-3 mx-auto">
                        <? if ($image && $image != "") : ?>
                            <img src="<?php echo $image; ?>" alt="profile_image" />
                        <? else : ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="240" height="240" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                            </svg>
                        <? endif ?>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputName" class="form-label">Ime</label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>" id=" exampleInputName">
                        <span class="invalid-feedback"><?php echo $name_err; ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputSurname" class="form-label">Priimek</label>
                        <input type="text" name="surname" class="form-control <?php echo (!empty($surname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $surname; ?>" id="exampleInputSurname">
                        <span class="invalid-feedback"><?php echo $surname_err; ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputAge" class="form-label">Starost</label>
                        <input type="number" name="age" class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age; ?>" id="exampleInputAge">
                        <span class="invalid-feedback"><?php echo $age_err; ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputImage" class="form-label">Slika</label>
                        <input type="text" name="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $image; ?>" id="exampleInputImage">
                        <span class="invalid-feedback"><?php echo $image_err; ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" name="email" disabled readonly class="form-control" value="<?php echo $email; ?>" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Change</button>
                </form>
            </div>
        </div>
    </main>

    <?php require_once './tpl/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>