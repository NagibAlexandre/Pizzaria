<?php
session_start();
require_once '../../config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: /pages/account/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head><meta charset="UTF-8"><title>Cancelado</title></head>
<body><h1>Pagamento cancelado.</h1></body>
</html>
