<?php
session_start(); // Coloque isso bem no início do arquivo!
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pizzaria</title>
  <link rel="stylesheet" href="/styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="/scripts/carrinho.js"></script>
  <script>
    const usuarioLogado = <?php echo isset($_SESSION['usuario']) ? 'true' : 'false'; ?>;
  </script>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="logo.png" alt="Logo da Pizzaria" style="width: 100px;">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Início</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/pages/cardapio.php">Cardápio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/pages/quemSomos.php">Quem Somos</a>
          </li>

          <li class="nav-item">
            <a class="nav-link position-relative <?php echo !isset($_SESSION['usuario']) ? 'disabled' : ''; ?>" href="#" onclick="<?php echo isset($_SESSION['usuario']) ? 'abrirCarrinho()' : ''; ?>">
              <i class="bi bi-cart"></i> Carrinho
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="contadorCarrinho">0</span>
            </a>
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

  <div class="carousel-wrapper">
    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="/images/teste.jpg" class="d-block w-100 img-fluid" alt="Imagem 1">
        </div>
        <div class="carousel-item">
          <img src="/images/teste.jpg" class="d-block w-100 img-fluid" alt="Imagem 2">
        </div>
        <div class="carousel-item">
          <img src="/images/teste.jpg" class="d-block w-100 img-fluid" alt="Imagem 3">
        </div>
        <div class="carousel-item">
          <img src="/images/teste.jpg" class="d-block w-100 img-fluid" alt="Imagem 4">
        </div>
        <div class="carousel-item">
          <img src="/images/teste.jpg" class="d-block w-100 img-fluid" alt="Imagem 5">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
        data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
        data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Próximo</span>
      </button>
    </div>
  </div>

  <div class="pizza-grid-wrapper">
    <div class="pizza-grid">
      <?php
      require_once __DIR__ . '/config.php';

      $pizzas = $pdo->query("SELECT * FROM pizzas LIMIT 8");

      foreach ($pizzas as $pizza) {
        echo '
    <div class="pizza-block">
      <img src="/images/' . $pizza['abreviacao'] . '.jpg" class="pizza-img" alt="' . $pizza['nome'] . '">
      <div class="pizza-name">' . $pizza['nome'] . '</div>
      <div class="pizza-description">' . $pizza['descricao'] . '</div>
      <button class="btn btn-primary mt-2" onclick="abrirModal(' . htmlspecialchars(json_encode($pizza), ENT_QUOTES, 'UTF-8') . ')">Adicionar ao Carrinho</button>
    </div>';
      }
      ?>

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
          <img id="imagemTamanhoPizza" src="/images/tamanhos/brotinho.jpg" class="img-fluid mb-3" alt="Tamanho da pizza">
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
          <button id="finalizarCompraBtnIndex" class="btn btn-primary">Finalizar Pedido</button>
        </div>
      </div>
    </div>
  </div>

  <footer class="footer text-center mt-5">
    <p>&copy; 2025 Pizzaria. Todos os direitos reservados. Puc Minas Coração Eucarísitico</p>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>