<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pizzaria</title>
  <link rel="stylesheet" href="/styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <a class="nav-link" href="#">Quem Somos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Login</a>
          </li>
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
      $pdo = new PDO('mysql:host=localhost;dbname=pizza', 'root', 'SNajainiBdCabuloso1!!');
      $pizzas = $pdo->query("SELECT * FROM pizzas LIMIT 8");

      foreach ($pizzas as $pizza) {
        echo '
    <div class="pizza-block">
      <img src="/images/' . $pizza['abreviacao'] . '.jpg" class="pizza-img" alt="' . $pizza['nome'] . '">
      <div class="pizza-name">' . $pizza['nome'] . '</div>
      <div class="pizza-description">' . $pizza['descricao'] . '</div>
      <button class="add-to-cart-btn">Adicionar ao Carrinho</button>
    </div>';
      }
      ?>
    </div>


  </div>

  <footer class="footer text-center mt-5">
    <p>&copy; 2025 Pizzaria. Todos os direitos reservados.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>