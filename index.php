<?php include 'db.php'; 
$result = $conn->query("SELECT * FROM products");?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Phone Store</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://kit.fontawesome.com/f386dbcdd1.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="shortcut icon" href="images/cart_icon.png" type="image/png" />
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="navbar-center">
      <span class="nav-icon">
        <img src="images/logo.png" alt="Logo" class="logo-img" id="logo" />
      </span>
      <span class="navbar-title">Phone Store</span>
      <div style="display: flex; align-items: center; gap: 1rem;">
    <!-- Tombol Login -->
    <a href="login.php" class="nav-icon" title="Login">
      <i class="fas fa-user-circle"></i>
    </a>
      <div class="cart-btn">
        <a href="cart.php">
          <span class="nav-icon">
            <i class="fas fa-cart-plus" style="color: #76abae"></i>
          </span>
          <div class="cart-items">0</div>
        </a>
      </div>
    </div>
  </nav>  

<!--hero-->
  <header class="hero">
    <div class="banner">
      <h1 class="banner-title">Phone Collection</h1>
      <button class="banner-btn" onclick="scrollToProducts()">Beli Sekarang</button>
    </div>
  </header>
  <!--end of hero-->

    </div>
  </div>

 <!-- Filter: Search & Sort -->
  <section class="filters" style="padding:2rem 0; text-align:center;">
    <input 
      type="text" 
      id="search-box" 
      placeholder="Cari produk..." 
      style="padding:0.5rem; width:200px;"
    />
    <select id="sort-options" style="padding:0.5rem; margin-left:1rem;">
      <option value="">— Urutkan —</option>
      <option value="az">Nama A–Z</option>
      <option value="za">Nama Z–A</option>
      <option value="lowest">Harga Terendah</option>
      <option value="highest">Harga Tertinggi</option>
      <option value="bestseller">Bestseller</option>
    </select>
  </section>

  <!-- Daftar Produk -->
  <section class="products">
    <div class="section-title"><h2>Produk HP</h2></div>
    <div class="products-center" id="product-container">
      <!-- dirender oleh inline JS -->
    </div>
  </section>

  <!-- Modal Detail Produk -->
  <div class="modal" id="product-modal" style="display:none;">
    <div class="modal-content">
      <span class="close-button" onclick="closeModal()">&times;</span>
      <img id="modal-image" src="" alt="" class="product-img" style="max-width:100%; margin-bottom:1rem;" />
      <h3 id="modal-name"></h3>
      <h4 id="modal-price"></h4>
      <p id="modal-description"></p>
    </div>
  </div>

  <!-- Slide-in Cart -->
  <div class="cart">
    <h2>Keranjang Belanja</h2>
    <div class="cart-content"></div>
    <button class="clear-cart-btn">Bersihkan Keranjang</button>
  </div>

  <!-- Inline JS: data + fungsi cart & UI -->
  <script>
    // 1) Data produk dari PHP
    const products = <?php
      $res = $conn->query("
        SELECT id, nama, harga, ram, kamera, baterai, image 
        FROM products
      ");
      $list = [];
      while ($r = $res->fetch_assoc()) {
        $desc = "RAM: {$r['ram']}GB, Kamera: {$r['kamera']}MP, Baterai: {$r['baterai']}mAh";
        // pastikan path image benar (folder 'images' di root)
        $path = $r['image'];
        $list[] = [
          'id'          => (string)$r['id'],
          'name'        => $r['nama'],
          'price'       => (int)$r['harga'],
          'description' => $desc,
          'image'       => $path,
          'bestseller'  => false
        ];
      }
      echo json_encode($list);
    ?>;

    // 2) Elemen-elemen DOM
    const productContainer = document.getElementById("product-container");
    const cartContent       = document.querySelector(".cart-content");
    const cartItemsCount    = document.querySelector(".cart-items");
    const clearCartBtn      = document.querySelector(".clear-cart-btn");
    const searchBox         = document.getElementById("search-box");
    const sortOptions       = document.getElementById("sort-options");

    // 3) State cart
    let cart = [];

    // 4) Render product list
    function renderProducts(list = products) {
      productContainer.innerHTML = "";
      list.forEach(p => {
        const div = document.createElement("div");
        div.className = "product-item";
        div.innerHTML = `
          <article class="product">
            <div class="img-container">
              <img src="${p.image}" alt="${p.name}" class="product-img">
              <button class="add-to-cart bag-btn" data-id="${p.id}">
                <i class="fas fa-shopping-cart"></i> Add to Bag
              </button>
              <button class="desc-btn"
                style="position:absolute; top:10px; left:10px; background:rgba(0,0,0,0.6); color:#fff; padding:0.2rem 0.5rem; border:none; cursor:pointer;"
                data-id="${p.id}"
              >Detail</button>
            </div>
            <h3>${p.name}</h3>
            <h4>Rp ${p.price.toLocaleString("id-ID")}</h4>
          </article>
        `;
        productContainer.appendChild(div);
      });
      attachProductButtons();
    }

    // 5) Attach tombol Add & Detail
    function attachProductButtons() {
      document.querySelectorAll(".add-to-cart").forEach(btn => {
        btn.onclick = () => {
          const id = btn.dataset.id;
          addToCart(id);
        };
      });
      document.querySelectorAll(".desc-btn").forEach(btn => {
        btn.onclick = () => {
          const id = btn.dataset.id;
          const p = products.find(x => x.id === id);
          openModal(p);
        };
      });
    }

    // 6) Modal detail
    function openModal(p) {
      document.getElementById("modal-image").src = p.image;
      document.getElementById("modal-name").innerText = p.name;
      document.getElementById("modal-price").innerText = "Rp " + p.price.toLocaleString("id-ID");
      document.getElementById("modal-description").innerText = p.description;
      document.getElementById("product-modal").style.display = "flex";
    }
    function closeModal() {
      document.getElementById("product-modal").style.display = "none";
    }

    // 7) Filter & Sort
    function applyFilters() {
      let tmp = [...products];
      const term = searchBox.value.toLowerCase();
      if (term) tmp = tmp.filter(p => p.name.toLowerCase().includes(term));
      const sort = sortOptions.value;
      if (sort === "az")     tmp.sort((a,b)=>a.name.localeCompare(b.name));
      else if (sort === "za") tmp.sort((a,b)=>b.name.localeCompare(a.name));
      else if (sort === "lowest") tmp.sort((a,b)=>a.price-b.price);
      else if (sort === "highest")tmp.sort((a,b)=>b.price-a.price);
      else if (sort === "bestseller") tmp = tmp.filter(p=>p.bestseller);
      renderProducts(tmp);
    }
    searchBox.oninput    = applyFilters;
    sortOptions.onchange  = applyFilters;

    // 8) Cart logic
    function addToCart(id) {
      const exists = cart.find(i=>i.id===id);
      if (exists) {
        exists.quantity++;
      } else {
        const p = products.find(x=>x.id===id);
        cart.push({ ...p, quantity:1 });
      }
      updateCartUI();
    }

    function removeFromCart(id) {
      cart = cart.filter(i=>i.id!==id);
      updateCartUI();
    }

    function clearCart() {
      cart = [];
      updateCartUI();
    }
    clearCartBtn.onclick = clearCart;

    // 9) Update UI cart
    function updateCartUI() {
      cartContent.innerHTML = "";
      let totalItems = 0;
      cart.forEach(item => {
        totalItems += item.quantity;
        const div = document.createElement("div");
        div.className = "cart-item";
        div.innerHTML = `
          <img src="${item.image}" alt="${item.name}">
          <div>
            <h4>${item.name}</h4>
            <h5>Rp ${item.price.toLocaleString("id-ID")}</h5>
            <span class="remove-item" data-id="${item.id}">hapus</span>
          </div>
          <div>
            <p class="item-amount">${item.quantity}</p>
          </div>
        `;
        cartContent.appendChild(div);
      });
      cartItemsCount.innerText = totalItems;
      // attach remove buttons
      document.querySelectorAll(".remove-item").forEach(btn => {
        btn.onclick = ()=> removeFromCart(btn.dataset.id);
      });
    }

    // 10) Scroll from banner
    document.querySelector(".banner-btn").onclick = ()=>
      document.querySelector(".products").scrollIntoView({behavior:"smooth"});

    // 11) Init
    document.addEventListener("DOMContentLoaded", () => {
      renderProducts();
      updateCartUI();
    });
  </script>

</body>
</html>
