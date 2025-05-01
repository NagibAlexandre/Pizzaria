<?php
session_start();

if (isset($_SESSION['usuario'])) {
  header("Location: ../../index.php");
  exit();
}

require_once __DIR__ . '/../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
  $stmt->execute([$email]);
  $usuario = $stmt->fetch();

  if ($usuario && password_verify($senha, $usuario['senha'])) {
    $_SESSION['usuario'] = $usuario;
    header("Location: ../../index.php");
    exit();
  } else {
    $erro = "Email ou senha incorretos.";
  }
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

  <!-- Navbar -->
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
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>
    <button type="submit">Entrar</button>
  </form>

  <a href="cadastro.php">Não tem cadastro?</a>

  <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>