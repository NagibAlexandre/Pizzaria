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
</head>

<body>
    <?php session_start(); ?>
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
                        <a class="nav-link" href="cardapio.php">Cardápio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="quemSomos.php">Quem Somos</a>
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

    <div class="container my-5">
        <h2 class="text-center mb-5">Quem Somos</h2>
        <div class="row justify-content-center g-5">

            <div class="col-md-5">
                <div class="card shadow-lg">
                    <img src="quemsomos/teste.jpg" class="card-img-top" alt="membro 1">
                    <div class="card-body text-center">
                        <h5 class="card-title">João Vítor de Freitas Scarlatelli</h5>
                        <img src="quemsomos/teste.jpg" class="rounded-circle my-3" style="width: 120px; height: 120px; object-fit: cover;" alt="joao">
                        <p class="card-text">Fundador da pizzaria, apaixonado por massas e responsável pelas receitas tradicionais da casa.</p>
                        <div class="social-icons mt-4">
                            <a href="https://instagram.com/seu_usuario" target="_blank" class="text-dark me-3 text-decoration-none">
                                <i class="bi bi-instagram" style="font-size: 1.5rem;"></i>
                            </a>
                            <a href="https://github.com/seu_usuario" target="_blank" class="text-dark me-3 text-decoration-none">
                                <i class="bi bi-github" style="font-size: 1.5rem;"></i>
                            </a>
                            <a href="https://linkedin.com/in/seu_usuario" target="_blank" class="text-dark me-3 text-decoration-none">
                                <i class="bi bi-linkedin" style="font-size: 1.5rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-lg">
                    <img src="quemsomos/teste.jpg" class="card-img-top" alt="membro 2">
                    <div class="card-body text-center">
                        <h5 class="card-title">Nagib Alexandre Verly Borjaili</h5>
                        <img src="quemsomos/teste.jpg" class="rounded-circle my-3" style="width: 120px; height: 120px; object-fit: cover;" alt="nagib">
                        <p class="card-text">Chef de cozinha e responsável pelas criações exclusivas e sabores inovadores do nosso cardápio.</p>
                        <div class="social-icons mt-4">
                            <a href="https://instagram.com/seu_usuario" target="_blank" class="text-dark me-3 text-decoration-none me-3">
                                <i class="bi bi-instagram" style="font-size: 1.5rem;"></i>
                            </a>
                            <a href="https://github.com/seu_usuario" target="_blank" class="text-dark me-3 text-decoration-none me-3">
                                <i class="bi bi-github" style="font-size: 1.5rem;"></i>
                            </a>
                            <a href="https://linkedin.com/in/seu_usuario" target="_blank" class="text-dark me-3 text-decoration-none">
                                <i class="bi bi-linkedin" style="font-size: 1.5rem;"></i>
                            </a>
                        </div>
                    </div>
                </div>
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
                    <button id="finalizarCompraBtn" class="btn btn-primary">Finalizar Pedido</button>
                </div>
            </div>
        </div>
    </div>


    <footer class="footer bg-light text-center py-4 mt-5">
        <p>&copy; 2025 Pizzaria. Todos os direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>