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

    echo "<script>
      sessionStorage.setItem('usuarioLogado', 'true');
      window.location.href = '/index.php';
    </script>";
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
  <script src="/scripts/carrinho.js"></script>

  <style>
    body,
    html {
      height: 100%;
      margin: 0;
      overflow: hidden;
    }

    .login-wrapper {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .card {
      max-height: 90vh;
      overflow-y: auto;
    }
  </style>

</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="/index.php">
        <img src="logo.png" alt="Logo da Pizzaria" style="width: 100px;">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="#" id="toggleTheme" title="Alternar tema">
              <i id="themeIcon" class="bi bi-sun-fill"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/index.php">Início</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/pages/cardapio.php">Cardápio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/pages/quemSomos.php">Quem Somos</a>
          </li>

          <?php if (isset($_SESSION['usuario'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="/pages/account/logout.php">Sair</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="/pages/account/login.php">Login</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="login-wrapper">
    <div class="card p-4 shadow-lg rounded-4" style="max-width: 400px; width: 100%;">
      <h3 class="text-center mb-4">Login</h3>

      <?php if (isset($erro)) echo "<div class='alert alert-danger text-center'>$erro</div>"; ?>

      <form method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" class="form-control" id="email" placeholder="Digite seu email" required>
        </div>

        <div class="mb-3">
          <label for="senha" class="form-label">Senha</label>
          <input type="password" name="senha" class="form-control" id="senha" placeholder="Digite sua senha" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 ">Entrar</button>
      </form>

      <div class="text-center mt-3">
        <a href="cadastro.php">Não tem cadastro? <strong>Crie agora</strong></a>
      </div>
    </div>
  </div>


  <div class="modal fade" id="modalTamanho" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Escolher Tamanho</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <p id="nomePizzaModal"></p>
          <select id="tamanhoSelecionado" class="form-select">
            <option value="brotinho" data-preco="25">Brotinho - R$25</option>
            <option value="media" data-preco="50">Média - R$50</option>
            <option value="familia" data-preco="75">Família - R$75</option>
          </select>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" onclick="adicionarCarrinho()">Adicionar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalCarrinho" tabindex="-1" aria-labelledby="carrinhoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="carrinhoLabel">Seu Carrinho</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <ul id="listaCarrinho" class="list-group mb-3"></ul>
          <h5>Total: R$ <span id="totalCarrinho">0.00</span></h5>
        </div>
        <div class="modal-footer">
          <button id="finalizarCompraBtn" class="btn btn-primary">Finalizar Pedido</button>
        </div>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/scripts/togglemode.js"></script>
</body>

</html>