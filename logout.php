<?php require('inc/common.php');

if(empty($_SESSION['user'])) { 
    header("Location: index.php"); 
    die("Redirecting to login.php");
}

// We remove the user's data from the session 
unset($_SESSION['user']);

// We redirect them to the login page 
header("Location: login.php"); 
die("Redirecting to: login.php");