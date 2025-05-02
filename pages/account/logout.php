<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

unset($_SESSION['usuario']);
unset($_SESSION['carrinho']);

$_SESSION = [];

session_regenerate_id(true);

session_destroy();

if (isset($_COOKIE['carrinho'])) {
    setcookie('carrinho', '', time() - 3600, '/');
}

header("Location: ../../index.php");
exit();
