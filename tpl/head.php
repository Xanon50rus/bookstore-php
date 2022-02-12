<?php
$pageTitle = "BookStore";

switch ($page) {
    case 'shop':
        $pageTitle = $pageTitle . " | Shop";
        break;
    case 'success':
        $pageTitle = $pageTitle . " | Success";
        break;
    case 'profile':
        $pageTitle = $pageTitle . " | Profile";
        break;
    case 'signup':
        $pageTitle = $pageTitle . " | Signup";
        break;
    case 'login':
        $pageTitle = $pageTitle . " | Login";
        break;
    case 'checkout':
        $pageTitle = $pageTitle . " | Checkout";
        break;
    case 'book':
        $pageTitle = $pageTitle . " | Book";
        break;
    case 'cms/users':
    case 'cms/categories':
    case 'cms/books':
        $pageTitle = $pageTitle . " | CMS";
        break;
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="<?= substr($page, 0, 3) === "cms" ? "../" : "" ?>index.css" rel="stylesheet">
</head>