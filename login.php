<?php
$page = 'login';

session_start();

include('inc/config.php');
include('inc/function.php');

checkLogin(true);

$email = $password = "";
$email_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = connectDb();

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter a email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($email_err) && empty($password_err)) {
        $sql = $db->prepare("SELECT id, email, password, slika, role FROM users WHERE email = ?");
        $sql->execute([$email]);
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        $data = (isset($data) && isset($data[0])) ? $data[0] : null;

        if (empty($data)) {
            $login_err = "Invalid email or password.";
        } else {
            $_SESSION["LogIn"] = true;
            $_SESSION["id"] = $data['id'];
            $_SESSION["email"] = $data['email'];
            $_SESSION["image"] = $data['slika'];
            $_SESSION["role"] = $data['role'];

            redirect("shop.php");
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
            <?php
            if (!empty($login_err)) {
                echo '<div class="alert alert-danger mb-4">' . $login_err . '</div>';
            }
            ?>
            <form class="form shadow p-3 rounded" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h3 class="mb-4 text-center">Login</h3>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" id="exampleInputEmail1" required aria-describedby="emailHelp">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required id="exampleInputPassword1">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Submit</button>
                <p class="mt-3 text-center">Don't have an account? <a href="signup.php">Sign up now</a>.</p>
            </form>
        </div>
    </main>

    <?php require_once './tpl/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>