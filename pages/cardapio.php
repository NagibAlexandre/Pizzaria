<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio - Pizzaria</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <img src="logo.png" alt="Logo da Pizzaria" style="width: 100px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../index.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cardápio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="quemSomos.php">Quem Somos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login/login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="menu-image">
        <h1>Nosso Cardápio</h1>
    </div>

    <div class="pizza-grid-wrapper">
        <div class="container">
            <div class="row">
                <?php
                $pdo = new PDO('mysql:host=localhost;dbname=pizza', 'root', 'SNajainiBdCabuloso1!!');
                $pizzas = $pdo->query("SELECT * FROM pizzas");

                foreach ($pizzas as $pizza) {
                    echo '
          <div class="col-md-3 mb-4">
            <div class="pizza-block">
              <img src="/images/' . $pizza['abreviacao'] . '.jpg" class="pizza-img img-fluid" alt="' . $pizza['nome'] . '">
              <div class="pizza-name">' . $pizza['nome'] . '</div>
              <div class="pizza-description">' . $pizza['descricao'] . '</div>
              <button class="add-to-cart-btn">Adicionar ao Carrinho</button>
            </div>
          </div>';
                }
                ?>
            </div>
        </div>
    </div>

    <footer class="footer text-center mt-5">
        <p>&copy; 2025 Pizzaria. Todos os direitos reservados. Puc Minas Coração Eucarísitico</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>