<?php
    session_start();
    session_unset();
    $_SESSION = array();
    session_destroy();
	setcookie("user", "", time() - 3600);
    unset($_COOKIE['user']);
    header("Location: index.php");
?>