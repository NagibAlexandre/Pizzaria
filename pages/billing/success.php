<?php

session_start();
require_once '../../config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: /pages/account/login.php");
    exit();
}

require_once '../../vendor/autoload.php';
require_once '../../config.php';

\Stripe\Stripe::setApiKey('sk_test_51RKOuIIetkxqAOVDJe63jaQoXUEKIGGrxWIkpd7lMawpbq5hmQekVk2wfHX0adoI0LqSzIx4cG2pHNwkKhYFw2R600LfHisOee');

$session_id = $_GET['session_id'] ?? '';

if (!$session_id) {
    die('Erro: Sessão do Stripe não encontrada.');
}

$session = \Stripe\Checkout\Session::retrieve($session_id);
$produtos = json_decode($session->metadata->produtos);
$endereco = $session->metadata->endereco;

$total = 0;
foreach ($produtos as $produto) {
    $total += $produto->preco;
}

$sql = "INSERT INTO recibos (produtos_comprados, id_usuario, endereco_entrega, valor, forma_pagamento) 
        VALUES (?, ?, ?, ?, 'cartão')";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    json_encode($produtos, JSON_UNESCAPED_UNICODE),
    $_SESSION['usuario']['id'],
    $endereco,
    $total
]);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Compra Aprovada</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .mensagem {
      margin-top: 20%;
      padding: 40px;
      background-color: #d4edda;
      border: 1px solid #c3e6cb;
      border-radius: 10px;
      color: #155724;
    }
  </style>
  <script>
    setTimeout(() => {
      window.location.href = 'acompanhar.php';
    }, 5000);
  </script>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="text-center mensagem shadow">
      <h2 class="mb-3">Compra aprovada com sucesso!</h2>
      <p>Você será redirecionado para o acompanhamento do pedido em instantes...</p>
      <div class="spinner-border text-success mt-3" role="status">
        <span class="visually-hidden">Carregando...</span>
      </div>
    </div>
  </div>
</body>
</html>
