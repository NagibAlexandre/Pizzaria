<?php

if (isset($_SESSION['usuario'])) {
  header("../../index.php");
  exit();
}

require_once __DIR__ . '/../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
  $telefone = $_POST['telefone'];
  $endereco = $_POST['endereco'];

  $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, telefone, endereco) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$nome, $email, $senha, $telefone, $endereco]);

  echo "Cadastro realizado com sucesso!";
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quem Somos - Pizzaria</title>
  <link rel="stylesheet" href="/styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <?php session_start(); ?>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="../../index.php">
        <img src="logo.png" alt="Logo da Pizzaria" style="width: 100px;">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../../index.php">Início</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../cardapio.php">Cardápio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../quemSomos.php">Quem Somos</a>
          </li>
          <?php if (isset($_SESSION['usuario'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Sair</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>


  <form method="POST">
    <input type="text" name="nome" placeholder="Nome" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>
    <input type="text" name="telefone" placeholder="Telefone" required><br>
    <input type="text" name="endereco" placeholder="Endereço" required><br>
    <button type="submit">Cadastrar</button>
  </form>