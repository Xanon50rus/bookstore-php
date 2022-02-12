<?php
session_start();

include('inc/config.php');
include('inc/function.php');

$_SESSION = array();

session_destroy();
redirect("login.php");
exit;
