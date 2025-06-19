<?php
include 'koneksi.php';

// Ambil data produk dari database
$produk = mysqli_query($conn, "SELECT * FROM produk_hp");
$produk_arr = [];
while ($row = mysqli_fetch_assoc($produk)) {
  $produk_arr[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Phone Store</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://kit.fontawesome.com/f386dbcdd1.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/style.css" />
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

   <!-- Hero Section -->
  <header class="hero">
    <div class="banner">
      <h1 class="banner-title">Phone Collection</h1>
      <button class="banner-btn" onclick="scrollToProducts()">Beli Sekarang</button>
    </div>
  </header>
  
  <!-- Rekomendasi Form Popup -->
  <div id="popup-rekomendasi" class="modal" style="display:none"  >
    <div class="modal-content">
      <span class="close-button" onclick="document.getElementById('popup-rekomendasi').style.display='none'">&times;</span>
      <form method="post" action="proses_saw.php">
  <h3>Form Rekomendasi HP</h3>
  <label>Budget Maksimal (contoh: 3000000):</label><br>
  <input type="number" name="harga" required><br>
  <label>Minimal RAM (contoh: 4):</label><br>
  <input type="number" name="ram" required><br>
  <label>Minimal Kamera (MP):</label><br>
  <input type="number" name="kamera" required><br>
  <label>Minimal Baterai (mAh):</label><br>
  <input type="number" name="baterai" required><br><br>
  <button type="submit" class="banner-btn">Rekomendasikan HP</button>
</form>

    </div>
  </div>

  <!-- Produk -->
  <section class="products">
    <div class="section-title">
      <div class="filter-controls" style="text-align: center; margin-bottom: 2rem">
        <input type="text" id="search-box" placeholder="Cari handphone..." style="padding: 0.5rem; width: 200px; margin-right: 1rem" />
        <select id="sort-options" style="padding: 0.5rem">
          <option value="default">Urutkan</option>
          <option value="az">Nama A-Z</option>
          <option value="bestseller">Best Seller</option>
          <option value="lowest">Harga Terendah</option>
          <option value="highest">Harga Tertinggi</option>
        </select>
        <select id="brand-filter" style="padding: 0.5rem">
          <option value="">Semua Brand</option>
          <?php
          $brands = mysqli_query($conn, "SELECT DISTINCT brand FROM produk_hp");
          while ($b = mysqli_fetch_assoc($brands)) {
            echo "<option value='{$b['brand']}'>{$b['brand']}</option>";
          }
          ?>
        </select>
        <select id="kategori-filter" style="padding: 0.5rem">
          <option value="">Semua Kategori</option>
          <option value="gaming">Gaming</option>
          <option value="fotografi">Fotografi</option>
          <option value="budget">Budget</option>
        </select>
        </div>
        <button onclick="document.getElementById('popup-rekomendasi').style.display='flex'" class="banner-btn">
        Gunakan Rekomendasi SPK
        </button>
      <h2>produk kami</h2>
    </div>
    <div class="products-center" id="product-container">
  <?php foreach ($produk_arr as $produk) : ?>
    <article class="product">
      <div class="img-container">
        <img src="<?php echo htmlspecialchars($produk['image']); ?>" 
             alt="<?php echo htmlspecialchars($produk['nama']); ?>" 
             class="product-img"
             onerror="this.src='images/default.png';" />
        <button class="add-to-cart" data-id="<?php echo $produk['id']; ?>">
          <i class="fas fa-shopping-cart"></i> tambah
        </button>
      </div>
      <h3><?php echo htmlspecialchars($produk['nama']); ?></h3>
      <h4>Rp<?php echo number_format($produk['harga'], 0, ',', '.'); ?></h4>
    </article>
  <?php endforeach; ?>
</div>

  </section>

  <!-- <script>
    const products = <?php echo json_encode($produk_arr); ?>;
  </script> -->
  <script src="js/script.js"></script>

  <!-- Modal Detail Produk -->
  <!-- <div id="product-modal" class="modal" style="display: none">
    <div class="modal-content">
      <span class="close-button" onclick="closeModal()">&times;</span>
      <img id="modal-image" src="" alt="Product" style="max-width: 100%; height: auto" />
      <h2 id="modal-name"></h2>
      <p id="modal-price"></p>
      <p id="modal-description"></p>
    </div>
  </div> -->

  <script>
    function scrollToProducts() {
      document.querySelector(".products").scrollIntoView({
        behavior: "smooth"
      });
    }

    document.getElementById("logo").addEventListener("click", () => {
      window.location.href = "index.php";
    });
  </script>
</body>
</html>
