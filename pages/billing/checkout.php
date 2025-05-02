<?php
require_once '../../vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51RKOuIIetkxqAOVDJe63jaQoXUEKIGGrxWIkpd7lMawpbq5hmQekVk2wfHX0adoI0LqSzIx4cG2pHNwkKhYFw2R600LfHisOee');

$produtos = isset($_POST['produtos']) ? json_decode($_POST['produtos'], true) : [];
$endereco = $_POST['endereco'] ?? '';
$forma_pagamento = $_POST['forma_pagamento'] ?? 'cartao';

if (empty($produtos)) {
    die('Carrinho vazio.');
}

$total = 0;
foreach ($produtos as $produto) {
    $total += $produto['preco'];
}

$taxa_entrega = 990;
$total_com_taxa = $total + $taxa_entrega;

if ($forma_pagamento === 'dinheiro') {
    header("Location: pagamento_dinheiro.php?produto=" . urlencode($produtos[0]['nome']) . "&endereco=" . urlencode($endereco));
    exit;
}

$host = $_SERVER['HTTP_HOST'];
$path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

$success_url = "http://$host$path/success.php?session_id={CHECKOUT_SESSION_ID}";
$cancel_url = "http://$host$path/cancel.php";

$session = \Stripe\Checkout\Session::create([
    'line_items' => array_merge(
        array_map(function ($produto) {
            return [
                'price_data' => [
                    'currency' => 'brl',
                    'product_data' => [
                        'name' => $produto['nome'] . ' (' . $produto['tamanho'] . ')',
                    ],
                    'unit_amount' => $produto['preco'] * 100,
                ],
                'quantity' => 1,
            ];
        }, $produtos),
        [[
            'price_data' => [
                'currency' => 'brl',
                'product_data' => [
                    'name' => 'Taxa de Entrega',
                ],
                'unit_amount' => $taxa_entrega,
            ],
            'quantity' => 1,
        ]],
    ),
    'mode' => 'payment',
    'success_url' => $success_url,
    'cancel_url' => $cancel_url,
    'metadata' => [
        'produtos' => json_encode($produtos),
        'endereco' => $endereco,
    ],
]);

header("Location: " . $session->url);
exit;
?>
