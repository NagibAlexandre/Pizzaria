<?php
require_once '../../config.php';

$stmt = $pdo->prepare("SELECT endereco_entrega FROM recibos WHERE id_usuario = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([1]);
$endereco_usuario = $stmt->fetchColumn();

$endereco_restaurante = "Avenida Dom José Gaspar - Coração Eucarístico - Belo Horizonte - Minas Gerais - 30535-610";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Acompanhamento do Pedido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/styles/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet" />
    <style>
        #map {
            height: 400px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .main-container {
            max-width: 800px;
            width: 100%;
        }

        #status-pedido {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .btn-voltar {
            width: 90%;
            max-width: 90%;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="container text-center main-container">
            <h2 class="mb-4">Acompanhamento do Pedido</h2>
            <div id="status-box" class="alert alert-danger" role="alert">
                <strong id="status-pedido">O pedido está sendo preparado...</strong>
            </div>
            <p>Veja abaixo o trajeto estimado.</p>
            <div id="map" class="mb-4"></div>
            <a href="/index.php" class="btn btn-primary btn-voltar">Voltar ao Início</a>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const enderecoUsuario = <?php echo json_encode($endereco_usuario); ?>;
        const enderecoRestaurante = <?php echo json_encode($endereco_restaurante); ?>;

        const map = L.map('map').setView([-19.9191, -43.9386], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        async function marcarEnderecos() {
            const buscarCoords = async (endereco) => {
                const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(endereco)}`);
                const data = await res.json();
                return data.length > 0 ? [parseFloat(data[0].lat), parseFloat(data[0].lon)] : null;
            };

            const coordsRestaurante = await buscarCoords(enderecoRestaurante);
            const coordsUsuario = await buscarCoords(enderecoUsuario);

            const bounds = [];

            if (coordsRestaurante) {
                L.marker(coordsRestaurante).addTo(map).bindPopup("Restaurante").openPopup();
                bounds.push(coordsRestaurante);
            }

            if (coordsUsuario) {
                L.marker(coordsUsuario).addTo(map).bindPopup("Seu endereço");
                bounds.push(coordsUsuario);
            }

            if (bounds.length > 0) {
                map.fitBounds(bounds, {
                    padding: [50, 50]
                });
            }
        }

        marcarEnderecos();

        const statusBox = document.getElementById("status-box");
        const statusText = document.getElementById("status-pedido");

        const atualizarStatus = (texto, classe) => {
            statusText.textContent = texto;
            statusBox.className = `alert ${classe}`;
            statusText.style.color = window.getComputedStyle(statusBox).color;
        };

        setTimeout(() => {
            atualizarStatus("O pedido saiu para entrega!", "alert-warning");
        }, 5000);

        setTimeout(() => {
            atualizarStatus("O pedido chegou!", "alert-success");
        }, 10000);
    </script>
    <script src="/scripts/togglemode.js"></script>
</body>

</html>
