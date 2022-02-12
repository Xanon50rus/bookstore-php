<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link <?= $page === "cms/users" ? "active" : "" ?>" aria-current="page" href="index.php">Users</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $page === "cms/categories" ? "active" : "" ?>" href="categories.php">Categories</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $page === "cms/books" ? "active" : "" ?>" href="books.php">Books</a>
    </li>
</ul>