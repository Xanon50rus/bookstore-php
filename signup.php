<?php
$page = 'signup';

session_start();

include('inc/config.php');
include('inc/function.php');

$name = $surname = $email = $password = $confirm_password = "";
$name_err = $surname_err = $email_err = $password_err = $confirm_password_err = "";

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

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter a email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $sql = $db->prepare("SELECT id FROM users WHERE email = ?");
        $sql->execute([$email]);
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        $data = (isset($data) && isset($data[0])) ? $data[0] : null;

        if (empty($data)) {
            $email = trim($_POST["email"]);
        } else {
            $email_err = "This email is already taken.";
        }
    }

    // if (empty(trim($_POST["age"]))) {
    //     $age_err = "Please enter a age.";
    // } elseif (preg_match('#[^0-9]#', trim($_POST["age"]))) {
    //     $age_err = "Only numbers allowed.";
    // } elseif (trim($_POST["age"]) < 5) {
    //     $age_err = "Invalid age.";
    // } else {
    //     $age = trim($_POST["age"]);
    // }

    // if (empty(trim($_POST["image"]))) {
    //     $image_err = "Please enter a image.";
    // } elseif (!filter_var(trim($_POST["image"]), FILTER_VALIDATE_URL)) {
    //     $image_err = "Invalid url format.";
    // } else {
    //     $image = filter_var(trim($_POST["image"]), FILTER_SANITIZE_URL);
    // }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if (empty($name_err) && empty($surname_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

        $sql = $db->prepare("INSERT INTO users (ime, priimek, email, password) VALUES (?, ?, ?, ?)");

        if ($sql->execute([$name, $surname, $email, password_hash($password, PASSWORD_DEFAULT)])) {
            redirect("login.php");
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    $db = null;
}
?>
<!DOCTYPE html>
<html lang="en">

<?php require_once './tpl/head.php' ?>

<body>
    <?php require_once './tpl/header.php' ?>

    <main class="main d-flex py-5">
        <div class="container d-flex align-items-center flex-column justify-content-center">
            <form class="form shadow p-3 rounded" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h3 class="mb-4 text-center">Join our shop!</h3>
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
                <!-- <div class="mb-3">
                    <label for="exampleInputAge" class="form-label">Starost</label>
                    <input type="number" name="age" class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age; ?>" id="exampleInputAge">
                    <span class="invalid-feedback"><?php echo $age_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="exampleInputImage" class="form-label">Slika</label>
                    <input type="text" name="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $image; ?>" id="exampleInputImage">
                    <span class="invalid-feedback"><?php echo $image_err; ?></span>
                </div> -->
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" id="exampleInputEmail1" aria-describedby="emailHelp">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" id="exampleInputPassword1">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword2" class="form-label">Confirm password</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" id="exampleInputPassword2">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Submit</button>
            </form>
        </div>
    </main>

    <?php require_once './tpl/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>