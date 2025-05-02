<?php
session_start();

if (isset($_SESSION['usuario'])) {
  header("Location: ../../index.php");
  exit();
}

require_once __DIR__ . '/../../config.php';

$erro = "";
$sucesso = "";
$nome = $email = $telefone = $endereco = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $telefone = $_POST['telefone'];
  $endereco = $_POST['endereco'];
  $senha = $_POST['senha'];
  $confirmar_senha = $_POST['confirmar_senha'];

  // Verificar se as senhas coincidem
  if ($senha !== $confirmar_senha) {
    $erro = "As senhas não coincidem.";
  } else {
    // Verificar se o email já está cadastrado
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuarioExistente = $stmt->fetch();

    if ($usuarioExistente) {
      $erro = "Este email já está cadastrado.";
    } else {
      // Se as senhas coincidem e o email não existe, então salva o usuário
      $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
      try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, telefone, endereco) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$nome, $email, $senha_hash, $telefone, $endereco])) {
          $sucesso = "Cadastro realizado com sucesso!";
          // Limpar os campos após sucesso
          $nome = $email = $telefone = $endereco = "";
        } else {
          $erro = "Erro ao cadastrar. Tente novamente.";
        }
      } catch (PDOException $e) {
        $erro = "Erro ao cadastrar: " . $e->getMessage();
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cadastro - Pizzaria</title>
  <link rel="stylesheet" href="/styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body,
    html {
      height: 100%;
      margin: 0;
      overflow: hidden;
    }

    .form-wrapper {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f8f9fa;
    }

    .card {
      width: 100%;
      max-width: 400px;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .container-form {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
  </style>
</head>

<body>
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
          <li class="nav-item">
            <a class="nav-link position-relative <?php echo !isset($_SESSION['usuario']) ? 'disabled' : ''; ?>" href="#" onclick="<?php echo isset($_SESSION['usuario']) ? 'abrirCarrinho()' : ''; ?>">
              <i class="bi bi-cart"></i> Carrinho
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="contadorCarrinho">0</span>
            </a>
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

  <div class="container-form">
    <div class="card">
      <h4 class="mb-4 text-center">Cadastro</h4>

      <?php if ($erro) echo "<div class='alert alert-danger'>$erro</div>"; ?>
      <?php if ($sucesso) echo "<div class='alert alert-success'>$sucesso</div>"; ?>

      <form method="POST">
        <div class="mb-3">
          <label for="nome" class="form-label">Nome</label>
          <input class="form-control" type="text" name="nome" id="nome" placeholder="Nome" value="<?= htmlspecialchars($nome) ?>" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input class="form-control" type="email" name="email" id="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
        </div>

        <div class="mb-3">
          <label for="telefone" class="form-label">Telefone</label>
          <input class="form-control" type="text" name="telefone" id="telefone" placeholder="Telefone" value="<?= htmlspecialchars($telefone) ?>" required>
        </div>

        <div class="mb-3">
          <label for="endereco" class="form-label">Endereço</label>
          <input class="form-control" type="text" name="endereco" id="endereco" placeholder="Endereço" value="<?= htmlspecialchars($endereco) ?>" required>
        </div>

        <div class="mb-3">
          <label for="senha" class="form-label">Senha</label>
          <input class="form-control" type="password" name="senha" id="senha" placeholder="Senha" required>
        </div>

        <div class="mb-3">
          <label for="confirmar_senha" class="form-label">Confirmar Senha</label>
          <input class="form-control" type="password" name="confirmar_senha" id="confirmar_senha" placeholder="Confirmar Senha" required>
        </div>

        <button class="btn btn-primary w-100" type="submit">Cadastrar</button>
      </form>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>