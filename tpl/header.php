<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">EBooks</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100">
                    <li class="nav-item mx-auto">
                        <a class="nav-link <?= $page === "shop" ? "active" : "" ?>" aria-current="page" href="<?= substr($page, 0, 3) === "cms" ? "../" : "" ?>shop.php">Shop</a>
                    </li>
                    <? if ($isLoggedIn == false) : ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $page === "login" ? "active" : "" ?>" href="<?= substr($page, 0, 3) === "cms" ? "../" : "" ?>login.php">Sign in</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $page === "singup" ? "active" : "" ?>" href="<?= substr($page, 0, 3) === "cms" ? "../" : "" ?>signup.php">Sign up</a>
                        </li>
                    <? else : ?>
                        <li class="nav-item d-none d-lg-flex">
                            <a class="nav-link d-flex align-items-center position-relative me-3" href="<?= substr($page, 0, 3) === "cms" ? "../" : "" ?>checkout.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16">
                                    <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z" />
                                </svg>
                                <? if (!$isEmptyCart) : ?>
                                    <div class="cart-notification"></div>
                                <? endif ?>
                            </a>
                        </li>
                        <div class="dropdown  d-none d-lg-flex">
                            <button class="btn p-0 header__profile-image d-flex align-items-center justify-content-center" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <? if ($profile_image == "") : ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                    </svg>
                                <? else : ?>
                                    <img class="rounded-circle" style="height: 100%" src="<?= $profile_image ?>" />
                                <? endif; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="<?= substr($page, 0, 3) === "cms" ? "../" : "" ?>profile.php">Profile</a></li>
                                <? if ($_SESSION["role"] == "admin") : ?>
                                    <li><a class="dropdown-item" href="<?= substr($page, 0, 3) === "cms" ? "" : "cms/" ?>index.php">CMS</a></li>
                                <? endif ?>
                                <li><a class="dropdown-item" href="<?= substr($page, 0, 3) === "cms" ? "../" : "" ?>logout.php">Logout</a></li>
                            </ul>
                        </div>
                        <div class="d-flex d-lg-none mx-auto mt-2">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center position-relative me-3" href="<?= substr($page, 0, 3) === "cms" ? "../" : "" ?>checkout.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16">
                                        <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z" />
                                    </svg>
                                    <? if (!$isEmptyCart) : ?>
                                        <div class="cart-notification" style="right: 0"></div>
                                    <? endif ?>
                                </a>
                            </li>
                            <div class="dropdown">
                                <button class="btn p-0 header__profile-image d-flex align-items-center justify-content-center" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <? if ($profile_image == "") : ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                        </svg>
                                    <? else : ?>
                                        <img class="rounded-circle" style="height: 100%" src="<?= $profile_image ?>" />
                                    <? endif; ?>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="<?= substr($page, 0, 3) === "cms" ? "../" : "" ?>profile.php">Profile</a></li>
                                    <? if ($_SESSION["role"] == "admin") : ?>
                                        <li><a class="dropdown-item" href="<?= substr($page, 0, 3) === "cms" ? "" : "cms/" ?>index.php">CMS</a></li>
                                    <? endif ?>
                                    <li><a class="dropdown-item" href="<?= substr($page, 0, 3) === "cms" ? "../" : "" ?>logout.php">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    <? endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>