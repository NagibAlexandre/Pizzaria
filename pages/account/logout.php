<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /index.php");
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
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Pizzaria</title>
</head>
<body>
    <script>
        localStorage.removeItem('carrinho');
        window.location.href = '/index.php';
    </script>
</body>
</html>
