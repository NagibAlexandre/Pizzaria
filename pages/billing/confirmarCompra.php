<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Finalizar Pedido</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">Resumo do Pedido</h2>

    <div id="resumoCarrinho" class="mb-4">
    </div>

    <form id="formFinalizar" action="checkout.php" method="POST">
      <input type="hidden" name="produtos" id="inputProdutos">
      <input type="hidden" name="total" id="inputTotal">

      <div class="mb-3">
        <label for="endereco" class="form-label">Endereço de Entrega</label>
        <input type="text" class="form-control" name="endereco" id="endereco" required>
      </div>

      <div class="mb-3">
        <label for="forma_pagamento" class="form-label">Forma de Pagamento</label>
        <select name="forma_pagamento" id="forma_pagamento" class="form-select" required>
          <option value="cartao">Cartão de Crédito</option>
          <option value="dinheiro">Dinheiro</option>
        </select>
      </div>

      <button type="submit" class="btn btn-success">Confirmar Pedido</button>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const dados = JSON.parse(localStorage.getItem("carrinho") || "[]");
      const resumo = document.getElementById("resumoCarrinho");
      const inputProdutos = document.getElementById("inputProdutos");
      const inputTotal = document.getElementById("inputTotal");

      if (dados.length === 0) {
        resumo.innerHTML = "<p>Seu carrinho está vazio.</p>";
        return;
      }

      let total = 0;
      let html = '<ul class="list-group mb-3">';
      dados.forEach(item => {
        total += item.preco;
        html += `
      <li class="list-group-item d-flex justify-content-between align-items-center">
        ${item.nome} (${item.tamanho})
        <span>R$ ${item.preco.toFixed(2)}</span>
      </li>`;
      });
      html += '</ul>';
      html += `<h4>Total: R$ ${total.toFixed(2)}</h4>`;
      resumo.innerHTML = html;

      inputProdutos.value = JSON.stringify(dados);
      inputTotal.value = total.toFixed(2);

      document.getElementById("formFinalizar").addEventListener("submit", () => {
        localStorage.removeItem("carrinho");
      });
    });
  </script>
</body>

</html>