<?php

session_start();
require_once '../../config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: /pages/account/login.php");
    exit();
}

require_once '/vendor/autoload.php';
require_once '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acesso inválido.');
}

$produtos = json_decode($_POST['produtos'] ?? '[]');
$endereco = $_POST['endereco'] ?? '';
$total = floatval($_POST['total'] ?? 0);

if (empty($produtos) || empty($endereco) || $total <= 0) {
    die('Dados incompletos.');
}

$sql = "INSERT INTO recibos (produtos_comprados, id_usuario, endereco_entrega, valor, forma_pagamento) 
        VALUES (?, ?, ?, ?, 'dinheiro')";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    json_encode($produtos, JSON_UNESCAPED_UNICODE),
    1, // ajuste com o id real do usuário, se tiver login
    $endereco,
    $total
]);

echo "Pedido em dinheiro registrado com sucesso!";
?>
