<?php
require 'db.php';

// Tangani aksi POST: add / remove / clear / increment / decrement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['action'] ?? '';
    $pid = intval($_POST['product_id'] ?? 0);

    if ($act === 'add' && $pid) {
        $chk = $conn->query("SELECT * FROM cart WHERE product_id = $pid");
        if ($chk->num_rows) {
            $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE product_id = $pid");
        } else {
            $conn->query("INSERT INTO cart(product_id,quantity) VALUES($pid,1)");
        }
    }
    if ($act === 'increment' && $pid) {
        $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE product_id = $pid");
    }
    if ($act === 'decrement' && $pid) {
        // kurangi 1, lalu hapus yang <=0
        $conn->query("UPDATE cart SET quantity = quantity - 1 WHERE product_id = $pid");
        $conn->query("DELETE FROM cart WHERE product_id = $pid AND quantity <= 0");
    }
    if ($act === 'remove' && $pid) {
        $conn->query("DELETE FROM cart WHERE product_id = $pid");
    }
    if ($act === 'clear') {
        $conn->query("DELETE FROM cart");
    }
    header('Location: cart.php');
    exit;
}

// Ambil badge & isi cart
$badge = $conn
    ->query("SELECT COALESCE(SUM(quantity),0) AS total FROM cart")
    ->fetch_assoc()['total'];
$res = $conn->query("
    SELECT c.product_id, c.quantity, p.nama, p.harga, p.image
    FROM cart c
    JOIN products p ON c.product_id = p.id
");

$totalPrice = 0;
$items = [];
while ($r = $res->fetch_assoc()) {
    $r['subtotal'] = $r['harga'] * $r['quantity'];
    $totalPrice += $r['subtotal'];
    $items[] = $r;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Keranjang Belanja</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://kit.fontawesome.com/f386dbcdd1.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="shortcut icon" href="images/cart_icon.png" type="image/png" />
  <!-- Inline CSS khusus cart -->
  <style>
    /* pastikan .cart-btn relatif */
.cart-btn {
  position: relative;
}

    :root { --grey-light:#ececec; }
    .cart-container { max-width:1170px; margin:2rem auto; padding:0 1.5rem; }
    .cart-badge { position:absolute; top: -6px; right: -10px; background:var(--primaryColor); color:var(--mainWhite); width:2rem; height:2rem; line-height:2rem; text-align:center; border-radius:50%; font-size:0.9rem; }
        /* pastikan .cart-btn relatif */
.cart-btn {
  position: relative;
}

    .cart-items-list { margin-top:2rem; }
    .cart-item { display:flex; align-items:center; padding:1rem 0; border-bottom:1px solid var(--grey-light); }
    .cart-item img { width:80px; margin-right:1.5rem; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1); }
    .cart-details { flex:1; }
    .cart-details h4 { margin:0 0 .5rem; font-size:1rem; }
    .cart-details h5 { margin:0 0 .25rem; color:var(--primaryColor); font-size:0.9rem; }
    .quantity-controls { display:flex; align-items:center; gap:.5rem; }
    .quantity-controls form { margin:0; }
    .quantity-btn { background:transparent; border:none; cursor:pointer; font-size:1.2rem; color:var(--primaryColor); }
    .quantity-btn:disabled { opacity:0.5; cursor:not-allowed; }
    .item-amount { margin:0; font-size:0.9rem; }
    .cart-actions { margin-left:1.5rem; }
    .remove-item { background:transparent; border:none; cursor:pointer; font-size:1.2rem; color:var(--mainBlack); }
    .remove-item:hover { color:#f44336; }
    .cart-footer { text-align:center; padding:2rem 0; }
    .cart-footer h3 { margin-bottom:1rem; font-size:1.2rem; }
    .btn-group { display:flex; justify-content:center; gap:1rem; flex-wrap:wrap; }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="navbar-center">
      <span class="nav-icon">
        <img src="images/logo.png" alt="Logo" class="logo-img" id="logo" />
      </span>
      <span class="navbar-title">Keranjang Anda</span>
      <div style="display: flex; align-items: center; gap: 1rem;">
    <!-- Tombol Login -->
    <a href="login.php" class="nav-icon" title="Login">
      <i class="fas fa-user-circle"></i>
    </a>
      <div class="cart-btn">
        <i class="fas fa-shopping-cart nav-icon"></i>
        <span class="cart-badge"><?= $badge ?></span>
      </div>
    </div>
  </nav>  

  <!-- Kontainer Keranjang -->
  <div class="cart-container">
    <div class="cart-header">
    </div>

    <?php if (empty($items)): ?>
      <p style="text-align:center; padding:2rem 0;">
        Keranjang Anda masih kosong. <a href="index.php">Lanjut Belanja</a>
      </p>
    <?php else: ?>
      <div class="cart-items-list">
        <?php foreach ($items as $it): ?>
          <div class="cart-item">
            <img src="<?= htmlspecialchars($it['image']) ?>" alt="<?= htmlspecialchars($it['nama']) ?>">
            <div class="cart-details">
              <h4><?= htmlspecialchars($it['nama']) ?></h4>
              <h5>Rp <?= number_format($it['harga'],0,',','.') ?></h5>
              <div class="quantity-controls">
                <!-- Decrement -->
                <form method="post" action="cart.php">
                  <input type="hidden" name="action" value="decrement">
                  <input type="hidden" name="product_id" value="<?= $it['product_id'] ?>">
                  <button type="submit" class="quantity-btn" <?= $it['quantity']<=1?'disabled':'' ?>>
                    <i class="fas fa-chevron-down"></i>
                  </button>
                </form>
                <p class="item-amount"><?= $it['quantity'] ?></p>
                <!-- Increment -->
                <form method="post" action="cart.php">
                  <input type="hidden" name="action" value="increment">
                  <input type="hidden" name="product_id" value="<?= $it['product_id'] ?>">
                  <button type="submit" class="quantity-btn">
                    <i class="fas fa-chevron-up"></i>
                  </button>
                </form>
              </div>
            </div>
            <div class="cart-actions">
              <form method="post" action="cart.php">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="product_id" value="<?= $it['product_id'] ?>">
                <button type="submit" class="remove-item" title="Hapus Produk">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="cart-footer">
        <h3>Total Belanja: Rp <?= number_format($totalPrice,0,',','.') ?></h3>
        <div class="btn-group">
          <form method="post" action="cart.php">
            <input type="hidden" name="action" value="clear">
            <button type="submit" class="clear-cart-btn">Kosongkan Keranjang</button>
          </form>
          <a href="index.php" class="banner-btn">Lanjut Belanja</a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
