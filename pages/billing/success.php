<?php
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
    json_encode($produtos),
    1,
    $endereco,
    $total
]);

echo "Pedido finalizado com sucesso!";
?>
