<?php
require 'db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Keranjang Belanja – RevoCar’s</title>
  <link rel="stylesheet" href="styles.css" />
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

  <!-- Navbar Sederhana -->
  <nav class="navbar">
    <div class="navbar-center">
      <h1 class="navbar-title">RevoCar’s - SPK SAW</h1>
      <div class="cart-btn">
        <i class="fas fa-shopping-cart nav-icon"></i>
        <span class="cart-items">
          <?php
            $cnt = $conn->query("SELECT SUM(quantity) AS total FROM cart")
                        ->fetch_assoc()['total'] ?? 0;
            echo $cnt;
          ?>
        </span>
      </div>
    </div>
  </nav>

  <div class="cart">
    <h2>Keranjang Belanja</h2>
    <div class="cart-content">
      <?php
      // ambil semua item di cart
      $res = $conn->query("
        SELECT 
          c.id        AS cart_id,
          c.product_id,
          c.quantity,
          p.nama      AS nama,
          p.harga     AS harga,
          p.image     AS image
        FROM cart c
        JOIN products p ON c.product_id = p.id
      ");
      if ($res->num_rows > 0):
        while ($item = $res->fetch_assoc()):
      ?>
        <div class="cart-item">
          <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['nama']) ?>">
          <div>
            <h4><?= htmlspecialchars($item['nama']) ?></h4>
            <h5>Rp <?= number_format($item['harga'], 0, ',', '.') ?></h5>
            <p>Jumlah: <?= $item['quantity'] ?></p>
            <form action="cart_action.php" method="post" style="display:inline;">
              <input type="hidden" name="action" value="remove">
              <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
              <button type="submit" class="remove-item">
                <i class="fas fa-trash"></i> Hapus
              </button>
            </form>
          </div>
        </div>
      <?php
        endwhile;
      else:
        echo '<p>Keranjang Anda masih kosong.</p>';
      endif;
      ?>
    </div>
    <div class="cart-footer">
      <a href="index.php" class="banner-btn">Kembali Belanja</a>
    </div>
  </div>

</body>
</html>
