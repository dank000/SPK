<?php
require 'db.php';

// hitung total item untuk badge di navbar
$totalItemsRow = $conn
  ->query("SELECT COALESCE(SUM(quantity),0) AS total FROM cart")
  ->fetch_assoc();
$totalItems = $totalItemsRow['total'];

// ambil data keranjang
$sql = "
  SELECT
    c.product_id,
    c.quantity,
    p.nama   AS nama,
    p.harga  AS harga,
    p.image  AS image
  FROM cart c
  JOIN products p ON c.product_id = p.id
";
$res = $conn->query($sql);

// hitung total harga
$totalPrice = 0;
$items = [];
if ($res->num_rows) {
  while ($r = $res->fetch_assoc()) {
    $subtotal = $r['harga'] * $r['quantity'];
    $totalPrice += $subtotal;
    $items[] = $r;
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Keranjang Belanja</title>
  <link rel="stylesheet" href="css/styles.css" />
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="navbar-center">
      <h1 class="navbar-title">Keranjang Anda</h1>
      <div class="cart-btn">
        <i class="fas fa-shopping-cart nav-icon"></i>
        <span class="cart-items"><?= $totalItems ?></span>
      </div>
    </div>
  </nav>

  <!-- Section Keranjang -->
  <section class="products">
    <div class="section-title">
      <h2>Keranjang Belanja</h2>
    </div>

    <?php if (empty($items)): ?>
      <p style="text-align:center; padding:2rem 0;">
        Keranjang Anda masih kosong. <a href="index.php">Lanjut Belanja</a>
      </p>
    <?php else: ?>

      <!-- Daftar item keranjang -->
      <div 
        class="products-center" 
        style="display:grid; grid-template-columns:1fr; max-width:1170px; margin:0 auto; gap:2rem;"
      >
        <?php foreach ($items as $item): ?>
          <div class="cart-item">
            <img 
              src="<?= htmlspecialchars($item['image']) ?>" 
              alt="<?= htmlspecialchars($item['nama']) ?>" 
            />
            <div>
              <h4><?= htmlspecialchars($item['nama']) ?></h4>
              <h5>Rp <?= number_format($item['harga'], 0, ',', '.') ?></h5>
              <p>Jumlah: <?= $item['quantity'] ?></p>
            </div>
            <div>
              <form method="post" action="cart_action.php">
                <input type="hidden" name="action"      value="remove">
                <input type="hidden" name="product_id"  value="<?= $item['product_id'] ?>">
                <button type="submit" class="remove-item">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Footer: total & tombol aksi -->
      <div class="cart-footer" style="padding-top:2rem;">
        <h3>Total Belanja: Rp <?= number_format($totalPrice, 0, ',', '.') ?></h3>
        <div style="display:flex; justify-content:center; gap:1rem; flex-wrap:wrap; margin-top:1rem;">
          <form method="post" action="cart_action.php">
            <input type="hidden" name="action" value="clear">
            <button type="submit" class="clear-cart-btn">Kosongkan Keranjang</button>
          </form>
          <a href="index.php" class="banner-btn">Lanjut Belanja</a>
        </div>
      </div>

    <?php endif; ?>
  </section>
</body>
</html>
