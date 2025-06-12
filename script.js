// Daftar produk handphone
const products = [
  {
    name: "Samsung Galaxy S23",
    price: 12500000,
    id: "1",
    image: "./images/product-1.png",
    bestseller: true,
    description:
      "Samsung Galaxy S23 dengan layar AMOLED 6.1 inci dan kamera 50MP.",
  },
  {
    name: "Xiaomi Redmi Note 12",
    price: 2999000,
    id: "2",
    image: "./images/product-2.png",
    bestseller: false,
    description: "Xiaomi dengan layar AMOLED dan baterai 5000mAh.",
  },
  {
    name: "iPhone 14",
    price: 15999000,
    id: "3",
    image: "./images/product-3.png",
    bestseller: true,
    description: "iPhone 14 dengan chip A15 Bionic dan kamera ganda 12MP.",
  },
  {
    name: "Realme C55",
    price: 2299000,
    id: "4",
    image: "./images/product-4.png",
    bestseller: false,
    description: "Realme C55 dengan RAM 6GB dan ROM 128GB.",
  },
];

const productContainer = document.getElementById("product-container");
const cartItems = document.querySelector(".cart-items");
const searchBox = document.getElementById("search-box");
const sortOptions = document.getElementById("sort-options");

let cart = JSON.parse(localStorage.getItem("cart")) || [];
let buttonsDOM = [];

function renderProducts(filteredProducts = products) {
  productContainer.innerHTML = "";

  filteredProducts.forEach((product) => {
    const productItem = document.createElement("div");
    productItem.classList.add("product-item");

    productItem.innerHTML = `
      <article class="product">
        <div class="img-container">
          <img src="${product.image}" alt="${product.name}" class="product-img">
          <button class="add-to-cart bag-btn" data-id="${product.id}">
            <i class="fas fa-shopping-cart"> Add to Bag </i>
          </button>
          <button class="desc-btn"
            style="position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.6); color: white; padding: 0.2rem 0.5rem; border: none; cursor: pointer;"
            onclick="openModal(
              \`${product.name}\`,
              \`${product.price}\`,
              \`${product.description.replace(/'/g, "\\'")}\`,
              \`${product.image}\`
            )"
          >Detail</button>
        </div>
        <h3>${product.name}</h3>
        <h4>Rp ${product.price.toLocaleString("id-ID")}</h4>
      </article>
    `;

    productContainer.appendChild(productItem);
  });

  getBagButtons();
}

function openModal(name, price, description, image) {
  document.getElementById("modal-name").innerText = name;
  document.getElementById("modal-price").innerText =
    "Rp " + parseInt(price).toLocaleString("id-ID");
  document.getElementById("modal-description").innerText = description;
  document.getElementById("modal-image").src = image;
  document.getElementById("product-modal").style.display = "flex";
}

function closeModal() {
  document.getElementById("product-modal").style.display = "none";
}

function applyFilters() {
  let filtered = [...products];
  const searchTerm = searchBox.value.toLowerCase();
  const sort = sortOptions.value;

  if (searchTerm) {
    filtered = filtered.filter((p) =>
      p.name.toLowerCase().includes(searchTerm)
    );
  }

  if (sort === "az") {
    filtered.sort((a, b) => a.name.localeCompare(b.name));
  } else if (sort === "za") {
    filtered.sort((a, b) => b.name.localeCompare(a.name));
  } else if (sort === "lowest") {
    filtered.sort((a, b) => a.price - b.price);
  } else if (sort === "highest") {
    filtered.sort((a, b) => b.price - a.price);
  } else if (sort === "bestseller") {
    filtered = filtered.filter((p) => p.bestseller);
  }

  renderProducts(filtered);
}

searchBox.addEventListener("input", applyFilters);
sortOptions.addEventListener("change", applyFilters);

function getBagButtons() {
  const buttons = [...document.querySelectorAll(".bag-btn")];
  buttonsDOM = buttons;

  buttons.forEach((button) => {
    const id = button.dataset.id;

    button.addEventListener("click", (event) => {
      const inCart = cart.find((item) => item.id === id);

      if (inCart) {
        cart = cart.filter((item) => item.id !== id);
        localStorage.setItem("cart", JSON.stringify(cart));
        setCartValues(cart);
        removeCartItem(id);
        event.target.innerText = "Add to Bag";
        event.target.disabled = false;
      } else {
        const product = products.find((prod) => prod.id === id);
        const cartItem = { ...product, quantity: 1 };

        cart.push(cartItem);
        localStorage.setItem("cart", JSON.stringify(cart));
        setCartValues(cart);
        addCartItem(cartItem);
        event.target.innerText = "In Cart";
        event.target.disabled = true;
      }
    });
  });
}

function removeCartItem(id) {
  const cartContent = document.querySelector(".cart-content");
  if (!cartContent) return;

  const itemToRemove = cartContent.querySelector(
    `.cart-item .remove-item[data-id="${id}"]`
  );
  if (itemToRemove) {
    itemToRemove.parentElement.parentElement.remove();
  }
}

function setCartValues(cart) {
  let totalItems = 0;

  cart.forEach((item) => {
    totalItems += item.quantity;
  });

  cartItems.innerText = totalItems;
}

function addCartItem(item) {
  const cartContent = document.querySelector(".cart-content");
  if (!cartContent) return;

  const div = document.createElement("div");
  div.classList.add("cart-item");
  div.innerHTML = `
    <img src="${item.image}" alt="${item.name}" />
    <div>
        <h4>${item.name}</h4>
        <h5>Rp ${item.price.toLocaleString("id-ID")}</h5>
        <span class="remove-item" data-id=${item.id}>remove</span>
    </div>
    <div>
        <i class="fas fa-chevron-up" data-id=${item.id}></i>
        <p class="item-amount">${item.quantity}</p>
        <i class="fas fa-chevron-down" data-id=${item.id}></i>
    </div>
  `;

  cartContent.appendChild(div);
}

function scrollToProducts() {
  document.querySelector(".products").scrollIntoView({
    behavior: "smooth",
  });
}

document.addEventListener("DOMContentLoaded", () => {
  renderProducts(); // tampilkan produk
  setCartValues(cart);
});

document.addEventListener("DOMContentLoaded", function () {
  const logo = document.getElementById("logo");

  if (logo) {
    logo.addEventListener("click", function () {
      window.location.href = "index.html";
    });
  }

  document.querySelector(".banner-btn")?.addEventListener("click", () => {
    document.querySelector(".products").scrollIntoView({
      behavior: "smooth",
    });
  });
});
