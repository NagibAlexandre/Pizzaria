<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Finalizar Pedido</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet" />
  <style>
    #map {
      height: 300px;
      margin-bottom: 1rem;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    #autocomplete-results {
      position: absolute;
      z-index: 1000;
      width: 100%;
      max-height: 200px;
      overflow-y: auto;
    }

    /* Botão de retorno */
    .btn-retorno {
      position: absolute;
      top: 20px;
      left: 20px;
      z-index: 10;
    }
  </style>
</head>

<body>
  <!-- Link para voltar ao Índice -->
  <a href="/index.php" class="btn btn-primary btn-retorno">Voltar ao Índice</a>
  
  <div class="container py-5 d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-12 col-md-8">
      <h2 class="mb-4 text-center">Finalizar Pedido</h2>

      <!-- Mapa -->
      <div id="map"></div>

      <!-- Formulário -->
      <form id="formFinalizar" action="checkout.php" method="POST">
        <input type="hidden" name="produtos" id="inputProdutos">
        <input type="hidden" name="total" id="inputTotal">

        <!-- Endereço com autocomplete -->
        <div class="mb-3 position-relative">
          <label for="endereco" class="form-label">Endereço de Entrega</label>
          <input type="text" class="form-control" id="endereco" name="endereco" autocomplete="off" placeholder="Exemplo: Dom josé gaspar" required>
          <ul id="autocomplete-results" class="list-group"></ul>
        </div>

        <!-- Forma de pagamento -->
        <div class="mb-3">
          <label for="forma_pagamento" class="form-label">Forma de Pagamento</label>
          <select name="forma_pagamento" id="forma_pagamento" class="form-select" required>
            <option value="cartao">Cartão de Crédito</option>
            <option value="dinheiro">Dinheiro</option>
          </select>
        </div>

        <!-- Total -->
        <div class="mb-4">
          <h4 id="resumoTotal">Total: R$ 0,00</h4>
        </div>

        <!-- Botão -->
        <button type="submit" class="btn btn-success w-100">Confirmar Pedido</button>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    // Inicializa mapa
    const map = L.map('map').setView([-19.9191, -43.9386], 13); // Centro BH

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const marker = L.marker([-19.9191, -43.9386]).addTo(map);

    // Autocomplete
    const input = document.getElementById("endereco");
    const list = document.getElementById("autocomplete-results");

    let debounceTimeout;

    input.addEventListener('input', () => {
      clearTimeout(debounceTimeout);

      const query = input.value.trim();
      if (query.length < 3) {
        list.innerHTML = "";
        return;
      }

      debounceTimeout = setTimeout(async () => {
        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1`);
        const data = await res.json();

        list.innerHTML = "";

        data.forEach(item => {
          const addr = item.address;
          const resumo = [
            `${addr.road || ""}${addr.house_number ? ", " + addr.house_number : ""}`,
            addr.suburb || "",
            addr.city || addr.town || addr.village || "",
            addr.state || "",
            addr.postcode || ""
          ].filter(Boolean).join(" - ");

          const li = document.createElement("li");
          li.className = "list-group-item list-group-item-action";
          li.textContent = resumo || item.display_name;
          li.onclick = () => {
            input.value = resumo || item.display_name;
            list.innerHTML = "";
            const lat = parseFloat(item.lat);
            const lon = parseFloat(item.lon);
            map.setView([lat, lon], 17);
            marker.setLatLng([lat, lon]);
          };
          list.appendChild(li);
        });
      }, 500);
    });
  </script>

  <script>
    // Carrinho e total
    document.addEventListener('DOMContentLoaded', () => {
      const dados = JSON.parse(localStorage.getItem("carrinho") || "[]");
      const inputProdutos = document.getElementById("inputProdutos");
      const inputTotal = document.getElementById("inputTotal");
      const resumoTotal = document.getElementById("resumoTotal");

      if (dados.length === 0) {
        resumoTotal.innerHTML = "<p>Seu carrinho está vazio.</p>";
        return;
      }

      let total = 0;
      dados.forEach(item => {
        total += item.preco;
      });

      inputProdutos.value = JSON.stringify(dados);
      inputTotal.value = total.toFixed(2);
      resumoTotal.innerHTML = `<h4>Total: R$ ${total.toFixed(2)}</h4>`;

      document.getElementById("formFinalizar").addEventListener("submit", () => {
        localStorage.removeItem("carrinho");
      });
    });
  </script>
  <script>
    const formaPagamento = document.getElementById("forma_pagamento");
    const form = document.getElementById("formFinalizar");

    formaPagamento.addEventListener("change", () => {
      if (formaPagamento.value === "dinheiro") {
        form.action = "dinheiroSuccess.php";
      } else {
        form.action = "checkout.php";
      }
    });
  </script>
  <script src="/scripts/togglemode.js"></script>
</body>

</html>
