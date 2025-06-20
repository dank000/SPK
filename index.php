<?php
require 'db.php';

// ——————————————————————————————————————————————
// 1) Baca target kriteria dari GET (atau null jika belum disubmit)
$price_target = isset($_GET['price_target']) ? (float)$_GET['price_target'] : null;
$ram_target   = isset($_GET['ram_target'])   ? (float)$_GET['ram_target']   : null;
$cam_target   = isset($_GET['cam_target'])   ? (float)$_GET['cam_target']   : null;
$bat_target   = isset($_GET['bat_target'])   ? (float)$_GET['bat_target']   : null;

// cek apakah kita perlu SAW
$isSAW = $price_target!==null && $ram_target!==null && $cam_target!==null && $bat_target!==null;

// 2) Bobot tetap
$w_price   = 0.4;  // cost
$w_ram     = 0.3;  // benefit
$w_camera  = 0.2;  // benefit
$w_battery = 0.1;  // benefit

// ——————————————————————————————————————————————
// 3) Ambil data produk
$res = $conn->query("
  SELECT id,nama,harga,ram,kamera,baterai,image
  FROM products
");
$products = [];
while ($r = $res->fetch_assoc()) {
  $products[] = [
    'id'      => (int)$r['id'],
    'name'    => $r['nama'],
    'price'   => (int)$r['harga'],
    'ram'     => (int)$r['ram'],
    'camera'  => (int)$r['kamera'],
    'battery' => (int)$r['baterai'],
    'image'   => $r['image'],
  ];
}

// ——————————————————————————————————————————————
// 4) Jika SAW: hitung normalisasi terhadap target & skor
if ($isSAW) {
  foreach ($products as &$p) {
    // cost: semakin kecilHarga bagus
    $n_price   = $price_target / $p['price'];
    // benefit: semakin besar RAM,kamera,baterai bagus
    $n_ram     = $p['ram']     / $ram_target;
    $n_camera  = $p['camera']  / $cam_target;
    $n_battery = $p['battery'] / $bat_target;
    // skor total
    $p['score'] =
      $n_price   * $w_price +
      $n_ram     * $w_ram +
      $n_camera  * $w_camera +
      $n_battery * $w_battery;
  }
  unset($p);
  // urutkan descending
  usort($products, fn($a,$b)=> $b['score'] <=> $a['score']);
}

// ——————————————————————————————————————————————
// 5) Hitung badge keranjang
$badge = $conn
  ->query("SELECT COALESCE(SUM(quantity),0) AS total FROM cart")
  ->fetch_assoc()['total'];
?>
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
  <!-- Inline CSS untuk form rekomendasi & skor -->
  <style>
    #reco-form {
      max-width:400px; margin:1rem auto; padding:1rem; background:var(--mainGrey2);
      border-radius:8px; display:none;
    }
    #reco-form label {
      display: block;
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
    }
    #reco-form input {
      width: 60px;
      margin-left: 0.5rem;
    }
    .product-score {
      font-size: 0.8rem;
      color: var(--primaryColor);
      margin-top: 0.25rem;
    }

    /* ===== Styling Form Rekomendasi SPK (SAW) ===== */
#target-form {
  max-width: 600px;
  margin: 2rem auto;
  padding: 2rem;
  background: var(--mainGrey2);
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
#target-form form {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  align-items: end;
}
#target-form .form-group {
  display: flex;
  flex-direction: column;
}
#target-form .form-group label {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--mainBlack);
  margin-bottom: 0.5rem;
}
#target-form .form-group input {
  padding: 0.5rem;
  font-size: 1rem;
  border: 1px solid var(--mainGrey);
  border-radius: 4px;
  transition: var(--mainTransition);
}
#target-form .form-group input:focus {
  outline: none;
  border-color: var(--mainTeal);
  box-shadow: 0 0 0 2px rgba(118, 171, 174, 0.3);
}
#target-form .btn-apply {
  grid-column: 1 / -1;
  padding: 0.75rem;
  font-size: 1rem;
  text-transform: uppercase;
  background: var(--mainTeal);
  color: var(--mainWhite);
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: var(--mainTransition);
}
#target-form .btn-apply:hover {
  filter: brightness(0. Nine);
}

  </style>
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
           <i class="fas fa-shopping-cart nav-icon"></i>
        <?php 
          $badge = $conn
            ->query("SELECT COALESCE(SUM(quantity),0) AS total FROM cart")
            ->fetch_assoc()['total'];
        ?>
        <span class="cart-items"><?= $badge ?></span>
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

   <!-- Form Target SAW -->
  <section id="target-form">
  <form method="get" action="index.php">
    <!-- Harga Maksimal (hingga 9 digit) -->
    <div class="form-group">
      <label for="price_target">Harga maksimal (Rp):</label>
      <input
        type="text"
        id="price_target"
        name="price_target"
        required
        placeholder="Contoh: 100.000.000"
        inputmode="numeric"
        maxlength="11"                   
        value="<?= htmlspecialchars(isset($price_target) 
                    ? preg_replace('/\B(?=(\d{3})+(?!\d))/','.', $price_target) 
                    : '') ?>"
        oninput="
          // 1) Hapus semua non-digit
          let raw = this.value.replace(/\D/g, '');
          // 2) Potong jadi maksimal 9 digit
          raw = raw.slice(0, 9);
          // 3) Format dengan titik sebagai pemisah ribuan
          this.value = raw.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        "
      />
    </div>

    <!-- RAM minimal -->
    <div class="form-group">
      <label for="ram_target">RAM minimal (GB):</label>
      <input
        type="number"
        id="ram_target"
        name="ram_target"
        required
        placeholder="Contoh: 8"
        value="<?= htmlspecialchars($ram_target ?? '') ?>"
      />
    </div>

    <!-- Kamera minimal -->
    <div class="form-group">
      <label for="cam_target">Kamera minimal (MP):</label>
      <input
        type="number"
        id="cam_target"
        name="cam_target"
        required
        placeholder="Contoh: 12"
        value="<?= htmlspecialchars($cam_target ?? '') ?>"
      />
    </div>

    <!-- Baterai minimal -->
    <div class="form-group">
      <label for="bat_target">Baterai minimal (mAh):</label>
      <input
        type="number"
        id="bat_target"
        name="bat_target"
        required
        placeholder="Contoh: 5000"
        value="<?= htmlspecialchars($bat_target ?? '') ?>"
      />
    </div>

    <button type="submit" class="btn-apply">Terapkan Rekomendasi</button>
  </form>
