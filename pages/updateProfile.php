<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit();
}

$id = $_SESSION['usuario']['id'];
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$senha = $_POST['senha'] ?? '';
$confirmaSenha = $_POST['confirmaSenha'] ?? '';

if (!$nome || !$email || !$telefone || !$endereco) {
    echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
    exit();
}

if (!empty($senha)) {
    if ($senha !== $confirmaSenha) {
        echo json_encode(['success' => false, 'message' => 'As senhas não coincidem.']);
        exit();
    }
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, telefone = ?, endereco = ?, senha = ? WHERE id = ?");
    $success = $stmt->execute([$nome, $email, $telefone, $endereco, $senhaHash, $id]);
} else {
    $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, telefone = ?, endereco = ? WHERE id = ?");
    $success = $stmt->execute([$nome, $email, $telefone, $endereco, $id]);
}

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar no banco de dados.']);
}
