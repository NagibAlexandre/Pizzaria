let carrinho = JSON.parse(localStorage.getItem("carrinho")) || []; 
let produtoAtual = null;

document.addEventListener('DOMContentLoaded', function () {
  atualizarContadorCarrinho();
  verificarLogin();
});

function verificarLogin() {
  if (!sessionStorage.getItem("usuarioLogado")) {
    const carrinhoBtn = document.querySelector("#carrinhoBtn");
    if (carrinhoBtn) {
      carrinhoBtn.classList.add("disabled");
      carrinhoBtn.setAttribute("title", "Você precisa estar logado para acessar o carrinho");
    }
  } else {
    const carrinhoBtn = document.querySelector("#carrinhoBtn");
    if (carrinhoBtn) {
      carrinhoBtn.classList.remove("disabled");
      carrinhoBtn.removeAttribute("title");
    }
  }
}

function abrirModal(produto) {
  produtoAtual = produto;
  document.getElementById("imagemTamanhoPizza").src = "/images/tamanhos/brotinho.jpg"; // valor inicial
  document.getElementById("nomePizzaModal").innerText = produto.nome;
  new bootstrap.Modal(document.getElementById("modalTamanho")).show();
}

function adicionarCarrinho() {
  const select = document.getElementById("tamanhoSelecionado");
  const tamanho = select.value;
  const preco = parseFloat(select.selectedOptions[0].dataset.preco);

  carrinho.push({
    nome: produtoAtual.nome,
    tamanho,
    preco
  });

  localStorage.setItem("carrinho", JSON.stringify(carrinho));

  atualizarContadorCarrinho();

  bootstrap.Modal.getInstance(document.getElementById("modalTamanho")).hide();
}

function abrirCarrinho() {
  if (!sessionStorage.getItem("usuarioLogado")) {
    alert("Você precisa estar logado para acessar o carrinho.");
    return;
  }

  const lista = document.getElementById("listaCarrinho");
  const totalEl = document.getElementById("totalCarrinho");
  lista.innerHTML = "";
  let total = 0;

  carrinho.forEach((item, index) => {
    total += item.preco;

    const li = document.createElement("li");
    li.className = "list-group-item d-flex justify-content-between align-items-center";

    li.innerHTML = `
      <div>
        ${item.nome} (${item.tamanho}) - R$ ${item.preco.toFixed(2)}
      </div>
      <button class="btn btn-danger btn-sm" onclick="removerItem(${index})">
        <i class="bi bi-x"></i>
      </button>
    `;

    lista.appendChild(li);
  });

  totalEl.innerText = total.toFixed(2);
  new bootstrap.Modal(document.getElementById("modalCarrinho")).show();
}

function atualizarContadorCarrinho() {
  document.getElementById("contadorCarrinho").innerText = carrinho.length;
}

function logout() {
  localStorage.removeItem("carrinho");

  sessionStorage.removeItem("usuarioLogado");

  window.location.href = "../../index.php";
}

document.addEventListener("DOMContentLoaded", function () {
  const finalizarBtn = document.getElementById("finalizarCompraBtn");
  if (finalizarBtn) {
    finalizarBtn.addEventListener("click", function () {
      if (carrinho.length === 0) {
        alert("Seu carrinho está vazio!");
        return;
      }

      window.location.href = "billing/confirmarCompra.php";
    });
  }

  const finalizarBtnIndex = document.getElementById("finalizarCompraBtnIndex");
  if (finalizarBtnIndex) {
    finalizarBtnIndex.addEventListener("click", function () {
      if (carrinho.length === 0) {
        alert("Seu carrinho está vazio!");
        return;
      }

      window.location.href = "/pages/billing/confirmarCompra.php";
    });
  }
});

function removerItem(index) {
  carrinho.splice(index, 1);
  localStorage.setItem("carrinho", JSON.stringify(carrinho));
  atualizarContadorCarrinho();

  if (carrinho.length === 0) {
    const modalEl = document.getElementById("modalCarrinho");
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) modal.hide();
  } else {
    abrirCarrinho();
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const selectTamanho = document.getElementById("tamanhoSelecionado");
  const imagem = document.getElementById("imagemTamanhoPizza");

  if (selectTamanho && imagem) {
    selectTamanho.addEventListener("change", function () {
      const path = window.location.pathname;

      const emSubpasta = path.includes("/pages");

      const basePath = emSubpasta ? "../images/tamanhos" : "/images/tamanhos";

      switch (this.value) {
        case "brotinho":
          imagem.src = `${basePath}/brotinho.jpg`;
          break;
        case "media":
          imagem.src = `${basePath}/media.jpg`;
          break;
        case "familia":
          imagem.src = `${basePath}/familia.jpg`;
          break;
        default:
          imagem.src = "";
      }
    });
  }

});