</section>




  <!-- Daftar Produk -->
  <section class="products">
    <div class="section-title"><h2>Produk HP</h2></div>
    <div class="products-center" id="product-container"></div>
  </section>

  <!-- Modal Detail -->
  <div class="modal" id="product-modal" style="display:none;">
    <div class="modal-content">
      <span class="close-button" onclick="closeModal()">&times;</span>
      <img id="modal-image" class="product-img">
      <h3 id="modal-name"></h3>
      <h4 id="modal-price"></h4>
      <p id="modal-description"></p>
      <?php if ($isSAW): ?>
        <p style="font-size:.8rem; color:var(--primaryColor);">
          Skor Produk: <strong id="modal-score"></strong>
        </p>
      <?php endif; ?>
    </div>
  </div>

  <script>
     // ambil data produk dari PHP
  const products = <?= json_encode($products, JSON_UNESCAPED_SLASHES) ?>;
  const cont     = document.getElementById("product-container");

    // 1) Fungsi scroll ke section produk
    function scrollToProducts() {
      document.querySelector('.products').scrollIntoView({ behavior: 'smooth' });
    }     

 // 2) Fungsi AJAX add to cart
    function addToCart(pid) {
      fetch(`cart_action.php?action=add&product_id=${pid}`)
        .then(res => {
          if (res.ok) {
            // update badge di navbar tanpa reload
            const badgeEl = document.querySelector('.cart-items');
            badgeEl.innerText = parseInt(badgeEl.innerText) + 1;
          } else {
            console.error('Gagal menambah ke keranjang');
          }
        })
        .catch(err => console.error('Error:', err));
    }

    // 3) Render produk dengan tombol AJAX
    function render(list) {
      cont.innerHTML = "";
      list.forEach(p => {
        const div = document.createElement("div");
        div.className = "product-item";
        div.innerHTML = `
          <article class="product">
            <div class="img-container">
              <img src="${p.image}" alt="${p.name}" class="product-img">
              <button class="add-to-cart bag-btn"
                onclick="addToCart(${p.id})">
                <i class="fas fa-shopping-cart"></i>
              </button>
              <button class="desc-btn"
                onclick="openModal(
                  '${p.name}', '${p.price}',
                  'RAM: ${p.ram}GB, Kamera: ${p.camera}MP, Baterai: ${p.battery}mAh',
                  '${p.image}',
                  ${p.score ?? 'null'}
                )">
                Detail
              </button>
            </div>
            <h3>${p.name}</h3>
            <h4>Rp ${p.price.toLocaleString('id-ID')}</h4>
            ${p.score!==undefined 
              ? `<p style="font-size:.8rem;color:var(--primaryColor)">
                   Skor: ${p.score.toFixed(2)}
                 </p>`
              : ''}
          </article>`;
        cont.appendChild(div);
      });
    }

    function openModal(name,price,desc,img,score){
      document.getElementById("modal-name").innerText        = name;
      document.getElementById("modal-price").innerText       = "Rp "+parseInt(price).toLocaleString("id-ID");
      document.getElementById("modal-description").innerText = desc;
      document.getElementById("modal-image").src             = img;
      if (score!==null) document.getElementById("modal-score").innerText = score.toFixed(2);
      document.getElementById("product-modal").style.display = "flex";
    }
    function closeModal(){
      document.getElementById("product-modal").style.display = "none";
    }
    function scrollToForm(){
      document.getElementById("target-form").scrollIntoView({behavior:"smooth"});
    }

    document.addEventListener("DOMContentLoaded", ()=> render(products));
  </script>
  <script>
// fungsi format dengan titik sebagai pemisah ribuan
function formatThousand(str) {
  // hapus semua karakter bukan digit
  let digits = str.replace(/\D/g, "");
  // sisipkan titik setiap tiga digit dari kanan
  return digits.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// daftar ID input yang ingin diformat
const targetIds = ["price_target", "ram_target", "cam_target", "bat_target"];

targetIds.forEach(id => {
  const inp = document.getElementById(id);
  if (!inp) return;
  inp.addEventListener("input", (e) => {
    const start = inp.selectionStart;
    const oldLength = inp.value.length;

    // terapkan formatting
    inp.value = formatThousand(inp.value);

    // hitung selisih panjang untuk menjaga kursor
    const newLength = inp.value.length;
    const diff = newLength - oldLength;
    inp.setSelectionRange(start + diff, start + diff);
  });
});
</script>

</body>
</html>
