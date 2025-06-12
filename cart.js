function renderCart() {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];
  const cartContainer = document.getElementById("cart-container");
  const totalPriceContainer = document.querySelector(".cart-total");
  const cartItems = document.querySelector(".cart-items"); // Elemen untuk menampilkan jumlah produk dalam keranjang

  cartContainer.innerHTML = ""; // Kosongkan keranjang sebelum render ulang
  let totalPrice = 0;

  cart.forEach((product) => {
    totalPrice += product.price * product.quantity;

    const cartItem = document.createElement("div");
    cartItem.classList.add("cart-item");
    cartItem.innerHTML = `
      <img src="${product.image || "images/default.png"}" alt="${product.name}">
      <div>
        <h4>${product.name}</h4>
        <h5>Rp ${product.price.toLocaleString("id-ID")}</h5>
        <span class="remove-item" data-id="${product.id}">remove</span>
      </div>
      <div>
        <button class="increase" data-id="${product.id}">
          <i class="fas fa-chevron-up"></i>
        </button>
        <p class="item-amount">${product.quantity}</p>
        <button class="decrease" data-id="${product.id}">
          <i class="fas fa-chevron-down"></i>
        </button>
      </div>
    `;

    // Gambar fallback jika terjadi error
    cartItem.querySelector("img").addEventListener("error", function () {
      this.src = "images/default.png";
    });

    cartContainer.appendChild(cartItem);
  });

  // Update total harga
  totalPriceContainer.textContent = `Rp ${totalPrice.toLocaleString("id-ID")}`;
}

document.addEventListener("click", function (event) {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  // Menambah jumlah produk
  if (event.target.closest(".increase")) {
    const id = event.target.closest(".increase").getAttribute("data-id");
    const product = cart.find((item) => item.id === id);

    if (product) {
      product.quantity += 1;
      localStorage.setItem("cart", JSON.stringify(cart));
      renderCart();
    }
  }

  // Mengurangi jumlah produk
  if (event.target.closest(".decrease")) {
    const id = event.target.closest(".decrease").getAttribute("data-id");
    const product = cart.find((item) => item.id === id);

    if (product) {
      if (product.quantity > 1) {
        product.quantity -= 1;
      } else {
        // Jika jumlah hanya 1, hapus produk
        cart = cart.filter((item) => item.id !== id);
      }

      localStorage.setItem("cart", JSON.stringify(cart));
      renderCart();
    }
  }

  // Menghapus produk
  if (event.target.classList.contains("remove-item")) {
    const id = event.target.getAttribute("data-id");
    cart = cart.filter((item) => item.id !== id);
    localStorage.setItem("cart", JSON.stringify(cart));
    renderCart();
  }

  // Menghapus semua produk
  if (event.target.id === "clear-cart") {
    localStorage.removeItem("cart");
    renderCart();
  }
});

// Render keranjang saat halaman dimuat
document.addEventListener("DOMContentLoaded", renderCart);

document.addEventListener("DOMContentLoaded", function () {
  const logo = document.getElementById("logo");

  if (logo) {
    logo.addEventListener("click", function () {
      window.location.href = "index.html"; // Navigasi ke halaman index
    });
  }
});
