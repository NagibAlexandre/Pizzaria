<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: /pages/account/login.php");
    exit();
}

$id_usuario = $_SESSION['usuario']['id'];

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

function formatarTelefone($numero)
{
    $numero = preg_replace('/\D/', '', $numero);
    return preg_match('/^55(\d{2})(\d{5})(\d{4})$/', $numero, $m)
        ? "+55 {$m[1]} {$m[2]}-{$m[3]}"
        : $numero;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar'])) {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirmaSenha = $_POST['confirmaSenha'] ?? '';

    if ($nome && $email && $telefone && $endereco) {
        if (!empty($senha) && $senha !== $confirmaSenha) {
            $erro = "As senhas não coincidem.";
        } else {
            $query = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, endereco = ?";
            $params = [$nome, $email, $telefone, $endereco];

            if (!empty($senha)) {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $query .= ", senha = ?";
                $params[] = $senhaHash;
            }

            $query .= " WHERE id = ?";
            $params[] = $id_usuario;

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            $_SESSION['usuario']['nome'] = $nome;
            $sucesso = "Dados atualizados com sucesso!";
        }
    } else {
        $erro = "Todos os campos são obrigatórios.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .pointer {
            cursor: pointer;
        }

        .active-btn {
            font-weight: bold;
            text-decoration: underline;
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

                    <li class="nav-item">
                        <a class="nav-link position-relative <?php echo !isset($_SESSION['usuario']) ? 'disabled' : ''; ?>" href="#" onclick="<?php echo isset($_SESSION['usuario']) ? 'abrirCarrinho()' : ''; ?>">
                            <i class="bi bi-cart"></i> Carrinho
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="contadorCarrinho">0</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo !isset($_SESSION['usuario']) ? 'disabled' : ''; ?>" href="/pages/perfil.php">
                            <i class="bi bi-person"></i> Perfil
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

    <div class="container mt-4">
        <div class="row g-3">
            <div class="col-md-4 border border-secondary rounded p-3">
                <div class="text-center mb-4">
                    <img src="/images/teste.jpg" class="img-fluid rounded-circle" style="width: 150px;" alt="Avatar">
                    <h4 class="mt-2"><?= htmlspecialchars($usuario['nome']) ?></h4>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="mostrar('perfil')">Perfil</button>
                    <button class="btn btn-outline-primary" onclick="mostrar('dados')">Dados Pessoais</button>
                    <button class="btn btn-outline-primary" onclick="mostrar('pedidos')">Pedidos</button>
                </div>
            </div>

            <div class="col-md-8 border border-secondary rounded p-3">
                <div id="perfil" class="conteudo">
                    <h4>Bem-vindo(a), <?= htmlspecialchars($usuario['nome']) ?>!</h4>
                    <p>Veja seus dados, pedidos anteriores e atualize suas informações aqui.</p>
                </div>

                <!-- Dados Pessoais -->
                <div id="dados" class="conteudo d-none">
                    <h4>Editar Dados</h4>
                    <?php if (isset($erro)) echo "<div class='alert alert-danger'>$erro</div>"; ?>
                    <?php if (isset($sucesso)) echo "<div class='alert alert-success'>$sucesso</div>"; ?>

                    <form method="POST" id="formDados">
                        <div class="mb-3">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Telefone</label>
                            <input type="text" name="telefone" class="form-control" value="<?= formatarTelefone($usuario['telefone']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Endereço</label>
                            <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($usuario['endereco']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Nova Senha</label>
                            <input type="password" name="senha" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Confirmar Nova Senha</label>
                            <input type="password" name="confirmaSenha" class="form-control">
                        </div>
                        <button type="submit" name="atualizar" class="btn btn-success">Atualizar Dados</button>
                    </form>
                </div>

                <!-- Pedidos -->
                <div id="pedidos" class="conteudo d-none">
                    <h4>Histórico de Pedidos</h4>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM recibos WHERE id_usuario = ? ORDER BY data DESC");
                    $stmt->execute([$id_usuario]);
                    $recibos = $stmt->fetchAll();

                    foreach ($recibos as $recibo) {
                        echo "<div class='mb-4'>";
                        echo "<strong>Data:</strong> " . date('d/m/Y H:i', strtotime($recibo['data'])) . "<br>";
                        echo "<strong>Valor:</strong> R$" . number_format($recibo['valor'], 2, ',', '.') . " + R$9,90 (Taxa) <br>";
                        echo "<strong>Endereço de entrega:</strong> " . htmlspecialchars($recibo['endereco_entrega']) . "<br>";
                        echo "<strong>Pagamento:</strong> " . ucfirst($recibo['forma_pagamento']) . "<br>";
                        echo "<strong>Produtos:</strong><ul>";

                        $produtos = json_decode($recibo['produtos_comprados'], true);
                        foreach ($produtos as $item) {
                            if (isset($item['nome'])) {
                                $tamanho = ucfirst($item['tamanho']);
                                $preco = number_format($item['preco'], 2, ',', '.');
                                echo "<li>1x Pizza {$tamanho} de {$item['nome']} - R$ {$preco}</li>";
                            }
                        }
                        echo "</ul></div><hr>";
                    }

                    if (count($recibos) === 0) {
                        echo "<p>Nenhum pedido encontrado.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function mostrar(id) {
            document.querySelectorAll('.conteudo').forEach(el => el.classList.add('d-none'));
            document.getElementById(id).classList.remove('d-none');
        }
    </script>
    <script>
        document.getElementById('formDados').addEventListener('submit', function(event) {
            const senha = document.querySelector('input[name="senha"]').value;
            const confirmaSenha = document.querySelector('input[name="confirmaSenha"]').value;

            if (senha && senha !== confirmaSenha) {
                alert('As senhas não coincidem!');
                event.preventDefault();
            }
        });
    </script>
    <script src="/scripts/togglemode.js"></script>
</body>

</html>